<?php

namespace App\Http\Controllers;

use App\Models\Geocerca;
use App\Models\Proyecto;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GeocercaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $geocercas = Geocerca::with(['proyecto', 'instalacion'])->get();
        return view('geocercas.index', compact('geocercas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proyectos = Proyecto::all();
        $instalaciones = Instalacion::all();
        return view('geocercas.create', compact('proyectos', 'instalaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:10',
            'color' => 'nullable|string|max:7',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'instalacion_id' => 'nullable|exists:instalaciones,id',
            'activa' => 'nullable|boolean',
        ]);

        Geocerca::create([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
            'color' => $request->color ?? '#FF0000',
            'proyecto_id' => $request->proyecto_id,
            'instalacion_id' => $request->instalacion_id,
            'activa' => $request->has('activa'),
        ]);

        return redirect()->route('geocercas.index')
            ->with('success', 'Geocerca creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $geocerca = Geocerca::with(['proyecto', 'instalacion'])->findOrFail($id);
        return view('geocercas.show', compact('geocerca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $geocerca = Geocerca::findOrFail($id);
        $proyectos = Proyecto::all();
        $instalaciones = Instalacion::all();
        return view('geocercas.edit', compact('geocerca', 'proyectos', 'instalaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:10',
            'color' => 'nullable|string|max:7',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'instalacion_id' => 'nullable|exists:instalaciones,id',
            'activa' => 'nullable|boolean',
        ]);

        $geocerca = Geocerca::findOrFail($id);
        $geocerca->update([
            'nombre' => $request->nombre,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
            'color' => $request->color ?? '#FF0000',
            'proyecto_id' => $request->proyecto_id,
            'instalacion_id' => $request->instalacion_id,
            'activa' => $request->has('activa'),
        ]);

        return redirect()->route('geocercas.index')
            ->with('success', 'Geocerca actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $geocerca = Geocerca::findOrFail($id);
        $geocerca->delete();

        return redirect()->route('geocercas.index')
            ->with('success', 'Geocerca eliminada correctamente.');
    }

    /**
     * Devuelve las geocercas activas en formato JSON (para el mapa)
     */
    public function activas()
    {
        $geocercas = Geocerca::where('activa', true)
            ->with(['proyecto', 'instalacion'])
            ->get();
        return response()->json($geocercas);
    }
}