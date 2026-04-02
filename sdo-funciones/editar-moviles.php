<?php

include 'conexion.php';

$idMovil = $_POST['idMovil'];
$idVehiculo = $_POST['idVehiculo'];
$nomVehiculo = $_POST['nomVehiculo'];
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


//ACTUALZA EN EL BASE DE DATOS
$sqlUpdate = "UPDATE  moviles_movilizacion SET idUnico = '$idVehiculo',nomVehiculo = '$nomVehiculo', patenteVehiculo = '$patenteVehiculo',marcaVehiculo = '$marcaVehiculo',modeloVehiculo = '$modeloVehiculo',numInventario = '$numInventario',servicioVehiculo = '$servicioVehiculo',numChasis = '$numChasis',numMotor = '$numMotor',frabricante = '$frabricante',fechaCompra = '$fechaCompra',ordenCompra = '$ordenCompra',vidaUtil = '$vidaUtil',valorConIva = '$valorConIva' WHERE idMovil = $idMovil ";
 $resUpdate = mysqli_query($conexion, $sqlUpdate);


header('Content-Type: application/json');
echo json_encode($sqlUpdate);