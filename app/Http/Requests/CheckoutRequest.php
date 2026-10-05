<?php

namespace App\Http\Requests;

use App\Models\Pedido;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'ciudad' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:10'],
            'notas' => ['nullable', 'string', 'max:1000'],
            'metodo_pago' => ['required', Rule::in(array_keys(Pedido::METODOS_PAGO))],
        ];
    }
}
