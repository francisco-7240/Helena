<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TiendaController extends Controller
{
    public function inicio(): View
    {
        $destacados = Producto::activos()
            ->where('destacado', true)
            ->with(['imagenes', 'categoria'])
            ->latest()
            ->take(8)
            ->get();

        $novedades = Producto::activos()
            ->with(['imagenes', 'categoria'])
            ->latest()
            ->take(8)
            ->get();

        $categorias = Categoria::activas()->whereNull('categoria_padre_id')->orderBy('nombre')->get();

        return view('tienda.inicio', compact('destacados', 'novedades', 'categorias'));
    }

    public function catalogo(Request $request, ?Categoria $categoria = null): View
    {
        abort_if($categoria && ! $categoria->estado, 404);

        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'marca' => ['nullable', 'string', 'exists:marcas,slug'],
            'color' => ['nullable', 'string', 'exists:colores,slug'],
            'orden' => ['nullable', 'in:recientes,precio_asc,precio_desc,nombre'],
        ]);

        $productos = Producto::activos()
            ->with(['imagenes', 'categoria', 'marca'])
            ->when($categoria, function ($query) use ($categoria) {
                $ids = $categoria->subcategorias()->pluck('id')->push($categoria->id);
                $query->whereIn('categoria_id', $ids);
            })
            ->when($filtros['buscar'] ?? null, function ($query, string $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->when($filtros['marca'] ?? null, fn ($query, string $marca) => $query->whereHas('marca', fn ($q) => $q->where('slug', $marca)))
            ->when($filtros['color'] ?? null, fn ($query, string $color) => $query->whereHas('productoColores.color', fn ($q) => $q->where('slug', $color)))
            ->when($filtros['orden'] ?? 'recientes', function ($query, string $orden) {
                $precio = 'COALESCE(precio_oferta, precio)';

                match ($orden) {
                    'precio_asc' => $query->orderByRaw("{$precio} asc"),
                    'precio_desc' => $query->orderByRaw("{$precio} desc"),
                    'nombre' => $query->orderBy('nombre'),
                    default => $query->latest(),
                };
            })
            ->paginate(config('tienda.productos_por_pagina'))
            ->withQueryString();

        return view('tienda.catalogo', [
            'productos' => $productos,
            'categoriaActual' => $categoria,
            'categorias' => Categoria::activas()->whereNull('categoria_padre_id')->orderBy('nombre')->get(),
            'marcas' => Marca::activas()->orderBy('nombre')->get(),
            'colores' => Color::where('estado', true)->orderBy('nombre')->get(),
            'filtros' => $filtros,
        ]);
    }

    public function producto(Producto $producto): View
    {
        abort_unless($producto->estado, 404);

        $producto->load(['categoria', 'marca', 'imagenes', 'productoColores.color']);

        $relacionados = Producto::activos()
            ->where('categoria_id', $producto->categoria_id)
            ->whereKeyNot($producto->id)
            ->with('imagenes')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('tienda.producto', compact('producto', 'relacionados'));
    }
}
