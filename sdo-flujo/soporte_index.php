<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();


    $_SESSION['rutParaSolicitud'] = '';
    $_SESSION['rutParaValidar'] = '';
} else {
    session_destroy();
    session_start();

    $_SESSION['rutParaSolicitud'] = '';
    $_SESSION['rutParaValidar'] = '';
}


include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; Solicitudes de Soporte</title>

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
    <script src="../dist/sweetalert2/dist/sweetalert2.css"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>


<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
    </div>
    <?php
    if(isset($_GET['exitoEnvioRecuperacion'])){
        ?>
        <script>
            Swal.fire(
                'Informativo',
                'Su Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a para Tickets<br>ha sido enviada con &eacute;xito.',
                'success'
            )
        </script>
        <?php
    }
    if(isset($_GET['errorCambioPassword'])){
        ?>
        <script>
            Swal.fire(
                'Error',
                'Error en Cambio de Password. Reintente',
                'error'
            )
        </script>
        <?php
    }
    if(isset($_GET['exitoRestauracionPassword'])){
        ?>
        <script>
            Swal.fire(
                'Informativo',
                'Su Contraseña fue Recuperada o Cambiada con Exito',
                'success'
            )
        </script>
        <?php
    }

    if(isset($_GET['noExisteUsuario'])){
        ?>
        <script>
            Swal.fire(
                'Error',
                'El RUT del Usuario no existe en la base de datos.',
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
                        <a href="#" class="h3"><b>Solicitud de Soporte SDO</b></a>
                        <br>
                        <input type=image src="../dist/img/controlCent-HBQP-80.png">
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <a href="soporte_verificarRUT.php" class="btn btn-block btn-primary">
                                Ingresar Nueva Solicitud
                            </a>
                        </div>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <a href="soporte_consultaEstado.php" class="btn btn-block btn-warning">
                                Consultar Estado de Solicitud
                            </a>
                        </div>
                       <!-- <div class="social-auth-links text-center mt-2 mb-3">
                            <a href="password_usuario_tickets.php" class="btn btn-block btn-info">
                                Recuperar o Cambiar la Contraseña
                            </a>
                        </div> -->
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <a href="sdo-manuales/Manual_Creacion_de_Tickets_SDO.pdf" target="_blank" class="btn btn-block btn-danger">
                                ¿C&oacute;mo crear un Ticket de Soporte?
                            </a>
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