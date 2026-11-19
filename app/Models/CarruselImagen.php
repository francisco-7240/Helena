<?php

namespace App\Models;

use App\Support\ArchivoSubido;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Banner del carrusel de la portada. "imagen" e "imagen_movil" son rutas dentro del disco "public".
 */
class CarruselImagen extends Model
{
    use HasFactory;

    protected $table = 'carrusel_imagenes';

    protected $fillable = [
        'imagen',
        'imagen_movil',
        'descripcion',
        'enlace',
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
        return ArchivoSubido::url($this->imagen);
    }

    /**
     * URL de la versión para celular, o la imagen principal si no tiene.
     */
    public function urlMovil(): string
    {
        return $this->imagen_movil ? ArchivoSubido::url($this->imagen_movil) : $this->url();
    }
}
