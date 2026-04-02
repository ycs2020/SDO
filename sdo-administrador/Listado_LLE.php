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
    <title>Listado de Hojas Técnicas con LLE</title>
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
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h3>Listado de Hojas Técnicas con LLE</h3>
            </div>
            <!-- /.container-fluid -->
        </div>
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
                                        <th>Fecha</th>
                                        <th>Folio HT</th>
                                        <th>Tipo de Mantencion</th>
                                        <th>Nombre Equipo</th>
                                        <th>Recinto</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio HT</th>
                                        <th>Tipo de Mantencion</th>
                                        <th>Nombre Equipo</th>
                                        <th>Recinto</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    $(document).ready(function () {
                                        var miTabla = $('#miTabla').DataTable({
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
                                                "url": "ajax-ht-lle.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "dataSrc": ""
                                            },
                                            "columns": [
                                                {"data": "fechaOrden"},
                                                {"data": "folioOrden"},
                                                {"data": "tipoMantencion"},
                                                {"data": "equipo"},
                                                {"data": "recinto"},
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
