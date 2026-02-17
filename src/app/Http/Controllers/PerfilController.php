<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        $perfiles = Perfil::with('user')->paginate(15);

        return view('perfiles.index', compact('perfiles'));
    }

    public function miPerfil()
    {
        $user = Auth::user();
        $perfil = $user->perfil;

        return view('perfiles.mi-perfil', compact('user', 'perfil'));
    }

    public function editarMiPerfil()
    {
        $user = Auth::user();
        $perfil = $user->perfil;

        return view('perfiles.editar-mi-perfil', compact('user', 'perfil'));
    }

    public function actualizarMiPerfil(Request $request)
    {
        $user = Auth::user();
        $perfil = $user->perfil;

        if (!$perfil) {
            // Create if missing
            $perfil = \App\Models\Perfil::create([
                'user_id' => $user->id,
                'nombre' => $user->name,
                'tipo_usuario' => 'propietario',
                'activo' => true,
            ]);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'cif' => 'nullable|string|max:20',
            'licencia_maritima' => 'nullable|string|max:50',
        ]);

        $perfil->update($validated);

        return back()->with('status', 'perfil-updated');
    }

    public function show($id)
    {
        $perfil = Perfil::with('user')->findOrFail($id);

        return view('perfiles.show', compact('perfil'));
    }

    // ... (rest of methods)

    public function edit($id)
    {
        $perfil = Perfil::with('user')->findOrFail($id);

        return view('perfiles.edit', compact('perfil'));
    }

    public function update(Request $request, $id)
    {
        $perfil = Perfil::findOrFail($id);

        $validated = $request->validate([
            'tipo_usuario' => 'required|in:administrador,propietario ',
            'telefono' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'licencia_maritima' => 'nullable|string|max:50|unique:perfils,licencia_maritima,' . $id,
            'activo' => 'boolean',
        ]);

        $perfil->update($validated);

        return redirect()->route('perfiles.show', $perfil->id)
            ->with('success', 'Perfil actualizado exitosamente');
    }

    public function toggleActivo($id)
    {
        $perfil = Perfil::findOrFail($id);
        $perfil->activo = !$perfil->activo;
        $perfil->save();

        return redirect()->back()
            ->with('success', 'Estado del perfil actualizado');
    }

/**
 * Update the authenticated user's profile.
 */

}