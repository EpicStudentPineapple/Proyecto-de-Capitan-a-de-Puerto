<?php

namespace App\Http\Controllers;

use App\Models\Muelle;
use Illuminate\Http\Request;

class MuelleController extends Controller
{
    public function index()
    {
        $muelles = Muelle::with('buqueActual')->paginate(10);

        return view('muelles.index', compact('muelles'));
    }

    public function create()
    {
        return view('muelles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:muelles,codigo',
            'longitud' => 'required|numeric|min:0',
            'calado_maximo' => 'required|numeric|min:0',
            'capacidad_toneladas' => 'required|integer|min:0',
            'tipo_muelle' => 'required|in:contenedores,carga_general,graneles,pesquero,pasajeros,ro-ro,hidrocarburos,deportivo,servicios',
            'disponible' => 'boolean',
            'grua_disponible' => 'boolean',
            'energia_tierra' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['disponible'] = $request->has('disponible');
        $validated['grua_disponible'] = $request->has('grua_disponible');
        $validated['energia_tierra'] = $request->has('energia_tierra');

        $muelle = Muelle::create($validated);

        return redirect()->route('muelles.show', $muelle->id)
            ->with('success', 'Muelle creado exitosamente');
    }

    public function show($id)
    {
        $muelle = Muelle::with(['buques' => function ($query) {
            $query->orderBy('fecha_atraque', 'desc')->limit(10);
        }, 'pantalans'])->findOrFail($id);

        return view('muelles.show', compact('muelle'));
    }

    public function edit($id)
    {
        $muelle = Muelle::findOrFail($id);

        return view('muelles.edit', compact('muelle'));
    }

    public function update(Request $request, $id)
    {
        $muelle = Muelle::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:muelles,codigo,' . $id,
            'longitud' => 'required|numeric|min:0',
            'calado_maximo' => 'required|numeric|min:0',
            'capacidad_toneladas' => 'required|integer|min:0',
            'tipo_muelle' => 'required|in:contenedores,carga_general,graneles,pesquero,pasajeros,ro-ro,hidrocarburos,deportivo,servicios',
            'disponible' => 'boolean',
            'grua_disponible' => 'boolean',
            'energia_tierra' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['disponible'] = $request->has('disponible');
        $validated['grua_disponible'] = $request->has('grua_disponible');
        $validated['energia_tierra'] = $request->has('energia_tierra');

        $muelle->update($validated);
        // Si el muelle se marca como no disponible, desactivamos todos sus pantalanes
        if ($muelle->wasChanged('disponible') && !$muelle->disponible) {
            $muelle->pantalans()->update(['disponible' => false]);
        }

        return redirect()->route('muelles.show', $muelle->id)
            ->with('success', 'Muelle actualizado exitosamente');
    }

    public function destroy($id)
    {
        $muelle = Muelle::findOrFail($id);

        if ($muelle->estaOcupado()) {
            return redirect()->route('muelles.index')
                ->with('error', 'No se puede eliminar un muelle con buques atracados');
        }

        $muelle->delete();

        return redirect()->route('muelles.index')
            ->with('success', 'Muelle eliminado exitosamente');
    }

    public function toggleDisponibilidad($id)
    {
        $muelle = Muelle::findOrFail($id);
        $muelle->disponible = !$muelle->disponible;
        $muelle->save();

        // Si el muelle se ha marcado como no disponible, desactivamos todos sus pantalanes
        if (!$muelle->disponible) {
            $muelle->pantalans()->update(['disponible' => false]);
        }

        return redirect()->back()
            ->with('success', 'Disponibilidad actualizada');
    }
}