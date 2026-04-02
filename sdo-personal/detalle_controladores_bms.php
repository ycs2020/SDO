<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Controladores BMS</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
                            <h4>Detalle Controladores BMS</h4>
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
                                                <th>
                                                    <center>N°</center>
                                                </th>
                                                <th>
                                                    <center>Piso<center>
                                                </th>
                                                <th>
                                                    <center>TCC<center>
                                                </th>
                                                <th>
                                                    <center>Controlador<center>
                                                </th>
                                                <th>
                                                    <center>N&uacute;mero de Entrada<center>
                                                </th>
                                                <th>
                                                    <center>Digital Input (UI)<center>
                                                </th>
                                                <th>
                                                    <center>Analog Input (UI)<center>
                                                </th>
                                                <th>
                                                    <center>N&uacute;mero de Salida<center>
                                                </th>
                                                <th>
                                                    <center>Digital Output (UO)<center>
                                                </th>
                                                <th>
                                                    <center>Analog Output (UO)<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM detalle_control_bms ORDER BY idControlBMS ASC';

                                            $res = mysqli_query($conexion, $query);

                                            $numContBMS = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td nowrap="nowrap" style="vertical-align:middle;">
                                                        <center><?php echo $numContBMS; ?></center>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                        <?php
                                                        $piso_ControlBMS = mb_strtoupper($row['piso_ControlBMS']);
                                                        echo $piso_ControlBMS;
                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $tcc_ControlBMS = mb_strtoupper($row['tcc_ControlBMS']);
                                                        echo $tcc_ControlBMS;
                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $controlador_ControlBMS = mb_strtoupper($row['controlador_ControlBMS']);
                                                        echo $controlador_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $numEntrada_ControlBMS = $row['numEntrada_ControlBMS'];
                                                        echo $numEntrada_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $digitalInput_ControlBMS = $row['digitalInput_ControlBMS'];
                                                        echo $digitalInput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $analogInput_ControlBMS = $row['analogInput_ControlBMS'];
                                                        echo $analogInput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $numSalida_ControlBMS = $row['numSalida_ControlBMS'];
                                                        echo $numSalida_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $digitalOutput_ControlBMS = $row['digitalOutput_ControlBMS'];
                                                        echo $digitalOutput_ControlBMS;

                                                        ?>
                                                    </td>
                                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                        <?php
                                                        $analogOutput_ControlBMS = $row['analogOutput_ControlBMS'];
                                                        echo $analogOutput_ControlBMS;

                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php

                                                $numContBMS = $numContBMS + 1;
                                            }
                                            ?>



                                        </tbody>
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
                "pageLength": 10,
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
                },
                initComplete: function() {
                    this.api().columns([3]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Controlador...</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    this.api().columns([1]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Piso...</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });


                }
            });
        });
    </script>
</body>

</html>