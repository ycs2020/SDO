<?php
include_once 'conexion.php';
include_once 'funciones.php';

header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION['userUsuario'], $_SESSION['idUsuario'], $_SESSION['nomUsuario'], $_SESSION['rolUsuario'], $_SESSION['idDepartamento'])
) {
    echo json_encode([
        'status' => false,
        'message' => 'Sesión no válida o expirada.'
    ]);
    exit;
}

// VARIABLES DE SESSION
$userUsuario     = $_SESSION['userUsuario'];
$idUsuario       = (int) $_SESSION['idUsuario'];
$nomUsuario      = $_SESSION['nomUsuario'];
$rolUsuario      = $_SESSION['rolUsuario'];
$rolUsuario2     = strtolower(trim($rolUsuario));
$idDepartamento  = (int) $_SESSION['idDepartamento'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

if (
    !isset($_POST['idEquipo'], $_POST['idCategoria'], $_POST['opcionEstado'], $_POST['observaciones'], $_POST['nombreEquipo'])
) {
    echo json_encode([
        'status' => false,
        'message' => 'Faltan datos obligatorios.'
    ]);
    exit;
}

$idEquipo       = (int) $_POST['idEquipo'];
$idCategoria    = (int) $_POST['idCategoria'];
$opcionEstado   = trim($_POST['opcionEstado']);
$observaciones  = trim($_POST['observaciones']);
$nombreEquipo   = trim($_POST['nombreEquipo']);

if ($idEquipo <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'ID de equipo inválido.'
    ]);
    exit;
}

if ($observaciones === '') {
    echo json_encode([
        'status' => false,
        'message' => 'Debe ingresar una observación.'
    ]);
    exit;
}

$opcionEstado  = mysqli_real_escape_string($conexion, $opcionEstado);
$observaciones = mysqli_real_escape_string($conexion, $observaciones);
$nombreEquipo  = mysqli_real_escape_string($conexion, $nombreEquipo);

mysqli_begin_transaction($conexion);

try {

    // 1. Actualizar estado principal
    $sqlUpdate = "UPDATE equipamiento_hbqp 
                  SET estadoEquipo = '$opcionEstado' 
                  WHERE id = $idEquipo";
    $resUpdate = mysqli_query($conexion, $sqlUpdate);

    if (!$resUpdate) {
        throw new Exception('No se pudo actualizar el estado del equipo.');
    }

    // 2. Eliminar observación previa si existe
    $existeRegistroObservacion = compruebaRegistroObservacion($idEquipo);

    if ($existeRegistroObservacion == "Si") {
        $sqlEliminaObs = "DELETE FROM historial_cambios_equipos_observacion WHERE idEquipo = $idEquipo";
        $resEliminaObs = mysqli_query($conexion, $sqlEliminaObs);

        if (!$resEliminaObs) {
            throw new Exception('No se pudo limpiar el historial de observaciones.');
        }
    }

    // 3. Eliminar falla previa si existe
    $existeRegistroFalla = compruebaRegistroFalla($idEquipo);

    if ($existeRegistroFalla == "Si") {
        $sqlEliminaFalla = "DELETE FROM historial_cambios_equipos_falla WHERE idEquipo = $idEquipo";
        $resEliminaFalla = mysqli_query($conexion, $sqlEliminaFalla);

        if (!$resEliminaFalla) {
            throw new Exception('No se pudo limpiar el historial de fallas.');
        }
    }

    // 4. Si queda fuera de servicio, insertar en historial de falla
    if ($opcionEstado === 'Fuera de Servicio') {
        $sqlInsertFalla = "INSERT INTO historial_cambios_equipos_falla 
            (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nomEquipo) 
            VALUES 
            ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
        $resInsertFalla = mysqli_query($conexion, $sqlInsertFalla);

        if (!$resInsertFalla) {
            throw new Exception('No se pudo registrar el historial de falla.');
        }
    }

    // 5. Insertar historial principal
    $sqlInsertHistorial = "INSERT INTO historial_cambios_equipos 
        (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nombreEquipo) 
        VALUES 
        ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
    $resInsertHistorial = mysqli_query($conexion, $sqlInsertHistorial);

    if (!$resInsertHistorial) {
        throw new Exception('No se pudo registrar el historial de cambios.');
    }

    // 6. Insertar respaldo
    $sqlInsertRespaldo = "INSERT INTO historial_cambios_equipos_respaldo 
        (estado, observaciones, idUsuarioCambia, tipoUsuario, idEquipo, idCategoria, nombreEquipo) 
        VALUES 
        ('$opcionEstado', '$observaciones', $idUsuario, '$rolUsuario2', $idEquipo, $idCategoria, '$nombreEquipo')";
    $resInsertRespaldo = mysqli_query($conexion, $sqlInsertRespaldo);

    if (!$resInsertRespaldo) {
        throw new Exception('No se pudo registrar el respaldo del cambio.');
    }

    mysqli_commit($conexion);

    echo json_encode([
        'status' => true,
        'message' => 'Estado actualizado correctamente.'
    ]);
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexion);

    echo json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}