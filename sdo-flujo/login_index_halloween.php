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
        body {
            cursor: url('../sdo-flujo/sdo-imagenes/pumpkin32.png'), auto;
            background:
                    url('../sdo-flujo/sdo-imagenes/pumpkin32.png') repeat-x top,
                    url('../sdo-flujo/sdo-imagenes/pumpkin32.png') repeat-x bottom,
                    url('../dist/img/Hospital.png') no-repeat center center;
            background-size: 50px 50px, 50px 50px, cover;
            background-color: #fff; /* Ajustar el color de fondo si es necesario */

        }

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
<body class="hold-transition login-page ">

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
        <div class="col-md-9 mx-auto">
            <div class="card shadow border form-login-sdo" id="form-login-sdo" >

                <div class="card-body  align-items-center">
                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 text-center align-content-center">

                            <a href="#" class="h3"><b>Plataforma SDO</b></a>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-12 text-center align-content-center">
                                    <input class="img-fluid" type="image" src="../dist/img/controlCent-HBQP.png" style="width: 150px" alt="Control Centralizado">
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-12 text-center align-content-center">
                                    <input class="img-fluid" type="image" src="../sdo-flujo/sdo-imagenes/halloween.gif" style="width: 150px;" alt="Halloween">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 text-center align-content-center">

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
</body>

</html>