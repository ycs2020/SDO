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
    <title>Listado de UCAS</title>

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
                    <div class="col-sm-6">
                        <h4>Listado de UCAS </h4>
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
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>Ubicaci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Piso<center>
                                                </th>
                                                <th>
                                                    <center>Modulo de Incendio<center>
                                                </th>
                                                <th>
                                                    <center>Ver Puertas<center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center>Nombre<center>
                                                </th>
                                                <th>
                                                    <center>Ubicaci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Piso<center>
                                                </th>
                                                <th>
                                                    <center>Modulo de Incendio<center>
                                                </th>
                                                <th>
                                                    <center>Ver Puertas<center>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <script>
                                            $(document).ready(function () {
                                                var columns = [
                                                    {"data": "name"},
                                                    {"data": "obtenerUbicacionSalaUCA"},
                                                    {"data": "obtenerPisoSalaUCA"},
                                                    {"data": "moduloIncendioUCA"},
                                                    {"data": null,
                                                        "render": function (data, type, row) {
                                                            if (row.idUnique > 0) {
                                                                return '<form style="margin: 0; text-align: center;" class="" action="../sdo-jefestecnicos/ucas-listado-puertas-incendio.php" method="post">' +
                                                                    '<input type="hidden" name="idUCA" id="idUCA" value="' + row.idUnique + '">' +
                                                                    '<button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-door-closed"></i></button>' +
                                                                    '</form>';
                                                            } else {
                                                                return '<center>Error al Cargar Datos.</center>';
                                                            }
                                                        }
                                                    },
                                                    // Otras columnas existentes

                                                    // Agregar columnas dinámicamente
                                                ];




                                                var example2 = $('#example2').DataTable({
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
                                                        "url": "ajax-detalle-ucas.php", // Archivo PHP que contiene la consulta SQL
                                                        "type": "POST",
                                                        "data": function (d) {
                                                            console.log(d);
                                                        },
                                                        "dataSrc": ""
                                                    },
                                                    "columns": columns,
                                                    "createdRow": function (row, data, dataIndex) {
                                                        $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                        $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas

                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
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
<script src="js/dark-mode.js"></script>
</body>

</html>