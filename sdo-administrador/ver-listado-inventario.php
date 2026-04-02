<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];


if(isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Observaciones de los Equipos</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Inventario de la Categoria </h4>
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
                            <div class="card-header">
                                <a href="equipamiento_hbqp.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <form action="agregar-equipo-inventario.php" method="POST" style="float: right;">
                                    <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta?>">
                                    <button type="submit" class="btn btn-info"><i class="fas fa-plus"></i>  Agregar un equipo al inventario</button>
                                </form>
                                <br>
                                <br>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm" id="tabla-equipo-falla">
                                    <thead  class="bg-info">
                                    <tr>
                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                        <th scope="col" class="text-center">Marca</th>
                                        <th scope="col" class="text-center">Modelo</th>
                                        <th scope="col" class="text-center">Cantidad</th>
                                        <th scope="col" class="text-center">Fecha de Ingreso</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sqlObservaciones = "SELECT * FROM inventario_equipos WHERE categoriaInventario = $idCarpeta ORDER BY fechaInventario DESC";
                                    $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                    while($rowObservaciones = $resObservaciones->fetch_assoc()){
                                        $datos = [];
                                        $datos['idInventario'] = $rowObservaciones['idInventario'];
                                        $idInventario = $datos['idInventario'];
                                        $datos['equipoInventario'] = $rowObservaciones['equipoInventario'];
                                        $equipoInventario = $datos['equipoInventario'];
                                        $datos['marcaInvetario'] = $rowObservaciones['marcaInventario'];
                                        $marcaInventario = $datos['marcaInventario'];
                                        $datos['modeloInventario'] = $rowObservaciones['modeloInventario'];
                                        $modeloInventario = $datos['modeloInventario'];
                                        $datos['cantidadInventario'] = $rowObservaciones['cantidadInventario'];
                                        $cantidadInventario = $datos['cantidadInventario'];
                                        $datos['fechaInventario'] = $rowObservaciones['fechaInventario'];
                                        $fechaInventario = $datos['fechaInventario'];

                                        $fechaInventario = date('d/m/Y', strtotime($fechaInventario));

                                        ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $equipoInventario;
                                                ?>
                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $marcaInventario;
                                                ?>
                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $modeloInventario;
                                                ?>
                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $cantidadInventario;
                                                ?>
                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <?php
                                                echo $fechaInventario;
                                                ?>
                                            </td>
                                            <td class="text-center" style="font-size: 14px">
                                                <form action="editar-cantidad-inventario.php" method="POST">
                                                    <button type="submit" data-toggle="modal" data-target="#exampleModal" title="Editar" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></button>
                                                    <input type="hidden" name="idInventario" id="idInventario" value="<?php echo $idInventario?>">
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot class="bg-info">
                                    <th scope="col" class="text-center">Nombre del Equipo</th>
                                    <th scope="col" class="text-center">Marca</th>
                                    <th scope="col" class="text-center">Modelo</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Fecha de Ingreso</th>
                                    <th scope="col" class="text-center">Acciones</th>
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
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#tabla-equipo-falla').DataTable({
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
<script src="js/dark-mode.js"></script>
</body>

</html>
