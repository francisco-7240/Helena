<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(): View
    {
        $colores = Color::orderBy('nombre')->paginate(30);

        return view('admin.colores.index', compact('colores'));
    }

    public function create(): View
    {
        return view('admin.colores.form', ['color' => new Color(['estado' => true, 'codigo_hex' => '#000000'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Color::create($this->validar($request));

        return redirect()->route('admin.colores.index')->with('status', 'Color creado.');
    }

    public function edit(Color $color): View
    {
        return view('admin.colores.form', compact('color'));
    }

    public function update(Request $request, Color $color): RedirectResponse
    {
        $color->update($this->validar($request, $color));

        return redirect()->route('admin.colores.index')->with('status', 'Color actualizado.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $color->delete();

        return redirect()->route('admin.colores.index')->with('status', 'Color eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?Color $color = null): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo_hex' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'estado' => ['boolean'],
        ]);

        $datos['slug'] = Str::slug($datos['nombre']);
        $datos['estado'] = $request->boolean('estado');

        validator($datos, [
            'slug' => [Rule::unique('colores', 'slug')->ignore($color)],
        ], ['slug.unique' => 'Ya existe un color con ese nombre.'])->validate();

        return $datos;
    }
}
