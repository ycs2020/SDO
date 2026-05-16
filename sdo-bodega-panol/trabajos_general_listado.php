<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Control de seguridad básico para evitar Notice de PHP
$idUsuario   = $_SESSION['idUsuario'] ?? null;
$userUsuario = $_SESSION['userUsuario'] ?? null;
$rolUsuario  = $_SESSION['rolUsuario'] ?? null;
$rolUsuario2 = $rolUsuario ? strtolower($rolUsuario) : '';
$nomUsuario  = $_SESSION['nomUsuario'] ?? null;
$descargaConfirma = "";

date_default_timezone_set('America/Santiago');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Trabajos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- Custom CSS (Dark Mode) -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <style>
        /* Alineación horizontal y vertical para todo el cuerpo de la tabla de OTs */
        #tablaOTEjecucion tbody td {
            vertical-align: middle !important;
            text-align: center !important;
        }

        /* Alineación vertical para las cabeceras por si acaso */
        #tablaOTEjecucion thead th {
            vertical-align: middle !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <!-- Navbar / Sidebar -->
    <?php include '../sdo-templates/bodega-panol-sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <!-- Título con bordes redondeados -->
                    <div class="col-xl-9 col-lg-9 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100 m-0 rounded-3 overflow-hidden border-0 evaluation-card">
                            <div class="card-body d-flex align-items-center bg-gradient-primary p-3">
                                <h5 class="mb-0 text-white">Listado de Ordenes de Trabajo</h5>
                            </div>
                        </div>
                    </div>
                    <!-- Botón Volver (Toda la tarjeta es el botón y es redondeada) -->
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3 d-flex">
                        <div class="card w-100 m-0 rounded-3 overflow-hidden border-0 bg-gradient-danger p-0 shadow-sm">
                            <a href="index.php" class="text-white w-100 h-100 d-flex align-items-center justify-content-center p-3 text-decoration-none btn-block transition-all" style="transition: background 0.2s ease;">
                                <i class="fa fa-chevron-left mr-2"></i> Volver Atrás
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tablaOTEjecucion" class="table table-bordered table-striped cell-border w-100" style="font-size:85%">
                                        <thead class="thead-dark text-center">
                                        <tr>
                                            <th>Fecha de Trabajo</th>
                                            <th>Folio de Trabajo</th>
                                            <th>Turno de Trabajo</th>
                                            <th>Solicitante</th>
                                            <th>Técnico Responsable</th>
                                            <th>Estado</th>
                                            <th>Descripción</th> <!-- Agregada -->
                                            <th>Información</th>
                                            <th>Detalles</th>
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

    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - <?= date('Y'); ?>. Derechos Reservados</strong>
    </footer>
</div>

<!-- ==========================================
     SCRIPTS (Carga e Inicialización)
     ========================================== -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 Bundle -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- DataTables & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Summernote -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<!-- Dark Mode Toggle -->
<script src="js/dark-mode.js"></script>

<!-- Carga de tu archivo de configuración base reutilizable -->
<script src="../dist/js/datatables-config.js"></script>

<!-- LÓGICA JAVASCRIPT DE LA PÁGINA -->
<script>
    // 1. FUERA DEL READY: Configuración global e inmediata de Toastr
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": true
    };

    // 2. DENTRO DEL READY: Inicialización fusionando la configuración base
    $(document).ready(function() {
        $('#tablaOTEjecucion').DataTable({
            ...dataTableConfig,
            "ordering": false,
            "order": [[0, "desc"]], // Sigue ordenando por Fecha Programación inicialmente
            "ajax": {
                "url": "../sdo-includes/ordenes_trabajos/ot_ejecucion_listado.php",
                "type": "POST",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "fecha_programacion" },
                {
                    "data": "folio_trabajo",
                    "className": "text-center",
                    "orderable": false // <- AGREGA ESTO para bloquear que ordenen por folio y se mantenga el correlativo intacto
                },
                { "data": "turno_trabajo" },
                { "data": "solicitante" },
                { "data": "tecnico_responsable" },
                { "data": "estado", "className": "text-center" },
                { "data": "descripcion" },
                { "data": "informacion", "className": "text-center" },
                { "data": "detalles", "className": "text-center" }
            ]
        });
    });
</script>

</body>
</html>