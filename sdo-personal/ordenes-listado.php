<?php
include '../sdo-funciones/conexion.php';


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Recepciones de Trabajos</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
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
                            <h4>Listado de Recepciones de Trabajos</h4>
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
                                                    <center>Folio Recepci&oacute;n de Trabajo<center>
                                                </th>
                                                <th>
                                                    <center>Nombre del Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n de Trabajo<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ordenes_trabajos WHERE idDepartamento = ' . $idDepartamento . ' ORDER BY fechaOrden DESC';

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
                                                        $folioOrden = mb_strtoupper($row['folioOrden']);
                                                        echo $folioOrden;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nomSolicitante = $row['nomSolicitante'];
                                                        echo $nomSolicitante;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php if ($row['fechaOrden'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                                    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                } ?></center>
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
                                                                    <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Recepci&oacute;n de Trabajo">

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
                                                    <center>Folio Recepci&oacute;n de Trabajo<center>
                                                </th>
                                                <th>
                                                    <center>Nombre del Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n de Trabajo<center>
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

    <!-- DARK MODE-->
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