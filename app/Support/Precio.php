<?php

namespace App\Support;

class Precio
{
    public static function formato(float|string|null $valor): string
    {
        return config('tienda.moneda').' '.number_format((float) $valor, 2, ',', '.');
    }
}
