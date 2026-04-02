<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Informe EDAN</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admtec-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listar Informe EDAN</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Termino Programada</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Modificar</center>
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Inicio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Termino Programada</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Modificar</center>
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
    $(document).ready(function () {
        var example2 = $('#example2').DataTable({
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
                "url": "sdo-includes/edan_cargarListado.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    // d.idDepartamento = $('#idDeptoHT').val(); // Pasa el valor seleccionado del select como parámetro
                    console.log(d);

                },
                "dataSrc": function (json) {
                    console.log("Response from server:", json);
                    return json;
                },
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {"data": "fecha2"},
                {"data": "fechaTermino2"},
                {"data": "folio"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id < 1) {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-primary btn-visualizar" data-id="' + row.id + '"><i class="fas fa-eye"></i></button>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.correo_enviado !== 0) {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-warning btn-modificar" data-id="' + row.id + '"><i class="fas fa-pen"></i></button>';
                        }
                    }
                }



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
            }
        });

        $('#example2').on('click', '.btn-visualizar', function () {
            var id = $(this).data('id');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': '../sdo-pdf/SDO-EDAN.php', // Reemplaza 'tu_url_destino' con la URL de destino
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


            // Realizar acciones adicionales si el usuario confirma
            // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
            // confirmarAccion(id);
        });
    });
</script>
<script src="js/dark-mode.js"></script>
</body>
</html>
