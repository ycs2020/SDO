<?php

include '../sdo-funciones/conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



$idHerramienta = $_GET['idHerramienta'];

$query = "SELECT * FROM historial_estado_herramienta WHERE idHerramienta = ${idHerramienta} ORDER BY fecha DESC";
$res = mysqli_query($conexion, $query);


//TRAE LOS DATOS DE LA HERRAMIENTA EN CUESTION MEDIANTE EL FILTRO DE SU ID

$sqlHerramienta = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
$resHerramienta = mysqli_query($conexion, $sqlHerramienta);

while ($rowHerramienta = $resHerramienta->fetch_assoc()) {

    $datos = [];

    //TRAE LOS DATOS DEL RESULTADO SQL Y LOS GUARDA EN UN ARRAY
    $datos['imagen'] = $rowHerramienta['imagen'];
    $datos['codHerramienta'] = $rowHerramienta['codHerramienta'];
    $datos['codInventario'] = $rowHerramienta['codInventario'];
    $datos['nomHerramienta'] = $rowHerramienta['nomHerramienta'];
    $datos['cantHerramienta'] = $rowHerramienta['cantHerramienta'];
    $datos['fechaIngreso'] = $rowHerramienta['fechaIngreso'];
    $datos['marcaHerramienta'] = $rowHerramienta['marcaHerramienta'];
    $datos['modeloHerramienta'] = $rowHerramienta['modeloHerramienta'];


    //ASIGNA LOS DATOS DEL ARRAY EN VARIABLES
    $imagen = $datos['imagen'];
    $codHerramienta = $datos['codHerramienta'];
    $codInventario = $datos['codInventario'];
    $nomHerramienta = $datos['nomHerramienta'];
    $cantHerramienta = $datos['cantHerramienta'];
    $fechaIngreso = $datos['fechaIngreso'];
    $marcaHerramienta = $datos['marcaHerramienta'];
    $modeloHerramienta = $datos['modeloHerramienta'];
}



