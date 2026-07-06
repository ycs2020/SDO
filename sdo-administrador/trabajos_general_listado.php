<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'] ?? null;
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = $rolUsuario ? strtolower($rolUsuario) : null;
$nomUsuario = $_SESSION['nomUsuario'] ?? null;

date_default_timezone_set('America/Santiago');

// Procesamiento seguro de descarga de archivos ZIP
if (isset($_POST['folio']) && isset($_POST['idTG1']) && !empty($_POST['idTG1'])) {
    $folioTG = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['folio']);
    $idTG1 = (int)$_POST['idTG1'];

    if ($idTG1 > 0 && !empty($folioTG)) {
        ini_set("memory_limit", "1256M");
        $dirOrigen = "../sdo-administrador/archivos-recepcion-documentos/folio-" . $folioTG . "/";
        $rutaDestino = "../sdo-administrador/";
        $nombreZip = "Documentos-" . $folioTG . ".zip";
        $rutaCompletaZip = $rutaDestino . $nombreZip;

        if (is_dir($dirOrigen)) {
            $zip = new ZipArchive();
            if ($zip->open($rutaCompletaZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                agregar_zip($dirOrigen, $zip);
                $zip->close();

                if (file_exists($rutaCompletaZip)) {
                    if (ob_get_level()) { ob_end_clean(); }
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . basename($nombreZip) . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($rutaCompletaZip));
                    readfile($rutaCompletaZip);
                    unlink($rutaCompletaZip);
                    exit;
                } else { $descargaConfirma = "N"; }
            } else { $descargaConfirma = "E"; }
        } else { $descargaConfirma = "D"; }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestión OT</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <style>
        .select2-container .select2-selection--single { height: calc(2.25rem + 2px); border: 1px solid #ced4da; border-radius: 0.25rem; }
        #tableListOT tbody td { vertical-align: middle !important; text-align: center !important; padding: 10px 8px; }
        #tableListOT thead th { vertical-align: middle !important; letter-spacing: 0.5px; }
        .fila-filtros th { padding: 5px 8px !important; background-color: #343a40 !important; }
        .fila-filtros select { height: calc(1.8rem + 2px) !important; font-size: 0.85rem !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row ">

                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3">
                        <div class="card bg-gradient-primary p-3 text-white h-100 d-flex justify-content-center mb-0">
                            <h5 class="mb-0">Gestión de Órdenes de Trabajo</h5>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="index.php" class="btn btn-danger btn-block h-100 d-flex align-items-center justify-content-center">
                            <i class="fa fa-chevron-left mr-2"></i> Volver
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-gradient-primary">
                        <ul class="nav nav-tabs" id="tabModulosOT" role="tablist">
                            <li class="nav-item"><a class="nav-link" id="tab-crear-ot" data-toggle="tab" href="#content-crear-ot"><i class="fas fa-pencil-alt mr-1"></i> Crear OT</a></li>
                            <li class="nav-item"><a class="nav-link active" id="tab-listado-ot" data-toggle="tab" href="#content-listado-ot"><i class="fas fa-list mr-1"></i> Listado OT</a></li>
                            <li class="nav-item"><a class="nav-link" id="tab-dashboard-ot" data-toggle="tab" href="#content-dashboard-ot"><i class="fas fa-chart-line mr-1"></i> Dashboard</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade" id="content-crear-ot" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos/ot_formulario_crear.php'; ?>
                            </div>

                            <div class="tab-pane fade show active" id="content-listado-ot" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos/ot_tabla_listado.php'; ?>
                            </div>

                            <div class="tab-pane fade" id="content-dashboard-ot" role="tabpanel">
                                <?php include '../sdo-includes/ordenes_trabajos/ot_dashboard_visor.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/datatables-config.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script src="../sdo-includes/ordenes_trabajos_js/ot_modulo_creacion.js"></script>
<script src="../sdo-includes/ordenes_trabajos_js/ot_modulo_listado.js"></script>

<script>
    $(document).ready(function() {
        // Escucha de forma activa el cambio a la pestaña del Dashboard
        $('#tab-dashboard-ot').on('shown.bs.tab', function () {
            if (typeof window.inicializarGraficosDashboard === 'function') {
                window.inicializarGraficosDashboard();
            }
        });

        // Evento delegado de filtrado (funciona incluso si el formulario se reemplaza por AJAX)
        $(document).on('click', '#btnFiltrarDashboard', function() {
            const dataForm = $('#formFiltrosDashboard').serialize();
            $.ajax({
                url: '../sdo-includes/ordenes_trabajos/ot_dashboard_visor.php',
                type: 'POST',
                data: dataForm,
                success: function(htmlResponse) {
                    $('#content-dashboard-ot').html(htmlResponse);
                    if (typeof window.inicializarGraficosDashboard === 'function') {
                        window.inicializarGraficosDashboard();
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.success('DASHBOARD FILTRADO CORRECTAMENTE.');
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('ERROR AL PROCESAR LOS FILTROS.');
                    }
                }
            });
        });
    });
</script>
</body>
</html>

