<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM pabellones_presion ORDER BY fechaPresion DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $fechaRegPresion = $row["fechaPresion"];
    $tecnicoPresion = $row["tecnicoPresion"];
    $pabellon2Presion = $row["pabellon2Presion"];


    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Presion';
        ${$nombreVariable} = $row[$nombreVariable];

        if (${$nombreVariable} > 1) {
            ${$nombreVariable} = ${$nombreVariable} . ' Pa';
        } else {
            ${$nombreVariable} = 'No Aplica';
        }

    }

    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRegPresion)));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaRegPresion)));

    $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

    $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
    $tecnicoPresion = mb_convert_case($tecnicoPresion, MB_CASE_UPPER, 'UTF-8');

    $row['fechaPresion'] = $fechaOrden;
    $row['tecnicoPresion'] = $tecnicoPresion;

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Presion';
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