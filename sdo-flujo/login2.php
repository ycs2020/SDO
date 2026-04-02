<?php
session_start();
session_unset();
session_destroy();

include '../sdo-funciones/subirTamano.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login SDO</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Control Centralizado">
</div>


    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h2"><b>Control Centralizado</b></a>
                <br>
                <br>
                <input type=image src="../dist/img/controlCent-HBQP-80.png">

            </div>
            <?php

            if (isset($_GET['cerrarSesion'])) {
                echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Sesi&oacute;n Finalizada con &Eacute;xito.</h5></div>';
            }

            if (isset($_GET['exitoSolicitarAcceso'])) {
                echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Solicitud ha sido enviada con &eacute;xito.</h5></div>';
            }

            if (isset($_GET['noExiste'])) {
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Usuario No Registrado. Reintente.</h5></div>';
            }

            if (isset($_GET['faltaActivacion'])) {
                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Su Acceso a&uacute;n se encuentra Pendiente.</h5></div>';
            }

            if (isset($_GET['errorLogin'])) {
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Iniciar Sesi&oacute;n. Reintente.</h5></div>';
            }

            if (isset($_GET['errorSesion'])) {
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Cargar Datos de Usuario. Reintente.</h5></div>';
            }

            if (isset($_GET['errorCambioPassword'])) {
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Cambiar Password de Usuario. Reintente.</h5></div>';
            }

            ?>
            <div class="card-body">
                <p class="login-box-msg">Ingrese sus Credenciales</p>

                <!-- <img src="../dist/img/ss-vina-quillota.png" alt="Control Centralizado"> -->

                <form action="../sdo-funciones/login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" id="usuarioRegistrado" required name="usuarioRegistrado" class="form-control" placeholder="Usuario">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="passwordRegistrado" required name="passwordRegistrado" class="form-control" placeholder="Contrase&nacute;a">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        <a href="solicitarAcceso.php" class="btn btn-block btn-secondary">
                            Solicitar Acceso
                        </a>
                    </div>
                </form>


            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
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