<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}


date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Formato de Acceso</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Solicitud de Formato de Acceso</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body  flex-column align-items-center">
                    <h5 style="text-indent:40px; text-align: justify;">Se ha generado de manera correcta la Solicitud de Acceso a la Unidad solicitada. Ahora debe descargar el archivo mediante el bot&oacute;n a continuaci&oacute;n y solicitar la Firma a la Jefatura de dicha Unidad.</h5>
                    <h5 style="text-indent:40px; text-align: justify;">Una vez firmado, debe ser enviado mediante correo electronico a la Sala de Control Centralizado, al e-mail: saladecontrolhbqp@gmail.com o de manera f&iacute;sica.</h5>

                    <br>

                    <form action="../sdo-pdf/SDO-Formulario-SolicitudAcceso-Nuevo.php" id="miFormulario" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="idFormRegAcceso" id="idFormRegAcceso" value="<?php echo '3'?>"/>
                        <button type="submit" style="text-align: center;" id="enviarFormulario" class="btn btn-block bg-success">Descargar Solicitud de Acceso</button>
                    </form>
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