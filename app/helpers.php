<?php

use App\Services\Contenidos;
use Illuminate\Support\HtmlString;

if (! function_exists('contenido')) {
    /**
     * Texto o ruta de imagen editable desde el panel ("pagina.seccion.campo").
     */
    function contenido(string $clave): ?string
    {
        return app(Contenidos::class)->get($clave);
    }
}

if (! function_exists('contenido_html')) {
    /**
     * Contenido escapado, con los saltos de línea convertidos en <br>.
     */
    function contenido_html(string $clave): HtmlString
    {
        return new HtmlString(nl2br(e((string) contenido($clave)), false));
    }
}

if (! function_exists('contenido_parrafos')) {
    /**
     * Contenido dividido en párrafos (separados por una línea en blanco).
     *
     * @return array<int, string>
     */
    function contenido_parrafos(string $clave): array
    {
        $texto = str_replace("\r\n", "\n", (string) contenido($clave));

        return array_values(array_filter(array_map('trim', preg_split("/\n\s*\n/", $texto))));
    }
}
