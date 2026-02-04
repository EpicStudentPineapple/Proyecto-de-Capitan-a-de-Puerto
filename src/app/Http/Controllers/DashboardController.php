<?php

namespace App\Http\Controllers;

use App\Models\Buque;
use App\Models\Muelle;
use App\Models\Servicio;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $buquesAtracados = Buque::atracados()->count();
        $buquesFondeados = Buque::fondeados()->count();
        $buquesNavegando = Buque::navegando()->count();
        $muellesDisponibles = Muelle::disponibles()->count();
        $muellesOcupados = Muelle::whereHas('buques', function($query) {
            $query->where('estado', 'atracado');
        })->count();

        return view('dashboard.index', compact(
            'buquesAtracados',
            'buquesFondeados',
            'buquesNavegando',
            'muellesDisponibles',
            'muellesOcupados'
        ));
    }

    public function trafico()
    {
        $buques = Buque::with(['muelle', 'propietario'])
            ->whereIn('estado', ['atracado', 'fondeado', 'en_maniobra'])
            ->orderBy('fecha_atraque', 'desc')
            ->get();

        $muelles = Muelle::with('buqueActual')->get();

        return view('dashboard.trafico', compact('buques', 'muelles'));
    }

    public function clima()
    {
        // Aquí se integrará la API de Euskalmet
        return view('dashboard.clima');
    }

    public function servicios()
    {
        $servicios = Servicio::all()->groupBy('tipo_servicio');
        
        return view('dashboard.servicios', compact('servicios'));
    }
}