<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; de Inicio - SDO</title>

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
    <link rel="stylesheet" href="../dist/css/navidad.css">
    <style>
        body {
            cursor: url('../dist/img/Navidad/santa-claus-24.png'), auto;
        }
    </style>
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;" class="hold-transition login-page ">
<div class="overlay">
    <img src="../dist/img/Navidad/Nieve%202.gif" width="100%" alt="Nieve">
</div>
<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>
<?php

if (isset($_GET['noExisteUsuario'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Usuario No Existe. Reintente.',
            'error'
        )
    </script>
    <?php
}

if (isset($_GET['cerrarSesion'])) {
    ?>
    <script>
        Swal.fire(
            'Informativo',
            'Su Sesi&oacute;n ha finalizado con &eacute;xito.',
            'success'
        )
    </script>
    <?php
}

if (isset($_GET['exitoSolicitarAcceso'])) {
    ?>
    <script>
        Swal.fire(
            'Informativo',
            'Su Solicitud ha sido enviada con &eacute;xito.',
            'success'
        )
    </script>
    <?php
}

if (isset($_GET['exitoEnvioRecuperacion'])) {
    ?>
    <script>
        Swal.fire(
            'Informativo',
            'Su Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a<br>ha sido enviada con &eacute;xito.',
            'success'
        )
    </script>
    <?php
}

if (isset($_GET['errorEnvioRecuperacion'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Enviar Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a. Reintente.',
            'warning'
        )
    </script>
    <?php
}

if (isset($_GET['exitoRestauracionPassword'])) {
    ?>
    <script>
        Swal.fire(
            'Informativo',
            'Recuperaci&oacute;n de Contrase&ntilde;a con &Eacute;xito.',
            'success'
        )
    </script>
    <?php
}

if (isset($_GET['errorRestauracionPassword'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Realizar Recuperaci&oacute;n de Contrase&ntilde;a. Reintente.',
            'warning'
        )
    </script>
    <?php
}

if (isset($_GET['errorRecuperacion'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Realizar Proceso de Recuperaci&oacute;n<br>de Contrase&ntilde;a. Reintente.',
            'warning'
        )
    </script>
    <?php
}

if (isset($_GET['faltaActivacion'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Su Acceso a&uacute;n se encuentra "Inactivo". <br> Contacte a Soporte.',
            'warning'
        )
    </script>
    <?php
}

if (isset($_GET['errorLogin'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Iniciar Sesi&oacute;n. Reintente.',
            'error'
        )
    </script>
    <?php
}

if (isset($_GET['errorSesion'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Cargar Datos de Usuario. Reintente.',
            'error'
        )
    </script>
    <?php
}

if (isset($_GET['errorCambioPassword'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'Error al Cambiar Password de Usuario. Reintente.',
            'error'
        )
    </script>
    <?php
}

?>
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Ingreso a Plataforma SDO</b></a>
                    <br>
                    <img style="width: 100%" src="../dist/img/navidad1.gif" alt="Navidad">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                        </div>
                        <div class="col-md-6">
                            <input type="image" width="100%" height="80px" src="../dist/img/Navidad/Trineo.png">
                        </div>
                    </div>

                    <img style="width: 100%" src="../dist/img/navidad1.gif" alt="Navidad">
                </div>

                <div class="card-body d-flex flex-column align-items-center">
                    <?php

                    $ipPermitida = $_SERVER['REMOTE_ADDR'];

                    $enlaceSDO = '../sdo-funciones/login.php';

                    if($ipPermitida == '10.69.250.151'){
                        $enlaceSDO = '../sdo-funciones/login_2.php';
                    }

                    ?>
                    <form action="<?php echo $enlaceSDO; ?>" method="post">
                        <div class="input-group mb-3">
                            <input type="text" id="usuarioRegistrado" required name="usuarioRegistrado" class="form-control" placeholder="Usuario">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" id="passwordRegistrado" required name="passwordRegistrado" class="form-control" placeholder="Contrase&ntilde;a">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()"></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" id="btnIngresar" class="btn btn-primary btn-block">Ingresar</button>
                            <a href="password_usuario.php" class="btn btn-block btn-danger">
                                Recuperar o Cambiar Contrase&ntilde;a
                            </a>
                            <a href="plataforma_index.php" class="btn btn-block btn-warning">
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
        var tipo = document.getElementById("passwordRegistrado");
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
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Obtén el botón y el formulario
        var btnIngresar = document.getElementById("btnIngresar");
        var loginForm = document.querySelector("form");

        // Agrega un evento clic al botón
        btnIngresar.addEventListener("click", function () {
            // Verifica si hay campos requeridos vacíos
            if (validarCamposRequeridos(loginForm)) {
                // Deshabilita el botón para evitar múltiples clics
                btnIngresar.disabled = true;

                // Cambia el contenido del botón a un indicador de carga
                btnIngresar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';

                // Envía el formulario después de un breve retraso para permitir que el indicador de carga sea visible
                setTimeout(function () {
                    loginForm.submit();
                }, 500);
            } else {
            }
        });

        // Función para validar campos requeridos
        function validarCamposRequeridos(formulario) {
            var camposRequeridos = formulario.querySelectorAll("[required]");
            var camposValidos = true;

            camposRequeridos.forEach(function (campo) {
                if (!campo.value.trim()) {
                    camposValidos = false;
                }
            });

            return camposValidos;
        }
    });
</script>
</body>

</html>