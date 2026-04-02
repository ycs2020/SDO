<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Access-Control-Allow-Origin: *'); // http://10.68.120.2/sighdesarrollobp/sighbp/soporte_sigh/generar_solic_tics.php
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');


$datosCCAA = array();
if(isset($_GET['VerificaDestinoSDO'])){
    $VerificaDestinoSDO = $_GET['VerificaDestinoSDO'];

    $fraseSe = md5('C0ntr0l5D0_2023');
    if($VerificaDestinoSDO == $fraseSe){
        if(isset($_GET['funCreador'],$_GET['rutFunSolic'],$_GET['nomFunSolic'],$_GET['unidadSolic'],$_GET['ubicacionSolic'], $_GET['anexoSolic'])){
            $funCreador = $_GET['funCreador'];
            $nomUT = $_GET['nomFunSolic'];
            $rutUT2 = $_GET['rutFunSolic'];
            $unidadUT = $_GET['unidadSolic'];
            $ubicacionSolic = $_GET['ubicacionSolic'];
            $telefonoUT = $_GET['anexoSolic'];

            $_SESSION['VerificaDestinoSDO'] = $VerificaDestinoSDO;
        }else{
            header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorVerificarUsuariosT=true');
        }
    }else{
        header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorVerificarUsuariosT=true');
//        echo 'Paso 1';
    }

}else if(isset($_SESSION['idUT'],$_SESSION['rutUT'])){

    $idUT = trim($_SESSION['idUT']);
    $rutUT = trim($_SESSION['rutUT']);

    if($idUT > 0 && !empty($rutUT)){

        $datosUT = getDatosUT($idUT);

        $idUT2 = $datosUT['idUT'];
        $nomUT = $datosUT['nomUT'];
        $rutUT2 = $datosUT['rutUT'];
        $passUT = $datosUT['passUT'];
        $emailUT = $datosUT['emailUT'];
        $cargoUT = $datosUT['cargoUT'];
        $unidadUT = $datosUT['unidadUT'];
        $telefonoUT = $datosUT['telefonoUT'];
        $activoUT = $datosUT['activoUT'];
        $_SESSION['VerificaDestinoSDO'] = 'Vacio';


    }else{
        header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT=true');
    }
}else{
    header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorVerificarUsuariosT1=true');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Ticket</title>

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
            <div class="col-lg-8 col-md-12 mx-auto">
                <div class="card shadow border">
                    <div class="card-header text-center">
                        <a href="#" class="h4"><b>Formulario de Ticket</b></a>
                    </div>

                    <div class="card-body align-items-center">

                        <form action="../sdo-funciones/soporte_agregar_solicitud.php" id="soporteAgregar" name="soporteAgregar" method="post">

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputRounded0">RUT Solicitante: <code>*</code></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                        <input type="text" id="rutSolicitud" readonly="readonly" name="rutSolicitud" class="form-control" placeholder="RUT Solicitud (XXXXXXXXX)" value="<?php echo $rutUT2; ?>">
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
                                        <input type="text" id="nomSolicitud" readonly="readonly" name="nomSolicitud" class="form-control" placeholder="Nombre Solicitud" value="<?php echo $nomUT; ?>">
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

                                        <input type="text" id="deptoSolicitud" readonly="readonly" name="deptoSolicitud" class="form-control" placeholder="Departamento Solicitud" value="<?php echo $unidadUT; ?>">
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
                                        <input type="email" id="correoSolicitud" required name="correoSolicitud" class="form-control" placeholder="E-mail Solicitante" value="<?php echo $emailUT; ?>">
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
                                        <input type="text" id="celularSolicitante" required readonly="readonly" name="celularSolicitante" class="form-control" placeholder="+56XXXXXXXXX o XXXXXX" value="<?php echo $telefonoUT; ?>">
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
                                                if($rowDepartamentos['nomDepartamento'] !== 'Informática'){
                                                    ?>
                                                    <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                        <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                    </option>
                                                    <?php
                                                }

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
                                    <?php
                                        if(!empty($ubicacionSolic)){
                                            ?>
                                            <input type="text" id="pmaSolicitud" readonly name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="<?php echo $ubicacionSolic?>">
                                            <?php
                                        } else{
                                            ?>
                                            <input type="text" id="pmaSolicitud" required name="pmaSolicitud" class="form-control" placeholder="Ubicaci&oacute;n donde se encuentra el Problema (PMA) (Ejemplo: A-1-1)" value="">
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputRounded0">Referente a su Problema, ¿En qu&eacute; podemos ayudarle?: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <textarea id="detalleSolicitud" style="resize:none;" name="detalleSolicitud" class="form-control" rows="4" placeholder="Detalle del Problema que Presenta..."></textarea>
                                </div>
                            </div>
                            <div class="social-auth-links text-center mt-2 mb-3">
                                <button id="ingresarSolicitud" name="ingresarSolicitud" type="submit" class="btn btn-primary btn-block" onclick="cambiarTipo()">Ingresar</button>
                            </div>
                        </form>
<!--                        <div class="form-group">-->
<!--                            <a href="soporte_index.php" class="btn btn-block btn-warning"> Volver </a>-->
<!--                        </div>-->
                        <script>
                            function cambiarTipo() {
                                const boton = document.querySelector('button[type="submit"]');
                                boton.type = 'button';  // Cambia el tipo del botón a "button"
                                boton.textContent = 'Enviando...';  // Cambia el texto del botón
                                boton.disabled = true;  // Deshabilita el botón para evitar envíos repetidos

                                // // Simula un tiempo de carga antes de enviar el formulario
                                // setTimeout(function() {
                                //     document.getElementById('soporteAgregar').submit();  // Envía el formulario
                                // }, 3000); // Simula 3 segundos de tiempo de carga


                                document.getElementById('soporteAgregar').submit();  // Envía el formulario
                            }
                        </script>


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