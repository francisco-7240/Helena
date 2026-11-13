<?php

/*
|--------------------------------------------------------------------------
| Contenido editable del sitio
|--------------------------------------------------------------------------
|
| Cada página tiene secciones y cada sección tiene campos. El panel de
| administración genera sus formularios a partir de este archivo y las
| vistas leen cada valor con contenido('pagina.seccion.campo').
|
| Tipos de campo:
|   texto   - una línea
|   parrafo - varias líneas (cada salto de línea se respeta; una línea en
|             blanco separa párrafos donde la vista lo admite)
|   imagen  - ruta pública de la imagen (se reemplaza subiendo un archivo)
|   numero  - valor numérico
|
| "defecto" es el valor que se muestra mientras el administrador no lo cambie.
| En la página "general", "config" indica qué valor de config('tienda') reemplaza.
|
*/

$texto = fn (string $etiqueta, ?string $defecto = null): array => ['etiqueta' => $etiqueta, 'tipo' => 'texto', 'defecto' => $defecto];
$parrafo = fn (string $etiqueta, ?string $defecto = null): array => ['etiqueta' => $etiqueta, 'tipo' => 'parrafo', 'defecto' => $defecto];
$imagen = fn (string $etiqueta, ?string $defecto = null): array => ['etiqueta' => $etiqueta, 'tipo' => 'imagen', 'defecto' => $defecto];

/**
 * Genera los campos de una lista de elementos (tarjetas, pasos, principios...).
 *
 * @param  array<int, array<string, string>>  $elementos
 * @param  array<string, string>  $tipos  campo => tipo
 */
$lista = function (string $nombre, array $elementos, array $tipos) use ($texto, $parrafo, $imagen): array {
    $campos = [];
    $etiquetas = ['titulo' => 'título', 'texto' => 'texto', 'imagen' => 'imagen'];

    foreach ($elementos as $indice => $elemento) {
        $numero = $indice + 1;

        foreach ($tipos as $campo => $tipo) {
            $etiqueta = ucfirst($nombre)." {$numero}: ".($etiquetas[$campo] ?? $campo);
            $campos["{$campo}_{$numero}"] = match ($tipo) {
                'parrafo' => $parrafo($etiqueta, $elemento[$campo] ?? null),
                'imagen' => $imagen($etiqueta, $elemento[$campo] ?? null),
                default => $texto($etiqueta, $elemento[$campo] ?? null),
            };
        }
    }

    return $campos;
};

