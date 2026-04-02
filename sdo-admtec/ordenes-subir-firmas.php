<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$mostrarDatosEquipo = ((int)$_SESSION['idDepartamento'] === 6);

?>


<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Recepciones de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4><b>Listado de Hojas Técnicas</b></h4>
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
                            <div class="card-header">

                                <?php

                                if (isset($_GET['noEditId'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;No fue posible recuperar el ID del registro para editar.</h5></div>';
                                }

                                if (isset($_GET['ExitoActualizar'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al actualizar la hoja tecnica</h5></div>';
                                }

                                if (isset($_GET['exitoEliminarHoja'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al eliminar hoja tecnica</h5></div>';
                                }

                                if (isset($_GET['exitoGenerar'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito la Hoja Técnica</h5></div>';
                                }

                                if (isset($_GET['exitoUpload'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al subir hoja técnica firmada</h5></div>';
                                }

                                if (isset($_GET['errorUpload'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al subir hoja técnica firmada</h5></div>';
                                }


                                if(isset($_GET['errorSeleccionarOrden'])){
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Seleccionar HT. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }

                                ?>
                                <br>
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <?php if ($mostrarDatosEquipo) { ?>
                                    <div class="row mb-3">
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="filtroNombreEquipo"><b>Buscar por Nombre Equipo:</b></label>
                                            <input type="text" id="filtroNombreEquipo" class="form-control" placeholder="Ingrese nombre del equipo">
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label for="filtroNumeroSerie"><b>Buscar por N° Serie:</b></label>
                                            <input type="text" id="filtroNumeroSerie" class="form-control" placeholder="Ingrese número de serie">
                                        </div>
                                    </div>
                                    <hr>
                                <?php } ?>

                                <table id="miTabla" style="font-size:11pt; text-align: center;" class="table table-bordered table-hover">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th>Folio</th>
                                        <th>Tec. Recepcionador</th>
                                        <th>Solicitante</th>

                                        <?php if ($mostrarDatosEquipo) { ?>
                                            <th>Nombre Equipo</th>
                                            <th>N° Serie</th>
                                        <?php } ?>

                                        <th>Fecha de Recepci&oacute;n</th>
                                        <th>Visualizar</th>
                                    </tr>
                                    </thead>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th>Folio</th>
                                        <th>Tec. Recepcionador</th>
                                        <th>Solicitante</th>

                                        <?php if ($mostrarDatosEquipo) { ?>
                                            <th>Nombre Equipo</th>
                                            <th>N° Serie</th>
                                        <?php } ?>

                                        <th>Fecha de Recepci&oacute;n</th>
                                        <th>Visualizar</th>
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
        let idDepartamento = <?php echo (int)$_SESSION['idDepartamento']; ?>;
        let mostrarDatosEquipo = (idDepartamento === 6);

        var columnas = [
            {"data": "folioOrden"},
            {"data": "nomTecnicoRecepcionador"},
            {"data": "nomSolicitante"}
        ];

        if (mostrarDatosEquipo) {
            columnas.push(
                {"data": "nombreEquipo"},
                {"data": "numeroSerie"}
            );
        }

        columnas.push(
            {"data": "fechaOrden"},
            {"data": "btnVisualizar"}
        );

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
                "search": "Buscar general:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "search": {
                "regex": false,
                "smart": true
            },
            "ajax": {
                "url": "ajax-ht-listado.php",
                "type": "POST",
                "data": function (d) {
                    d.idDepartamento = idDepartamento;
                },
                "dataSrc": ""
            },
            "columns": columnas,
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle');
                $('td', row).css('text-align', 'center');
            }
        });

        if (mostrarDatosEquipo) {
            $('#filtroNombreEquipo').on('keyup change', function () {
                miTabla.column(3).search(this.value).draw();
            });

            $('#filtroNumeroSerie').on('keyup change', function () {
                miTabla.column(4).search(this.value).draw();
            });
        }
    });

    $(document).on('click', '.firmarDocumento', function () {
        var idOrdenMod = $(this).attr('id');
        $('#modalVisado #idOrden').val(idOrdenMod);
        $('#modalVisado').modal('show');
    });
</script>
<script src="js/dark-mode.js"></script>


</body>

</html>