<?php
$modulosInicio = [
    [
        'grupo' => 'Equipamiento HBQP',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Equipamiento HBQP',
                'ruta' => '../sdo-directiva/equipamiento_hbqp.php',
            ],
        ]
    ],
    [
        'grupo' => 'Hojas Técnicas',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Historial HT',
                'ruta' => '../sdo-directiva/ordenes-listado-general.php',
            ],
        ]
    ],
    [
        'grupo' => 'Informes de Turno',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informes de Turno',
                'ruta' => '../sdo-directiva/reportes-visualizar.php',
            ],
        ]
    ],
    [
        'grupo' => 'Informes',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear Informe',
                'ruta' => '../sdo-directiva/informe_crear.php',
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Historial Informe',
                'ruta' => '../sdo-directiva/informe_listado.php',
            ],
        ]
    ],
    [
        'grupo' => 'Informe EDAN',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informe EDAN',
                'ruta' => '../sdo-directiva/listar_informe_edan.php',
            ],
        ]
    ],
    [
        'grupo' => 'Memorandum',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear Memorandum',
                'ruta' => '../sdo-directiva/memorandum_crear.php',
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Historial Memorandum',
                'ruta' => '../sdo-directiva/memorandum_listado.php',
            ],
        ]
    ],
    [
        'grupo' => 'Orden de Trabajo',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Trabajos en Curso',
                'ruta' => '../sdo-directiva/trabajos_general_listado.php',
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Trabajos Terminados',
                'ruta' => '../sdo-directiva/trabajos_general_listado_terminado.php',
            ],
        ]
    ],
    [
        'grupo' => 'Personal SDO',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Encargados de Departamento',
                'ruta' => '../sdo-directiva/jefes-listado.php',
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Profesionales del Departamento',
                'ruta' => '../sdo-directiva/profesionales-listado.php',
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Técnicos y Auxiliares del Departamento',
                'ruta' => '../sdo-directiva/tecnicos-listado.php',
            ],
        ]
    ],
    [
        'grupo' => 'Solicitudes de Compra',
        'items' => [
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - PM',
                'ruta' => '../sdo-directiva/soliCompra_pm_administracion.php',
            ],
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - SDO',
                'ruta' => '../sdo-directiva/soliCompra_sdo_administracion.php',
            ],
        ]
    ],
    [
        'grupo' => 'Tickets SDO en SIGH',
        'items' => [
            [
                'icono' => '<i class="fas fa-ticket-alt fa-2x"></i>',
                'nombre' => 'Tickets Recibidos',
                'ruta' => '../sdo-directiva/solicitudes_recibidas.php',
            ],
            [
                'icono' => '<i class="fas fa-check fa-2x"></i>',
                'nombre' => 'Tickets Finalizados',
                'ruta' => '../sdo-directiva/solicitudes_finalizadas.php',
            ],
            [
                'icono' => '<i class="fas fa-times fa-2x"></i>',
                'nombre' => 'Tickets Cancelados',
                'ruta' => '../sdo-directiva/solicitudes_canceladas.php',
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
    $botonesPermitidos = $grupo['items'];

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