return [

    'inicio' => [
        'titulo' => 'Inicio',
        'ruta' => 'inicio',
        'secciones' => [
            'hero' => [
                'titulo' => 'Portada principal',
                'campos' => [
                    'titulo' => $parrafo('Título', "Me\ncuido,"),
                    'destacado' => $texto('Título destacado (en verde)', 'me amo.'),
                    'texto' => $texto('Texto', 'Cuidado capilar consciente para volver a ti.'),
                    'boton' => $texto('Texto del botón', 'Conoce el cuidado capilar'),
                    'imagen' => $imagen('Imagen de productos', 'storage/recursos/212Recurso 2.png'),
                ],
            ],
            'que_es' => [
                'titulo' => '¿Qué es Helena?',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'El cuidado empieza en ti'),
                    'titulo' => $parrafo('Título', '¿Qué es'),
                    'destacado' => $texto('Título destacado (en verde)', 'Helena?'),
                    'texto' => $parrafo('Texto', 'Un espacio para recordar que cuidarte también es una forma de escucharte. Creamos rituales honestos, efectivos y sensibles.'),
                    'boton' => $texto('Texto del botón', 'Conoce Helena'),
                    'imagen' => $imagen('Imagen', 'storage/recursos/212Recurso 4.png'),
                ],
            ],
            'autocuidado' => [
                'titulo' => 'Autocuidado',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Una mirada completa'),
                    'titulo' => $texto('Título', 'Autocuidado'),
                    ...$lista('tarjeta', [
                        ['titulo' => 'Cuerpo', 'texto' => 'Lo que habitas. Pequeños rituales para volver a sentirte presente.', 'imagen' => 'storage/recursos/cuerpo.png'],
                        ['titulo' => 'Mente', 'texto' => 'Lo que sientes y piensas. Una pausa para hacer espacio.', 'imagen' => 'storage/recursos/mente.png'],
                        ['titulo' => 'Espíritu', 'texto' => 'Lo que te conecta. Cuidar también es darte permiso de ser.', 'imagen' => 'storage/recursos/espiritu.png'],
                    ], ['titulo' => 'texto', 'texto' => 'parrafo', 'imagen' => 'imagen']),
                ],
            ],
            'capilar' => [
                'titulo' => 'Cuidado capilar (franja verde)',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'El ritual Helena'),
                    'titulo' => $parrafo('Título', 'Cuidado'),
                    'destacado' => $texto('Título destacado (cursiva)', 'capilar'),
                    'texto' => $parrafo('Texto', 'Fórmulas pensadas para cuidar tu cabello sin olvidar lo que tu cuerpo necesita.'),
                    'boton' => $texto('Texto del botón', 'Conoce Helena'),
                    ...$lista('paso', [
                        ['titulo' => 'Limpiar'], ['titulo' => 'Acondicionar'], ['titulo' => 'Nutrir'], ['titulo' => 'Transformar'],
                    ], ['titulo' => 'texto']),
                ],
            ],
            'productos' => [
                'titulo' => 'Productos',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Productos'),
                    'titulo' => $parrafo('Título', 'Cuida tu cabello'),
                    'destacado' => $texto('Título destacado (en verde)', 'a tu manera.'),
                    'boton' => $texto('Texto del botón', 'Ver toda la tienda'),
                ],
            ],
            'manifiesto' => [
                'titulo' => 'Manifiesto (franja rosa)',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Manifiesto de marca'),
                    'titulo' => $parrafo('Título', "Me cuido.\nMe escucho."),
                    'destacado' => $texto('Título destacado (en verde)', 'Me amo.'),
                    'texto' => $parrafo('Texto', 'Creemos que el autocuidado comienza cuando elegimos cuidarnos de manera consciente, sin exigencias y con mucha ternura.'),
                    'boton' => $texto('Texto del botón', 'Conoce Helena'),
                ],
            ],
        ],
    ],

    'nosotros' => [
        'titulo' => 'Nosotros',
        'ruta' => 'nosotros',
        'secciones' => [
            'encabezado' => [
                'titulo' => 'Encabezado (franja rosa)',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'La historia Helena'),
                    'titulo' => $parrafo('Título', 'Nacimos para'),
                    'destacado' => $texto('Título destacado (en verde)', 'volver a ti.'),
                ],
            ],
            'historia' => [
                'titulo' => 'Sobre Helena Baüt',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Sobre Helena Baüt'),
                    'titulo' => $parrafo('Título', 'El cuidado'),
                    'destacado' => $texto('Título destacado (en verde)', 'consciente'),
                    'texto' => $parrafo('Texto (deja una línea en blanco entre párrafos)', "Helena Baüt nació de una necesidad personal: encontrar productos de cuidado elaborados con buenos ingredientes y con la menor cantidad posible de componentes sintéticos.\n\nComenzamos con jabones y shampoo en barra. Con el tiempo, escuchamos a nuestras clientas y fuimos construyendo nuestra línea de cuidado capilar.\n\nHoy seguimos haciendo lo mismo: crear productos de buena calidad y elegir cuidadosamente aquello que ponemos en ellos."),
                    'imagen' => $imagen('Imagen', 'storage/recursos/nosotros-historia.png'),
                ],
            ],
            'filosofia' => [
                'titulo' => 'Nuestra filosofía',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestra filosofía'),
                    'titulo' => $parrafo('Título', 'Cuidarte es una forma de'),
                    'destacado' => $texto('Título destacado (en verde, en la misma línea)', 'amarte.'),
                    'texto' => $parrafo('Texto', 'Para nosotras, el autocuidado empieza cuando decides escucharte, reconocer lo que necesitas y elegir conscientemente cómo cuidarte.'),
                    'firma' => $texto('Frase en negrita', 'Me cuido. Me amo.'),
                    ...$lista('principio', [
                        ['titulo' => 'Ingredientes conscientes', 'texto' => 'Priorizamos ingredientes de origen natural y vegetal y procuramos reducir al máximo el uso de componentes sintéticos.'],
                        ['titulo' => 'Calidad sin atajos', 'texto' => 'Buscamos desarrollar productos efectivos, agradables de usar y elaborados con cuidado.'],
                        ['titulo' => 'Transparencia', 'texto' => 'Preferimos hablar con honestidad sobre lo que nuestros productos son y lo que pueden ofrecer.'],
                        ['titulo' => 'Coherencia', 'texto' => 'Nuestros productos deben reflejar los principios en los que creemos.'],
                    ], ['titulo' => 'parrafo', 'texto' => 'parrafo']),
                ],
            ],
            'mas_alla' => [
                'titulo' => 'Más allá del cabello',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Más allá del cabello'),
                    'texto' => $parrafo('Texto (deja una línea en blanco entre párrafos)', "Hoy Helena Baüt es una marca de cuidado capilar. Pero nuestra visión es más amplia.\n\nQueremos construir un espacio donde podamos hablar de cuerpo, mente y espíritu, y explorar juntas distintas formas de autocuidado."),
                    'titulo' => $parrafo('Título', 'Cuidarnos.'),
                    'destacado' => $parrafo('Título destacado (en verde)', "Escucharnos.\nVolver a nosotras."),
                    'firma' => $texto('Frase en negrita', 'Helena Baüt · Me cuido. Me amo.'),
                    'boton' => $texto('Texto del botón', 'Conoce el autocuidado'),
                    'imagen' => $imagen('Imagen', 'storage/recursos/nosotros-mas-alla.png'),
                ],
            ],
        ],
    ],

    'autocuidado' => [
        'titulo' => 'Autocuidado',
        'ruta' => 'autocuidado',
        'secciones' => [
            'hero' => [
                'titulo' => 'Banner principal',
                'campos' => [
                    'imagen' => $imagen('Imagen del banner (a todo el ancho, sin texto)', 'storage/Autocuidado/Belleza serena entre luz y hojas.png'),
                    'titulo' => $parrafo('Título', "Me cuido,\nme escucho,"),
                    'destacado' => $texto('Título destacado (en rosa)', 'me amo.'),
                    'texto' => $parrafo('Texto', 'Un espacio para reconectar contigo, equilibrar tu bienestar y hacer del autocuidado un ritual de vida.'),
                    'boton' => $texto('Texto del botón', 'Descubre el autocuidado'),
                ],
            ],
            'que_es' => [
                'titulo' => '¿Qué es el autocuidado?',
                'campos' => [
                    'titulo' => $parrafo('Título', "¿Qué es el\nautocuidado?"),
                    'texto' => $parrafo('Texto', 'Es la práctica consciente de atender tus necesidades físicas, emocionales y espirituales para sentirte bien, en equilibrio y en armonía contigo y con tu entorno.'),
                    'imagen' => $imagen('Imagen', 'storage/Autocuidado/ritual_vela_y_plantas.png'),
                ],
            ],
            'pilares' => [
                'titulo' => 'Cuerpo, mente y espíritu',
                'campos' => $lista('tarjeta', [
                    ['titulo' => 'Cuerpo', 'texto' => 'Escucha sus necesidades, nutre tu energía y cuídalo con hábitos que te hagan sentir bien.', 'imagen' => 'storage/Autocuidado/cuerpo_bienestar.png'],
                    ['titulo' => 'Mente', 'texto' => 'Calma el ruido, ordena tus ideas y cultiva una mente más serena, clara y positiva.', 'imagen' => 'storage/Autocuidado/mente_escritura_y_te.png'],
                    ['titulo' => 'Espíritu', 'texto' => 'Conecta con tu esencia, cultiva la gratitud y descubre aquello que te da propósito y bienestar.', 'imagen' => 'storage/Autocuidado/espiritu_meditacion.png'],
                ], ['titulo' => 'texto', 'texto' => 'parrafo', 'imagen' => 'imagen']),
            ],
            'filosofia' => [
                'titulo' => 'Nuestra filosofía',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestra filosofía'),
                    'titulo' => $parrafo('Título', 'Cuidarte es una forma de amarte.'),
                    ...$lista('principio', [
                        ['titulo' => "Ingredientes\nconscientes", 'texto' => 'Elegimos lo mejor de la naturaleza, con respeto por tu piel y por el planeta.'],
                        ['titulo' => "Calidad\nsin atajos", 'texto' => 'Fórmulas efectivas, seguras y cuidadosamente desarrolladas.'],
                        ['titulo' => 'Transparencia', 'texto' => 'Te contamos con claridad qué usamos, por qué y cómo te beneficia.'],
                        ['titulo' => 'Coherencia', 'texto' => 'Hacemos lo que creemos: bienestar real, belleza consciente y un impacto positivo.'],
                    ], ['titulo' => 'parrafo', 'texto' => 'parrafo']),
                ],
            ],
            'manifiesto' => [
                'titulo' => 'Nuestro manifiesto',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestro manifiesto'),
                    'titulo' => $parrafo('Título', "Bienestar integral\npara"),
                    'destacado' => $texto('Palabra destacada (en rosa, en la misma línea)', 'una'),
                    'cierre' => $texto('Final del título', 'vida más plena.'),
                    'texto' => $parrafo('Texto', 'Creemos en un autocuidado que va más allá de la piel: una forma de vivir más consciente, más presente y más feliz. Porque cuando te cuidas de verdad, todo a tu alrededor también florece.'),
                    'boton' => $texto('Texto del botón', 'Comienza tu ritual de bienestar'),
                    'imagen' => $imagen('Imagen', 'storage/Cuidado_capilar/flor_rosada_manifiesto.png'),
                ],
            ],
        ],
    ],

    'cuidado_capilar' => [
        'titulo' => 'Cuidado capilar',
        'ruta' => 'cuidado-capilar',
        'secciones' => [
            'hero' => [
                'titulo' => 'Banner principal',
                'campos' => [
                    'imagen' => $imagen('Imagen del banner (a todo el ancho, sin texto)', 'storage/Cuidado_capilar/Belleza natural en calma spa.png'),
                    'titulo' => $parrafo('Título', 'Cuidado capilar'),
                    'destacado' => $texto('Título destacado (cursiva)', 'consciente.'),
                    'texto' => $parrafo('Texto', 'Rituales y productos naturales para un cabello más fuerte, saludable y lleno de vida.'),
                    'boton' => $texto('Texto del botón', 'Descubre el cuidado capilar'),
                ],
            ],
            'que_es' => [
                'titulo' => '¿Qué es el cuidado capilar?',
                'campos' => [
                    'titulo' => $parrafo('Título', '¿Qué es el'),
                    'destacado' => $texto('Título destacado (cursiva)', 'cuidado capilar?'),
                    'texto' => $parrafo('Texto', 'Es un conjunto de hábitos, productos y rituales que limpian, nutren, fortalecen y equilibran el cabello, respetando su naturaleza y sus necesidades únicas.'),
                    'imagen' => $imagen('Imagen', 'storage/Cuidado_capilar/cabello_natural_espalda.png'),
                ],
            ],
            'rutina' => [
                'titulo' => 'Tu rutina capilar',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Tu rutina capilar'),
                    'titulo' => $parrafo('Título', 'Un ritual completo para un cabello saludable.'),
                    ...$lista('paso', [
                        ['titulo' => 'Limpiar', 'texto' => 'Purifica el cuero cabelludo, elimina impurezas y prepara el cabello.', 'imagen' => 'storage/Cuidado_capilar/rutina_limpiar.png'],
                        ['titulo' => 'Acondicionar', 'texto' => 'Suaviza, desenreda y recupera la hidratación natural.', 'imagen' => 'storage/Cuidado_capilar/rutina_acondicionar.png'],
                        ['titulo' => 'Nutrir', 'texto' => 'Aporta nutrientes esenciales que fortalecen y revitalizan desde la raíz.', 'imagen' => 'storage/Cuidado_capilar/rutina_nutrir.png'],
                        ['titulo' => 'Transformar', 'texto' => 'Mejora visiblemente la textura, el brillo y la fuerza de tu cabello.', 'imagen' => 'storage/Cuidado_capilar/rutina_transformar.png'],
                    ], ['titulo' => 'texto', 'texto' => 'parrafo', 'imagen' => 'imagen']),
                ],
            ],
            'productos' => [
                'titulo' => 'Nuestros productos',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestros productos'),
                    'titulo' => $parrafo('Título', 'Fórmulas naturales para cada necesidad.'),
                ],
            ],
            'filosofia' => [
                'titulo' => 'Nuestra filosofía',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestra filosofía'),
                    'titulo' => $parrafo('Título', 'Cuidado capilar que respeta tu esencia.'),
                    ...$lista('principio', [
                        ['titulo' => "Ingredientes\nconscientes", 'texto' => 'De origen natural y seleccionados por sus beneficios reales para tu cabello.'],
                        ['titulo' => "Hidratación\nprofunda", 'texto' => 'Restaura la humedad, la suavidad y la elasticidad.'],
                        ['titulo' => "Brillo\nnatural", 'texto' => 'Realza la belleza de tu cabello, sin residuos ni peso.'],
                        ['titulo' => "Fórmulas\nsuaves", 'texto' => 'Efectivas y respetuosas, ideales para el uso diario y para todo tipo de cabello.'],
                    ], ['titulo' => 'parrafo', 'texto' => 'parrafo']),
                ],
            ],
            'manifiesto' => [
                'titulo' => 'Nuestro manifiesto',
                'campos' => [
                    'etiqueta' => $texto('Texto pequeño superior', 'Nuestro manifiesto'),
                    'titulo' => $parrafo('Título', "Ama tu cabello\nde forma consciente."),
                    'texto' => $parrafo('Texto', 'Cuídalo, escúchalo y celebra su naturaleza. Creemos en un cuidado capilar real, natural y respetuoso, que te ayude a sentirte bien por dentro y por fuera.'),
                    'boton' => $texto('Texto del botón', 'Explora nuestros productos'),
                    'imagen' => $imagen('Imagen', 'storage/Cuidado_capilar/flor_rosada_manifiesto.png'),
                ],
            ],
        ],
    ],

    'general' => [
        'titulo' => 'Configuración general',
        'ruta' => null,
        'secciones' => [
            'marca' => [
                'titulo' => 'Marca',
                'campos' => [
                    'eslogan' => ['etiqueta' => 'Eslogan (debajo del logo)', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.eslogan'],
                    'descripcion' => ['etiqueta' => 'Frase del pie de página', 'tipo' => 'texto', 'defecto' => 'Cuidado consciente para tu cabello.'],
                ],
            ],
            'contacto' => [
                'titulo' => 'Datos de contacto',
                'campos' => [
                    'email' => ['etiqueta' => 'Correo', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.email'],
                    'telefono' => ['etiqueta' => 'Teléfono', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.telefono'],
                    'telefono_2' => ['etiqueta' => 'Teléfono 2', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.telefono_2'],
                    'direccion' => ['etiqueta' => 'Dirección', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.direccion'],
                    'ciudad' => ['etiqueta' => 'Ciudad', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.ciudad'],
                ],
            ],
            'redes' => [
                'titulo' => 'Redes sociales',
                'campos' => [
                    'instagram' => ['etiqueta' => 'Enlace de Instagram', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.redes.instagram'],
                    'facebook' => ['etiqueta' => 'Enlace de Facebook', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.redes.facebook'],
                    'whatsapp' => ['etiqueta' => 'Número de WhatsApp (con indicativo, sin +)', 'tipo' => 'texto', 'defecto' => null, 'config' => 'tienda.redes.whatsapp'],
                ],
            ],
            'envios' => [
                'titulo' => 'Envíos',
                'campos' => [
                    'envio' => ['etiqueta' => 'Costo de envío', 'tipo' => 'numero', 'defecto' => null, 'config' => 'tienda.envio'],
                    'envio_gratis_desde' => ['etiqueta' => 'Envío gratis desde', 'tipo' => 'numero', 'defecto' => null, 'config' => 'tienda.envio_gratis_desde'],
                ],
            ],
        ],
    ],

];
