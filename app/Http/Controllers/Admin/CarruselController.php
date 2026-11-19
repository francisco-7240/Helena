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
 * Imágenes del carrusel de la portada.
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
            'orden' => ['required', 'integer', 'min:0', 'max:9999'],
            'activo' => ['boolean'],
        ]);

        $imagen->update([...$datos, 'activo' => $request->boolean('activo')]);

        return redirect()->route('admin.carrusel.index')->with('status', 'Imagen actualizada.');
    }

    public function destroy(CarruselImagen $imagen): RedirectResponse
    {
        Storage::disk('public')->delete($imagen->imagen);
        $imagen->delete();

        return redirect()->route('admin.carrusel.index')->with('status', 'Imagen eliminada del carrusel.');
    }
}
