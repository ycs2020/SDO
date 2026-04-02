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
    <title>Listado de Actas</title>

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
                                <a style="pointer-events: none;" class="btn font-weight-bold">Sistema de Actas</a>
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

                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-3 col-md-4 col-12">

                                        <form method="post" action="actas-formulario.php">
                                            <button type="submit" id="inputMozi" class="btn btn-success btn-block"><i class="fa fa-plus"></i>&nbsp;&nbsp;Crear Acta de Activaci&oacute;n</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="table-responsive">

                                    <table id="miTabla" class="table table-bordered text-center table-striped table-sm">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle; text-align: center;">Funcionario</th>
                                            <th style="vertical-align:middle; text-align: center;">RUT</th>
                                            <th style="vertical-align:middle; text-align: center;">C&oacute;digo Tarjeta</th>
                                            <th style="vertical-align:middle; text-align: center;">Unidad</th>
                                            <th style="vertical-align:middle; text-align: center;">Visualizar</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle; text-align: center;">Funcionario</th>
                                            <th style="vertical-align:middle; text-align: center;">RUT</th>
                                            <th style="vertical-align:middle; text-align: center;">C&oacute;digo Tarjeta</th>
                                            <th style="vertical-align:middle; text-align: center;">Unidad</th>
                                            <th style="vertical-align:middle; text-align: center;">Visualizar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <script>
                                        $(document).ready(function () {
                                            var tabla = $('#miTabla').DataTable({
                                                lengthChange: false,
                                                searching: true,
                                                autoWidth: false,
                                                responsive: true,
                                                ordering: false,
                                                language: {
                                                    lengthMenu: "Visualizando _MENU_ Registros por Página",
                                                    zeroRecords: "No se han encontrado Registros.",
                                                    info: "Mostrando página _PAGE_ de _PAGES_",
                                                    infoEmpty: "No existen Registros.",
                                                    infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                                                    search: "Buscar:",
                                                    processing: "Procesando...",
                                                    paginate: {
                                                        previous: "Anterior",
                                                        next: "Siguiente"
                                                    }
                                                },
                                                ajax: {
                                                    url: "/sdo-includes/actas_cargar_listado.php",
                                                    type: "POST",
                                                    dataSrc: ""
                                                },
                                                columns: [
                                                    { data: "nomTarjeta" },
                                                    { data: "rutTarjeta" },
                                                    { data: "codigoTarjeta" },
                                                    { data: "unidadTarjeta" },
                                                    {
                                                        data: null,
                                                        render: function (data, type, row) {
                                                            if (row.idActa < 1) {
                                                                return '<button type="button" class="btn btn-secondary" disabled>Deshabilitado</button>';
                                                            } else {
                                                                return `
                                                                <button type="button" class="btn btn-primary btn-visualizar"
                                                                    data-id="${row.idActa}"
                                                                    data-rut="${row.rutTarjeta}">
                                                                    <i class="fas fa-eye"></i> Visualizar
                                                                </button>`;
                                                            }
                                                        }
                                                    }
                                                ],
                                                createdRow: function (row, data, dataIndex) {
                                                    $('td', row).css('vertical-align', 'middle');
                                                }
                                            });

                                            // Evento para capturar click en botón de Visualizar
                                            $('#miTabla tbody').on('click', '.btn-visualizar', function () {
                                                const idActa = $(this).data('id');
                                                const rutActa2 = $(this).data('rut');

                                                // Crear y enviar formulario dinámico con POST
                                                const form = $('<form>', {
                                                    method: 'POST',
                                                    action: '/sdo-administrador/actas-funcionario.php'
                                                });

                                                form.append($('<input>', {
                                                    type: 'hidden',
                                                    name: 'idActa',
                                                    value: idActa
                                                }));

                                                form.append($('<input>', {
                                                    type: 'hidden',
                                                    name: 'rutActa2',
                                                    value: rutActa2
                                                }));

                                                // Agregar al body y enviar
                                                $('body').append(form);
                                                form.submit();
                                            });
                                        });
                                    </script>

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
</body>

</html>