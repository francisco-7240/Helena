<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::with('categoriaPadre')->withCount('productos')->orderBy('nombre')->paginate(20);

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('admin.categorias.form', [
            'categoria' => new Categoria(['estado' => true]),
            'padres' => Categoria::whereNull('categoria_padre_id')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Categoria::create($this->validar($request));

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría creada.');
    }

    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.form', [
            'categoria' => $categoria,
            'padres' => Categoria::whereNull('categoria_padre_id')->whereKeyNot($categoria->id)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $categoria->update($this->validar($request, $categoria));

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->productos()->exists()) {
            return back()->withErrors(['categoria' => 'No se puede eliminar una categoría con productos.']);
        }

        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?Categoria $categoria = null): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categorias', 'slug')->ignore($categoria)],
            'descripcion' => ['nullable', 'string'],
            'categoria_padre_id' => ['nullable', 'integer', Rule::exists('categorias', 'id'), Rule::notIn([$categoria?->id])],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'estado' => ['boolean'],
        ]);

        $datos['slug'] = Str::slug($datos['slug'] ?: $datos['nombre']);
        $datos['estado'] = $request->boolean('estado');

        if ($request->hasFile('imagen')) {
            if ($categoria?->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('categorias', 'public');
        } else {
            unset($datos['imagen']);
        }

        return $datos;
    }
}
