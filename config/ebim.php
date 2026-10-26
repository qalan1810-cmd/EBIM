<?php

/*
|--------------------------------------------------------------------------
| EBIM — Escuela Boliviana Intercultural de Música
|--------------------------------------------------------------------------
| Navegación, datos de contacto y enlaces compartidos del sitio.
*/

return [

    'name' => 'EBIM',

    'full_name' => 'Escuela Boliviana Intercultural de Música',

    /*
    |--------------------------------------------------------------------------
    | Especialidades instrumentales oficiales
    |--------------------------------------------------------------------------
    | Se usan para asignar la especialidad de cada estudiante. La lista es la
    | misma que se muestra en la Oferta Académica.
    */
    'especialidades' => [
        'Charango',
        'Guitarra',
        'Piano',
        'Clarinete',
        'Saxofón',
        'Trompeta',
        'Trombón',
        'Barítono-Bajo',
        'Tuba',
        'Aerófonos andinos',
        'Batería y Percusión',
        'Canto',
    ],

    'contact' => [
        'phone' => '(591) 25212512',
        'phone_href' => 'tel:+59125212512',
        'email' => 'info@ebim.edu.bo',
        'address' => 'Carretera Oruro – Cochabamba Km 13, Zona Capachos, Oruro, Bolivia',
        'address_short' => 'Km 13 Carretera Oruro – Cochabamba, Capachos',
        'facebook' => 'https://www.facebook.com/100063251977207/',
        'instagram' => 'https://www.instagram.com/ebimoruro/',
        'tiktok' => 'https://www.tiktok.com/@ebim.s0cial.media',
        'maps' => 'https://www.google.com/maps/search/?api=1&query=Escuela+Boliviana+Intercultural+de+M%C3%BAsica+Oruro',
    ],

    /*
    | Navegación principal. Un arreglo `children` despliega un submenú.
    */
    'navigation' => [
        ['label' => 'Inicio', 'url' => '/'],
        ['label' => 'Sobre EBIM', 'url' => '/sobre-ebim'],
        ['label' => 'Oferta Académica', 'url' => '/oferta-academica'],
        ['label' => 'Inscripción', 'url' => '/inscripcion'],
        ['label' => 'Docentes', 'url' => '/docentes'],
        ['label' => 'Contacto', 'url' => '/contacto'],
    ],

    'footer' => [
        'quick_links' => [
            ['label' => 'Sobre EBIM', 'url' => '/sobre-ebim'],
            ['label' => 'Oferta Académica', 'url' => '/oferta-academica'],
            ['label' => 'Requisitos de Inscripción', 'url' => '/inscripcion'],
            ['label' => 'Plantel Docente', 'url' => '/docentes'],
            ['label' => 'Portal del Estudiante', 'url' => '/portal'],
        ],
        'legal' => [
            ['label' => 'Términos y Condiciones', 'url' => '/terminos'],
            ['label' => 'Política de Privacidad', 'url' => '/privacidad'],
        ],
    ],

    /*
    | Enlaces de la página de Inscripción.
    |
    | Cada requisito puede llevar un enlace debajo del texto. La clave
    | `coincide` es el fragmento que se busca en el requisito tal como lo
    | cargó la dirección desde el panel; así el enlace acompaña a su
    | requisito aunque cambie el orden de la lista.
    |
    | Los `tipo` posibles son:
    |   - `descarga`: abre el PDF en una pestaña nueva para imprimirlo.
    |   - `ejemplo`:  muestra la imagen del documento en una ventana
    |                 emergente, sin salir de la página.
    |   - `drive`:    abre una carpeta externa.
    */
    'inscripcion' => [

        /*
        | Carpeta de Drive con el material de estudio (teórico y práctico por
        | instrumento) para la evaluación de aptitud musical.
        |
        | ATENCIÓN: la dirección de abajo es un ENLACE DE EJEMPLO para que el
        | botón ya esté visible en la página. Cuando la dirección de la escuela
        | cree la carpeta real, basta con reemplazar esta línea por su URL de
        | Google Drive.
        */
        'material_estudio' => 'https://drive.google.com/drive/folders/1EbimMaterialAptitudMusical2026',

        /*
        | Número de cuenta de la EBIM para el depósito bancario de inscripción.
        |
        | ATENCIÓN: el número de abajo es PROVISIONAL (no corresponde a ninguna
        | cuenta real). Reemplázalo por el número oficial que entregue la escuela.
        */
        'cuenta_bancaria' => '4023187659',

        /*
        | Fecha límite de recepción de inscripciones que se muestra en la
        | pestaña Inscripción.
        |
        | ATENCIÓN: la fecha de abajo es PROVISIONAL. Reemplázala por la que
        | disponga la dirección de la escuela; con cambiarla aquí se actualiza
        | la página.
        */
        'fecha_limite' => '30 de enero de 2027',

        'recursos' => [
            [
                'coincide' => 'Solicitud de inscripción',
                'texto' => 'Descargar solicitud en PDF',
                'tipo' => 'descarga',
                'href' => '/assets/ebim/inscripcion/solicitud-inscripcion.pdf',
            ],
            [
                'coincide' => 'Certificado de nacimiento',
                'texto' => 'Ver ejemplo',
                'tipo' => 'ejemplo',
                'href' => '/assets/ebim/inscripcion/ejemplo-certificado-nacimiento.jpg',
            ],
            [
                'coincide' => 'Cédula de identidad',
                'texto' => 'Ver ejemplo',
                'tipo' => 'ejemplo',
                'href' => '/assets/ebim/inscripcion/ejemplo-cedula-identidad.jpg',
            ],
            [
                'coincide' => 'fotografías',
                'texto' => 'Ver ejemplo',
                'tipo' => 'ejemplo',
                'href' => '/assets/ebim/inscripcion/ejemplo-fotografias-4x4.jpg',
            ],
            [
                'coincide' => 'Certificado de estudios',
                'texto' => 'Ver ejemplo',
                'tipo' => 'ejemplo',
                'href' => '/assets/ebim/inscripcion/ejemplo-certificado-estudios.jpg',
            ],
            [
                'coincide' => 'Formulario de inscripción',
                'texto' => 'Descargar formulario en PDF',
                'tipo' => 'descarga',
                'href' => '/assets/ebim/inscripcion/formulario-inscripcion.pdf',
            ],
            [
                'coincide' => 'aptitud musical',
                'texto' => 'Ver material de estudio',
                'tipo' => 'drive',
                'href' => null,
            ],
        ],
    ],
];
