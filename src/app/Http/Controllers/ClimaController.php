<?php
/**
 * app/Http/Controllers/ClimaController.php
 *
 * Controlador para obtener datos meteorológicos usando OpenMeteo API
 * (100% gratuita, sin registro, sin API keys)
 *
 * API: https://open-meteo.com/
 * Coordenadas: Donostia-San Sebastián (43.3183, -1.9812)
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClimaController extends Controller
{
    /* ── Configuración ───────────────────────────────────────────────── */
    
    // Coordenadas de Donostia-San Sebastián
    private const LATITUD  = 43.3183;
    private const LONGITUD = -1.9812;
    
    // API base de OpenMeteo (gratuita, sin autenticación)
    private const API_BASE = 'https://api.open-meteo.com/v1/forecast';
    
    // Timeout en segundos
    private const TIMEOUT = 10;
    
    // Tiempo de caché en segundos (30 minutos)
    private const CACHE_TTL = 1800;

    /* ── Endpoint público: GET /api/clima/prediccion ─────────────────── */

    public function prediccion(): JsonResponse
    {
        try {
            // Intentar obtener desde caché primero
            $datos = Cache::remember('clima_donostia', self::CACHE_TTL, function () {
                return $this->obtenerDatosClima();
            });
            
            return response()->json($datos);

        } catch (\Throwable $e) {
            Log::error('[Clima] Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'No se pudo obtener la predicción meteorológica.',
                'mensaje' => 'Intenta de nuevo en unos momentos.'
            ], 503);
        }
    }

    /* ── Lógica principal ────────────────────────────────────────────── */

    private function obtenerDatosClima(): array
    {
        // Construir URL con parámetros
        $url = self::API_BASE . '?' . http_build_query([
            'latitude'  => self::LATITUD,
            'longitude' => self::LONGITUD,
            'current'   => implode(',', [
                'temperature_2m',
                'relative_humidity_2m',
                'apparent_temperature',
                'precipitation',
                'weather_code',
                'wind_speed_10m',
                'wind_direction_10m'
            ]),
            'daily' => implode(',', [
                'weather_code',
                'temperature_2m_max',
                'temperature_2m_min',
                'precipitation_sum',
                'wind_speed_10m_max',
                'wind_direction_10m_dominant'
            ]),
            'timezone' => 'Europe/Madrid',
            'forecast_days' => 2
        ]);

        Log::info('[Clima] Petición a OpenMeteo', ['url' => $url]);

        // Realizar petición
        $response = Http::timeout(self::TIMEOUT)->get($url);

        if ($response->failed()) {
            throw new \RuntimeException(
                "OpenMeteo API respondió {$response->status()}: " . 
                substr($response->body(), 0, 200)
            );
        }

        $data = $response->json();
        
        Log::info('[Clima] Respuesta recibida exitosamente');

        return $this->normalizarDatos($data);
    }

    /* ── Normalización de datos ──────────────────────────────────────── */

    private function normalizarDatos(array $data): array
    {
        $current = $data['current'] ?? [];
        $daily = $data['daily'] ?? [];
        
        // Índices: 0 = hoy, 1 = mañana
        $manana = 1;

        return [
            // Información de ubicación
            'municipio' => 'Donostia - San Sebastián',
            'latitud' => $data['latitude'] ?? self::LATITUD,
            'longitud' => $data['longitude'] ?? self::LONGITUD,
            
            // Datos actuales
            'actual' => [
                'temperatura' => round($current['temperature_2m'] ?? 0, 1),
                'sensacion_termica' => round($current['apparent_temperature'] ?? 0, 1),
                'humedad' => round($current['relative_humidity_2m'] ?? 0),
                'precipitacion' => round($current['precipitation'] ?? 0, 1),
                'viento' => round($current['wind_speed_10m'] ?? 0),
                'viento_direccion' => $this->obtenerDireccionViento($current['wind_direction_10m'] ?? 0),
                'estado_cielo' => $this->obtenerEstadoCielo($current['weather_code'] ?? 0),
                'codigo_clima' => $current['weather_code'] ?? 0,
            ],
            
            // Predicción para mañana
            'manana' => [
                'fecha' => $daily['time'][$manana] ?? now()->addDay()->format('Y-m-d'),
                'temperatura_max' => round($daily['temperature_2m_max'][$manana] ?? 0, 1),
                'temperatura_min' => round($daily['temperature_2m_min'][$manana] ?? 0, 1),
                'precipitacion' => round($daily['precipitation_sum'][$manana] ?? 0, 1),
                'viento_max' => round($daily['wind_speed_10m_max'][$manana] ?? 0),
                'viento_direccion' => $this->obtenerDireccionViento($daily['wind_direction_10m_dominant'][$manana] ?? 0),
                'estado_cielo' => $this->obtenerEstadoCielo($daily['weather_code'][$manana] ?? 0),
                'codigo_clima' => $daily['weather_code'][$manana] ?? 0,
            ],
            
            // Condiciones para navegación (basado en mañana)
            'navegacion' => [
                'altura_olas' => $this->estimarAlturaOlas($daily['wind_speed_10m_max'][$manana] ?? 0),
                'apto_maniobras' => $this->evaluarAptoManiobras(
                    $daily['wind_speed_10m_max'][$manana] ?? 0,
                    $daily['precipitation_sum'][$manana] ?? 0
                ),
            ],
            
            // Metadata
            'actualizado_en' => now()->toIso8601String(),
            'fuente' => 'OpenMeteo',
        ];
    }

    /* ── Utilidades de conversión ────────────────────────────────────── */

    /**
     * Convierte grados a dirección cardinal
     */
    private function obtenerDireccionViento(float $grados): string
    {
        $direcciones = ['N', 'NE', 'E', 'SE', 'S', 'SO', 'O', 'NO'];
        $index = round($grados / 45) % 8;
        return $direcciones[$index];
    }

    /**
     * Convierte código WMO a descripción en español
     * https://open-meteo.com/en/docs
     */
    private function obtenerEstadoCielo(int $codigo): string
    {
        $estados = [
            0 => 'Despejado',
            1 => 'Principalmente despejado',
            2 => 'Parcialmente nublado',
            3 => 'Nublado',
            45 => 'Niebla',
            48 => 'Niebla con escarcha',
            51 => 'Llovizna ligera',
            53 => 'Llovizna moderada',
            55 => 'Llovizna intensa',
            61 => 'Lluvia ligera',
            63 => 'Lluvia moderada',
            65 => 'Lluvia intensa',
            71 => 'Nevada ligera',
            73 => 'Nevada moderada',
            75 => 'Nevada intensa',
            80 => 'Chubascos ligeros',
            81 => 'Chubascos moderados',
            82 => 'Chubascos violentos',
            95 => 'Tormenta',
            96 => 'Tormenta con granizo ligero',
            99 => 'Tormenta con granizo intenso',
        ];

        return $estados[$codigo] ?? 'Desconocido';
    }

    /**
     * Estima altura de olas basándose en velocidad del viento
     * Fórmula simplificada: Altura (m) ≈ Viento (km/h) / 40
     */
    private function estimarAlturaOlas(float $vientoKmh): float
    {
        // Conversión aproximada de viento a altura de olas
        $alturaMetros = $vientoKmh / 40;
        
        // Limitar entre 0.3m (mar calmado) y 4m (mar muy gruesa)
        $alturaMetros = max(0.3, min(4.0, $alturaMetros));
        
        return round($alturaMetros, 1);
    }

    /**
     * Evalúa si las condiciones son aptas para maniobras portuarias
     */
    private function evaluarAptoManiobras(float $viento, float $precipitacion): bool
    {
        // Criterios de seguridad
        $vientoSeguro = $viento < 72; // < 40 nudos (72 km/h)
        $lluviaSegura = $precipitacion < 20; // < 20 mm
        
        return $vientoSeguro && $lluviaSegura;
    }
}