<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'] ?? null;
$nomUsuario = $_SESSION['nomUsuario'] ?? null;
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'] ?? null;

// 1. Manejo y sanitización de idTG (Asegura protección contra SQLi)
$idTG = $_POST['idTG'] ?? $_SESSION['idTG'] ?? null;

if (!$idTG || !is_numeric($idTG) || $idTG <= 0) {
    header('Location: trabajos_general_listado.php?errorDetalle=true');
    exit();
}
$_SESSION['idTG'] = $idTG;

// 2. Consulta Preparada para obtener datos de trabajos_general
$sql = "SELECT ticketAsociado, folioTG, estadoTG, solicitanteTG, detalleTG, fechaTG, 
               turnoTG, tecnico1, fechaProgramacion, detalleTurnoCamara, detalleTurnoLLE, 
               detalleTurnoCA, detalleTurnoRondas, detalleOtros, departamento, 
               formularioRondas, tipoTrabajo, servicio 
        FROM trabajos_general WHERE idTG = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $idTG);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($res)) {
    $ticket = $row['ticketAsociado'];
    $folioTG = $row['folioTG'];
    $estadoTG = $row['estadoTG'];
    $solicitanteTG = $row['solicitanteTG'];
    $detalleTG = $row['detalleTG'];
    $fechaTG = $row['fechaTG'];
    $turnoTG = $row['turnoTG'];
    $tecnico1 = $row['tecnico1'];
    $fechaTG2 = strtotime($fechaTG);
    $fechaTG3 = fechaEspañol2($fechaTG2);
    $fechaProgramacion = $row['fechaProgramacion'];
    $fechaProgram1 = strtotime($fechaProgramacion);
    $fechaProgram2 = fechaEspañol2($fechaProgram1);
    $detalleTurnoCamara = $row['detalleTurnoCamara'];
    $detalleTurnoLLE = $row['detalleTurnoLLE'];
    $detalleTurnoCA = $row['detalleTurnoCA'];
    $detalleTurnoRonda = $row['detalleTurnoRondas'];
    $detalleOtros = $row['detalleOtros'];
    $departamentos = $row['departamento'];
    $formularioRondas = $row['formularioRondas'];
    $tipoTrabajo = $row['tipoTrabajo'];
    $servicio = $row['servicio'];
}
mysqli_stmt_close($stmt);

// Función auxiliar para compactar la lógica idéntica de descargas ZIP
function procesarDescargaZip($rutaOrigen, $archivoZipNombre) {
    ini_set("memory_limit", "5256M");
    $zip = new ZipArchive();
    $rutaFinal = "../sdo-administrador/";

    if ($zip->open($archivoZipNombre, ZipArchive::CREATE) === true) {
        if (strpos($archivoZipNombre, "Archivos-") !== false) {
            agregar_zip($rutaOrigen, $zip);
        } else {
            agregar_zipSC($rutaOrigen, $zip);
        }
        $zip->close();

        if (file_exists($rutaFinal . $archivoZipNombre)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($archivoZipNombre));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivoZipNombre));
            ob_clean();
            flush();
            readfile($archivoZipNombre);
            unlink($archivoZipNombre);
            exit;
        }
    }
    return "N";
}

// 3. Procesamiento seguro de descargas ZIP
if (isset($_POST['folioDocumento']) && isset($_POST['idArchivoDocu']) && (int)$_POST['idArchivoDocu'] > 0) {
    $folioDocumento = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folioDocumento']);
    $dir = "../sdo-administrador/archivos-recepcion-documentos/folio-" . $folioDocumento . "/";
    $archivoZip = "Documentos-" . $folioDocumento . ".zip";
    $descargaConfirma .= procesarDescargaZip($dir, $archivoZip);
}

if (isset($_POST['idTG1']) && (int)$_POST['idTG1'] > 0) {
    $idTG1 = (int)$_POST['idTG1'];
    $folioTG_Post = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folio']);

    $dir = ($idTG1 > 116)
        ? "../sdo-archivosOT/" . $folioTG_Post . "/"
        : "../sdo-administrador/archivos-trabajo-general/folio-" . $folioTG_Post . "/carpeta-" . $idTG1 . "/";

    $archivoZip = "Documentos-" . $folioTG_Post . ".zip";
    $descargaConfirma .= procesarDescargaZip($dir, $archivoZip);
}

