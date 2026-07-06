<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';
include_once '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sanitizar variables de sesión esenciales contra XSS reflejado
$nomUsuario = filter_var($_SESSION['nomUsuario'] ?? 'Invitado', FILTER_SANITIZE_SPECIAL_CHARS);
$idDepartamento = filter_var($_SESSION['idDepartamento'] ?? 0, FILTER_VALIDATE_INT);

$horaActual = date('H');
$anio = filter_var(date('Y'), FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú del Sistema</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">

    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">

    <?php include '../sdo-templates/admin-sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <?php
                $contarfaltavisado = contarfaltavisado($nomUsuario);
                $contarNotificacionesPersonal = 0;

                if ($idDepartamento === 1 || $idDepartamento === 13) {
                    $contarNotificacionesPersonal = contarNotificacionesPersonal();
                }

                // Llamadas seguras parametrizadas
                $totalHojasTec = obtenerTotalSeguro($conexion, "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = ?", $anio);
                $totalCompras  = obtenerTotalSeguro($conexion, "SELECT COUNT(*) AS TOTAL FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = ?", $anio);
                $totalEquipos  = obtenerTotalSeguro($conexion, "SELECT COUNT(*) AS TOTAL FROM equipamiento_hbqp", null);
                $totalReportes = obtenerTotalSeguro($conexion, "SELECT COUNT(*) AS TOTAL FROM reportes_generales WHERE YEAR(fechaReporte) = ?", $anio);
                ?>

                <div class="row mb-4">
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="info-box shadow-sm bg-info">
                            <span class="info-box-icon"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Hojas Técnicas <?= htmlspecialchars($anio, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="info-box-number"><?= number_format($totalHojasTec, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="info-box shadow-sm bg-warning">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Solicitudes de Compra <?= htmlspecialchars($anio, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="info-box-number"><?= number_format($totalCompras, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="info-box shadow-sm bg-purple" style="background-color: #6f42c1 !important; color: white;">
                            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Equipamiento Total</span>
                                <span class="info-box-number"><?= number_format($totalEquipos, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="info-box shadow-sm bg-success">
                            <span class="info-box-icon"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reportes Turno <?= htmlspecialchars($anio, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="info-box-number"><?= number_format($totalReportes, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include_once '../sdo-includes/admin_menu_opciones.php'; ?>

            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">SDO Plataforma</div>
        <strong>Copyright &copy; 2022-<?= htmlspecialchars(date('Y'), ENT_QUOTES, 'UTF-8') ?>. Todos los derechos reservados.
    </footer>
</div>

<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="js/dark-mode.js"></script>
<script src="js/fullScreen.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };

    $(document).ready(function() {
        <?php if ($contarNotificacionesPersonal > 0): ?>
        Swal.fire({
            icon: 'info',
            title: 'Interfaz de Administración de Personal',
            text: 'Tienes <?= (int)$contarNotificacionesPersonal ?> notificaciones pendientes',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#17a2b8'
        });
        toastr.info('Tienes <?= (int)$contarNotificacionesPersonal ?> notificaciones pendientes.');
        <?php endif; ?>

        <?php if ($contarfaltavisado > 0): ?>
        toastr.warning('Existen <?= (int)$contarfaltavisado ?> Órdenes de Trabajo pendientes de visar.');
        <?php endif; ?>

        <?php if (isset($_GET['loginExito'])): ?>
        toastr.success('Ingreso Exitoso a la Plataforma.');
        <?php endif; ?>

        <?php if (isset($_GET['exitoCambioPassword'])): ?>
        toastr.success('Contraseña modificada con éxito.');
        <?php endif; ?>
    });
</script>
</body>
</html>