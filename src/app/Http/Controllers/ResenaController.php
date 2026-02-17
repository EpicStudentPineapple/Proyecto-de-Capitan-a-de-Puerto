<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResenaController extends Controller
{
    /**
     * Display a listing of all reviews (admin only).
     */
    /**
     * Display a listing of all reviews (admin only).
     */
    public function index(Request $request)
    {
        $query = Resena::with(['user.perfil', 'servicio']);

        // Filter by approval status
        if ($request->has('aprobado')) {
            $query->where('aprobado', $request->aprobado);
        }

        $resenas = $query->recientes()->paginate(20);

        return \App\Http\Resources\ResenaResource::collection($resenas);
    }

    /**
     * Store a newly created review (propietario only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Resena::validationRules());

        // Check if user has used this service
        $hasUsedService = DB::table('buque_servicio')
            ->join('buques', 'buque_servicio.buque_id', '=', 'buques.id')
            ->where('buques.propietario_id', Auth::id())
            ->where('buque_servicio.servicio_id', $validated['servicio_id'])
            ->exists();

        if (!$hasUsedService) {
            return response()->json([
                'message' => 'No puede reseñar un servicio que no ha utilizado.'
            ], 403);
        }

        // Check for duplicate review
        $existingResena = Resena::where('user_id', Auth::id())
            ->where('servicio_id', $validated['servicio_id'])
            ->first();

        if ($existingResena) {
            return response()->json([
                'message' => 'Ya ha enviado una reseña para este servicio.'
            ], 409);
        }

        $resena = Resena::create([
            'user_id' => Auth::id(),
            'servicio_id' => $validated['servicio_id'],
            'calificacion' => $validated['calificacion'],
            'comentario' => $validated['comentario'],
            'fecha_resena' => now(),
            'aprobado' => false,
        ]);

        return response()->json([
            'message' => 'Reseña enviada correctamente. Pendiente de aprobación.',
            'resena' => new \App\Http\Resources\ResenaResource($resena->load(['user.perfil', 'servicio']))
        ], 201);
    }

    /**
     * Display the specified review.
     */
    public function show(Resena $resena)
    {
        return new \App\Http\Resources\ResenaResource($resena->load(['user.perfil', 'servicio']));
    }

    /**
     * Update the specified review (admin only).
     */
    public function update(Request $request, Resena $resena)
    {
        $validated = $request->validate([
            'calificacion' => 'sometimes|integer|min:1|max:5',
            'comentario' => 'sometimes|string|min:10|max:500',
        ]);

        $resena->update($validated);

        return response()->json([
            'message' => 'Reseña actualizada correctamente.',
            'resena' => new \App\Http\Resources\ResenaResource($resena->load(['user.perfil', 'servicio']))
        ]);
    }

    /**
     * Remove the specified review (admin only).
     */
    public function destroy(Resena $resena)
    {
        $resena->delete();

        return response()->json([
            'message' => 'Reseña eliminada correctamente.'
        ]);
    }

    /**
     * Approve a review (admin only).
     */
    public function aprobar(Resena $resena)
    {
        $resena->update(['aprobado' => true]);

        return response()->json([
            'message' => 'Reseña aprobada correctamente.',
            'resena' => new \App\Http\Resources\ResenaResource($resena->load(['user.perfil', 'servicio']))
        ]);
    }

    /**
     * Get all approved reviews (public).
     */
    public function aprobadas()
    {
        $resenas = Resena::with(['user.perfil', 'servicio'])
            ->aprobadas()
            ->recientes()
            ->paginate(20);

        return \App\Http\Resources\ResenaResource::collection($resenas);
    }

    /**
     * Get reviews for a specific service (public).
     */
    public function porServicio($servicio_id)
    {
        $resenas = Resena::with(['user.perfil'])
            ->where('servicio_id', $servicio_id)
            ->aprobadas()
            ->recientes()
            ->get();

        $promedio = Resena::where('servicio_id', $servicio_id)
            ->aprobadas()
            ->avg('calificacion');

        return response()->json([
            'resenas' => \App\Http\Resources\ResenaResource::collection($resenas),
            'promedio' => round($promedio, 1)
        ]);
    }

    /**
     * Get servicios that the authenticated propietario can review.
     */
    public function misServicios()
    {
        $servicios = DB::table('buque_servicio')
            ->join('buques', 'buque_servicio.buque_id', '=', 'buques.id')
            ->join('servicios', 'buque_servicio.servicio_id', '=', 'servicios.id')
            ->leftJoin('resenas', function ($join) {
            $join->on('servicios.id', '=', 'resenas.servicio_id')
                ->where('resenas.user_id', '=', Auth::id());
        })
            ->where('buques.propietario_id', Auth::id())
            ->whereNull('resenas.id') // Only services not yet reviewed
            ->select('servicios.*')
            ->distinct()
            ->get();

        return response()->json($servicios);
    }
}
