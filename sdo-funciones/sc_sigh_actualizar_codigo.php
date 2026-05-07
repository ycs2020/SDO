<?php
include 'conexion.php'; // Ajusta la ruta según corresponda

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nuevoSigh = trim($_POST['nuevoSigh']);

    if ($id > 0 && !empty($nuevoSigh)) {
        // Asegúrate de que el nombre de la columna sea 'scSigh' y la PK 'idSoliCompra'
        $stmt = $conexion->prepare("UPDATE solicitudes_compra_sdo SET scSigh = ? WHERE idSoliCompra = ?");
        $stmt->bind_param("si", $nuevoSigh, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conexion->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    }
}
$conexion->close();