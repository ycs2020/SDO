<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM pabellones_temperatura ORDER BY fechaTemperatura DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $fechaRegTemperatura = $row["fechaTemperatura"];
    $tecnicoTemperatura = $row["tecnicoTemperatura"];

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Temperatura';
        ${$nombreVariable} = $row[$nombreVariable];

        if (${$nombreVariable} > 1) {
            ${$nombreVariable} = ${$nombreVariable} . ' °C';
        } else {
            ${$nombreVariable} = 'No Aplica';
        }

    }

    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRegTemperatura)));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaRegTemperatura)));

    $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

    $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
    $tecnicoTemperatura = mb_convert_case($tecnicoTemperatura, MB_CASE_UPPER, 'UTF-8');

    $row['fechaTemperatura'] = $fechaOrden;
    $row['tecnicoTemperatura'] = $tecnicoTemperatura;

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Temperatura';
        $row[$nombreVariable] = ${$nombreVariable};

    }

    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>