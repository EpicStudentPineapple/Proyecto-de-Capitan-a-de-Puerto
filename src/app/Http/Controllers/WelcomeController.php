<?php

namespace App\Http\Controllers;

use App\Models\Muelle;
use App\Models\Buque;
use App\Models\Servicio;
use App\Models\Resena;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Get statistics for the landing page.
     */
    public function estadisticas()
    {
        $stats = [
            'total_muelles' => Muelle::count(),
            'total_buques' => Buque::count(),
            'total_servicios' => Servicio::count(),
            'total_resenas_aprobadas' => Resena::aprobadas()->count(),
            'promedio_calificacion' => round(Resena::aprobadas()->avg('calificacion'), 1),
        ];

        return response()->json($stats);
    }
}
