<?php

namespace App\Services;

use App\Models\Contenido;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Lee y guarda el contenido editable del sitio.
 *
 * Los campos y sus valores por defecto se definen en config/paginas.php;
 * en la base de datos sólo se guardan los valores que el administrador cambia.
 */
class Contenidos
{
    private const CACHE = 'contenidos';

    /** @var array<string, string|null>|null */
    private ?array $valores = null;

    /**
     * Valor de un campo ("pagina.seccion.campo"): el guardado o, si no hay, el de por defecto.
     */
    public function get(string $clave): ?string
    {
        $valores = $this->valores();

        if (array_key_exists($clave, $valores) && $valores[$clave] !== null && $valores[$clave] !== '') {
            return $valores[$clave];
        }

        return $this->campo($clave)['defecto'] ?? null;
    }

    /**
     * Indica si el campo tiene un valor guardado distinto del de por defecto.
     */
    public function personalizado(string $clave): bool
    {
        return isset($this->valores()[$clave]);
    }

    /**
     * Guarda varios campos a la vez. Un valor null o vacío vuelve al valor por defecto.
     *
     * @param  array<string, string|null>  $valores
     */
    public function guardar(array $valores): void
    {
        foreach ($valores as $clave => $valor) {
            if ($valor === null || $valor === '') {
                Contenido::where('clave', $clave)->delete();
            } else {
                Contenido::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
            }
        }

        $this->olvidar();
    }

    public function olvidar(): void
    {
        Cache::forget(self::CACHE);
        $this->valores = null;
    }

    /**
     * Definición de un campo en config/paginas.php.
     *
     * @return array{etiqueta?: string, tipo?: string, defecto?: string|null, config?: string}|null
     */
    public function campo(string $clave): ?array
    {
        $partes = explode('.', $clave, 3);

        if (count($partes) !== 3) {
            return null;
        }

        [$pagina, $seccion, $campo] = $partes;

        return config("paginas.{$pagina}.secciones.{$seccion}.campos.{$campo}");
    }

    /**
     * Aplica los valores de "Configuración general" sobre config('tienda.*').
     */
    public function aplicarConfiguracion(): void
    {
        try {
            foreach (config('paginas.general.secciones', []) as $seccion => $definicion) {
                foreach ($definicion['campos'] as $campo => $opciones) {
                    $clave = "general.{$seccion}.{$campo}";

                    if (isset($opciones['config']) && $this->personalizado($clave)) {
                        $valor = $this->get($clave);
                        config([$opciones['config'] => ($opciones['tipo'] ?? null) === 'numero' ? (float) $valor : $valor]);
                    }
                }
            }
        } catch (Throwable) {
            // La tabla aún no existe (por ejemplo, antes de migrar): se usan los valores del .env.
        }
    }

    /**
     * @return array<string, string|null>
     */
    private function valores(): array
    {
        return $this->valores ??= Cache::rememberForever(
            self::CACHE,
            fn () => Contenido::pluck('valor', 'clave')->all(),
        );
    }
}
