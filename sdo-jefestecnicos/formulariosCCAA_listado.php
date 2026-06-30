<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$nomUsuario = $_SESSION['nomUsuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formularios CCAA - Recibidos</title>

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
                                <a style="pointer-events: none;" class="btn font-weight-bold">Solicitudes de Acceso Recibidas</a>
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
                            <div class="card-body" style="text-align:center;">
                                <div class="row justify-content-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">

                                        <form method="post" action="../sdo-pdf/SDO-Formularios-CCAA-Accesos.php" target="_blank">

                                            <button type="submit" id="inputMozi" class="btn btn-primary btn-block">
                                                <i class="fa fa-download"></i>&nbsp;&nbsp;Listado de Accesos en Solicitudes
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12">
                        <!-- Custom Tabs -->
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <button class="btn btn-block bg-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i> Buscar Funcionario</button>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="exampleModalLabel">Buscar Funcionarios en los Formatos de Accesos</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table id="tablaFuncionarios" class="table table-striped table-bordered" style="width:100%">                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th>Folio</th>
                                                        <th>Funcionario</th>
                                                        <th>Documento Recibido</th>
                                                        <th>Visualizar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th>Folio</th>
                                                        <th>Funcionario</th>
                                                        <th>Documento Recibido</th>
                                                        <th>Visualizar</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table id="tablaSolicitudesRecibidas" class="table table-bordered table-striped table-sm text-center" style="font-size: 11pt;">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>
                                            Folio
                                        </th>
                                        <th>
                                            Solicitante
                                        </th>
                                        <th>
                                            Unidad
                                        </th>
                                        <th>
                                            Fecha
                                        </th>
                                        <th>
                                            Categoria
                                        </th>
                                        <th>
                                            Visualizar
                                        </th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>
                                            Folio
                                        </th>
                                        <th>
                                            Solicitante
                                        </th>
                                        <th>
                                            Unidad
                                        </th>
                                        <th>
                                            Fecha
                                        </th>
                                        <th>
                                            Categoria
                                        </th>
                                        <th>
                                            Visualizar
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <style>
                                    #tablaSolicitudesRecibidas td,
                                    #tablaSolicitudesRecibidas th {
                                        vertical-align: middle !important;
                                    }
                                </style>
                                <script>
                                    $(document).ready(function () {
                                        $('#tablaSolicitudesRecibidas').DataTable({
                                            "processing": true,
                                            "serverSide": true,
                                            "ajax": {
                                                "url": "/sdo-includes/formularios_ccaa_recibidos_cargar.php",
                                                "type": "POST"
                                            },
                                            "columns": [
                                                {"data": "folioFormRegAcceso"},
                                                {"data": "solicitanteFormRegAcceso"},
                                                {
                                                    "data": "unidades",
                                                    "render": function (data) {
                                                        return data.replace(/\n/g, "<br>");
                                                    }
                                                },
                                                {"data": "fechaFormRegAcceso2"},
                                                {"data": "categoriaFormRegAcceso"},
                                                {
                                                    "data": "botonVisualizar"
                                                }
                                            ],
                                            "createdRow": function (row, data, dataIndex) {
                                                if (parseInt(data.existeDocumento) > 0) {
                                                    $(row).addClass('bg-primary text-white'); // pinta toda la fila de azul con texto blanco
                                                }
                                            },
                                            "responsive": true,
                                            "lengthChange": false,
                                            "ordering": false,
                                            "searching": true,
                                            "language": {
                                                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                                                "zeroRecords": "No se han encontrado registros.",
                                                "info": "Mostrando página _PAGE_ de _PAGES_",
                                                "infoEmpty": "No existen registros.",
                                                "infoFiltered": "(filtrado desde _MAX_ registros totales)",
                                                "search": "Buscar:",
                                                "processing": "Procesando...",
                                                "paginate": {
                                                    "previous": "Anterior",
                                                    "next": "Siguiente"
                                                }
                                            }
                                        });
                                    });

                                </script>
                            </div>
                        </div>
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
<!-- Page specific script -->
<script>
    $(document).ready(function () {
        $('#tablaFuncionarios').DataTable({
            "ajax": {
                "url": "/sdo-includes/formularios_ccaa_recibidos_cargar_funcionarios.php",
                "type": "POST"
            },
            "columns": [
                {"data": "folioFormRegAcceso"},
                {"data": "funcionarios"},
                {"data": "estadoDocumento"},
                {"data": "botonVisualizar"},
            ],
            "responsive": true,
            "lengthChange": false,
            "ordering": false,
            "searching": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen registros.",
                "infoFiltered": "(filtrado desde _MAX_ registros totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

</body>

</html><?php

