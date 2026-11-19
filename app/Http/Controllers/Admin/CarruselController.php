<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarruselImagen;
use App\Support\ArchivoSubido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Banners del carrusel de la portada.
 */
class CarruselController extends Controller
{
    public function index(): View
    {
        return view('admin.carrusel.index', [
            'imagenes' => CarruselImagen::orderBy('orden')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'imagenes' => ['required', 'array', 'max:10'],
            'imagenes.*' => ['image', 'max:5120'],
        ], [
            'imagenes.required' => 'Selecciona al menos una imagen.',
        ]);

        $orden = (int) CarruselImagen::max('orden');

        foreach ($request->file('imagenes') as $archivo) {
            CarruselImagen::create([
                'imagen' => ArchivoSubido::guardarConNombreOriginal($archivo, 'carrusel'),
                'descripcion' => pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME),
                'orden' => ++$orden,
            ]);
        }

        $cantidad = count($request->file('imagenes'));

        return redirect()->route('admin.carrusel.index')
            ->with('status', $cantidad === 1 ? 'Imagen agregada al carrusel.' : "{$cantidad} imágenes agregadas al carrusel.");
    }

    public function update(Request $request, CarruselImagen $imagen): RedirectResponse
    {
        $datos = $request->validate([
            'descripcion' => ['nullable', 'string', 'max:255'],
            'enlace' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|\/)/'],
            'orden' => ['required', 'integer', 'min:0', 'max:9999'],
            'activo' => ['boolean'],
            'imagen_movil' => ['nullable', 'image', 'max:5120'],
            'quitar_movil' => ['boolean'],
        ], [
            'enlace.regex' => 'El enlace debe empezar por https:// o por / (por ejemplo /tienda).',
        ]);

        $cambios = [
            'descripcion' => $datos['descripcion'] ?? null,
            'enlace' => $datos['enlace'] ?? null,
            'orden' => $datos['orden'],
            'activo' => $request->boolean('activo'),
        ];

        if ($request->hasFile('imagen_movil') || $request->boolean('quitar_movil')) {
            if ($imagen->imagen_movil) {
                Storage::disk('public')->delete($imagen->imagen_movil);
            }

            $cambios['imagen_movil'] = $request->hasFile('imagen_movil')
                ? ArchivoSubido::guardarConNombreOriginal($request->file('imagen_movil'), 'carrusel')
                : null;
        }

        $imagen->update($cambios);

        return redirect()->route('admin.carrusel.index')->with('status', 'Imagen actualizada.');
    }

    public function destroy(CarruselImagen $imagen): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([$imagen->imagen, $imagen->imagen_movil]));
        $imagen->delete();

        return redirect()->route('admin.carrusel.index')->with('status', 'Imagen eliminada del carrusel.');
    }
}
