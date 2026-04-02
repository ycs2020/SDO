<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}
session_start();

/* ===================== FECHAS ===================== */

$hoy = date('m-d');
$anioActual = date('Y');
$anioDespues = $anioActual + 1;

$modoFiestasPatrias = ($hoy >= '09-01' && $hoy <= '09-21');
$modoNavidad = ($hoy >= '12-24' && $hoy <= '12-25');
$modoAnioNuevo = ($hoy === '01-01'); // puedes forzarlo en true para pruebas
$modoPreAnioNuevo = ($hoy === '12-30' || $hoy === '12-31');


/* ===================== FUNCIONES FESTIVIDADES ===================== */

function decoracionNavidad()
{
    ?>
    <link rel="stylesheet" href="../dist/css/navidad.css">

    <div class="guirnalda guirnalda-top"></div>
    <div class="guirnalda guirnalda-bottom"></div>

    <div class="overlay">
        <img src="../dist/img/Navidad/Nieve%202.gif" width="100%">
    </div>

    <style>
        body {
            cursor: url('../dist/img/Navidad/santa-claus-24.png'), auto;
        }
    </style>
    <?php
}

function decoracionFiestasPatrias()
{
    ?>
    <div class="bandchile bandchile-top"></div>
    <div class="bandchile bandchile-bottom"></div>
    <?php
}

function decoracionAnioNuevo()
{
    ?>
    <link rel="stylesheet" href="../dist/css/anionuevo_css.css">

    <style>
        body {
            animation: shake 5s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% {
                transform: translate(0, 0);
            }
            25%, 75% {
                transform: translate(-5px, 0);
            }
            50% {
                transform: translate(5px, 0);
            }
        }
    </style>

    <?php for ($i = 0; $i < 12; $i++): ?>
    <div class="firework"></div>
<?php endfor; ?>
    <?php
}

