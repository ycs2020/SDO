<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Reportes</title>

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
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
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
                                <a style="pointer-events: none;" class="btn font-weight-bold">Listado de Reportes</a>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <a href="reportes-formulario.php" class="btn btn-block btn-success "><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Reporte</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" name="nomUsuarioModificar" id="nomUsuarioModificar" value="<?php echo $nomUsuario; ?>">
                                <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 11pt;">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>
                                            <center>N°</center>
                                        </th>
                                        <th>
                                            <center>Folio de Reporte<center>
                                        </th>
                                        <th>
                                            <center>Tecnico Encargado<center>
                                        </th>
                                        <th>
                                            <center>Fecha de Reporte<center>
                                        </th>
                                        <th>
                                            <center>Visualizar<center>
                                        </th>
                                        <th>
                                            <center>Modificar<center>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>
                                            <center>N°</center>
                                        </th>
                                        <th>
                                            <center>Folio de Reporte<center>
                                        </th>
                                        <th>
                                            <center>Tecnico Encargado<center>
                                        </th>
                                        <th>
                                            <center>Fecha de Reporte<center>
                                        </th>
                                        <th>
                                            <center>Visualizar<center>
                                        </th>
                                        <th>
                                            <center>Modificar<center>
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
<!-- Page specific script -->

<?php
$notificacionesSweet = [
    'reporteConFoto' => ['Reporte Con Fotografias Creado con Exito.', 'success'],
    'reporteSinFoto' => ['Reporte <b>SIN</b> Fotografias Creado con Exito.', 'success'],
    'errorCargaFoto' => ['Reporte Creado, pero hubo error al subir Imagenes. Reintente.', 'error'],
    'formatoIncorrectoFoto' => ['Reporte Creado, pero las Imagenes poseen un Formato No Permitido. Reintente.', 'error'],
    'errorGenerar' => ['Error al Guardar Reporte. Reintente','error'],
    'errorModificar' => ['Error al Modificar Reporte. Reintente.','error'],
];

include_once "../sdo-includes/sweetalert_dark_mode.php";
?>
<script>
    $(document).ready(function () {
        var example2 = $('#example2').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
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
                "url": "sdo-includes/reportes_cargarInforme.php",
                "type": "POST",
                "dataSrc": "",
                "data": function(d) {
                    d.nomUsuarioModificar = $('#nomUsuarioModificar').val(); // Pasa el valor seleccionado del select como parámetro

                    console.log("Enviando datos al servidor:", d);
                    return d;
                },
                "complete": function(jqXHR, textStatus) {
                    console.log("Datos recibidos del servidor:", jqXHR.responseJSON);
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "folioReporte"},
                {"data": "nomEncargadoTurno"},
                {"data": "fechaReporteFinal"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.idReporte > 0) {
                            return '<button class="btn btn-primary btn-block btn-visualizar" data-id="' + row.idReporte + '"><i class="fas fa-eye"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.aplicaModificar === 'si') {
                            return '<button class="btn btn-warning btn-block btn-modificar" data-id="' + row.idReporte + '"><i class="fas fa-pen"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                }



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });

        $('#example2').on('click', '.btn-visualizar', function () {
            var id = $(this).data('id');
            let ipasig = "<?php echo $_SERVER['REMOTE_ADDR']; ?>";
            let ruta = '';

            if (ipasig === '10.69.250.151') {
                ruta = '../sdo-pdf/SDO-TurnoTrabajado.php';
            } else {
                ruta = '../sdo-pdf/SDO-TurnoTrabajado.php';
            }

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

        $('#example2').on('click', '.btn-modificar', function () {
            var id = $(this).data('id');

            var form = $('<form>', {
                'action': 'reportes-modificar.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'idReporte',
                'value': id
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
    });
</script>

</body>

</html>

