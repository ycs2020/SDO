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
    <title>Solicitud de Soporte SDO</title>

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

<?php


        if (isset($_GET['exitoGenerarUsuariosT'])) {
            ?>
<script>
    Swal.fire(
        'Usuario Creado con &Eacute;xito. ',
        '',
        'success'
    )
</script>
<?php
}

if (isset($_GET['errorActivarCCAA'])) {
?>
<script>
    Swal.fire(
        'Error al Verificar Usuario en Sistema de Control de Acceso. Reintente.',
        '',
        'error'
    )
</script>
<?php
}
?>
<?php
if (isset($_GET['errorVerificarUsuariosT'])) {
    ?>
    <script>
        Swal.fire(
            'Error al Verificar Usuario. Reintente.',
            '',
            'error'
        )
    </script>
    <?php
}
?>
<?php
if (isset($_GET['errorVerificarUsuariosT1'])) {
    ?>
    <script>
        Swal.fire(
            'Error al Verificar Destino. Reintente.',
            '',
            'error'
        )
    </script>
    <?php
}
?>


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
                        <a href="#" class="h3"><b>Solicitud de Soporte</b></a>
                        <br>
                        <br>
                        <input type=image src="../dist/img/controlCent-HBQP-80.png">
                    </div>
                    <?php

                    function verifica_RUT($rutSolicitud = '')
                    {
                        $tmpRUT = '';
                        $sep = array();
                        $multi = 2;
                        $suma = 0;
                        if (empty($rutSolicitud)) {
                            $_SESSION['rutParaSolicitud'] = '';
                            $_SESSION['rutParaValidar'] = '';
                            return 1;
                        };

                        for ($i = 0; $i < strlen($rutSolicitud); $i++) {
                            if ($rutSolicitud[$i] != ' ' and $rutSolicitud[$i] != ' ' and $rutSolicitud[$i] != '.' and $rutSolicitud[$i] != '-') $tmpRUT .= $rutSolicitud[$i];
                        }

                        if (strlen($tmpRUT) == 8) $tmpRUT = '0' . $tmpRUT;
                        $sep['rut'] = substr($tmpRUT, 0, 8);

                        $sep['dv']  = substr($tmpRUT, -1);

                        if ($sep['dv'] == 'k') {
                            $sep['dv'] = 'K';
                        }

                        if (!is_numeric($sep['rut'])) {
                            $_SESSION['rutParaSolicitud'] = '';
                            $_SESSION['rutParaValidar'] = '';
                            return 3;
                        }

                        if (empty($sep['rut']) or $sep['dv'] == '') {
                            $_SESSION['rutParaSolicitud'] = '';
                            $_SESSION['rutParaValidar'] = '';
                            return 4;
                        }

                        for ($i = strlen($sep['rut']) - 1; $i >= 0; $i--) {
                            $suma = $suma + $sep['rut'][$i] * $multi;
                            if ($multi == 7) $multi = 2;
                            else $multi++;
                        }

                        $resto = $suma % 11;

                        if ($resto == 1) {

                            $sep['dvt'] = 'K';
                        } else {

                            if ($resto == 0) {

                                $sep['dvt'] = '0';
                            } else {

                                $sep['dvt'] = 11 - $resto;
                            }
                        }

                        if ($sep['dvt'] != $sep['dv']) {
                            $_SESSION['rutParaSolicitud'] = '';
                            $_SESSION['rutParaValidar'] = '';
                            return 5;
                        }

                        if ($tmpRUT[0] == 0) {
                            $tmpRUT = substr($tmpRUT, 1);
                        }

                        $_SESSION['rutParaSolicitud'] = $tmpRUT;

                        $tmpRUT = strtolower($tmpRUT);

                        /*if (stripos($tmpRUT, "k") == false) {
                            $rutFinal = $tmpRUT;
                        } else {
                            $tmpRUT2 = explode("k", strtolower($tmpRUT));

                            $rutFinal = $tmpRUT2[0] . '0';
                        }*/

                        $rutFinal = $tmpRUT;



                        $_SESSION['rutParaValidar'] = $rutFinal;

                        $buscarCCAA = buscarRutTicket($rutFinal);

                        if ($buscarCCAA > 0) {
                            header('Location: tickets_ingresoFuncionario.php');
                        } else {
                            header('Location: tickets_registroFuncionario.php');
                        }
                    }
                    // --------------------------------------------------------
                    // Hasta aquí llega, lo de abajo fue una prueba de concepto
                    // no más, que pueden copiar tal cual.

                    $rut = '';

                    if (isset($_GET['errorAgregarSolicitud'])) {
                        ?>
                        <script>
                            Swal.fire(
                                '! Ha ocurrido un problema !',
                                'Error al agregar la Solicitud de Soporte.',
                                'error'
                            )
                        </script>
                        <?php

                    }

                    if (isset($_POST['rutSolicitud'])) {
                        $error = verifica_RUT($_POST['rutSolicitud']);
                        switch ($error) {
                            case 0:
                        ?>
                                <script>
                                    Swal.fire(
                                        'Consulta de RUT correcta.',
                                        'Ud. puede realizar la solicitud de soporte.',
                                        'success'
                                    )
                                </script>
                            <?php
                                break;
                            case 1:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'RUT Vacio. Reintente.',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            case 2:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'Faltan caracteres en el RUT. Reintente.',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            case 3:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'El RUT solo puede contener n&uacute;meros y la letra K. Reintente.',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            case 4:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'RUT Vacio. Reintente.',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            case 5:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'RUT Invalido. Reintente.',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            case 6:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'Seg&uacute;n nuestra Base de Datos, Ud. no se encuentra registrado. <br> <br> Favor acercarse a Control Centralizado (C-3-4). <br> <br> Para resolver dudas : <br> <br> Desde Anexo Minsal: 335604. <br><br> Desde Celular: 332345604',
                                        'error'
                                    )
                                </script>
                            <?php
                                break;
                            default:
                            ?>
                                <script>
                                    Swal.fire(
                                        '! Ha ocurrido un problema !',
                                        'RUT Invalido. Reintente.',
                                        'error'
                                    )
                                </script>
                    <?php
                                break;
                        }
                        $rutSolicitud = $_POST['rutSolicitud'];
                    }

                    if (isset($_GET['errorIngresoTicket'])) {
                        ?>
                        <script>
                            Swal.fire(
                                '! Ha ocurrido un problema !',
                                'Error al Ingresar a Sistema de Ticket.',
                                'error'
                            )
                        </script>
                        <?php

                    }

                    if (isset($_GET['errorFaltaHabilitacion'])) {
                        ?>
                        <script>
                            Swal.fire(
                                '! Ha ocurrido un problema !',
                                'RUT no habilitado para Sistema de Tickets.<br><br>Favor contactar a Control Centralizado.<br><br>Desde Anexo Minsal: 335604. <br><br> Desde Celular: 332345604',
                                'error'
                            )
                        </script>
                        <?php

                    }

                    if (isset($_GET['errorGenerarUsuariosT'])) {
                        ?>
                        <script>
                            Swal.fire(
                                '! Ha ocurrido un problema !',
                                'Error al Iniciar Sesi&oacute;n para realizar Ticket.<br>Reintente.',
                                'error'
                            )
                        </script>
                        <?php

                    }

                    if (isset($_GET['exitoGenerarUsuariosT'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Proceso con &Eacute;xito.',
                                'Usuario Registrado con &Eacute;xito.<br><br>Dentro de 24 a 48 horas h&aacute;biles, se habilitara su acceso para la creaci&oacute;n de Tickets.',
                                'success'
                            )
                        </script>
                        <?php

                    }
                    ?>
                    <div class="card-body d-flex flex-column align-items-center">
                        <h5 class="card-text"><b>Ingrese su RUT para realizar solicitud.</b></h5>
                        <h5 class="card-text"><b>Formato RUT: XXXXXXXXX</b></h5>
                        <br>
                        <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" id="usuarioRegistrado" required name="rutSolicitud" class="form-control" placeholder="RUT Solicitud (XXXXXXXXX)">
                                <input type="hidden" name="rutParaSolicitud" id="rutParaSolicitud" value="<?php echo $_SESSION['rutParaSolicitud']; ?>">
                                <input type="hidden" name="rutParaValidar" id="rutParaValidar" value="<?php echo $_SESSION['rutParaValidar']; ?>">
                            </div>
                            <br>
                            <div class="social-auth-links text-center mt-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                                <br>
                                <a href="soporte_index.php" class="btn btn-block btn-danger">
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
</body>

</html>