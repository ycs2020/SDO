<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$sql = "SELECT * FROM viajes_movilizacion ORDER BY fechaViaje DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $fechaViaje = $row['fechaViaje'];
    $fechaViajeMostrar = date("d-m-Y", strtotime($fechaViaje));

    $destinoViaje = $row['destinoViaje'];
    if(empty($destinoViaje)){
        $destinoViaje = 'No Aplica';
    }
    $destinoViajeMostrar = mb_convert_case($destinoViaje,MB_CASE_UPPER,'UTF-8');

    $patenteVehiculo = $row['patenteVehiculo'];
    if(empty($patenteVehiculo)){
        $patenteVehiculo = 'No Aplica';
    }
    $patenteVehiculoMostrar = mb_convert_case($patenteVehiculo,MB_CASE_UPPER,'UTF-8');

    $tipoVehiculo = $row['tipoVehiculo'];
    if(empty($tipoVehiculo)){
        $tipoVehiculo = 'No Aplica';
    }else{
        if($tipoVehiculo == 1){
            $tipoVehiculo = 'STATION WAGON';
        }else if($tipoVehiculo == 2){
            $tipoVehiculo = 'VAN';
        }else{
            $tipoVehiculo = 'NO APLICA';
        }
    }
    $tipoVehiculoMostrar = mb_convert_case($tipoVehiculo,MB_CASE_UPPER,'UTF-8');

    $unidadViaje = $row['unidadViaje'];
    if(empty($unidadViaje)){
        $unidadViaje = 'No Aplica';
    }
    $unidadViajeMostrar = mb_convert_case($unidadViaje,MB_CASE_UPPER,'UTF-8');

    $conductorViaje = $row['conductorViaje'];
    if(empty($conductorViaje)){
        $conductorViaje = 'No Aplica';
    }
    $conductorViajeMostrar = mb_convert_case($conductorViaje,MB_CASE_UPPER,'UTF-8');

    $tipoJornada = $row['tipoJornada'];
    if(empty($tipoJornada)){
        $tipoJornada = 'No Aplica';
    }
    $tipoJornadaMostrar = mb_convert_case($tipoJornada,MB_CASE_UPPER,'UTF-8');

    $numeroViaje = $row['numeroViaje'];
    if(empty($numeroViaje)){
        $numeroViaje = 'No Aplica';
    }
    $numeroViajeMostrar = mb_convert_case($numeroViaje,MB_CASE_UPPER,'UTF-8');

    $horaInicia = $row['horaInicia'];
    if(empty($horaInicia)){
        $horaInicia = 'No Aplica';
    }else{
        $horaInicia = date("H:i",strtotime($horaInicia));
    }
    $horaIniciaMostrar = mb_convert_case($horaInicia,MB_CASE_UPPER,'UTF-8');

    $horaTermino = $row['horaTermino'];
    if(empty($horaTermino)){
        $horaTermino = 'No Aplica';
    }else{
        $horaTermino = date("H:i",strtotime($horaTermino));
    }
    $horaTerminoMostrar = mb_convert_case($horaTermino,MB_CASE_UPPER,'UTF-8');

    $valorViaje = $row['valorViaje'];
    if($valorViaje > 0){
        $valorViajeSuma = $valorViaje;
//        $valorViaje = moneda_chilena_excel($valorViaje);
    }else{
        $valorViajeSuma = 0;
//        $valorViaje = '$0';
        $valorViaje = 0;
    }
    $valorViajeMostrar = mb_convert_case($valorViaje,MB_CASE_UPPER,'UTF-8');

    $valorExtension = $row['valorExtension'];
    if($valorExtension > 0){
        $valorExtensionSuma = $valorViaje;
//        $valorExtension = moneda_chilena_excel($valorExtension);
    }else{
        $valorExtensionSuma = 0;
//        $valorExtension = '$0';
        $valorExtension = 0;
    }
    $valorExtensionMostrar = mb_convert_case($valorExtension,MB_CASE_UPPER,'UTF-8');

    $valorTotalViaje = $valorViajeSuma + $valorExtensionSuma;

    if($valorTotalViaje > 0){
//        $valorTotalViaje = moneda_chilena_excel($valorTotalViaje);
        $valorTotalViaje2 = $valorTotalViaje;
    }else{
//        $valorTotalViaje = '$0';
        $valorTotalViaje2 = 0;
    }
    $valorTotalViajeMostrar = mb_convert_case($valorTotalViaje2,MB_CASE_UPPER,'UTF-8');

    $row['fechaViaje'] = $fechaViajeMostrar;
    $row['destinoViaje'] = $destinoViajeMostrar;
    $row['patenteVehiculo'] = $patenteVehiculoMostrar;
    $row['tipoVehiculo'] = $tipoVehiculoMostrar;
    $row['unidadViaje'] = $unidadViajeMostrar;
    $row['conductorViaje'] = $conductorViajeMostrar;
    $row['tipoJornada'] = $tipoJornadaMostrar;
    $row['numeroViaje'] = $numeroViajeMostrar;
    $row['horaInicia'] = $horaIniciaMostrar;
    $row['horaTermino'] = $horaTerminoMostrar;
    $row['valorViaje'] = $valorViajeMostrar;
    $row['valorExtension'] = $valorExtensionMostrar;
    $row['valorTotalViaje'] = $valorTotalViaje;


    $datos[] = $row;
}
$stmt->close();
$conexion->close();
echo json_encode($datos);
