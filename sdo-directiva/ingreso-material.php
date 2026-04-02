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
        include '../sdo-templates/directiva-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Ingreso Material</h1>
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
                            <h3 class="card-title">Información Ingreso Material</h3>
                        </div>
                        <div class="row">



                            <!-- left column -->
                            <div class="col-md-12">



                                <!-- /.card-header -->
                                <div class="card-body">

                                    <a href="materiales-administrar.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>


                                    <form action="valida-codigo-material.php" method="POST">

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Valida Código de Barras:</legend>
                                            <div class="form-group col-md-6">

                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-info" id="valida">Validar</button>
                                                    </div>
                                                    <input type="text" required style="text-transform:capitalize" class="form-control" id="codigoBarra5" name="codBarra" minlength="3" placeholder="Código de barras a Validar">
                                                    <div style="display: inline ; margin-left: 20px; font-size: 20px">

                                                        <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="Si el material no cuenta con código de barras valido o visible, debe inventar un código (Ej: 23DF5896DS21D) alfanumérico con una extensión maxima de 13 caracteres. Recuerde rotular el material para que pueda ser identificado en el futuro." data-title="Información"><i class="fas fa-question-circle"></i></a>

                                                    </div>
                                                </div>

                                                <?php



                                                if (isset($_GET['no-repetido'])) {

                                                    echo "<p style='color: #55c42c; font-weight: bold;'>No ingresado, favor llenar formulario de ingreso</p>";
                                                    echo "<p style='color: red; font-weight: bold;'>**Información obligatoria</p>";
                                                }

                                                if (isset($_GET['yaExiste'])) {

                                                ?>

                                                    <script>
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'info',
                                                            title: 'Material ya ingresada, intente con otra material, si lo que desea es modificar su información, debe ir al listado y desde hay realizar la modificación.',
                                                            showConfirmButton: true,
                                                            timer: 7000
                                                        })
                                                    </script>

                                                <?php
                                                }


                                                ?>
                                            </div>
                                        </fieldset>
                                    </form>

                                    <br>

                                    <?php

                                    $codigoRandom = rand(0000000000000, 9999999999999);

                                    ?>

                                    <button class="btn btn-info" id="GenerarCodigo" onclick="mostrarCodigo()">Generar Código de barras</button>
                                    <div style="display: inline ; margin-left: 20px; font-size: 20px">

                                        <a class="pop-me-over" id="popover" data-toggle="popover" data-placement="right" data-content="Recuerde guardar el código de barras generado antes de validarlo." data-title="Información"><i class="fas fa-question-circle"></i></a>

                                    </div>
                                    <br>
                                    <br>
                                    <img class="barcode" id="barcode" jsbarcode-format="auto" jsbarcode-value="<?php echo $codigoRandom ?>" jsbarcode-textmargin="0" jsbarcode-fontoptions="bold">
                                    <input type="hidden" id="codigoGenerado" value="<?php echo $codigoRandom ?>">






                                    <?php if (isset($_GET['no-repetido']) && isset($_GET['buscCodigo'])) { ?>


                                        <?php

                                        $buscarCodigo = trim($_GET['buscCodigo'])

                                        ?>

                                        <script>
                                            var input = document.getElementById('codigoBarra5');
                                            var btn = document.getElementById('valida');
                                            var GenerarCodigo = document.getElementById('GenerarCodigo');
                                            var barcode = document.getElementById('barcode');
                                            var popover = document.getElementById('popover');

                                                  
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'warning',
                                                title: 'Material aun no ingresado, por favor llene el formulario con los datos solicitados',
                                                showConfirmButton: true,
                                                timer: 3500
                                            })

                                            GenerarCodigo.style.display = 'none';
                                            barcode.style.display = 'none';
                                            popover.style.display = 'none';

                                            input.disabled = true;
                                            input.style.cursor = "not-allowed"
                                            btn.disabled = true;

                                      
                                        </script>
                                        <?php
                                        ?>

                                        <form action="../sdo-funciones/guardar-materiales.php" method="post" enctype="multipart/form-data">

                                            <fieldset class="border p-3">
                                                <legend class="w-auto">Informaci&oacute;n General del material:</legend>

                                                <p ><code>*</code> <span style="color: #be2596; font-weight:bold">Solo permiten el ingreso de letras, números, guiones. Nada de caracteres especiales.</span>   </p>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">

                                                        <label for="codigoBarra">Código de Barra:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text"  required readonly style="text-transform:uppercase; cursor: not-allowed" class="form-control" id="codigoBarra" minlength="3" value="<?php echo $buscarCodigo ?>" name="codBarra" placeholder="Código de barras del material" value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">

                                                        <label for="codigoBarra">Código de Inventario:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text"    style="text-transform:uppercase;" class="form-control" id="codigoInventario" minlength="3" maxlength="12" name="codigoInventario" placeholder="Código de Inventario" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">

                                                        <label for="codInventario">Número de serie:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text"   style="text-transform:uppercase;" class="form-control" id="numSerial" name="numSerial" placeholder="Número de serie " minlength="3">
                                                        </div>

                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputNomTarjeta">Nombre Descriptivo:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                            </div>
                                                            <input type="text"   required style="text-transform:uppercase" class="form-control" id="rutTarjeta" name="nombre" placeholder="Nombre Material">
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="departamento">Uso Departamento:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                            </div>
                                                            <select name="departamento" class="form-control" required id="departamento">
                                                                <option value="">-- Seleccione --</option>
                                                                <?php

                                                                $queryDepa = "SELECT * FROM departamentos";
                                                                $resDepa = mysqli_query($conexion, $queryDepa);

                                                                while ($rowDepa = $resDepa->fetch_assoc()) {

                                                                    $datos = [];

                                                                    $datos['idDepartamento'] = $rowDepa['idDepartamento'];
                                                                    $datos['nomDepartamento'] = $rowDepa['nomDepartamento'];

                                                                    $idDepartamento = $datos['idDepartamento'];
                                                                    $nomDepartamento = $datos['nomDepartamento'];



                                                                ?>

                                                                    <option value="<?php echo $idDepartamento ?>">
                                                                        <?php echo $nomDepartamento ?>
                                                                    </option>


                                                                <?php
                                                                    
                                                                }
                                                                
                                                                ?>
                                                                <option value="10">Todos</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputFile">Imagen:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-file-image"></i></span>
                                                            </div>
                                                            <input type="file" accept="image/jpeg, image/png" required class="form-control" id="imagen" name="imagen">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputFile">Sector:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                            </div>
                                                            <input type="text"  style="text-transform:uppercase" required class="form-control" id="sector" name="sector" placeholder="ESCRIBA SECTOR EJ: A">
                                                        </div>
                                                    </div>

                                                </div>



                                                <div class="form-row">

                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputCodigoTarjeta">Marca:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="numTelefono" name="marca" placeholder="Marca Material" style="text-transform:uppercase">
                                                        </div>
                                                    </div>


                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputNomTarjeta">Modelo:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:uppercase" class="form-control" id="rutTarjeta" name="modelo" placeholder="Modelo del Material" style="text-transform:uppercase">

                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputNomTarjeta">Fecha Ingreso:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-calendar-times"></i></span>
                                                            </div>

                                                            <input disabled type="text" required style="text-transform:capitalize; cursor: not-allowed" class="form-control" id="rutTarjeta" name="ingreso" value="<?php echo date('Y/m/d'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="vencimiento">Fecha Vencimiento:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-calendar-times"></i></span>
                                                            </div>

                                                            <input type="date" style="text-transform:capitalize" class="form-control" id="vencimiento" name="vencimiento">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row">


                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputCodigoTarjeta">Cantidad:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-angle-up"></i></span>
                                                            </div>
                                                            <input type="number" min="1" required class="form-control"  id="cantidad" name="cantidad"  placeholder="Ej: 5">
                         
                                                        </div>
                                                    </div>
                                                </div>

                                            </fieldset>

                                            <br>
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">Informaci&oacute;n Especifica del producto</legend>

                                                <div class="form-group col-md-12">
                                                    <label for="descripcion">Especificaciones del material</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <!--<input type="text" required class="form-control" id="unidadTarjeta" name="unidadTarjeta" placeholder="Unidad del Usuario de Tarjeta">-->
                                                        <textarea class="form-control" cols="10" rows="10" name="descripcion" id="descripcion"  aria-label="With textarea" placeholder="Manuales, especificaciones tecncias, etc...."></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <br>

                                            <br>
                                            <br>


                                            <div class="form-group">
                                                <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario; ?>">
                                                <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="<?php echo $nomUsuario; ?>" />
                                                <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                            </div>
                                            <div class="form-group">
                                                <a href="materiales-administrar.php" class="btn btn-block btn-warning"> Volver </a>
                                            </div>
                                        </form>

                                    <?php } ?>

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
    <script src="js/funciones.js"></script>
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
    <script>
        function mostrarCodigo() {

            codigoBarra5 = document.getElementById('codigoBarra5');
            codigoGenerado = document.getElementById('codigoGenerado').value;

            codigoBarra5.value = codigoGenerado;



            var x = document.getElementById("barcode");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }

        }
    </script>
     <style>
        #barcode {
            display: none;
        }
    </style>

</body>

</html>