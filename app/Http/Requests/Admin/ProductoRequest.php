<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('nombre', '')),
            'estado' => $this->boolean('estado'),
            'destacado' => $this->boolean('destacado'),
            'variantes' => array_values(array_filter(
                $this->input('variantes', []),
                fn ($v) => is_array($v) && ($v['stock'] ?? '') !== ''
            )),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');

        return [
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'marca_id' => ['nullable', 'integer', 'exists:marcas,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('productos', 'slug')->ignore($producto)],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('productos', 'sku')->ignore($producto)],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'precio_oferta' => ['nullable', 'numeric', 'min:0', 'lt:precio'],
            'estado' => ['boolean'],
            'destacado' => ['boolean'],
            'variantes' => ['required', 'array', 'min:1'],
            'variantes.*.id' => ['nullable', 'integer'],
            'variantes.*.color_id' => ['nullable', 'integer', 'exists:colores,id', 'distinct'],
            'variantes.*.stock' => ['required', 'integer', 'min:0'],
            'imagenes' => ['nullable', 'array', 'max:10'],
            'imagenes.*' => ['image', 'max:4096'],
            'eliminar_imagenes' => ['nullable', 'array'],
            'eliminar_imagenes.*' => ['integer'],
            'portada_id' => ['nullable', 'integer'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $sinColor = collect($this->input('variantes', []))->filter(fn ($v) => empty($v['color_id']))->count();

                if ($sinColor > 1) {
                    $validator->errors()->add('variantes', 'Sólo puede haber una variante sin color.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'categoria_id' => 'categoría',
            'marca_id' => 'marca',
            'precio_oferta' => 'precio de oferta',
            'variantes' => 'variantes',
            'variantes.*.stock' => 'stock',
            'variantes.*.color_id' => 'color',
        ];
    }
}
