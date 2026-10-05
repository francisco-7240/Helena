<?php

namespace App\Http\Controllers;

use App\Models\ProductoColor;
use App\Services\Carrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function __construct(private Carrito $carrito) {}

    public function index(): View
    {
        $lineas = $this->carrito->lineas();
        $subtotal = $this->carrito->subtotal($lineas);
        $envio = $this->carrito->envio($subtotal);

        return view('carrito.index', [
            'lineas' => $lineas,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $subtotal + $envio,
        ]);
    }

    public function agregar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'producto_color_id' => ['required', 'integer', 'exists:producto_colores,id'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $variante = ProductoColor::with('producto')->findOrFail($datos['producto_color_id']);

        if (! $variante->producto?->estado || $variante->stock < 1) {
            return back()->withErrors(['producto_color_id' => 'Este producto no está disponible.']);
        }

        $this->carrito->agregar($variante, $datos['cantidad']);

        return redirect()->route('carrito.index')->with('status', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, int $variante): RedirectResponse
    {
        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->carrito->actualizar($variante, $datos['cantidad']);

        return redirect()->route('carrito.index')->with('status', 'Carrito actualizado.');
    }

    public function eliminar(int $variante): RedirectResponse
    {
        $this->carrito->eliminar($variante);

        return redirect()->route('carrito.index')->with('status', 'Producto eliminado del carrito.');
    }

    public function vaciar(): RedirectResponse
    {
        $this->carrito->vaciar();

        return redirect()->route('carrito.index')->with('status', 'Carrito vaciado.');
    }
}
