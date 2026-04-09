<?php
require_once("../../sdo-funciones/conexion.php"); // Verifica que esta ruta llegue al archivo
header('Content-Type: application/json');

// Desactivar reporte de errores de texto plano para no romper el JSON
error_reporting(0);

try {
    // Validar que lleguen los datos mínimos
    if(!isset($_POST['numKilometraje']) || !isset($_POST['idMovil'])){
        throw new Exception("Datos incompletos.");
    }

    $fechaRegistro       = $_POST['fechaReporte'];
    $cantKilometraje     = intval($_POST['numKilometraje']);
    $estadoVehiculo      = $_POST['estadoVehiculo'];
    $idMovil             = intval($_POST['idMovil']);
    $registroMantenimiento = isset($_POST['registroMantenimiento']) ? 1 : 0;

    // Si usas MySQLi (Estructura estándar de SDO):
    $sql = "INSERT INTO historial_kilometraje_moviles 
            (fechaRegistro, cantKilometraje, estadoVehiculo, idMovil, registroMantenimiento) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    // "siiis" -> string, integer, string, integer, integer
    $stmt->bind_param("sisis", $fechaRegistro, $cantKilometraje, $estadoVehiculo, $idMovil, $registroMantenimiento);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("Error en ejecución: " . $stmt->error);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>