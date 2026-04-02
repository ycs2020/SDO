<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');

if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '12' && $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
}

date_default_timezone_set('America/Santiago');
$ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
$ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
$ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
//
// Obtén la IP del cliente
$ipCliente = $_SERVER['REMOTE_ADDR'];
//
/// // Verifica si la IP del cliente es la permitida


if ($ipCliente === $ipPermitida) {
    $valorDesarrollo = 'Mostrar';
} else {
    $valorDesarrollo = 'Ocultar';
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " . $nomUsuario . " " . $icono ?> </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab"> <b>Hojas Técnicas</b> </a></li>
                                </ul>
                                <?php
                                if (isset($_GET['loginExito'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Ingreso Exitoso a la Plataforma.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        }).then((result) => {
                                            /* Read more about isConfirmed, isDenied below */
                                            <?php
                                            if ($obtenerSolicitudesSegunNombre > 0) {
                                            ?>
                                            Swal.fire({
                                                title: 'Existen nuevas Solicitudes Creadas.',
                                                confirmButtonText: 'Cerrar',
                                                icon: 'info',
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                <?php
                                                if (isset($_GET['exitoCambioPassword'])) {
                                                ?>

                                                Swal.fire(
                                                    'Contrase&ntilde;a Modificada con Exito.',
                                                    '',
                                                    'success'
                                                )

                                                <?php
                                                }

                                                ?>
                                            })

                                            <?php
                                            } else {

                                            if (isset($_GET['exitoCambioPassword'])) {
                                            ?>

                                            Swal.fire(
                                                'Contrase&ntilde;a Modificada con Exito.',
                                                '',
                                                'success'
                                            )
                                            <?php
                                            }
                                            }

                                            ?>
                                        })
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['exitoCambioPassword'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-check"></i>&nbsp;Contrase&ntilde;a Modificada con Exito. Ingreso Exitoso a la Plataforma</h5></div>';
                                }
                                ?>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <?php

                                        $tipoUsuarioVer = 'todos';
                                        include_once '../sdo-includes/hojastecnicas_dashboard.php';
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <!--
                            <h5><b>Combustible </b></h5>
                            <br>
                            <div class="row">
                                <div class="col-lg-3 col-12">

                                    <a href="combustible_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Informe de Combustible</a>

                                </div>
                            </div>
                            -->


                            <br>


                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
<!-- Page specific script -->
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
<script src="js/dark-mode.js"></script>
</body>

</html>