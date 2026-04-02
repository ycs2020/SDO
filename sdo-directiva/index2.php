<?php
include '../sdo-funciones/conexion.php';
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
                            <h4>Men&uacute; del Sistema</h4>
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
                                <div class="card-header">



                                    <h5><b>Bienvenido(a): </b><?php echo $nomUsuario; ?></h5>
                                    <h5><b>Cargo: </b><?php echo $cargoUsuario; ?></h5>

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

                                    <h5><b>Combustible </b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <a href="combustible_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i><br>Informe de Combustible</a>

                                        </div>
                                    </div>



                                    <hr>


                                    <h5><b>Informes de los Departamentos</b></h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-primary">

                                                <div class="inner">
                                                    <h4>Visualizar</h4>
                                                    <h4>Reportes de Eventos</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-book"></i>

                                                </div>


                                                <a href="reportes-visualizar.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-secondary">

                                                <div class="inner">

                                                    <h4>Visualizar</h4>
                                                    <h4>Recepciones de Trabajo</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-book"></i>

                                                </div>


                                                <a href="ordenes-visualizar.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                    </div>

                                    <hr>

                                    <h5><b>Informaci&oacute;n de Funcionarios de SDO</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-primary">

                                                <div class="inner">

                                                    <h4>Encargados de</h4>
                                                    <h4>Departamento</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="jefes-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-secondary">

                                                <div class="inner">

                                                    <h4>Profesionales de</h4>
                                                    <h4>Departamento</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="profesionales-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">

                                                <div class="inner">

                                                    <h4>T&eacute;cnicos de</h4>
                                                    <h4>Departamento</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="tecnicos-listado.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                    </div>

                                    <hr>

                                    <h5><b>Sistema de Tickets</b></h5>
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
                                                    <i class="nav-icon fas fa-list"></i>

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
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="solicitudes_finalizadas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-warning">

                                                <div class="inner">

                                                    <h4>Tickets</h4>
                                                    <h4>Cancelados</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="solicitudes_canceladas.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                    </div>

                                    <hr>

                                    <h5><b>Solicitudes de Compra</b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-primary">

                                                <div class="inner">

                                                    <h4>Administrar</h4>
                                                    <h4>SC - Puesta en Marcha</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="soliCompra_pm_administracion.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <!-- small box -->
                                            <div class="small-box bg-secondary">

                                                <div class="inner">

                                                    <h4>Administrar</h4>
                                                    <h4>SC - SDO</h4>
                                                    <br>
                                                </div>

                                                <div class="icon">
                                                    <i class="nav-icon fas fa-list"></i>

                                                </div>


                                                <a href="soliCompra_sdo_administracion.php" class="small-box-footer">Ingresar <i class="fas fa-arrow-circle-right"></i></a>



                                            </div>

                                        </div>
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
    <script src="js/dark-mode.js"></script>
</body>

</html>