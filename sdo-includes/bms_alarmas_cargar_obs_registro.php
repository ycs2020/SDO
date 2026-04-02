<?php
header('Content-Type: application/json; charset=utf-8');

// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Verificar si se recibe un idRegistro válido
if (isset($_POST['idRegistro']) && $_POST['idRegistro'] > 0) {
    $idRegistro = (int)$_POST['idRegistro']; // Asegurarse de que sea un entero
    $idHistorial = 0;

    // Consulta SQL con filtro
    $sql = "SELECT * FROM bms_alarmas_observacion WHERE idRegistro = ? AND idHistorial = ? ORDER BY fechaObservacion DESC;";

// Preparar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idRegistro, $idHistorial);

// Ejecutar la consulta
    $stmt->execute();

// Obtener los resultados
    $result = $stmt->get_result();
    $totalCount = $result->num_rows;
    $datos = array();

// Calcular total de observaciones fuera del bucle
    $totalObservacionesHistorial = contarObservacionesAlarmasBMS("idRegistro", $idRegistro);

// Variable para identificar la primera fila
    $isFirstRow = true;

// Iterar sobre los resultados y aplicar la lógica necesaria
    while ($row = $result->fetch_assoc()) {
        $idObservacion = $row["idObservacion"];
        $detalleObservacion = $row["detalleObservacion"];
        $creadorObservacion = $row["creadorObservacion"];
        $fechaObservacion = $row["fechaObservacion"];

        // Procesar fecha
        if (!empty($fechaObservacion)) {
            $fechaTime = strtotime($fechaObservacion);

            if (validateTimestamp($fechaObservacion)) {
                $fechaEscrita = fechaEspañol2($fechaTime);
                $horaEscrita = date('H:i', $fechaTime);
                $fechaCompleta = "$fechaEscrita<br>$horaEscrita hrs.";
            } else {
                $fechaCompleta = 'No Aplica';
            }
        } else {
            $fechaCompleta = 'No Aplica';
        }

        // Asignar valores adicionales a la fila
        $row["idRegistro"] = $idRegistro;
        $row["idObservacion"] = $idObservacion;
        $row["fechaObservacion2"] = $fechaCompleta;
        $row["cantidadObsRegistro"] = $totalObservacionesHistorial;

        // Asignar el número de fila
        $row['numFila'] = $isFirstRow ? $totalCount : --$totalCount;
        $isFirstRow = false;


        // Agregar la fila modificada al array de resultados
        $datos[] = $row;
    }

// Cerrar la conexión y liberar recursos
    $stmt->close();
    $conexion->close();

// Enviar los resultados modificados como respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($datos);
} else {
    // No cargar datos si idRegistro no es válido
    echo json_encode([]);
}
?>
