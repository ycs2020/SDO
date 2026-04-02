<?php

header('Content-Type: application/json; charset=utf-8');
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$conexion->set_charset("utf8mb4");

function traspasoFechasEspañol($fecha)
{
    $timestamp = strtotime(str_replace('/', '-', $fecha));

    if ($timestamp !== false && $timestamp > 0) {
        $fecha2 = fechaEspañol2($timestamp);
    } else {
        $fecha2 = 'Fecha inválida';
    }
    $fecha3 = mb_convert_case($fecha2, MB_CASE_UPPER, "UTF-8");
    return $fecha3;
}


$idPerfil = isset($_POST['idPerfil']) ? (int)$_POST['idPerfil'] : 0;

if ($idPerfil <= 0) {
    echo json_encode([]);
    exit;
}

$sql = $conexion->prepare("
    SELECT 
        crrp.idRegistro,
        pcp.idPerfil,
        pcp.nomPerfil,
        crrp.fechaRegistro
    FROM credenciales_rrhh_registro_perfiles crrp
    INNER JOIN protoc_ccaa_perfil pcp ON crrp.idPerfil = pcp.idPerfil
    WHERE crrp.idCredencial = ?
    ORDER BY crrp.fechaRegistro DESC
");

$sql->bind_param("i", $idCredencial);
$sql->execute();
$resultado = $sql->get_result();

$datos = [];
while ($fila = $resultado->fetch_assoc()) {
    $idPerfil = $fila['idPerfil'];

    $fechaRegistro = $fila['fechaRegistro'];
    $fechaRegistro2 = traspasoFechasEspañol($fechaRegistro);

    if ($idPerfil > 0) {
        $buttonVisual = "<button class='btn btn-primary btn-sm btn-block' id='btnDataPerfil' data-perfil='$idPerfil' ><i class='fas fa-eye'></i></button>";
    } else {
        $buttonVisual = "<span class='text-muted'>Deshabilitado</span>";

    }

    $fila['fechaRegistro2'] = $fechaRegistro2;
    $fila['buttonVisual'] = $buttonVisual;
    $datos[] = $fila; // 👈 esta es la forma correcta
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE); // importante para emojis, tildes, etc.
$sql->close();
$conexion->close();
