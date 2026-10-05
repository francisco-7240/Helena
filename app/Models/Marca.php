<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'estado',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Productos pertenecientes a la marca.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }
}
