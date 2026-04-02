<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'] ?? 'Usuario';
$anioActual = date('Y');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | SDO</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">

                <div class="row justify-content-center mb-4">
                    <?php
                    $tarjetas = [
                        ['href' => 'ordenes-listado-general.php', 'bg' => 'bg-gradient-primary', 'icon' => 'fas fa-file-alt', 'label' => 'Hojas Técnicas '.$anioActual, 'value' => countHTDeptoAnio('admin', $anioActual)],
                        ['href' => 'soliCompra_sdo_administracion.php', 'bg' => 'bg-gradient-warning', 'icon' => 'fas fa-shopping-cart', 'label' => 'Solic. Compra SDO '.$anioActual, 'value' => countSCSDODeptoAnio('admin', $anioActual)],
                        ['href' => 'equipamiento_hbqp.php', 'bg' => 'bg-gradient-purple', 'icon' => 'fas fa-cogs', 'label' => 'Equipos', 'value' => countEquipamientoDepto('admin')],
                        ['href' => 'avisos_relevantes_administracion.php', 'bg' => 'bg-gradient-success', 'icon' => 'fas fa-phone', 'label' => 'Avisos Relevantes', 'value' => countTotalAvisosRelevantes()],
                        ['href' => 'reportes-listado.php', 'bg' => 'bg-gradient-danger', 'icon' => 'fas fa-book', 'label' => 'Reportes Turno', 'value' => getCantTotalReportesAnio($anioActual)]
                    ];

                    foreach ($tarjetas as $card) {
                        echo '
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <a href="' . $card['href'] . '" class="text-white text-decoration-none">
                                <div class="card ' . $card['bg'] . ' shadow-sm py-2 animate__animated animate__fadeIn">
                                    <div class="card-body p-2 text-center">
                                        <i class="' . $card['icon'] . ' fa-lg mb-2 d-block"></i>
                                        <div class="h5 font-weight-bold mb-0">' . $card['value'] . '</div>
                                        <small class="text-uppercase" style="font-size:10px">' . $card['label'] . '</small>
                                    </div>
                                </div>
                            </a>
                        </div>';
                    }
                    ?>
                </div>

                <div class="card card-outline card-warning">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills justify-content-center" id="tabMenu">
                            <li class="nav-item col-md-3">
                                <a class="nav-link active btn btn-outline-dark btn-block mb-1" data-toggle="pill" href="#htTab" data-url="../sdo-includes/ht_dashboard_general.php">Hojas Técnicas</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link btn btn-outline-dark btn-block mb-1" data-toggle="pill" href="#scTab" data-url="../sdo-includes/sc_dashboard.php">Solicitudes Compra</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link btn btn-outline-dark btn-block mb-1" data-toggle="pill" href="#otTab" data-url="../sdo-includes/ot_dashboard.php">Órdenes Trabajo</a>
                            </li>
                            <li class="nav-item col-md-3">
                                <a class="nav-link btn btn-outline-dark btn-block mb-1" data-toggle="pill" href="#pulsadorTab" data-url="../sdo-includes/pulsador_dashboard.php">Pulsador Cama</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="tabContent">
                            <div class="tab-pane fade show active" id="htTab">
                                <div class="ajax-container text-center">
                                    <i class="fas fa-sync fa-spin fa-2x"></i><br>Cargando Dashboard...
                                </div>
                            </div>
                            <div class="tab-pane fade" id="scTab"><div class="ajax-container"></div></div>
                            <div class="tab-pane fade" id="otTab"><div class="ajax-container"></div></div>
                            <div class="tab-pane fade" id="pulsadorTab"><div class="ajax-container"></div></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022-2026. Derechos Reservados.</strong>
    </footer>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function() {

        // Función para cargar contenido AJAX
        function cargarModulo(targetTab, url) {
            const container = $(targetTab).find('.ajax-container');
            container.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-warning"></i><br><br>Procesando información...</div>');

            $.get(url, function(data) {
                container.html(data);
            }).fail(function() {
                container.html('<div class="alert alert-danger text-center">No se pudo cargar el módulo. Verifique el archivo.</div>');
            });
        }

        // Carga inicial por defecto (HT)
        cargarModulo('#htTab', '../sdo-includes/ht_dashboard_general.php');

        // Manejador de cambio de pestaña
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            const targetId = $(e.target).attr("href");
            const url = $(e.target).data("url");

            // Estética de botones
            $('a[data-toggle="pill"]').removeClass('btn-primary text-white').addClass('btn-outline-dark');
            $(e.target).removeClass('btn-outline-dark').addClass('btn-primary text-white active');

            // Cargar solo si el contenedor está vacío o es necesario refrescar
            cargarModulo(targetId, url);
        });
    });
</script>
</body>
</html>