<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'] ?? '';
$idUsuario = $_SESSION['idUsuario'] ?? '';
$nomUsuario = $_SESSION['nomUsuario'] ?? '';
$rolUsuario = $_SESSION['rolUsuario'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contraseña - Plataforma SDO</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
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

        #form-cambiar-pass {
            border-radius: 10px;
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, 0.9);
        }

        body.dark-mode #form-cambiar-pass {
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
        body.dark-mode .alert-warning {
            background-color: #4f4c2c;
            border-color: #d39e00;
            color: #f8f9fa;
        }

    </style>
</head>

<body class="hold-transition login-page">

<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border" id="form-cambiar-pass">
                <div class="card-header text-center">
                    <h4><b>Cambiar Contraseña</b></h4>
                    <img src="../dist/img/controlCent-HBQP-80.png" class="mt-2">
                </div>

                <div class="card-body">
                    <form action="../sdo-funciones/password-cambiar.php" method="post" id="formCambioPassword" autocomplete="off">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($userUsuario) ?>">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>

                        <div class="input-group mb-1">
                            <input type="password" name="nuevaPassword" id="nuevaPassword" class="form-control"
                                    placeholder="Nueva Contraseña" required minlength="8"
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                    title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-eye" id="ojo" style="cursor:pointer"></span></div>
                            </div>
                        </div>

                        <!-- Indicador de requisitos -->
                        <div id="requisitosPassword" class="mt-2 text-sm" style="font-size: 0.9em;">
                            <ul class="pl-4 mb-0">
                                <li id="req-length">❌ Mínimo 8 caracteres</li>
                                <li id="req-uppercase">❌ Al menos una <strong>mayúscula</strong></li>
                                <li id="req-lowercase">❌ Al menos una <strong>minúscula</strong></li>
                                <li id="req-number">❌ Al menos un <strong>número</strong></li>
                                <li id="req-symbol">❌ Al menos un <strong>símbolo</strong> (@$!%*?&)</li>
                                <li id="req-temporal">❌ No debe ser una <strong>clave temporal</strong></li>
                            </ul>
                        </div>

                        <input type="hidden" name="idUsuario" value="<?= htmlspecialchars($idUsuario) ?>">
                        <input type="hidden" name="rolUsuario" value="<?= htmlspecialchars($rolUsuario) ?>">

                        <button type="submit" class="btn btn-primary btn-block mt-3" id="btnCambiar" disabled>Cambiar Contraseña</button>
                        <a href="login_index.php" class="btn btn-danger btn-block mt-2">Volver</a>
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
<script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>

<script>
    // Modo oscuro persistente
    if (localStorage.getItem("darkMode") === "on") {
        document.body.classList.add("dark-mode");
        document.getElementById("btnToggleDark").innerText = "☀️ Modo Claro";
    }

    document.getElementById("btnToggleDark").addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        const isDark = document.body.classList.contains("dark-mode");
        localStorage.setItem("darkMode", isDark ? "on" : "off");
        document.getElementById("btnToggleDark").innerText = isDark ? "☀️ Modo Claro" : "🌙 Modo Oscuro";
    });

    // Mostrar/ocultar contraseña
    document.getElementById("ojo").addEventListener("click", () => {
        const campo = document.getElementById("nuevaPassword");
        campo.type = campo.type === "password" ? "text" : "password";
        document.getElementById("ojo").classList.toggle("fa-eye-slash");
    });

    const inputPass = document.getElementById("nuevaPassword");
    const btnCambiar = document.getElementById("btnCambiar");

    inputPass.addEventListener("input", function () {
        const pass = this.value;
        const regexClaveTemporal = /^@sdo20[0-9][0-9]$/i;

        // Elementos
        const reqLength = document.getElementById("req-length");
        const reqUpper = document.getElementById("req-uppercase");
        const reqLower = document.getElementById("req-lowercase");
        const reqNumber = document.getElementById("req-number");
        const reqSymbol = document.getElementById("req-symbol");
        const reqTemporal = document.getElementById("req-temporal");

        // Evaluaciones
        const tieneLongitud = pass.length >= 8;
        const tieneMayus = /[A-Z]/.test(pass);
        const tieneMinus = /[a-z]/.test(pass);
        const tieneNumero = /\d/.test(pass);
        const tieneSimbolo = /[@$!%*?&]/.test(pass);
        const esTemporal = regexClaveTemporal.test(pass);

        // Actualizar UI
        reqLength.innerHTML   = tieneLongitud ? '✔️ Mínimo 8 caracteres' : '❌ Mínimo 8 caracteres';
        reqUpper.innerHTML    = tieneMayus ? '✔️ Al menos una <strong>mayúscula</strong>' : '❌ Al menos una <strong>mayúscula</strong>';
        reqLower.innerHTML    = tieneMinus ? '✔️ Al menos una <strong>minúscula</strong>' : '❌ Al menos una <strong>minúscula</strong>';
        reqNumber.innerHTML   = tieneNumero ? '✔️ Al menos un <strong>número</strong>' : '❌ Al menos un <strong>número</strong>';
        reqSymbol.innerHTML   = tieneSimbolo ? '✔️ Al menos un <strong>símbolo</strong> ( @ $ ! % * ? & )' : '❌ Al menos un <strong>símbolo</strong> ( @ $ ! % * ? & )';
        reqTemporal.innerHTML = !esTemporal ? '✔️ No es una <strong>clave temporal</strong>' : '❌ No debe ser una <strong>clave temporal</strong>';

        // Habilitar botón solo si cumple todos
        const cumpleTodo = tieneLongitud && tieneMayus && tieneMinus && tieneNumero && tieneSimbolo && !esTemporal;
        btnCambiar.disabled = !cumpleTodo;
    });

    // Validación final por seguridad
    document.getElementById("formCambioPassword").addEventListener("submit", function(e) {
        const pass = inputPass.value.trim();
        const regexFuerte = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        const clavesTemporales = ['@Sdo2025', '@SDO2025', '@sdo2025', '@Sdo2026', '@SDO2026', '@sdo2026'];

        if (clavesTemporales.includes(pass)) {
            e.preventDefault();
            Swal.fire('Clave Temporal', 'Debe ingresar una nueva contraseña segura.', 'warning');
        } else if (!regexFuerte.test(pass)) {
            e.preventDefault();
            Swal.fire('Contraseña Débil', 'Debe tener mayúscula, minúscula, número y símbolo.', 'warning');
        }
    });

</script>
</body>
</html>
