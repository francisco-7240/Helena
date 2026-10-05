<?php

namespace App\Models;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\ProductoColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'marca_id',
        'nombre',
        'slug',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Categoría del producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Marca del producto.
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    /**
     * Colores disponibles para el producto.
     */
    public function productoColores(): HasMany
    {
        return $this->hasMany(ProductoColor::class, 'producto_id');
    }

    /**
     * Imágenes del producto.
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(ProductoImagen::class, 'producto_id');
    }
}