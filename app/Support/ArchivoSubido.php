<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Guarda archivos subidos conservando su nombre original.
 */
class ArchivoSubido
{
    /**
     * Guarda el archivo en la carpeta indicada con su nombre original y devuelve la ruta relativa al disco.
     * Si ya existe un archivo con ese nombre, agrega "-1", "-2"... para no sobrescribirlo.
     */
    public static function guardarConNombreOriginal(UploadedFile $archivo, string $carpeta, string $disco = 'public'): string
    {
        $nombre = self::limpiarNombre(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = strtolower($archivo->guessExtension() ?: $archivo->getClientOriginalExtension() ?: 'jpg');

        $candidato = "{$nombre}.{$extension}";
        for ($numero = 1; Storage::disk($disco)->exists("{$carpeta}/{$candidato}"); $numero++) {
            $candidato = "{$nombre}-{$numero}.{$extension}";
        }

        return $archivo->storeAs($carpeta, $candidato, $disco);
    }

    /**
     * Quita del nombre los caracteres que no son válidos en un archivo o en una URL
     * (barras, comodines, caracteres de control...), conservando letras con tilde y espacios.
     */
    private static function limpiarNombre(string $nombre): string
    {
        $nombre = preg_replace('/[^\pL\pN \-_.()]+/u', '', $nombre);
        $nombre = trim(preg_replace('/\s+/', ' ', $nombre), ' .-');

        return mb_substr($nombre, 0, 150) ?: 'imagen';
    }
}
