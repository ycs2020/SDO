<?php
$menuAdmin = [
    [
        'title' => 'Dashboard',
        'icon' => 'fas fa-home',
        'link' => '../sdo-administrador/dashboard_admin.php'
    ],
    [
        'title' => 'Equipamiento HBQP',
        'icon' => 'fas fa-laptop-house',
        'link' => '../sdo-administrador/equipamiento_hbqp.php'
    ],
    [
        'title' => 'Documentos',
        'icon' => 'fas fa-file',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Generar Documento',
                'icon' => 'fas fa-file',
                'link' => '../sdo-administrador/recepcion_documento.php'
            ],
            [
                'title' => 'Listado Documentos',
                'icon' => 'fas fa-list',
                'link' => '../sdo-administrador/listar_recepcion_documento.php'
            ]
        ]
    ],
    [
        'title' => 'Reporte de Eventos',
        'icon' => 'fas fa-book',
        'link' => '../sdo-administrador/reportes-administrar.php'
    ],
    [
        'title' => 'Hojas T&eacute;cnicas',
        'icon' => 'fas fa-file',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Generar HT',
                'icon' => 'fas fa-file',
                'link' => '../sdo-administrador/ordenes-formulario-general.php'
            ],
            [
                'title' => 'Listado HT',
                'icon' => 'fas fa-list',
                'link' => '../sdo-administrador/ordenes-subir-firmas.php'
            ]
        ]
    ],
    [
        'title' => 'Ordenes de Trabajo (OT)',
        'icon' => 'fas fa-file',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'OT en Curso',
                'icon' => 'fas fa-file',
                'link' => '../sdo-administrador/trabajos_general_listado.php'
            ],
            [
                'title' => 'OT Finalizadas',
                'icon' => 'fas fa-list',
                'link' => '../sdo-administrador/trabajos_general_listado_terminado.php'
            ]
        ]
    ],
    [
        'title' => 'Bitacora',
        'icon' => 'fas fa-book',
        'link' => '../sdo-administrador/bitacora-listado.php'
    ],
    [
        'title' => 'Planificaci&oacute;n',
        'icon' => 'fas fa-book',
        'link' => '../sdo-administrador/planificador_administracion.php'
    ],
    [
        'title' => 'Sistema BMS',
        'icon' => 'fas fa-book',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Listado de Sistemas',
                'icon' => 'fas fa-cog',
                'link' => '../sdo-administrador/sistemas-listado.php'
            ],
            [
                'title' => 'Equipos Forzados',
                'icon' => 'fas fa-cog',
                'link' => '../sdo-administrador/forzados-listado.php'
            ],
            [
                'title' => 'Observaciones',
                'icon' => 'fas fa-book',
                'link' => '../sdo-administrador/sistemas-observaciones.php'
            ],
        ]
    ],
    [
        'title' => 'Levantamientos BMS',
        'icon' => 'fas fa-book',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Servidor BMS',
                'icon' => 'fas fa-cog',
                'link' => '../sdo-administrador/controladores-maestros-listado.php'
            ],
            [
                'title' => 'Controladores BMS',
                'icon' => 'fas fa-cog',
                'link' => '../sdo-administrador/controladores-esclavos-listado.php'
            ],
            [
                'title' => 'Detalle Controladores BMS',
                'icon' => 'fas fa-book',
                'link' => '../sdo-administrador/detalle_controladores_bms.php'
            ],
        ]
    ],
    [
        'title' => 'Usuarios Plataforma',
        'icon' => 'fas fa-users',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Encargados de Departamento',
                'icon' => 'fas fa-users',
                'link' => '../sdo-administrador/jefes-administrar.php'
            ],
            [
                'title' => 'Profesionales de Departamento',
                'icon' => 'fas fa-users',
                'link' => '../sdo-administrador/profesionales-administrar.php'
            ],
            [
                'title' => 'Tecnicos de Departamento',
                'icon' => 'fas fa-users',
                'link' => '../sdo-administrador/personal-administrar.php'
            ],
        ]
    ],
    [
        'title' => 'Tickets',
        'icon' => 'fas fa-book',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Usuarios',
                'icon' => 'fas fa-users',
                'link' => '../sdo-administrador/usuarios_tickets.php'
            ],
            [
                'title' => 'Tickets Recibidos',
                'icon' => 'fas fa-file',
                'link' => '../sdo-administrador/solicitudes_recibidas.php'
            ],
            [
                'title' => 'Tickets Finalizados',
                'icon' => 'fas fa-check',
                'link' => '../sdo-administrador/solicitudes_finalizadas.php'
            ],
            [
                'title' => 'Tickets Cancelados',
                'icon' => 'fas fa-times-circle',
                'link' => '../sdo-administrador/solicitudes_canceladas.php'
            ],
        ]
    ],
    [
        'title' => 'Llaves Entregadas',
        'icon' => 'fas fa-key',
        'link' => '../sdo-administrador/llaves_entregadas.php'
    ],
    [
        'title' => 'Formularios CCAA',
        'icon' => 'fas fa-door-closed',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Ingresar Formulario',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/formulariosCCAA_menuSeleccion.php'
            ],
            [
                'title' => 'Formularios CCAA - Recibidos',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/formulariosCCAA_listado.php'
            ],
            [
                'title' => 'Formularios CCAA - Finalizados',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/formulariosCCAA_listadoFinalizado.php'
            ],
            [
                'title' => 'Formularios CCAA - Usuarios',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/solicitudes_canceladas.php'
            ],
        ]
    ],
    [
        'title' => 'Actas, CCAA y Parking',
        'icon' => 'fas fa-book',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Actas',
                'icon' => 'fas fa-book',
                'link' => '../sdo-administrador/actas-listado.php'
            ],
            [
                'title' => 'Listado CCAA',
                'icon' => 'fas fa-book',
                'link' => '../sdo-administrador/control-acceso.php'
            ],
            [
                'title' => 'Listado Parking',
                'icon' => 'fas fa-parking',
                'link' => '../sdo-administrador/parking-acceso.php'
            ],
            [
                'title' => 'UCAS',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/ucas_listado.php'
            ],
            [
                'title' => 'Puertas',
                'icon' => 'fas fa-door-closed',
                'link' => '../sdo-administrador/puertas_ccaa_listado.php'
            ],
        ]
    ],
    [
        'title' => 'Solicitudes de Compra',
        'icon' => 'fas fa-shopping-bag',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'SC - Puesta en Marcha',
                'icon' => 'fas fa-shopping-bag',
                'link' => '../sdo-administrador/soliCompra_pm_administracion.php'
            ],
            [
                'title' => 'SC - SDO',
                'icon' => 'fas fa-shopping-bag',
                'link' => '../sdo-administrador/soliCompra_sdo_administracion.php'
            ],
            [
                'title' => 'SC - SDO - Control',
                'icon' => 'fas fa-shopping-bag',
                'link' => '../sdo-administrador/soliCompra_sdo_administracion_nuevo.php'
            ],
            [
                'title' => 'SC - SDO - Resumen',
                'icon' => 'fas fa-shopping-bag',
                'link' => '../sdo-administrador/soliCompra_sdo_resumen.php'
            ],
        ]
    ],
    [
        'title' => 'Movilizaci&oacute;n',
        'icon' => 'fas fa-car',
        'link' => '#',
        'subitems' => [
            [
                'title' => 'Moviles',
                'icon' => 'fas fa-car',
                'link' => '../sdo-administrador/administrar-moviles.php'
            ],
            [
                'title' => 'Viajes',
                'icon' => 'fas fa-car',
                'link' => '../sdo-administrador/viajes-1.php'
            ],
        ]
    ],
    [
        'title' => 'Combustible',
        'icon' => 'fas fa-gas-pump',
        'link' => '../sdo-administrador/combustible_mediciones.php'
    ],
    [
        'title' => 'Consumos Basicos',
        'icon' => 'fas fa-hospital',
        'link' => '../sdo-administrador/consumos_basicos.php'
    ],
];


// Ordenar el array $menu por el título ('title')
usort($menuAdmin, function ($a, $b) {
    return strcmp($a['title'], $b['title']);
});