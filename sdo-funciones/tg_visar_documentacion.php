<?php
include_once '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');

$idTG = $_POST['idTG'] ?? null;
$nombreVisador = $_POST['usuario'] ?? null;

if (!$idTG || !is_numeric($idTG)) {
    echo json_encode(["status" => "error", "msg" => "ID no válido"]);
    exit;
}

if (!$nombreVisador) {
    echo json_encode(["status" => "error", "msg" => "No se recibió nombre del usuario"]);
    exit;
}

$conexion->begin_transaction();

try {

    // Obtener estado TG + folio + tarea
    $stmt = $conexion->prepare("SELECT isVisado, folioTG, idTareaProgramada FROM trabajos_general WHERE idTG = ?");
    $stmt->bind_param("i", $idTG);
    $stmt->execute();
    $stmt->bind_result($isVisadoTG, $folioTG, $idTareaProgramada);
    $stmt->fetch();
    $stmt->close();

    // Visar trabajos_general
    if ($isVisadoTG == 0) {
        $stmt = $conexion->prepare("UPDATE trabajos_general 
            SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() 
            WHERE idTG = ?");
        $stmt->bind_param("si", $nombreVisador, $idTG);
        $stmt->execute();
        $stmt->close();
    }

    // Visar Hojas Técnicas (ordenes)
    $stmt = $conexion->prepare("UPDATE ordenes_trabajos_general 
        SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() 
        WHERE noOrdenTrabajo = ? AND isVisado = 0");
    $stmt->bind_param("ss", $nombreVisador, $folioTG);
    $stmt->execute();
    $stmt->close();

    // Tablas relacionadas por idTG
    $tablas = [
        'datos_checklist',
        'datos_checklist_ucas',
        'datos_uma',
        'datos_checklist_logica',
        'datos_checklist_controlador_ecb',
        'datos_tcc_uma',
        'datos_vav',
        'datos_vf'
    ];

    foreach ($tablas as $tabla) {
        $stmt = $conexion->prepare("UPDATE $tabla 
            SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() 
            WHERE idTG = ? AND (isVisado = 0 OR isVisado IS NULL)");
        $stmt->bind_param("si", $nombreVisador, $idTG);
        $stmt->execute();
        $stmt->close();
    }

    // Si tiene tarea programada → cerrar en carta gantt
    if (!empty($idTareaProgramada)) {
        $stmt = $conexion->prepare("UPDATE tareas_carta_gantt 
            SET finRealTareaP = NOW(), porcentajeTareaP = 100, finalizadaTareaP = 1 
            WHERE idTareaP = ?");
        $stmt->bind_param("i", $idTareaProgramada);
        $stmt->execute();
        $stmt->close();
    }

    $conexion->commit();

    echo json_encode(["status" => "ok", "msg" => "Documentación visada correctamente"]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}

$conexion->close();
