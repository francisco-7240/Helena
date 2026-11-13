<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Listado de usuarios y asignación del rol de administrador.
 */
class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $usuarios = User::with('roles')
            ->withCount('pedidos')
            ->when($request->string('buscar')->trim()->value(), fn ($q, string $buscar) => $q->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")->orWhere('email', 'like', "%{$buscar}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'admin' => ['required', 'boolean'],
        ]);

        if ($usuario->is($request->user()) && ! $datos['admin']) {
            return back()->withErrors(['admin' => 'No puedes quitarte a ti mismo el rol de administrador.']);
        }

        Role::findOrCreate('admin');

        if ($datos['admin']) {
            $usuario->assignRole('admin');
        } else {
            $usuario->removeRole('admin');
        }

        return back()->with('status', $datos['admin'] ? "{$usuario->name} ahora es administrador." : "{$usuario->name} ya no es administrador.");
    }
}
