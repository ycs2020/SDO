<?php
$modulosInicio = [
    [
        'grupo' => 'Aadministración de Archivos',
        'items' => [
            [
                'icono' => '<i class="fas fa-server fa-2x"></i>',
                'nombre' => 'Soporte Plataforma SDO',
                'ruta' => '../sdo-jefestecnicos/soporte_plataforma_listado.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ],
            [
                'icono' => '<i class="fas fa-server fa-2x"></i>',
                'nombre' => 'Nube de Datos (En Desarrollo)',
                'ruta' => '../sdo-jefestecnicos/archivos_administracion_nube.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ],
            [
                'icono' => '<i class="fas fa-server fa-2x"></i>',
                'nombre' => 'Planos',
                'ruta' => '../sdo-jefestecnicos/planos_visualizar.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ],
            [
                'icono' => '<i class="fas fa-list fa-2x"></i>',
                'nombre' => 'Listado de Materiales',
                'ruta' => '../sdo-jefestecnicos/materiales_listado_solicitudes.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ],
            [
                'icono' => '<i class="fas fa-server fa-2x"></i>',
                'nombre' => 'Documentacion General',
                'ruta' => '../sdo-jefestecnicos/documentacion_general.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ],
            [
                'icono' => '<i class="fas fa-server fa-2x"></i>',
                'nombre' => 'Informe UMAS Pabellon',
                'ruta' => '../sdo-pdf/SDO-UMAS-Pabellon.php',
                'departamentos' => [1, 13],
                'usuarios' => ['Yorgo Casimis Subiabre'], // <-- sólo para estos usuarios
            ]
        ]
    ],
    [
        'grupo' => 'Actas, CCAA y Parking',
        'items' => [
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Adm. de Funcionarios RRHH',
                'ruta' => '../sdo-jefestecnicos/credenciales_administracion.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Actas de Funcionarios',
                'ruta' => '../sdo-jefestecnicos/actas-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Eventos CCAA',
                'ruta' => '../sdo-jefestecnicos/eventos_registro.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Funcionarios en CCAA',
                'ruta' => '../sdo-jefestecnicos/control-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-car fa-2x"></i>',
                'nombre' => 'Funcionarios con Parking',
                'ruta' => '../sdo-jefestecnicos/parking-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cogs fa-2x"></i>',
                'nombre' => 'Levant. UCAS - Incendio',
                'ruta' => '../sdo-jefestecnicos/ucas_levantamiento_incendio.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-door-closed fa-2x"></i>',
                'nombre' => 'Listado de UCAS',
                'ruta' => '../sdo-jefestecnicos/ucas_listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-door-closed fa-2x"></i>',
                'nombre' => 'Listado de Puertas en CCAA',
                'ruta' => '../sdo-jefestecnicos/puertas_ccaa_listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Memorándum de Accesos',
                'ruta' => '../sdo-jefestecnicos/memo_control_acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Grupos Protocolo CCAA',
                'ruta' => '../sdo-jefestecnicos/ccaa_grupos_protocolo.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Perfiles Protocolo CCAA',
                'ruta' => '../sdo-jefestecnicos/ccaa_perfiles_protocolo.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Excel Protocolo CCAA',
                'ruta' => '../sdo-jefestecnicos/ccaa_perfiles_permisos.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'CREDENCIALES RRHH',
                'ruta' => '../sdo-jefestecnicos/credenciales_rrhh_listado_activacion.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Actividades Diarias',
        'items' => [
            [
                'icono' => '<i class="fas fa-clock fa-2x"></i>',
                'nombre' => 'Bitacora',
                'ruta' => '../sdo-jefestecnicos/bitacora-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Reportes Control y Electricos',
                'ruta' => '../sdo-jefestecnicos/reportes-listado.php',
                'departamentos' => [1, 4, 13]
            ],

            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - CC',
                'ruta' => '../sdo-jefestecnicos/ordenes-formulario-general4.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - Especialidades',
                'ruta' => '../sdo-jefestecnicos/ordenes-formulario-general.php',
                'departamentos' => [2, 3, 4, 5, 7, 8, 10, 12],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - EEMM',
                'ruta' => '../sdo-jefestecnicos/ordenes-formulario-general-inge-clinica.php',
                'departamentos' => [6],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado HT',
                'ruta' => '../sdo-jefestecnicos/ordenes-listado-general.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado HT - Apoyo Clinico',
                'ruta' => '../sdo-jefestecnicos/ordenes-listado-general1.php',
                'departamentos' => [10],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Ordenes de Trabajo Activas',
                'ruta' => '../sdo-jefestecnicos/trabajos_general_listado.php',
                'cantidad' => contarOTTecnico($nomUsuario),
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Ordenes de Trabajo Finalizadas',
                'ruta' => '../sdo-jefestecnicos/trabajos_general_listado_terminados.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Visualizar Checklists',
                'ruta' => '../sdo-jefestecnicos/check_list_todos.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Asistencia',
        'items' => [
            [
                'icono' => '<i class="fas fa-user fa-2x"></i>',
                'nombre' => 'Administración Asistencia',
                'ruta' => '../sdo-jefestecnicos/asistencia_registro.php',
                'departamentos' => [1, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Consumos Basicos',
        'items' => [
            [
                'icono' => '<i class="fas fa-gas-pump fa-2x"></i>',
                'nombre' => 'Medición Combustible',
                'ruta' => '../sdo-jefestecnicos/combustible_mediciones.php',
                'departamentos' => [1, 2, 12],
            ],
            [
                'icono' => '<i class="fas fa-gas-pump fa-2x"></i>',
                'nombre' => 'Consumos Basicos',
                'ruta' => '../sdo-jefestecnicos/consumos_basicos.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Documentación',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Documentación',
                'ruta' => '../sdo-jefestecnicos/listar_recepcion_documento.php',
                'cantidad' => obtenerHistorialDocu($nomUsuario),
                'departamentos' => [1, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Equipamiento',
        'items' => [
            [
                'icono' => '<i class="fas fa-desktop fa-2x"></i>',
                'nombre' => 'Listado de Equipamiento',
                'ruta' => '../sdo-jefestecnicos/equipamiento_hbqp.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-snowflake fa-2x"></i>',
                'nombre' => 'Protocolo Refrigeradores',
                'ruta' => '../sdo-jefestecnicos/protocolo_refrigeradores.php',
                'departamentos' => [1, 10],
            ]
        ]
    ],
    [
        'grupo' => 'Formularios de Solicitudes de Acceso',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Selección de Formularios',
                'ruta' => '../sdo-jefestecnicos/formulariosCCAA_menuSeleccion.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CA - Recibidos',
                'ruta' => '../sdo-jefestecnicos/formulariosCCAA_listado.php',
                'cantidad' => contarFormulariosRecibidos(),
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CA - Finalizados',
                'ruta' => '../sdo-jefestecnicos/formulariosCCAA_listadoFinalizado.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Informe EDAN',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informes EDAN',
                'ruta' => '../sdo-jefestecnicos/listar_informe_edan.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informes EDAN - Especialidades',
                'ruta' => '../sdo-jefestecnicos/listar_informe_edan_especialidades.php',
                'departamentos' => [2, 3, 4, 5, 6, 7, 8, 10, 12],
            ]
        ]
    ],
    [
        'grupo' => 'Levantamiento de Sistema BMS',
        'items' => [
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Controladores Maestros',
                'ruta' => '../sdo-jefestecnicos/controladores-maestros-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Controladores Esclavos',
                'ruta' => '../sdo-jefestecnicos/controladores-esclavos-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Detalle Controladores',
                'ruta' => '../sdo-jefestecnicos/detalle_controladores_bms.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Memorandum',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Memorandum',
                'ruta' => '../sdo-jefestecnicos/memorandum_listado.php',
                'departamentos' => [10],
            ]
        ]
    ],
    [
        'grupo' => 'Movilización',
        'items' => [
            [
                'icono' => '<i class="fas fa-ambulance fa-2x"></i>',
                'nombre' => 'Moviles',
                'ruta' => '../sdo-jefestecnicos/administrar-moviles.php',
                'departamentos' => [8],
            ],
            [
                'icono' => '<i class="fas fa-ambulance fa-2x"></i>',
                'nombre' => 'Viajes',
                'ruta' => '../sdo-jefestecnicos/viajes-1.php',
                'departamentos' => [8],
            ],
        ]
    ],
    [
        'grupo' => 'Nube de Datos',
        'items' => [
            [
                'icono' => '<i class="fas fa-user fa-2x"></i>',
                'nombre' => 'Nube de Datos',
                'ruta' => '../sdo-jefestecnicos/carpetas_listado.php',
                'departamentos' => [1, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Observaciones de Sistema BMS',
        'items' => [
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Sistema BMS',
                'ruta' => '../sdo-jefestecnicos/sistemas-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Equipos Forzados',
                'ruta' => '../sdo-jefestecnicos/forzados-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Observaciones',
                'ruta' => '../sdo-jefestecnicos/sistemas-observaciones.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Organigrama',
        'items' => [
            [
                'icono' => '<i class="fas fa-project-diagram fa-2x"></i>',
                'nombre' => 'Organigrama',
                'ruta' => '../sdo-jefestecnicos/hbqp_organigrama.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Pabellones',
        'items' => [
            [
                'icono' => '<i class="fas fa-user fa-2x"></i>',
                'nombre' => 'Pabellones',
                'ruta' => '../sdo-jefestecnicos/pabellones_administracion.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-user fa-2x"></i>',
                'nombre' => 'Informar Variables',
                'ruta' => '../sdo-jefestecnicos/pabellones_administracion_correo.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Planificaci&oacute;n de Trabajos',
        'items' => [
            [
                'icono' => '<i class="fas fa-user fa-2x"></i>',
                'nombre' => 'Planificaci&oacute;n',
                'ruta' => '../sdo-jefestecnicos/planificador_administracion.php',
                'departamentos' => [1, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Protocolo Refrigeradores',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Protocolo Refrigeradores',
                'ruta' => '../sdo-jefestecnicos/protocolo_refrigeradores.php',
                'departamentos' => [1, 10],
            ]
        ]
    ],
    [
        'grupo' => 'Recepcion de Documentos',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Agregar Documento',
                'ruta' => '../sdo-jefestecnicos/recepcion_documento.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Historial Documentos',
                'ruta' => '../sdo-jefestecnicos/listar_recepcion_documento.php',
                'cantidad' => obtenerHistorialDocu($nomUsuario),
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Solicitudes de Compra',
        'items' => [
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - PM',
                'ruta' => '../sdo-jefestecnicos/soliCompra_pm_administracion.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - SDO',
                'ruta' => '../sdo-jefestecnicos/soliCompra_sdo_administracion.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - CC',
                'ruta' => '../sdo-jefestecnicos/soliCompra_sdo_administracion.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Tickets de Soporte (Sistema SIGH)',
        'items' => [
            [
                'icono' => '<i class="fas fa-ticket-alt fa-2x"></i>',
                'nombre' => 'Tickets Recibidos',
                'ruta' => '../sdo-jefestecnicos/solicitudes_recibidas.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-check fa-2x"></i>',
                'nombre' => 'Tickets Finalizados',
                'ruta' => '../sdo-jefestecnicos/solicitudes_finalizadas.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-times fa-2x"></i>',
                'nombre' => 'Tickets Cancelados',
                'ruta' => '../sdo-jefestecnicos/solicitudes_canceladas.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
        ]
    ],
];

// Ordenar alfabéticamente por 'grupo'
usort($modulosInicio, function ($a, $b) {
    return strcmp($a['grupo'], $b['grupo']);
});

$idDepartamento = $_SESSION['idDepartamento'] ?? 0;

$clasesBg = [
    'bg-gradient-primary',
    'bg-gradient-secondary',
    'bg-gradient-info',
    'bg-gradient-purple',
    'bg-gradient-success',
    'bg-gradient-warning',
    'bg-gradient-danger'
];
$indexColor = 0;
?>

    <!-- 🔍 Buscador -->
    <div class="row mb-4">
        <div class="col-12 col-md-6 mx-auto">
            <input type="text" id="buscadorModulos" class="form-control form-control-lg text-center shadow-sm"
                    placeholder="🔍 Buscar módulo o grupo..." autocomplete="off">
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#buscadorModulos").on("keyup", function () {
                const texto = $(this).val().toLowerCase().trim();

                $(".grupo-modulo").each(function () {
                    const grupo = $(this);
                    const nombreGrupo = grupo.data("grupo");
                    let visible = false;

                    // Buscar coincidencias dentro de los items del grupo
                    grupo.find(".item-modulo").each(function () {
                        const nombreItem = $(this).data("nombre");
                        const coincide = nombreItem.includes(texto) || nombreGrupo.includes(texto);

                        $(this).toggle(coincide);
                        if (coincide) visible = true;
                    });

                    // Mostrar/ocultar grupo según coincidencias
                    grupo.toggle(visible);
                });
            });
        });
    </script>

<?php
foreach ($modulosInicio as $grupo) {
    $botonesPermitidos = array_filter($grupo['items'], function ($item) use ($idDepartamento, $nomUsuario) {
        $departamentoValido = in_array($idDepartamento, $item['departamentos']);

        // Si 'usuarios' está definido, se restringe por nombre
        if (isset($item['usuarios'])) {
            return $departamentoValido && in_array($nomUsuario, $item['usuarios']);
        }

        return $departamentoValido;
    });

    if (count($botonesPermitidos) > 0) {
        ?>
        <div class="row grupo-modulo mb-3" data-grupo="<?php echo strtolower($grupo['grupo']); ?>">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase font-weight-bold text-black mb-3 grupo-titulo">
                            <?php echo $grupo['grupo']; ?>
                        </h6>
                        <div class="row">
                            <?php
                            foreach ($botonesPermitidos as $item) {
                                $bg = $clasesBg[$indexColor];
                                $indexColor = ($indexColor + 1) % count($clasesBg);

                                $atributosExtra = isset($item['modalTexto']) ? $item['modalTexto'] : '';
                                $href = isset($item['modalTexto']) ? '#' : $item['ruta'];

                                $mostrarBadge = isset($item['cantidad']) && $item['cantidad'] > 0;
                                $nombreLower = strtolower($item['nombre']);
                                ?>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3 position-relative item-modulo"
                                        data-nombre="<?php echo $nombreLower; ?>">
                                    <a href="<?php echo $href; ?>"
                                            class="btn btn-sm <?php echo $bg; ?> w-100 h-100 text-center p-3 shadow rounded position-relative"
                                            style="text-decoration: none;"
                                        <?php echo $atributosExtra; ?>>
                                        <div><?php echo $item['icono']; ?></div>
                                        <strong><?php echo $item['nombre']; ?></strong>
                                    </a>

                                    <?php if ($mostrarBadge): ?>
                                        <span class="position-absolute badge bg-danger text-white px-2 py-1"
                                                style="top: -5px; right: -5px; font-size: 0.85rem;">
                                            <i class="fas fa-flag"></i> <?php echo $item['cantidad']; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php $indexColor = 0; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>