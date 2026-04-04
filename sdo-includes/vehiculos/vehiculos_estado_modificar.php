<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurar la cabecera para devolver JSON
header('Content-Type: application/json');

// Validar que el usuario esté logueado
if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // RECIBIMOS LOS DATOS Y LOS ALMACENAMOS EN VARIABLES (con validación básica)
    $idMovil = isset($_POST['idMovil']) ? intval($_POST['idMovil']) : 0;
    $estadoMovil = isset($_POST['estadoMovil']) ? strtoupper(trim($_POST['estadoMovil'])) : '';
    $motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';

    if ($idMovil <= 0 || empty($estadoMovil)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    // Iniciar transacción para asegurar que ambas consultas se ejecuten correctamente
    $conexion->begin_transaction();

    try {
        // INSERTAMOS EN LA BASE DE DATOS (Usando Prepared Statements por seguridad)
        $sqlInsert = "INSERT INTO historial_estados_moviles (estadoMovil, motivo, idMovil) VALUES (?, ?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->bind_param('ssi', $estadoMovil, $motivo, $idMovil);
        $stmtInsert->execute();

        // ACTUALIZAMOS LA TABLA DE LOS MOVILES
        $sqlUpdate = "UPDATE moviles_movilizacion SET estadoVehiculo = ? WHERE idMovil = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bind_param('si', $estadoMovil, $idMovil);
        $stmtUpdate->execute();

        // Confirmar los cambios
        $conexion->commit();

        echo json_encode(['success' => true, 'message' => 'El estado se ha guardado correctamente.']);

    } catch (Exception $e) {
        // Si algo falla, deshacemos los cambios
        $conexion->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}