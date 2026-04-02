<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Obtener la categoría seleccionada desde la petición POST
// Consulta SQL
$sql = "SELECT * FROM presupuesto_equipos ORDER BY fechaPresup DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $folioPresup = $row["folioPresup"];
    $creadorPresup = $row["creadorPresup"];
    $fechaPresup = $row["fechaPresup"];
    $unidadPresup = $row["unidadPresup"];
    $valorPresup = $row["valorPresup"];
    $descripcion = $row["descripcion"];


    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaPresup)));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaPresup)));

    $fechaPresup2 = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';

    if (empty($folioPresup)) {
        $folioPresup = 'NO APLICA';
    }

    if (empty($creadorPresup)) {
        $creadorPresup = 'NO APLICA';
    }else{
        $creadorPresup = mb_convert_case($creadorPresup, MB_CASE_UPPER, 'UTF-8');
    }

    if (empty($unidadPresup)) {
        $unidadPresup = 'NO APLICA';
    }else{
        $unidadPresup = mb_convert_case($unidadPresup, MB_CASE_UPPER, 'UTF-8');
    }

    if (empty($valorPresup)) {
        $valorPresup = '0';
    }else{
        $valorPresup = intval($valorPresup);
    }

    $row['folioPresup'] = $folioPresup;
    $row['creadorPresup'] = $creadorPresup;
    $row['fechaPresup'] = $fechaPresup2;
    $row['unidadPresup'] = $unidadPresup;
    $row['valorPresup'] = $valorPresup;
    $row['descripcion'] = $descripcion;

    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();


// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>