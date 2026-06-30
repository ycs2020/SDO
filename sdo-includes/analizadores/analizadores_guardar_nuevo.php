<?php
// analizadores_guardar_nuevo.php
header('Content-Type: application/json');
include '../../sdo-funciones/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomAnalizador = isset($_POST['nomAnalizador']) ? trim($_POST['nomAnalizador']) : '';
    $pisoAnalizador = isset($_POST['pisoAnalizador']) ? (int)$_POST['pisoAnalizador'] : 0;
    $sectorAnalizador = isset($_POST['sectorAnalizador']) ? trim($_POST['sectorAnalizador']) : '';

    // Validación estricta de todos los campos obligatorios
    if (empty($nomAnalizador) || $_POST['pisoAnalizador'] === '' || empty($sectorAnalizador)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos obligatorios.']);
        exit;
    }

    // Consulta SQL con las 3 columnas correspondientes de tu tabla analizadores_red
    $queryInsert = "INSERT INTO analizadores_red (nomAnalizador, pisoAnalizador, sectorAnalizador) VALUES (?, ?, ?)";

    if ($stmt = $conexion->prepare($queryInsert)) {
        // Enlace de parámetros: s = string, i = integer, s = string
        $stmt->bind_param("sis", $nomAnalizador, $pisoAnalizador, $sectorAnalizador);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'El Analizador de Red se registró exitosamente con su sector.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar en la base de datos: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta estructurada SQL.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de petición denegado.']);
}
?>