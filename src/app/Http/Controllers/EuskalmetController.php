<?php
/**
 * app/Http/Controllers/EuskalmetController.php
 *
 * Proxy seguro para la API de Euskalmet (Open Data Euskadi).
 *
 * ══════════════════════════════════════════════════════════════════════════
 * CAUSA DEL 502 — MÉTODO DE AUTENTICACIÓN INCORRECTO
 * ══════════════════════════════════════════════════════════════════════════
 * La versión anterior enviaba X-Euskadi-Signature / X-Euskadi-Timestamp,
 * pero la API de Euskalmet exige un JWT firmado con RS256 en:
 *
 *   Authorization: Bearer <JWT>
 *
 * Referencia oficial:
 *   https://opendata.euskadi.eus/api-euskalmet/-/how-to-use-meteo-rest-services/
 *
 * ══════════════════════════════════════════════════════════════════════════
 * Variables de entorno requeridas en .env:
 *
 *   EUSKALMET_PRIVATE_KEY="MIIEvQIBADANBgkq..."   ← clave privada PKCS#8 base64
 *   EUSKALMET_EMAIL="tu@email.com"                 ← email con el que te registraste
 *
 * ══════════════════════════════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EuskalmetController extends Controller
{
    /* ── Configuración ───────────────────────────────────────────────── */

    private const API_BASE  = 'https://api.euskadi.eus/euskalmet';
    private const PROVINCIA = '20';    // Gipuzkoa
    private const MUNICIPIO = '069';   // Donostia-San Sebastián
    private const TIMEOUT   = 12;

    /* ── Endpoint público: GET /api/euskalmet/prediccion ─────────────── */

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

    /* ── Lógica principal ────────────────────────────────────────────── */

    private function obtenerPrediccion(): array
    {
        // Pedimos el pronóstico de mañana directamente por URL
        $manana = now()->addDay();


    $url = sprintf(
        '%s/forecasts/at/%04d/%02d/%02d/forLocation/inProvince/%s/inMunicipality/%s',
        self::API_BASE,
        $manana->year,
        $manana->month,
        $manana->day,
        self::PROVINCIA,
        self::MUNICIPIO
        );

        $jwt = $this->generarJWT();

        $response = Http::timeout(self::TIMEOUT)
            ->withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $jwt,
            ])
            ->get($url);

        // Log de diagnóstico (útil durante el desarrollo)
        Log::info('[Euskalmet] Respuesta de la API', [
            'url'    => $url,
            'status' => $response->status(),
            'body'   => substr($response->body(), 0, 600),
        ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                "API Euskalmet respondió {$response->status()}: " .
                substr($response->body(), 0, 300)
            );
        }

        return $this->normalizar($response->json() ?? []);
    }

    /* ── Generación del JWT RS256 (sin dependencias externas) ─────────── */
    /**
     * Implementación manual del JWT según RFC-7519 + especificación Euskalmet.
     *
     * Header  : { "alg": "RS256" }
     * Payload : {
     *   "aud"     : "met01.apikey",   <- fijo
     *   "iss"     : "puerto-app",     <- nombre libre de la app
     *   "exp"     : ahora + 3600,
     *   "version" : "1.0.0",          <- fijo
     *   "iat"     : ahora,
     *   "email"   : EUSKALMET_EMAIL   <- email registrado en Euskalmet
     * }
     * Firma   : RSA-SHA256( base64url(header).base64url(payload), privKey )
     */
    private function generarJWT(): string
    {
        $privKeyBase64 = trim(env('EUSKALMET_PRIVATE_KEY', ''));
        $email         = trim(env('EUSKALMET_EMAIL', ''));

        if (empty($privKeyBase64)) {
            throw new \RuntimeException('EUSKALMET_PRIVATE_KEY no está configurada en .env');
        }
        if (empty($email)) {
            throw new \RuntimeException('EUSKALMET_EMAIL no está configurada en .env');
        }

        // 1. Header
        $header = $this->b64url(json_encode(['alg' => 'RS256']));

        // 2. Payload
        $ahora   = time();
        $payload = $this->b64url(json_encode([
            'aud'     => 'met01.apikey',
            'iss'     => 'puerto-donostia-app',
            'exp'     => $ahora + 3600,
            'version' => '1.0.0',
            'iat'     => $ahora,
            'email'   => $email,
        ]));

        $datos = $header . '.' . $payload;

        // 3. Clave privada PKCS#8
        $pem   = "-----BEGIN PRIVATE KEY-----\n"
               . chunk_split($privKeyBase64, 64, "\n")
               . "-----END PRIVATE KEY-----\n";

        $clave = openssl_pkey_get_private($pem);

        if ($clave === false) {
            throw new \RuntimeException(
                'Clave privada no válida: ' . openssl_error_string()
            );
        }

        // 4. Firma RSA-SHA256
        $firma = '';
        if (!openssl_sign($datos, $firma, $clave, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('Error firmando el JWT: ' . openssl_error_string());
        }

        return $datos . '.' . $this->b64url($firma);
    }

    /** Codificación base64url sin padding (RFC-4648 §5) */
    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /* ── Normalización de la respuesta ──────────────────────────────── */

    private function normalizar(array $raw): array
    {
        // La API puede envolver los datos en distintas claves
        $lista = $raw['forecast']   ??
                 $raw['dias']       ??
                 $raw['prediccion'] ??
                 $raw['data']       ??
                 [];

        // Como pedimos el día exacto por URL, tomamos el primer elemento
        $dia = (is_array($lista) && count($lista) > 0) ? $lista[0] : $raw;

        return [
            'municipio'       => 'Donostia - San Sebastián',
            'fecha'           => $dia['fecha']                         ?? $raw['fecha']           ?? null,
            'temperatura'     => $dia['temperatura']['maxima']         ?? $dia['tMax']             ?? null,
            'tempMin'         => $dia['temperatura']['minima']         ?? $dia['tMin']             ?? null,
            'precipitacion'   => $dia['precipitacion']['valor']        ?? $dia['lluvia']           ?? null,
            'viento'          => $dia['viento']['velocidad']           ?? $dia['vientoKmh']        ?? null,
            'vientoDireccion' => $dia['viento']['direccion']           ?? null,
            'estadoCielo'     => $dia['estadoCielo']['descripcion']    ?? $dia['descripcion']      ?? null,
            'humedadMax'      => $dia['humedad']['maxima']             ?? null,
            'alturaOlas'      => $dia['oleaje']['altura']              ?? $dia['alturaOlas']       ?? null,
            'actualizadoEn'   => $raw['actualizadoEn']                ?? $raw['fechaGeneracion']  ?? now()->toIso8601String(),
        ];
    }
}