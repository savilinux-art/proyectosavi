<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ventas = Venta::all();
        return view('clientes.create', compact('ventas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rfc' => 'required|string|unique:clientes|max:13',
            'razon_social' => 'required|string|max:255',
            'nombre_proyecto' => 'nullable|string|exists:ventas,nombre_proyecto',
            'regimen_fiscal' => 'required|string|max:255',
            'codigo_postal' => 'required|integer|digits:5',
            'correo_electronico' => 'required|email|max:255',
            'constancia_situacion_fiscal' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            if ($request->hasFile('constancia_situacion_fiscal')) {
                $data['constancia_situacion_fiscal'] = file_get_contents($request->file('constancia_situacion_fiscal')->getRealPath());
            }

            Cliente::create($data);

            DB::commit();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $ventas = Venta::all();
        return view('clientes.edit', compact('cliente', 'ventas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rfc' => 'required|string|max:13|unique:clientes,rfc,' . $id,
            'razon_social' => 'required|string|max:255',
            'nombre_proyecto' => 'nullable|string|exists:ventas,nombre_proyecto',
            'regimen_fiscal' => 'required|string|max:255',
            'codigo_postal' => 'required|integer|digits:5',
            'correo_electronico' => 'required|email|max:255',
            'constancia_situacion_fiscal' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $cliente = Cliente::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('constancia_situacion_fiscal')) {
                $data['constancia_situacion_fiscal'] = file_get_contents($request->file('constancia_situacion_fiscal')->getRealPath());
            }

            $cliente->update($data);

            DB::commit();

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}