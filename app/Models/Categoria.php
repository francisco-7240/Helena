<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'estado',
        'categoria_padre_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Productos pertenecientes a la categoría.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    public function subcategorias(): HasMany
    {
        return $this->hasMany(self::class, 'categoria_padre_id');
    }

    public function categoriaPadre(): BelongsTo
    {
        return $this->belongsTo(self::class, 'categoria_padre_id');
    }
}