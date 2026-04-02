<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() === PHP_SESSION_NONE) {
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
    <title>Ingreso - Plataforma SDO</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <style>
        body {
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url('../dist/img/Hospital.png');
            background-size: cover;
            background-position: center;
            z-index: -1;
            transition: filter 0.4s ease;
        }

        body.dark-mode::before {
            filter: brightness(0.15);
        }

        #form-login-sdo {
            border-radius: 10px;
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, 0.9);
        }

        body.dark-mode #form-login-sdo {
            background: rgba(25, 25, 30, 0.95);
            color: #fff;
        }

        body.dark-mode input,
        body.dark-mode .form-control {
            background-color: #2c2f3a;
            color: #fff;
            border-color: #555;
        }

        body.dark-mode .input-group-text {
            background-color: #343a40;
            color: #fff;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
        }

        #btnToggleDark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            padding: 10px 14px;
            background: #343a40;
            color: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0,0,0,.5);
        }
    </style>
</head>

<body class="hold-transition login-page">






<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border" id="form-login-sdo">
                <div class="card-header text-center">
                    <h4><b>Ingreso a Plataforma SDO</b></h4>
                    <img src="../dist/img/controlCent-HBQP-80.png" class="mt-2">
                </div>

                <div class="card-body">
                    <?php $enlaceSDO = '../sdo-funciones/login_2.php'; ?>
                    <form action="<?= $enlaceSDO ?>" method="post" id="loginForm" autocomplete="off">
                        <div class="input-group mb-3">
                            <label class="sr-only">Usuario</label>
                            <input type="text" name="usuarioRegistrado" class="form-control" placeholder="Usuario" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="sr-only">Contraseña</label>
                            <input type="password" name="passwordRegistrado" id="passwordRegistrado" class="form-control" placeholder="Contraseña" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-eye" id="ojo" onclick="mostrarContrasena()" style="cursor:pointer"></span>
                                </div>
                            </div>
                        </div>

                        <button id="btnIngresar" class="btn btn-primary btn-block">Ingresar</button>
                        <a href="password_usuario.php" class="btn btn-danger btn-block mt-2">Recuperar / Cambiar Contraseña</a>
                        <a href="plataforma_index.php" class="btn btn-warning btn-block mt-2">Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botón modo oscuro -->
<button id="btnToggleDark">🌙 Modo Oscuro</button>

<!-- JS -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<?php
$notificacionesSweet = [

    'noExisteUsuario' => ['Usuario No Existe. Reintente.', 'error'],
    'cerrarSesion' => ['Su Sesión ha finalizado con éxito.', 'success'],
    'exitoSolicitarAcceso' => ['Su Solicitud ha sido enviada con éxito.', 'success'],
    'exitoEnvioRecuperacion' => ['Solicitud de recuperación enviada con éxito.', 'success'],
    'errorEnvioRecuperacion' => ['Error al enviar solicitud.', 'warning'],
    'exitoRestauracionPassword' => ['Contraseña restaurada con éxito.', 'success'],
    'errorRestauracionPassword' => ['Error al restaurar contraseña.', 'warning'],
    'errorRecuperacion' => ['Error en proceso de recuperación.', 'warning'],
    'faltaActivacion' => ['Acceso inactivo. Contacte a soporte.', 'warning'],
    'errorLogin' => ['Error al iniciar sesión.', 'error'],
    'errorSesion' => ['Error al cargar sesión.', 'error'],
    'errorCambioPassword' => ['Error al cambiar contraseña.', 'error'],
    'tipoUsuarioNoPermitido' => ['No posee autorización.', 'error'],
];

include_once "../sdo-includes/sweetalert_dark_mode.php";
?>

<script>
    // Activar dark-mode si estaba previamente activado
    if (localStorage.getItem("darkMode") === "on") {
        document.body.classList.add("dark-mode");
    }

    // Mostrar/ocultar contraseña
    function mostrarContrasena() {
        const p = document.getElementById("passwordRegistrado");
        const i = document.getElementById("ojo");
        p.type = p.type === "password" ? "text" : "password";
        i.classList.toggle("fa-eye");
        i.classList.toggle("fa-eye-slash");
    }

    // Deshabilitar botón al enviar
    document.getElementById("loginForm").addEventListener("submit", () => {
        const b = document.getElementById("btnIngresar");
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    });

    // Modo oscuro toggle
    const btn = document.getElementById("btnToggleDark");
    if (btn) {
        if (localStorage.getItem("darkMode") === "on") {
            document.body.classList.add("dark-mode");
            btn.innerText = "☀️ Modo Claro";
        }

        btn.onclick = () => {
            document.body.classList.toggle("dark-mode");
            const activo = document.body.classList.contains("dark-mode");
            localStorage.setItem("darkMode", activo ? "on" : "off");
            btn.innerText = activo ? "☀️ Modo Claro" : "🌙 Modo Oscuro";
        };
    }
</script>

</body>
</html>
