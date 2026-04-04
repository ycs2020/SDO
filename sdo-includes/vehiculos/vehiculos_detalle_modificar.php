<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

// Configuramos la cabecera para que siempre devuelva JSON
header('Content-Type: application/json');

// Validamos que por lo menos venga el ID del movil y el ID Único
if (!isset($_POST['idMovil'], $_POST['idVehiculo'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit;
}

// Recibimos las variables
$idMovil = intval($_POST['idMovil']);
$idVehiculo = trim($_POST['idVehiculo']); // Este corresponde a idUnico en la BD
$nomVehiculo = $_POST['nomVehiculo'];
$patenteVehiculo = $_POST['patenteVehiculo'];
$marcaVehiculo = $_POST['marcaVehiculo'];
$modeloVehiculo = $_POST['modeloVehiculo'];
$numInventario = $_POST['numInventario'];
$servicioVehiculo = $_POST['servicioVehiculo'];
$numChasis = $_POST['numChasis'];
$numMotor = $_POST['numMotor'];
$frabricante = $_POST['frabricante'];
$fechaCompra = $_POST['fechaCompra'];
$ordenCompra = $_POST['ordenCompra'];
$vidaUtil = $_POST['vidaUtil'];
$valorConIva = $_POST['valorConIva'];

// 1. VALIDACIÓN DEL ID ÚNICO
// Buscamos si el idVehiculo existe en algún registro que NO sea el que estamos editando
$sqlCheck = "SELECT idMovil FROM moviles_movilizacion WHERE idUnico = ? AND idMovil != ?";
$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param('si', $idVehiculo, $idMovil);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    // Si arroja resultados, el ID ya lo tiene otro auto. Frenamos el proceso.
    echo json_encode([
        'success' => false,
        'message' => 'El ID "' . $idVehiculo . '" ya se encuentra registrado en otro vehículo.'
    ]);
    $stmtCheck->close();
    exit;
}
$stmtCheck->close();

// 2. ACTUALIZACIÓN EN LA BASE DE DATOS
// Utilizamos Prepared Statements por seguridad contra inyecciones SQL
$sqlUpdate = "UPDATE moviles_movilizacion SET 
                idUnico = ?, 
                nomVehiculo = ?, 
                patenteVehiculo = ?, 
                marcaVehiculo = ?, 
                modeloVehiculo = ?, 
                numInventario = ?, 
                servicioVehiculo = ?, 
                numChasis = ?, 
                numMotor = ?, 
                frabricante = ?, 
                fechaCompra = ?, 
                ordenCompra = ?, 
                vidaUtil = ?, 
                valorConIva = ? 
              WHERE idMovil = ?";

$stmtUpdate = $conexion->prepare($sqlUpdate);

// Vinculamos los parámetros. 's' significa String, 'i' significa Integer.
// Hay 14 strings y al final 1 integer (el idMovil del WHERE)
$stmtUpdate->bind_param('ssssssssssssssi',
    $idVehiculo,
    $nomVehiculo,
    $patenteVehiculo,
    $marcaVehiculo,
    $modeloVehiculo,
    $numInventario,
    $servicioVehiculo,
    $numChasis,
    $numMotor,
    $frabricante,
    $fechaCompra,
    $ordenCompra,
    $vidaUtil,
    $valorConIva,
    $idMovil
);

// Ejecutamos la consulta y devolvemos la respuesta al JS
if ($stmtUpdate->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'La modificación se ha guardado correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar la base de datos: ' . $stmtUpdate->error
    ]);
}

$stmtUpdate->close();
$conexion->close();
?>