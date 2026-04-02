<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM pabellones_humedades ORDER BY fechaHumedad DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $fechaRegHumedad = $row["fechaHumedad"];
    $tecnicoHumedad = $row["tecnicoHumedad"];
    $pabellon2Humedad = $row["pabellon2Humedad"];

    if($pabellon2Humedad > 1){
        $pabellon2Humedad = $pabellon2Humedad.'%';
    }else{
        $pabellon2Humedad = 'No Aplica';
    }

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Humedad';
        ${$nombreVariable} = $row[$nombreVariable];

        if (${$nombreVariable} > 1) {
            ${$nombreVariable} = ${$nombreVariable} . '%';
        } else {
            ${$nombreVariable} = 'No Aplica';
        }

    }

    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRegHumedad)));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaRegHumedad)));

    $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

    $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
    $tecnicoHumedad = mb_convert_case($tecnicoHumedad, MB_CASE_UPPER, 'UTF-8');

    $row['fechaHumedad'] = $fechaOrden;
    $row['tecnicoHumedad'] = $tecnicoHumedad;

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'pabellon' . $i . 'Humedad';
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