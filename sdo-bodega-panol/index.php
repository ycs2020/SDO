<?php


include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/dark-mode.css">


    <link rel="stylesheet" href="css/estilos.css">
</head>

<body class="hold-transition sidebar-mini " id="body">
<div class="wrapper">
    <!-- Navbar -->
    <?php

    $inicio = true;
    include '../sdo-templates/bodega-panol-sidebar.php';
    ?>


    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="col-sm-6">
                    <h3 style="font-family: 'Roboto', sans-serif; font-weight: 900;  color: #03cea4 ;"><?php echo $mensajeBienvenida . ", " . $nomUsuario . " " . $icono ?> </h3>
                </div>
                <div class="row mb-2 animate__animated animate__fadeInDown">
                    <div class="col-sm-6">


                    </div>
                    <div class="col-sm-6">
                        <!--
                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")

                                    document.body.classList.toggle("dark-mode");

                                }
                            </script> -->


                        <br>
                        <br>

                        <div aria-live="polite" aria-atomic="true">
                            <!-- Position it -->
                            <div style="position: absolute; top: 0; right: 0;z-index:1;">

                                <!-- Then put toasts within -->
                                <div class="toast bg-success" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                                    <div class="toast-header">

                                        <i class="fas fa-check-circle rounded mr-2"></i>
                                        <strong class="mr-auto"> Solicitudes Pendientes</strong>
                                        <small class="text">Justo Ahora</small>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="toast-body">
                                        Usted tiene algunas solicitudes pendientes,
                                        <a href="arriendo-retiro.php">quiero revisarlas!</a>
                                    </div>
                                </div>

                                <div class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                                    <div class="toast-header">
                                        <i class="fas fa-info-circle rounded mr-2"></i>
                                        <strong class="mr-auto">Herramientas Atrasadas</strong>
                                        <small class="text-muted">Hace 1 minuto</small>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="toast-body">
                                        Solicitudes con herramientas aún no entregadas en la fecha,
                                        <center>
                                            <button class="btn btn-primary" title="Ir revisar esas solicitudes">
                                                <a href="arriendo-retiro.php"><i class="fas fa-eye"></i></a>
                                            </button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div><!-- /.container-fluid -->
        </section>

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

                            <!-- /.card-header -->
                            <div class="card-body">

                                <h5>
                                    <b> Reportes Control Centralizado</b>
                                </h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="reportes-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Reportes Control Centralizado</a>
                                    </div>
                                </div>

                                <hr>
                                <h5>
                                    <b> Solicitud de Compra</b>
                                </h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-12">
                                        <!-- small box -->
                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-money-bill-wave"></i><br>Administrar Solicitudes de Compra</a>
                                    </div>
                                </div>
                                <hr>
                                <h5>
                                    <b>Solicitudes de Compra - Puesta en Marcha</b>
                                </h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-12">
                                        <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-money-bill-alt"></i><br>Administrar Solicitudes de Compra - PM</a>
                                    </div>
                                </div>
                                <?php
                                if ($nomUsuario == 'Bodega Pañol') {
                                    ?>
                                    <hr>
                                    <h5>
                                        <b> Ordenes de Trabajo</b>
                                    </h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Administrar Ordenes de Trabajo OT</a>
                                        </div>
                                    </div>
                                    <?php
                                }

                                ?>

                                <?php
                                $ipCliente = $_SERVER['REMOTE_ADDR'];

                                if ($ipCliente == '10.69.250.1512') {
                                    ?>
                                    <h5>
                                        <b></b>
                                    </h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <div class="small-box bg-primary animate__animated animate__bounceInDown">

                                                <div class="inner ">

                                                    <h4>Entrada y Salida</h4>
                                                    <h4>Productos</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="fas fa-store"></i>

                                                </div>


                                                <a href="panel-arriendo-retiro.php" class="small-box-footer">Administrar
                                                    <i class="fas fa-arrow-circle-right"></i></a>


                                            </div>

                                        </div>


                                    </div>

                                    <h5 class="animate__animated animate__heartBeat">
                                        <b>Herramientas y Materiales</b>
                                    </h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <div class="small-box bg-primary animate__animated animate__bounceInLeft">

                                                <div class="inner">

                                                    <h4>Ingresar Nuevas</h4>
                                                    <h4>Herramientas</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-tools"></i>

                                                </div>


                                                <a href="bodega-administrar.php" class="small-box-footer">Ingresar
                                                    <i class="fas fa-arrow-circle-right"></i></a>


                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <div class="small-box bg-info animate__animated animate__bounceInRight">

                                                <div class="inner">

                                                    <h4>Ingresar Nuevos</h4>
                                                    <h4>Materiales</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-box-open"></i>

                                                </div>


                                                <a href="materiales-administrar.php" class="small-box-footer">Ingresar
                                                    <i class="fas fa-arrow-circle-right"></i></a>


                                            </div>

                                        </div>
                                    </div>

                                    <hr>
                                    <br>
                                    <h5>
                                        <b> Arriendo Herramientas y/o Retiro de Materiales por ticket de trabajo</b>
                                    </h5>
                                    <br>

                                    <div class="row">


                                        <div class="col-lg-3 col-12">

                                            <div class="small-box bg-secondary">

                                                <div class="inner">

                                                    <h4>Administrar </h4>
                                                    <h4>Arriendo y/o Retiro
                                                    </h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-door-open"></i>

                                                </div>


                                                <a href="arriendo-retiro-administrar.php" class="small-box-footer">Arrendar
                                                    <i class="fas fa-arrow-circle-right"></i></a>


                                            </div>

                                        </div>
                                    </div>


                                    <hr>
                                    <br>
                                    <h5>
                                        <b>Reservas Herramienta y/o Materiales por Solicitud Compra</b>
                                    </h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">

                                            <div class="small-box bg-warning">

                                                <div class="inner">

                                                    <h4>Administrar</h4>
                                                    <h4>Reserva Herramientas y/o Materiales</h4>
                                                    <br>
                                                </div>


                                                <div class="icon">
                                                    <i class="nav-icon fas fa-door-closed"></i>

                                                </div>


                                                <a href="reserva-administrar.php" class="small-box-footer">Administrar
                                                    <i class="fas fa-arrow-circle-right"></i></a>


                                            </div>

                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>


                            </div>


                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.container-fluid -->


    <!-- /.content-wrapper -->
    <footer id="copy" class="main-footer animate__animated animate__fadeInRightBig">
        <strong>Copyright &copy; 2022. Derechos Reservados.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
<script>
    $(function () {
        $('[data-toggle="popover"]').popover({
            trigger: 'hover'
        })
    })
</script>
<script>
    $(document).ready(function () {

        $("#btnMostrar").click(function () {
            $("#btnMostrar").addClass('animate__animated animate__tada');
            $('.toast').toast('show');
            setTimeout(function () {
                $("#btnMostrar").removeClass('animate__animated animate__tada');

            }, 2000);
        });

        $("#btnOcultar").click(function () {
            $("#btnOcultar").addClass('animate__animated animate__fadeOut');
            setTimeout(function () {
                $("#btnOcultar").removeClass('animate__animated animate__fadeOut');
                $("#btnOcultar").addClass('animate__animated animate__fadeInDown');

            }, 2300);
            Swal.fire({
                title: 'Por el momento no tiene notificaciones que visualizar',
                timer: 2000,
                showConfirmButton: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })
        })
    })
</script>
<script src="js/dark-mode.js"></script>


</body>

</html>
