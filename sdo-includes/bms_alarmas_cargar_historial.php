<?php
// Archivo: tu_servidor.php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['idRegistro']) && $_POST['idRegistro'] > 0) {
    $idRegistro = (int)$_POST['idRegistro'];

    // Consulta SQL optimizada con JOIN
    $sql = "
        SELECT 
            h.*, 
            c.idDepartamento,
            (
                SELECT COUNT(*) 
                FROM bms_alarmas_observacion o 
                WHERE o.idHistorial = h.idHistorial
            ) AS totalObs
        FROM bms_alarmas_historial h
        LEFT JOIN bms_alarmas_categoria c ON h.classHistorial = c.nomCategoria
        WHERE h.idRegistro = ?
        ORDER BY h.fechaHistorial DESC;
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idRegistro);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalCount = $result->num_rows;
    $datos = [];
    $isFirstRow = true;

    while ($row = $result->fetch_assoc()) {
        $idHistorial = $row["idHistorial"];
        $fechaHistorial = $row["fechaHistorial"];
        $mensajeHistorial = $row["mensajeHistorial"];
        $classHistorial = $row["classHistorial"];
        $stateHistorial = $row["stateHistorial"];
        $alarmDataHistorial = trim($row["alarmDataHistorial"]);
        $vacioAlarmData = !empty($alarmDataHistorial) ? 1 : 0;

        // Fecha
        if (!empty($fechaHistorial) && validateTimestamp($fechaHistorial)) {
            $fechaTime = strtotime($fechaHistorial);
            $fechaEscrita = date("d / m / Y", $fechaTime);
            $fechaEscrita2 = date("d/m/Y", $fechaTime);
            $horaEscrita = date('H:i', $fechaTime);
            $fechaCompleta = "$fechaEscrita <br> $horaEscrita hrs.";
            $fechaCompleta2 = "$fechaEscrita2 $horaEscrita hrs.";
        } else {
            $fechaCompleta = 'No Aplica';
            $fechaCompleta2 = 'No Aplica';
        }

        $mensajeHistorial2 = "$fechaCompleta2 -- $mensajeHistorial";

        // Departamento
        $idDepartamento = $row["idDepartamento"];
        if (!empty($idDepartamento) && $idDepartamento > 0) {
            $nombreDepartamento = trim(getNomDepartamento($idDepartamento));
            if ($nombreDepartamento === "Instrumentación") {
                $nombreDepartamento = "Control Centralizado";
            } elseif ($nombreDepartamento === "Inst. Térmicas, Climatización y Refrigeración") {
                $nombreDepartamento = "Climatización";
            }
        } else {
            $nombreDepartamento = "No Aplica";
        }

        // AlarmData texto
        if (!empty($alarmDataHistorial)) {
            $claves = ['alarmValue'];
            $resultados = [];
            foreach ($claves as $clave) {
                if (preg_match('/' . $clave . '=([^,]+)/', $alarmDataHistorial, $coincidencias)) {
                    $resultados[$clave] = trim($coincidencias[1]);
                } else {
                    $resultados[$clave] = "";
                }
            }

            $lineas = [];
            foreach ($resultados as $clave => $valor) {
                $lineas[] = "$clave: $valor";
            }
            $textoDataAlarma = implode("\n", $lineas);
        } else {
            $textoDataAlarma = '';
        }

        // Asignar número de fila
        if ($isFirstRow) {
            $row['numFila'] = $totalCount;
            $isFirstRow = false;
        } else {
            $totalCount--;
            $row['numFila'] = $totalCount;
        }

        // Agregar campos calculados
        $row["fechaHistorial2"] = $fechaCompleta;
        $row["mensajeHistorial2"] = $mensajeHistorial2;
        $row["deptoHistorial"] = $nombreDepartamento;
        $row["cantidadObsHistorial"] = (int) $row["totalObs"];
        $row["vacioAlarmData"] = $vacioAlarmData;
        $row["textoDataAlarma"] = $textoDataAlarma;

        $datos[] = $row;
    }

    $stmt->close();
    $conexion->close();

    echo json_encode($datos);
} else {
    echo json_encode([]);
}
