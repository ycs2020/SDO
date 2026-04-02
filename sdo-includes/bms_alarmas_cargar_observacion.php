<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Verificar si se recibe un idHistorial válido
if (isset($_POST['idHistorial']) && $_POST['idHistorial'] > 0) {
    $idHistorial = (int)$_POST['idHistorial']; // Asegurarse de que sea un entero

    // Consulta SQL con filtro
    $sql = "SELECT * FROM bms_alarmas_observacion WHERE idHistorial = ? ORDER BY fechaObservacion DESC;";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idHistorial);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->get_result();
    $totalCount = $result->num_rows;
    $datos = array();


    // Variable para identificar la primera fila
    $isFirstRow = true;

    // Iterar sobre los resultados y aplicar la lógica necesaria
    while ($row = $result->fetch_assoc()) {
        $idObservacion = $row["idObservacion"];
        $detalleObservacion = $row["detalleObservacion"];
        $creadorObservacion = $row["creadorObservacion"];
        $fechaObservacion = $row["fechaObservacion"];
        $idRegistro = $row["idRegistro"];
        $idHistorial = $row["idHistorial"];


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


        $row["idObservacion"] = $idObservacion;
        $row["detalleObservacion"] = $detalleObservacion;
        $row["creadorObservacion"] = $creadorObservacion;

        $row["fechaObservacion"] = $fechaObservacion;
        $row["fechaObservacion2"] = $fechaCompleta;

        $row["idRegistro"] = $idRegistro;
        $row["idHistorial"] = $idHistorial;


        // Agregar la fila modificada al array de resultados
        $datos[] = $row;
    }

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conexion->close();

    // Enviar los resultados modificados como respuesta JSON
    echo json_encode($datos);
} else {
    // No cargar datos si idHistorial no es válido
    echo json_encode([]);
}
?>
