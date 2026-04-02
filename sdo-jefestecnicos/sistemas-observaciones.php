<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// $idEquipo = '';
// $nomEquipo = '';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

// $fecha = date('d-m-Y h:i a');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Observaciones Generales</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
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
                                    <h4>Observaciones Generales</h4>

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12">
                                            <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                            <ul>
                                                <li><i style="color: #28A745; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Encendido</li>

                                                <li><i style="color: #E83E8C; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Apagado</li>

                                                <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado - Variables</li>

                                                <li><i style="color: #6C757D; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Forzado</li>

                                                <li><i style="color: #000080; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Modo Automatico</li>

                                                <li><i style="color: #001F3F; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Observaci&oacute;n Normal</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <br>

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Equipo</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Departamento:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha Observaciones:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Nombre del Tecnico:<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Tipo de Observaci&oacute;n:<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Observaciones:<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM equipos_observaciones ORDER BY fechaObservaciones DESC";

                                            $res = mysqli_query($conexion, $query);

                                            $numReporte = 1;

                                            while ($row = $res->fetch_assoc()) {


                                                $esForzado = $row['esForzado'];

                                                $classObservacion = "";

                                                if ($esForzado == "Forzado - Encendido") {

                                                    $classObservacion = "bg-success";
                                                } else if ($esForzado == "Forzado - Apagado") {

                                                    $classObservacion = "bg-pink";
                                                } else if ($esForzado == "Forzado - Variables") {

                                                    $classObservacion = "bg-yellow";
                                                } else if ($esForzado == "Modo Automatico") {

                                                    $classObservacion = "bg-navy";
                                                } else if ($esForzado == "Observación Normal") {

                                                    $classObservacion = "bg-primary";
                                                } else if ($esForzado == "Forzado") {

                                                    $classObservacion = "bg-gray";
                                                } else {

                                                    $classObservacion = "bg-light";
                                                }

                                            ?>

                                                <tr class="<?php echo $classObservacion; ?>">
                                                    <td nowrap="nowrap" style="vertical-align:middle; font-weight: normal;">
                                                        <center>
                                                            <?php echo mb_strtoupper(getNomEquipo($row['idEquipo']), 'UTF-8'); ?>
                                                        </center>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle; font-weight: normal;">
                                                        <center>
                                                            <?php echo getCategoriaEquipo($row['idEquipo']); ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle; font-weight: normal;">
                                                        <center>
                                                            <?php if ($row['fechaObservaciones'] == 0) {
                                                                echo "No registra fecha";
                                                            } else {
                                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                                $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                                echo $inicioFecha . '<br>' . $inicioHora;
                                                            } ?>
                                                        </center>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle; font-weight: normal;">
                                                        <center>
                                                            <?php echo $row['personalObservacion']; ?>
                                                        </center>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle; text-align:justify; font-weight: normal;">
                                                        <center><?php echo $row['esForzado']; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:justify; font-weight: normal;">
                                                        <?php echo nl2br($row['detalleObservaciones']); ?>
                                                    </td>
                                                </tr>

                                            <?php

                                                $numReporte++;
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>Equipo</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Departamento:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Fecha Observaciones:</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Nombre del Tecnico:<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Tipo de Observaci&oacute;n:<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Observaciones:<center>
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