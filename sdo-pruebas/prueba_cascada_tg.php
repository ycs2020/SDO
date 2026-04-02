<?php
include_once '../sdo-funciones/conexion.php';

date_default_timezone_set('America/Santiago');
$idTG = 2718;
$nombreVisador = 'Yorgo Casimis Subiabre';

if (!$idTG || !is_numeric($idTG)) {
    die("idTG no válido.");
}

$conexion->begin_transaction();

try {
    echo "<h4>Marcando como visado para idTG <strong>$idTG</strong></h4><ul>";

    // 1. trabajos_general
    $stmt = $conexion->prepare("SELECT isVisado, folioTG, idTareaProgramada FROM trabajos_general WHERE idTG = ?");
    $stmt->bind_param("i", $idTG);
    $stmt->execute();
    $stmt->bind_result($isVisadoTG, $folioTG, $idTareaProgramada);
    $stmt->fetch();
    $stmt->close();

    // Mostrar folio e idTareaProgramada si existe
    echo "<li><strong>folioTG:</strong> $folioTG</li>";
    if (!empty($idTareaProgramada) && is_numeric($idTareaProgramada)) {
        echo "<li><strong>idTareaProgramada:</strong> $idTareaProgramada</li>";
    }

    if ($isVisadoTG == 0) {
        $stmt = $conexion->prepare("UPDATE trabajos_general SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() WHERE idTG = ?");
        $stmt->bind_param("si", $nombreVisador, $idTG);
        $stmt->execute();
        $stmt->close();
        echo "<li><strong>trabajos_general:</strong> Actualizado</li>";
    } else {
        echo "<li><strong>trabajos_general:</strong> Ya estaba visado</li>";
    }

    // 2. ordenes_trabajos_general (por folioTG)
    $stmt = $conexion->prepare("UPDATE ordenes_trabajos_general SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() WHERE noOrdenTrabajo = ? AND isVisado = 0");
    $stmt->bind_param("ss", $nombreVisador, $folioTG);
    $stmt->execute();
    $afectadas = $stmt->affected_rows;
    echo "<li><strong>ordenes_trabajos_general:</strong> $afectadas filas actualizadas</li>";
    $stmt->close();

    // 3. Tablas que usan idTG
    $tablas = [
        'datos_checklist' => 'id',
        'datos_checklist_ucas' => 'id',
        'datos_uma' => 'idUma',
        'datos_checklist_logica' => 'id',
        'datos_checklist_controlador_ecb' => 'id',
        'datos_tcc_uma' => 'id',
        'datos_vav' => 'idVav',
        'datos_vf' => 'id'
    ];

    foreach ($tablas as $tabla => $campoId) {
        $sql = "UPDATE $tabla SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() WHERE idTG = ? AND (isVisado = 0 OR isVisado IS NULL)";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            echo "<li><strong>$tabla:</strong> Error preparando la consulta: " . $conexion->error . "</li>";
            continue;
        }

        $stmt->bind_param("si", $nombreVisador, $idTG);
        if ($stmt->execute()) {
            $afectadas = $stmt->affected_rows;
            echo "<li><strong>$tabla:</strong> $afectadas filas actualizadas</li>";
        } else {
            echo "<li><strong>$tabla:</strong> Error al ejecutar: " . $stmt->error . "</li>";
        }
        $stmt->close();
    }

    // 4. Actualizar tareas_carta_gantt si existe idTareaProgramada
    if (!empty($idTareaProgramada) && is_numeric($idTareaProgramada)) {
        $finPlanTareaPMod = date('Y-m-d H:i:s');

        $stmt = $conexion->prepare("UPDATE tareas_carta_gantt SET finRealTareaP = ?, porcentajeTareaP = 100, finalizadaTareaP = 1 WHERE idTareaP = ?");
        $stmt->bind_param("si", $finPlanTareaPMod, $idTareaProgramada);
        if ($stmt->execute()) {
            echo "<li><strong>tareas_carta_gantt:</strong> Tarea marcada como finalizada</li>";
        } else {
            echo "<li><strong>tareas_carta_gantt:</strong> Error al ejecutar: " . $stmt->error . "</li>";
        }
        $stmt->close();
    }

    $conexion->commit();

    echo "</ul><hr><strong>Resultado:</strong> Todo lo no visado fue actualizado correctamente.";
} catch (Exception $e) {
    $conexion->rollback();
    echo "<p>Error durante el proceso: " . $e->getMessage() . "</p>";
}

$conexion->close();
?>
