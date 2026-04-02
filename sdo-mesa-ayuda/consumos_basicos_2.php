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

/**
 * Optimización de Consultas de Sumas (Consumos)
 * Podemos agrupar las sumas en una sola consulta por tabla para ahorrar tiempo
 */

// Electricidad
$resElec = mysqli_query($conexion, "SELECT SUM(consumoEnergia) as total, SUM(montoTotalMes) as monto FROM electricidad_administracion WHERE tipohospital = 'HBQP'");
$dataElec = mysqli_fetch_assoc($resElec);
$totalEnergia = $dataElec['total'];
$totalMontoElec = $dataElec['monto'];

// Agua
$resAgua = mysqli_query($conexion, "SELECT SUM(consumoAgua) as total, SUM(montoTotalMes) as monto FROM agua_administracion WHERE tipoHospital = 'HBQP'");
$dataAgua = mysqli_fetch_assoc($resAgua);
$totalAgua = $dataAgua['total'];
$totalMontoAgua = $dataAgua['monto'];

// Gases Clínicos
$resGases = mysqli_query($conexion, "SELECT SUM(valorTotalNeto) as total, SUM(valorTotalIva) as monto FROM gases_clinicos");
$dataGases = mysqli_fetch_assoc($resGases);
$totalGases = $dataGases['total'];
$totalMontoGases = $dataGases['monto'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consumos Basicos</title>
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
    include '../sdo-templates/mesaayuda-sidebar.php';
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
                                <h5 class="mb-0"><i class="fas fa-industry mr-2"></i> Panel de Consumos Básicos</h5>
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
                                    <i class="fas fa-bolt text-warning mr-1"></i> Electricidad
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#contenedor" onclick="cargarMenuHospitales('agua')">
                                    <i class="fas fa-tint text-info mr-1"></i> Agua Potable
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#contenedor" onclick="cargarMenuHospitales('gases')">
                                    <i class="fas fa-cloud text-success mr-1"></i> Gases Clínicos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#contenedor" onclick="cargarMenuHospitales('combustible')">
                                    <i class="fas fa-gas-pump text-danger mr-1"></i> Combustible
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h3 id="titulo-seccion" class="font-weight-bold text-secondary">Electricidad</h3>
                            <hr style="width: 50px; border-top: 3px solid #007bff;">
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-primary px-4" id="btnMenuHosp" onclick="cambiarVista('menu')">
                                    <i class="fas fa-th-large mr-2"></i> Menú Hospitales
                                </button>
                                <button type="button" class="btn btn-light border px-4" id="btnDashboard" onclick="cambiarVista('dash')">
                                    <i class="fas fa-chart-line mr-2"></i> Dashboard de Consumo
                                </button>
                            </div>
                        </div>

                        <div id="contenedor-principal" class="mt-3">
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
<script>
    // Variable global para mantener el rastro de qué consumo estamos viendo
    let tipoActual = 'electricidad';

    $(document).ready(function() {
        cargarMenuHospitales('electricidad');
    });

    function cargarMenuHospitales(tipo) {
        tipoActual = tipo; // Actualizamos el tipo global

        // 1. Actualizar Título
        const titulos = {
            electricidad: 'Electricidad',
            agua: 'Agua Potable',
            gases: 'Gases Clínicos',
            combustible: 'Combustible'
        };
        $("#titulo-seccion").text(titulos[tipo]);

        // 2. Gestionar botones (Resetear a estado original)
        $("#btnMenuHosp").removeClass("btn-light border").addClass("btn-primary");
        $("#btnDashboard").removeClass("btn-primary").addClass("btn-light border");

        // 3. Configuración de URLs para las CARDS
        const urls = {
            electricidad: { sm: 'electricidad_administracion_hsmq.php', hbqp: 'electricidad_administracion_hbqp.php' },
            agua:         { sm: 'agua_administracion_hsmq.php', hbqp: 'agua_administracion_hbqp.php' },
            gases:        { hbqp: 'gases_clinicos.php' },
            combustible:  { hbqp: 'combustible_visor.php' }
        };

        const config = urls[tipo];
        let html = '<div class="row justify-content-center">'; // Iniciamos el row

        if (config.sm) {
            html += generarCard("Hospital San Martín de Quillota", "Monitoreo de consumo", "../dist/img/HSMQ.jpg", config.sm);
        }
        html += generarCard("Hospital Biprovincial Quillota Petorca", "Monitoreo de consumo", "../dist/img/HBQP.jpg", config.hbqp);

        html += '</div>'; // Cerramos el row

        $("#contenedor-principal").hide().html(html).fadeIn();
    }

    function cambiarVista(vista) {
        const contenedor = $("#contenedor-principal");

        if (vista === 'dash') {
            // Estética de botones
            $("#btnDashboard").removeClass("btn-light border").addClass("btn-primary");
            $("#btnMenuHosp").removeClass("btn-primary").addClass("btn-light border");

            // Carga AJAX del dashboard correspondiente
            // Ejemplo: dash_electricidad.php, dash_agua.php, etc.
            const urlDash = `../sdo-includes/consumos/dash_${tipoActual}.php`;

            contenedor.fadeOut(200, function() {
                contenedor.load(urlDash, function(response, status, xhr) {
                    if (status == "error") {
                        contenedor.html("<div class='alert alert-danger text-center'>No se encontró el dashboard para " + tipoActual + "</div>");
                    }
                    contenedor.fadeIn();
                });
            });

        } else {
            // Volver al menú de hospitales (re-usamos la función que ya tenemos)
            cargarMenuHospitales(tipoActual);
        }
    }

    function generarCard(titulo, subtitulo, imagen, url) {
        return `
    <div class="col-md-5 col-sm-12 mb-4">
        <div class="card h-100 shadow-sm border-0 elevation-1">
            <div class="card-body text-center p-4">
                <img src="${imagen}" class="img-fluid rounded mb-3" style="height: 180px; width: 100%; object-fit: cover;">
                <h5 class="font-weight-bold mb-1">${titulo}</h5>
                <p class="text-muted small">${subtitulo}</p>
                <a href="${url}" class="btn btn-info btn-block mt-3 shadow-sm">
                    <i class="fas fa-external-link-alt mr-1"></i> Visualizar
                </a>
            </div>
        </div>
    </div>`;
    }
</script>
<script>
    // Este evento detecta cuando cambias de pestaña (Año) y fuerza el redibujado
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        window.dispatchEvent(new Event('resize'));
    });
</script>
</body>

</html>