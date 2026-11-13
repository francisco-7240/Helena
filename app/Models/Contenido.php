<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Texto o imagen editable de una página, identificado por "pagina.seccion.campo".
 */
class Contenido extends Model
{
    protected $table = 'contenidos';

    protected $fillable = [
        'clave',
        'valor',
    ];
}