// 4. Consulta Preparada para ordenes_trabajos_general
$idOrden = null;
$folio = null;
if (isset($folioTG)) {
    $sqlTrabajo = "SELECT idOrden, folioOrden FROM ordenes_trabajos_general WHERE noOrdenTrabajo = ?";
    $stmtTrabajo = mysqli_prepare($conexion, $sqlTrabajo);
    mysqli_stmt_bind_param($stmtTrabajo, "s", $folioTG);
    mysqli_stmt_execute($stmtTrabajo);
    $resTrabajo = mysqli_stmt_get_result($stmtTrabajo);
    if ($rowTrabajo = mysqli_fetch_assoc($resTrabajo)) {
        $idOrden = $rowTrabajo['idOrden'];
        $folio = $rowTrabajo['folioOrden'];
    }
    mysqli_stmt_close($stmtTrabajo);
}

// 5. Contadores y helpers auxiliares
$contarHT = contarHT1($folioTG);
$contarBMS = contarBMS1($folioTG);
$contarCA = contarCA1($folioTG);
$contarCCTV = contarCCTV1($folioTG);
$contarRondas = contarRondas1($folioTG);
$contarLLE = contarLLE1($folioTG);
$contarOtros = contarOtros1($folioTG);
$buscarNombreServicio = buscarNombreServicio($servicio);
$contarArchivos = contarArchivosOT($idTG);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Orden de Trabajo</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link class="datatables-css" rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        .info-box-sm {
            min-height: 62px !important;
            margin-bottom: 8px !important;
            padding: 6px 12px !important;
            box-shadow: 0 0 1px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.08);
            border-radius: 0.25rem;
            background: #fff;
            border-left: 3px solid #007bff;
        }
        .info-box-sm.state-style {
            border-left: 3px solid #28a745;
        }
        .info-box-sm .info-box-text {
            font-size: 11px !important;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 1px;
            display: block;
        }
        .info-box-sm .info-box-number {
            font-size: 13.5px !important;
            color: #212529;
            font-weight: 700;
        }
        .fieldset-compact {
            border: 1px solid #dee2e6 !important;
            padding: 12px !important;
            margin-bottom: 10px !important;
            border-radius: 4px;
            background-color: #fafafa;
        }
        .fieldset-compact legend {
            font-size: 14px !important;
            font-weight: bold;
            color: #495057;
            width: auto !important;
            padding: 0 8px !important;
            margin-bottom: 0px !important;
        }
        .desc-p {
            font-size: 13.5px;
            line-height: 1.5;
            color: #333;
            margin-bottom: 0;
        }
        .nav-tabs-master .nav-link {
            color: #495057;
            font-weight: 600;
            padding: 10px 20px;
        }
        .nav-tabs-master .nav-link.active {
            color: #007bff !important;
            border-top: 3px solid #007bff;
            background-color: #fff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header py-2">
            <div class="container-fluid">
                <div class="row mb-3 bg-white p-2 rounded border shadow-sm mx-0 align-items-center">
                    <div class="col-12 col-md-8 col-xl-9 mb-2 mb-md-0">
                        <h6 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-file-alt text-primary mr-2"></i>
                            Orden de Trabajo N°: <span class="text-primary"><?php echo $folioTG; ?></span>
                        </h6>
                    </div>

                    <div class="col-12 col-md-4 col-xl-3 text-md-right">
                        <a href="trabajos_general_listado.php" class="btn btn-xs bg-gradient-red text-white btn-block btn-md-inline-block">
                            <i class="fa fa-chevron-left mr-1"></i> Volver Atrás
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary card-outline card-outline-tabs shadow-sm mb-4">
                    <div class="card-header p-0 border-bottom-0 bg-light">
                        <ul class="nav nav-tabs nav-tabs-master" id="masterTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-detalle-master" data-toggle="pill" href="#paneDetalleMaster" role="tab" aria-selected="true">
                                    <i class="fas fa-info-circle mr-1"></i> Detalle de la OT
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-lineavida-master" data-toggle="pill" href="#paneLineaVidaMaster" role="tab" aria-selected="false">
                                    <i class="fas fa-history mr-1"></i> Línea de Vida de la OT
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-parametros-master" data-toggle="pill" href="#paneParametrosMaster" role="tab" aria-selected="false">
                                    <i class="fas fa-sliders-h mr-1"></i> Parámetros de Pabellón
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content" id="masterTabContent">

                            <div class="tab-pane fade show active" id="paneDetalleMaster" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos_tab/ot_detalle.php'; ?>
                            </div>

                            <div class="tab-pane fade" id="paneLineaVidaMaster" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos_tab/ot_linea_vida.php'; ?>
                            </div>

                            <div class="tab-pane fade" id="paneParametrosMaster" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos_tab/ot_parametros.php'; ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <?php include '../sdo-includes/ordenes_trabajos_tab/ot_modales_generales.php'; ?>

    <footer class="main-footer py-2" style="font-size:12px;"><strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong></footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {
        // Corrección de redimensionamiento de DataTables en el cambio de pestañas
        $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        $('#observacionTotal').DataTable({
            "paging": true, "lengthChange": false, "searching": true, "ordering": false, "info": true, "autoWidth": false, "responsive": true,
            "language": { "lengthMenu": "Visualizando _MENU_ registros", "zeroRecords": "No se han encontrado registros.", "search": "Buscar:", "paginate": { "previous": "Anterior", "next": "Siguiente" } }
        });

        $('#obsercacion2').DataTable({
            "paging": true, "lengthChange": false, "searching": true, "ordering": false, "info": true, "autoWidth": false, "responsive": true,
            "language": { "zeroRecords": "No se han encontrado registros.", "search": "Buscar:", "paginate": { "previous": "Anterior", "next": "Siguiente" } }
        });
    });
