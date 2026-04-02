<?php
$menuPersonal = [
    //EQUIPAMIENTO GENERAL
    [
        'title' => 'Equipamiento HBQP',
        'icon' => 'fas fa-laptop-house',
        'link' => '../sdo-personal/equipamiento_hbqp.php'
    ],
    //FIN EQUIPAMIENTO GENERAL

    //TICKETERA
    [
        'title' => 'Ticketera',
        'icon' => 'fas fa-book',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Tickets Recibidos',
                'icon' => 'fas fa-file',
                'link' => '../sdo-personal/solicitudes_recibidas.php'
            ],
            [
                'title' => 'Tickets Finalizados',
                'icon' => 'fas fa-check',
                'link' => '../sdo-personal/solicitudes_finalizadas.php'
            ],
            [
                'title' => 'Tickets Cancelados',
                'icon' => 'fas fa-times-circle',
                'link' => '../sdo-personal/solicitudes_canceladas.php'
            ]
        ]
    ],
    //TICKETERA

    //CONSUMOS BASICOS
    [
        'title' => 'Consumos Básicos',
        'icon' => 'fas fa-hospital',
        'link' => '../sdo-personal/consumos_basicos.php'
    ],
    //FIN CONSUMOS BASICOS
];

if ($idDepartamento == 1 || $idDepartamento == 13) {

    //REPORTES DE EVENTOS
    $menuPersonal[] = [
        'title' => 'Reportes',
        'icon' => 'fas fa-laptop-house',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Crear Reportes',
                'icon' => 'fas fa-file',
                'link' => '../sdo-personal/reportes-formulario.php'
            ],
            [
                'title' => 'Listado de Reportes',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/reportes-listado.php'
            ]
        ]
    ];
    //FIN REPORTES DE EVENTOS

    //BITACORA
    $menuPersonal[] = [
        'title' => 'Bitácora',
        'icon' => 'fas fa-clock',
        'link' => '../sdo-personal/bitacora-listado.php'
    ];
    //FIN BITACORA

    //DOCUMENTOS
    $menuPersonal[] = [
        'title' => 'Documentos',
        'icon' => 'fas fa-clipboard',
        'link' => '../sdo-personal/listar_recepcion_documento.php'
    ];
    //FIN DOCUMENTOS

    //SISTEMAS
    $menuPersonal[] = [
        'title' => 'Sistemas',
        'icon' => 'fas fa-list-ul',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Listado de Sistemas',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/sistemas-listado.php'
            ],
            [
                'title' => 'Listado de Equipos Forzados',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/forzados-listado.php'
            ],
            [
                'title' => 'Observaciones',
                'icon' => 'fas fa-list-ul',
                'link' => '../sdo-personal/sistemas-observaciones.php'
            ]
        ]
    ];
    //FIN SISTEMAS

    //Actas, CCAA, Parking y UCAS
    $menuPersonal[] = [
        'title' => 'Actas, CCAA, Parking y UCAS',
        'icon' => 'fas fa-door-closed',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Actas de Funcionarios',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/actas-listado.php'
            ],
            [
                'title' => 'Administracion Funcionarios',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/control-acceso.php'
            ],
            [
                'title' => 'Listado de Permisos de Funcionarios',
                'icon' => 'fas fa-list-ul',
                'link' => '../sdo-personal/personal-controlAcceso.php'
            ],
            [
                'title' => 'Listado de Funcionarios con Parking',
                'icon' => 'fas fa-list-ul',
                'link' => '../sdo-personal/parking-acceso.php'
            ],
            [
                'title' => 'Listado de UCAS',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-personal/ucas_listado.php'
            ],
            [
                'title' => 'Listado de Puertas en CCAA',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-personal/puertas_ccaa_listado.php'
            ],
        ]
    ];
    //FIN Actas, CCAA, Parking y UCAS

    //LEVANTAMIENTOS BMS
    $menuPersonal[] = [
        'title' => 'Levantamientos BMS',
        'icon' => 'fas fa-list-ul',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Servidor BMS',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/controladores-maestros-listado.php'
            ],
            [
                'title' => 'Controladores BMS',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/controladores-esclavos-listado.php'
            ],
            [
                'title' => 'Detalle Controladores BMS',
                'icon' => 'fas fa-book',
                'link' => '../sdo-personal/detalle_controladores_bms.php'
            ]
        ]
    ];
    //FIN LEVANTAMIENTOS BMS

    //SALAS ELECTRICAS
    $menuPersonal[] = [
        'title' => 'Salas Electricas y Comunicaci&oacute;n HBQP',
        'icon' => 'fas fa-clipboard',
        'link' => '../sdo-personal/salas_electricas_listado.php'
    ];
    //FIN SALAS ELECTRICAS
}

