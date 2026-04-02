<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');
$fecha_actual = date("Y-m-d");

//VARIABLES DE SESSION

$nomUsuario = $_SESSION['nomUsuario'];

//$valorInicial = 157614000;

$valorTotal = valorTotalViajeNuevoVamoos();
$valorTotalExtension = valorTotalExtensionVamoos();

$valorActual = $valorTotal + $valorTotalExtension;

$valorActual = '$' . number_format($valorActual, 0, ",", ".");

$now = date('Y-m-d H:i:s');


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de viajes</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        /* Forzar alineación vertical centrada en toda la tabla */
        #tablaViajesVamoos th,
        #tablaViajesVamoos td {
            vertical-align: middle !important;
            text-align: center !important;
            white-space: nowrap;
            padding: 8px 5px !important;
        }

        /* Estilo para el thead para que destaque y esté alineado */
        #tablaViajesVamoos thead th {
            background-color: #6c757d !important;
            color: white !important;
            vertical-align: middle !important;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* Ajuste de la tabla para que ocupe el 100% */
        #tablaViajesVamoos {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* Evitar que el dropdown de acciones se corte */
        .table-responsive {
            overflow: visible !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-10 col-9">
                        <div class="card bg-primary text-white shadow-sm mb-0">
                            <div class="card-body py-2 text-center">
                                <h5 class="mb-0"><i class="fas fa-industry mr-2"></i> Listado de viajes realizados - Vamoos SpA</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-3">
                        <a href="viajes-1.php" class="btn btn-danger btn-block font-weight-bold shadow-sm">
                            <i class="fa fa-chevron-left"></i> VOLVER
                        </a>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-md-center align-items-stretch">

                    <!-- DINERO GASTADO -->
                    <div class="col-xl-3 col-lg-4 col-12 mb-3">
                        <div class="card card-primary h-100 mb-0">
                            <div class="card-body d-flex align-items-center py-2"> <button class="btn btn-info btn-block shadow-sm h-100 py-2" style="pointer-events: none; min-height: 60px;" id="btn-dinero-gastado">
                                    <small class="d-block"><b><i class="fas fa-car"></i> DINERO GASTADO:</b></small>
                                    <span class="h5 mb-0"><?= $valorActual; ?></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- MODULOS -->
                    <div class="col-xl-9 col-lg-8 col-12 mb-3">
                        <div class="card card-primary h-100 mb-0">
                            <div class="card-body py-2"> <div class="row h-100">
                                    <div class="col-lg-4 col-md-6 col-12 my-1">
                                        <button class="btn btn-success btn-sm btn-block shadow-sm h-100 d-flex align-items-center justify-content-center py-2" id="btn-anadir-viaje">
                                            <i class="fas fa-plus mr-2"></i> <b>AÑADIR VIAJE</b>
                                        </button>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12 my-1">
                                        <button class="btn btn-warning btn-sm btn-block shadow-sm h-100 d-flex align-items-center justify-content-center py-2" id="btn-generar-requerimiento">
                                            <i class="fas fa-file-pdf mr-2"></i> <b class="text-dark">GENERAR REQUERIMIENTO</b>
                                        </button>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12 my-1">
                                        <button class="btn btn-secondary btn-sm btn-block shadow-sm h-100 d-flex align-items-center justify-content-center py-2" id="btn-generar-reporte">
                                            <i class="fas fa-file-pdf mr-2"></i> <b>DESCARGAR REPORTE</b>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABLA -->
                    <div class="col-12 mb-3">
                        <div class="card card-primary h-100 mb-0">
                            <div class="card-body d-flex align-items-center justify-content-center">

                                <div class="table-responsive">

                                    <table id="tablaViajesVamoos" style="font-size: 12px" class="table table-bordered table-hover text-center table-sm">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Fecha Ingreso Plataforma</th>
                                            <th>Destino</th>
                                            <th>Patente</th>
                                            <th>Tipo de Vehiculo</th>
                                            <th>Unidad</th>
                                            <th>Conductor</th>
                                            <th>Cantidad</th>
                                            <th>N° Boleta</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Tiempo Trabajado</th>
                                            <th>Valor</th>
                                            <th>Valor Extension</th>
                                            <th>Valor Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Fecha Ingreso Plataforma</th>
                                            <th>Destino</th>
                                            <th>Patente</th>
                                            <th>Tipo de Vehiculo</th>
                                            <th>Unidad</th>
                                            <th>Conductor</th>
                                            <th>Cantidad</th>
                                            <th>N° Boleta</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Tiempo Trabajado</th>
                                            <th>Valor</th>
                                            <th>Valor Extension</th>
                                            <th>Valor Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                    <script>
                                        function actualizarDineroGastado() {
                                            $.ajax({
                                                url: '../sdo-includes/viajes/ajax-get-total-gastado.php',
                                                type: 'GET',
                                                success: function(nuevoTotal) {
                                                    // Actualizamos el texto dentro del botón de dinero gastado
                                                    $('#btn-dinero-gastado span.h5').text(nuevoTotal);
                                                }
                                            });
                                        }

                                        $(document).ready(function () {
                                            // Inicializamos la tabla
                                            var tablaViajesVamoos = $('#tablaViajesVamoos').DataTable({
                                                "lengthChange": false,
                                                "searching": true,
                                                "autoWidth": false,
                                                "responsive": true,
                                                "ordering": false,
                                                "processing": true, // Muestra el indicador de carga
                                                "language": {
                                                    "url": "//cdn.datatables.net/plug-ins/1.13.2/i18n/es-ES.json" // Traducción estándar
                                                },
                                                "ajax": {
                                                    "url": "ajax-viajes-listado-vamoos.php",
                                                    "type": "POST",
                                                    "dataSrc": ""
                                                },
                                                "columns": [
                                                    {"data": "fechaViaje"},
                                                    {"data": "fechaIngresoPlata"},
                                                    {"data": "destinoViaje"},
                                                    {"data": "patenteVehiculo"},
                                                    {"data": "tipoVehiculo"},
                                                    {"data": "unidadViaje"},
                                                    {"data": "conductorViaje"},
                                                    {"data": "tipoJornada"},
                                                    {
                                                        "data": "numeroViaje",
                                                        "render": function (data) {
                                                            return '<span class="text-danger">' + data + '</span>';
                                                        }
                                                    },
                                                    {"data": "horaInicia"},
                                                    {"data": "horaTermino"},
                                                    {"data": "duracionViaje2"},
                                                    {
                                                        "data": "valorViaje",
                                                        "render": function (data) {
                                                            return '<span style="color: limegreen;">' + data + '</span>';
                                                        }
                                                    },
                                                    {"data": "valorExtension"},
                                                    {
                                                        "data": "valorTotal",
                                                        "render": function (data) {
                                                            return '<span style="color: limegreen;">' + data + '</span>';
                                                        }
                                                    },
                                                    {
                                                        "data": null,
                                                        "render": function (data, type, row) {
                                                            var dropdown = `<div class="dropdown">
                                                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Acciones</button>
                                                                <div class="dropdown-menu">
                                                                    <form action="editar-viaje-movilizacion-vamoos-spa.php" method="post">
                                                                        <input type="hidden" name="idViaje" value="${row.idViaje}">
                                                                        <button class="dropdown-item" type="submit"><i class="fas fa-edit"></i> Editar</button>
                                                                    </form>`;

                                                            if (row.hasArchivos > 0) {
                                                                dropdown += `<form action="listado-viajes-vamoos-spa.php" method="POST">
                                                                            <input type="hidden" name="idViaje" value="${row.idViaje}">
                                                                            <button type="submit" class="dropdown-item"><i class="fas fa-download"></i> Descargar</button>
                                                                        </form>`;
                                                            }

                                                            dropdown += `<div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item eliminar text-danger" id="${row.idViaje}" href="#"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                                                    </div></div>`;
                                                            return dropdown;
                                                        }
                                                    }
                                                ],
                                                "createdRow": function (row) {
                                                    $('td', row).addClass('align-middle font-weight-bold');
                                                }
                                            });

                                            // Lógica de eliminación OPTIMIZADA
                                            $(document).on('click', '.eliminar', function (e) {
                                                e.preventDefault();
                                                let id = $(this).attr('id');

                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: '¿Eliminar registro?',
                                                    text: 'Esta acción no se puede deshacer',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sí, eliminar',
                                                    cancelButtonText: 'Cancelar',
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $.ajax({
                                                            url: '../sdo-funciones/eliminar-viaje-movilizacion-vamoos-spa.php',
                                                            type: 'POST',
                                                            data: { id: id },
                                                            success: function (response) {
                                                                Swal.fire('Eliminado', 'Registro borrado.', 'success');
                                                                tablaViajesVamoos.ajax.reload(null, false); // Recarga la tabla
                                                                actualizarDineroGastado(); // <--- AGREGAR ESTO: Recarga el contador de arriba
                                                            }
                                                        });
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    $(function () {
        bsCustomFileInput.init();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });
</script>
</body>

</html>