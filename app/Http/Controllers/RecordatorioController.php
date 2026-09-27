<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use App\Models\CertificadoHistorial;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RecordatorioController extends Controller
{
    private function esAdmin(): bool
    {
        return Session::get('user_rol') === 'Administrador';
    }

    private function userId(): int
    {
        return (int) Session::get('user_id');
    }

    private function queryBase()
    {
        $q = Recordatorio::with(['usuario', 'creador']);
        if (!$this->esAdmin()) {
            $q->where('usuario_id', $this->userId());
        }
        return $q;
    }

    public function index(Request $request)
    {
        $query = $this->queryBase();

        if ($estatus = $request->input('estatus')) {
            if (in_array($estatus, ['pendiente', 'enviado', 'cancelado', 'completado', 'error'])) {
                $query->where('estatus', $estatus);
            }
        }

        if ($tipo = $request->input('tipo')) {
            if (in_array($tipo, ['general', 'certificado', 'sistema'])) {
                $query->where('tipo', $tipo);
            }
        }

        if ($this->esAdmin() && ($uid = $request->input('usuario_id'))) {
            $query->where('usuario_id', $uid);
        }

        if ($desde = $request->input('desde')) {
            $query->where('fecha_hora_programada', '>=', $desde . ' 00:00:00');
        }
        if ($hasta = $request->input('hasta')) {
            $query->where('fecha_hora_programada', '<=', $hasta . ' 23:59:59');
        }

        $recordatorios = $query->orderBy('fecha_hora_programada', 'desc')->get();

        $kpiBase = Recordatorio::query();
        if (!$this->esAdmin()) {
            $kpiBase->where('usuario_id', $this->userId());
        }

        $pendientesHoy = (clone $kpiBase)->where('estatus', 'pendiente')
            ->whereDate('fecha_hora_programada', today())->count();
        $proximos7Dias = (clone $kpiBase)->where('estatus', 'pendiente')
            ->whereBetween('fecha_hora_programada', [now(), now()->addDays(7)])->count();
        $certificadosPorVencer = (clone $kpiBase)->where('tipo', 'certificado')
            ->whereNotNull('cert_fecha_vencimiento')
            ->whereBetween('cert_fecha_vencimiento', [today(), today()->addDays(30)])
            ->count();
        $total = (clone $kpiBase)->count();

        $usuarios = $this->esAdmin()
            ? Usuario::orderBy('nombre')->get()
            : collect();

        return view('recordatorios.index', compact(
            'recordatorios', 'usuarios',
            'pendientesHoy', 'proximos7Dias', 'certificadosPorVencer', 'total'
        ));
    }

    public function create()
    {
        $usuarios = $this->usuariosDisponibles();
        return view('recordatorios.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        $data['created_by'] = $this->userId();
        if (!$this->esAdmin()) {
            $data['usuario_id'] = $this->userId();
        }
        $data['estatus']  = 'pendiente';
        $data['intentos'] = 0;

        // Si es certificado y no hay avisos definidos, usar default
        if (($data['tipo'] ?? null) === 'certificado' && empty($data['cert_avisos_dias'])) {
            $data['cert_avisos_dias'] = [15, 7, 3, 1, 0];
        }

        Recordatorio::create($data);

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio creado');
    }

    public function edit($id)
    {
        $recordatorio = $this->queryBase()->findOrFail($id);
        $usuarios = $this->usuariosDisponibles();
        return view('recordatorios.edit', compact('recordatorio', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $recordatorio = $this->queryBase()->findOrFail($id);
        $data = $this->validar($request);
        if (!$this->esAdmin()) {
            unset($data['usuario_id']);
        }
        $recordatorio->update($data);

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio actualizado');
    }

    public function destroy($id)
    {
        $recordatorio = $this->queryBase()->findOrFail($id);
        $recordatorio->delete();
        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio eliminado');
    }

    public function cancelar($id)
    {
        $recordatorio = $this->queryBase()->findOrFail($id);
        if ($recordatorio->estatus !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar recordatorios pendientes.');
        }
        $recordatorio->update(['estatus' => 'cancelado']);
        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio cancelado');
    }

    public function renovar(Request $request, $id)
    {
        $recordatorio = $this->queryBase()->findOrFail($id);

        if (!$recordatorio->esCertificado()) {
            return back()->with('error', 'Este recordatorio no es un certificado.');
        }

        $data = $request->validate([
            'fecha_vencimiento_nueva' => 'required|date',
            'cert_link_renovacion'    => 'nullable|url|max:500',
            'notas'                   => 'nullable|string|max:1000',
        ]);

        $vencAnterior = $recordatorio->cert_fecha_vencimiento;
        $vencNueva    = Carbon::parse($data['fecha_vencimiento_nueva']);

        // 1. Registrar historial
        CertificadoHistorial::create([
            'recordatorio_id'            => $recordatorio->id,
            'usuario_id'                 => $this->userId(),
            'fecha_vencimiento_anterior' => $vencAnterior,
            'fecha_vencimiento_nueva'    => $vencNueva,
            'notas'                      => $data['notas'] ?? null,
        ]);

        // 2. Actualizar el recordatorio actual: certificado renovado
        $recordatorio->update([
            'cert_fecha_vencimiento' => $vencNueva,
            'cert_renovado_at'       => now(),
            'estatus'                => 'completado',
            'cert_link_renovacion'   => $data['cert_link_renovacion'] ?? $recordatorio->cert_link_renovacion,
        ]);

        // 3. Crear nuevo recordatorio clonado para el próximo ciclo
        //    El nuevo vencimiento es 1 año después (o según su periodicidad)
        $proxVencimiento = $vencNueva->copy()->addYear();
        $nuevo = $recordatorio->replicate([
            'cert_renovado_at', 'enviado_at', 'intentos', 'ultimo_error',
        ]);
        $nuevo->estatus                = 'pendiente';
        $nuevo->intentos               = 0;
        $nuevo->enviado_at             = null;
        $nuevo->ultimo_error           = null;
        $nuevo->cert_fecha_vencimiento = $proxVencimiento;
        $nuevo->cert_renovado_at       = null;
        $nuevo->fecha_hora_programada  = $proxVencimiento->copy()->subDays(15)->setTime(9, 0, 0);
        $nuevo->created_by             = $this->userId();
        $nuevo->save();

        return redirect()->route('recordatorios.index')
            ->with('success', "Certificado renovado. Nuevo ciclo programado para {$proxVencimiento->format('d/m/Y')}.");
    }

    private function usuariosDisponibles()
    {
        return $this->esAdmin()
            ? Usuario::orderBy('nombre')->get()
            : Usuario::where('id', $this->userId())->get();
    }

    private function validar(Request $request): array
    {
        $rules = [
            'titulo'                => 'required|string|max:200',
            'descripcion'           => 'nullable|string|max:2000',
            'tipo'                  => ['required', Rule::in(['general', 'certificado', 'sistema'])],
            'canal'                 => 'required|array|min:1',
            'canal.*'               => ['string', Rule::in(['telegram', 'email', 'web', 'whatsapp'])],
            'fecha_hora_programada' => 'required|date',
            'recurrencia'           => ['nullable', Rule::in(['una_vez', 'diario', 'semanal', 'mensual', 'personalizado'])],
        ];

        if ($this->esAdmin()) {
            $rules['usuario_id'] = 'required|exists:usuarios,id';
        }

        if ($request->input('tipo') === 'certificado') {
            $rules['cert_nombre']             = 'required|string|max:200';
            $rules['cert_tipo']               = ['nullable', Rule::in(['ssl', 'csd', 'dominio', 'otro'])];
            $rules['cert_emisor']             = 'nullable|string|max:200';
            $rules['cert_serie']              = 'nullable|string|max:200';
            $rules['cert_fecha_emision']      = 'nullable|date';
            $rules['cert_fecha_vencimiento']  = 'required|date';
            $rules['cert_link_renovacion']    = 'nullable|url|max:500';
        }

        $data = $request->validate($rules);

        if (($data['tipo'] ?? null) === 'general' && empty($data['recurrencia'])) {
            $data['recurrencia'] = 'una_vez';
        }
        if (($data['tipo'] ?? null) === 'certificado' && empty($data['recurrencia'])) {
            $data['recurrencia'] = 'personalizado';
        }

        return $data;
    }
}