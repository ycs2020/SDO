<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Actas</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Actas</h4>
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
                            <!-- /.card-header -->
                            <div class="card-header">

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                                <form method="post" action="actas-formulario.php">

                                    <button type="submit" style="float: right;" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Actas</button>

                                    <br>
                                    <br>
                                </form>
                                <?php

                                if (isset($_GET['exitoCrearActa'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Creada con Exito.</h5></div>';
                                }

                                if (isset($_GET['exitoModActa'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Acta Modificada con Exito.</h5></div>';
                                }

                                if (isset($_GET['errorCrearActa'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorCrearFolio'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Folio de Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorVisualizarActa'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Visualizar Acta. Reintente.</h5></div>';
                                }

                                if (isset($_GET['vehiculo1Vacio'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Datos de Vehiculo N° 1 estan Vacios. Reintente.</h5></div>';
                                }

                                if (isset($_GET['soloVehiculo1'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1.</h5></div>';
                                }

                                if (isset($_GET['sinEstacionamiento'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Persona No Requiere Estacionamiento.</h5></div>';
                                }

                                if (isset($_GET['exitoCrearActaVehiculo1y2'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Se ha inscrito Vehiculo N° 1 y Vehiculo N° 2.</h5></div>';
                                }

                                if (isset($_GET['errorVehiculo1'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 1. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorVehiculo2'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Inscribir Vehiculo N° 2. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existePatente1'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 1 ya se encuentra registrada.</h5></div>';
                                }

                                if (isset($_GET['existePatente2'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Patente de Vehiculo N° 2 ya se encuentra registrada.</h5></div>';
                                }

                                ?>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">

                                    <table id="miTabla" class="table table-bordered text-center">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Usuario de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>RUT de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Unidad de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Funcionario
                                                    <center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Usuario de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>RUT de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Unidad de la Tarjeta
                                                    <center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Funcionario
                                                    <center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
                                                    "url": "ajax-actas-listado.php", // Archivo PHP que contiene la consulta SQL
                                                    "type": "POST",
                                                    "data": function (d) {

                                                    },
                                                    "dataSrc": ""
                                                },
                                                "columns": [
                                                    {"data": "nomTarjeta"},
                                                    {"data": "rutTarjeta"},
                                                    {"data": "codigoTarjeta"},
                                                    {"data": "unidadTarjeta"},
                                                    {
                                                        "data": null,
                                                        "render": function (data, type, row) {
                                                            // Agrega el formulario y el botón de visualización para cada fila
                                                            return '' +
                                                                '<form style="margin: 0; text-align: center;" class="" action="actas-funcionario.php" method="post">' +
                                                                '<input type="hidden" name="idActa" value="' + row.idActa + '">' +
                                                                '<input type="hidden" name="rutActa2" value="' + row.rutTarjeta + '">' +
                                                                '<center>' +
                                                                (row.idActa < 1 ?
                                                                        '<button type="button" class="btn btn-primary" disabled>Deshabilitado</button>' :
                                                                        '<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar</button>'
                                                                ) +
                                                                '</center>' +
                                                                '</form>';
                                                        }

                                                    }



                                                    // Agrega más columnas según sea necesario
                                                ],
                                                "createdRow": function (row, data, dataIndex) {
                                                    $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                }
                                            });
                                        });
                                    </script>
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
<script>
    $(function () {
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