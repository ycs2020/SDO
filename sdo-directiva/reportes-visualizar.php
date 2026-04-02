<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Reportes de Eventos Relevantes</title>

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
                            <h4>Listado de Reportes de Eventos Relevantes</h4>
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

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                    <?php
                                    if (isset($_GET['loginExito'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Sesi&oacute;n Iniciada con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['exitoModificarReporte'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Reporte Modificado con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['errorModificarFotoReporte'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Se ha Modificado el Reporte, pero hubo error al Modificar Imagenes. Contacte a Soporte.</h5></div>';
                                    }

                                    if (isset($_GET['errorModificarReporte'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar el Reporte. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['exitoEliminarReporte'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Reporte Eliminado con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['errorEliminarFotoReporte'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Se ha eliminado el Reporte, pero hubo error al eliminar Imagenes. Contacte a Soporte.</h5></div>';
                                    }

                                    if (isset($_GET['errorEliminarReporte'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Eliminar el Reporte. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorSeleccionarReporte'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Seleccionar el Reporte a Eliminar. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['reporteConFoto'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Reporte Con Imagenes Modificado con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['reporteSinFoto'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Reporte Sin Imagenes Modificado con &Eacute;xito.</h5></div>';
                                    }

                                    if (isset($_GET['sobrepasoImagen'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Imagenes exceden el M&aacute;ximo Permitido. Reintente.</h5></div>';
                                    }

                                    if (isset($_GET['errorCargaFoto'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Subir Imagenes. Contacte a Soporte.</h5></div>';
                                    }

                                    if (isset($_GET['formatoIncorrectoFoto'])) {
                                        echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Formato de Imagen Incorrecto. Reintente.</h5></div>';
                                    }

                                    ?>
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
                                                    <center>Encargado<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Reporte<center>
                                                </th>
                                                <th>
                                                    <center>Turno<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM reportes_generales ORDER BY fechaReporte DESC';

                                            $res = mysqli_query($conexion, $query);

                                            $numReporte = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numReporte; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['folioReporte']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $nuevoNom = $row['nomEncargadoTurno'];
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;">
                                                        <center>
                                                            <?php if ($row['fechaReporte'] == 0) {
                                                                echo "No registra fecha";
                                                            } else {
                                                                $inicioFecha = strtotime($row['fechaReporte']);
                                                                $inicioFecha = fechaEspañol($inicioFecha);
                                                                $inicioHora = date('H:i', strtotime($row['fechaReporte']));
                                                                echo $inicioFecha . '<br>' . $inicioHora . ' hrs.';
                                                            } ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <?php
                                                        if ($row['turnoReporte'] == "Dia" || $row['turnoReporte'] == "dia" || $row['turnoReporte'] == "DIA") {

                                                            $cadenaFecha = "Desde las 08:00 hrs. hasta las 20:00 hrs. del " . $inicioFecha;
                                                        } else if ($row['turnoReporte'] == "Noche" || $row['turnoReporte'] == "noche" || $row['turnoReporte'] == "NOCHE") {

                                                            $fechaAnterior = date("d-M-Y", strtotime($row['fechaReporte'] . "- 1 days"));
                                                            $fechaAnterior2 = strtotime($fechaAnterior);
                                                            $fechaAnterior2 = fechaEspañol($fechaAnterior2);

                                                            $cadenaFecha = "Desde las 20:00 hrs. del " . $fechaAnterior2 . " hasta las 08:00 hrs. del " . $inicioFecha;
                                                        } else {
                                                        }
                                                        echo $cadenaFecha;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idReporte = $row['idReporte'];
                                                            $idReporte = trim($idReporte);
                                                            if ($idReporte == 0 || $idReporte == "" || $idReporte == " " || $idReporte == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-TurnoTrabajado.php" method="post">

                                                                    <input type="hidden" name="idReporteVisual" value="<?php echo $idReporte; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>

                                                </tr>
                                            <?php

                                                $numReporte = $numReporte + 1;
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
                                                    <center>Encargado<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Reporte<center>
                                                </th>
                                                <th>
                                                    <center>Turno<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
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