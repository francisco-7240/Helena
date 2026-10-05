<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TiendaController;
use Illuminate\Support\Facades\Route;

// Tienda pública
Route::get('/', [TiendaController::class, 'inicio'])->name('inicio');
Route::get('/tienda', [TiendaController::class, 'catalogo'])->name('tienda.catalogo');
Route::get('/categoria/{categoria}', [TiendaController::class, 'catalogo'])->name('tienda.categoria');
Route::get('/producto/{producto}', [TiendaController::class, 'producto'])->name('tienda.producto');

// Carrito
Route::prefix('carrito')->name('carrito.')->controller(CarritoController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'agregar')->name('agregar');
    Route::patch('/{variante}', 'actualizar')->whereNumber('variante')->name('actualizar');
    Route::delete('/{variante}', 'eliminar')->whereNumber('variante')->name('eliminar');
    Route::delete('/', 'vaciar')->name('vaciar');
});

// Checkout
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
Route::get('/pedido/{pedido}/confirmacion', [CheckoutController::class, 'confirmacion'])->name('checkout.confirmacion');

// Contacto
Route::get('/contacto', [ContactoController::class, 'create'])->name('contacto.create');
Route::post('/contacto', [ContactoController::class, 'store'])->middleware('throttle:5,1')->name('contacto.store');

// Área de clientes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route(auth()->user()->esAdmin() ? 'admin.dashboard' : 'pedidos.index'))->name('dashboard');
    Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel de administración
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::resource('categorias', Admin\CategoriaController::class)->except('show')->parameters(['categorias' => 'categoria']);
    Route::resource('marcas', Admin\MarcaController::class)->except('show')->parameters(['marcas' => 'marca']);
    Route::resource('colores', Admin\ColorController::class)->except('show')->parameters(['colores' => 'color']);
    Route::resource('productos', Admin\ProductoController::class)->except('show')->parameters(['productos' => 'producto']);
    Route::resource('pedidos', Admin\PedidoController::class)->only(['index', 'show', 'update'])->parameters(['pedidos' => 'pedido']);
    Route::resource('contactos', Admin\ContactoController::class)->only(['index', 'update', 'destroy'])->parameters(['contactos' => 'contacto']);
});

require __DIR__.'/auth.php';