</script>

<script>
    $(document).ready(function() {
        var columns = [ {"data": "fechaRegistroPab"}, {"data": "nomRegistroPab"} ];
        for (var i = 2; i <= 10; i++) { columns.push({"data": "valorTotal" + i}); }

        columns.push(
            { "data": null, "render": function (data, type, row) { return (row.correoEnviado === 0) ? '<button class="btn btn-xs btn-primary btn-confirm" data-id="' + row.idRegistroPab + '"><i class="fas fa-envelope"></i></button>' : '<button class="btn btn-xs btn-primary btn-confirm" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-envelope"></i></button>'; } },
            { "data": null, "render": function (data, type, row) { return (row.correoEnviado === 0) ? '<button class="btn btn-xs btn-warning btn-editar text-white" data-id="' + row.idRegistroPab + '"><i class="fas fa-pen"></i></button>' : '<button class="btn btn-xs btn-warning btn-editar text-white" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-pen"></i></button>'; } },
            { "data": null, "render": function (data, type, row) { return '<button class="btn btn-xs btn-danger btn-eliminar" data-id="' + row.idRegistroPab + '"><i class="fas fa-trash"></i></button>'; } }
        );

        $('#example2').DataTable({
            "lengthChange": false, "searching": true, "autoWidth": false, "responsive": true, "ordering": false,
            "language": { "zeroRecords": "No se han encontrado registros.", "search": "Buscar:", "paginate": { "previous": "Anterior", "next": "Siguiente" } },
            "ajax": { "url": "ajax-pabCorreo-listado.php", "type": "POST", "dataSrc": "" },
            "columns": columns,
            "createdRow": function (row, data, dataIndex) { $('td', row).css({ 'text-align': 'center', 'vertical-align': 'middle', 'border': '1px solid #dddddd' }); }
        });

        $('#example2').on('click', '.btn-confirm', function () {
            var id = $(this).data('id');
            Swal.fire({ title: '¿Estás seguro?', text: 'Esta acción no se puede revertir.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, confirmar' }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('<form>', { 'action': '../sdo-funciones/pabellones_enviarCorreo.php', 'method': 'POST' });
                    form.append($('<input>', { 'type': 'hidden', 'name': 'idRegPab', 'value': id }));
                    form.appendTo('body').submit();
                }
            });
        });

        $('#example2').on('click', '.btn-editar', function () {
            var idRegistro = $(this).data('id');
            $.ajax({ url: 'sdo-includes/parametros_pabellon_modificar.php', type: 'POST', data: {id: idRegistro}, dataType: 'json', success: function (data) { for (var key in data) { $('#' + key).val(data[key]); } $('#modalEditar').modal('show'); } });
        });

        $('#example2').on('click', '.btn-eliminar', function () {
            var id = $(this).data('id');
            Swal.fire({ title: '¿Estás seguro?', text: 'Esta acción no se puede revertir.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, confirmar' }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('<form>', { 'action': '../sdo-funciones/pabellones_eliminarParametros.php', 'method': 'POST' });
                    form.append($('<input>', { 'type': 'hidden', 'name': 'idRegPab', 'value': id }));
                    form.appendTo('body').submit();
                }
            });
        });

        $('.terminartodo').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('name');
            Swal.fire({ icon: 'question', title: '¿Desea finalizar esta OT?', text: 'Esta acción no se puede revertir', showCancelButton: true, confirmButtonText: 'Sí, finalizar' }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ url: '../sdo-funciones/finalizar_OT.php', type: 'POST', data: 'id=' + id, success: function(response) { Swal.fire({ icon: 'success', title: 'OT finalizada', showConfirmButton: false, timer: 2000 }); setTimeout(function() { location.href = "trabajos_general_listado_finalizados.php"; }, 2100); } });
                }
            });
        });
    });
</script>

</body>
</html>