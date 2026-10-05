<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de la tienda Helena
    |--------------------------------------------------------------------------
    */

    'nombre' => env('TIENDA_NOMBRE', 'Helena'),

    'moneda' => env('TIENDA_MONEDA', '$'),

    'email' => env('TIENDA_EMAIL', 'contacto@helena.test'),

    'telefono' => env('TIENDA_TELEFONO', ''),

    // Costo de envío fijo y monto a partir del cual el envío es gratis.
    'envio' => (float) env('TIENDA_ENVIO', 10),

    'envio_gratis_desde' => (float) env('TIENDA_ENVIO_GRATIS_DESDE', 100),

    'productos_por_pagina' => 12,

];
