<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de la tienda Helena
    |--------------------------------------------------------------------------
    */

    'nombre' => env('TIENDA_NOMBRE', 'Helena'),

    'moneda' => env('TIENDA_MONEDA', '$'),

    'decimales' => (int) env('TIENDA_DECIMALES', 0),

    'eslogan' => env('TIENDA_ESLOGAN', 'Cuido de ti'),

    'email' => env('TIENDA_EMAIL', 'helenabeautyc@gmail.com'),

    'telefono' => env('TIENDA_TELEFONO', '3136451501'),

    'telefono_2' => env('TIENDA_TELEFONO_2', '3219276858'),

    'direccion' => env('TIENDA_DIRECCION', 'Calle 07 # 1-03'),

    'ciudad' => env('TIENDA_CIUDAD', 'Neiva - Huila, Colombia'),

    'redes' => [
        'instagram' => env('TIENDA_INSTAGRAM', '#'),
        'facebook' => env('TIENDA_FACEBOOK', '#'),
        'whatsapp' => env('TIENDA_WHATSAPP', '573136451501'),
    ],

    // Costo de envío fijo y monto a partir del cual el envío es gratis.
    'envio' => (float) env('TIENDA_ENVIO', 12000),

    'envio_gratis_desde' => (float) env('TIENDA_ENVIO_GRATIS_DESDE', 150000),

    'productos_por_pagina' => 12,

];
