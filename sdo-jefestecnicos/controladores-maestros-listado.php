<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Controladores Maestros</title>

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
                            <h4>Listado de Servidor BMS</h4>
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
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="vertical-align:middle;">
                                                    <center>N°</center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>EC Boss<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Piso<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ejes EC Boss<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ubicaci&oacute;n EC Boss<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ejes Rack<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Ubicaci&oacute;n Rack<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Rack<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Puerto<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Direcci&oacute;n IP<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>COM Network<center>
                                                </th>
                                                <th style="vertical-align:middle;">
                                                    <center>Red de Comunicaci&oacute;n<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM controladores_maestros ORDER BY idMaster ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numContMaestro = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numContMaestro; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $ecBoss = mb_strtoupper($row['ecBoss']);
                                                        echo $ecBoss;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <center>
                                                            <?php
                                                            $piso = $row['piso'];
                                                            echo $piso;

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $ejesECBoss = $row['ejesECBoss'];
                                                        echo $ejesECBoss;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $ubicacionECBoss = $row['ubicacionECBoss'];
                                                        echo $ubicacionECBoss;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $ejesRack = $row['ejesRack'];
                                                        echo $ejesRack;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $ubicacionRack = $row['ubicacionRack'];
                                                        echo $ubicacionRack;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $rack = $row['rack'];
                                                        echo $rack;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $puerto = $row['puerto'];
                                                        echo $puerto;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                                                        <?php
                                                        $direccionIP = $row['direccionIP'];
                                                        echo $direccionIP;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $queryCOM = 'SELECT * FROM com_network WHERE idMaster = ' . $row['idMaster'] . ' ORDER BY nomComNetwork ASC';

                                                            $resCOM = mysqli_query($conexion, $queryCOM);

                                                            $numCOMMaestro = 1;

                                                            while ($rowCOM = $resCOM->fetch_assoc()) {

                                                                echo $rowCOM['nomComNetwork'] . '<br>';
                                                            }
                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $queryRedComunica = 'SELECT * FROM redes_comunicacion WHERE idMaster = ' . $row['idMaster'] . ' ORDER BY nomRedComunicacion ASC';

                                                            $resRedComunica = mysqli_query($conexion, $queryRedComunica);

                                                            $numRedComunica = 1;

                                                            while ($rowRedComunica = $resRedComunica->fetch_assoc()) {

                                                                echo $rowRedComunica['nomRedComunicacion'] . '<br>';
                                                            }
                                                            ?>
                                                        </center>
                                                    </td>

                                                </tr>
                                            <?php

                                                $numContMaestro = $numContMaestro + 1;
                                            }
                                            ?>



                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <th style="vertical-align:middle;">
                                                <center>N°</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>EC Boss<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Piso<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ejes EC Boss<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ubicaci&oacute;n EC Boss<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ejes Rack<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ubicaci&oacute;n Rack<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Rack<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Puerto<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Direcci&oacute;n IP<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>COM Network<center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Red de Comunicaci&oacute;n<center>
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