// var_dump($idHerramienta);
// exit;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Historial</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
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

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed" style="font-weight: bold;">

    <div class="preloader  flex-column justify-content-center align-items-center">

        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>

    </div>

    <div class="wrapper">
        <!-- Navbar -->
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Historial Ingreso de Herramientas</h4>
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
                                    <a href="historial-herramientas.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <!-- <a href="ingreso-herramientas.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Nueva Herramienta</a> -->


                                </div>
                                <div class="card-body">

                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Información Actual De La Herramienta</legend>

                                        <div class="row mb-2">

                                            <div class="col-sm-6">

                                                <h4 style="font-weight: bold;">Nombre de la herramienta: <span class="text-info"><?php echo $nomHerramienta ?></span> </h4>
                                                <label style="font-weight: bold;">Código Barra: <span class="text-info"><?php echo $codHerramienta ?></span></label>
                                                <br>
                                                <label style="font-weight: bold;">Código de Inventario: <span class="text-info" style="text-transform: uppercase;"><?php echo $codInventario ?></span></label>
                                                <br>

                                                <?php

                                                if ($cantHerramienta == 1) {
                                                    $estado = "Disponible";
                                                } else if ($cantHerramienta == -1) {
                                                    $estado = "Entrega Definitiva";
                                                } else {
                                                    $estado = "En Arriendo";
                                                }


                                                if ($estado == "Disponible") {
                                                    $color = "style=' color:  #58d68d ;font-weight:bold;vertical-align:middle;text-transform: uppercase'";
                                                } else if ($estado == 'Entrega Definitiva') {
                                                    $color = "style=' color:  #f5b70f ;font-weight:bold;vertical-align:middle;text-transform: uppercase'";
                                                } else {
                                                    $color = "style=' color: red;font-weight:bold;vertical-align:middle;text-transform: uppercase'";
                                                }

                                                ?>

                                                <label>Estado: <span <?php echo $color ?>> <?php echo $estado  ?> </span> </label>
                                                <br>
                                                <label style="font-weight: bold;">Fecha de Ingreso: <span class="text-info"><?php echo $fechaIngreso ?></span></label>
                                                <br>
                                                <label style="font-weight: bold;">Marca: <span class="text-info"><?php echo $marcaHerramienta ?></label>
                                                <br>
                                                <label style="font-weight: bold;">Modelo: <span class="text-info"><?php echo $modeloHerramienta ?></label>

                                            </div>

                                            <div class="col-sm-6">


                                                <img class="rounded " width="300px" height="300px" src="../sdo-bodega-panol/img3/<?php echo $imagen ?>" alt="Imagen herramienta">

                                            </div>


                                        </div>


                                    </fieldset>
                                    <br>
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover " style="font-weight: bold;">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Tipo Actualización<center>
                                                        </th>




                                                        <th style="vertical-align:middle;">
                                                            <center>Mas Detalles<center>
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    while ($row = $res->fetch_assoc()) {

                                                        $idEstadoHerramienta = $row['idEstadoHerramienta'];
                                                        $tipoActualizacion = $row['tipoActualizacion'];

                                                        if ($tipoActualizacion == 'Ingreso') {

                                                            $color = "style='background-color:#49be25;font-weight: bold;text-transform:uppercase;' ";
                                                            $color1 = "style='color:#49be25;font-weight: bold;text-transform:uppercase;' ";
                                                        } else if ($tipoActualizacion == 'Modificacion') {

                                                            $color = "style='background-color:#D5f746;font-weight: bold;text-transform:uppercase;color: black;' ";
                                                            $color1 = "style='color:#D5f746;font-weight: bold;text-transform:uppercase;' ";
                                                        } else if ($tipoActualizacion == 'Entrega Definitiva') {

                                                            $color = "style='background-color:#f5b70f;font-weight: bold;text-transform:uppercase;color: black;' ";
                                                            $color1 = "style='color:#f5b70f;font-weight: bold;text-transform:uppercase;' ";
                                                        } else {

                                                            $color = "style='background-color:#46f7e7;font-weight: bold;text-transform:uppercase;color:black' ";
                                                            $color1 = "style='color:#46f7e7;font-weight: bold;text-transform:uppercase;' ";
                                                        }



                                                    ?>
                                                        <tr <?php echo $color ?>>

                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                <center>
                                                                    <?php

                                                                    $fecha = $row['fecha'];
                                                                    $newDate = date("d/m/Y H:i ", strtotime($fecha));
                                                                    echo $newDate;

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;">
                                                                <center>
                                                                    <?php
                                                                    echo $tipoActualizacion
                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <td style="vertical-align:middle; text-align:justify;">
                                                                <center>

                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" class="btn btn-lg btn-info" id="#idEstadoHerramienta<?php echo $idEstadoHerramienta ?>" data-toggle="modal" data-target="#idEstadoHerramienta<?php echo $idEstadoHerramienta ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="idEstadoHerramienta<?php echo $idEstadoHerramienta ?>" name="idEstadoHerramienta<?php echo $idEstadoHerramienta ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" role="document">
                                                                            <div class="modal-content ">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel" style="color: white;font-weight:bold">Detalles de la Actualización</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <?php

                                                                                    $sqldetalles = "SELECT detalles, detalles1 FROM historial_estado_herramienta WHERE idEstadoHerramienta = ${idEstadoHerramienta}";
                                                                                    $resDetalles = mysqli_query($conexion, $sqldetalles);

                                                                                    while ($rowDetalles = $resDetalles->fetch_assoc()) {

                                                                                        $descript = $rowDetalles['detalles'];
                                                                                        $descriptNuevo = $rowDetalles['detalles1'];
                                                                                    }


                                                                                    ?>

                                                                                    <div style="text-align:left;" class="form-group">

                                                                                        <?php




                                                                                        ?>


                                                                                        <textarea type="text" readonly class="form-control" rows="10" <?php echo $color1 ?>>
                                                                                                <?php echo $descript ?>
                                                                                                <?php echo $descriptNuevo ?>
                                                                                            </textarea>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Ya he leído esto!</button>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </center>
                                                            </td>


                                                        </tr>
                                                    <?php


                                                    }


                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Tipo Actualización<center>
                                                        </th>




                                                        <th style="vertical-align:middle;">
                                                            <center>Mas Detalles<center>
                                                        </th>


                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
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
    <script>
        $(function() {
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
                    "search": "Buscar:",
                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>
    <script src="js/color.js"></script>
    <!-- DARK MODE-->
    <script src="js/dark-mode.js"></script>
</body>

</html>