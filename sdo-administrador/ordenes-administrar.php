<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Recepciones de Trabajo</title>

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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Listado de Recepciones de Trabajo</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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

                                    <?php
                                    if (isset($_GET['loginExito'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Sesi&oacute;n Iniciada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['errorGenerar'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Visualizar Recepci&oacute;n de Trabajo. Contactar a Soporte.</h5></div>';
                                    }

                                    if (isset($_GET['errorFolio'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Generar Folio de Recepci&oacute;n de Trabajo. No se guardo Recepci&oacute;n de Trabajo.</h5></div>';
                                    }

                                    if (isset($_GET['recepcionSinFotoSinMaterial'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Recepci&oacute;n sin Fotos y sin Materiales Creada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['recepcionSinFotoConMaterial'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Recepci&oacute;n sin Fotos y con Materiales Creada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['recepcionConFotoSinMaterial'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Recepci&oacute;n con Fotos y sin Materiales Creada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['recepcionConFotoConMaterial'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Recepci&oacute;n Creada con &Eacute;xito. Se incluyeron Fotos y Materiales.</h5></div>';
                                    }

                                    if (isset($_GET['errorCargaFotoSinMaterial'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Recepci&oacute;n sin Materiales Creada. Ocurrio un Error al Subir Imagenes. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorCargaFotoConMaterial'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Recepci&oacute;n con Materiales Creada. Ocurrio un Error al Subir Imagenes. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['formatoIncorrectoFotoSinMaterial'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Recepci&oacute;n sin Materiales Creada. Formato de Imagenes No Permitido. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['formatoIncorrectoFotoConMaterial'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Recepci&oacute;n con Materiales Creada. Formato de Imagenes No Permitido. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['exitoFirmarRecepcion'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Recepci&oacute;n Firmada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['errorFirmarRecepcion'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Firmar Recepci&oacute;n.</h5></div>';
                                    }

                                    ?>
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <form method="post" action="ordenes-formulario.php">

                                        <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Recepcion de Trabajo</button>

                                    </form>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Folio<center>
                                                </th>
                                                <th>
                                                    <center>Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ordenes_trabajos ORDER BY fechaOrden DESC';

                                            $res = mysqli_query($conexion, $query);

                                            $numOrden = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numOrden; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['folioOrden']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = $row['nomSolicitante'];
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <CENTER><?php if ($row['fechaOrden'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaOrden']));
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                } ?></CENTER>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-OrdenTrabajo.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="ordenes-modificar.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/ordenes-eliminar.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-danger"><i class="fa fa-trash"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>

                                                </tr>
                                            <?php

                                                $numOrden = $numOrden + 1;
                                            }
                                            ?>



                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Folio<center>
                                                </th>
                                                <th>
                                                    <center>Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
            });
        });
    </script>
    <script src="js/dark-mode.js"></script>
</body>

</html>