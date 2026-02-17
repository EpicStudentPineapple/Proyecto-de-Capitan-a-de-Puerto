<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Buque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::paginate(15);

        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:servicios,codigo',
            'tipo_servicio' => 'required|in:practicaje,remolque,amarre,suministro_combustible,suministro_agua,suministro_electrico,retirada_residuos,limpieza,reparaciones,aprovisionamiento,inspeccion_aduana,sanidad_portuaria,seguridad,otros',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'unidad_cobro' => 'required|in:fijo,por_hora,por_tonelada,por_metro,por_servicio',
            'disponible_24h' => 'boolean',
            'requiere_reserva' => 'boolean',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
            'proveedor' => 'nullable|string|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
        ]);

        $servicio = Servicio::create($validated);

        return redirect()->route('servicios.show', $servicio->id)
            ->with('success', 'Servicio creado exitosamente');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);

        if (Auth::user()->isAdmin()) {
            $servicio->load('buques');
        }
        else {
            $servicio->load(['buques' => function ($query) {
                $query->where('propietario_id', Auth::id());
            }]);
        }

        return view('servicios.show', compact('servicio'));
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);

        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:servicios,codigo,' . $id,
            'tipo_servicio' => 'required|in:practicaje,remolque,amarre,suministro_combustible,suministro_agua,suministro_electrico,retirada_residuos,limpieza,reparaciones,aprovisionamiento,inspeccion_aduana,sanidad_portuaria,seguridad,otros',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'unidad_cobro' => 'required|in:fijo,por_hora,por_tonelada,por_metro,por_servicio',
            'disponible_24h' => 'boolean',
            'requiere_reserva' => 'boolean',
            'tiempo_estimado_minutos' => 'nullable|integer|min:0',
            'proveedor' => 'nullable|string|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios.show', $servicio->id)
            ->with('success', 'Servicio actualizado exitosamente');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado exitosamente');
    }

    public function solicitar(Request $request)
    {
        $validated = $request->validate([
            'buque_id' => 'required|exists:buques,id',
            'servicio_id' => 'required|exists:servicios,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_solicitud' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $buque = Buque::findOrFail($request->buque_id);
        $servicio = Servicio::findOrFail($request->servicio_id);

        $precioTotal = $servicio->calcularPrecio($request->cantidad);

        $buque->servicios()->attach($servicio->id, [
            'fecha_solicitud' => $request->fecha_solicitud,
            'cantidad' => $request->cantidad,
            'precio_total' => $precioTotal,
            'estado' => 'solicitado',
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->back()
            ->with('success', 'Servicio solicitado exitosamente');
    }

    public function actualizarEstado(Request $request, $buqueId, $servicioId)
    {
        $validated = $request->validate([
            'estado' => 'required|in:solicitado,en_proceso,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        $buque = Buque::findOrFail($buqueId);

        $buque->servicios()->updateExistingPivot($servicioId, [
            'estado' => $request->estado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->back()
            ->with('success', 'Estado del servicio actualizado');
    }
}