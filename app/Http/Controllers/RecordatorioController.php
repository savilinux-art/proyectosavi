<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RecordatorioController extends Controller
{
    private function esAdmin(): bool
    {
        return Session::get('user_rol') === 'Administrador';
    }

    private function queryBase()
    {
        $q = Recordatorio::with(['usuario', 'creador']);
        if (!$this->esAdmin()) {
            $q->where('usuario_id', Session::get('user_usuario'));
        }
        return $q;
    }

    public function index(Request $request)
    {
        $query = $this->queryBase();

        if ($estatus = $request->input('estatus')) {
            if ($estatus === 'pendientes')      $query->where('estatus', 'pendiente');
            elseif ($estatus === 'enviados')    $query->where('estatus', 'enviado');
            elseif ($estatus === 'cancelados')  $query->where('estatus', 'cancelado');
        }

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($this->esAdmin() && ($usuarioId = $request->input('usuario_id'))) {
            $query->where('usuario_id', $usuarioId);
        }

        if ($desde = $request->input('desde')) {
            $query->where('fecha_hora_programada', '>=', $desde . ' 00:00:00');
        }
        if ($hasta = $request->input('hasta')) {
            $query->where('fecha_hora_programada', '<=', $hasta . ' 23:59:59');
        }

        $recordatorios = $query->orderBy('fecha_hora_programada', 'desc')->get();

        $kpiBase = $this->queryBase();
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
            ? Usuario::orderBy('usuario')->get()
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
        $data['created_by'] = Session::get('user_usuario');
        if (!$this->esAdmin()) {
            $data['usuario_id'] = Session::get('user_usuario');
        }
        $data['estatus']  = 'pendiente';
        $data['intentos'] = 0;

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

    private function usuariosDisponibles()
    {
        return $this->esAdmin()
            ? Usuario::orderBy('usuario')->get()
            : Usuario::where('usuario', Session::get('user_usuario'))->get();
    }

    private function validar(Request $request): array
    {
        $rules = [
            'titulo'                => 'required|string|max:200',
            'descripcion'           => 'nullable|string|max:2000',
            'tipo'                  => ['required', Rule::in(['unico', 'recurrente', 'certificado', 'sistema'])],
            'canal'                 => 'required|array|min:1',
            'canal.*'               => ['string', Rule::in(['telegram', 'email', 'web', 'whatsapp'])],
            'fecha_hora_programada' => 'required|date',
            'recurrencia'           => ['nullable', Rule::in(['una_vez', 'diaria', 'semanal', 'mensual', 'anual'])],
        ];

        if ($this->esAdmin()) {
            $rules['usuario_id'] = 'required|exists:usuarios,usuario';
        }

        if ($request->input('tipo') === 'certificado') {
            $rules['cert_nombre']             = 'required|string|max:200';
            $rules['cert_tipo']               = 'nullable|string|max:50';
            $rules['cert_emisor']             = 'nullable|string|max:200';
            $rules['cert_serie']              = 'nullable|string|max:200';
            $rules['cert_fecha_emision']      = 'nullable|date';
            $rules['cert_fecha_vencimiento']  = 'required|date';
            $rules['cert_link_renovacion']    = 'nullable|url|max:500';
        }

        $data = $request->validate($rules);

        if (($data['tipo'] ?? null) === 'recurrente' && empty($data['recurrencia'])) {
            $data['recurrencia'] = 'mensual';
        }
        if (($data['tipo'] ?? null) === 'unico') {
            $data['recurrencia'] = 'una_vez';
        }
        if (($data['tipo'] ?? null) === 'certificado' && empty($data['recurrencia'])) {
            $data['recurrencia'] = 'anual';
        }

        return $data;
    }
}
