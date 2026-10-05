<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactoController extends Controller
{
    public function index(): View
    {
        $contactos = Contacto::latest()->paginate(20);

        return view('admin.contactos.index', compact('contactos'));
    }

    public function update(Request $request, Contacto $contacto): RedirectResponse
    {
        $contacto->update($request->validate([
            'estado' => ['required', 'in:pendiente,respondido,archivado'],
        ]));

        return back()->with('status', 'Mensaje actualizado.');
    }

    public function destroy(Contacto $contacto): RedirectResponse
    {
        $contacto->delete();

        return back()->with('status', 'Mensaje eliminado.');
    }
}
