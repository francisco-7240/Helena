<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colores';

    protected $fillable = [
        'nombre',
        'slug',
        'codigo_hex',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relación con producto_colores.
     */
    public function productoColores(): HasMany
    {
        return $this->hasMany(ProductoColor::class, 'color_id');
    }

    /**
     * Productos que utilizan este color.
     */
    public function productos(): HasManyThrough
    {
        return $this->hasManyThrough(
            Producto::class,
            ProductoColor::class,
            'color_id',
            'id',
            'id',
            'producto_id'
        );
    }
}