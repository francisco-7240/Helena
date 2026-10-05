<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $estado = $request->input('estado');

        $pedidos = Pedido::withCount('items')
            ->when(array_key_exists((string) $estado, Pedido::ESTADOS), fn ($q) => $q->where('estado', $estado))
            ->when($request->string('buscar')->trim()->value(), fn ($q, string $buscar) => $q->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                    ->orWhere('nombre', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['items', 'user']);

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(array_keys(Pedido::ESTADOS))],
        ]);

        DB::transaction(function () use ($pedido, $datos) {
            // Al cancelar se devuelve el stock; al reactivar un pedido cancelado se vuelve a descontar.
            if ($datos['estado'] === 'cancelado' && $pedido->estado !== 'cancelado') {
                foreach ($pedido->items()->with('productoColor')->get() as $item) {
                    $item->productoColor?->increment('stock', $item->cantidad);
                }
            } elseif ($pedido->estado === 'cancelado' && $datos['estado'] !== 'cancelado') {
                foreach ($pedido->items()->with('productoColor')->get() as $item) {
                    if ($item->productoColor) {
                        $item->productoColor->stock = max(0, $item->productoColor->stock - $item->cantidad);
                        $item->productoColor->save();
                    }
                }
            }

            $pedido->update($datos);
        });

        return redirect()->route('admin.pedidos.show', $pedido)->with('status', 'Estado del pedido actualizado.');
    }
}
