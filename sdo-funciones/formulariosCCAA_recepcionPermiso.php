<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$now        = date('Y-m-d H:i:s');
$nowFormat  = date('d-m-Y H:i', strtotime($now));
$horaFormat = date('H:i', strtotime($now));
$nowFormat2 = fechaEspañol2(strtotime($now)); // fecha en español

// 🔹 Usuario actual
$userUsuario = $_SESSION['userUsuario'] ?? '';
$idUsuario   = $_SESSION['idUsuario'] ?? 0;
$nomUsuario  = $_SESSION['nomUsuario'] ?? '';
$rolUsuario  = $_SESSION['rolUsuario'] ?? '';

// 🔹 Rol a ruta
$mapaRutas = [
    "personal"        => "sdo-personal",
    "administrativo"  => "sdo-admtec",
    "bodega pañol"    => "sdo-bodega-panol",
    "jefe tecnico"    => "sdo-jefestecnicos",
    "administrador"   => "sdo-administrador",
    "directiva"       => "sdo-directiva",
    "oficina tecnica" => "sdo-oficinaTecnica"
];

$rolUsuario2 = strtolower($rolUsuario);
$ruta = $mapaRutas[$rolUsuario2] ?? null;

// 🔹 Función para redirigir con mensaje
function redirigir($ruta, $msg) {
    header("Location: ../$ruta/formulariosCCAA_administracion_2.php?$msg");
    exit;
}

if (!$ruta) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

if (!empty($_POST['idFormRegAcceso']) && !empty($_POST['nomCreador'])) {
    $idFormRegAcceso = (int) $_POST['idFormRegAcceso'];
    $nomCreador      = trim($_POST['nomCreador']);
    $nomCreador2     = mb_convert_case($nomCreador, MB_CASE_TITLE, 'UTF-8');

    if ($idFormRegAcceso > 0) {
        // 🔹 Actualizar recepción
        $stmt = $conexion->prepare("
            UPDATE formularios_registro 
            SET fechaRecepcionFormRegAcceso = ? 
            WHERE idFormRegAcceso = ?
        ");
        $stmt->bind_param("si", $now, $idFormRegAcceso);
        $resRecepcion = $stmt->execute();
        $stmt->close();

        if ($resRecepcion) {
            $getFolio = getFolioFormRegAcceso($idFormRegAcceso);

            $observacion = "$nomCreador2 recepciona Solicitud de Permisos a Funcionario(s) con Fecha: $nowFormat2 a las $horaFormat hrs.";

            // 🔹 Insertar observación
            $stmt2 = $conexion->prepare("
                INSERT INTO formularios_observaciones 
                (detalleObs, fechaObs, creador, idFormRegAcceso, idFormFuncCCAA, folioFormRegAcceso) 
                VALUES (?, ?, ?, ?, 0, ?)
            ");
            $stmt2->bind_param("sssds", $observacion, $now, $nomCreador2, $idFormRegAcceso, $getFolio);
            $resObs = $stmt2->execute();
            $stmt2->close();

            if ($resObs) {
                redirigir($ruta, "exitoRecepcion=true");
            } else {
                redirigir($ruta, "recepcionadoSinObservacion=true");
            }
        } else {
            redirigir($ruta, "errorRecepcion=true");
        }
    } else {
        redirigir($ruta, "errorRecepcion=true");
    }
} else {
    redirigir($ruta, "errorRecepcion=true");
}
