<?php
// Archivo: tu_servidor.php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i:s');

// Obtener valores POST con sanitización mínima
$id = isset($_POST['idMemo']) ? (int)$_POST['idMemo'] : 0;
$numero = isset($_POST['numeroMemoSDO']) ? (int)$_POST['numeroMemoSDO'] : 0;
$creador = isset($_POST['creadorNumMemoSDO']) ? trim($_POST['creadorNumMemoSDO']) : '';

// Validación
if ($id <= 0 || $numero <= 0) {
    echo 'error: ID de Memorandum y Número de Memorandum SDO deben ser mayores que 0';
    exit;
}

if ($creador === '') {
    echo 'error: Usuario no puede ser nulo o vacío';
    exit;
}

// Verificar conexión
if ($conexion->connect_error) {
    echo 'error: Fallo en la conexión a la base de datos';
    exit;
}

//// 1. Verificar si el número ya existe en otro registro
//$checkStmt = $conexion->prepare("SELECT idMemo FROM memorandum_sdo WHERE numeroMemoSDO = ? AND idMemo != ?");
//if (!$checkStmt) {
//    echo 'error: No se pudo preparar la consulta de validación.';
//    exit;
//}
//$checkStmt->bind_param('ii', $numero, $id);
//$checkStmt->execute();
//$checkStmt->store_result();
//
//if ($checkStmt->num_rows > 0) {
//    echo 'error: El número de Memorandum SDO ya existe en otro registro';
//    $checkStmt->close();
//    $conexion->close();
//    exit;
//}
//$checkStmt->close();

// 2. Si no existe duplicado, proceder con el UPDATE
$stmt = $conexion->prepare("UPDATE memorandum_sdo SET 
    numeroMemoSDO = ?, 
    creadorNumMemoSDO = ?, 
    fechaRegistroNumMemoSDO = ? 
    WHERE idMemo = ?");

if (!$stmt) {
    echo 'error: No se pudo preparar la consulta de actualización.';
    exit;
}

// Enlazar parámetros (i = integer, s = string)
$stmt->bind_param('issi', $numero, $creador, $now, $id);

// Ejecutar
if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'error: No se pudo ejecutar el update';
}

// Cerrar statement y conexión
$stmt->close();
$conexion->close();
?>
