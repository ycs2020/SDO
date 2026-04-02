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
    <title>Admin Materiales a Solicitar</title>

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

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .img-zoom-hover {
            transition: all 0.3s ease-in-out;
            cursor: zoom-in;
            border: 2px solid transparent;
        }

        .img-zoom-hover:hover {
            transform: scale(2.5);
            z-index: 9999;
            border-color: #007bff;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.5);
        }
    </style>
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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Listado de Materiales</a>
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
                        <!-- Custom Tabs -->
                        <div class="card">

                            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                                <h3 class="card-title p-3"><b>Men&uacute;</b></h3>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active " href="#vistaMateriales" data-toggle="tab"> <b>Materiales</b> </a></li>
                                    <li class="nav-item"><a class="nav-link" href="#vistaSolicitudes" data-toggle="tab"> <b>Solicitudes</b> </a></li>
                                </ul>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="vistaMateriales">

                                        <h5 class="text-center font-weight-bold">Listado de Materiales</h5>

                                        <br>

                                        <div class="row text-center" style="display: flex; justify-content:center;">
                                            <div class="col-lg-2 col-12">
                                                <button type="button" id="btnAgregarMateriales" class="btn btn-success btn-block">
                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Materiales
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Modal Agregar Material -->
                                        <div class="modal fade" id="modalAgregarMaterial" tabindex="-1" role="dialog" aria-labelledby="modalLabelMaterial" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <form id="formAgregarMaterial" enctype="multipart/form-data">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title"><i class="fa fa-plus"></i> Agregar Material</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="nomProducto">Nombre del producto</label>
                                                                    <input type="text" name="nomProducto" id="nomProducto" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="unidadProducto">Unidad de medida</label>
                                                                    <input type="text" name="unidadProducto" id="unidadProducto" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="cantidadProducto">Cantidad</label>
                                                                    <input type="number" name="cantidadProducto" id="cantidadProducto" class="form-control" min="1" value="1" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="valorProducto">Valor estimado ($)</label>
                                                                    <input type="number" name="valorProducto" id="valorProducto" class="form-control" min="0" value="0">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="imagenProducto">Imagen del producto (opcional)</label>
                                                                    <input type="file" name="imagenProducto" id="imagenProducto" class="form-control-file" accept="image/*">
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="enlaceProducto">Enlace archivo adjunto (opcional)</label>
                                                                    <input type="text" name="enlaceProducto" id="enlaceProducto" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <hr>


                                        <div class="row">
                                            <div class="col-12">

                                                <div class="table-responsive">

                                                    <table id="tablaListMateriales" class="table table-bordered text-center table-sm">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                N°
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Nombre
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Medida
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Cantidad
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Valor
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Imagen
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Enlace
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                N°
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Nombre
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Medida
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Cantidad
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Valor
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Imagen
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Enlace
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <script>
                                                        $(document).ready(function () {
                                                            var tablaListMateriales = $('#tablaListMateriales').DataTable({
                                                                "processing": true,
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
                                                                    "url": "/sdo-includes/materiales_listado_datos.php", // Archivo PHP que contiene la consulta SQL
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
                                                                    {"data": "nombre2"},
                                                                    {"data": "unidad2"},
                                                                    { "data": "cantidad" },
                                                                    { "data": "valor2" },
                                                                    { "data": "imagenHTML" },
                                                                    { "data": "buttonEnlace" },




                                                                    // Agrega más columnas según sea necesario
                                                                ],
                                                                "createdRow": function (row, data, dataIndex) {
                                                                    $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                    $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="vistaSolicitudes">

                                        <h5 class="text-center font-weight-bold">Listado de Solicitudes</h5>

                                        <hr>
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
<script src="js/dark-mode.js"></script>
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
    $(document).ready(function() {
        // Abrir modal
        $('#btnAgregarMateriales').click(function () {
            $('#modalAgregarMaterial').modal('show');
            $('#formAgregarMaterial')[0].reset();
        });


        // Enviar formulario con AJAX
        $('#formAgregarMaterial').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/sdo-funciones/material_listado_agregar.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    try {
                        let json = JSON.parse(resp);
                        if (json.success) {
                            Swal.fire('Éxito', json.message, 'success');
                            $('#modalAgregarMaterial').modal('hide');

                            $('#tablaListMateriales').DataTable().ajax.reload(null, false);
                            // Aquí podrías recargar una tabla si la tienes
                        } else {
                            Swal.fire('Error', json.message, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'Error en el servidor: ' + resp, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo conectar al servidor.', 'error');
                }
            });
        });
    });
</script>

</body>

</html>