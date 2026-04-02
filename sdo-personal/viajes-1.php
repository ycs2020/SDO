<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];

date_default_timezone_set('America/Santiago');

function generarCard($titulo, $subtitulo, $imagen, $url) {
    return <<<HTML
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
    <div class="card h-100 shadow-sm border-0 elevation-1">
        <div class="card-body text-center p-4">
            <img src="{$imagen}" class="img-fluid rounded mb-3" style="height: 180px; width: 100%; object-fit: cover;">
            <h5 class="font-weight-bold mb-1">{$titulo}</h5>
            <p class="text-muted small">{$subtitulo}</p>
            <a href="{$url}" class="btn btn-info btn-block mt-3 shadow-sm">
                <i class="fas fa-external-link-alt mr-1"></i> Visualizar
            </a>
        </div>
    </div>
</div>
HTML;
}

// El array con tus datos
$proyectos = [
    [
        "titulo"    => "Viajes Contrato Antiguo",
        "subtitulo" => "Información de Viajes",
        "imagen"    => "../dist/img/HBQP_h180.png",
        "url"       => "listado-viajes.php"
    ],
    [
        "titulo"    => "Viajes Contrato Nuevo",
        "subtitulo" => "Información de Viajes",
        "imagen"    => "../dist/img/HBQP_h180.png",
        "url"       => "listado-viajes-contra-nuevo.php"
    ],
    [
        "titulo"    => "Viajes Contrato Vamoos SPA",
        "subtitulo" => "Información de Viajes",
        "imagen"    => "../dist/img/HBQP_h180.png",
        "url"       => "listado-viajes-vamoos-spa.php"
    ]
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Viajes Movilizaci&oacute;n</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="../plugins/chart.js/Chart.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-10 col-9">
                        <div class="card bg-primary text-white shadow-sm mb-0">
                            <div class="card-body py-2 text-center">
                                <h5 class="mb-0"><i class="fas fa-industry mr-2"></i> Viajes Movilizaci&oacute;n</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-3">
                        <a href="index.php" class="btn btn-danger btn-block font-weight-bold shadow-sm">
                            <i class="fa fa-chevron-left"></i> VOLVER
                        </a>
                    </div>
                </div>

                <div class="card card-primary card-outline card-tabs shadow-sm">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="tabConsumos" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#contenedor" onclick="cargarMenuHospitales('electricidad')">
                                    <i class="fas fa-ambulance text-warning mr-1"></i> Viajes Movilizaci&oacute;n
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h3 id="titulo-seccion" class="font-weight-bold text-secondary">Viajes Movilizaci&oacute;n</h3>
                            <hr style="width: 50px; border-top: 3px solid #007bff;">
                        </div>

                        <div id="contenedor-principal" class="mt-3">
                            <div class="row justify-content-center">
                                <?php
                                foreach ($proyectos as $p) {
                                    echo generarCard(
                                        $p['titulo'],
                                        $p['subtitulo'],
                                        $p['imagen'],
                                        $p['url']
                                    );
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>

<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

</body>

</html>