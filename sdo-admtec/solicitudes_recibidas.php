<?php
include_once '../sdo-funciones/conexion.php';

include_once '../sdo-funciones/funciones.php';

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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">





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

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Tickets Recibidos</a>
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
                            <div class="card-body">
                                <?php
                                $estados = [
                                    ['color' => '#28A745', 'texto' => 'Solicitud ingresada'],
                                    ['color' => '#FFC107', 'texto' => 'Solicitud con personal asignado'],
                                    ['color' => '#563D7C', 'texto' => 'Solicitud de compra'],
                                    ['color' => '#76D7C4', 'texto' => 'Cambio de departamento responsable'],
                                    ['color' => '#A04000', 'texto' => 'Observaciones nuevas'],
                                    ['color' => '#868678', 'texto' => 'Programación de trabajos'],
                                    ['color' => '#FF6600', 'texto' => 'Ticket – Proyecto'],
                                ];
                                ?>

                                <h5>Colores para identificar</h5>
                                <ul>
                                    <?php foreach ($estados as $e): ?>
                                        <li>
                                            <i class="fa fa-circle" style="color:<?= $e['color']; ?>"></i>
                                            <?= $e['texto']; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr>

                                <div class="table-responsive">
                                    <table id="tableTicketsRecibidos" class="table table-bordered table-striped text-center" style="font-size: 11pt;">
                                        <thead class="bg-secondary text-white">
                                        <tr>
                                            <th>FOLIO</th>
                                            <th>SOLICITANTE</th>
                                            <th>UBICACI&Oacute;N</th>
                                            <th>TIPO PROBLEMA</th>
                                            <th>FECHA</th>
                                            <th>PERSONAL</th>
                                            <th>SITUACIÓN</th>
                                            <th>VISUALIZAR</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-secondary text-white">
                                        <tr>
                                            <th>FOLIO</th>
                                            <th>SOLICITANTE</th>
                                            <th>UBICACI&Oacute;N</th>
                                            <th>TIPO PROBLEMA</th>
                                            <th>FECHA</th>
                                            <th>PERSONAL</th>
                                            <th>SITUACIÓN</th>
                                            <th>VISUALIZAR</th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Page specific script -->

<script>
    $(document).ready(function () {
        var tableTicketsRecibidos = $('#tableTicketsRecibidos').DataTable({
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
                "url": "/sdo-includes/tickets_recibidos_cargar.php",
                "type": "POST",
                "data": function (d) {
                    d.idDepto = <?php echo $idDepartamento; ?>
                },
                "dataSrc": function (json) {
                    if (json.debug_sql) {
                        console.log("[SQL DEBUG]", json.debug_sql);
                    }
                    return json.data; // IMPORTANTE: retornar el array "data"
                }
            },
            "columns": [
                {"data": "folioSolicitud2"},
                {"data": "nomSolicitante2"},
                {"data": "ubicacionSolicitud2"},
                {"data": "tipoProblemaCompleto"},
                {"data": "inicioSolicitud2"},
                {"data": "tecnicoAsignadoCompleto"},
                {"data": "situacionSolicitud2"},
                {"data": "btnVisualizar"}



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                const situacion = data.situacionSolicitud2;  // MAYÚSCULAS
                const tecnico = data.tecnicoSolicitud2;      // MAYÚSCULAS
                const idSolicitud = data.idSolicitud;

                let colorTR = "";

                switch (true) {
                    case (situacion === "SOLICITUD CREADA" || situacion === "ACTIVO"):
                        colorTR = "background-color: #28A745; color:white;";
                        break;

                    case (situacion === "SOLICITUD CON PERSONAL ASIGNADO" || tecnico !== "NO APLICA"):
                        colorTR = "background-color: #FFC107; color:black;";
                        break;

                    case (data.contarSolicitudComprasSC > 0):
                        colorTR = "background-color: #563D7C; color:white;";
                        break;

                    case (data.getNuevaObservacionSolicitud === 1):
                        colorTR = "background-color: #A04000; color:white;";
                        break;

                    case (situacion === "TICKET CON PROGRAMACIÓN DE TRABAJOS"):
                        colorTR = "background-color: #868678; color:black;";
                        break;

                    case (situacion === "TICKET DE PROYECTO"):
                        colorTR = "background-color: #FF6600; color:white;";
                        break;

                    case (situacion === "SE MODIFICA DEPARTAMENTO RESPONSABLE"):
                        colorTR = "background-color: #76D7C4; color:black;";
                        break;
                }

                if (colorTR !== "") {
                    $(row).attr('style', colorTR);
                }

                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
            },
            initComplete: function () {
                this.api().columns([6]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">SITUACIÓN</option></select>')
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

            },

        });

        $(document).on('click', '.btnVisualSolic', function (e) {
            e.preventDefault();

            const idSolicitud = $(this).data('idticket');
            const form = $('<form>', {
                method: 'POST',
                action: 'solicitudes_detalle.php',
                target: '_blank'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: 'idSolicitud',
                value: idSolicitud
            }));

            // Agrega y envía el form
            $('body').append(form);
            form.trigger('submit');
            form.remove();
        });
    });
</script>

</body>

</html>
