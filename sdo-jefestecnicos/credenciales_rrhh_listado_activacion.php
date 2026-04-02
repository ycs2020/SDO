<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$horaActual = date('H');
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$idUsuario = $_SESSION['idUsuario'];




?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activaci&oacute;n Credenciales RRHH</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">

        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Activaci&oacute;n Credenciales RRHH</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 10pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabCredMod" data-toggle="tab">Credenciales para Modificar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabCredListado" data-toggle="tab">Listado de Credenciales</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabCredMod">
                                        <h5 class="text-center align-content-center">Credenciales para Modificar</h5>

                                        <hr>

                                    </div>


                                    <div class="tab-pane" id="tabCredListado">
                                        <h5 class="text-center align-content-center">Listado de Credenciales</h5>

                                        <hr>

                                        <div class="table-responsive">

                                            <table id="tablaListadoTarjetas" class="table table-bordered table-hover table-sm">

                                                <thead class="bg-gradient-success">
                                                <tr style="text-transform: uppercase;">
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        C&oacute;digo
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Tipo
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Unidad
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Texto
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Detalle
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot class="bg-gradient-success">
                                                <tr style="text-transform: uppercase;">
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        C&oacute;digo
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Tipo
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Unidad
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Texto
                                                    </th>
                                                    <th style="vertical-align: middle; text-align: center;">
                                                        Detalle
                                                    </th>
                                                </tr>
                                                </tfoot>

                                            </table>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
</div>



<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>
<script>

    $(document).ready(function () {
        var tablaListadoTarjetas = $('#tablaListadoTarjetas').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
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
                "url": "/sdo-includes/credenciales_rrhh_tarjetas_listado.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    // d.idDepartamento = $('#idDeptoHT').val(); // Pasa el valor seleccionado del select como parámetro

                },
                "dataSrc": "data",
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "codigoTarjeta"},
                {"data": "tipoTarjeta"},
                {"data": "unidadTarjeta"},
                {"data": "textoTarjeta"},
                {"data": "buttonVisualizar"},


                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle');
                $('td', row).css('text-align', 'center');
                $('td', row).css('text-transform', 'uppercase');
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                // $('td', row).css('font-size', '10pt');

            }
        });
    });
</script>




</body>
</html>

