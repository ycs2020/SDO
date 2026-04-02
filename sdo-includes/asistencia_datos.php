<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT fechaAsistencia, turnoAsistencia
FROM asistencia_sdo
GROUP BY fechaAsistencia, turnoAsistencia
ORDER BY fechaAsistencia DESC, turnoAsistencia DESC;";

$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();


// Procesar los resultados
while ($row = $result->fetch_assoc()) {
    $fechaAsistencia = $row["fechaAsistencia"];
    $turnoAsistencia = $row["turnoAsistencia"];

    $date = DateTime::createFromFormat('Y-m-d', $fechaAsistencia);

    if ($date && $date->format('Y-m-d') === $fechaAsistencia) {

        $fechaTime = strtotime($fechaAsistencia);

        $fechaPalabra = fechaEspañol2($fechaTime);



        $row['fechaAsistencia'] = $fechaAsistencia;
        $row['turnoAsistencia'] = $turnoAsistencia;
        $row['fechaPalabra'] = $fechaPalabra;

        $datos[] = $row;

    }

}

// Cerrar el statement y la conexión
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
header('Content-Type: application/json');
echo json_encode(array('data' => $datos));
?>
