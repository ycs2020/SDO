<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Control de Acceso</title>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Logo Control Centralizado">
    <br>
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only"></span>
    </div>
</div>
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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Control de Acceso</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>

                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <a href="../sdo-pdf/SDO-Estado-Funcionarios.php" target="_blank" class="btn btn-block bg-gradient-primary btn-md"><i class="fa-solid fa-eye"></i>&nbsp;&nbsp; <b>Ver Estado de Personas</b></a>


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
                                <div class="table-responsive">
                                    <table id="miTabla" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Nombre
                                                    <center>
                                            </th>
                                            <th>
                                                <center>RUT
                                                    <center>
                                            </th>
                                            <th>
                                                <center>N° Tarjeta
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Departamento
                                                    <center>
                                            </th>
                                            <th>
                                                <center>¿Existe en Intercom?
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Calidad Juridica
                                                    <center>
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
                                                <center>Nombre
                                                    <center>
                                            </th>
                                            <th>
                                                <center>RUT
                                                    <center>
                                            </th>
                                            <th>
                                                <center>N° Tarjeta
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Departamento
                                                    <center>
                                            </th>
                                            <th>
                                                <center>¿Existe en Intercom?
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Calidad Juridica
                                                    <center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                    <script>
                                        $(document).ready(function () {
                                            // Inicializa la tabla DataTable
                                            var miTabla = $('#miTabla').DataTable({
                                                "lengthChange": false,
                                                "searching": true,
                                                "autoWidth": false,
                                                "responsive": true,
                                                "ordering": false,
                                                "processing": true,
                                                "language": {
                                                    "lengthMenu": "Visualizando _MENU_ registros por página",
                                                    "zeroRecords": "No se han encontrado registros.",
                                                    "info": "Mostrando página _PAGE_ de _PAGES_",
                                                    "infoEmpty": "No existen registros.",
                                                    "infoFiltered": "(Filtrado desde _MAX_ registros totales)",
                                                    "search": "Buscar:",
                                                    "processing": "<span class='fa-stack fa-lg'>\n\ <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\ </span>&emsp;Procesando ...",
                                                    "paginate": {
                                                        "previous": "Anterior",
                                                        "next": "Siguiente"
                                                    }
                                                },
                                                "ajax": {
                                                    "url": "ajax-control-acceso.php",
                                                    "type": "POST",
                                                    "dataSrc": ""
                                                },
                                                "columns": [
                                                    {
                                                        "data": null,
                                                        "render": function (data, type, row, meta) {
                                                            return meta.row + 1; // Número de fila
                                                        }
                                                    },
                                                    { "data": "OWNER_Name" },
                                                    { "data": "OWNER_CODE" },
                                                    { "data": "tarjetaAcceso" },
                                                    { "data": "nomDepartamento" },
                                                    { "data": "existeIntercom" },
                                                    { "data": "calidadJuridica" },
                                                ]
                                            });

                                        });
                                    </script>

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