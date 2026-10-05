<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\Color;
use App\Models\ProductoImagen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductoColor extends Model
{
    use HasFactory;

    protected $table = 'producto_colores';

    protected $fillable = [
        'producto_id',
        'color_id',
        'stock',
        'es_predeterminado',
    ];

    protected $casts = [
        'stock' => 'integer',
        'es_predeterminado' => 'boolean',
    ];

    /**
     * Producto asociado.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Color asociado.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * Imágenes correspondientes a este color.
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(ProductoImagen::class, 'producto_color_id');
    }
}