<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];


date_default_timezone_set('America/Santiago');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Temperatura de Pabellones</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                        <h4>Registro de Temperatura de Pabellones</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <?php

        if ($descargaConfirma == "Y" || $descargaConfirma == "y") {
            ?>
            <script>
                Swal.fire({
                    title: 'Descarga de Cotizaciones con &Eacute;xito.',
                    confirmButtonText: 'Cerrar',
                    icon: 'success',
                });
            </script>
        <?php
        } else if ($descargaConfirma == "N" || $descargaConfirma == "n") {
        ?>
            <script>
                Swal.fire({
                    title: 'Error al Descargar Cotizaci&oacute;n.',
                    confirmButtonText: 'Cerrar',
                    icon: 'error',
                });
            </script>
            <?php
        }


        ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="pabellones_administracion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Temperatura en Pabellones</h5>
                                <hr>

                                <table style="font-size:90%" id="miTabla" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th style="vertical-align:middle; text-align: center;">
                                            FECHA DE REGISTRO
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PERSONAL
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 2
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 3
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 4
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 5
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 6
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 7
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 8
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 9
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 10
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary">

                                    <tr>
                                        <th style="vertical-align:middle; text-align: center;">
                                            FECHA DE REGISTRO
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PERSONAL
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 2
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 3
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 4
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 5
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 6
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 7
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 8
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 9
                                        </th>
                                        <th style="vertical-align:middle; text-align: center;">
                                            PABELL&Oacute;N 10
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
            </div>
            <!-- /.timeline -->

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
<script src="js/dark-mode.js"></script>
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
    $(document).ready(function () {
        var miTabla = $('#miTabla').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-temperatura-pabellones.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {

                },
                "dataSrc": ""
            },
            "columns": [
                {"data": "fechaTemperatura"},
                {"data": "tecnicoTemperatura"},
                {"data": "pabellon2Temperatura"},
                {"data": "pabellon3Temperatura"},
                {"data": "pabellon4Temperatura"},
                {"data": "pabellon5Temperatura"},
                {"data": "pabellon6Temperatura"},
                {"data": "pabellon7Temperatura"},
                {"data": "pabellon8Temperatura"},
                {"data": "pabellon9Temperatura"},
                {"data": "pabellon10Temperatura"},




                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });
    });
</script>

</body>

</html>

