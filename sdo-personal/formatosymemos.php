<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formatos y Memos Revisados</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Formatos y Memos Revisados</h4>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <button type="button" style="float: right" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fas fa-plus"></i> Agregar Numeros
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Numeros</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/agregar-numeros-memos-forma.php" method="post">
                                                <div class="modal-body">
                                                    <label>Cantidad de Memos:</label>
                                                    <input type="number" name="memos" id="memos" class="form-control">
                                                    <br>
                                                    <label>Cantidad de Formatos:</label>
                                                    <input type="number" name="formatos" id="memos" class="form-control">
                                                    <hr>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead class="bg-secondary" style="text-align: center">
                                        <tr>
                                            <th>N°</th>
                                            <th>Fecha</th>
                                            <th>Cantidad de Memos</th>
                                            <th>Cantidad de Formatos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center">
                                        <?php
                                            $sqlBuscar = "SELECT * FROM `memos_formatos` ORDER BY `id` DESC";
                                            $resBuscar = mysqli_query($conexion,$sqlBuscar);
                                            $contar = 1;
                                            while($rowBuscar = $resBuscar->fetch_assoc()){
                                                $id = $rowBuscar['id'];
                                                $cantidadMemo = $rowBuscar['cantidadMemo'];
                                                $cantidadForma = $rowBuscar['cantidadFormato'];
                                                $fecha = $rowBuscar['fecha'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $contar?></td>
                                                    <td><?php echo $fecha?></td>
                                                    <td><?php echo $cantidadMemo?></td>
                                                    <td><?php echo $cantidadForma?></td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#modificar<?php echo $id?>" type="button" class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modificar<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="modificar<?php echo $id?>" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Numeros</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar-numeros.php" method="post">
                                                                        <div class="modal-body">
                                                                            <label>Cantidad de Memos:</label>
                                                                            <input type="number" value="<?php echo $cantidadMemo?>" name="memos" id="memos" class="form-control">
                                                                            <br>
                                                                            <label>Cantidad de Formatos:</label>
                                                                            <input type="number" value="<?php echo $cantidadForma?>" name="formatos" id="formatos" class="form-control">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $contar++;

                                            }
                                        ?>
                                    </tbody>
                                    <tfoot class="bg-secondary" style="text-align: center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Fecha</th>
                                        <th>Cantidad de Memos</th>
                                        <th>Cantidad de Formatos</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- ./wrapper -->
<script src="js/dark-mode.js"></script>
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>

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
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoUsuarios').select2({
            theme: 'bootstrap4'
        })

    });
</script>
</body>
</html>
