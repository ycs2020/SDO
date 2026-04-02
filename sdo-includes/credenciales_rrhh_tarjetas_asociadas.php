<?php
header('Content-Type: application/json; charset=utf-8');
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$conexion->set_charset("utf8mb4");

$idCredencial = isset($_POST['idCredencialCargar']) ? (int)$_POST['idCredencialCargar'] : 0;

if ($idCredencial <= 0) {
    echo json_encode(['data' => []]);
    exit;
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha No Ingresada';

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
    crt.tipoTarjeta AS tipoTarjeta,
    crt.codigoTarjeta AS codigoTarjeta,
    crt.textoTarjeta AS textoTarjeta,
    crt.unidadTarjeta AS unidadTarjeta,
    crrt.inicioTarjeta,
    crrt.terminoTarjeta,
    crrt.activacionCredencial,
    crrt.fechaRegistro
FROM credenciales_rrhh_registro_tarjetas crrt
INNER JOIN credenciales_rrhh_tarjetas crt 
    ON crrt.idTarjeta = crt.idTarjeta
WHERE crrt.idCredencial = ?
ORDER BY 
    CASE 
        WHEN crrt.activacionCredencial = 1 THEN 1
        WHEN crrt.activacionCredencial = 2 THEN 2
        WHEN crrt.activacionCredencial = 3 THEN 3
        WHEN crrt.activacionCredencial = 0 THEN 4
        ELSE 4
    END,
    crrt.fechaRegistro DESC,
    crt.fechaCreacion DESC;

");

$sql->bind_param('i', $idCredencial);
$sql->execute();
$resultado = $sql->get_result();

$datos = [];

while ($fila = $resultado->fetch_assoc()) {

    $fila['inicioTarjeta2'] = formatearFechaCompletaAJAX($fila['inicioTarjeta']);
    $fila['terminoTarjeta2'] = formatearFechaCompletaAJAX($fila['terminoTarjeta']);

    $activacionCredencial2 = (int)$fila['activacionCredencial'];

    if($activacionCredencial2 === 1){
        $fila['activacionCredencial2'] = "<span class='badge badge-success'>Activo</span>";
    }else if($activacionCredencial2 === 2){
        $fila['activacionCredencial2'] = "<span class='badge badge-warning'>Por Habilitar</span>";
    }else if($activacionCredencial2 === 3){
        $fila['activacionCredencial2'] = "<span class='badge badge-primary'>Por Deshabilitar</span>";
    }else{
        $fila['activacionCredencial2'] = "<span class='badge badge-danger'>Inactivo</span>";
    }

    //$btnOT = "Opción Deshabilitada";

    $btnDeshabilitar = "Opción Deshabilitada";
    $idRegistroTarjeta = $fila['idRegistroTarjeta'];

    if($activacionCredencial2 === 1 || $activacionCredencial2 === 2) {
        $btnDeshabilitar = "<button class='btn btn-sm btn-danger'  data-idcreddesh='$idRegistroTarjeta' ><i class='fas fa-times'></i>&nbsp;&nbsp;Deshabilitar</button>";
    }


   /* if($activacionCredencial2 == 2 || $activacionCredencial2 == 3){
        $btnOT = "<button class='btn btn-sm btn-primary' data-idOT='$idRegistroTarjeta'><i class='fas fa-check'></i>&nbsp;&nbsp;Crear OT</button>";
    }
   */

    $fila['btnDeshabilitar'] = $btnDeshabilitar;
    $fila['idRegistroTarjeta'] = $idRegistroTarjeta;
    $fila['activacionCredencial3'] = $activacionCredencial2;
   // $fila['btnOT'] = $btnOT;


    $datos[] = $fila;
}

echo json_encode(['data' => $datos], JSON_UNESCAPED_UNICODE);

$sql->close();
$conexion->close();
