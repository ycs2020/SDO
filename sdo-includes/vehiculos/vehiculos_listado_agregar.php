<?php
// Evitar que cualquier error de PHP ensucie el JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Verificar conexión
    if (!$conexion) {
        echo json_encode(['status' => 'error', 'message' => 'Fallo de conexión: ' . mysqli_connect_error()]);
        exit;
    }

    // 2. Recibir y limpiar datos
    $idVehiculo       = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['idVehiculo']), 'UTF-8'));
    $nomVehiculo      = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['nomVehiculo']), 'UTF-8'));
    $patenteVehiculo  = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['patenteVehiculo']), 'UTF-8'));
    $marcaVehiculo    = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['marcaVehiculo']), 'UTF-8'));
    $modeloVehiculo   = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['modeloVehiculo']), 'UTF-8'));
    $numInventario    = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['numInventario']), 'UTF-8'));
    $servicioVehiculo = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['servicioVehiculo']), 'UTF-8'));
    $numChasis        = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['numChasis']), 'UTF-8'));
    $numMotor         = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['numMotor']), 'UTF-8'));
    $frabricante      = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['frabricante']), 'UTF-8'));
    $fechaCompra      = mysqli_real_escape_string($conexion, $_POST['fechaCompra']);
    $ordenCompra      = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['ordenCompra']), 'UTF-8'));
    $valorConIva      = trim($_POST['valorConIva']);
    $vidaUtil         = mysqli_real_escape_string($conexion, mb_strtoupper(trim($_POST['vidaUtil']), 'UTF-8'));

    $valorSQL = (empty($valorConIva) || !is_numeric($valorConIva)) ? "NULL" : $valorConIva;
    $estadoVehiculo = "OPERATIVA";

    // 3. LA CONSULTA (Asegúrate de que 'idUnico' exista en tu tabla)
    $sql = "INSERT INTO moviles_movilizacion (
                idUnico, nomVehiculo, patenteVehiculo, marcaVehiculo, 
                modeloVehiculo, numInventario, servicioVehiculo, 
                numChasis, numMotor, frabricante, ordenCompra, 
                valorConIva, vidaUtil, estadoVehiculo
            ) VALUES (
                '$idVehiculo', '$nomVehiculo', '$patenteVehiculo', '$marcaVehiculo', 
                '$modeloVehiculo', '$numInventario', '$servicioVehiculo', 
                '$numChasis', '$numMotor', '$frabricante', '$ordenCompra', 
                $valorSQL, '$vidaUtil', '$estadoVehiculo'
            )";

    if (mysqli_query($conexion, $sql)) {
        $idMovil = mysqli_insert_id($conexion);

        // Actualizar Folio y Fecha
        $folio = "HBQP-MOVIL-" . $idMovil;
        $sqlExtra = "UPDATE moviles_movilizacion SET folio = '$folio'";
        if(!empty($fechaCompra)) { $sqlExtra .= ", fechaCompra = '$fechaCompra'"; }
        $sqlExtra .= " WHERE idMovil = $idMovil";

        mysqli_query($conexion, $sqlExtra);

        echo json_encode(['status' => 'success', 'message' => 'Guardado con Folio: ' . $folio]);
    } else {
        // AQUÍ FORZAMOS EL ERROR REAL
        echo json_encode(['status' => 'error', 'message' => 'Error SQL: ' . mysqli_error($conexion)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió POST']);
}