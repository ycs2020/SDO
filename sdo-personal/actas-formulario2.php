
<?php

// ASIGNAR ID
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$nomUsuario = $_SESSION['nomUsuario'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generar Acta de Activaci&oacute;n</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            recargarDatosJefatura();

            $('#unidadTarjeta').change(function() {
                recargarDatosJefatura();
            });



            function recargarDatosJefatura() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-actas/obtenerDataJefaturas.php",
                    data: "nomUnidad=" + $('#unidadTarjeta').val(),
                    success: function(r) {

                        if (r.trim() === 'Habilitar') {
                            document.getElementById("formOtraUnidad").style.display = "block";
                            document.getElementById('nomSolicitante').value = '';

                            document.getElementById('datosUnidad').value = "OtraUnidad";
                            document.getElementById("formOtroJefe").style.display = "none";
                            document.getElementById("visualizarNomJefes").style.display = "none";

                            document.getElementById('nomSolicitante').value = 'NUEVODATO';
                        } else if (r !== '') {
                            document.getElementById("visualizarNomJefes").style.display = "block";
                            document.getElementById('nomSolicitante').value = '';
                            $('#visualizarNomJefes').html(r);

                            document.getElementById('datosUnidad').value = "ConJefe";
                            document.getElementById("formOtraUnidad").style.display = "none";
                            document.getElementById("formOtroJefe").style.display = "none";
                        } else {

                        }


                    }
                });

            };

            $('body').on('change', '#nomSolicitante1', function() {
                var nom = document.getElementById('nomSolicitante1').value;

                if (nom == 'OTRONOMBRE') {

                    document.getElementById("formOtroJefe").style.display = "block";
                    document.getElementById('datosUnidad').value = "OtroJefe";
                    document.getElementById('nomSolicitante').value = nom;
                } else {
                    document.getElementById('nomSolicitante').value = nom;
                    document.getElementById("formOtroJefe").style.display = "none";
                }
            })


        });
    </script>
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
                        <h1>Generar Acta de Activaci&oacute;n</h1>
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
                <form action="../sdo-funciones/actas-generar.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario?>">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Informaci&oacute;n de Acta de Activaci&oacute;n:</h3>
                                </div>
                                <!-- /.card-header -->
                                <?php


                                if (isset($_GET['errorCrearActa'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Crear Acta. Reintente.</h5></div>';
                                }


                                ?>
                                <div class="card-body">

                                    <a href="actas-listado.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                    <br>
                                    <br>

                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Funcionario:&nbsp;</legend>


                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomTarjeta">Nombre de Funcionario:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" required style="text-transform:uppercase" class="form-control" id="nomTarjeta" name="nomTarjeta" placeholder="Nombre y Apellido de Usuario de Tarjeta">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomTarjeta">RUT de Funcionario:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" required style="text-transform:uppercase" class="form-control" id="rutTarjeta" name="rutTarjeta" placeholder="RUT de Usuario de Tarjeta (Formato XXXXXXXXX, la K es 0)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputNomTarjeta">Calidad Contractual de Funcionario:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select class="form-control" name="calidadContractual" id="calidadContractual">
                                                        <option value="Seleccione">Seleccione...</option>
                                                        <option value="Contrata">Alumno en Practica</option>
                                                        <option value="Contrata">Contrata</option>
                                                        <option value="Empresa Externa">Empresa Externa</option>
                                                        <option value="Honorarios">Honorarios</option>
                                                        <option value="Reemplazo">Reemplazo</option>
                                                        <option value="Titular">Titular</option>
                                                        <option value="Clinico Interno">Clinico Interno</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCodigoTarjeta">N&uacute;mero de Contacto:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" required class="form-control" id="numTelefono" name="numTelefono" placeholder="+569XXXXXXXX">
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <br>

                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Funcionario con Beca:&nbsp;</legend>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">

                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                    <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                    <input type="hidden" name="valorBeca" id="valorBeca" value="NO">
                                                </div>
                                                <script type="text/javascript">
                                                    document.getElementById('chkBecado').onchange = function() {
                                                        var esBecado = document.getElementById('valorBeca').value;

                                                        if (esBecado == 'SI') {
                                                            document.getElementById('valorBeca').value = 'NO';
                                                        } else if (esBecado == 'NO') {
                                                            document.getElementById('valorBeca').value = 'SI';
                                                        } else {
                                                            document.getElementById('valorBeca').value = 'NO';
                                                        }
                                                    };
                                                </script>
                                            </div>
                                        </div>


                                    </fieldset>

                                    <br>
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n de la Tarjeta:&nbsp;</legend>

                                        <div class="form-row">


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCodigoTarjeta">Codigo de Tarjeta:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                    </div>
                                                    <input type="text" required style="text-transform:uppercase" class="form-control" id="codigoTarjeta" name="codigoTarjeta" placeholder="Codigo de Tarjeta">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsuarioTarjeta">Unidad del Usuario de Tarjeta:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                    </div>
                                                    <select id="unidadTarjeta" class="form-control" name="unidadTarjeta">
                                                        <option value="">
                                                            SELECCIONE...
                                                        </option>
                                                        <?php

                                                        $queryUnidad = "SELECT DISTINCT unidadTarjeta FROM actas ORDER BY unidadTarjeta ASC";

                                                        $resUnidad = mysqli_query($conexion, $queryUnidad);

                                                        while ($rowUnidad = $resUnidad->fetch_assoc()) {

                                                            ?>
                                                            <option value="<?php echo $rowUnidad['unidadTarjeta']; ?>">
                                                                <?php echo $rowUnidad['unidadTarjeta']; ?>
                                                            </option>
                                                            <?php

                                                        }


                                                        ?>
                                                        <option value="OTRO">
                                                            AGREGAR OTRA UNIDAD
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div name="visualizarNomJefes" id="visualizarNomJefes" class="form-group col-md-6">

                                            </div>

                                        </div>

                                        <div name="formOtraUnidad" id="formOtraUnidad" style="display:none;">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputUsuarioTarjeta">Unidad del Usuario de Tarjeta:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:uppercase" class="form-control" id="unidadTarjeta2" name="unidadTarjeta2" placeholder="Unidad del Usuario de Tarjeta">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Nombre de Quien Solicita:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="nomSolicitante2" name="nomSolicitante2" placeholder="Nombre de Quien Solicita">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div name="formOtroJefe" id="formOtroJefe" style="display:none;">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Agregar Nombre de Quien Solicita:<code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" style="text-transform:capitalize" class="form-control" id="nomSolicitante3" name="nomSolicitante3" placeholder="Nombre de Quien Solicita">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="datosUnidad" id="datosUnidad" value="Normal">
                                        <input type="hidden" name="nomSolicitante" id="nomSolicitante">

                                </div>

                                </fieldset>

                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n sobre Parking:&nbsp;</legend>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">

                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="NO">
                                            </div>
                                            <script type="text/javascript">
                                                document.getElementById('checkBoxDiscapacidad').onchange = function() {
                                                    var esDiscapacitado = document.getElementById('valorDiscapacidad').value;

                                                    if (esDiscapacitado == 'SI') {
                                                        document.getElementById('valorDiscapacidad').value = 'NO';
                                                    } else if (esDiscapacitado == 'NO') {
                                                        document.getElementById('valorDiscapacidad').value = 'SI';
                                                    } else {
                                                        document.getElementById('valorDiscapacidad').value = 'NO';
                                                    }
                                                };
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkboxParking" name="checkboxParking" value="option1">
                                                <label for="checkboxParking" class="custom-control-label">Necesita Permisos de Estacionamiento</label>
                                            </div>
                                            <script type="text/javascript">
                                                document.getElementById('checkboxParking').onchange = function() {
                                                    document.getElementById('patente1').disabled = !this.checked;
                                                    document.getElementById('patente2').disabled = !this.checked;
                                                    document.getElementById('modelo1').disabled = !this.checked;
                                                    document.getElementById('modelo2').disabled = !this.checked;
                                                };
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 1:</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                </div>
                                                <input type="text" disabled class="form-control" id="patente1" name="patente1" placeholder="Patente de Vehiculo N° 1: (XXXXXX)">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 1</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                </div>
                                                <input type="text" disabled style="text-transform:capitalize" class="form-control" id="modelo1" name="modelo1" placeholder="Modelo y Color de Vehiculo N° 1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputUsuarioTarjeta">Patente de Vehiculo N° 2</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                </div>
                                                <input type="text" disabled class="form-control" id="patente2" name="patente2" placeholder="Unidad del Usuario de Tarjeta">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputCodigoTarjeta">Modelo y Color de Vehiculo N° 2</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                </div>
                                                <input type="text" disabled style="text-transform:capitalize" class="form-control" id="modelo2" name="modelo2" placeholder="Modelo y Color de Vehiculo N° 2">
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <br>
                                <br>


                                <div class="form-group">
                                    <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario; ?>">
                                    <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="<?php echo $nomUsuario; ?>" />
                                    <input type="hidden" id="rolUsuario" name="rolUsuario" value="<?php echo $rolUsuario; ?>" />
                                    <input type="submit" class="btn btn-block btn-primary" value="Guardar" />
                                </div>
                                <div class="form-group">
                                    <a href="actas-listado.php" class="btn btn-block btn-warning"> Volver </a>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!--/.col  -->

                </form>
            </div>
            <!-- /.row -->
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

<script src="js/dark-mode.js"></script>
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

</body>

</html>