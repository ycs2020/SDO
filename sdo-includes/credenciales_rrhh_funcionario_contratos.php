<?php

header('Content-Type: application/json; charset=utf-8');
include '../sdo-funciones/conexion.php';

$conexion->set_charset("utf8mb4");

$idCredencial = isset($_POST['idCredencialContratos']) ? (int)$_POST['idCredencialContratos'] : 0;

if ($idCredencial <= 0) {
    echo json_encode([]);
    exit;
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = date('d/m/Y', $timestamp);
            $inicioHora  = date('H:i', $timestamp);
            $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
        }
    }

    return $fechaCompleta;
}

$sql = $conexion->prepare("
    SELECT idContrato, nomUnidad, nomJefatura, cargoContrato, calidadContrato, inicioContrato, terminoContrato FROM credenciales_rrhh_contratos WHERE idFuncionario = ? ORDER BY inicioContrato DESC
");

$sql->bind_param("i", $idCredencial);
$sql->execute();
$resultado = $sql->get_result();

$datos = [];
while ($fila = $resultado->fetch_assoc()) {

    $inicioContrato2 = formatearFechaCompletaAJAX($fila['inicioContrato']);
    $terminoContrato2 = formatearFechaCompletaAJAX($fila['terminoContrato']);

    $fila['inicioContrato2'] = $inicioContrato2;
    $fila['terminoContrato2'] = $terminoContrato2;


    $datos[] = $fila; // 👈 esta es la forma correcta
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE); // importante para emojis, tildes, etc.
$sql->close();
$conexion->close();
