<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('q');

        $reservas = Reserva::with(['cliente','servicio','estilista'])
            ->when($busqueda, function($query, $busqueda) {
                return $query->whereHas('cliente', function($q) use ($busqueda) {
                    $q->where('nombre', 'like', "%$busqueda%");
                });
            })->get();

        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $estilistas = User::where('role','estilista')->get();

        return view('reservas.create', compact('clientes','servicios','estilistas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada'
        ]);

        Reserva::create($request->all());

        return redirect()->route('reservas.index')->with('success','Reserva creada correctamente.');
    }

    public function edit(Reserva $reserva)
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $estilistas = User::where('role','estilista')->get();

        return view('reservas.edit', compact('reserva','clientes','servicios','estilistas'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada'
        ]);

        $reserva->update($request->all());

        return redirect()->route('reservas.index')->with('success','Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index')->with('success','Reserva eliminada correctamente.');
    }
}
