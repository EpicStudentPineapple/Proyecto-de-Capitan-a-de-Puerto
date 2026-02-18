<?php

namespace App\Http\Controllers;

use App\Models\Pantalan;
use App\Models\Muelle;
use Illuminate\Http\Request;

class PantalanController extends Controller
{
    public function index()
    {
        $pantalans = Pantalan::with('muelle')->paginate(15);

        return view('pantalans.index', compact('pantalans'));
    }

    public function create()
    {
        $muelles = Muelle::all();

        return view('pantalans.create', compact('muelles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'muelle_id' => 'required|exists:muelles,id',
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:pantalans,codigo',
            'numero_amarre' => 'required|integer|min:1',
            'longitud_maxima' => 'required|numeric|min:0',
            'manga_maxima' => 'nullable|numeric|min:0',
            'calado_maximo' => 'required|numeric|min:0',
            'tipo_amarre' => 'required|in:finger,lateral,muerto,boya',
            'agua_disponible' => 'boolean',
            'electricidad_disponible' => 'boolean',
            'amperaje' => 'nullable|integer|min:0',
            'wifi_disponible' => 'boolean',
            'disponible' => 'boolean',
            'precio_dia' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $validated['agua_disponible'] = $request->has('agua_disponible');
        $validated['electricidad_disponible'] = $request->has('electricidad_disponible');
        $validated['wifi_disponible'] = $request->has('wifi_disponible');
        $validated['disponible'] = $request->has('disponible');

        $pantalan = Pantalan::create($validated);

        return redirect()->route('pantalans.show', $pantalan->id)
            ->with('success', 'Pantalán creado exitosamente');
    }

    public function show($id)
    {
        $pantalan = Pantalan::with('muelle')->findOrFail($id);

        return view('pantalans.show', compact('pantalan'));
    }

    public function edit($id)
    {
        $pantalan = Pantalan::findOrFail($id);
        $muelles = Muelle::all();

        return view('pantalans.edit', compact('pantalan', 'muelles'));
    }

    public function update(Request $request, $id)
    {
        $pantalan = Pantalan::findOrFail($id);

        $validated = $request->validate([
            'muelle_id' => 'required|exists:muelles,id',
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:pantalans,codigo,' . $id,
            'numero_amarre' => 'required|integer|min:1',
            'longitud_maxima' => 'required|numeric|min:0',
            'manga_maxima' => 'nullable|numeric|min:0',
            'calado_maximo' => 'required|numeric|min:0',
            'tipo_amarre' => 'required|in:finger,lateral,muerto,boya',
            'agua_disponible' => 'boolean',
            'electricidad_disponible' => 'boolean',
            'amperaje' => 'nullable|integer|min:0',
            'wifi_disponible' => 'boolean',
            'disponible' => 'boolean',
            'precio_dia' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $validated['agua_disponible'] = $request->has('agua_disponible');
        $validated['electricidad_disponible'] = $request->has('electricidad_disponible');
        $validated['wifi_disponible'] = $request->has('wifi_disponible');
        $validated['disponible'] = $request->has('disponible');

        $pantalan->update($validated);

        return redirect()->route('pantalans.show', $pantalan->id)
            ->with('success', 'Pantalán actualizado exitosamente');
    }

    public function destroy($id)
    {
        $pantalan = Pantalan::findOrFail($id);
        $pantalan->delete();

        return redirect()->route('pantalans.index')
            ->with('success', 'Pantalán eliminado exitosamente');
    }

    public function toggleDisponibilidad($id)
    {
        $pantalan = Pantalan::findOrFail($id);
        $pantalan->disponible = !$pantalan->disponible;
        $pantalan->save();

        return redirect()->back()
            ->with('success', 'Disponibilidad actualizada');
    }

    public function porMuelle($muelleId)
    {
        $muelle = Muelle::findOrFail($muelleId);
        $pantalans = $muelle->pantalans()->paginate(10);

        return view('pantalans.por-muelle', compact('muelle', 'pantalans'));
    }
}