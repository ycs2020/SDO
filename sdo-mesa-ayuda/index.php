<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];


//$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');


if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '12' &&  $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema </title>

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

    <script src="js/dark-mode.js"></script>

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
        include '../sdo-templates/mesaayuda-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 animate__animated animate__fadeInDown">
                        <div class="col-sm-6">
                            <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " .  $nomUsuario . " " . $icono  ?> </h3>
                        </div>
                        <div class="col-sm-6">
                            <p id="tiempo-actual"></p>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <?php

            $obtenerSolicitudesCreadas = obtenerSolicitudesCreadas();

            if ($obtenerSolicitudesCreadas > 0) {
            ?>
                <script>
                    Swal.fire(
                        'Existen nuevas Solicitudes Creadas.',
                        '',
                        'info'
                    )
                </script>
            <?php
            }

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
                        if ($obtenerSolicitudesCreadas > 0) {
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


            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <!--

                                    <?php

                                    ?>
                                    -->


                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php
                                        if($idUsuario == 4 || $idUsuario == 2 || $idUsuario == 5){
                                            ?>
                                            <h5><b>Recepcion Conforme</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <a href="recepcion_conforme.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Recepcion Conforme</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php
                                        }
                                        if($idUsuario == 4){
                                            ?>
                                            <h5><b>Convenios</b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <a href="convenios_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Convenios</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <?php
                                        }
                                    ?>
                                    <?php
                                    if($idUsuario == 5){
                                    }else{
                                        ?>
                                        <h5><b>Licitaciones</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <a href="licitaciones_admin.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Listado de Licitaciones</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php
                                    }
                                    ?>

                                    <?php

                                    if($idUsuario == 2){
                                        ?>
                                        <h5><b>Memorandum</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <a href="listado_memorandum.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Listado de Memorandum</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php
                                    }
                                    if ($idUsuario == 1) {
                                    ?>
                                        <h5><b>Ticket SIGH</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <a href="tickets_sigh.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Listado de Tickets Sigh</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Equipamiento HBQP</b></h5>
                                        <br>

                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-list"></i><br> Listado de Equipos</a>
                                            </div>
                                        </div>
                                        <hr>




                                    <?php
                                    }

                                    ?>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_4">
                                            <h5><b>Sistema de Tickets</b></h5>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">

                                                    <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-ticket-alt"></i><br> Ticket Recibidos</a>
                                                </div>
                                                <div class="col-lg-3 col-12">

                                                    <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-check"></i><br> Ticket Finalizado</a>
                                                </div>
                                                <div class="col-lg-3 col-12">
                                                    <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-lg"><i class="fas fa-times"></i><br> Ticket Cancelado</a>
                                                </div>

                                                <?php
                                                if($nomUsuario == 'Mesa De Ayuda S D O'){
                                                    ?>
                                                    <div class="col-lg-2 col-12">
                                                        <a href="usuarios_tickets.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="fas fa-list"></i><br> Listado de Usuarios</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <hr>
                                            <?php
                                            if($idUsuario == 5){

                                            }else{
                                                ?>
                                                <h5><b>Sistema de Solicitudes de Compra</b></h5>
                                                <div class="row">
                                                    <div class="col-lg-3 col-12">
                                                        <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-book"></i><br> Visualizar SC- Puesta en Marcha</a>

                                                    </div>
                                                    <div class="col-lg-3 col-12">
                                                        <!-- small box -->
                                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-list"></i><br> Visualizar SC - SDO</a>
                                                    </div>

                                                    <div class="col-lg-3 col-12">
                                                        <!-- small box -->
                                                        <a href="soliCompra_sdo_resumen.php" class="btn btn-block bg-gradient-info btn-lg"><i class="fas fa-book"></i><br> Visualizar SC - SDO - Resumen</a>
                                                    </div>

                                                </div><!--ROW CLASS 2-->
                                                <hr>

                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if($idUsuario == 5){

                                            }else{
                                                ?>
                                                <h5><b>Solicitudes Telefonicas</b></h5>
                                                <div class="row">
                                                    <div class="col-lg-3 col-12">
                                                        <!-- small box -->
                                                        <a href="solicitudes_telefonicas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-phone-square"></i><br>Visualizar Solicitudes Telefonicas</a>

                                                    </div>
                                                </div>
                                                <hr>
                                                <?php
                                            }
                                            ?>

                                            <!--
                                            <hr>
                                            <h5><b>Combustible </b></h5>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">

                                                    <a href="combustible_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump    "></i><br>Combustible Administracion</a>
                                                </div>
                                            </div>
                                            -->

                                            <h5><b>Consumos Basicos</b></h5>
                                            <br>
                                            <div class="row">

                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                                </div>
                                            </div>

                                            <hr>

                                        </div> <!-- Tab Pane ACTIVE-->
                                    </div> <!--Tab Content -->

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
        $(function() {
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
</body>

</html>