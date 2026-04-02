<?php
session_start();
session_destroy();

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login SDO</title>

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
                <a href="#" class="h2"><b>Control Centralizado</b></a>
            </div>
            <?php


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

            if (isset($_GET['errorSolicitarAcceso'])) {
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Enviar Solicitud de Acceso. Reintente.</h5></div>';
            }

            if (isset($_GET['existeUsuario'])) {
                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;El Nombre de Usuario ya se encuentra registrado. Reintente.</h5></div>';
            }

            ?>
            <div class="card-body">
                <h6 class="login-box-msg">Solicitud de Acceso para Reportes</h6>
                <br>
                <form action="../sdo-funciones/solicitarAcceso.php" method="post">
                    <div class="form-group">
                        <label for="labelNomSolicitante">Nombre Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" id="nombreSolicitar" required name="nombreSolicitar" class="form-control" placeholder="Nombre Solicitante">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="labelUserSolicitante">Usuario Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" id="usuarioSolicitar" required name="usuarioSolicitar" class="form-control" placeholder="Usuario Solicitante">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="labelPassSolicitante">Password Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" id="passwordSolicitar" required maxlength="10" name="passwordSolicitar" class="form-control" placeholder="Contrase&nacute;a Solicitante">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="labelDepaSolicitante">Departamento Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-bars"></span>
                                </div>
                            </div>
                            <select class="custom-select" name="departamentoSolicitar" id="departamentoSolicitar">
                                <?php

                                $queryDepartamentos = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                ?>
                                    <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                        <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                    </option>
                                <?php

                                }


                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block">Solicitar</button>
                </form>
                <div class="social-auth-links text-center mt-2 mb-3">

                    <a href="plataforma_index.php" class="btn btn-block btn-danger">
                        Volver
                    </a>
                </div>


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