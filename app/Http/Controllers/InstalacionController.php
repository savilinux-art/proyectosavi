<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instalacion;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Services\TelegramService;
class InstalacionController extends Controller
{
    // Mostrar todas las instalaciones, filtradas por rol metodo index
    public function index()
    {
        $rol = Session::get('user_rol');
        $user = Session::get('user_usuario');

        if ($rol == 'Instalador') {
            $instalaciones = Instalacion::whereHas('instaladores', function ($q) use ($user) {
                $q->where('instalador_usuario', $user);
            })->with(['proyecto', 'instaladores'])->get();
        } else {
             $instalaciones = Instalacion::with(['proyecto', 'instaladores', 'ubicaciones'])->get();
        }


        return view('instalaciones.index', compact('instalaciones'));
    }

    // Mostrar formulario para crear una nueva instalación metodo create
    public function create()
    {
        $proyectos = Venta::where('venta_ganada', true)->get();
        $instaladores = Usuario::where('rol', 'Instalador')->get();
        $estatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];
        return view('instalaciones.create', compact('proyectos', 'instaladores', 'estatus'));
    }

    // Guardar nueva instalación en la base de datos metodo store use App\Services\TelegramService;


public function store(Request $request, TelegramService $telegramService)
{
   

    // Convertir a array si llega como string
    if ($request->has('instaladores') && is_string($request->instaladores)) {
        $request->merge([
            'instaladores' => explode(',', $request->instaladores)
        ]);
    }

    $validated = $request->validate([
        'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
        'fecha_hora_inicio' => 'required|date',
        'estatus_instalacion' => 'required|exists:estatus,estatus',
        'instaladores' => 'required|array|min:1',
        'instaladores.*' => 'exists:usuarios,id',
    ]);

    $instalacion = Instalacion::create($validated);

    if ($request->has('instaladores')) {
        $instaladorUsuarios = Usuario::whereIn('id', $request->instaladores)->pluck('usuario')->toArray();
        $instalacion->instaladores()->sync($instaladorUsuarios);

        foreach ($instalacion->instaladores as $instalador) {
            $telegramService->notifyInstalacionAsignada($instalador, $instalacion);
        }
    }

    return redirect()->route('instalaciones.index')->with('success', 'Instalación creada y notificada.');
}

public function update(Request $request, Instalacion $instalacion, TelegramService $telegramService)
{
    $validated = $request->validate([
        'nombre_proyecto' => 'required|exists:ventas,nombre_proyecto',
        'fecha_hora_inicio' => 'required|date',
        'estatus_instalacion' => 'required|exists:estatus,estatus',
        'instaladores' => 'array|exists:usuarios,id',
    ]);

    $instalacion->update($validated);

    if ($request->has('instaladores')) {
        $instaladorUsuarios = Usuario::whereIn('id', $request->instaladores)->pluck('usuario')->toArray();
        $instalacion->instaladores()->sync($instaladorUsuarios);

        foreach ($instalacion->instaladores as $instalador) {
            $telegramService->notifyInstalacionAsignada($instalador, $instalacion);
        }
    }

    return redirect()->route('instalaciones.index')->with('success', 'Instalación actualizada.');
}
 
    // Mostrar detalles de una instalación metodo show
    public function show($id)
    {
        $instalacion = Instalacion::with(['proyecto', 'instaladores','ubicaciones.usuario'])->findOrFail($id);
        return view('instalaciones.show', compact('instalacion'));
    }

    // Mostrar formulario para editar una instalación metodo edit
    public function edit($id)
    {
        $instalacion = Instalacion::with('instaladores')->findOrFail($id);
        $proyectos = Venta::where('venta_ganada', true)->get();
        $instaladores = Usuario::where('rol', 'Instalador')->get();
        $estatus = ['preparacion', 'en_proceso', 'programacion', 'pruebas', 'entrega'];
        $instaladoresSeleccionados = $instalacion->instaladores->pluck('usuario')->toArray();

        return view('instalaciones.edit', compact('instalacion', 'proyectos', 'instaladores', 'estatus', 'instaladoresSeleccionados'));
    }

    
   

    // Eliminar una instalación metodo destroy
    public function destroy($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        $instalacion->delete();
        return redirect()->route('instalaciones.index')->with('success', 'Instalación eliminada');
    }
}