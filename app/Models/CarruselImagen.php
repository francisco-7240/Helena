<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Imagen del carrusel de la portada. "imagen" es la ruta dentro del disco "public".
 */
class CarruselImagen extends Model
{
    use HasFactory;

    protected $table = 'carrusel_imagenes';

    protected $fillable = [
        'imagen',
        'descripcion',
        'orden',
        'activo',
    ];

    protected $casts = [
        'orden' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Imágenes visibles, en el orden en que se muestran.
     */
    public function scopeVisibles(Builder $query): Builder
    {
        return $query->where('activo', true)->orderBy('orden')->orderBy('id');
    }

    /**
     * URL pública de la imagen, relativa al dominio por el que se está navegando.
     */
    public function url(): string
    {
        return asset('storage/'.$this->imagen);
    }
}
