<?php
function obtenerIPUsuario() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ipActualData = obtenerIPUsuario();
$nomUsuario = filter_var($_SESSION['nomUsuario'] ?? 'Invitado', FILTER_SANITIZE_SPECIAL_CHARS);

// $modulosInicio mantiene su declaración estructural intacta...
$modulosInicio = [
    [
        'grupo' => 'Administración',
        'items' => [
            ['icono' => '<i class="fas fa-calendar-check fa-2x"></i>', 'nombre' => 'Asistencia', 'ruta' => '../sdo-administrador/asistencia_registro.php'],
            ['icono' => '<i class="fas fa-file fa-2x"></i>', 'nombre' => 'Informes Control Centralizado', 'ruta' => '../sdo-administrador/controlCent_informes.php'],
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Listado Funcionarios', 'ruta' => '../sdo-pdf/SDO-Excel-Funcionarios.php'],
            ['icono' => '<i class="fas fa-project-diagram fa-2x"></i>', 'nombre' => 'Organigrama HBQP', 'ruta' => '../sdo-administrador/hbqp_organigrama.php'],
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Turnos Control Centralizado', 'ruta' => '../sdo-administrador/turnos_calendario.php'],
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Administracion RRHH', 'ruta' => '../sdo-administrador/credencial_administracion.php'],
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Informe Funcionarios - CCAA', 'ruta' => '../sdo-pdf/SDO-Informe-Funcionarios-CCAA.php', 'ips' => ['10.69.250.151']],
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Informe Estado y T° - Calderas', 'ruta' => '../sdo-administrador/calderas_documento.php'],
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Analizadores de Red - Listado', 'ruta' => '../sdo-administrador/analizadores_red_listado.php'],
            ['icono' => '<i class="fas fa-gas-pump fa-2x"></i>', 'nombre' => 'Medicion PSI - Combustible', 'ruta' => '../sdo-administrador/combustible_psi_medicion.php'],
        ]
    ],
    [
        'grupo' => 'Actas, CCAA, Parking',
        'items' => [
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Listado Actas CCAA', 'ruta' => '../sdo-administrador/actas-listado.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Listado Funcionarios CCAA', 'ruta' => '../sdo-administrador/control-acceso.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Listado Funcionarios Parking', 'ruta' => '../sdo-administrador/parking-acceso.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Llaves Entregadas', 'ruta' => '../sdo-administrador/llaves_entregadas.php'],
            ['icono' => '<i class="fas fa-clock fa-2x"></i>', 'nombre' => 'Eventos CCAA', 'ruta' => '../sdo-administrador/eventos_registro.php'],
            ['icono' => '<i class="fas fa-door-closed fa-2x"></i>', 'nombre' => 'UCAS - Listado', 'ruta' => '../sdo-administrador/ucas_listado.php'],
            ['icono' => '<i class="fas fa-door-closed fa-2x"></i>', 'nombre' => 'UCAS - Puertas', 'ruta' => '../sdo-administrador/puertas_ccaa_listado.php'],
            ['icono' => '<i class="fas fa-fire fa-2x"></i>', 'nombre' => 'UCAS - Modulos Incendio', 'ruta' => '../sdo-administrador/ucas_levantamiento_incendio.php'],
        ]
    ],
    [
        'grupo' => 'Bitacora y Reportes',
        'items' => [
            ['icono' => '<i class="fas fa-file fa-2x"></i>', 'nombre' => 'Bitacora', 'ruta' => '../sdo-administrador/bitacora-listado.php'],
            ['icono' => '<i class="fas fa-file fa-2x"></i>', 'nombre' => 'Reportes de Eventos', 'ruta' => '../sdo-administrador/reportes-administrar.php']
        ]
    ],
    [
        'grupo' => 'BMS',
        'items' => [
            ['icono' => '<i class="fas fa-cogs fa-2x"></i>', 'nombre' => 'Sistema BMS - Listado', 'ruta' => '../sdo-administrador/sistemas-listado.php'],
            ['icono' => '<i class="fas fa-cogs fa-2x"></i>', 'nombre' => 'Sistema BMS - Equipos Forzados', 'ruta' => '../sdo-administrador/forzados-listado.php'],
            ['icono' => '<i class="fas fa-cogs fa-2x"></i>', 'nombre' => 'Sistema BMS - Resumen', 'ruta' => '../sdo-administrador/sistemas-observaciones.php'],
            ['icono' => '<i class="fas fa-cogs fa-2x"></i>', 'nombre' => 'ECBOS - Controladores Maestros', 'ruta' => '../sdo-administrador/controladores-maestros-listado.php'],
            ['icono' => '<i class="fas fa-cogs fa-2x"></i>', 'nombre' => 'ECBOS - Controladores Esclavos', 'ruta' => '../sdo-administrador/controladores-esclavos-listado.php'],
            ['icono' => '<i class="fas fa-book fa-2x"></i>', 'nombre' => 'BMS - Reporte Alarmas', 'ruta' => '../sdo-administrador/bms_reporte_admin.php']
        ]
    ],
    [
        'grupo' => 'Consumos Basicos',
        'items' => [
            ['icono' => '<i class="fas fa-gas-pump fa-2x"></i>', 'nombre' => 'Consumos Basicos', 'ruta' => '../sdo-administrador/consumos_basicos.php'],
            ['icono' => '<i class="fas fa-gas-pump fa-2x"></i>', 'nombre' => 'Combustible - Medición', 'ruta' => '../sdo-administrador/combustible_mediciones.php']
        ]
    ],
    [
        'grupo' => 'Documentos',
        'items' => [
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Documentos - Agregar', 'ruta' => '../sdo-administrador/recepcion_documento.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Documentos - Historial', 'ruta' => '../sdo-administrador/listar_recepcion_documento.php']
        ]
    ],
    [
        'grupo' => 'EDAN',
        'items' => [
            ['icono' => '<i class="fas fa-list-alt fa-2x"></i>', 'nombre' => 'EDAN - Listado', 'ruta' => '../sdo-administrador/listar_informe_edan.php']
        ]
    ],
    [
        'grupo' => 'Equipamiento',
        'items' => [
            ['icono' => '<i class="fas fa-briefcase fa-2x"></i>', 'nombre' => 'Equipamiento HBQP', 'ruta' => '../sdo-administrador/equipamiento_hbqp.php']
        ]
    ],
    [
        'grupo' => 'Hojas Tecnicas (HT)',
        'items' => [
            ['icono' => '<i class="fas fa-file fa-2x"></i>', 'nombre' => 'Hojas Tecnicas - Agregar', 'ruta' => '../sdo-administrador/ordenes-formulario-general.php'],
            ['icono' => '<i class="fas fa-book fa-2x"></i>', 'nombre' => 'Hojas Tecnicas - Historial', 'ruta' => '../sdo-administrador/ordenes-subir-firmas.php']
        ]
    ],
    [
        'grupo' => 'Inventario',
        'items' => [
            ['icono' => '<i class="fas fa-briefcase fa-2x"></i>', 'nombre' => 'Inventario - Presupuesto', 'ruta' => '../sdo-administrador/presupuesto-equipo.php'],
            ['icono' => '<i class="fas fa-briefcase fa-2x"></i>', 'nombre' => 'Inventario - Listado', 'ruta' => '../sdo-administrar/inventario-hbqp.php']
        ]
    ],
    [
        'grupo' => 'Llamado de Enfermeria (LLE)',
        'items' => [
            ['icono' => '<i class="fas fa-list-alt fa-2x"></i>', 'nombre' => 'LLE - Listado', 'ruta' => '../sdo-administrador/listado_LLE.php']
        ]
    ],
    [
        'grupo' => 'Licitaciones',
        'items' => [
            ['icono' => '<i class="fas fa-book fa-2x"></i>', 'nombre' => 'Licitaciones', 'ruta' => '../sdo-administrador/licitaciones_admin.php']
        ]
    ],
    [
        'grupo' => 'Memorandum',
        'items' => [
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Memorandum - Agregar', 'ruta' => '../sdo-administrador/memorandum_crear.php'],
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Memorandum - Historial', 'ruta' => '../sdo-administrador/memorandum_listado.php']
        ]
    ],
    [
        'grupo' => 'Movilización',
        'items' => [
            ['icono' => '<i class="fas fa-car fa-2x"></i>', 'nombre' => 'Movilización - Viaje', 'ruta' => '../sdo-administrador/viajes-1.php']
        ]
    ],
    [
        'grupo' => 'Ordenes de Trabajo (OT)',
        'items' => [
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Ordenes de Trabajo', 'ruta' => '../sdo-administrador/trabajos_general_listado.php'],
        ]
    ],
    [
        'grupo' => 'Pañol',
        'items' => [
            ['icono' => '<i class="fas fa-store fa-2x"></i>', 'nombre' => 'Pañol - Productos', 'ruta' => '../sdo-administrador/panel-arriendo-retiro.php'],
            ['icono' => '<i class="fas fa-tools fa-2x"></i>', 'nombre' => 'Pañol - Herramientas', 'ruta' => '../sdo-administrador/bodega-administrar.php'],
            ['icono' => '<i class="fas fa-tools fa-2x"></i>', 'nombre' => 'Pañol - Materiales', 'ruta' => '../sdo-administrador/materiales-administrar.php']
        ]
    ],
    [
        'grupo' => 'Planificador',
        'items' => [
            ['icono' => '<i class="fas fa-briefcase fa-2x"></i>', 'nombre' => 'Planificador', 'ruta' => '../sdo-administrador/planificador_administracion.php']
        ]
    ],
    [
        'grupo' => 'Protocolo CCAA',
        'items' => [
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Protocolo - Grupos CCAA', 'ruta' => '../sdo-administrador/ccaa_grupos_protocolo.php'],
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Protocolo - Perfiles CCAA', 'ruta' => '../sdo-administrador/ccaa_perfiles_protocolo.php']
        ]
    ],
    [
        'grupo' => 'Registro - Comunicación',
        'items' => [
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Registro - Comunicación', 'ruta' => '../sdo-administrador/registros_comunicacion.php']
        ]
    ],
    [
        'grupo' => 'Solicitudes CCAA',
        'items' => [
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Solicitudes CCAA Recibidas', 'ruta' => '../sdo-administrador/formulariosCCAA_listado.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Solicitudes CCAA Finalizadas', 'ruta' => '../sdo-administrador/formulariosCCAA_listadoFinalizado.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Solicitudes CCAA Usuarios', 'ruta' => '../sdo-administrador/formulariosCCAA_listadoUsuarios.php'],
            ['icono' => '<i class="fas fa-clipboard-list fa-2x"></i>', 'nombre' => 'Solicitudes CCAA - Listado Puertas', 'ruta' => '../sdo-pdf/SDO-Formularios-CCAA-Accesos.php']
        ]
    ],
    [
        'grupo' => 'Solicitudes de Compra (SC)',
        'items' => [
            ['icono' => '<i class="fas fa-shopping-cart fa-2x"></i>', 'nombre' => 'SC - Puesta en Marcha', 'ruta' => '../sdo-administrador/soliCompra_pm_administracion.php'],
            ['icono' => '<i class="fas fa-shopping-cart fa-2x"></i>', 'nombre' => 'SC - SDO', 'ruta' => '../sdo-administrador/soliCompra_sdo_administracion.php'],
            ['icono' => '<i class="fas fa-shopping-cart fa-2x"></i>', 'nombre' => 'SC - Resumen', 'ruta' => '../sdo-administrador/soliCompra_sdo_resumen.php'],
            ['icono' => '<i class="fas fa-shopping-cart fa-2x"></i>', 'nombre' => 'SC - Cotiza Productos', 'ruta' => '../sdo-administrador/soliCompra_sdo_cotiza_productos.php']
        ]
    ],
    [
        'grupo' => 'Tanque Indura',
        'items' => [
            ['icono' => '<i class="fas fa-shopping-cart fa-2x"></i>', 'nombre' => 'Mediciones - Tanque Indura', 'ruta' => '../sdo-administrador/indura_mediciones.php']
        ]
    ],
    [
        'grupo' => 'Tickets Plataforma',
        'items' => [
            ['icono' => '<i class="fas fa-ticket-alt fa-2x"></i>', 'nombre' => 'Tickets - Recibidos', 'ruta' => '../sdo-administrador/solicitudes_recibidas.php'],
            ['icono' => '<i class="fas fa-check fa-2x"></i>', 'nombre' => 'Tickets - Finalizados', 'ruta' => '../sdo-administrador/solicitudes_finalizadas.php'],
            ['icono' => '<i class="fas fa-times fa-2x"></i>', 'nombre' => 'Tickets - Cancelados', 'ruta' => '../sdo-administrador/solicitudes_canceladas.php'],
            ['icono' => '<i class="fas fa-users fa-2x"></i>', 'nombre' => 'Tickets - Usuarios', 'ruta' => '../sdo-administrador/usuarios_tickets.php']
        ]
    ],
    [
        'grupo' => 'Tickets SIGH',
        'items' => [
            ['icono' => '<i class="fas fa-list fa-2x"></i>', 'nombre' => 'Tickets SIGH - Listado', 'ruta' => '../sdo-administrador/tickets_sigh.php'],
            ['icono' => '<i class="fas fa-tachometer-alt fa-2x"></i>', 'nombre' => 'Tickets SIGH - Dashboard', 'ruta' => '../sdo-administrador/dashboard-ticket-sigh.php']
        ]
    ]
];

