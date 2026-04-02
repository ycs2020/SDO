<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');


if (session_status() == PHP_SESSION_NONE) {

    session_start();
}
if (isset($_GET['correoPWtickets'])) {
} else {
    header('Location: ../sdo-flujo/login_index.php?errorRecuperacion=true');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperaci&oacute;n de Contrase&ntilde;a - SDO</title>

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
                    <a href="#" class="h3"><b>Recuperaci&oacute;n de Contrase&ntilde;a - SDO</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body d-flex flex-column align-items-center">
                    <form action="../sdo-funciones/password-restaurar-tickets.php" method="post">
                        <div class="form-group">
                            <label for="usuarioRegistrado">Rut de Usuario <code>*</code></label>
                            <div class="input-group mb-3">
                                <input type="text" id="usuarioRegistrado" required name="rutRegistrado" class="form-control" placeholder="Rut de Usuario">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="codigoRegistrado">Código de Recuperaci&oacute;n <code>*</code></label>
                            <div class="input-group mb-3">
                                <input type="text" id="codigoRegistrado" required name="codigoRegistrado" class="form-control" placeholder="Codigo">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-code"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="passwordRegistrado">Nueva Contrase&ntilde;a <code>*</code></label>
                            <div class="input-group mb-3">
                                <input type="password" id="passwordRestaurar" title="Debe contar con al menos 8 caracteres y al menos 1 Mayuscula, 1 Minuscula, 1 Número y uno de estos simbolos @$!%*?& y " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" minlength="8" required name="passwordRestaurar" class="form-control" placeholder="Contrase&ntilde;a">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                            <a href="soporte_index.php" class="btn btn-block btn-warning">
                                Volver
                            </a>
                        </div>
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
<script>
    function mostrarContrasena() {
        var tipo = document.getElementById("passwordRestaurar");
        var ojo = document.getElementById("ojo");
        if (tipo.type == "password") {
            tipo.type = "text";
            ojo.classList.add('fa-eye-slash');
            ojo.classList.remove('fa-eye');
        } else {
            tipo.type = "password";
            ojo.classList.remove('fa-eye-slash');
            ojo.classList.add('fa-eye');
        }
    }

    function mostrarContrasena2() {
        var tipo = document.getElementById("passwordRegistrado2");
        var ojo = document.getElementById("ojo2");
        if (tipo.type == "password") {
            tipo.type = "text";
            ojo.classList.add('fa-eye-slash');
            ojo.classList.remove('fa-eye');
        } else {
            tipo.type = "password";
            ojo.classList.remove('fa-eye-slash');
            ojo.classList.add('fa-eye');
        }
    }


</script>

</body>
