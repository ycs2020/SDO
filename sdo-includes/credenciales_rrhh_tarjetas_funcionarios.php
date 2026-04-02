<?php
header('Content-Type: application/json; charset=utf-8');
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$conexion->set_charset("utf8mb4");

$idTarjeta = isset($_POST['idTarjetaCargar']) ? (int)$_POST['idTarjetaCargar'] : 0;

if ($idTarjeta <= 0) {
    echo json_encode(['data' => []]);
    exit;
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {

            $inicioFecha = date('d / m / Y', $timestamp);
            $fechaCompleta = $inicioFecha;
        }
    }

    return $fechaCompleta;
}

$sql = $conexion->prepare("
    SELECT 
    crrt.idRegistro AS idRegistroTarjeta,
    CONCAT(cr.nombreCredencial, ' ', cr.apellidoCredencial) AS nombreCredencial,
    cr.rutCredencial AS rutCredencial,
    cu.nomUnidad AS nombreUnidad,
    cc.nomCargo AS nombreCargo,
    cal.nomCalidad AS nombreCalidadJuridica,
    cj.nomJefatura AS nombreJefatura,
    ctr.inicioContrato AS fechaInicioContrato,
    ctr.terminoContrato AS fechaTerminoContrato,
    ctr.contratoActivo AS contratoActivo,
    crrt.inicioTarjeta,
    crrt.terminoTarjeta,
    crrt.activacionCredencial,
    crrt.fechaRegistro
FROM credenciales_rrhh_registro_tarjetas crrt
INNER JOIN credenciales_rrhh cr 
    ON crrt.idCredencial = cr.idCredencial
LEFT JOIN credenciales_rrhh_contratos ctr 
    ON ctr.idCredencial = cr.idCredencial
LEFT JOIN credenciales_rrhh_unidades cu 
    ON ctr.idUnidad = cu.idUnidad
LEFT JOIN credenciales_rrhh_cargos cc 
    ON ctr.idCargo = cc.idCargo
LEFT JOIN credenciales_rrhh_calidades cal 
    ON ctr.idCalidad = cal.idCalidad
LEFT JOIN credenciales_rrhh_jefaturas cj 
    ON ctr.idJefatura = cj.idJefatura
WHERE crrt.idTarjeta = ?
ORDER BY crrt.fechaRegistro DESC, ctr.inicioContrato DESC;

");

$sql->bind_param('i', $idTarjeta);
$sql->execute();
$resultado = $sql->get_result();

$datos = [];

while ($fila = $resultado->fetch_assoc()) {

    $fila['fechaInicioContrato2'] = formatearFechaCompletaAJAX($fila['fechaInicioContrato']);
    $fila['fechaTerminoContrato2'] = formatearFechaCompletaAJAX($fila['fechaTerminoContrato']);

    $contratoActivo2 = (int)$fila['contratoActivo'];

    if($contratoActivo2 === 1){
        $fila['contratoActivo2'] = "<span class='badge badge-success'>Activo</span>";
    }else if($contratoActivo2 === 3){
        $fila['contratoActivo2'] = "<span class='badge badge-primary'>Por Deshabilitar</span>";
    }else{
        $fila['contratoActivo2'] = "<span class='badge badge-danger'>Inactivo</span>";
    }


    $datos[] = $fila;
}

echo json_encode(['data' => $datos], JSON_UNESCAPED_UNICODE);

$sql->close();
$conexion->close();
