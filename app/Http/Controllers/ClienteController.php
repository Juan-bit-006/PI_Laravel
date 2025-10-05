<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('q');

        $clientes = Cliente::when($busqueda, function($query, $busqueda) {
            return $query->where('nombre', 'like', "%$busqueda%")
                         ->orWhere('email', 'like', "%$busqueda%");
        })->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:clientes,email',
            'telefono' => 'nullable|string|max:15',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success','Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:clientes,email,'.$cliente->id,
            'telefono' => 'nullable|string|max:15',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success','Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success','Cliente eliminado correctamente.');
    }
}
