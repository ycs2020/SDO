<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Reportes Generales</title>

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


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

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
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none; font-size: 13pt; text-transform: uppercase" class="btn font-weight-bold">Listado de Reportes</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php

                $notificacionesSweet = [
                    'loginExito' => ['Sesión iniciada con éxito.', 'success'],
                    'exitoModificarReporte' => ['Reporte Modificado con Éxito.', 'success'],
                    'errorModificarFotoReporte' => ['Error al Modificar Imagenes de Reporte. Reintente.', 'error'],
                    'errorModificarReporte' => ['Error al Modificar Reporte. Reintente.', 'error'],

                    'exitoEliminarReporte' => ['Reporte Eliminado con Éxito.', 'success'],
                    'errorEliminarFotoReporte' => ['Error al Eliminar Imagenes de Reporte. Reintente.', 'error'],
                    'errorEliminarReporte' => ['Error al Eliminar Reporte. Reintente.', 'error'],

                    'errorSeleccionarReporte' => ['Error al Seleccionar Reporte. Reintente.', 'error'],

                    'reporteConFoto' => ['Reporte Con Imágenes Modificado con Éxito.', 'success'],
                    'reporteSinFoto' => ['Reporte Sin Imágenes Modificado con Éxito.', 'success'],

                    'sobrepasoImagen' => ['Imágenes exceden el Tamaño Maximo permitido. Reintente.', 'error'],

                    'errorCargaFoto' => ['Error al Subir Imágenes. Reintente.', 'error'],

                    'formatoIncorrectoFoto' => ['Formato de Imagen Incorrecto. Reintente.', 'error'],
                ];

                include_once "../sdo-includes/sweetalert_dark_mode.php";

                ?>



                <?php


                if ($nomDepartamento == 'Instrumentación') {
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="text-align:center;">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                                            <form method="post" action="reportes-formulario.php">

                                                <button type="submit" id="inputMozi" class="btn btn-primary btn-block">
                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Reporte
                                                </button>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div style="font-size: 10pt;" class="card-header bg-secondary d-flex p-0">
                                <ul class="nav nav-pills mr-auto ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="nav-link text-white active" href="#tabControl" data-toggle="tab">Reportes - Control Centralizado</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="#tabElectricos" data-toggle="tab">Reportes - Departamento Electrico</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabControl">
                                        <h5 class="text-center align-content-center">Reportes - Control Centralizado</h5>

                                        <hr>

                                        <div class="table-responsive">


                                            <table class="table table-bordered table-striped table-sm text-sm" id="tablaReportes">
                                                <thead class="bg-secondary text-center">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha</th>
                                                    <th>Personal de Turno</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="bg-secondary text-center">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha</th>
                                                    <th>Personal de Turno</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <!-- Estilo para achicar fuente -->
                                            <style>
                                                #tablaReportes td,
                                                #tablaReportes th {
                                                    vertical-align: middle !important;
                                                    text-align: center !important;
                                                    font-size: 14px !important;
                                                }

                                                .dataTables_wrapper {
                                                    font-size: 14px; /* También afecta paginación, buscador, etc */
                                                }

                                                /* Puedes ajustar aún más si deseas */
                                                #tablaReportes thead th {
                                                    font-size: 14px !important;
                                                }

                                                #tablaReportes tbody td {
                                                    font-size: 14px !important;
                                                }
                                            </style>
                                            <script>
                                                $(document).ready(function () {
                                                    var tablaReportes = $('#tablaReportes').DataTable({
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
                                                            "url": "/sdo-includes/reportes_turno_cargar.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                            },
                                                            "dataSrc": ""
                                                        },
                                                        "columns": [
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row, meta) {
                                                                    return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                                }
                                                            },
                                                            {"data": "cadenaFecha"},
                                                            {"data": "nomEncargadoTurno"},
                                                            {"data": "buttonDescarga"},




                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                                        }
                                                    });

                                                    $('#tablaReportes').on('click', '.btn-visualizar', function () {
                                                        console.log('hola');
                                                        var id = $(this).data('id');
                                                        let ruta = '../sdo-pdf/SDO-TurnoTrabajado.php';

                                                        var form = $('<form>', {
                                                            'action': ruta, // Reemplaza 'tu_url_destino' con la URL de destino
                                                            'method': 'POST',
                                                            'target': '_blank',
                                                        });

                                                        // Agregar un campo oculto para el ID
                                                        form.append($('<input>', {
                                                            'type': 'hidden',
                                                            'name': 'idReporteVisual',
                                                            'value': id
                                                        }));

                                                        // Agregar el formulario al documento y enviarlo
                                                        form.appendTo('body').submit();
                                                    });
                                                });
                                            </script>

                                        </div>

                                    </div>


                                    <div class="tab-pane" id="tabElectricos">
                                        <h5 class="text-center align-content-center">Reportes - Departamento Electrico</h5>

                                        <hr>

                                        <div class="table-responsive">

                                            <table class="table table-bordered table-striped table-sm text-sm" id="tablaRepElectrico">
                                                <thead class="bg-secondary text-center">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Folio</th>
                                                    <th>Fecha</th>
                                                    <th>Turno</th>
                                                    <th>Personal de Turno</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="bg-secondary text-center">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Folio</th>
                                                    <th>Fecha</th>
                                                    <th>Turno</th>
                                                    <th>Personal de Turno</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <!-- Estilo para achicar fuente -->
                                            <style>
                                                #tablaRepElectrico td,
                                                #tablaRepElectrico th {
                                                    vertical-align: middle !important;
                                                    text-align: center !important;
                                                    font-size: 14px !important;
                                                }

                                                .dataTables_wrapper {
                                                    font-size: 14px; /* También afecta paginación, buscador, etc */
                                                }

                                                /* Puedes ajustar aún más si deseas */
                                                #tablaRepElectrico thead th {
                                                    font-size: 14px !important;
                                                }

                                                #tablaRepElectrico tbody td {
                                                    font-size: 14px !important;
                                                }
                                            </style>
                                            <script>
                                                $(document).ready(function () {
                                                    var tablaRepElectrico = $('#tablaRepElectrico').DataTable({
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
                                                            "url": "/sdo-includes/reportes_electrico_cargar.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                            },
                                                            "dataSrc": ""
                                                        },
                                                        "columns": [
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row, meta) {
                                                                    return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                                }
                                                            },
                                                            {"data": "folioReporte"},
                                                            {"data": "fechaReporte"},
                                                            {"data": "tipoReporte"},
                                                            {"data": "nomEncargadoTurno"},
                                                            {"data": "buttonDescarga"},




                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                                        }
                                                    });

                                                    $('#tablaRepElectrico').on('click', '.btn-visualelect', function () {
                                                        console.log('hola');
                                                        var id = $(this).data('id');
                                                        let ruta = '../sdo-pdf/SDO-Reporte-Electricos.php';

                                                        var form = $('<form>', {
                                                            'action': ruta, // Reemplaza 'tu_url_destino' con la URL de destino
                                                            'method': 'POST',
                                                            'target': '_blank',
                                                        });

                                                        // Agregar un campo oculto para el ID
                                                        form.append($('<input>', {
                                                            'type': 'hidden',
                                                            'name': 'id',
                                                            'value': id
                                                        }));

                                                        // Agregar el formulario al documento y enviarlo
                                                        form.appendTo('body').submit();
                                                    });
                                                });
                                            </script>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
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

</body>

</html>