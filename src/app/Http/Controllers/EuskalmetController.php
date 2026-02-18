<?php
/**
 * app/Http/Controllers/EuskalmetController.php
 *
 * Proxy seguro para la API de Euskalmet (Open Data Euskadi).
 *
 * La clave privada RSA vive SOLO en el servidor (variable de entorno
 * EUSKALMET_PRIVATE_KEY en el archivo .env); jamás se expone al cliente.
 *
 * Flujo:
 *  1. El cliente JS llama a GET /api/euskalmet/prediccion
 *  2. Este controlador construye la cadena de firma, la firma con RSA-SHA256
 *     y realiza la petición a la API de Euskalmet.
 *  3. Devuelve los datos normalizados como JSON.
 *
 * Endpoint Euskalmet usado:
 *   GET https://api.euskadi.eus/euskalmet/forecasts/forMunicipality/{municipio}/forDay/{dias}
 *
 * Documentación oficial:
 *   https://opendata.euskadi.eus/catalogo/-/euskalmet-prediccion-meteorologica/
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EuskalmetController extends Controller
{
    /*
     * ── Configuración ────────────────────────────────────────────────────
     */
    private const API_BASE      = 'https://api.euskadi.eus/euskalmet';
    private const MUNICIPIO_ID  = '01036';   // Código INE de Irun (Gipuzkoa)
    private const DIAS          = 2;         // Hoy (0) + mañana (1)
    private const TIMEOUT       = 10;        // segundos

    /*
     * ── Endpoint público ─────────────────────────────────────────────────
     * GET /api/euskalmet/prediccion   (routes/api.php)
     */
    public function prediccion(): JsonResponse
    {
        try {
            $datos = $this->obtenerPrediccion();
            return response()->json($datos);
        } catch (\Throwable $e) {
            Log::error('[Euskalmet] ' . $e->getMessage());
            return response()->json(
                ['error' => 'No se pudo obtener la predicción meteorológica.'],
                502
            );
        }
    }

    /*
     * ── Lógica principal ─────────────────────────────────────────────────
     */
    private function obtenerPrediccion(): array
    {
        $url = sprintf(
            '%s/forecasts/forMunicipality/%s/forDay/%d',
            self::API_BASE,
            self::MUNICIPIO_ID,
            self::DIAS
        );

        // Construcción de la cadena de firma:
        //   METHOD\nURL\nTIMESTAMP
        $timestamp = now()->toIso8601String();
        $cadena    = "GET\n{$url}\n{$timestamp}";

        // Firma RSA-SHA256
        $firma = $this->firmar($cadena);

        $response = Http::timeout(self::TIMEOUT)
            ->withHeaders([
                'Accept'              => 'application/json',
                'X-Euskadi-Signature' => $firma,
                'X-Euskadi-Timestamp' => $timestamp,
            ])
            ->get($url);

        if ($response->failed()) {
            throw new \RuntimeException(
                "API Euskalmet respondió {$response->status()}: " .
                substr($response->body(), 0, 200)
            );
        }

        return $this->normalizar($response->json());
    }

    /*
     * ── Firma RSA-SHA256 ─────────────────────────────────────────────────
     *
     * La clave privada se lee desde la variable de entorno EUSKALMET_PRIVATE_KEY.
     * Formato esperado: base64 puro (PKCS#8, sin cabeceras PEM).
     * En .env:
     *   EUSKALMET_PRIVATE_KEY="MIIEvQIBADANBgkqhki..."
     */
    private function firmar(string $cadena): string
    {
        $privKeyBase64 = config('services.euskalmet.private_key')
            ?? env('EUSKALMET_PRIVATE_KEY');

        if (empty($privKeyBase64)) {
            throw new \RuntimeException(
                'EUSKALMET_PRIVATE_KEY no está configurada en .env'
            );
        }

        // Reconstruir PEM
        $pem = "-----BEGIN PRIVATE KEY-----\n"
            . chunk_split(trim($privKeyBase64), 64, "\n")
            . "-----END PRIVATE KEY-----\n";

        $clave = openssl_pkey_get_private($pem);
        if ($clave === false) {
            throw new \RuntimeException(
                'No se pudo cargar la clave privada: ' . openssl_error_string()
            );
        }

        $firma = '';
        if (!openssl_sign($cadena, $firma, $clave, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException(
                'Error al firmar: ' . openssl_error_string()
            );
        }

        return base64_encode($firma);
    }

    /*
     * ── Normalización de respuesta ───────────────────────────────────────
     *
     * Extrae el día de mañana (índice 1) y estandariza los campos.
     * Ajustar según el esquema real que devuelva la API en producción.
     */
    private function normalizar(array $raw): array
    {
        // Intentamos localizar la lista de días en los campos más comunes
        $dias = $raw['forecast']    ??
                $raw['dias']        ??
                $raw['prediccion']  ??
                [];

        // Mañana = índice 1; si solo hay un día, tomamos el 0
        $dia = is_array($dias) && count($dias) > 1 ? $dias[1] : ($dias[0] ?? $raw);

        return [
            'fecha'           => $dia['fecha']                             ?? $raw['fecha']          ?? null,
            'temperatura'     => $dia['temperatura']['maxima']             ?? $dia['tMax']            ?? null,
            'tempMin'         => $dia['temperatura']['minima']             ?? $dia['tMin']            ?? null,
            'precipitacion'   => $dia['precipitacion']['valor']            ?? $dia['lluvia']          ?? null,
            'viento'          => $dia['viento']['velocidad']               ?? $dia['vientoKmh']       ?? null,
            'vientoDireccion' => $dia['viento']['direccion']               ?? null,
            'estadoCielo'     => $dia['estadoCielo']['descripcion']        ?? $dia['descripcion']     ?? null,
            'humedadMax'      => $dia['humedad']['maxima']                 ?? null,
            'alturaOlas'      => $dia['oleaje']['altura']                  ?? null,
            'actualizadoEn'   => $raw['actualizadoEn']                    ?? $raw['fechaGeneracion'] ?? now()->toIso8601String(),
        ];
    }
}