<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['volverAtras'] = "Recibidas";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tickets Recibidos</title>

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

    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>
</head>


<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
        $obtenerSolicitudesCreadas = obtenerSolicitudesCreadas();

        if ($obtenerSolicitudesCreadas > 0) {
            ?>
            <script>
                Swal.fire(
                    'Existen nuevas Solicitudes Creadas.',
                    '',
                    'info'
                )
            </script>
            <?php
        }
        ?>
        <script>
            // Función para obtener el valor de un parámetro en la URL
            function getUrlParameter(name) {
                let url = new URL(window.location.href); // Obtiene la URL actual
                return url.searchParams.get(name); // Obtiene el valor del parámetro 'name'
            }

            // Obtener los valores de los parámetros de la URL
            let agregados = getUrlParameter('agregados');
            let agregadosConTicket = getUrlParameter('agregadosConTicket');
            let error = getUrlParameter('error');
            let noPasa = getUrlParameter('noPasa');

            // Verifica si los parámetros existen antes de mostrar la alerta
            if (agregados !== null && agregadosConTicket !== null) {
                // Crea el mensaje de la alerta
                let mensaje = `Se han subido ${agregados} registros. Se han subido ${agregadosConTicket} registros con ticket.`;

                // Si hay información de error y registros que no pasaron, agregarla al mensaje
                if (error !== null) {
                    mensaje += ` Hubo ${error} errores.`;
                }
                if (noPasa !== null) {
                    mensaje += ` ${noPasa} registros no pasaron.`;
                }

                // Muestra la alerta con SweetAlert
                Swal.fire({
                    text: mensaje,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }

        </script>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-12 d-flex">
                        <div class="card w-100 h-100">
                            <div class="card-body">
                                Tickets Recibidos
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 d-flex">
                        <div class="card w-100 h-100">
                            <div class="card-body">
                                <button class="btn btn-block btn-sm btn-primary" onclick="actualizarTickets()">
                                    <i class="fas fa-arrow-alt-circle-down"></i> Actualizar Tickets SIGH
                                </button>
                                <script>
                                    function actualizarTickets() {
                                        $('.overlay').show();

                                        var form = $('<form>', {
                                            'action': '../sdo-funciones/sigh_actualizarTickets2.php',
                                            'method': 'POST'
                                        });

                                        form.appendTo('body').submit();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 d-flex">
                        <div class="card w-100 h-100">
                            <div class="card-body">
                                <a href="index.php" class="btn btn-danger btn-block btn-sm">
                                    <i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <br>
                                <style>
                                    .estado-ticket {
                                        list-style: none;
                                        padding-left: 0;
                                    }

                                    .estado-ticket li {
                                        margin-bottom: 6px;
                                        font-size: 15px;
                                        display: flex;
                                        align-items: center;
                                    }

                                    .estado-icono {
                                        width: 16px;
                                        height: 16px;
                                        margin-right: 8px;
                                        text-shadow: 0 0 3px #000;
                                    }
                                </style>

                                <h5 class="mb-2">
                                    <i class="fas fa-palette"></i> Colores para Identificar:
                                </h5>
                                <ul class="estado-ticket">
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #28A745;"></i> Solicitud Ingresada
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #FFC107;"></i> Solicitud con Personal Asignado
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #563D7C;"></i> Se encuentra con Solicitud de Compra
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #76D7C4;"></i> Se modifica Departamento Responsable
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #A04000;"></i> Se encuentra con Observaciones Nuevas
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #868678;"></i> Ticket con Programación de Trabajos
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #FF6600;"></i> Ticket - Proyecto
                                    </li>
                                    <li>
                                        <i class="fa fa-circle estado-icono" style="color: #ff0000;"></i> Pendiente
                                    </li>
                                </ul>

                                <table style="font-size:90%" id="tablaSoliRecib" class="table table-bordered table-hover text-center table-sm">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>FOLIO</th>
                                        <th>SOLICITANTE</th>
                                        <th>UBICACIÓN</th>
                                        <th>TIPO DE PROBLEMA</th>
                                        <th>FECHA</th>
                                        <th>PERSONAL ASIGNADO</th>
                                        <th>SITUACIÓN</th>
                                        <th>VISUALIZAR</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>FOLIO</th>
                                        <th>SOLICITANTE</th>
                                        <th>UBICACIÓN</th>
                                        <th>TIPO DE PROBLEMA</th>
                                        <th>FECHA</th>
                                        <th>PERSONAL ASIGNADO</th>
                                        <th>SITUACIÓN</th>
                                        <th>VISUALIZAR</th>
                                    </tr>
                                    </tfoot>
                                </table>


                                <script>
                                    $(document).ready(function () {
                                        var tablaSoliRecib = $('#tablaSoliRecib').DataTable({
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
                                                "url": "sdo-includes/solicitudes_recibidas_data.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {
                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": [
                                                {"data": "folioSolicitud"},
                                                {"data": "nomSolicitante"},
                                                {"data": "ubicacionSolicitud"},
                                                {"data": "problemaCompleto"},
                                                {"data": "inicioSolicitud"},
                                                {"data": "tecnicoSolicitud"},
                                                {"data": "situacionSolicitud"},
                                                {
                                                    "data": null,
                                                    "render": function (data, type, row) {

                                                        if (row.idSolicitud > 0) {
                                                            return '' +
                                                                '<form class="" action="solicitudes_detalle.php" target="_blank" method="post">' +
                                                                '<input type="hidden" name="idSolicitud" value="' + row.idSolicitud + '">' +
                                                                '<center>' +
                                                                '<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-eye"></i></button>' +
                                                                '</center>' +
                                                                '</form>';
                                                        } else {
                                                            return 'DESHABILITADO';
                                                        }
                                                        // Agrega el formulario y el botón de visualización para cada fila

                                                    }
                                                }
                                                // Agrega más columnas según sea necesario
                                            ],
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css({
                                                    'vertical-align': 'middle',
                                                    'text-align': 'center'
                                                });

                                                switch (data.situacionSolicitud) {
                                                    case 'ACTIVO':
                                                        $(row).css('background-color', '#28A745');
                                                        $(row).css('color', '#ffffff');
                                                        break;
                                                    case 'EN DESARROLLO':
                                                        $(row).css('background-color', '#FFC107');
                                                        $(row).css('color', '#000000');
                                                        break;
                                                    case 'PENDIENTE':
                                                        $(row).css('background-color', '#FF0000');
                                                        $(row).css('color', '#FFFFFF');
                                                        break;
                                                    case 'PROGRAMADO':
                                                        $(row).css('background-color', '#868678');
                                                        $(row).css('color', '#000000');
                                                        break;
                                                    case 'SOLICITUD CON OBSERVACIONES NUEVAS':
                                                        $(row).css('background-color', '#A04000');
                                                        $(row).css('color', '#000000');
                                                        break;
                                                    case 'SOLICITUD CON PERSONAL ASIGNADO':
                                                        $(row).css('background-color', '#FFC107');
                                                        $(row).css('color', '#000000');
                                                        break;
                                                    default:
                                                        $(row).css('background-color', '#f0f0f0');
                                                        $(row).css('color', '#000000');
                                                        break;
                                                }
                                            },
                                            initComplete: function () {
                                                this.api().columns([5]).every(function () {
                                                    var column = this;
                                                    var select = $('<select style="width: 100%;"><option value="">Personal Asignado...</option></select>')
                                                        .appendTo($(column.header()).empty())
                                                        .on('change', function () {
                                                            var val = $.fn.dataTable.util.escapeRegex(
                                                                $(this).val()
                                                            );

                                                            column
                                                                .search(val ? '^' + val + '$' : '', true, false)
                                                                .draw();
                                                        });

                                                    column.data().unique().sort().each(function (d, j) {
                                                        select.append('<option value="' + d + '">' + d + '</option>')
                                                    });
                                                });
                                            }
                                        });

                                    });
                                </script>
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
    <div class="overlay">
        <div class="spinner">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <!-- Spinner icon -->
        </div>
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