usort($modulosInicio, function ($a, $b) {
    return strcmp($a['grupo'], $b['grupo']);
});

$clasesBg = [
    'bg-gradient-primary',
    'bg-gradient-info',
    'bg-gradient-purple',
    'bg-gradient-success',
    'bg-gradient-navy text-white'
];
$indexColor = 0;
?>

    <div class="row mb-4">
        <div class="col-12 col-md-6 mx-auto">
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" id="buscadorModulos" class="form-control form-control-lg border-left-0 pl-1"
                        placeholder="Buscar módulo o grupo..." autocomplete="off">
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#buscadorModulos").on("keyup", function () {
                const texto = $(this).val().toLowerCase().trim();

                $(".grupo-modulo").each(function () {
                    const grupo = $(this);
                    const nombreGrupo = grupo.data("grupo").toString();
                    let visible = false;

                    grupo.find(".item-modulo").each(function () {
                        const nombreItem = $(this).data("nombre").toString();
                        const coincide = nombreItem.includes(texto) || nombreGrupo.includes(texto);

                        if (coincide) {
                            $(this).fadeIn(200);
                            visible = true;
                        } else {
                            $(this).fadeOut(100);
                        }
                    });

                    if (visible) {
                        grupo.fadeIn(200);
                    } else {
                        grupo.fadeOut(100);
                    }
                });
            });
        });
    </script>

