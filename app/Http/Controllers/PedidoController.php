<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $pedidos = $request->user()->pedidos()->withCount('items')->latest()->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Request $request, Pedido $pedido): View
    {
        abort_unless($pedido->user_id === $request->user()->id, 404);

        $pedido->load('items');

        return view('pedidos.show', compact('pedido'));
    }
}
