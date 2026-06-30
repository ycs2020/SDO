<?php if ($idDepartamento == 1 || $idDepartamento == 13): ?>
    <div class="row d-flex align-items-stretch">
        <div class="col-lg-12 col-md-12 col-12 mb-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-center">
                    <h3 class="card-title">Pronóstico Meteorológico</h3>
                </div>

                <div class="card-body" style="min-height: 100px;">

                    <?php
                    // API Key
                    $apikey = 'Q4KG2kqXK8pK2rCEisVz4gbPIE7l30Wg';

                    // Ubicación
                    $location = '-32.8801,-71.2472';

                    $url = "https://api.tomorrow.io/v4/weather/realtime?location=" . urlencode($location) . "&apikey={$apikey}&units=metric";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
                    $response = curl_exec($ch);

                    if (!curl_errno($ch)) {
                        $data = json_decode($response, true);
                    }

                    curl_close($ch);

                    ?>

                    <!-- WIDGET DE TOMORROW.IO -->
                    <script>
                        (function (d, s, id) {
                            if (d.getElementById(id)) {
                                if (window.__TOMORROW__) {
                                    window.__TOMORROW__.renderWidget();
                                }
                                return;
                            }
                            const fjs = d.getElementsByTagName(s)[0];
                            const js = d.createElement(s);
                            js.id = id;
                            js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        })(document, 'script', 'tomorrow-sdk');
                    </script>

                    <div class="tomorrow"
                            data-location-id="019092"
                            data-language="ES"
                            data-unit-system="METRIC"
                            data-skin="dark"
                            data-widget-type="upcoming"
                            style="padding-bottom: 10px; position:relative; width: 100%; max-width: none;">
                    </div>

                    <style>
                        .tomorrow {
                            width: 100% !important;
                            max-width: 100% !important;
                        }
                    </style>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$modulosInicio = [
    [
        'grupo' => 'Actas, CCAA y Parking',
        'items' => [
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Actas de Funcionarios',
                'ruta' => '../sdo-personal/actas-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Funcionarios en CCAA',
                'ruta' => '../sdo-personal/control-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-car fa-2x"></i>',
                'nombre' => 'Funcionarios con Parking',
                'ruta' => '../sdo-personal/parking-acceso.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-door-closed fa-2x"></i>',
                'nombre' => 'Listado de UCAS',
                'ruta' => '../sdo-personal/ucas_listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-door-closed fa-2x"></i>',
                'nombre' => 'Listado de Puertas en CCAA',
                'ruta' => '../sdo-personal/puertas_ccaa_listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-users fa-2x"></i>',
                'nombre' => 'Memorándum de Accesos',
                'ruta' => '../sdo-personal/memo_control_acceso.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Actividades Diarias',
        'items' => [
            [
                'icono' => '<i class="fas fa-shopping-cart fa-2x"></i>',
                'nombre' => 'Solicitudes de Compra',
                'ruta' => '../sdo-personal/soliCompra_sdo_administracion.php',
                'departamentos' => [10],
            ],
            [
                'icono' => '<i class="fas fa-clock fa-2x"></i>',
                'nombre' => 'Bitacora',
                'ruta' => '../sdo-personal/bitacora-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Reportes Generales',
                'ruta' => '../sdo-personal/reportes-listado.php',
                'departamentos' => [1, 13]
            ],
            [
                'icono' => '<i class="fas fa-bolt fa-2x"></i>',
                'nombre' => 'Reportes Eléctricos',
                'ruta' => '../sdo-personal/reportes_electricos.php',
                'departamentos' => [1, 4, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - CC',
                'ruta' => '../sdo-personal/ordenes-formulario-general2.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - Especialidades',
                'ruta' => '../sdo-personal/ordenes-formulario-general.php',
                'departamentos' => [2, 3, 4, 5, 7, 8, 10, 12],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Crear HT - EEMM',
                'ruta' => '../sdo-personal/ordenes-formulario-general-inge-clinica.php',
                'departamentos' => [6],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado HT',
                'ruta' => '../sdo-personal/ordenes-listado-general.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Listado HT - Apoyo Clinico',
                'ruta' => '../sdo-personal/ordenes-listado-general1.php',
                'departamentos' => [10],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Ordenes de Trabajo Activas',
                'ruta' => '../sdo-personal/trabajos_general_listado.php',
                'cantidad' => contarOTTecnico($nomUsuario),
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Ordenes de Trabajo Finalizadas',
                'ruta' => '../sdo-personal/trabajos_general_listado_finalizados.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Analizadores de Red',
                'ruta' => '../sdo-personal/analizadores_red_listado.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Asistencia',
        'items' => [
            [
                'icono' => '<i class="fas fa-calendar-check fa-2x"></i>',
                'nombre' => 'Asistencia',
                'ruta' => '#',
                'modalTexto' => 'data-toggle="modal" data-target="#modalAsistencia"',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ]
        ]
    ],
    [
        'grupo' => 'AST',
        'items' => [
            [
                'icono' => '<i class="fas fa-file fa-2x"></i>',
                'nombre' => 'AST',
                'ruta' => '../sdo-personal/listadosAST.php',
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
                'ruta' => '../sdo-personal/combustible_mediciones.php',
                'departamentos' => [1, 2, 12],
            ],
            [
                'icono' => '<i class="fas fa-gas-pump fa-2x"></i>',
                'nombre' => 'Consumos Basicos',
                'ruta' => '../sdo-personal/consumos_basicos.php',
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
                'ruta' => '../sdo-personal/listar_recepcion_documento.php',
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
                'ruta' => '../sdo-personal/equipamiento_hbqp.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-snowflake fa-2x"></i>',
                'nombre' => 'Protocolo Refrigeradores',
                'ruta' => '../sdo-personal/protocolo_refrigeradores.php',
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
                'ruta' => '../sdo-personal/formulariosCCAA_menuSeleccion.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CA - Recibidos',
                'ruta' => '../sdo-personal/formulariosCCAA_listado.php',
                'cantidad' => contarFormulariosRecibidos(),
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Solicitudes CA - Finalizados',
                'ruta' => '../sdo-personal/formulariosCCAA_listadoFinalizado.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Informe EDAN',
        'items' => [
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Habilitar Informe EDAN',
                'ruta' => '#',
                'modalTexto' => 'data-toggle="modal" data-target="#exampleModal"',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informes EDAN',
                'ruta' => '../sdo-personal/listar_informe_edan.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-clipboard-list fa-2x"></i>',
                'nombre' => 'Informes EDAN - Especialidades',
                'ruta' => '../sdo-personal/listar_informe_edan_especialidades.php',
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
                'ruta' => '../sdo-personal/controladores-maestros-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Controladores Esclavos',
                'ruta' => '../sdo-personal/controladores-esclavos-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Detalle Controladores',
                'ruta' => '../sdo-personal/detalle_controladores_bms.php',
                'departamentos' => [1, 13],
            ],
        ]
    ],
    [
        'grupo' => 'Movilización',
        'items' => [
            [
                'icono' => '<i class="fas fa-ambulance fa-2x"></i>',
                'nombre' => 'Moviles',
                'ruta' => '../sdo-personal/administrar-moviles.php',
                'departamentos' => [8],
            ],
            [
                'icono' => '<i class="fas fa-ambulance fa-2x"></i>',
                'nombre' => 'Viajes',
                'ruta' => '../sdo-personal/viajes-1.php',
                'departamentos' => [8],
            ],
        ]
    ],
    [
        'grupo' => 'Observaciones de Sistema BMS',
        'items' => [
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Sistema BMS',
                'ruta' => '../sdo-personal/sistemas-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Equipos Forzados',
                'ruta' => '../sdo-personal/forzados-listado.php',
                'departamentos' => [1, 13],
            ],
            [
                'icono' => '<i class="fas fa-cog fa-2x"></i>',
                'nombre' => 'Observaciones',
                'ruta' => '../sdo-personal/sistemas-observaciones.php',
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
                'ruta' => '../sdo-personal/hbqp_organigrama.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ]
        ]
    ],
    [
        'grupo' => 'Solicitudes de Compra',
        'items' => [
            [
                'icono' => '<i class="fas fa-shopping-bag fa-2x"></i>',
                'nombre' => 'SC - SDO',
                'ruta' => '../sdo-personal/soliCompra_sdo_administracion.php',
                'departamentos' => [8],
            ],
        ]
    ],
    [
        'grupo' => 'Tickets de Soporte (Sistema SIGH)',
        'items' => [
            [
                'icono' => '<i class="fas fa-ticket-alt fa-2x"></i>',
                'nombre' => 'Tickets Recibidos',
                'ruta' => '../sdo-personal/solicitudes_recibidas.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-check fa-2x"></i>',
                'nombre' => 'Tickets Finalizados',
                'ruta' => '../sdo-personal/solicitudes_finalizadas.php',
                'departamentos' => [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 13],
            ],
            [
                'icono' => '<i class="fas fa-times fa-2x"></i>',
                'nombre' => 'Tickets Cancelados',
                'ruta' => '../sdo-personal/solicitudes_canceladas.php',
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
    'bg-gradient-primary text-white',
    'bg-gradient-secondary text-white',
    'bg-gradient-info text-white',
    'bg-gradient-purple text-white',
    'bg-gradient-success text-white',
    'bg-gradient-warning text-black',
    'bg-gradient-danger text-white'
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
<?php
if($nomUsuario == 'Pablo Sanchez Molina'){
    ?>
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold text-uppercase font-weight-bold text-black mb-3 grupo-titulo">
            Activar RRHH                        </h6>
        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3 position-relative item-modulo" data-nombre="ast">
                <a href="../sdo-personal/activarRRHH.php" class="btn btn-sm bg-gradient-primary text-white w-100 h-100 text-center p-3 shadow rounded position-relative" style="text-decoration: none;">
                    <div><i class="fas fa-file fa-2x"></i></div>
                    <strong>Activar RRHH</strong>
                </a>

            </div>
        </div>
    </div>
</div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold text-uppercase font-weight-bold text-black mb-3 grupo-titulo">
                Documentacion General                       </h6>
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3 position-relative item-modulo" data-nombre="ast">
                    <a href="../sdo-personal/documentacion_general.php" class="btn btn-sm bg-gradient-primary text-white w-100 h-100 text-center p-3 shadow rounded position-relative" style="text-decoration: none;">
                        <div><i class="fas fa-file fa-2x"></i></div>
                        <strong>Documentacion General</strong>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold text-uppercase font-weight-bold text-black mb-3 grupo-titulo">
                Adm. de Funcionarios RRHH                       </h6>
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3 position-relative item-modulo" data-nombre="ast">
                    <a href="../sdo-personal/credenciales_administracion.php" class="btn btn-sm bg-gradient-primary text-white w-100 h-100 text-center p-3 shadow rounded position-relative" style="text-decoration: none;">
                        <div><i class="fas fa-users fa-2x"></i></div>
                        <strong>Adm. de Funcionarios RRHH</strong>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<?php
foreach ($modulosInicio as $grupo) {
    $botonesPermitidos = array_filter($grupo['items'], function ($item) use ($idDepartamento) {
        return in_array($idDepartamento, $item['departamentos']);
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

<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Registrar Asistencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../sdo-funciones/asistencia_registrar.php" method="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreFuncionario" style="font-size:15px">Nombre Funcionario:<code>*</code></label>
                        <input type="text" class="form-control" required id="nombreFuncionario" name="nombreFuncionario" placeholder="Nombre de Funcionario" value="<?php echo $nomUsuario; ?>" readonly="readonly">
                    </div>
                    <?php
                    $now = date("Y-m-d");
                    ?>
                    <div class="form-group">
                        <label for="fechaAsistencia" style="font-size:15px">Fecha Asistencia:<code>*</code></label>
                        <input type="date" class="form-control" required id="fechaAsistencia" name="fechaAsistencia" value="<?php echo $now; ?>" readonly="readonly">
                    </div>
                    <div class="form-group">
                        <label for="turnoFuncionario" style="font-size:15px">Turno Funcionario:<code>*</code></label>
                        <select id="turnoFuncionario" name="turnoFuncionario" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Turno Dia">Turno Día</option>
                            <option value="Turno Noche">Turno Noche</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="departamentoFuncionario" style="font-size:15px">Departamento de Turno:<code>*</code></label>
                        <select id="departamentoFuncionario" name="departamentoFuncionario" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $departamentos = [];

                            $queryDepartamentos1 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN('Informática', 'Ingeniería Operacional', 'Control Centralizado')";
                            $resDepartamentos1 = mysqli_query($conexion, $queryDepartamentos1);

                            // Guardar en array con nombre modificado si aplica
                            while ($row = $resDepartamentos1->fetch_assoc()) {
                                $idDepa = $row['idDepartamento'];
                                $nomDepa1 = $row['nomDepartamento'];

                                if ($nomDepa1 == 'Instrumentación') {
                                    $nomDepa1 = 'Control Centralizado';
                                }

                                $departamentos[] = ['id' => $idDepa, 'nombre' => $nomDepa1];
                            }

                            // Ordenar alfabéticamente por el nombre mostrado
                            usort($departamentos, function ($a, $b) {
                                return strcmp($a['nombre'], $b['nombre']);
                            });


                            foreach ($departamentos as $dep) {
                                if($departamentoPersonal == 'Instrumentación'){
                                    $departamentoPersonal = 'Control Centralizado';
                                }


                                $selectedDepartamento = "";
                                if($dep['nombre'] == $departamentoPersonal){

                                    $selectedDepartamento = "selected";
                                }
                                ?>
                                <option value="<?php echo htmlspecialchars($dep['nombre']) ?>" <?php echo $selectedDepartamento; ?>><?php echo htmlspecialchars($dep['nombre']) ?></option>

                                <?php
                            } ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" id="btnGuardarAsistencia" class="btn btn-success" disabled><i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                </div>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const turnoSelect = document.getElementById('turnoFuncionario');
                    const departamentoSelect = document.getElementById('departamentoFuncionario');
                    const submitBtn = document.getElementById('btnGuardarAsistencia');

                    function validarFormulario() {
                        const turnoVal = turnoSelect.value.trim();
                        const departamentoVal = departamentoSelect.value.trim();
                        submitBtn.disabled = (turnoVal === '' || departamentoVal === '');
                    }

                    turnoSelect.addEventListener('change', validarFormulario);
                    departamentoSelect.addEventListener('change', validarFormulario);

                    // Ejecutar al cargar
                    validarFormulario();
                });
            </script>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Habilitar Informe EDAN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../sdo-personal/sdo-includes/habilitar_edac.php" method="POST">
                <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario ?>">
                <div class="modal-body">
                    <label for="fechaInicio">Fecha de Inicio</label>
                    <br>
                    <input type="datetime-local" required style="width: 100%" class="form-control-border form-control" id="fechaInicio" name="fechaInicio" value="<?php echo date('Y-m-d H:i') ?>">
                    <br>
                    <label for="fechaTermino">Fecha de Termino</label>
                    <br>
                    <input type="datetime-local" required style="width: 100%" class="form-control-border form-control" id="fechaTermino" name="fechaTermino">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Habilitar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- 🔍 Script de filtrado -->
<script>
    $(document).ready(function() {
        $("#buscadorModulos").on("keyup", function() {
            const texto = $(this).val().toLowerCase().trim();

            $(".grupo-modulo").each(function() {
                const grupo = $(this);
                const nombreGrupo = grupo.data("grupo");
                let visible = false;

                // Buscar coincidencias dentro de los items del grupo
                grupo.find(".item-modulo").each(function() {
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