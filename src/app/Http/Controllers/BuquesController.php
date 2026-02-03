<?php

namespace App\Http\Controllers;

use App\Models\Buque;
use App\Models\Muelle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuqueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Si es cliente, solo ve sus buques
        if ($user->isCliente()) {
            $buques = Buque::dePropietario($user->id)->with(['muelle', 'propietario'])->paginate(15);
        } else {
            // Administradores y operadores ven todos
            $buques = Buque::with(['muelle', 'propietario'])->paginate(15);
        }

        return view('buques.index', compact('buques'));
    }

    public function create()
    {
        $propietarios = User::whereHas('perfil', function($query) {
            $query->whereIn('tipo_usuario', ['consignatario', 'armador']);
        })->get();

        $muelles = Muelle::disponibles()->get();

        return view('buques.create', compact('propietarios', 'muelles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'imo' => 'required|string|max:10|unique:buques,imo',
            'mmsi' => 'nullable|string|max:15',
            'bandera' => 'required|string|max:50',
            'eslora' => 'required|numeric|min:0',
            'manga' => 'required|numeric|min:0',
            'calado' => 'required|numeric|min:0',
            'tonelaje_bruto' => 'required|integer|min:0',
            'tipo_buque' => 'required|in:portacontenedores,granelero,petrolero,gasero,pesquero,ferry,ro-ro,carga_general,crucero,narcolancha,deportivo,remolcador',
            'propietario_id' => 'required|exists:users,id',
            'muelle_id' => 'nullable|exists:muelles,id',
            'fecha_atraque' => 'nullable|date',
            'fecha_salida_prevista' => 'nullable|date|after:fecha_atraque',
            'estado' => 'required|in:navegando,fondeado,atracado,en_maniobra,mantenimiento',
            'carga_actual' => 'nullable|integer|min:0',
            'tripulacion' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $buque = Buque::create($validated);

        return redirect()->route('buques.show', $buque->id)
            ->with('success', 'Buque registrado exitosamente');
    }

    public function show($id)
    {
        $buque = Buque::with(['muelle', 'propietario.perfil', 'servicios'])->findOrFail($id);

        // Verificar permisos
        $user = Auth::user();
        if ($user->isCliente() && $buque->propietario_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este buque');
        }

        return view('buques.show', compact('buque'));
    }

    public function edit($id)
    {
        $buque = Buque::findOrFail($id);

        // Verificar permisos
        $user = Auth::user();
        if ($user->isCliente() && $buque->propietario_id !== $user->id) {
            abort(403, 'No tienes permiso para editar este buque');
        }

        $propietarios = User::whereHas('perfil', function($query) {
            $query->whereIn('tipo_usuario', ['consignatario', 'armador']);
        })->get();

        $muelles = Muelle::disponibles()->get();

        return view('buques.edit', compact('buque', 'propietarios', 'muelles'));
    }

    public function update(Request $request, $id)
    {
        $buque = Buque::findOrFail($id);

        // Verificar permisos
        $user = Auth::user();
        if ($user->isCliente() && $buque->propietario_id !== $user->id) {
            abort(403, 'No tienes permiso para editar este buque');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'imo' => 'required|string|max:10|unique:buques,imo,' . $id,
            'mmsi' => 'nullable|string|max:15',
            'bandera' => 'required|string|max:50',
            'eslora' => 'required|numeric|min:0',
            'manga' => 'required|numeric|min:0',
            'calado' => 'required|numeric|min:0',
            'tonelaje_bruto' => 'required|integer|min:0',
            'tipo_buque' => 'required|in:portacontenedores,granelero,petrolero,gasero,pesquero,ferry,ro-ro,carga_general,crucero,deportivo,remolcador',
            'propietario_id' => 'required|exists:users,id',
            'muelle_id' => 'nullable|exists:muelles,id',
            'fecha_atraque' => 'nullable|date',
            'fecha_salida_prevista' => 'nullable|date|after:fecha_atraque',
            'estado' => 'required|in:navegando,fondeado,atracado,en_maniobra,mantenimiento',
            'carga_actual' => 'nullable|integer|min:0',
            'tripulacion' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $buque->update($validated);

        return redirect()->route('buques.show', $buque->id)
            ->with('success', 'Buque actualizado exitosamente');
    }

    public function destroy($id)
    {
        $buque = Buque::findOrFail($id);

        // Verificar permisos
        $user = Auth::user();
        if ($user->isCliente() && $buque->propietario_id !== $user->id) {
            abort(403, 'No tienes permiso para eliminar este buque');
        }

        // No permitir eliminar si está atracado
        if ($buque->estado === 'atracado') {
            return redirect()->route('buques.index')
                ->with('error', 'No se puede eliminar un buque atracado');
        }

        $buque->delete();

        return redirect()->route('buques.index')
            ->with('success', 'Buque eliminado exitosamente');
    }

    public function asignarMuelle(Request $request, $id)
    {
        $buque = Buque::findOrFail($id);
        $muelle = Muelle::findOrFail($request->muelle_id);

        // Validar que el buque puede atracar
        if (!$muelle->puedeAtracar($buque)) {
            return response()->json([
                'success' => false,
                'message' => 'El buque no cumple las condiciones para atracar en este muelle'
            ], 400);
        }

        $buque->update([
            'muelle_id' => $muelle->id,
            'estado' => 'atracado',
            'fecha_atraque' => now(),
            'fecha_salida_prevista' => $request->fecha_salida ?? now()->addDays(2),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buque asignado exitosamente',
            'buque' => $buque->load('muelle')
        ]);
    }

    public function desatracar($id)
    {
        $buque = Buque::findOrFail($id);

        $buque->update([
            'muelle_id' => null,
            'estado' => 'navegando',
            'fecha_atraque' => null,
            'fecha_salida_prevista' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Buque desatracado exitosamente');
    }

    public function gestionAtraques()
    {
        $buquesFondeados = Buque::fondeados()->with('propietario')->get();
        $muelles = Muelle::with('buqueActual')->get();

        return view('buques.gestion-atraques', compact('buquesFondeados', 'muelles'));
    }
}