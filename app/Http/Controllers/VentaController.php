<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VentaController extends Controller
{
    // Mostrar todas las ventas
    public function index()
    {
        $ventas = Venta::with('vendedor')->get();
        return view('ventas.index', compact('ventas'));
    }

    // Mostrar formulario para crear una nueva venta
    public function create()
    {
        $vendedores = Usuario::whereIn('rol', ['Ventas', 'Administrador'])->get();
        return view('ventas.create', compact('vendedores'));
    }

    // Guardar nueva venta en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_proyecto'           => 'required|string|unique:ventas',
            'moneda'                    => 'required|string',
            'requerimiento_venta'       => 'required|string',
            'fecha_hora_levantamiento'  => 'required|date',
            'venta_ganada'              => 'required|boolean',
            'estatus'                   => 'required|string',
            'cotizacion'                => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'levantamiento'             => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['cotizacion', 'levantamiento', '_token', '_method']);
            $data['vendedor'] = Session::get('user_usuario');

            $venta = Venta::create($data);
            $this->guardarDocumentos($venta, $request);
            $venta->save();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta creada');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Mostrar formulario para editar una venta
    public function edit($id)
    {
        $venta = Venta::findOrFail($id);

        $vendedores = Usuario::whereIn('rol', ['Ventas', 'Administrador'])->get();
        $estatus = ['prospeccion', 'levantamiento', 'cotizacion', 'cierre_venta'];

        return view('ventas.edit', compact('venta', 'vendedores', 'estatus'));
    }

    // Actualizar una venta
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_proyecto'     => 'required|string|unique:ventas,nombre_proyecto,' . $id . ',id',
            'moneda'              => 'required|string',
            'requerimiento_venta' => 'required|string',
            'vendedor'            => 'required|exists:usuarios,usuario',
            'estatus'             => 'required|string',
            'cotizacion'          => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'levantamiento'       => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $venta = Venta::findOrFail($id);

        $data = $request->except(['cotizacion', 'levantamiento', '_token', '_method']);
        $venta->update($data);

        $this->guardarDocumentos($venta, $request);
        $venta->save();

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
    }

    // Mostrar una venta específica
    public function show($id)
    {
        $venta = Venta::with(['proyecto', 'instalaciones'])->findOrFail($id);
        $instalaciones = $venta->instalaciones;

        return view('ventas.show', compact('venta', 'instalaciones'));
    }

    /**
     * Servir un documento (cotización o levantamiento).
     * Lee del disco (Storage::disk('local')); fallback al blob legacy si existe.
     * PDF → inline (ver en navegador), DOC/DOCX → attachment (descargar).
     */
    public function descargarDocumento($id, $tipo)
    {
        if (!in_array($tipo, ['cotizacion', 'levantamiento'], true)) {
            abort(404);
        }

        $venta = Venta::findOrFail($id);
        $path  = $venta->{"{$tipo}_path"};

        // Caso normal (Fase 2): archivo en disco
        if ($path && Storage::disk('local')->exists($path)) {
            $mime = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
            $ext  = pathinfo($path, PATHINFO_EXTENSION) ?: 'bin';

            $base     = preg_replace('/[^A-Za-z0-9_-]+/', '-', $venta->nombre_proyecto ?? 'venta');
            $filename = "{$tipo}-{$base}.{$ext}";

            $disposition = in_array($mime, [
                'application/pdf', 'image/png', 'image/jpeg', 'image/webp',
            ], true) ? 'inline' : 'attachment';

            return Storage::disk('local')->response($path, $filename, [
                'Content-Type'        => $mime,
                'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
                'X-Frame-Options'     => 'SAMEORIGIN',
            ]);
        }

        // Fallback transitorio: blob legacy válido (se elimina en Fase 3)
        $blob = $venta->{$tipo} ?? '';
        if (strlen($blob) > 200) {
            $mime = 'application/octet-stream';
            $ext  = 'bin';

            if (str_starts_with($blob, '%PDF-')) {
                $mime = 'application/pdf';
                $ext  = 'pdf';
            } elseif (str_starts_with($blob, "\xD0\xCF\x11\xE0")) {
                $mime = 'application/msword';
                $ext  = 'doc';
            } elseif (str_starts_with($blob, "PK\x03\x04")) {
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                $ext  = 'docx';
            }

            $base        = preg_replace('/[^A-Za-z0-9_-]+/', '-', $venta->nombre_proyecto ?? 'venta');
            $filename    = "{$tipo}-{$base}.{$ext}";
            $disposition = $ext === 'pdf' ? 'inline' : 'attachment';

            return response($blob, 200, [
                'Content-Type'        => $mime,
                'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
                'X-Frame-Options'     => 'SAMEORIGIN',
            ]);
        }

        abort(404, 'Documento no encontrado');
    }

    // Eliminar una venta y sus documentos en disco
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        if ($venta->instalaciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la venta porque tiene instalaciones asociadas.');
        }

        // Borrar archivos asociados antes de eliminar la fila
        foreach (['cotizacion', 'levantamiento'] as $tipo) {
            $path = $venta->{"{$tipo}_path"};
            if ($path && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
        }
        Storage::disk('local')->deleteDirectory("ventas/{$venta->id}");

        $venta->delete();

        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada exitosamente.');
    }

    /**
     * Guarda los archivos subidos (cotizacion / levantamiento) en storage/app/private/ventas/{id}/
     * Solo reemplaza si se subió un archivo nuevo. No toca el archivo anterior si no hay upload.
     */
    private function guardarDocumentos(Venta $venta, Request $request): void
    {
        foreach (['cotizacion', 'levantamiento'] as $tipo) {
            if (!$request->hasFile($tipo) || !$request->file($tipo)->isValid()) {
                continue;
            }

            $oldPath = $venta->{"{$tipo}_path"};
            if ($oldPath && Storage::disk('local')->exists($oldPath)) {
                Storage::disk('local')->delete($oldPath);
            }

            $file = $request->file($tipo);
            $ext  = strtolower($file->getClientOriginalExtension()) ?: 'bin';

            $venta->{"{$tipo}_path"} = $file->storeAs(
                "ventas/{$venta->id}",
                "{$tipo}.{$ext}",
                'local'
            );
        }
    }
}