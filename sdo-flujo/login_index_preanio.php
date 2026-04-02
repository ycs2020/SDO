<?php
require_once('../sdo-funciones/funciones.php');
require_once('../sdo-funciones/conexion.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$anioActual  = (int)date('Y');
$anioDespues = $anioActual + 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú de Inicio - SDO - Año Nuevo</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>

<body class="hold-transition login-page"
        style="background-image:url(../dist/img/Hospital.png); background-size:cover; background-position:center;">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="SDO">
</div>

<?php
$alertas = [
    'noExisteUsuario' => ['error', 'Usuario no existe. Reintente.'],
    'cerrarSesion'   => ['success', 'Su sesión ha finalizado con éxito.'],
    'exitoSolicitarAcceso' => ['success', 'Solicitud enviada con éxito.'],
    'errorLogin'     => ['error', 'Error al iniciar sesión. Reintente.'],
    'errorSesion'    => ['error', 'Error al cargar datos de usuario.'],
];

foreach ($alertas as $key => $config) {
    if (isset($_GET[$key])) {
        echo "<script>
            Swal.fire('Informativo','{$config[1]}','{$config[0]}');
        </script>";
    }
}
?>

<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3><b>Ingreso a Plataforma SDO</b></h3>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <img src="../dist/img/AnioNuevo/controlCent-HBQP-200.png">
                        </div>
                        <div class="col-md-6">
                            <img src="../dist/img/AnioNuevo/MovingFile.gif" style="width:100%">
                            <h6 class="mt-2 font-weight-bold">
                                Finalizando Procesos <?= $anioActual ?>
                            </h6>
                            <h6 id="demoAnio" class="font-weight-bold"></h6>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php


                    $ipPermitida = $_SERVER['REMOTE_ADDR'];

                    $enlaceSDO = '../sdo-funciones/login.php';

                    if($ipPermitida == '10.69.250.151'){
                        $enlaceSDO = '../sdo-funciones/login_2.php';
                    }
                    ?>
                    <form action="<?php echo $enlaceSDO; ?>" method="post" id="loginForm">

                        <div class="input-group mb-3">
                            <input type="text" name="usuarioRegistrado" class="form-control" placeholder="Usuario" required>
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" id="passwordRegistrado" name="passwordRegistrado"
                                    class="form-control" placeholder="Contraseña" required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="mostrarContrasena()">
                                    <i class="fas fa-eye" id="ojo"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" id="btnIngresar" class="btn btn-primary btn-block">
                            Ingresar
                        </button>

                        <a href="password_usuario.php" class="btn btn-danger btn-block mt-2">
                            Recuperar o Cambiar Contraseña
                        </a>
                        <a href="plataforma_index.php" class="btn btn-warning btn-block">
                            Volver
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
    /* ========= COUNTDOWN ========= */
    const anioDespues = <?= $anioDespues ?>;
    const targetDate = new Date("<?= $anioDespues ?>-01-01T00:00:00-03:00").getTime();
    const output = document.getElementById("demoAnio");

    function plural(v, s, p) {
        return v + " " + (v === 1 ? s : p);
    }

    const timer = setInterval(() => {
        const now = Date.now();
        let distance = targetDate - now;

        if (distance <= 0) {
            clearInterval(timer);
            output.innerHTML = "¡Feliz Año Nuevo " + anioDespues + " en Chile! 🎉";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((distance / (1000 * 60)) % 60);
        const seconds = Math.floor((distance / 1000) % 60);

        output.innerHTML =
            "En " +
            plural(days, "día", "días") + ", " +
            plural(hours, "hora", "horas") + ", " +
            plural(minutes, "minuto", "minutos") + " y " +
            plural(seconds, "segundo", "segundos") + ".";
    }, 1000);

    /* ========= PASSWORD ========= */
    function mostrarContrasena() {
        const input = document.getElementById("passwordRegistrado");
        const icon = document.getElementById("ojo");
        input.type = input.type === "password" ? "text" : "password";
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    }

    /* ========= BOTÓN CARGA ========= */
    document.getElementById("loginForm").addEventListener("submit", () => {
        const btn = document.getElementById("btnIngresar");
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    });
</script>

</body>
</html>
