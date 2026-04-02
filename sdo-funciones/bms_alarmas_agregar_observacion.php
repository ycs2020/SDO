<?php

include 'conexion.php'; // Archivo de conexión a la base de datos


date_default_timezone_set('America/Santiago');

$fechaNow = date('Y-m-d H:i:s');

$idHistorial = $_POST['idHistorial'] ?? null;
$observacion = $_POST['observacion'];
// Convierte etiquetas <br> a saltos de línea reales
//$observacion = str_replace('<br>', "\n", $observacion);
$idRegistro = $_POST['idRegistro'] ?? null;
$nomControl = $_POST['nomControl'] ?? null;
$fechaObservacion = !empty($_POST['fechaObservacion']) ? $_POST['fechaObservacion'] : $fechaNow;
//
//$fechaTime = strtotime($fechaObservacion);
//
//$fechaEscrita = date('Y-m-d H:i:s',$fechaTime);

//$frase = "($idHistorial) -- ($observacion) -- ($idRegistro) -- ($nomControl) ";

if ($observacion && $idHistorial >= 0 && $nomControl && $idRegistro >= 0) {
    $stmt = $conexion->prepare("INSERT INTO bms_alarmas_observacion (detalleObservacion, creadorObservacion, idRegistro, idHistorial, fechaObservacion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssiis', $observacion, $nomControl, $idRegistro, $idHistorial, $fechaObservacion);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Observación agregada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $fechaObservacion]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos Incompletos']);
}

