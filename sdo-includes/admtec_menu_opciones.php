<?php
$modulosInicio = [
    [
        'grupo' => 'Actas, CCAA, Parking',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear Actas CCAA',
                'ruta' => '../sdo-admtec/actas-formulario.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado Actas CCAA',
                'ruta' => '../sdo-admtec/actas-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado Funcionarios CCAA',
                'ruta' => '../sdo-admtec/control-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado Funcionarios Parking',
                'ruta' => '../sdo-admtec/parking-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Llaves Entregadas',
                'ruta' => '../sdo-admtec/llaves_entregadas.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Eventos CCAA',
                'ruta' => '../sdo-admtec/eventos_registro.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Memorandum CCAA',
                'ruta' => '../sdo-admtec/memo_control_acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CCAA Recibidas',
                'ruta' => '../sdo-admtec/formulariosCCAA_listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CCAA Finalizadas',
                'ruta' => '../sdo-admtec/formulariosCCAA_listadoFinalizado.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Consumos Basicos',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Consumos Basicos',
                'ruta' => '../sdo-admtec/consumos_basicos.php',
                'departamentos' => [1, 6, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Mediciones Tecnicos',
                'ruta' => '../sdo-admtec/combustible_mediciones.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Documentación',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Agregar Documento',
                'ruta' => '../sdo-admtec/recepcion_documento.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Historial Documentos',
                'ruta' => '../sdo-admtec/listar_recepcion_documento.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Equipamiento',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Equipamiento HBQP',
                'ruta' => '../sdo-admtec/equipamiento_hbqp.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Hojas Técnicas (HT)',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT',
                'ruta' => '../sdo-admtec/ordenes-formulario-general.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado HT',
                'ruta' => '../sdo-admtec/ordenes-subir-firmas.php',
                'departamentos' => [1, 6, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Informe EDAN',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informe EDAN',
                'ruta' => '../sdo-admtec/listado_informe_edan.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Interfaces Control Centralizado',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Turnos Control Centralizado',
                'ruta' => '../sdo-admtec/turnos_calendario.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Memorandum',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear Memorandum',
                'ruta' => '../sdo-admtec/memorandum_crear.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listar Memorandum',
                'ruta' => '../sdo-admtec/memorandum_listado.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Ordenes de Trabajo (OT)',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'OT en Curso',
                'ruta' => '../sdo-admtec/trabajos_general_listado.php',
                'departamentos' => [1, 6, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Ordenes de Trabajo (OT)',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'OT Finalizadas',
                'ruta' => '../sdo-admtec/trabajos_general_listado_terminado.php',
                'departamentos' => [1, 6, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Organigrama',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Organigrama',
                'ruta' => '../sdo-admtec/hbqp_organigrama.php',
                'departamentos' => [1, 6, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Rutina Diaria',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Bitacora',
                'ruta' => '../sdo-admtec/bitacora-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Reportes Generales',
                'ruta' => '../sdo-admtec/reportes-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes Telefonicas',
                'ruta' => '../sdo-admtec/solicitudes_telefonicas.php',
                'departamentos' => [1, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Solicitudes de Compra',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'SC - Puesta en Marcha',
                'ruta' => '../sdo-admtec/soliCompra_pm_administracion.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'SC - SDO',
                'ruta' => '../sdo-admtec/soliCompra_sdo_administracion.php',
                'departamentos' => [1, 6, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Ticket de Soporte',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Tickets Recibidos',
                'ruta' => '../sdo-admtec/solicitudes_recibidas.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Tickets Finalizados',
                'ruta' => '../sdo-admtec/solicitudes_finalizadas.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Tickets Cancelados',
                'ruta' => '../sdo-admtec/solicitudes_canceladas.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Usuarios Tickets',
                'ruta' => '../sdo-admtec/usuarios_tickets.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Ticket SIGH',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Ticket SIGH',
                'ruta' => '../sdo-admtec/tickets_sigh.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Dasboard SIGH',
                'ruta' => '../sdo-admtec/dashboard-ticket-sigh.php',
                'departamentos' => [1, 13],
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