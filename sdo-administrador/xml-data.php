<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');



?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DATA XML</title>

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
</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h4>Data XML</h4>
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
                                    <br>

                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>idUnique<center>
                                                </th>
                                                <th>
                                                    <center>idDevice<center>
                                                </th>
                                                <th>
                                                    <center>idRegion<center>
                                                </th>
                                                <th>
                                                    <center>name<center>
                                                </th>
                                                <th>
                                                    <center>no<center>
                                                </th>
                                                <th>
                                                    <center>type<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM dataXML';

                                            $res = mysqli_query($conexion, $query);

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $textoDataXML = $row['textoDataXML'];

                                                        $nuevoTextoDataXML = str_replace('<', '', $textoDataXML);

                                                        $nuevoTextoDataXML2 = str_replace('/>', '', $nuevoTextoDataXML);

                                                        echo $nuevoTextoDataXML2;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php

                                                        $porciones = explode('idUnique="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                    <td style="vertical-align:middle;" align="justify">

                                                        <?php

                                                        $porciones = explode('idDevice="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                    <td style="vertical-align:middle;" align="justify">

                                                        <?php

                                                        $porciones = explode('idRegion="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                    <td style="vertical-align:middle;" align="justify">

                                                        <?php

                                                        $porciones = explode('name="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                    <td style="vertical-align:middle;" align="justify">

                                                        <?php

                                                        $porciones = explode('no="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                    <td style="vertical-align:middle;" align="justify">

                                                        <?php

                                                        $porciones = explode('type="', $nuevoTextoDataXML2);
                                                        $porciones = explode('"', $porciones['1']);

                                                        echo $porciones['0'];

                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php
                                            }
                                            ?>



                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>idUnique<center>
                                                </th>
                                                <th>
                                                    <center>idDevice<center>
                                                </th>
                                                <th>
                                                    <center>idRegion<center>
                                                </th>
                                                <th>
                                                    <center>name<center>
                                                </th>
                                                <th>
                                                    <center>no<center>
                                                </th>
                                                <th>
                                                    <center>type<center>
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
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'DataXML',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script src="js/dark-mode.js"></script>
</body>

</html>