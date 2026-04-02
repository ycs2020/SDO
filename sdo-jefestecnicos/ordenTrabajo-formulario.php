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
    <title>Formulario Orden de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/jefes-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Formulario de Orden de Trabajo</h1>
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
                    <form action="../sdo-funciones/jefe-reportes-generar.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulario de Orden de Trabajo</h3>
                                    </div>

                                    <div class="card-body">
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputFecha">Fecha Actual:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="date" id="fechaActualReporte" name="fechaActualReporte" class="form-control" value="<?php echo date('d-m-Y'); ?>" />

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Turno:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <select class="form-control" name="turnoOT" id="turnoOT">
                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                        <option value="dia">D&iacute;a</option>
                                                        <option value="noche">Noche</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <hr>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputOTrabajos">Trabajos a Realizar:</label>
                                                <textarea id="OTDetalle" style="resize:none;" name="OTDetalle" class="form-control" placeholder="Trabajos a Realizar"></textarea>
                                                <!-- Script -->
                                                <script type="text/javascript">
                                                    $('#OTDetalle').summernote({
                                                        height: 200,
                                                        focus: true,
                                                        disableResizeEditor: true
                                                    });
                                                    $('#OTDetalle').summernote('foreColor', 'rgb(255, 255, 255)');
                                                </script>
                                            </div>

                                        </div>



                                        <hr>

                                        <br>
                                        <br>

                                        <div class="form-group">
                                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!--/.col  -->

                        </div>
                    </form>
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
    <script src="js/dark-mode"></script>
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