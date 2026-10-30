<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TasaBcvServicio
{
    /**
     * URL oficial del Banco Central de Venezuela.
     */
    protected const URL_BCV = 'https://www.bcv.org.ve';

    /**
     * Clave utilizada en caché para almacenar la tasa del día.
     */
    protected const CACHE_KEY = 'tasa_oficial_bcv_usd';

    /**
     * Tasa de respaldo por si el portal del BCV estuviese fuera de servicio temporalmente.
     */
    protected const TASA_FALLBACK = 888.89;

    /**
     * Obtiene automáticamente la tasa del dólar (USD) oficial del BCV.
     * Almacena el resultado en caché (por 2 horas) para optimizar las peticiones diarias.
     *
     * @param bool $forzarActualizacion Si es true, ignora la caché y consulta de nuevo a la web del BCV.
     * @return float
     */
    public function obtenerTasaUsd(bool $forzarActualizacion = false): float
    {
        if ($forzarActualizacion) {
            Cache::forget(self::CACHE_KEY);
        }

        return (float) Cache::remember(self::CACHE_KEY, now()->addHours(2), function () {
            // 1. Intentar scraping directo a la web oficial del BCV
            $tasaScraped = $this->extraerTasaDesdePortalBcv();
            if ($tasaScraped !== null && $tasaScraped > 0) {
                return $tasaScraped;
            }

            // 2. Intentar pasarelas API de respaldo que replican la tasa oficial BCV
            $tasaApi = $this->consultarPasarelasBcv();
            if ($tasaApi !== null && $tasaApi > 0) {
                return $tasaApi;
            }

            // 3. Si no hay respuesta, retornar valor de respaldo seguro
            return self::TASA_FALLBACK;
        });
    }

    /**
     * Realiza la petición HTTP a https://www.bcv.org.ve y parsea la estructura HTML:
     * <div id="dolar"> ... <strong class="strong-tb">888,89090000</strong>
     *
     * @return float|null
     */
    protected function extraerTasaDesdePortalBcv(): ?float
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                ])
                ->timeout(7)
                ->get(self::URL_BCV);

            if ($response->successful()) {
                $html = $response->body();

                // Patrón 1: Bloque con id="dolar" y etiqueta <strong class="strong-tb">
                if (preg_match('/id=["\']dolar["\'][\s\S]*?<strong[^>]*>([\d\.,]+)<\/strong>/i', $html, $matches)) {
                    return $this->limpiarYConvertirMonto($matches[1]);
                }

                // Patrón 2: Bloque con texto USD y posterior etiqueta <strong class="strong-tb">
                if (preg_match('/USD[\s\S]*?<strong[^>]*>([\d\.,]+)<\/strong>/i', $html, $matches)) {
                    return $this->limpiarYConvertirMonto($matches[1]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Error de conexión con la web oficial del BCV: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Consulta pasarelas API de soporte en caso de timeout en el sitio web oficial.
     *
     * @return float|null
     */
    protected function consultarPasarelasBcv(): ?float
    {
        try {
            $res = Http::timeout(4)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            if ($res->successful()) {
                $promedio = $res->json('promedio');
                if (is_numeric($promedio) && $promedio > 0) {
                    return (float) $promedio;
                }
            }
        } catch (\Throwable $e) {
            // Silencioso
        }

        return null;
    }

    /**
     * Convierte el formato numérico del BCV (ej. '888,89090000') a float redondeado a 2 decimales (888.89).
     *
     * @param string $valorTexto
     * @return float
     */
    protected function limpiarYConvertirMonto(string $valorTexto): float
    {
        $limpio = trim(strip_tags($valorTexto));
        // Si tiene formato '1.234,56' o '888,89'
        $limpio = str_replace([' ', "\n", "\r", "\t"], '', $limpio);
        $limpio = str_replace('.', '', $limpio); // Quitar puntos de miles
        $limpio = str_replace(',', '.', $limpio); // Reemplazar coma decimal por punto

        return round((float) $limpio, 2);
    }

    /**
     * Convierte cualquier monto de Dólares (USD) a Bolívares (VES)
     * utilizando automáticamente la tasa oficial del día del BCV.
     *
     * @param float $montoUsd
     * @param float|null $tasaPersonalizada
     * @return float
     */
    public function convertirUsdABs(float $montoUsd, ?float $tasaPersonalizada = null): float
    {
        $tasa = $tasaPersonalizada ?? $this->obtenerTasaUsd();
        return round($montoUsd * $tasa, 2);
    }

    /**
     * Convierte un monto de Bolívares (VES) a Dólares (USD).
     *
     * @param float $montoBs
     * @param float|null $tasaPersonalizada
     * @return float
     */
    public function convertirBsAUsd(float $montoBs, ?float $tasaPersonalizada = null): float
    {
        $tasa = $tasaPersonalizada ?? $this->obtenerTasaUsd();
        if ($tasa <= 0) {
            return 0.0;
        }

        return round($montoBs / $tasa, 2);
    }
}
