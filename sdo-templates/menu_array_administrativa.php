<?php
$menuAdmin = [
    [
        'title' => 'Actas Funcionarios',
        'icon' => 'fas fa-users',
        'link' => '../sdo-admtec/actas-listado.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Bitacora',
        'icon' => 'fas fa-clipboard-list',
        'link' => '../sdo-admtec/bitacora-listado.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Consumos Basicos',
        'icon' => 'fas fa-clipboard-list',
        'link' => '../sdo-admtec/consumos_basicos.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Documentos',
        'icon' => 'fas fa-file',
        'link' => '#',
        'allowed_departments' => [1, 13],
        'subitems' => [
            [
                'title' => 'Ingresar Documento',
                'icon' => 'fas fa-file',
                'link' => '../sdo-admtec/recepcion_documento.php',
                'allowed_departments' => [1, 13]
            ],
            [
                'title' => 'Listado Documentos',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/listar_recepcion_documento.php',
                'allowed_departments' => [1, 13]
            ]
        ]
    ],
    [
        'title' => 'Equipamiento HBQP',
        'icon' => 'fas fa-clipboard-list',
        'link' => '../sdo-admtec/equipamiento_hbqp.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Funcionarios CCAA',
        'icon' => 'fas fa-users',
        'link' => '../sdo-admtec/control-acceso.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Hojas Técnicas',
        'icon' => 'fas fa-file',
        'link' => '#',
        'allowed_departments' => [1, 6, 13],
        'subitems' => [
            [
                'title' => 'Generar HT',
                'icon' => 'fas fa-file',
                'link' => '../sdo-admtec/ordenes-formulario-general.php',
                'allowed_departments' => [1, 13]
            ],
            [
                'title' => 'Listado HT',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/ordenes-subir-firmas.php',
                'allowed_departments' => [1, 6, 13]
            ]
        ]
    ],
    [
        'title' => 'Llaves Entregadas',
        'icon' => 'fas fa-key',
        'link' => '../sdo-admtec/llaves_entregadas.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Ordenes De Trabajo (OT)',
        'icon' => 'fas fa-file',
        'link' => '#',
        'allowed_departments' => [1, 6, 13],
        'subitems' => [
            [
                'title' => 'Listado OT En Curso',
                'icon' => 'fas fa-file',
                'link' => '../sdo-admtec/trabajos_general_listado.php',
                'allowed_departments' => [1, 6, 13]
            ],
            [
                'title' => 'Listado OT Terminadas',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/trabajos_general_listado_terminado.php',
                'allowed_departments' => [1, 6, 13]
            ]
        ]
    ],
    [
        'title' => 'Organigrama HBQP',
        'icon' => 'fas fa-male',
        'link' => '../sdo-admtec/hbqp_organigrama.php',
        'allowed_departments' => [1, 6, 13]
    ],
    [
        'title' => 'Parking',
        'icon' => 'fas fa-car',
        'link' => '../sdo-admtec/parking-acceso.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Reportes de Eventos',
        'icon' => 'fas fa-clipboard-list',
        'link' => '../sdo-admtec/reportes-listado.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Solicitudes de Compra',
        'icon' => 'fas fa-book',
        'link' => '#',
        'allowed_departments' => [1, 6, 13],
        'subitems' => [
            [
                'title' => 'SC - Puesta en Marcha',
                'icon' => 'fas fa-file',
                'link' => '../sdo-admtec/soliCompra_pm_administracion.php',
                'allowed_departments' => [1, 13]
            ],
            [
                'title' => 'SC - SDO',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/soliCompra_sdo_administracion.php',
                'allowed_departments' => [1, 6, 13]
            ]
        ]
    ],
    [
        'title' => 'Solicitudes Telefonicas',
        'icon' => 'fas fa-clipboard-list',
        'link' => '../sdo-admtec/solicitudes_telefonicas.php',
        'allowed_departments' => [1, 13]
    ],
    [
        'title' => 'Tickets',
        'icon' => 'fas fa-book',
        'link' => '#',
        'allowed_departments' => [1, 6, 13],
        'subitems' => [
            [
                'title' => 'Listado de Usuarios',
                'icon' => 'fas fa-file',
                'link' => '../sdo-admtec/usuarios_tickets.php',
                'allowed_departments' => [1, 13]
            ],
            [
                'title' => 'Tickets Recibidos',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/solicitudes_recibidas.php',
                'allowed_departments' => [1, 6, 13]
            ],
            [
                'title' => 'Tickets Cancelados',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/solicitudes_canceladas.php',
                'allowed_departments' => [1, 6, 13]
            ],
            [
                'title' => 'Tickets Finalizados',
                'icon' => 'fas fa-list',
                'link' => '../sdo-admtec/solicitudes_finalizadas.php',
                'allowed_departments' => [1, 6, 13]
            ]
        ]
    ]
];


usort($menuAdmin, function ($a, $b) {
    return strcmp($a['title'], $b['title']);
});
