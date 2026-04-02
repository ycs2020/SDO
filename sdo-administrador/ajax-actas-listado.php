<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM actas ORDER BY unidadTarjeta, nomTarjeta ASC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $nomTarjeta = $row["nomTarjeta"];
    $rutTarjeta = $row["rutTarjeta"];
    $codigoTarjeta = $row["codigoTarjeta"];
    $unidadTarjeta = $row["unidadTarjeta"];


    $nomTarjeta = mb_convert_case($nomTarjeta, MB_CASE_UPPER, 'UTF-8');
    $rutTarjeta = mb_convert_case($rutTarjeta, MB_CASE_UPPER, 'UTF-8');
    $codigoTarjeta = mb_convert_case($codigoTarjeta, MB_CASE_UPPER, 'UTF-8');
    $unidadTarjeta = mb_convert_case($unidadTarjeta, MB_CASE_UPPER, 'UTF-8');

    $row['nomTarjeta'] = $nomTarjeta;
    $row['rutTarjeta'] = $rutTarjeta;
    $row['codigoTarjeta'] = $codigoTarjeta;
    $row['unidadTarjeta'] = $unidadTarjeta;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>