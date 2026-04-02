<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'mensaje' => 'Operación no válida'
];

// =========================
// 1️⃣ Recibir y validar datos
// =========================
$idPerfil     = isset($_POST['perfil']) ? (int) $_POST['perfil'] : 0;
$idCredencial = isset($_POST['credencial']) ? (int) $_POST['credencial'] : 0;
$usuario      = trim($_POST['usuario'] ?? '');
$nomPerfil    = trim($_POST['nomPerfil'] ?? '');

// 🔑 Normalizar acción UNA sola vez
$accion = strtoupper(trim($_POST['accion'] ?? ''));

if (
    $idPerfil <= 0 ||
    $idCredencial <= 0 ||
    !in_array($accion, ['AUTORIZA', 'ANULAR'], true) ||
    $usuario === ''
) {
    $response['mensaje'] = 'Datos incompletos o inválidos.';
    echo json_encode($response);
    exit;
}

// =========================
// 2️⃣ Ejecutar acción principal
// =========================
try {

    $conexion->begin_transaction();

    if ($accion === 'AUTORIZA') {

        // 🔎 Evitar duplicados
        $check = $conexion->prepare("
            SELECT idRegistro
            FROM credenciales_rrhh_registro_perfiles
            WHERE idCredencial = ? AND idPerfil = ?
            LIMIT 1
        ");
        if (!$check) {
            throw new Exception($conexion->error);
        }

        $check->bind_param("ii", $idCredencial, $idPerfil);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows === 0) {
            $stmt = $conexion->prepare("
                INSERT INTO credenciales_rrhh_registro_perfiles
                (idCredencial, idPerfil)
                VALUES (?, ?)
            ");
            if (!$stmt) {
                throw new Exception($conexion->error);
            }

            $stmt->bind_param("ii", $idCredencial, $idPerfil);
            $stmt->execute();
            $stmt->close();
        }

    } elseif ($accion === 'ANULAR') {

        $stmt = $conexion->prepare("
            DELETE FROM credenciales_rrhh_registro_perfiles
            WHERE idCredencial = ? AND idPerfil = ?
        ");
        if (!$stmt) {
            throw new Exception($conexion->error);
        }

        $stmt->bind_param("ii", $idCredencial, $idPerfil);
        $stmt->execute();
        $stmt->close();
    }

    // =========================
    // 3️⃣ Obtener observación desde FUNCIÓN CORRECTA
    // =========================
    $mensajeObs = obtenerObservacionPerfilCredencialConn(
        $conexion,
        $idPerfil,
        $idCredencial,
        $accion,
        $nomPerfil
    );

    if ($mensajeObs === 'vacio') {
        throw new Exception('No se pudo generar la observación.');
    }

    // =========================
    // 4️⃣ Registrar observación
    // =========================
    $stmtObs = $conexion->prepare("
        INSERT INTO credenciales_rrhh_observaciones
        (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion)
        VALUES (?, NOW(), ?, 'PERFIL', ?)
    ");
    if (!$stmtObs) {
        throw new Exception($conexion->error);
    }

    $stmtObs->bind_param(
        "iss",
        $idCredencial,
        $usuario,
        $mensajeObs
    );
    $stmtObs->execute();
    $stmtObs->close();

    // =========================
    // 5️⃣ Commit final
    // =========================
    $conexion->commit();

    $response['status']  = 'ok';
    $response['mensaje'] = "Acción \"$accion\" realizada correctamente.";

} catch (Exception $e) {

    $conexion->rollback();
    $response['status']  = 'error';
    $response['mensaje'] = 'Error en la operación.';
    error_log('ERROR perfiles credencial: ' . $e->getMessage());
}

$conexion->close();
echo json_encode($response);
