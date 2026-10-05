<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarcaController extends Controller
{
    public function index(): View
    {
        $marcas = Marca::withCount('productos')->orderBy('nombre')->paginate(20);

        return view('admin.marcas.index', compact('marcas'));
    }

    public function create(): View
    {
        return view('admin.marcas.form', ['marca' => new Marca(['estado' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Marca::create($this->validar($request));

        return redirect()->route('admin.marcas.index')->with('status', 'Marca creada.');
    }

    public function edit(Marca $marca): View
    {
        return view('admin.marcas.form', ['marca' => $marca]);
    }

    public function update(Request $request, Marca $marca): RedirectResponse
    {
        $marca->update($this->validar($request, $marca));

        return redirect()->route('admin.marcas.index')->with('status', 'Marca actualizada.');
    }

    public function destroy(Marca $marca): RedirectResponse
    {
        if ($marca->productos()->exists()) {
            return back()->withErrors(['marca' => 'No se puede eliminar una categoría con productos.']);
        }

        if ($marca->imagen) {
            Storage::disk('public')->delete($marca->imagen);
        }

        $marca->delete();

        return redirect()->route('admin.marcas.index')->with('status', 'Marca eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?Marca $marca = null): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('marcas', 'slug')->ignore($marca)],
            'descripcion' => ['nullable', 'string'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'estado' => ['boolean'],
        ]);

        $datos['slug'] = Str::slug($datos['slug'] ?: $datos['nombre']);
        $datos['estado'] = $request->boolean('estado');

        if ($request->hasFile('imagen')) {
            if ($marca?->imagen) {
                Storage::disk('public')->delete($marca->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('marcas', 'public');
        } else {
            unset($datos['imagen']);
        }

        return $datos;
    }
}
