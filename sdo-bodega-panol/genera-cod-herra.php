<?php

// ASIGNAR ID
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso Material</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">



    <script type="text/javascript">
        $(document).ready(function() {

            recargarNomCategoria();

            $('#categoriaOrden').change(function() {
                recargarNomCategoria();
            });

            function recargarNomCategoria() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-categorias/habilitarIngresoCategoria.php",
                    data: "idCategoria=" + $('#categoriaOrden').val(),
                    success: function(r) {
                        $('#visualizarNomCategoria').html(r);
                    }
                });
            };
        });
    </script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">
        <?php
        include '../sdo-templates/bodega-panol-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Código Barras</h1>
                        </div>
                        <div class="col-sm-6">
                           
                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")

                                    document.body.classList.toggle("dark-mode");

                                }
                            </script>
                            <br>
                            <br>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Generar código de barras</h3>
                        </div>
                        <div class="row">



                            <!-- left column -->
                            <div class="col-md-12">



                                <!-- /.card-header -->
                                <div class="card-body">

                                    <a href="materiales-administrar.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                        <form action="../sdo-funciones/genera_barras_cod_existente.php" method="post">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">Generar Código Barras:</legend>
                                                <div class="form-group col-md-6">

                                                    <div class="input-group mb-3">
                                                    
                                                        <input type="text" required style="text-transform:capitalize" class="form-control" id="codigoBarra5" name="codBarra" minlength="3" placeholder="Código de barras a Generar">
                                                    
                                                    </div>

                                                    <button type="submit" class="btn btn-info" id="GenerarCodigo" >Generar Código de barras</button>

                                                </div>
                                            </fieldset>
                                        </form>

                                    <br>

                                    <?php

                                    ?>

                                    <br>
                                    <br>

                                    <?php 
                                    
                                    if(isset($_GET["codBarra"])){

                                        ?>
                                        <img class="barcode" id="barcode" jsbarcode-format="auto" jsbarcode-value="<?php echo $_GET['codBarra']?>" jsbarcode-textmargin="0" jsbarcode-fontoptions="bold">

                                        <?php


                                    }
                                    
                                    
                                    ?>

                                    <input type="hidden" id="codigoGenerado" >

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
    <script src="js/lector-barras.js"></script>
    <script>
        $(function() {
            $('[data-toggle="popover"]').popover()
        })
    </script>
     <script src="js/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode(".barcode").init();
    </script>


</body>

</html>