// PARA MOVILIZACIÓN

if ($idDepartamento == 8) {
    $menuPersonal[] = [
        'title' => 'Movilizaci&oacute;n',
        'icon' => 'fas fa-car',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Moviles',
                'icon' => 'fas fa-ambulance',
                'link' => '../sdo-personal/administrar-moviles.php'
            ],
            [
                'title' => 'Viajes',
                'icon' => 'fas fa-car',
                'link' => '../sdo-personal/viajes-1.php'
            ]
        ]
    ];
}

if($idDepartamento == 10){
    $menuPersonal[] = [
        'title' => 'Solicitudes de Compra,',
        'icon' => 'fas fa-shopping-cart',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Solicitud de Compra - SDO',
                'icon' => 'fas fa-shopping-cart',
                'link' => '../sdo-personal/soliCompra_sdo_administracion.php'
            ]
        ]
    ];
}

// FIN PARA MOVILIZACIÓN

//HOJAS TECNICAS

$rutaFormHojasTecnicas = '../sdo-personal/ordenes-formulario-general.php';
$rutaVerHojasTecnicas = '../sdo-personal/ordenes-listado-general.php';

//Formulario de Equipos Medicos
if($idDepartamento == 6){
    $rutaFormHojasTecnicas = '../sdo-personal/ordenes-formulario-general-inge-clinica.php';
}

//Formulario de Control Centralizado
if($idDepartamento == 1 || $idDepartamento == 13){
    $rutaFormHojasTecnicas = '../sdo-personal/ordenes-formulario-general2.php';
}

//Listado de Equipos de Apoyo Clinico
if($idDepartamento == 10){
    $rutaVerHojasTecnicas = '../sdo-personal/ordenes-listado-general1.php';
}

$menuPersonal[] = [
    'title' => 'Hojas Tecnicas (HT)',
    'icon' => 'fas fa-list',
    'link' => '#',
    'subitems' => [
        [
            'title' => 'Crear HT',
            'icon' => 'fas fa-pencil-alt',
            'link' => $rutaFormHojasTecnicas
        ],
        [
            'title' => 'HT Finalizadas',
            'icon' => 'fas fa-list',
            'link' => $rutaVerHojasTecnicas
        ]
    ]
];

//FIN HOJAS TECNICAS

//ORDENES DE TRABAJO
$menuPersonal[] = [
    'title' => 'Ordenes de Trabajo (OT)',
    'icon' => 'fas fa-list',
    'link' => '#',
    'subitems' => [
        [
            'title' => 'OT en Curso',
            'icon' => 'fas fa-list',
            'link' => '../sdo-personal/trabajos_general_listado.php'
        ],
        [
            'title' => 'OT Finalizadas',
            'icon' => 'fas fa-list',
            'link' => '../sdo-personal/trabajos_general_listado_finalizados.php'
        ]
    ]
];
//FIN ORDENES DE TRABAJO

//COMBUSTIBLE
if($idDepartamento == 1 || $idDepartamento == 13 || $idDepartamento == 2){
    $menuPersonal[] = [
        'title' => 'Mediciones de Combustible',
        'icon' => 'fas fa-clipboard',
        'link' => '../sdo-personal/combustible_mediciones.php'
    ];
}
//FIN COMBUSTIBLE

// Ordenar el array $menu por el título ('title')
usort($menuPersonal, function ($a, $b) {
    return strcmp($a['title'], $b['title']);
});