<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'marca_id',
        'nombre',
        'slug',
        'sku',
        'descripcion',
        'precio',
        'precio_oferta',
        'estado',
        'destacado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'destacado' => 'boolean',
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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
        return $this->hasMany(ProductoImagen::class, 'producto_id')->orderBy('orden');
    }

    /**
     * Productos visibles en la tienda.
     */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

    /**
     * Precio que paga el cliente (considera la oferta).
     */
    public function precioFinal(): float
    {
        return $this->enOferta() ? (float) $this->precio_oferta : (float) $this->precio;
    }

    public function enOferta(): bool
    {
        return $this->precio_oferta !== null && (float) $this->precio_oferta < (float) $this->precio;
    }

    public function stockTotal(): int
    {
        return (int) $this->productoColores->sum('stock');
    }

    /**
     * URL de la imagen de portada, o null si el producto no tiene imágenes.
     */
    public function portadaUrl(): ?string
    {
        $imagen = $this->imagenes->firstWhere('es_portada', true) ?? $this->imagenes->first();

        return $imagen ? Storage::disk('public')->url($imagen->imagen) : null;
    }
}
