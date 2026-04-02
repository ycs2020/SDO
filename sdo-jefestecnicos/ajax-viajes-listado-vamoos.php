<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = "SELECT * FROM viajes_movilizacion_vamoos_spa ORDER BY fechaViaje DESC, idViaje DESC;";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $unidadViaje = $row['unidadViaje'];
    $idViaje = $row['idViaje'];
    $valorViaje = $row['valorViaje'];
    $numeroViaje = $row['numeroViaje'];
    $tipoVehiculo = $row['tipoVehiculo'];
    $fechaViaje = $row['fechaViaje'];
    $patenteVehiculo = $row['patenteVehiculo'];
    $conductorViaje = $row['conductorViaje'];
    $pasajeroViaje = $row['pasajeroViaje'];
    $partidaViaje = $row['partidaViaje'];
    $destinoViaje = $row['destinoViaje'];
    $tipoJornada = $row['tipoJornada'];
    $motivoViaje = $row['motivoViaje'];
    $horaInicia = $row['horaInicia'];
    $horaTermino = $row['horaTermino'];
    $valorExtension = $row['valorExtension'];
    $valorTotalSuma = $valorViaje + $valorExtension;
    $fechaIngresoPlata = $row['fechaIngresoPlata'];


    if(empty($fechaViaje)){
        $fechaViaje = 'NO APLICA';
    }else{
        $fechaViaje = date('d-m-Y', (strtotime(str_replace('/', '-', $fechaViaje))));
    }

    if(empty($fechaIngresoPlata)){
        $fechaIngresoPlata = 'SIN REGISTRO';
    }else{
        $fechaIngresoPlata = date('d-m-Y', (strtotime(str_replace('/', '-', $fechaIngresoPlata))));
    }

    if(empty($destinoViaje)){
        $destinoViaje = 'NO APLICA';
    }else{
        $destinoViaje = mb_convert_case($destinoViaje,MB_CASE_UPPER,'UTF-8');
    }

    if(empty($patenteVehiculo)){
        $patenteVehiculo = 'NO APLICA';
    }else{
        $patenteVehiculo = mb_convert_case($patenteVehiculo,MB_CASE_UPPER,'UTF-8');
    }

    if ($tipoVehiculo == 1) {
        $tipoVehiculo = 'AUTOMOVIL';
    } else if ($tipoVehiculo == 2) {
        $tipoVehiculo = 'VAN';
    } else if ($tipoVehiculo == 3) {
        $tipoVehiculo = 'CAMIONETA DE CARGA';
    }else{
        $tipoVehiculo = 'NO APLICA';
    }

    if(empty($unidadViaje)){
        $unidadViaje = 'NO APLICA';
    }else{
        $unidadViaje = mb_convert_case($unidadViaje,MB_CASE_UPPER,'UTF-8');
    }

    if($conductorViaje > 0) {
        $sqlConductores1 = "SELECT * FROM conductores_movilizacion WHERE idConductor = $conductorViaje";
        $resConductores1 = mysqli_query($conexion,$sqlConductores1);
        while($rowConductores1 = $resConductores1->fetch_assoc()){
            $nomConductor = $rowConductores1['nomConductor'];
        }
        $conductorViaje = mb_convert_case($nomConductor,MB_CASE_UPPER,'UTF-8');

    }else{
        $conductorViaje = 'NO APLICA';
    }

    if(empty($tipoJornada)){
        $tipoJornada = 'NO APLICA';
    }else{
        $tipoJornada = mb_convert_case($tipoJornada,MB_CASE_UPPER,'UTF-8');
    }

    if(empty($numeroViaje)){
        $numeroViaje = 'NO APLICA';
    }else{
        $numeroViaje = mb_convert_case($numeroViaje,MB_CASE_UPPER,'UTF-8');
    }

    if(empty($horaInicia)){
        $horaInicia = 'NO APLICA';
    }else{
        $horaInicia = mb_convert_case($horaInicia,MB_CASE_UPPER,'UTF-8');
    }

    if(empty($horaTermino)){
        $horaTermino = 'NO APLICA';
    }else{
        $horaTermino = mb_convert_case($horaTermino,MB_CASE_UPPER,'UTF-8');
    }

    $valorViaje = '$' . number_format($valorViaje, 0, ",", ".");

    if (empty($valorExtension)) {
        $valorExtension = 'NO APLICA';
    } else {
        $valorExtension = '$' . number_format($valorExtension, 0, ",", ".");

    }

    if (empty($valorTotalSuma)) {
        $valorTotalSuma = 'NO APLICA';
    } else {
        $valorTotalSuma = '$' . number_format($valorTotalSuma, 0, ",", ".");

    }

    $sqlArchivos = "SELECT * FROM archivos_viajes_movilizacion_vamoos_spa WHERE idViaje = $idViaje";
    $resArchivos = mysqli_query($conexion, $sqlArchivos);

    $hasArchivos = $resArchivos->num_rows;

    $row['fechaViaje'] = $fechaViaje;
    $row['destinoViaje'] = $destinoViaje;
    $row['patenteVehiculo'] = $patenteVehiculo;
    $row['tipoVehiculo'] = $tipoVehiculo;
    $row['unidadViaje'] = $unidadViaje;
    $row['conductorViaje'] = $conductorViaje;
    $row['tipoJornada'] = $tipoJornada;
    $row['numeroViaje'] = $numeroViaje;
    $row['horaInicia'] = $horaInicia;
    $row['horaTermino'] = $horaTermino;
    $row['valorViaje'] = $valorViaje;
    $row['valorExtension'] = $valorExtension;
    $row['valorTotal'] = $valorTotalSuma;
    $row['hasArchivos'] = $hasArchivos;
    $row['fechaIngresoPlata'] = $fechaIngresoPlata;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>