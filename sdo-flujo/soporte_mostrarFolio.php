<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (isset($_SESSION['folioSolicitud'])) {

    $folioSolicitud = $_SESSION['folioSolicitud'];

    $nomSolicitante = $_SESSION['nomSolicitante'];
} else {
    header('Location: ../sdo-flujo/login_index.php?errorAgregarSolicitud=true');
}

$VerificaDestinoSDO = $_SESSION['VerificaDestinoSDO'];

$fraseSe = md5('C0ntr0l5D0_2023');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud Creada con Exito</title>

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


<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>


<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Solicitud de Soporte - SDO</b></a>
                    <br>
                    <br>
                    <input type=image src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body align-items-center">

                    <div class="form-group">
                        <h5 style="text-align:justify;"><b>Estimado(a): </b><?php echo $nomSolicitante; ?></h5>
                        <br>
                        <br>
                        <h5 style="text-align:justify;">Su Solicitud ha sido ingresada con &eacute;xito en nuestro sistema y se le ha asignado el N# <b><?php echo $folioSolicitud; ?></b>.</h5>
                        <br>
                        <h5 style="text-align:justify;">Para consultar las actualizaciones de su estado, debe ingresar al siguiente enlace: <a href='http://sdo.hospitalquillota.cl/sdo-flujo/soporte_consultaEstado.php'>Comprobar Estado de Solicitud</a></h5>
                        <br>
                        <h5 style="text-align:justify;"><b>Importante: </b>El seguimiento de este Ticket solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.</h5>
                        <br>
                        <h5 style="text-align:center;">Atte.<br><br>Administrador de Tickets de Soporte - SDO</h5>
                        <br>
                    </div>

                    <?php

                    if ($VerificaDestinoSDO == $fraseSe) {
                        ?>
                        <div class="form-group">
                            <button class="btn btn-block btn-warning" onclick="cerrarPestana()">Finalizar</button>
                        </div>

                        <script>
                            function cerrarPestana() {
                                window.close();  // Cierra la pestaña actual
                            }
                        </script>
                        <?php
                    } else {
                        ?>
                        <div class="form-group">
                            <a href="soporte_index.php" class="btn btn-block btn-warning"> Volver </a>
                        </div>
                        <?php
                    }

                    ?>


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