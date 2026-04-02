<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

$rutParaSolicitud = '';
$rutParaValidar = '';

$datosCCAA = array();

if (isset($_POST['rutParaSolicitud'], $_POST['rutParaValidar'])) {

    $rutParaSolicitud = trim($_POST['rutParaSolicitud']);
    $rutParaValidar = trim($_POST['rutParaValidar']);

    if (empty($rutParaSolicitud) || empty($rutParaValidar)) {
        header('Location: soporte_verificarRUT.php?errorCargarRUT=true');
    } else {
        $datosCCAA = getDatosCCAA($rutParaValidar);

        $nomPersona = mb_convert_case($datosCCAA['name'], MB_CASE_LOWER, "UTF-8");

        $nomPersona = mb_convert_case($nomPersona, MB_CASE_TITLE, "UTF-8");

        $department = mb_strtoupper($datosCCAA['department'], "UTF-8");
    }
} else if (isset($_SESSION['rutParaSolicitud'], $_SESSION['rutParaValidar'])) {

    $rutParaSolicitud = trim($_SESSION['rutParaSolicitud']);
    $rutParaValidar = trim($_SESSION['rutParaValidar']);

    if (empty($rutParaSolicitud) || empty($rutParaValidar)) {
        header('Location: soporte_verificarRUT.php?errorCargarRUT=true');
    } else {
        $datosCCAA = getDatosCCAA($rutParaValidar);

        $nomPersona = mb_convert_case($datosCCAA['name'], MB_CASE_LOWER, "UTF-8");

        $nomPersona = mb_convert_case($nomPersona, MB_CASE_TITLE, "UTF-8");

        $deptoPersona = mb_strtoupper($datosCCAA['department'], "UTF-8");
    }
} else {
    header('Location: soporte_verificarRUT.php?errorCargarRUT=true');
}

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Soporte SDO</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoResponsable').change(function() {
                cargarTipoObservacion();
            });




            function cargarTipoObservacion() {

                var departamentoResponsable = $('#departamentoResponsable').val();

                if (departamentoResponsable == 0) {

                    document.getElementById('ingresarSolicitud').disabled = true;

                } else {

                    document.getElementById('ingresarSolicitud').disabled = false;

                }

                $.ajax({
                    type: "POST",
                    url: "sdo-solicitud/mostrarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoResponsable').val(),
                    success: function(r) {
                        $('#visualizarTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>


<body style="background-image:url(../dist/img/Hospital.png); height:100%; background-repeat: no-repeat;  background-size: cover;" class="hold-transition login-page ">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
    </div>


    <div class="container">
        <div class="row align-items-center vh-100">
            <div class="col-md-8 mx-auto">
                <div class="card shadow border">
                    <div class="card-header text-center">
                        <a href="#" class="h3"><b>Formulario de Solicitud de Soporte</b></a>
                    </div>

                    <div class="card-body align-items-center">

                        <form action="../sdo-funciones/soporte_agregar_solicitud.php" method="post">

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">RUT Solicitante: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        <input type="text" id="rutSolicitud" readonly="readonly" name="rutSolicitud" class="form-control" placeholder="RUT Solicitud (XXXXXXXXX)" value="<?php echo $rutParaSolicitud; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">Nombre Solicitante: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        <input type="text" id="nomSolicitud" readonly="readonly" name="nomSolicitud" class="form-control" placeholder="Nombre Solicitud" value="<?php echo $nomPersona; ?>">
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">Unidad o Servicio: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-building"></span>
                                            </div>
                                        </div>
                                        <?php

                                        if (!empty($deptoPersona)) {
                                            $deptoPersona = str_replace("\\", '/', $deptoPersona);
                                        }

                                        $deptoSolicitante = explode('/', $deptoPersona);
                                        $longitud_deptoSolicitante = count($deptoSolicitante);

                                        $ultima_deptoSolicitante = $deptoSolicitante[$longitud_deptoSolicitante - 1];

                                        $ultima_deptoSolicitante = mb_strtoupper($ultima_deptoSolicitante);

                                        ?>
                                        <input type="text" id="deptoSolicitud" readonly="readonly" name="deptoSolicitud" class="form-control" placeholder="Departamento Solicitud" value="<?php echo $ultima_deptoSolicitante; ?>">
                                    </div>
                                </div>
                            </div>



                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">E-Mail Solicitante: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-at"></span>
                                            </div>
                                        </div>
                                        <input type="email" id="correoSolicitud" required name="correoSolicitud" class="form-control" placeholder="E-mail Solicitante" value="">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">Telefono Solicitante o Anexo Minsal: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-phone"></span>
                                            </div>
                                        </div>
                                        <input type="text" id="celularSolicitante" required name="celularSolicitante" class="form-control" placeholder="+56XXXXXXXXX o XXXXXX" value="">
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">Tipo de Ayuda que necesita: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-building"></span>
                                            </div>
                                        </div>
                                        <select class="form-control" name="departamentoResponsable" id="departamentoResponsable">
                                            <option value="0">Seleccione...</option>
                                            <?php
                                            $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                            $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                            while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {
                                            ?>
                                                <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                    <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                </option>
                                            <?php

                                            }


                                            ?>
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div name="visualizarTipoSolicitud" id="visualizarTipoSolicitud" class="form-group col-md-6">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputRounded0">Ubicaci&oacute;n donde se debe brindar ayuda (PMA): <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-map"></span>
                                        </div>
                                    </div>
                                    <input type="text" id="pmaSolicitud" required name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputRounded0">Referente a su Problema, ¿En qu&eacute; podemos ayudarle?: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <textarea id="detalleSolicitud" style="resize:none;" name="detalleSolicitud" class="form-control" rows="8" placeholder="Detalle del Problema que Presenta..."></textarea>
                                </div>
                            </div>
                            <div class="social-auth-links text-center mt-2 mb-3">
                                <button id="ingresarSolicitud" name="ingresarSolicitud" type="submit" class="btn btn-primary btn-block">Ingresar</button>
                            </div>
                        </form>
                        <div class="form-group">
                            <a href="soporte_index.php" class="btn btn-block btn-warning"> Volver </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>