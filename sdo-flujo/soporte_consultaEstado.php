<?php



if (session_status() == PHP_SESSION_NONE) {
    session_start();


    $_SESSION['folioSolicitud'] = '';
} else {
    session_destroy();
    session_start();

    $_SESSION['folioSolicitud'] = '';
}



include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar Estado de Solicitud de Soporte SDO</title>

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
            <div class="col-md-6 mx-auto">
                <div class="card shadow border">
                    <div class="card-header text-center">
                        <a href="#" class="h3"><b>Consulta de Estado de Solicitud de Soporte</b></a>
                        <br>
                        <br>
                        <input type=image src="../dist/img/controlCent-HBQP-80.png">
                    </div>

                    <?php
                    if (isset($_GET['noExisteFolio'])) {
                    ?>
                        <script>
                            Swal.fire(
                                '! Ha ocurrido un problema !',
                                'Solicitud No Existe. Reintente.',
                                'error'
                            )
                        </script>
                    <?php
                    }
                    ?>

                    <div class="card-body d-flex flex-column align-items-center">
                        <h5 class="card-text"><b>Ingrese Folio para Consultar Estado.</b></h5>
                        <br>
                        <form action="soporte_mostrarEstado.php" method="post" >
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-book"></span>
                                    </div>
                                </div>
                                <input type="text" id="folioSolicitud" required name="folioSolicitud" class="form-control" placeholder="Folio Solicitud">
                            </div>
                            <br>
                            <div class="social-auth-links text-center mt-2 mb-3 col-12">
                                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                            </div>
                        </form>

                        <div class="social-auth-links text-center mt-2 mb-3 col-lg-6 col-sm-8">
                            <button class="btn btn-warning btn-block" onclick="cerrarPestana()">Finalizar</button>
                        </div>

                        <script>
                            function cerrarPestana() {
                                window.close();  // Cierra la pestaña actual
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