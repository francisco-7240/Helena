<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'ventasTotales' => Pedido::whereNotIn('estado', ['pendiente', 'cancelado'])->sum('total'),
            'pedidosPendientes' => Pedido::where('estado', 'pendiente')->count(),
            'productosActivos' => Producto::activos()->count(),
            'mensajesPendientes' => Contacto::where('estado', 'pendiente')->count(),
            'ultimosPedidos' => Pedido::latest()->take(8)->get(),
            'pocoStock' => ProductoColor::with(['producto', 'color'])->where('stock', '<=', 3)->orderBy('stock')->take(8)->get(),
        ]);
    }
}
