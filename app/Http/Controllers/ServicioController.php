<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('q');

        $servicios = Servicio::when($busqueda, function($query, $busqueda) {
            return $query->where('nombre', 'like', "%$busqueda%");
        })->get();

        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'duracion' => 'required|integer'
        ]);

        Servicio::create($request->all());

        return redirect()->route('servicios.index')->with('success','Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'duracion' => 'required|integer'
        ]);

        $servicio->update($request->all());

        return redirect()->route('servicios.index')->with('success','Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success','Servicio eliminado correctamente.');
    }
}
