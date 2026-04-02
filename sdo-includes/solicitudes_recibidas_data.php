<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = 'SELECT * FROM solicitudes_soportes WHERE canceladaSolicitud = 0 AND finalizadaSolicitud = 0 ORDER BY inicioSolicitud DESC';

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $folioSolicitud = $row["folioSolicitud"];


    $folioSolicitud = mb_convert_case($folioSolicitud, MB_CASE_UPPER, 'UTF-8');

    $row['folioSolicitud'] = $folioSolicitud;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>