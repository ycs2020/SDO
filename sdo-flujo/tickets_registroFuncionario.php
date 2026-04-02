<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');


$datosCCAA = array();

if(isset($_SESSION['rutParaValidar'])){

    $rutUT = trim($_SESSION['rutParaValidar']);

    if(empty($rutUT)){
        header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT=true');
    }

}else{
    header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT=true');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Ingreso</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarNomDepartamento();

            $('#unidadUsuariosT').change(function() {
                recargarNomDepartamento();
            });


            function recargarNomDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#unidadUsuariosT').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);


                    }
                });

            };



        });
    </script>


</head>


<body style="background-image:url(../dist/img/Hospital.png); height:100%; background-repeat: no-repeat;  background-size: cover;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>


<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <a href="#" class="h3"><b>Formulario de Ingreso a Sistema Ticket</b></a>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <div class="card-body align-items-center">

                    <form action="../sdo-funciones/ticket_agregarFuncionario.php" method="post">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">RUT Funcionario: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" id="rutUsuariosT" readonly="readonly" name="rutUsuariosT" class="form-control" placeholder="RUT (XXXXXXXXX)" value="<?php echo $rutUT; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">Nombre Funcionario: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                    <input type="text" id="nomUsuariosT" required name="nomUsuariosT" class="form-control" placeholder="Nombre Funcionario" value="">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="passUsuarioT">Nueva Password Funcionario: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-key"></span>
                                        </div>
                                    </div>
                                    <input type="password" id="passUsuariosT" title="Debe contar con al menos 8 caracteres y al menos 1 Mayuscula, 1 Minuscula, 1 Número y uno de estos simbolos @$!%*?& y " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" minlength="8" required name="passUsuariosT" class="form-control" placeholder="Password" value="">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">Unidad o Servicio: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-building"></span>
                                        </div>
                                    </div>

                                    <select id="unidadUsuariosT" class="form-control" name="unidadUsuariosT">
                                        <option value="" selected disabled>
                                            Seleccione...
                                        </option>
                                        <?php

                                        $queryUnidad = "SELECT DISTINCT unidadTarjeta FROM actas ORDER BY unidadTarjeta ASC";

                                        $resUnidad = mysqli_query($conexion, $queryUnidad);

                                        while ($rowUnidad = $resUnidad->fetch_assoc()) {

                                            ?>
                                            <option value="<?php echo $rowUnidad['unidadTarjeta']; ?>">
                                                <?php echo $rowUnidad['unidadTarjeta']; ?>
                                            </option>
                                            <?php

                                        }


                                        ?>
                                        <option value="OTRO">
                                            AGREGAR OTRA UNIDAD
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">Cargo: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-building"></span>
                                        </div>
                                    </div>

                                    <input type="text" id="cargoUsuariosT" required name="cargoUsuariosT" class="form-control" placeholder="Cargo  Funcionario" value="">
                                </div>
                            </div>
                        </div>


                        <div name="visualizarNomServicio" id="visualizarNomServicio" class="form-row">

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">E-Mail Solicitante: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-at"></span>
                                        </div>
                                    </div>
                                    <input type="email" id="emailUsuariosT" required name="emailUsuariosT" class="form-control" placeholder="E-mail Solicitante" value="">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputRounded0">Telefono Solicitante o Anexo Minsal: <code>*</code></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-phone"></span>
                                        </div>
                                    </div>
                                    <input type="text" id="telefonoUsuariosT" required name="telefonoUsuariosT" class="form-control" placeholder="+56XXXXXXXXX o XXXXXX" value="">
                                </div>
                            </div>
                        </div>


                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button id="ingresarSolicitud" name="ingresarSolicitud" type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                    </form>
                    <div class="form-group">
                        <a href="soporte_index.php" class="btn btn-block btn-warning"> Volver </a>
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