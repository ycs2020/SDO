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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Formato de Acceso</title>

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

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;background-attachment: fixed;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Solicitud de Formato de Acceso</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body  flex-column align-items-center">
                    <p>
                        Seleccione la Unidad a la cual desea ingresar.
                        <br>
                        <br>
                        <b>Importante: </b>Si la Unidad a la cual desea ingresar no aparece en el listado, quiere decir que dicha Unidad no cuenta con Sistema de Control de Acceso, por lo cual debe seleccionar Accesos Comunes.
                        <br>
                        <br>
                    </p>
                    <form action="accesos_solicitud_rev.php" method="post">
                        <label for="solicitanteFormRegAcceso">Nombre del Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="text" class="form-control" required placeholder="Nombre del Solicitante" id="solicitanteFormRegAcceso" name="solicitanteFormRegAcceso"/>
                        </div>
                        <br>
                        <label for="unidadSolicitaFormRegAcceso">Unidad del Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                            <input type="text" class="form-control" required placeholder="Unidad del Solicitante" id="unidadSolicitaFormRegAcceso" name="unidadSolicitaFormRegAcceso"/>
                        </div>
                        <br>
                        <label for="emailSolicitaFormRegAcceso">E-Mail del Solicitante:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mail-bulk"></span>
                                </div>
                            </div>
                            <input type="email" class="form-control" required placeholder="Email del Solicitante" id="emailSolicitaFormRegAcceso" name="emailSolicitaFormRegAcceso"/>
                        </div>
                        <br>
                        <label for="unidadSolicitaFormRegAcceso">Unidad donde desea ingresar:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                            <select class="form-control" id="seleccionAcceso" name="seleccionAcceso">
                                <option value="Seleccione..." selected disabled="disabled">Seleccione...</option>
                                <?php
                                $querySelec = "SELECT * FROM formularios_acceso ORDER BY unidadFormCCAA ASC";
                                $resSelec = mysqli_query($conexion, $querySelec);

                                while ($rowSelec = $resSelec->fetch_assoc()) {

                                    $idFormCCAA = $rowSelec['idFormCCAA'];
                                    $unidadFormCCAA = $rowSelec['unidadFormCCAA'];
                                    $unidadFormCCAA = mb_convert_case($unidadFormCCAA,MB_CASE_UPPER,'UTF-8');

                                    ?>
                                    <option value="<?php echo $idFormCCAA; ?>"><?php echo $unidadFormCCAA; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
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
</body>

</html>