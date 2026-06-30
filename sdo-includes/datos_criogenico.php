<?php
header('Content-Type: application/json; charset=utf-8');
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$conexion->set_charset("utf8mb4");


$nomUsuario = $_POST['nomUsuario'];

$sql = $conexion->prepare("SELECT * FROM `criogenico_datos` ORDER BY fecha DESC");
$sql->execute();
$resultado = $sql->get_result();

$datos = [];

while ($fila = $resultado->fetch_assoc()) {
    $id = $fila['id'];

// 🔽 GUARDAR VALORES SIN FORMATO
    $fila['metrosConsumidos_raw'] = $fila['metrosConsumidos'];
    $fila['precioUnitario_raw']   = $fila['precioUnitario'];
    $fila['valorTotal_raw']       = $fila['valorTotal'];
    $fila['valorTotalConIVA_raw'] = $fila['valorTotalConIVA'];
    $fila["fecha_raw"] = date("Y-m", strtotime($fila["fecha"]));

// 🔽 FORMATEOS (para mostrar)
    $fila["fecha"] = date("m/Y", strtotime($fila["fecha"]));
    $fila['metrosConsumidos'] = number_format($fila['metrosConsumidos'], 0, ',', '.');
    $fila['precioUnitario'] = '$'.number_format($fila['precioUnitario'], 0, ',', '.');
    $fila['valorTotal']     = '$'.number_format($fila['valorTotal'], 0, ',', '.');
    $fila['valorTotalConIVA'] = '$'.number_format($fila['valorTotalConIVA'], 0, ',', '.');

    $fila['now'] = date('Y-m-d H:i:s');
    $fila['nomUsuario'] = $nomUsuario;

// 🔽 CONSULTA DE OBSERVACIONES
    $obsQuery = $conexion->prepare("SELECT fecha, obs, nomUsuarioCreador FROM criogenico_obs WHERE idConsumo = ? ORDER BY fecha DESC");
    $obsQuery->bind_param("i", $id);
    $obsQuery->execute();
    $obsResult = $obsQuery->get_result();

    $observaciones = [];
    while ($obs = $obsResult->fetch_assoc()) {
        $observaciones[] = $obs;
    }

    $fila['observaciones'] = $observaciones;

    $datos[] = $fila;
}
echo json_encode(['data' => $datos], JSON_UNESCAPED_UNICODE);

$sql->close();
$conexion->close();