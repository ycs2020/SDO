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
    <style>
        #rain-container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .raindrop {
            position: absolute;
            width: 5px; /* Ancho de las gotas de lluvia */
            height: 20px; /* Altura de las gotas de lluvia */
            background: rgba(174, 194, 224, 0.7);
            animation: fall linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }

        #cloud {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px; /* Altura de la nube */
            background: linear-gradient(to bottom, #fff, #ccc);
            border-radius: 0 0 50% 50%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            z-index: 1; /* Asegura que la nube esté encima de las gotas de lluvia */
        }
    </style>


    <style>
        #form-login-sdo {
            position: relative;
            border-radius: 10px;
            overflow: hidden; /* Asegura que los elementos hijos no se salgan del contenedor */
        }

        #form-login-sdo::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../dist/img/Hospital.png'); /* Ruta de la imagen del fondo */
            background-position: center;
            background-size: cover;
            filter: blur(5px);
            z-index: -1; /* Coloca el fondo detrás de los elementos hijos */
            opacity: 0.7; /* Ajusta la opacidad según sea necesario */
        }

        #form-login-sdo .card-body {
            position: relative;
            z-index: 1; /* Asegura que los elementos hijos estén sobre el fondo borroso */
        }
    </style>

</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;" class="hold-transition login-page ">
<div id="cloud"></div>
<div id="rain-container"></div>

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

if (isset($_GET['tipoUsuarioNoPermitido'])) {
    ?>
    <script>
        Swal.fire(
            '! Ha ocurrido un problema !',
            'No posee autorización para ver esta Interfaz.',
            'error'
        )
    </script>
    <?php
}


?>
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border form-login-sdo" id="form-login-sdo" >
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Ingreso a Plataforma SDO</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body d-flex flex-column align-items-center">
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
                            <input type="password" id="passwordRegistrado" required name="passwordRegistrado" class="form-control" placeholder="Contrase&ntilde;a">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()"></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
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
    function createRain() {
        const rainContainer = document.getElementById('rain-container');
        const numberOfDrops = 100; // Ajusta el número de gotas de lluvia

        for (let i = 0; i < numberOfDrops; i++) {
            const drop = document.createElement('div');
            drop.className = 'raindrop';
            drop.style.left = `${Math.random() * 100}vw`;
            drop.style.animationDuration = `${Math.random() * 1 + 1}s`;
            drop.style.animationDelay = `${Math.random() * 2}s`;
            rainContainer.appendChild(drop);
        }
    }

    createRain();
</script>
</body>

</html>