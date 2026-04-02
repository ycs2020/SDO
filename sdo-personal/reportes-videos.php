<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Imagenes de Reporte</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".delete_imagen").click(function() {
                var idImagen = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '../sdo-funciones/imagen-eliminar.php',
                    data: 'idImagen=' + idImagen,
                    success: function(r) {
                        $('#eliminarImagen').html(r);
                        location.reload();
                    }
                });
            });
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/user-sidebar.php';

        $idReporte = $_SESSION['idReporte'];

        $contaImagen = contarImagen($idReporte);

        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Modificar Imagenes de Reporte</h1>
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
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">

                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <h3 class="card-title">Modificar Imagenes de Reporte</h3>
                                </div>
                                <div id="eliminarImagen" name="eliminarImagen">

                                </div>
                                <!-- /.card-header -->
                                <?php

                                if (isset($_GET['exitoModificarEventosReporte'])) {
                                    echo '<br><div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Eventos de Reporte Modificados con &Eacute;xito.</h5></div>';
                                }

                                ?>
                                <div class="card-body">

                                    <br>
                                    <div class="table-responsive">
                                        <table id="tableImagen" name="tableImagen" class="table table-bordered">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Nombre<center>
                                                    </th>
                                                    <th>
                                                        <center>Ruta<center>
                                                    </th>
                                                    <th>
                                                        <center>Visualizar<center>
                                                    </th>
                                                    <th>
                                                        <center>Eliminar<center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $queryImagenes = "SELECT idImagen, nomImagen, extImagen, rutaImagen FROM imagenes WHERE idReporte =" . $idReporte;

                                                $resImagenes = mysqli_query($conexion, $queryImagenes);

                                                $numImagen = 1;

                                                $totalImagen = 0;

                                                while ($rowImagenes = $resImagenes->fetch_assoc()) {
                                                ?>

                                                    <tr class="tr[<?php echo $idImagen; ?>]">
                                                        <td style="vertical-align:middle;">
                                                            <center><?php echo $numImagen; ?></center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <?php echo trim($rowImagenes['nomImagen']); ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <?php echo trim($rowImagenes['rutaImagen']); ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idImagen = $rowImagenes['idImagen'];
                                                                $idImagen = trim($idImagen);
                                                                if ($idImagen == 0 || $idImagen == "" || $idImagen == " " || $idImagen == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>


                                                                    <button type="button" data-toggle="modal" data-target="#modalImagen<?php echo $numImagen; ?>" id="inputMozi" class="btn btn-warning"><i class="fa fa-eye"></i></button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="modalImagen<?php echo $numImagen; ?>" name="modalImagen<?php echo $numImagen; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-primary">
                                                                                    <h5 class="modal-title" id="exampleModalLabel"><b>Imagen: </b><?php echo $rowImagenes['nomImagen']; ?></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    <div style="text-align:center;" class="form-group">
                                                                                        <img style="height:100%; width:100%;" border="1" src="<?php echo $rowImagenes['rutaImagen']; ?>" alt="<?php echo $rowImagenes['nomImagen']; ?>"><br>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <center>
                                                                <?php
                                                                $idImagen = $rowImagenes['idImagen'];
                                                                $idImagen = trim($idImagen);
                                                                if ($idImagen == 0 || $idImagen == "" || $idImagen == " " || $idImagen == "  ") {
                                                                ?>
                                                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                <?php
                                                                } else {

                                                                ?>

                                                                    <button class="btn btn-danger delete_imagen" type="button" name="<?php echo $idImagen; ?>" id="<?php echo $idImagen; ?>"><i class="fa fa-trash"></i></button>
                                                                    <!-- <a class="btn btn-danger" href="#" target="" rel="noopener noreferrer"><i class="fa fa-trash"></i></a> -->

                                                                <?php

                                                                }
                                                                ?>


                                                            </center>
                                                        </td>
                                                    </tr>

                                                <?php

                                                    $numImagen++;

                                                    $totalImagen++;
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <tr>
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Nombre<center>
                                                    </th>
                                                    <th>
                                                        <center>Ruta<center>
                                                    </th>
                                                    <th>
                                                        <center>Visualizar<center>
                                                    </th>
                                                    <th>
                                                        <center>Eliminar<center>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <hr>

                                    <?php

                                    $quedanVideos = 100 - $totalImagen;

                                    if ($quedanVideos > 0) {
                                    ?>
                                        <form action="../sdo-funciones/personal-reportes-guardarVideos.php" method="post" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <!-- <label for="customFile">Custom File</label> -->
                                                <h4 for="exampleInputTareasControlCent">Subir Imagenes: (Maximo <?php echo $quedanVideos; ?> Imagenes)<code>*</code></h4>
                                                <h4 for="exampleInputTareasControlCent">Formatos Permitidos: .jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif</h4>
                                                <br>
                                                <div class="custom-file">
                                                    <input type="file" max-uploads="<?php echo $quedanVideos; ?>" class="custom-file-input" multiple="" id="archivoImagen[]" name="archivoImagen[]" accept=".jpg, .png, .jpeg, .bmp, .tif, .webp, .svg, .jfif">
                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                </div>
                                            </div>

                                            <br>
                                            <br>

                                            <div class="form-group">
                                                <input type="hidden" name="idReporte" id="idReporte" value="<?php echo $idReporte; ?>">
                                                <input type="hidden" name="quedanVideos" id="quedanVideos" value="<?php echo $quedanVideos; ?>">
                                                <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                            </div>

                                        </form>
                                    <?php
                                    }

                                    ?>


                                    <div class="form-group">
                                        <a href="reportes-listado.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col  -->

                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>

</body>

</html>