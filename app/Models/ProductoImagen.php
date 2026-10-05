<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\ProductoColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoImagen extends Model
{
    use HasFactory;

    protected $table = 'producto_imagenes';

    protected $fillable = [
        'producto_id',
        'producto_color_id',
        'imagen',
        'es_portada',
        'orden',
    ];

    protected $casts = [
        'es_portada' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Producto al que pertenece la imagen.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Color del producto al que pertenece la imagen.
     *
     * Puede ser NULL cuando la imagen es general
     * para todo el producto.
     */
    public function productoColor(): BelongsTo
    {
        return $this->belongsTo(ProductoColor::class, 'producto_color_id');
    }
}