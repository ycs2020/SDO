<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
// echo '';


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Ticket SIGH</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h3>Listado de Tickets SIGH</h3>
            </div>
            <!-- /.container-fluid -->
        </div>
        <?php
        if ($_GET['exitoModiEstado']) {
            ?>
            <script>
                Swal.fire({
                    title: "Exito!",
                    text: "Se ha modificado el estado!",
                    icon: "success"
                });
            </script>
            <?php
        }
        if ($_GET['exitoObs']) {
            ?>
            <script>
                Swal.fire({
                    title: "Exito!",
                    text: "Se ha agregado la observación con exito!",
                    icon: "success"
                });
            </script>
            <?php
        }
        if ($_GET['errorModiEstado']) {
            ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error.",
                    text: "Ha fallado la modificación. Por favor, reintente.",
                });
            </script>
            <?php
        }
        if ($_GET['errorObs']) {
            ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error.",
                    text: "Ha fallado el ingreso de la observación. Por favor, reintente.",
                });
            </script>
            <?php
        }

        ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">

                                <table id="miTabla" style="font-size: 10pt;" class="table table-bordered text-center">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>N° Ticket</th>
                                        <th>Solicitante</th>
                                        <th>Unidad</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Tipo de Solicitud</th>
                                        <th>Funcionario Asignado</th>
                                        <th>Fecha Cierre Ticket</th>
                                        <th>Estado</th>
                                        <th>Editar Estado</th>
                                        <th>Ver Detalles</th>
                                        <th>Agregar Observación</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>N° Ticket</th>
                                        <th>Solicitante</th>
                                        <th>Unidad</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Tipo de Solicitud</th>
                                        <th>Funcionario Asignado</th>
                                        <th>Fecha Cierre Ticket</th>
                                        <th>Estado</th>
                                        <th>Editar Estado</th>
                                        <th>Ver Detalles</th>
                                        <th>Agregar Observación</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    function generarModalObservaciones(row) {
                                        return '<div class="modal fade" id="obs' + row.idTicketSIGH + '" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">' +
                                            '<div class="modal-dialog modal-lg" role="document">' +
                                            '<div class="modal-content">' +
                                            '<div class="modal-header bg-primary">' +
                                            '<h5 class="modal-title" id="modalDetalleLabel">Observaciones Ticket N°: ' + row.numTicketSIGH + '</h5>' +
                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">' +
                                            '<span aria-hidden="true">&times;</span>' +
                                            '</button>' +
                                            '</div>' +
                                            '<form action="../sdo-funciones/guardar-obs-sigh.php" method="POST">' +
                                            '<div class="modal-body">' +
                                            '<div class="form-group">' +
                                            '<label for="estado">Ingrese una Observacion</label>' +
                                            '<textarea id="obs" name="obs" style="resize: none" rows="5" class="form-control"></textarea>' +
                                            '<input type="hidden" id="idTicketSIGH" name="idTicketSIGH" value="' + row.idTicketSIGH + '">' +
                                            '<br><button type="submit" class="btn btn-warning" style="float: right">Guardar</button><br><br>' +
                                            '</form>' +
                                            '</div>' +
                                            '<table class="table table-bordered" id="tablaObservaciones' + row.idTicketSIGH + '">' +
                                            '<thead class="bg-secondary">' +
                                            '<tr>' +
                                            '<th>Creador</th>' +
                                            '<th>Fecha</th>' +
                                            '<th>Detalle</th>' +
                                            '</tr>' +
                                            '</thead>' +
                                            '<tbody></tbody>' +
                                            '<tfoot class="bg-secondary">' +
                                            '<th>Creador</th>' +
                                            '<th>Fecha</th>' +
                                            '<th>Detalle</th>' +
                                            '</tfoot>' +
                                            '</table>' +
                                            '<br>' +
                                            '</div>' +
                                            '<div class="modal-footer">' +
                                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>' +
                                            '</div>';
                                    }

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
                                                "url": "ajax-ticket-sigh.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "dataSrc": ""
                                            },
                                            "columns": [
                                                {"data": "numTicketSIGH"},
                                                {"data": "nomTicketSIGH"},
                                                {"data": "unidadTicketSIGH"},
                                                {"data": "fechaSolicTicketSIGH"},
                                                {"data": "tipos"},
                                                {"data": "nomTecnicoTicketSIGH"},
                                                {"data": "fechaCierreTicketSIGH"},
                                                {"data": "estadoTicketSIGH"},
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        // Agrega un botón para abrir el modal para cada fila
                                                        return '<center>' +
                                                            '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalDetalle' + row.idTicketSIGH + '"><i class="fas fa-pen"></i></button>' +
                                                            '</center>' +
                                                            '<div class="modal fade" id="modalDetalle' + row.idTicketSIGH + '" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">' +
                                                            '<div class="modal-dialog" role="document">' +
                                                            '<div class="modal-content">' +
                                                            '<div class="modal-header bg-warning">' +
                                                            '<h5 class="modal-title" id="modalDetalleLabel">Editar Estado</h5>' +
                                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>' +
                                                            '<form action="../sdo-funciones/cambiar-estado-sigh.php" method="POST">' +
                                                            '<div class="modal-body">' +
                                                            '<div class="form-group">' +
                                                            '<label for="estado">Seleccione una Opcion</label>' +
                                                            '<select class="form-control" id="estado" name="estado">' +
                                                            '<option value="" selected disabled>Seleccione</option>' +
                                                            '<option value="Activo">Activo</option>' +
                                                            '<option value="En Desarrollo">En Desarrollo</option>' +
                                                            '<option value="Pendiente">Pendiente</option>' +
                                                            '<option value="Finalizado">Finalizado</option>' +
                                                            '<option value="Programado">Programado</option>' +
                                                            '</select>' +
                                                            '</div>' +
                                                            // Otros campos de formulario...
                                                            '</div>' +
                                                            '<div class="modal-footer">' +
                                                            '<input type="hidden" id="idTicketSIGH" name="idTicketSIGH" value="' + row.idTicketSIGH + '">' +
                                                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
                                                            '<button type="submit" class="btn btn-success">Modificar</button>' +
                                                            '</form>' +
                                                            // Puedes agregar más botones de acción si es necesario
                                                            '</div>' +
                                                            '</div>' +
                                                            '</div>' +
                                                            '</div>';
                                                    }
                                                },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        return '<center>' +
                                                            '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalVerDetalle' + row.idTicketSIGH + '"><i class="fas fa-eye"></i></button>' +
                                                            '</center>' +
                                                            '<div class="modal fade" id="modalVerDetalle' + row.idTicketSIGH + '" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">' +
                                                            '<div class="modal-dialog" role="document">' +
                                                            '<div class="modal-content">' +
                                                            '<div class="modal-header bg-primary">' +
                                                            '<h5 class="modal-title" id="modalDetalleLabel">Visualizar Detalle</h5>' +
                                                            '<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>' +
                                                            '<div class="modal-body">' +
                                                            '<div class="form-group">' +
                                                            '<textarea readonly style="resize: none" rows="5" class="form-control">' + row.detalleTicketSIGH + '</textarea>' +
                                                            '</div>' +
                                                            // Otros campos de formulario...
                                                            '</div>' +
                                                            '<div class="modal-footer">' +
                                                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
                                                            // Puedes agregar más botones de acción si es necesario
                                                            '</div>' +
                                                            '</div>' +
                                                            '</div>' +
                                                            '</div>';
                                                    }
                                                },
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {
                                                        var modalContent = '<center>' +
                                                            '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#obs' + row.idTicketSIGH + '"><i class="fas fa-list-alt"></i></button>' +
                                                            '</center>' +
                                                            generarModalObservaciones(row);

                                                        // Realizar la llamada AJAX para obtener los datos del tbody
                                                        $.ajax({
                                                            url: 'ajax-observaciones-sigh.php',
                                                            method: 'POST',
                                                            data: {idTicketSIGH: row.idTicketSIGH},
                                                            success: function (response) {

                                                                var tbodyContent = '';
                                                                if (response.length > 0) {

                                                                    // Insertar los datos en el tbody
                                                                    response.forEach(function (observation) {
                                                                        tbodyContent += '<tr>' +
                                                                            '<td>' + observation.creador + '</td>' +
                                                                            '<td>' + observation.fecha + '</td>' +
                                                                            '<td>' + observation.detalle + '</td>' +
                                                                            '</tr>';
                                                                    });
                                                                    $('#tablaObservaciones' + row.idTicketSIGH + ' tbody').html(tbodyContent);
                                                                } else {

                                                                    // Si no hay observaciones, mostrar un mensaje
                                                                    tbodyContent += '<tr><td colspan="3">No se encontraron observaciones para este ticket.</td></tr>';
                                                                    $('#tablaObservaciones' + row.idTicketSIGH + ' tbody').html(tbodyContent);
                                                                }
                                                            },
                                                            error: function (xhr, status, error) {
                                                                console.error('Error al cargar los datos del tbody:', error);
                                                            }
                                                        });

                                                        return modalContent;
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
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<footer class="main-footer">
    <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
</footer>
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- Control Sidebar -->
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
