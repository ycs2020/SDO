<?php

header('Content-Type: application/json; charset=utf-8');
include '../sdo-funciones/conexion.php';

$conexion->set_charset("utf8mb4");

$idCredencial = isset($_POST['idCredencialDatos']) ? (int)$_POST['idCredencialDatos'] : 0;

if ($idCredencial <= 0) {
    echo json_encode([]);
    exit;
}

$sql = $conexion->prepare("
    SELECT 
        DATE_FORMAT(fechaObservacion, '%d-%m-%Y %H:%i') AS fecha,
        usuarioObservacion AS usuario,
        tipoObservacion AS tipo,
        observacion
    FROM credenciales_rrhh_observaciones
    WHERE idCredencial = ?
    ORDER BY fechaObservacion DESC
");

$sql->bind_param("i", $idCredencial);
$sql->execute();
$resultado = $sql->get_result();

$datos = [];
while ($fila = $resultado->fetch_assoc()) {
    $datos[] = $fila; // 👈 esta es la forma correcta
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE); // importante para emojis, tildes, etc.
$sql->close();
$conexion->close();
