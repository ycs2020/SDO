<?php

include_once '../sdo-funciones/conexion.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Recepción de Trabajo</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../sdo-funciones/signature_pad.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php
        include '../sdo-templates/user-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Formulario de Recepción de Trabajo</h1>
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
                                    <h3 class="card-title">Formulario de Recepción de Trabajo</h3>
                                </div>
                                <!-- /.card-header -->
                                <?php


                                if (isset($_GET['errorGenerar'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Recepción de Trabajo. Reintente.</h5></div>';
                                }


                                ?>

                                <!-- <form action="../sdo-funciones/ordenes-generar.php" method="post" enctype="multipart/form-data"> -->
                                <div class="card-body">

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
                                                <input type="datetime" readonly="readonly" id="fechaActualOrden" name="fechaActualOrden" class="form-control" value="<?php echo date('d-m-Y'); ?>" />

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomSolicitante">Nombre Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" readonly="readonly" style="text-transform:capitalize" class="form-control" id="nomSolicitante" name="nomSolicitante" value="<?php echo $nomUsuario; ?>" placeholder="Nombre Solicitante">
                                            </div>
                                        </div>

                                    </div>

                                    <hr>

                                    <!-- Contenedor y Elemento Canvas -->
                                    <div id="signature-pad" class="signature-pad">
                                        <div class="description">Dibujar aqui</div>
                                        <div class="signature-pad--body">
                                            <canvas style="width: 100%; height: 300px; border: 1px black solid; " id="canvas"></canvas>
                                        </div>
                                    </div>

                                    <!-- Formulario que recoge los datos y los enviara al servidor -->
                                    <form id="form" action="../sdo-funciones/firma-guardar.php" method="post">
                                        <input type="hidden" name="pacient_id" value="Hola">
                                        <input type="hidden" name="base64" value="" id="base64">
                                        <button id="saveandfinish" class="btn btn-success">Guardar y Finalizar</button>
                                    </form>



                                    <script type="text/javascript">
                                        var wrapper = document.getElementById("signature-pad");

                                        var canvas = wrapper.querySelector("canvas");
                                        var signaturePad = new SignaturePad(canvas, {
                                            backgroundColor: 'rgb(255, 255, 255)'
                                        });

                                        function resizeCanvas() {

                                            var ratio = Math.max(window.devicePixelRatio || 1, 1);

                                            canvas.width = canvas.offsetWidth * ratio;
                                            canvas.height = canvas.offsetHeight * ratio;
                                            canvas.getContext("2d").scale(ratio, ratio);

                                            signaturePad.clear();
                                        }

                                        window.onresize = resizeCanvas;
                                        resizeCanvas();
                                    </script>
                                    <script>
                                        document.getElementById('form').addEventListener("submit", function(e) {

                                            var ctx = document.getElementById("canvas");
                                            var image = ctx.toDataURL(); // data:image/png....
                                            document.getElementById('base64').value = image;
                                        }, false);
                                    </script>

                                    <br>
                                    <br>

                                    <img src="../sdo-funciones/Firma 1.png" alt="">
                                    <!-- <div class="form-group">
                                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar" />

                                        </div>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                                        </div> -->

                                </div>
                                <!-- </form> -->

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
    <!-- DARK MODE-->
    <script src="js/dark-mode.js"></script>
</body>

</html>