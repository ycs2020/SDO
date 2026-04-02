
<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['rutActa2']) || isset($_SESSION['rutActa2'])) {

    $rutActa2 = isset($_POST['rutActa2']) ? trim($_POST['rutActa2']) : trim($_SESSION['rutActa2']);

    if (empty($rutActa2)) {
        header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
        exit;
    }

    $idActa = isset($_POST['idActa']) ? trim($_POST['idActa']) : (isset($_SESSION['idActa']) ? trim($_SESSION['idActa']) : '');

    if (empty($idActa) || $idActa < 1) {
        header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
        exit;
    }

    // Guardar en sesión si viene desde POST
    if (isset($_POST['rutActa2'])) {
        $_SESSION['rutActa2'] = $rutActa2;
        $_SESSION['idActa'] = $idActa;
    }


    $datosActa = array();
    $datosActa = getActa($idActa);

    $idActa2 = $datosActa['idActa'];
    $folioActa = $datosActa['folioActa'];
    $fechaActa = $datosActa['fechaActa'];
    $rutTarjeta = $datosActa['rutTarjeta'];
    $codigoTarjeta = $datosActa['codigoTarjeta'];
    $nomTarjeta = $datosActa['nomTarjeta'];
    $unidadTarjeta = $datosActa['unidadTarjeta'];
    $nomSolicitante = $datosActa['nomSolicitante'];
    $nomControlCentralizado = $datosActa['nomControlCentralizado'];
    $calidadContractual = $datosActa['calidadContractual'];
    $numTelefono = $datosActa['numTelefono'];
    $tipoActa = $datosActa['tipoActa'];
    $personaDiscapacitada = $datosActa['personaDiscapacitada'];
    $personaBecada = $datosActa['personaBecada'];


    $sqlObservacion = "SELECT * FROM actas_observacion WHERE idActa = $idActa2";
    $resObservacion = mysqli_query($conexion, $sqlObservacion);
    while ($rowObservacion = $resObservacion->fetch_assoc()) {
        $fechaIngreso = $rowObservacion['fechaIngreso'];
    }


} else {
    header('Location: ../sdo-personal/actas-listado.php?errorModificarActa=true');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Acta</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            recargarDatosJefatura();

            $('#unidadTarjeta').change(function () {
                recargarDatosJefatura();
            });


            function recargarDatosJefatura() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-actas/modificarDataJefaturas.php",
                    data: "nomUnidad=" + $('#unidadTarjeta').val() + "&nomJefeSolicita=" + $('#nomJefeSolicita').val(),
                    success: function (r) {
                        console.log(r);

                        if (r.trim() === 'Habilitar') {
                            document.getElementById("formOtraUnidad").style.display = "block";
                            document.getElementById('nomSolicitante').value = '';

                            document.getElementById('datosUnidad').value = "OtraUnidad";
                            document.getElementById("formOtroJefe").style.display = "none";
                            document.getElementById("visualizarNomJefes").style.display = "none";

                            document.getElementById('nomSolicitante').value = 'NUEVODATO';
                        } else if (r !== '') {
                            document.getElementById("visualizarNomJefes").style.display = "block";
                            $('#visualizarNomJefes').html(r);

                            document.getElementById('datosUnidad').value = "ConJefe";
                            document.getElementById("formOtraUnidad").style.display = "none";
                            document.getElementById("formOtroJefe").style.display = "none";
                        } else {

                        }


                    }
                });

            };

            $('body').on('change', '#nomSolicitante', function () {
                var nom = document.getElementById('nomSolicitante').value;

                if (nom == 'OtroNombre') {

                    document.getElementById("formOtroJefe").style.display = "block";
                    document.getElementById('datosUnidad').value = "OtroJefe";
                } else {

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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <form action="../sdo-funciones/actas-modificar.php" method="post" enctype="multipart/form">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Modificar Acta de Funcionario</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="actas-funcionario.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                            </div>
                        </div>


                        <input type="hidden" id="fechaIngreso" name="fechaIngreso" value="<?php echo $fechaIngreso ?>">
                        <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario ?>">


                        <div class="col-12">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomTarjeta">Nombre de Funcionario:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" required style="text-transform:capitalize" class="form-control" id="nomTarjeta" name="nomTarjeta" value="<?php echo $nomTarjeta ?>" placeholder="Nombre y Apellido de Usuario de Tarjeta">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomTarjeta">RUT de Funcionario:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize" class="form-control" id="rutTarjeta" name="rutTarjeta" value="<?php echo $rutTarjeta ?>" placeholder="RUT de Usuario de Tarjeta (Formato XXXXXXXXX, la K es 0)">
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
                                                    <?php
                                                    // Lista de opciones
                                                    $opciones = [
                                                        'Seleccione' => 'Seleccione...',
                                                        'Alumno en Practica' => 'Alumno en Practica',
                                                        'Contrata' => 'Contrata',
                                                        'Empresa Externa' => 'Empresa Externa',
                                                        'Honorarios' => 'Honorarios',
                                                        'Reemplazo' => 'Reemplazo',
                                                        'Titular' => 'Titular',
                                                        'Clinico Interno' => 'Clinico Interno'
                                                    ];

                                                    // Generar opciones dinámicamente
                                                    foreach ($opciones as $valor => $texto) {
                                                        $selected = ($calidadContractual == $valor) ? 'selected' : '';
                                                        echo "<option value='$valor' $selected>$texto</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="exampleInputCodigoTarjeta">N&uacute;mero de Contacto:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:capitalize" class="form-control" id="numTelefono" name="numTelefono" value="<?php echo $numTelefono ?>" placeholder="+569XXXXXXXX">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">

                                    <div class="form-row">


                                        <div class="form-group col-md-6">
                                            <label for="exampleInputCodigoTarjeta">Codigo de Tarjeta:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                </div>
                                                <input type="text" style="text-transform:uppercase" class="form-control" id="codigoTarjeta" name="codigoTarjeta" value="<?php echo $codigoTarjeta ?>" placeholder="Codigo de Tarjeta">
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

                                                        if ($rowUnidad['unidadTarjeta'] == $unidadTarjeta) {
                                                            ?>
                                                            <option value="<?php echo $rowUnidad['unidadTarjeta']; ?>" selected>
                                                                <?php echo $rowUnidad['unidadTarjeta']; ?>
                                                            </option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo $rowUnidad['unidadTarjeta']; ?>">
                                                                <?php echo $rowUnidad['unidadTarjeta']; ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }


                                                    ?>
                                                    <option value="OTRO">
                                                        AGREGAR OTRA UNIDAD
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="nomJefeSolicita" id="nomJefeSolicita" value="<?php echo $nomSolicitante; ?>">

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
<!--                                    <input type="hidden" name="nomSolicitante" id="nomSolicitante" value="--><?php //echo $nomSolicitante ?><!--">-->
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">

                                            <div class="custom-control custom-checkbox">
                                                <?php
                                                if ($personaBecada == 1) {
                                                    ?>
                                                    <input class="custom-control-input" checked type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                    <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                    <input type="hidden" name="valorBeca" id="valorBeca" value="SI">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input class="custom-control-input" type="checkbox" id="chkBecado" name="chkBecado" value="Becado">
                                                    <label id="" for="chkBecado" class="custom-control-label">Persona Becada</label>
                                                    <input type="hidden" name="valorBeca" id="valorBeca" value="NO">
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <script type="text/javascript">
                                                document.getElementById('chkBecado').onchange = function () {
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

                                    <div class="form-row">
                                        <div class="form-group col-md-6">

                                            <div class="custom-control custom-checkbox">
                                                <?php
                                                if ($personaDiscapacitada == 1) {
                                                    ?>
                                                    <input class="custom-control-input" checked type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                    <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                    <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="SI">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input class="custom-control-input" type="checkbox" id="checkBoxDiscapacidad" name="checkBoxDiscapacidad" value="Discapacitado">
                                                    <label id="" for="checkBoxDiscapacidad" class="custom-control-label">Persona con Discapacidad</label>
                                                    <input type="hidden" name="valorDiscapacidad" id="valorDiscapacidad" value="NO">
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <script type="text/javascript">
                                                document.getElementById('checkBoxDiscapacidad').onchange = function () {
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
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="form-group">
                                        <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                                        <input type="hidden" name="nomControlCentralizado" id="nomControlCentralizado" value="<?php echo $nomUsuario; ?>">
                                        <input type="hidden" name="antiguoRutTarjeta" id="antiguoRutTarjeta" value="<?php echo $rutTarjeta; ?>">
                                        <input type="hidden" name="idActa2" id="idActa2" value="<?php echo $idActa; ?>">
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>
                                    </div>


                                    <div class="form-group">
                                        <a href="actas-funcionario.php" class="btn btn-block btn-warning"> Volver </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

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
    $(function () {
        bsCustomFileInput.init();
    });
</script>
</body>

</html>
