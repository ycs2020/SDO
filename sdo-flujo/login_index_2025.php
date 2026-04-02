<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$horaActual = date('H:i');

$imgBg = '../dist/img/Hospital.png';

// Función para mostrar alertas SweetAlert2
function mostrarAlerta($tipo, $titulo, $mensaje) {
    echo "<script>
        Swal.fire({
            icon: '$tipo',
            title: '$titulo',
            html: '$mensaje'
        });
    </script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú de Inicio - SDO</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <style>
        body {
            background-image: url('<?php echo $imgBg; ?>');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }

        #form-login-sdo {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #form-login-sdo .card-header,
        #form-login-sdo .card-body {
            color: black;
        }

        #form-login-sdo input {
            background-color: rgba(255, 255, 255, 0.1);
            color: black;
            border: none;
        }

        #form-login-sdo .form-control::placeholder {
            color: #ccc;
        }

        #form-login-sdo .input-group-text {
            background-color: transparent;
            border: none;
            color: white;
        }

        #form-login-sdo .btn {
            border-radius: 25px;
            font-weight: bold;
        }

        #form-login-sdo .btn-primary {
            background: #007bff;
            border: none;
        }

        #form-login-sdo .btn-danger {
            background: #dc3545;
            border: none;
        }

        #form-login-sdo .btn-warning {
            background: #ffc107;
            border: none;
            color: black;
        }

        .preloader img {
            height: 80px;
        }
    </style>
</head>

<body class="hold-transition login-page">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="Logo SDO">
</div>

<?php
$alertas = [
    'noExisteUsuario'           => ['error', '! Ha ocurrido un problema !', 'Usuario No Existe. Reintente.'],
    'cerrarSesion'              => ['success', 'Informativo', 'Su Sesi&oacute;n ha finalizado con &eacute;xito.'],
    'exitoSolicitarAcceso'      => ['success', 'Informativo', 'Su Solicitud ha sido enviada con &eacute;xito.'],
    'exitoEnvioRecuperacion'    => ['success', 'Informativo', 'Solicitud de Recuperaci&oacute;n enviada con &eacute;xito.'],
    'errorEnvioRecuperacion'    => ['warning', '! Ha ocurrido un problema !', 'Error al enviar recuperaci&oacute;n. Reintente.'],
    'exitoRestauracionPassword' => ['success', 'Informativo', 'Recuperaci&oacute;n de Contrase&ntilde;a exitosa.'],
    'errorRestauracionPassword' => ['warning', '! Ha ocurrido un problema !', 'Error al realizar restauraci&oacute;n. Reintente.'],
    'errorRecuperacion'         => ['warning', '! Ha ocurrido un problema !', 'Proceso de Recuperaci&oacute;n fallido.'],
    'faltaActivacion'           => ['warning', '! Ha ocurrido un problema !', 'Su acceso a&uacute;n est&aacute; inactivo. Contacte a Soporte.'],
    'errorLogin'                => ['error', '! Ha ocurrido un problema !', 'Error al Iniciar Sesi&oacute;n. Reintente.'],
    'errorSesion'               => ['error', '! Ha ocurrido un problema !', 'Error al cargar datos de usuario.'],
    'errorCambioPassword'       => ['error', '! Ha ocurrido un problema !', 'No se pudo cambiar el Password.'],
    'tipoUsuarioNoPermitido'    => ['error', '! Ha ocurrido un problema !', 'No posee autorizaci&oacute;n para esta interfaz.']
];

foreach ($alertas as $key => [$tipo, $titulo, $mensaje]) {
    if (isset($_GET[$key])) mostrarAlerta($tipo, $titulo, $mensaje);
}
?>

<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border" id="form-login-sdo">
                <div class="card-header text-center">
                    <h3><b>Ingreso a Plataforma - SDO</b></h3>
                    <br>
                    <img src="../dist/img/controlCent-HBQP-80.png" alt="Logo" style="filter: drop-shadow(0 0 12px #007bff);">
                </div>

                <div class="card-body d-flex flex-column align-items-center">
                    <form action="../sdo-funciones/login.php" method="post">
                        <div class="input-group mb-3">
                            <input type="text" required name="usuarioRegistrado" class="form-control" placeholder="Usuario">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" required name="passwordRegistrado" id="passwordRegistrado" class="form-control" placeholder="Contrase&ntilde;a">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()"></span>
                                </div>
                            </div>
                        </div>

                        <div class="social-auth-links text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                            <a href="password_usuario.php" class="btn btn-block btn-danger">Recuperar o Cambiar Contrase&ntilde;a</a>
                            <a href="plataforma_index.php" class="btn btn-block btn-warning">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS scripts -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>

<script>
    function mostrarContrasena() {
        const passInput = document.getElementById("passwordRegistrado");
        const ojoIcon = document.getElementById("ojo");
        if (passInput.type === "password") {
            passInput.type = "text";
            ojoIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = "password";
            ojoIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
