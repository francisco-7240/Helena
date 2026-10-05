<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Pedido;
use App\Models\ProductoColor;
use App\Services\Carrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private Carrito $carrito) {}

    public function create(Request $request): View|RedirectResponse
    {
        $lineas = $this->carrito->lineas();

        if ($lineas->isEmpty()) {
            return redirect()->route('carrito.index');
        }

        $subtotal = $this->carrito->subtotal($lineas);
        $envio = $this->carrito->envio($subtotal);

        return view('checkout.create', [
            'lineas' => $lineas,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $subtotal + $envio,
            'usuario' => $request->user(),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        if ($this->carrito->estaVacio()) {
            return redirect()->route('carrito.index');
        }

        $pedido = DB::transaction(function () use ($request) {
            $contenido = $this->carrito->contenido();

            $variantes = ProductoColor::with(['producto', 'color'])
                ->whereIn('id', array_keys($contenido))
                ->lockForUpdate()
                ->get();

            $items = [];
            foreach ($variantes as $variante) {
                $cantidad = $contenido[$variante->id];

                if (! $variante->producto?->estado || $variante->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'carrito' => "No hay stock suficiente de «{$variante->producto?->nombre}». Revisa tu carrito.",
                    ]);
                }

                $precio = $variante->producto->precioFinal();
                $items[] = [
                    'producto_id' => $variante->producto_id,
                    'producto_color_id' => $variante->id,
                    'nombre_producto' => $variante->producto->nombre,
                    'nombre_color' => $variante->color?->nombre,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => round($precio * $cantidad, 2),
                ];

                $variante->decrement('stock', $cantidad);
            }

            if ($items === []) {
                throw ValidationException::withMessages(['carrito' => 'Tu carrito está vacío.']);
            }

            $subtotal = round(array_sum(array_column($items, 'subtotal')), 2);
            $envio = $this->carrito->envio($subtotal);

            $pedido = Pedido::create([
                ...$request->validated(),
                'user_id' => $request->user()?->id,
                'codigo' => Pedido::generarCodigo(),
                'subtotal' => $subtotal,
                'envio' => $envio,
                'total' => $subtotal + $envio,
                'estado' => 'pendiente',
            ]);

            $pedido->items()->createMany($items);

            return $pedido;
        });

        $this->carrito->vaciar();
        $request->session()->put('pedido_confirmado', $pedido->codigo);

        return redirect()->route('checkout.confirmacion', $pedido);
    }

    public function confirmacion(Request $request, Pedido $pedido): View
    {
        $esDelUsuario = $request->user() && $pedido->user_id === $request->user()->id;
        abort_unless($esDelUsuario || $request->session()->get('pedido_confirmado') === $pedido->codigo, 404);

        $pedido->load('items');

        return view('checkout.confirmacion', compact('pedido'));
    }
}
