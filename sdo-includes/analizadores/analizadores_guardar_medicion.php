<?php
// analizadores_guardar_medicion.php
header('Content-Type: application/json');
include '../../sdo-funciones/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAnalizador = isset($_POST['idAnalizador']) ? (int)$_POST['idAnalizador'] : 0;
    $idSubAnalizador = isset($_POST['idSubAnalizador']) ? (int)$_POST['idSubAnalizador'] : 0;
    $valorMedicion = isset($_POST['valorMedicion']) ? (float)$_POST['valorMedicion'] : 0.0;
    $lineaMedicion = isset($_POST['lineaMedicion']) ? trim($_POST['lineaMedicion']) : '';
    $fechaMedicion = isset($_POST['fechaMedicion']) ? $_POST['fechaMedicion'] : '';

    // Validaciones estrictas de servidor
    if ($idAnalizador <= 0 || empty($lineaMedicion) || empty($fechaMedicion)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos obligatorios.']);
        exit;
    }

    // Convertir formato HTML5 datetime-local (YYYY-MM-DDTHH:MM) a formato MySQL (YYYY-MM-DD HH:MM:SS)
    $fechaMySQL = date('Y-m-d H:i:s', strtotime($fechaMedicion));

    // Preparar inserción parametrizada según las columnas de tu BD real
    $queryInsert = "INSERT INTO analizadores_red_mediciones (fechaMedicion, valorMedicion, lineaMedicion, idAnalizador, idSubAnalizador) 
                    VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($queryInsert)) {
        $stmt->bind_param("sdsii", $fechaMySQL, $valorMedicion, $lineaMedicion, $idAnalizador, $idSubAnalizador);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'La medición se guardó de manera exitosa.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la medición en la base de datos: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al estructurar la consulta SQL preparada.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de petición denegado.']);
}
?>