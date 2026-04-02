<?php
//SE CAMBIA A INCLUDE_ONCE PARA EVITAR PROBLEMAS DE INCLUSIONES MÚLTIPLES EN CASO DE QUE ESTE ARCHIVO SEA INCLUIDO VARIAS VECES EN EL FUTURO

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

$horaActual = date('H');
$anio = date('Y');


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }

        .cantidad1 {
            background-color: blue;
            color: white;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <script>
                    toastr.options = {
                        "positionClass": "toast-top-right",
                        "timeOut": "5000",
                        "closeButton": true,
                        "progressBar": true
                    };
                </script>

                <?php
                $contarfaltavisado = contarfaltavisado($nomUsuario);

                if ($contarfaltavisado > 0) {
                    echo "<script>toastr.info('Existen Ordenes de Trabajo sin visar, en total: $contarfaltavisado');</script>";
                }
                if (isset($_GET['loginExito'])) {
                    echo "<script>toastr.success('Ingreso Exitoso a la Plataforma.');</script>";
                }

                if (isset($_GET['exitoCambioPassword'])) {
                    echo "<script>toastr.success('Contraseña Modificada con Exito.');</script>";
                }

                // Función helper rápida
                function obtenerTotal($conexion, $sql)
                {
                    $res = mysqli_query($conexion, $sql);
                    $row = $res->fetch_assoc();
                    return (int)$row['TOTAL'];
                }

                // Totales
                $totalHojasTec = obtenerTotal($conexion, "SELECT COUNT(*) AS TOTAL FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = $anio");
                $totalCompras = obtenerTotal($conexion, "SELECT COUNT(*) AS TOTAL FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = $anio");
                $totalEquipos = obtenerTotal($conexion, "SELECT COUNT(*) AS TOTAL FROM equipamiento_hbqp");
                $totalReportes = obtenerTotal($conexion, "SELECT COUNT(*) AS TOTAL FROM reportes_generales WHERE YEAR(fechaReporte) = $anio");
                ?>

                <div class="row mb-2">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Hojas Técnicas Año <?= $anio ?></span>
                                <span class="info-box-number"><?= $totalHojasTec ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Solicitudes de Compra Año <?= $anio ?></span>
                                <span class="info-box-number"><?= $totalCompras ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <div class="info-box bg-purple">
                            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Equipamiento</span>
                                <span class="info-box-number"><?= $totalEquipos ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reportes Turno Año <?= $anio ?></span>
                                <span class="info-box-number"><?= $totalReportes ?></span>
                            </div>
                        </div>
                    </div>

                </div>
                <?php

                $userUsuarioMinusAdmin = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');

                if (in_array($userUsuarioMinusAdmin, ['adminsdo', 'acantillano'])) {
                    include_once '../sdo-includes/admin_menu_opciones.php';
                }
//                else if ($userUsuarioMinusAdmin == 'acantillano') {
//                    include_once 'sdo-includes/index-yc.php';
//
//                }
                else {

                    include_once 'sdo-includes/index-admin.php';
                }

                ?>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>
<script scr="js/fullscreen.js"></script>

<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Page specific script -->

<?php
if (isset($_GET['loginExito'])) {
    echo "<script>toastr.success('Ingreso Exitoso a la Plataforma.');</script>";
}
?>

<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function () {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
</script>
<script>
    const Anuladas = document.getElementById('Anuladas').textContent;
    const Rechazadas = document.getElementById('Rechazadas').textContent;
    const Aprobadas = document.getElementById('Aprobadas').textContent;
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Aprobadas', 'Rechazadas', 'Anuladas'],
            datasets: [{
                label: 'Cantidad',
                data: [Aprobadas, Rechazadas, Anuladas],
                borderWidth: 1,
                backgroundColor: ['green', 'red', 'orange'],
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script src="js/fullScreen.js"></script>

</body>

</html>