<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contenidos;
use App\Support\ArchivoSubido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Edición del contenido (textos e imágenes) de las páginas del sitio.
 */
class PaginaController extends Controller
{
    /**
     * Carpeta del disco "public" donde se guardan las imágenes subidas desde el panel.
     */
    private const CARPETA = 'contenidos';

    public function __construct(private Contenidos $contenidos) {}

    public function index(): View
    {
        return view('admin.paginas.index', ['paginas' => config('paginas')]);
    }

    public function edit(string $pagina): View
    {
        return view('admin.paginas.edit', [
            'clavePagina' => $pagina,
            'pagina' => $this->definicion($pagina),
            'contenidos' => $this->contenidos,
        ]);
    }

    public function update(Request $request, string $pagina): RedirectResponse
    {
        $definicion = $this->definicion($pagina);
        $request->validate($this->reglas($pagina, $definicion));

        $valores = [];

        foreach ($this->campos($pagina, $definicion) as $clave => $campo) {
            $nombre = $this->nombreInput($clave);

            if ($campo['tipo'] === 'imagen') {
                $actual = $this->contenidos->personalizado($clave) ? $this->contenidos->get($clave) : null;

                if ($request->hasFile($nombre)) {
                    $this->borrarImagenSubida($actual);
                    $valores[$clave] = 'storage/'.ArchivoSubido::guardarConNombreOriginal($request->file($nombre), self::CARPETA);
                } elseif ($request->boolean($nombre.'_restaurar')) {
                    $this->borrarImagenSubida($actual);
                    $valores[$clave] = null;
                }

                continue;
            }

            $valores[$clave] = $this->valorAGuardar($clave, $campo, $request->input($nombre));
        }

        $this->contenidos->guardar($valores);

        return redirect()->route('admin.paginas.edit', $pagina)->with('status', 'Contenido guardado.');
    }

    /**
     * Valor a guardar para un campo de texto: null (usar el valor original) si viene vacío
     * o si coincide con el texto original.
     *
     * @param  array<string, mixed>  $campo
     */
    private function valorAGuardar(string $clave, array $campo, mixed $valor): ?string
    {
        if ($valor === null) {
            return null;
        }

        $valor = str_replace("\r\n", "\n", (string) $valor);
        $original = $campo['defecto'] ?? null;

        if (isset($campo['config']) && ! $this->contenidos->personalizado($clave)) {
            $original = (string) config($campo['config']);
        }

        return $valor === (string) $original ? null : $valor;
    }

    /**
     * @return array<string, mixed>
     */
    private function definicion(string $pagina): array
    {
        $definicion = config("paginas.{$pagina}");
        abort_unless(is_array($definicion), 404);

        return $definicion;
    }

    /**
     * Todos los campos de la página, indexados por su clave completa.
     *
     * @param  array<string, mixed>  $definicion
     * @return array<string, array<string, mixed>>
     */
    private function campos(string $pagina, array $definicion): array
    {
        $campos = [];

        foreach ($definicion['secciones'] as $seccion => $datos) {
            foreach ($datos['campos'] as $campo => $opciones) {
                $campos["{$pagina}.{$seccion}.{$campo}"] = $opciones;
            }
        }

        return $campos;
    }

    /**
     * @param  array<string, mixed>  $definicion
     * @return array<string, array<int, string>>
     */
    private function reglas(string $pagina, array $definicion): array
    {
        $reglas = [];

        foreach ($this->campos($pagina, $definicion) as $clave => $campo) {
            $nombre = $this->nombreInput($clave);

            $reglas[$nombre] = match ($campo['tipo']) {
                'imagen' => ['nullable', 'image', 'max:5120'],
                'numero' => ['nullable', 'numeric', 'min:0'],
                'parrafo' => ['nullable', 'string', 'max:5000'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        return $reglas;
    }

    /**
     * Nombre del input del formulario para una clave ("inicio.hero.titulo" → "inicio__hero__titulo").
     */
    private function nombreInput(string $clave): string
    {
        return str_replace('.', '__', $clave);
    }

    /**
     * Borra del disco una imagen subida desde el panel (nunca las imágenes por defecto).
     */
    private function borrarImagenSubida(?string $ruta): void
    {
        if ($ruta && Str::startsWith($ruta, 'storage/'.self::CARPETA.'/')) {
            Storage::disk('public')->delete(Str::after($ruta, 'storage/'));
        }
    }
}
