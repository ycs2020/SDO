<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM bms_alarmas_registro ORDER BY fechaRegistro DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $idRegistro = $row["idRegistro"];
    $fechaRegistro = $row["fechaRegistro"];
    $nomRegistro = $row["nomRegistro"];
    $controlRegistro = $row["controlRegistro"];

    $fechaTime = strtotime($fechaRegistro);
    $fechaPalabra = fechaEspañol2($fechaTime);
    $horaPalabra = date('H:i',$fechaTime).' hrs.';

    $fechaCompleta = $fechaPalabra.'<br>'.$horaPalabra;

    $row["idRegistro"] = $idRegistro;

    $row["fechaRegistro"] = $fechaRegistro;
    $row["fechaRegistro2"] = $fechaCompleta;

    $row["nomRegistro"] = $nomRegistro;
    $row["controlRegistro"] = $controlRegistro;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>