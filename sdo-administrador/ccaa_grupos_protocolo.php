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
    <title>Grupos Protocolo CCAA</title>

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
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">


<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
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
                                <a style="pointer-events: none;" class="btn font-weight-bold">Grupos Protocolo CCAA</a>
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
                                    <li class="nav-item"><a class="nav-link active " href="#tabGruposProt" data-toggle="tab"> <b>Grupos Protocolo CCAA</b> </a></li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tabGruposProt">

                                        <h5 class="text-center font-weight-bold">Grupos Protocolo CCAA</h5>

                                        <br>

                                        <div class="row text-center" style="display: flex; justify-content:center;">
                                            <div class="col-lg-2 col-12">
                                                <button type="button" id="btnAgregarGrupo" class="btn btn-success btn-block">
                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Grupo
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Modal Agregar Grupo -->
                                        <div class="modal fade" id="modalAgregarGrupo" tabindex="-1" role="dialog" aria-labelledby="modalAgregarGrupoLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form id="formAgregarGrupo">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="modalAgregarGrupoLabel">Agregar Nuevo Grupo</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="nomGrupo">Nombre del Grupo</label>
                                                                <input type="text" class="form-control" name="nomGrupo" id="nomGrupo" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="descripGrupo">Descripción</label>
                                                                <textarea class="form-control" name="descripGrupo" id="descripGrupo" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Guardar</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>



                                        <hr>


                                        <div class="row">
                                            <div class="col-12">

                                                <div class="table-responsive">

                                                    <table id="tablaGruposProtoc" class="table table-bordered text-center table-sm">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                N°
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Nombre
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Descripcion
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Visualizar
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
                                                                Descripcion
                                                            </th>
                                                            <th style="vertical-align:middle; text-align:center;">
                                                                Visualizar
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <script>
                                                        $(document).ready(function () {
                                                            var tablaGruposProtoc = $('#tablaGruposProtoc').DataTable({
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
                                                                    "url": "/sdo-includes/ccaa_grupos_protocolo_obtener.php", // Archivo PHP que contiene la consulta SQL
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
                                                                    {"data": "nomGrupo"},
                                                                    {"data": "descripGrupo"},
                                                                    { "data": "botonGrupo" },




                                                                    // Agrega más columnas según sea necesario
                                                                ],
                                                                "columnDefs": [
                                                                    { "width": "30%", "targets": 1 }, // Columna "Nombre" (índice 1)
                                                                    { "width": "40%", "targets": 2 }  // Columna "Descripción" (índice 2) si también deseas ajustarla
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
    $('#btnAgregarGrupo').on('click', function () {
        $('#formAgregarGrupo')[0].reset();
        $('#modalAgregarGrupo').modal('show');
    });

    $('#formAgregarGrupo').on('submit', function (e) {
        e.preventDefault();
        var datos = $(this).serialize();
        $.ajax({
            url: '/sdo-includes/ccaa_grupos_protocolo_insertar.php',
            type: 'POST',
            data: datos,
            success: function (response) {
                if (response.trim() === 'OK') {
                    $('#modalAgregarGrupo').modal('hide');
                    toastr.success('Grupo agregado correctamente');
                    $('#tablaGruposProtoc').DataTable().ajax.reload(null, false);
                } else if (response.includes("ya existe")) {
                    toastr.warning(response); // muestra "El grupo ya existe"
                } else {
                    toastr.error('Error: ' + response);
                }
            },
            error: function () {
                toastr.error('No se pudo conectar con el servidor.');
            }
        });
    });

</script>
</body>

</html>