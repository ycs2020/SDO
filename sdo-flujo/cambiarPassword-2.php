<?php

$idUsuario = '';
$nomUsuario = '';
$rolUsuario = '';

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    $userUsuario = $_SESSION['userUsuario'];
    $idUsuario = $_SESSION['idUsuario'];
    $nomUsuario = $_SESSION['nomUsuario'];
    $rolUsuario = $_SESSION['rolUsuario'];
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contrase&ntilde;a de Usuario</title>

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
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
    </div>


    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h2"><b>Cambiar Contrase&ntilde;a</b></a>
            </div>
            <?php


            if (isset($_GET['cambiarPassword'])) {
                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Debe cambiar su Contrase&ntilde;a por motivos de seguridad.
                <br><br>
                Debe contar con al menos 8 caracteres y al menos 1 Mayuscula, 1 Minuscula, 1 Número y uno de estos simbolos @$!%*?&
                </h5></div>';
            }

            ?>
            <div class="card-body">
                <p class="login-box-msg">Ingrese Nueva Contrase&ntilde;a</p>

                <form action="../sdo-funciones/password-cambiar.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" id="userUsuario" readonly="readonly" required name="userUsuario" class="form-control" placeholder="Usuario" value="<?php echo $userUsuario; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="nuevaPassword" required name="nuevaPassword" title="Debe contar con al menos 8 caracteres y al menos 1 Mayuscula, 1 Minuscula, 1 Número y uno de estos simbolos @$!%*?&" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" minlength="8" class="form-control" placeholder="Nueva Contrase&ntilde;a">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()"></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="social-auth-links text-center mt-2 mb-3">
                        <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                        <input type="hidden" name="rolUsuario" id="rolUsuario" value="<?php echo $rolUsuario; ?>">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        <a href="login_index.php" class="btn btn-block btn-danger">
                            Volver
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
    <script>
        function mostrarContrasena() {
            var tipo = document.getElementById("nuevaPassword");
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