function logosFestivos($modoFiestasPatrias, $modoAnioNuevo)
{

    if ($modoFiestasPatrias) {
        echo '<div class="col-md-6 col-12">';
        echo '<img src="../dist/img/fiestas_patrias_chile.png" style="width:100%" >';
        echo '</div>';
    }

    if ($modoAnioNuevo) {

        echo '<div class="col-md-6 col-12">';
        echo '<img src="../dist/img/AnioNuevo/anio-nuevo.png" width="140">';
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ingreso - Plataforma SDO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Source Sans Pro';
            overflow: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: url('../dist/img/Hospital.png') center/cover;
            filter: blur(6px) brightness(0.8);
            z-index: -2;
        }

        body.dark-mode::before {
            filter: blur(6px) brightness(0.25);
        }

        .login-wrapper {
            background: rgba(255, 255, 255, .95);
            border-radius: 18px;
            box-shadow: 0 0 40px rgba(0, 0, 0, .3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }

        .login-row {
            display: flex;
        }

        .login-left {
            width: 40%;
            background: linear-gradient(135deg, #0056b3, #003366);
            color: #fff;
            padding: 50px;
            text-align: center;
        }

        .login-right {
            width: 60%;
            padding: 50px;
        }

        .toggle-dark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #222;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 30px;
            cursor: pointer;
            z-index: 200;
        }

        body.dark-mode .login-wrapper {
            background: rgba(20, 20, 25, .92);
            color: #fff;
        }

        body.dark-mode .form-control {
            background: #2a2d36;
            color: #fff;
            border-color: #555;
        }

        /* NAVIDAD */
        .guirnalda {
            position: fixed;
            width: 100%;
            height: 70px;
            background: url('../dist/img/navidad1.gif') repeat-x;
            background-size: auto 70px;
            z-index: 10;
        }

        .guirnalda-top {
            top: 0;
        }

        .guirnalda-bottom {
            bottom: 0;
            transform: rotate(180deg);
        }

        /* FIESTAS PATRIAS */
        .bandchile {
            position: fixed;
            width: 100%;
            height: 40px;
            background: url('../sdo-flujo/sdo-imagenes/chile.png') repeat-x;
            background-size: auto 40px;
            z-index: 10;
        }

        .bandchile-top {
            top: 0;
        }

        .bandchile-bottom {
            bottom: 0;
        }

        /* 📱 RESPONSIVO MÓVIL */
        @media (max-width: 768px) {

            body {
                align-items: flex-start;
                padding-top: 10px;
            }

            .login-row {
                flex-direction: column; /* azul arriba */
            }

            .login-left,
            .login-right {
                width: 100%;
                padding: 20px; /* menos padding */
            }

            .login-wrapper {
                margin: 10px;
                border-radius: 14px;
            }

            /* 🔽 PANEL AZUL MÁS BAJO */
            .login-left {
                padding: 20px 15px;
            }

            /* 🔽 LOGO MÁS PEQUEÑO */
            .login-left img {
                max-width: 160px;
                height: auto;
                margin-bottom: 8px;
            }

            /* 🔽 TEXTOS MÁS COMPACTOS */
            .login-left h2 {
                font-size: 1.2rem;
                margin-top: 10px;
            }

            .login-left p {
                font-size: 0.85rem;
                margin-bottom: 0;
            }

            .toggle-dark {
                right: 10px;
                bottom: 10px;
                font-size: 13px;
                padding: 6px 12px;
            }
        }


    </style>

</head>

<body>


<!-- ===================== DECORACIONES ===================== -->

<?php if ($modoNavidad) decoracionNavidad(); ?>
<?php if ($modoFiestasPatrias) decoracionFiestasPatrias(); ?>
<?php if ($modoAnioNuevo) decoracionAnioNuevo(); ?>

<!-- ===================== LOGIN ===================== -->

<div class="login-wrapper">
    <div class="login-row">

        <!-- PANEL IZQUIERDO -->
        <div class="login-left d-flex flex-column justify-content-center align-items-center text-center">

            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 col-12">
                    <img src="../dist/img/controlCent-HBQP-2.png" style="width: 100%" alt="Logo HBQP">
                </div>

                <?php logosFestivos($modoFiestasPatrias, $modoAnioNuevo); ?>

                <div class="col-12">

                    <h2 class="mt-4 font-weight-bold">Plataforma SDO</h2>
                    <p>Hospital Biprovincial Quillota Petorca</p>
                </div>

            </div>


        </div>

        <!-- PANEL DERECHO -->
        <div class="login-right">
            <form action="../sdo-funciones/login_2.php" method="post" id="loginForm">

                <?php
                if ($modoPreAnioNuevo) {
                    ?>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-6 col-12 text-center">

                            <img src="../dist/img/AnioNuevo/MovingFile.gif" style="width:100%">
                            <h6 class="mt-2 font-weight-bold">
                                Finalizando Procesos <?= $anioActual ?>
                            </h6>

                        </div>
                        <div class="col-12 text-center mt-4">
                            <h6 id="demoAnio" class="font-weight-bold"></h6>

                        </div>
                    </div>

                    <hr>
                    <?php
                }
                ?>


                <div class="form-group">
                    <label>Usuario</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input type="text" name="usuarioRegistrado" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" id="password" name="passwordRegistrado" class="form-control" required>
                        <div class="input-group-append">
                            <span class="input-group-text" style="cursor:pointer" onclick="togglePassword()">
                                <i class="fas fa-eye" id="ojo"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button id="btnIngresar" class="btn btn-primary btn-block mt-4">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>

                <a href="password_usuario.php" class="btn btn-outline-danger btn-block mt-2">
                    <i class="fas fa-key"></i> Recuperar / Cambiar Contraseña
                </a>

            </form>
        </div>

    </div>
</div>

<!-- BOTÓN MODO OSCURO -->
<button class="toggle-dark" id="btnDark">🌙 Modo Oscuro</button>

<script>
    const btnDark = document.getElementById("btnDark");

    if (localStorage.getItem("darkMode") === "on") {
        document.body.classList.add("dark-mode");
        btnDark.innerText = "☀️ Modo Claro";
    }

    btnDark.onclick = () => {
        document.body.classList.toggle("dark-mode");
        const activo = document.body.classList.contains("dark-mode");
        localStorage.setItem("darkMode", activo ? "on" : "off");
        btnDark.innerText = activo ? "☀️ Modo Claro" : "🌙 Modo Oscuro";
    };

    function togglePassword() {
        const p = document.getElementById("password");
        const i = document.getElementById("ojo");
        p.type = p.type === "password" ? "text" : "password";
        i.classList.toggle("fa-eye");
        i.classList.toggle("fa-eye-slash");
    }

    document.getElementById("loginForm").addEventListener("submit", () => {
        const b = document.getElementById("btnIngresar");
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    });

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

            // Recargar la página de inmediato
            location.reload();
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


</script>


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
    'errorCambioPassword' => ['Error al cambiar contraseña.',
        'error'], 'tipoUsuarioNoPermitido' => ['No posee autorización.', 'error'],
];
include_once "../sdo-includes/sweetalert_dark_mode.php"; ?>

</body>
</html>
