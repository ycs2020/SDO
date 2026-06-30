<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');
$fecha = date('d-m-Y H:i');

$rutasRoles = [
    "personal" => "sdo-personal",
    "administrativo" => "sdo-admtec",
    "bodega pañol" => "sdo-bodega-panol",
    "jefe tecnico" => "sdo-jefestecnicos",
    "administrador" => "sdo-administrador",
    "directiva" => "sdo-directiva",
    "oficina tecnica" => "sdo-oficinaTecnica"
];

if (array_key_exists($rolUsuario2, $rutasRoles)) {
    $tipoUsuario = $rolUsuario2;
    $rutaDireccion = $rutasRoles[$rolUsuario2];
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$datosTanque = obtenerDatosTanque();

if ($datosTanque !== 'null' && is_array($datosTanque)) {
    $idTanque = $datosTanque['idTanque'];
    $codigoClienteTanque = $datosTanque['codigoClienteTanque'];
    $nomClienteTanque = $datosTanque['nomClienteTanque'];
    $direccionTanque = $datosTanque['direccionTanque'];
    $tipoProductoTanque = $datosTanque['tipoProductoTanque'];
    $numSerieTanque = $datosTanque['numSerieTanque'];
    $capacidadTanqueIN = $datosTanque['capacidadTanqueIN'];
    $capacidadTanqueM3 = $datosTanque['capacidadTanqueM3'];
    $capacidadTanqueGAL = $datosTanque['capacidadTanqueGAL'];
    $fechaActivacion = $datosTanque['fechaActivacion'];
} else {
    header('Location: sistemas-listado.php?errorTanqueIndura=true');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tanque Indura | Control Centralizado</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <style>
        .description-block > .description-header {
            font-size: 1.4rem;
            font-weight: 700;
            color: #007bff;
        }

        .dark-mode .description-block > .description-header {
            color: #3f9af7;
        }

        .custom-select-sm-table {
            max-width: 180px;
            display: inline-block;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-xl-9 col-lg-8 col-md-7 col-12 mb-2 mb-md-0">
                        <div class="card h-100 mb-0 shadow-sm border-0">
                            <div class="card-header bg-primary text-white d-flex align-items-center">
                                <h5 class="mb-0 w-100 font-weight-bold" id="tanqueIndura">
                                    <i class="fas fa-info-circle"></i> Datos técnicos Indura
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-5 col-12">
                        <a href="index.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center shadow-sm">
                            <i class="fa fa-chevron-left mr-2"></i> Volver Atrás
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <?php

        if (isset($_GET['mensajeVar']) && isset($_SESSION['varRespuesta'])) {
            $varRespuesta = $_SESSION['varRespuesta'];

            if (!empty($varRespuesta)) {

                ?>
                <script>
                    Swal.fire(
                        '<?php echo addslashes($varRespuesta); ?>', // Asegúrate de escapar las comillas y caracteres especiales
                        '',
                        'info'
                    );
                </script>
                <?php
            }

            $_SESSION['varRespuesta'] = '';
        }
        ?>

        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="card card-primary card-outline shadow-sm">
                            <div class="card-body box-profile">
                                <div class="text-center mb-3">
                                    <img class="img-fluid" style="max-width: 160px;" src="../sdo-administrador/img/controlCent-HBQP.png" alt="Control Centralizado">
                                </div>
                                <h3 class="profile-username text-center font-weight-bold text-sm mb-3"><?= htmlspecialchars($numSerieTanque); ?></h3>
                                <?php

                                $obtenerUltimoRegistroTanqueIndura = obtenerUltimoRegistroTanqueIndura();

                                if ($obtenerUltimoRegistroTanqueIndura !== 'null' && is_array($obtenerUltimoRegistroTanqueIndura)) {
                                    $dataUltRegTanque = $obtenerUltimoRegistroTanqueIndura;
                                    $fechaMedicionReg2 = date('d/m/Y H:i', strtotime($dataUltRegTanque['fechaMedicion'])) . " hrs.";
                                    ?>

                                    <h3 class="profile-username text-center text-sm mb-3">
                                        <b>&Uacute;ltima Actualización: </b><?= htmlspecialchars($fechaMedicionReg2); ?>
                                    </h3>
                                    <?php

                                    $fechaMedicion24 = 'Sin Información';
                                    $sqlFecha = "SELECT fechaMedicion FROM medicion_tanque_indura WHERE fuenteMedicion LIKE '%Driver after delivery%' OR fuenteMedicion LIKE '%Conductor Después de la Entrega%' ORDER BY fechaMedicion DESC LIMIT 1;";
                                    $resFecha = mysqli_query($conexion, $sqlFecha);
                                    if ($resFecha && $rowFecha = $resFecha->fetch_assoc()) {
                                        $fechaMedicion24 = date('d/m/Y H:i \h\r\s\.', strtotime(str_replace('/', '-', $rowFecha['fechaMedicion'])));
                                    }

                                    ?>

                                    <h3 class="profile-username text-center text-sm mb-3">
                                        <b>&Uacute;ltima Recarga: </b><?= htmlspecialchars($fechaMedicion24); ?>
                                    </h3>
                                    <?php

                                }

                                ?>

                                <ul class="list-group list-group-unbordered text-sm">
                                    <li class="list-group-item">
                                        <b>Tipo:</b>
                                        <span class="float-right text-muted"><?= htmlspecialchars($tipoProductoTanque); ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad (IN):</b>
                                        <span class="float-right text-muted"><?= htmlspecialchars($capacidadTanqueIN); ?> IN</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad (M³):</b>
                                        <span class="float-right text-muted"><?= htmlspecialchars($capacidadTanqueM3); ?> M³</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Capacidad (Gal):</b>
                                        <span class="float-right text-muted"><?= htmlspecialchars($capacidadTanqueGAL); ?> Gal</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Cliente:</b>
                                        <span class="float-right text-truncate text-muted" style="max-width: 140px;"><?= htmlspecialchars($nomClienteTanque); ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Código:</b>
                                        <span class="float-right text-muted"><?= htmlspecialchars($codigoClienteTanque); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-body">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4">
                                    <h5 class="card-title m-0 text-muted mb-2 mb-sm-0">
                                        <i class="far fa-chart-bar"></i> Lecturas Actuales
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <select id="filtroAnio" class="form-control form-control-sm mr-2" style="max-width: 120px;">
                                            <?php
                                            // Generamos dinámicamente los años disponibles
                                            $sqlAnios = "SELECT DISTINCT YEAR(fechaMedicion) AS anio FROM medicion_tanque_indura WHERE tanqueMedicion = ? ORDER BY anio DESC";
                                            if ($stmtAnios = $conexion->prepare($sqlAnios)) {
                                                $stmtAnios->bind_param("s", $idTanque);
                                                $stmtAnios->execute();
                                                $resAnios = $stmtAnios->get_result();

                                                $esPrimerAnio = true; // Variable bandera para identificar el año más reciente

                                                while ($rowAnio = $resAnios->fetch_assoc()) {
                                                    if ($rowAnio['anio']) {
                                                        // Si es el año más reciente, le añadimos 'selected' por defecto
                                                        $selected = $esPrimerAnio ? 'selected' : '';
                                                        echo '<option value="' . $rowAnio['anio'] . '" ' . $selected . '>' . $rowAnio['anio'] . '</option>';

                                                        $esPrimerAnio = false; // Desactivamos la bandera para los siguientes años
                                                    }
                                                }
                                                $stmtAnios->close();
                                            }
                                            ?>
                                            <option value="">Todos los años</option>
                                        </select>

                                        <button type="button" data-toggle="modal" data-target="#modalSubirExcel" class="btn btn-sm btn-success">
                                            <i class="fa fa-upload"></i> Importar Excel (.xlsx)
                                        </button>
                                    </div>
                                </div>

                                <?php
                                $obtenerUltimoRegistroTanqueIndura = obtenerUltimoRegistroTanqueIndura();

                                if ($obtenerUltimoRegistroTanqueIndura !== 'null' && is_array($obtenerUltimoRegistroTanqueIndura)) {
                                    $dataUltRegTanque = $obtenerUltimoRegistroTanqueIndura;
                                    $fechaMedicionReg2 = date('d/m/Y H:i', strtotime($dataUltRegTanque['fechaMedicion'])) . " hrs.";

                                    $unidadMedicionReg2 = mb_convert_case($dataUltRegTanque['unidadMedicion'], MB_CASE_UPPER, 'UTF-8');
                                    $lecturaMedicionReg = $dataUltRegTanque['lecturaMedicion'];

                                    $lecturaIN = $lecturaM3 = $lecturaGAL = $porcentajeEstanque = $nivelEstanqueOxGas2 = 'Sin Información';

                                    if ($lecturaMedicionReg > 0) {
                                        if ($unidadMedicionReg2 == 'IN') {
                                            $lecturaIN_val = $lecturaMedicionReg;
                                            $lecturaM3_val = ($lecturaMedicionReg * $capacidadTanqueM3) / $capacidadTanqueIN;
                                            $lecturaGAL_val = ($lecturaMedicionReg * $capacidadTanqueGAL) / $capacidadTanqueIN;
                                        } else if ($unidadMedicionReg2 == 'M³') {
                                            $lecturaIN_val = ($lecturaMedicionReg * $capacidadTanqueIN) / $capacidadTanqueM3;
                                            $lecturaM3_val = $lecturaMedicionReg;
                                            $lecturaGAL_val = ($lecturaMedicionReg * $capacidadTanqueGAL) / $capacidadTanqueM3;
                                        } else if ($unidadMedicionReg2 == 'GAL') {
                                            $lecturaIN_val = ($lecturaMedicionReg * $capacidadTanqueIN) / $capacidadTanqueGAL;
                                            $lecturaM3_val = ($lecturaMedicionReg * $capacidadTanqueM3) / $capacidadTanqueGAL;
                                            $lecturaGAL_val = $lecturaMedicionReg;
                                        }

                                        if (isset($lecturaIN_val)) {
                                            $porcentajeCalc = (intOdecimal($lecturaIN_val) * 100) / $capacidadTanqueIN;
                                            $porcentajeEstanque = ($unidadMedicionReg2 == 'IN') ? number_format($porcentajeCalc, 0, ',', '.') . '%' : intOdecimal($porcentajeCalc) . '%';

                                            $nivelEstanqueOxGas2 = intOdecimal($lecturaGAL_val * 3.78 * 0.86) . ' M³';
                                            $lecturaIN = intOdecimal($lecturaIN_val) . ' IN';
                                            $lecturaM3 = intOdecimal($lecturaM3_val) . ' M³';
                                            $lecturaGAL = intOdecimal($lecturaGAL_val) . ' GAL';
                                        }
                                    }

                                    ?>

                                    <div class="row border-bottom pb-2 mb-4 text-center justify-content-center">
                                        <div class="col-xl-2 col-md-4 col-6 border-right mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $lecturaIN; ?></h5>
                                                <span class="description-text text-xs text-muted">NIVEL PULGADAS</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-6 border-right mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $lecturaM3; ?></h5>
                                                <span class="description-text text-xs text-muted">OXÍGENO LÍQUIDO</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-6 border-right mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $nivelEstanqueOxGas2; ?></h5>
                                                <span class="description-text text-xs text-muted">OXÍGENO GASEOSO</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-6 border-right mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header"><?= $lecturaGAL; ?></h5>
                                                <span class="description-text text-xs text-muted">NIVEL EN GALONES</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-6 border-right mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header text-success"><?= $porcentajeEstanque; ?></h5>
                                                <span class="description-text text-xs text-muted">CAPACIDAD ESTANQUE</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-6 mb-3">
                                            <div class="description-block">
                                                <h5 class="description-header text-danger" id="contadorRecargas">0</h5>
                                                <span class="description-text text-xs text-muted" id="textoContadorRecargas">RECARGAS (TODO)</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="table-responsive">
                                    <input type="hidden" id="idTanqueDatos" value="<?= htmlspecialchars($idTanque); ?>">
                                    <table id="tableIndura" class="table table-sm table-hover table-bordered text-center w-100">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Fecha Captura</th>
                                            <th>Lectura</th>
                                            <th>Unidad</th>
                                            <th style="min-width: 220px;">Filtrar por Fuente</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <div class="modal fade" id="modalSubirExcel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="../sdo-includes/indura/indura_mediciones_subir.php" method="post" enctype="multipart/form-data">
                <div class="modal-header bg-success py-2">
                    <h5 class="modal-title text-sm">
                        <i class="fas fa-file-excel"></i> Subir Datos Indura
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <div class="form-group mb-0">
                        <label for="archivo_excel" class="text-xs text-muted">Seleccione la plantilla .xlsx consolidada</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="archivo_excel" name="archivo_excel" accept=".csv">
                            <label class="custom-file-label" for="archivo_excel">Buscar archivo...</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <button type="submit" class="btn btn-sm btn-primary">Procesar</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="main-footer text-xs">
        <strong>Copyright &copy; 2022 - <?= date('Y'); ?>. Control Centralizado</strong>
    </footer>
</div>

<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../dist/js/datatables-config.js"></script>

<script>
    // Busca tu script de DataTables y reemplaza la sección correspondiente:
    $(document).ready(function () {
        const customConfig = Object.assign({}, dataTableConfig, {
            "ajax": {
                "url": "../sdo-includes/indura/indura_mediciones_cargar.php",
                "type": "POST",
                "data": function (d) {
                    d.idTanque = $('#idTanqueDatos').val();
                    d.anio = $('#filtroAnio').val(); // <-- Captura automáticamente el año 'selected' por defecto
                },
                "dataSrc": function (json) {
                    if (!json || json.error) {
                        console.error("Fallo de respuesta:", json ? json.error : "Nulo");
                        $('#contadorRecargas').text(0);
                        return [];
                    }

                    // Actualizamos dinámicamente los elementos visuales del contador
                    let anioSeleccionado = $('#filtroAnio').val();
                    $('#contadorRecargas').text(json.totalRecargas);

                    if (anioSeleccionado !== "") {
                        $('#textoContadorRecargas').text("RECARGAS (" + anioSeleccionado + ")");
                    } else {
                        $('#textoContadorRecargas').text("RECARGAS (TODO)");
                    }

                    return json.datos;
                }
            },
            "columns": [
                {"data": "fechaMedicion2"},
                {"data": "lecturaMedicion"},
                {"data": "unidadMedicion"},
                {"data": "fuenteMedicion2"}
            ],
            initComplete: function () {
                this.api().columns([3]).every(function () {
                    let column = this;
                    let select = $('<select class="form-control form-control-sm custom-select-sm-table"><option value="">Todas las Fuentes</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            let val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function (d) {
                        if (d) select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });
            },
            "createdRow": function (row) {
                $('td', row).css({
                    'vertical-align': 'middle',
                    'text-align': 'center'
                });
            }
        });

        const table = $('#tableIndura').DataTable(customConfig);

        // Escuchador para recargar la tabla cuando cambie el selector de año manualmente
        $('#filtroAnio').on('change', function () {
            table.ajax.reload();
        });

        // Listener estético del input file del modal
        $('#archivo_excel').on('change', function () {
            let fileName = this.value.split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
</body>
</html>