<?php
foreach ($modulosInicio as $grupo) {
    $botonesPermitidos = array_filter($grupo['items'], function ($item) use ($nomUsuario, $ipActualData) {
        $accesoUsuario = !isset($item['usuarios']) || in_array($nomUsuario, $item['usuarios']);
        $accesoIP = !isset($item['ips']) || in_array($ipActualData, $item['ips']);
        return $accesoUsuario && $accesoIP;
    });

    if (count($botonesPermitidos) > 0) {
        ?>
        <div class="row grupo-modulo mb-4" data-grupo="<?php echo htmlspecialchars(strtolower($grupo['grupo']), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="col-12">
                <div class="card card-outline card-secondary shadow-sm">
                    <div class="card-header py-2 bg-light">
                        <h3 class="card-title text-uppercase font-weight-bold text-secondary text-sm">
                            <?php echo htmlspecialchars($grupo['grupo'], ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            foreach ($botonesPermitidos as $item) {
                                $bg = $clasesBg[$indexColor];
                                $indexColor = ($indexColor + 1) % count($clasesBg);

                                // Limpieza preventiva sobre atributos inyectables de configuración externa
                                $atributosExtra = filter_var($item['modalTexto'] ?? '', FILTER_DEFAULT);
                                $href = isset($item['modalTexto']) ? '#' : filter_var($item['ruta'], FILTER_SANITIZE_URL);
                                $mostrarBadge = isset($item['cantidad']) && $item['cantidad'] > 0;
                                ?>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-3 item-modulo"
                                        data-nombre="<?php echo htmlspecialchars(strtolower($item['nombre']), ENT_QUOTES, 'UTF-8'); ?>">
                                    <a href="<?php echo $href; ?>"
                                            class="btn btn-block <?php echo $bg; ?> p-3 shadow-sm d-flex flex-column align-items-center justify-content-center h-100 position-relative transition-all"
                                            style="border-radius: 8px; min-height: 110px; text-decoration: none;"
                                        <?php echo $atributosExtra; ?>>
                                        <div class="mb-2"><?php echo $item['icono']; ?></div>
                                        <span class="text-sm font-weight-bold text-center"><?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>

                                        <?php if ($mostrarBadge): ?>
                                            <span class="position-absolute badge badge-danger badge-pill px-2 py-1"
                                                    style="top: -6px; right: -6px; font-size: 0.75rem; border: 2px solid white;">
                                                <i class="fas fa-flag mr-1"></i><?php echo (int)$item['cantidad']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>