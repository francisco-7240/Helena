<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductoRequest;
use App\Models\Categoria;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        $productos = Producto::with(['categoria', 'marca', 'productoColores', 'imagenes'])
            ->when($request->string('buscar')->trim()->value(), fn ($q, string $buscar) => $q->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")->orWhere('sku', 'like', "%{$buscar}%");
            }))
            ->when($request->integer('categoria'), fn ($q, int $categoria) => $q->where('categoria_id', $categoria))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.productos.index', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->formulario(new Producto(['estado' => true]));
    }

    public function store(ProductoRequest $request): RedirectResponse
    {
        $producto = DB::transaction(function () use ($request) {
            $producto = Producto::create($this->datosProducto($request));
            $this->sincronizarVariantes($producto, $request->validated('variantes'));
            $this->guardarImagenes($producto, $request);

            return $producto;
        });

        return redirect()->route('admin.productos.edit', $producto)->with('status', 'Producto creado.');
    }

    public function edit(Producto $producto): View
    {
        $producto->load(['productoColores.color', 'imagenes']);

        return $this->formulario($producto);
    }

    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        DB::transaction(function () use ($request, $producto) {
            $producto->update($this->datosProducto($request));
            $this->sincronizarVariantes($producto, $request->validated('variantes'));
            $this->guardarImagenes($producto, $request);
        });

        return redirect()->route('admin.productos.edit', $producto)->with('status', 'Producto actualizado.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $rutas = $producto->imagenes()->pluck('imagen')->all();

        $producto->delete();
        Storage::disk('public')->delete($rutas);

        return redirect()->route('admin.productos.index')->with('status', 'Producto eliminado.');
    }

    private function formulario(Producto $producto): View
    {
        return view('admin.productos.form', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'marcas' => Marca::orderBy('nombre')->get(),
            'colores' => Color::orderBy('nombre')->get(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function datosProducto(ProductoRequest $request): array
    {
        return Arr::only($request->validated(), [
            'categoria_id', 'marca_id', 'nombre', 'slug', 'sku', 'descripcion',
            'precio', 'precio_oferta', 'estado', 'destacado',
        ]);
    }

    /**
     * @param  array<int, array{id?: int|null, color_id?: int|null, stock: int}>  $variantes
     */
    private function sincronizarVariantes(Producto $producto, array $variantes): void
    {
        $conservar = [];

        foreach (array_values($variantes) as $i => $datos) {
            $variante = $producto->productoColores()->updateOrCreate(
                ['color_id' => $datos['color_id'] ?? null],
                ['stock' => $datos['stock'], 'es_predeterminado' => $i === 0],
            );
            $conservar[] = $variante->id;
        }

        $producto->productoColores()->whereNotIn('id', $conservar)->delete();
    }

    private function guardarImagenes(Producto $producto, ProductoRequest $request): void
    {
        $eliminar = $producto->imagenes()->whereIn('id', $request->validated('eliminar_imagenes', []))->get();
        Storage::disk('public')->delete($eliminar->pluck('imagen')->all());
        $producto->imagenes()->whereIn('id', $eliminar->modelKeys())->delete();

        $orden = (int) $producto->imagenes()->max('orden');
        foreach ($request->file('imagenes', []) as $archivo) {
            $producto->imagenes()->create([
                'imagen' => $archivo->store("productos/{$producto->id}", 'public'),
                'orden' => ++$orden,
            ]);
        }

        if ($portada = $request->validated('portada_id')) {
            if ($producto->imagenes()->whereKey($portada)->exists()) {
                $producto->imagenes()->update(['es_portada' => false]);
                $producto->imagenes()->whereKey($portada)->update(['es_portada' => true]);
            }
        }

        if (! $producto->imagenes()->where('es_portada', true)->exists()) {
            $producto->imagenes()->orderBy('orden')->first()?->update(['es_portada' => true]);
        }
    }
}
