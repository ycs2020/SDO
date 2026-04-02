<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


    <script type="text/javascript">
        var keep_alive = false;
        $(document).bind("click keydown keyup mousemove", function() {
            keep_alive = true;
        });
        setInterval(function() {
            if (keep_alive) {
                pingServer();
                keep_alive = false;
            }
        }, 1200000);

        function pingServer() {
            $.ajax('/keepAlive');
        }
    </script>
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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Men&uacute; del Sistema</h4>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <?php
            $obtenerSolicitudesSegunNombre = obtenerSolicitudesSegunNombre($nomUsuario);

            if ($obtenerSolicitudesSegunNombre > 0) {
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
            if ($userUsuario == 'lperez') {
            ?>
                <script>
                    Swal.fire(
                        '<img src="../dist/img/chill.jpg" alt="NICE">'
                    )
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


                                    <br>
                                    <br>

                                    <table>
                                        <tr>
                                            <td>
                                                <h5><b>Bienvenido: </b><?php echo $nomUsuario . ' ( ' . $cargoPersonal . ' )'; ?></h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5><b>Departamento: </b><?php echo getNomDepartamento($idDepartamento); ?></h5>
                                            </td>
                                        </tr>
                                        <?php
                                        if ($idSubDepartamento > 0) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <h5><b>SubDepartamento: </b><?php echo getNomSubDepartamento($idSubDepartamento); ?></h5>
                                                </td>
                                            </tr>
                                        <?php
                                        } else {
                                        }
                                        ?>
                                    </table>


                                </div>
                                <!-- /.card-header -->
                                <div style="font-size: 85%;" class="card-body">

                                    <?php
                                    if ($_SESSION['departamentoPersonal'] == "Instrumentación") {
                                    ?>

                                        <h5><b>Rutina Diaria</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Visualizar
                                                        </h4>
                                                        <h4>Bitacora</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-clock"></i>

                                                    </div>


                                                    <a href="bitacora-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Reportes Generales de Eventos Relevantes</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Crear
                                                        </h4>
                                                        <h4>Reportes Generales</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="reportes-formulario.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Historial de
                                                        </h4>
                                                        <h4>Reportes Generales</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="reportes-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Recepciones de trabajo</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Generar
                                                        </h4>
                                                        <h4>Recepción de trabajo</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-file"></i>

                                                    </div>


                                                    <a href="ordenes-formulario.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Historial de
                                                        </h4>
                                                        <h4>Recepciones de trabajos</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="ordenes-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Hojas Técnicas</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Generar
                                                        </h4>
                                                        <h4>Hoja Técnica</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-file"></i>

                                                    </div>


                                                    <a href="ordenes-formulario-general.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Historial de
                                                        </h4>
                                                        <h4>Hojas Técnicas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="ordenes-listado-general.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Observaciones de Sistema BMS</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Listado de
                                                        </h4>
                                                        <h4>Sistemas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="sistemas-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Listado de
                                                        </h4>
                                                        <h4>Equipos Forzados</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="forzados-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-info">

                                                    <div class="inner">

                                                        <h4>
                                                            Observaciones de
                                                        </h4>
                                                        <h4>Equipos</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-list"></i>

                                                    </div>


                                                    <a href="sistemas-observaciones.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        <h5><b>Levantamiento de Sistema BMS</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Sistema
                                                        </h4>
                                                        <h4>BMS</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-list"></i>

                                                    </div>


                                                    <a href="controladores-maestros-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Controladores
                                                        </h4>
                                                        <h4>BMS</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-list"></i>

                                                    </div>


                                                    <a href="controladores-esclavos-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-info">

                                                    <div class="inner">

                                                        <h4>
                                                            Detalle
                                                        </h4>
                                                        <h4>Controladores BMS</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-list"></i>

                                                    </div>


<!--                                                    <a href="detalle_controladores_bms.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>-->



                                                </div>

                                            </div>
                                        </div>

                                        <hr>
                                        <h5><b>Control de Acceso</b></h5>
                                        <br>
                                        <div class="row">

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Listado de
                                                        </h4>
                                                        <h4>Control de Acceso</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-users"></i>

                                                    </div>


                                                    <a href="personal-controlAcceso.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Listado de
                                                        </h4>
                                                        <h4>Acceso a Parking</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-parking"></i>

                                                    </div>


                                                    <a href="parking-acceso.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-info">

                                                    <div class="inner">

                                                        <h4>
                                                            Administrar
                                                        </h4>
                                                        <h4>Actas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="actas-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>

                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-warning">

                                                    <div class="inner">

                                                        <h4>
                                                            Listado de
                                                        </h4>
                                                        <h4>Llaves Entregadas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-key"></i>

                                                    </div>


                                                    <a href="llaves_entregadas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>

                                    <?php
                                    } else {
                                    ?>



                                        <?php
                                        if ($idDepartamento == 8) {
                                        ?>
                                            <h5><b>Viajes - Transporte </b></h5>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-3 col-12">
                                                    <!-- small box -->
                                                    <div class="small-box bg-secondary">

                                                        <div class="inner">

                                                            <h4>
                                                                Ingresar
                                                            </h4>
                                                            <h4>Viaje</h4>
                                                            <br>
                                                        </div>

                                                        <div class="icon">
                                                            <i class="fas fa-car"></i>

                                                        </div>


                                                        <a href="listado-viajes.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                    </div>

                                                </div>
                                            </div>
                                            <hr>

                                        <?php
                                        }
                                        ?>


                                        <h5><b>Recepciones de Trabajos</b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Generar
                                                        </h4>
                                                        <h4>Hojas Técnicas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-file"></i>

                                                    </div>


                                                    <a href="ordenes-formulario-general.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-secondary">

                                                    <div class="inner">

                                                        <h4>
                                                            Historial de
                                                        </h4>
                                                        <h4>de Hojas Técnicas</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                        <i class="nav-icon fas fa-book"></i>

                                                    </div>


                                                    <a href="ordenes-listado-general.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                                </div>

                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <hr>
                                    <h5><b>Solicitudes de Soporte</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-primary">

                                                <div class="inner">

                                                    <h4>Tickets</h4>
                                                    <h4>Recibidos</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-file"></i>

                                                </div>


                                                <a href="solicitudes_recibidas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-secondary">

                                                <div class="inner">

                                                    <h4>Tickets</h4>
                                                    <h4>Finalizados</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-file"></i>

                                                </div>


                                                <a href="solicitudes_finalizadas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-info">

                                                <div class="inner">

                                                    <h4>Tickets</h4>
                                                    <h4>Cancelados</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-file"></i>

                                                </div>


                                                <a href="solicitudes_canceladas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                            </div>

                                        </div>
                                    </div>

                                    <?php
                                    if ($departamentoPersonal == 'Equipos Industriales') {
                                    ?>
                                        <hr>
                                        <h5><b>Combustible </b></h5>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-12">
                                                <!-- small box -->
                                                <div class="small-box bg-primary">

                                                    <div class="inner">

                                                        <h4>
                                                            Registrar Medici&oacute;n
                                                        </h4>
                                                        <h4>Combustible</h4>
                                                        <br>
                                                    </div>

                                                    <div class="icon">
                                                    <i class="fas fa-gas-pump"></i>

                                                    </div>

                                                    <a href="combustible_mediciones.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>

                                                </div>

                                            </div>
                                        </div>
                                        <hr>

                                    <?php
                                    }
                                    ?>


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

    <script src="js/dark-mode.js"></script>
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

    <script src="../dist/sweetalert-master/src/sweetalert.js"></script>
    <link rel="stylesheet" type="text/css" href="../dist/sweetalert-master/src/sweetalert.css">
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