<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['volverAtras'] = "Finalizadas";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tickets Finalizados</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
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
                        <div class="col-sm-6">
                            <h4>Tickets Finalizados</h4>
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
                                </div>
                                <div class="card-body">
                                    <br>
                                    <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                    <ul>
                                        <li><i style="color: gray; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Solicitud Finalizada</li>
                                    </ul>

                                    <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-info">
                                            <th style="vertical-align:middle;">
                                                <center>FOLIO SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>NOMBRE SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>UBICACI&Oacute;N SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TIPO DE PROBLEMA<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA DE FINALIZACI&Oacute;N<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>&Uacute;LTIMO PERSONAL ASIGNADO<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VISUALIZAR<center>
                                            </th>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM solicitudes_soportes WHERE finalizadaSolicitud = 1 AND canceladaSolicitud = 0 AND deptoResponsable = "' . $nomDepartamento . '" ORDER BY inicioSolicitud DESC';

                                            $res = mysqli_query($conexion, $query);

                                            $numSolicitud = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr style="background-color:gray; color:white;">
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $folioSolicitud = mb_strtoupper($row['folioSolicitud']);
                                                        echo $folioSolicitud;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php
                                                            $nomSolicitante = mb_strtoupper($row['nomSolicitante']);

                                                            $deptoSolicitante = explode('/', $row['deptoSolicitante']);

                                                            $longitud_deptoSolicitante = count($deptoSolicitante);

                                                            $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

                                                            $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);



                                                            echo $nomSolicitante . '<br> ( ' . $ultima_deptoSolicitante . ' )';

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php
                                                            $ubicacionSolicitud = mb_strtoupper($row['ubicacionSolicitud']);
                                                            echo $ubicacionSolicitud;

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">

                                                        <center>
                                                            <?php
                                                            $deptoResponsable = mb_strtoupper($row['deptoResponsable']);

                                                            $tipoProblemaSolicitud = mb_strtoupper($row['tipoProblemaSolicitud']);

                                                            echo $deptoResponsable . ':<br>' . $tipoProblemaSolicitud;

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php if ($row['terminoSolicitud'] == 0) {
                                                                echo "No registra fecha";
                                                            } else {

                                                                $terminoFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['terminoSolicitud'])));
                                                                $terminoHora = date('H:i', strtotime($row['terminoSolicitud']));

                                                                echo $terminoFecha . '<br>' . $terminoHora;
                                                            } ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php
                                                            $tecnicoSolicitud = trim($row['tecnicoSolicitud']);

                                                            $tecnicoSolicitud = mb_strtoupper($row['tecnicoSolicitud'], "UTF-8");

                                                            if (empty($tecnicoSolicitud)) {
                                                                echo 'Sin Asignar';
                                                            } else {
                                                                $deptoTecnico = explode(' (', $tecnicoSolicitud);
                                                                echo $deptoTecnico[0] . '<br>(' . $deptoTecnico[1];
                                                            }

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idSolicitud = $row['idSolicitud'];
                                                            $idSolicitud = trim($idSolicitud);
                                                            if ($idSolicitud == 0 || $idSolicitud == "" || $idSolicitud == " " || $idSolicitud == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="solicitudes_detalle.php" method="post">

                                                                    <input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>
                                                </tr>
                                            <?php

                                                $numSolicitud = $numSolicitud + 1;
                                            }
                                            ?>



                                        </tbody>
                                        <tfoot class="bg-info">
                                            <th style="vertical-align:middle;">
                                                <center>FOLIO SOLICITUD<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>NOMBRE SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>UBICACI&Oacute;N SOLICITANTE<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>TIPO DE PROBLEMA<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>FECHA DE FINALIZACI&Oacute;N<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>&Uacute;LTIMO PERSONAL ASIGNADO<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>VISUALIZAR<center>
                                            </th>
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