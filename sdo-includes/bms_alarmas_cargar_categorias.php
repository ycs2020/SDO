<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');


// Consulta SQL con filtro
$sql = "SELECT * FROM bms_alarmas_categoria ORDER BY nomCategoria ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idRegistro);

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
    $idCategoria = $row["idCategoria"];
    $nomCategoria = $row["nomCategoria"];
    $nomCategoria = mb_convert_case($nomCategoria,MB_CASE_UPPER,'UTF-8');
    $idDepartamento = $row["idDepartamento"];

    if (!empty($idDepartamento) && $idDepartamento > 0) {

        $nomDepartamento = getNomDepartamento($idDepartamento);

    } else {
        $nomDepartamento = 'No Aplica';
    }

    $contarAlarmasBMSCategoria = contarAlarmasBMSCategoria($nomCategoria);

    $row["idCategoria"] = $idCategoria;

    $row["nomCategoria"] = $nomCategoria;

    $row["idDepartamento"] = $idDepartamento;

    $row["nomDepartamento"] = $nomDepartamento;

    $row['cantidadCategoria'] = $contarAlarmasBMSCategoria;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>
