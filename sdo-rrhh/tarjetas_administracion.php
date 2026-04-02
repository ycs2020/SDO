<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anioActual = date('Y');

$ipCliente = $_SERVER['REMOTE_ADDR'];


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administraci&oacute;n de Credenciales</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Alinear Select2 dentro de input-group */
        .input-group .select2-container {
            flex: 1 1 auto !important;
            width: 1% !important;
        }

        .input-group .select2-selection {
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container .select2-selection__arrow {
            height: 36px !important;
        }

        /* Ajuste botón verde */
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding-left: 12px;
            padding-right: 12px;
        }

        /* Cambiar texto de warning a negro */
        .toast-warning {
            color: #000 !important;
        }

        /* Opcional: también el título si usas titulo en toastr.warning(title,message) */
        .toast-warning strong {
            color: #000 !important;
        }

        .toast-warning .toast-message::before {
            color: #000 !important;
        }

        .toast-warning::before {
            color: #000 !important;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/unidadPersonal-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Administraci&oacute;n de Tarjetas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger ">
                                <a href="index.php" class="btn btn-block btn-danger">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row" id="botonesMenu">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-header d-flex p-0 text-center bg-gradient-gray text-white">
                                <ul style="font-size: 10pt;" class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                    <li class="nav-item text-white">
                                        <a class="nav-link active text-white" href="#listadoTarjetas" data-toggle="tab">
                                            Listado de Tarjetas
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content p-3">

                                    <div class="tab-pane fade show active" id="listadoTarjetas">

                                        <h5 style="text-align: center; font-weight: bold;">LISTADO DE TARJETAS</h5>

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
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022. Derechos Reservados.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

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

<script>
    $(document).ready(function () {


        $('#tablaListadoTarjetas').on('click', '.btn-vertarjeta', function () {
            var idtarjeta = $(this).data('idtarjeta');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': 'credenciales_tarjetas.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idTarjetaVer',
                'value': idtarjeta
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
    });
</script>


</body>

</html>


