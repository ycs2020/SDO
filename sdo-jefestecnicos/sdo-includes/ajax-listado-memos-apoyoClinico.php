<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


// Consulta SQL
$sql = "SELECT * FROM memorandum_apoyo_clinico ORDER BY fechaCreacionMemo DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $materiaMemo = $row['materiaMemo'];
    $fechaCreacionMemo = $row['fechaCreacionMemo'];
    $nomEmisorMemo = $row['nomEmisorMemo'];
    $cargoEmisorMemo = $row['cargoEmisorMemo'];
    $nomDestinatarioMemo = $row['nomDestinatarioMemo'];
    $cargoDestinatarioMemo = $row['cargoDestinatarioMemo'];
    $numeroMemo = $row['numeroMemo'];
    $fechaEnvioMemo = $row['fechaEnvioMemo'];

    if(empty($materiaMemo)){
        $materiaMemo = 'No Aplica';
    }

    if(empty($fechaCreacionMemo)){
        $fechaCreacionMemo2 = 'No Aplica';
    }else{
        $fechaCreacionMemo = strtotime($fechaCreacionMemo);
        $fecha = date("d-m-Y",$fechaCreacionMemo);
        $hora = date("H:i",$fechaCreacionMemo);

        $fechaCreacionMemo2 = $fecha.' a las '.$hora.' hrs.';
    }

    if(empty($nomEmisorMemo)){
        $nomEmisorMemo = 'No Aplica';
    }

    if(empty($cargoEmisorMemo)){
        $cargoEmisorMemo = 'No Aplica';
    }

    if(empty($nomDestinatarioMemo)){
        $nomDestinatarioMemo = 'No Aplica';
    }

    if(empty($cargoDestinatarioMemo)){
        $cargoDestinatarioMemo = 'No Aplica';
    }

    if($numeroMemo > 0){
        $numeroMemo2 = $numeroMemo;
    }else{
        $numeroMemo2 = 'No Aplica';
    }

    if(empty($fechaEnvioMemo)){
        $fechaEnvioMemo2 = 0;
    }else{
        $fechaEnvioMemo = strtotime($fechaEnvioMemo);
        $fecha = date("d-m-Y",$fechaEnvioMemo);
        $hora = date("H:i",$fechaEnvioMemo);

        $fechaEnvioMemo2 = $fecha.' a las '.$hora.' hrs.';
    }

    $materiaMemo2 = mb_convert_case($materiaMemo,MB_CASE_UPPER,'UTF-8');
    $fechaCreacionMemo2 = mb_convert_case($fechaCreacionMemo2,MB_CASE_UPPER,'UTF-8');
    $nomEmisorMemo2 = mb_convert_case($nomEmisorMemo,MB_CASE_UPPER,'UTF-8');
    $cargoEmisorMemo2 = mb_convert_case($cargoEmisorMemo,MB_CASE_UPPER,'UTF-8');
    $nomDestinatarioMemo2 = mb_convert_case($nomDestinatarioMemo,MB_CASE_UPPER,'UTF-8');
    $cargoDestinatarioMemo2 = mb_convert_case($cargoDestinatarioMemo,MB_CASE_UPPER,'UTF-8');
    $numeroMemo2 = mb_convert_case($numeroMemo2,MB_CASE_UPPER,'UTF-8');
    $fechaEnvioMemo2 = mb_convert_case($fechaEnvioMemo2,MB_CASE_UPPER,'UTF-8');


    $row['materiaMemo'] = $materiaMemo2;
    $row['fechaCreacionMemo'] = $fechaCreacionMemo2;
    $row['nomEmisorMemo'] = $nomEmisorMemo2;
    $row['cargoEmisorMemo'] = $cargoEmisorMemo2;
    $row['nomDestinatarioMemo'] = $nomDestinatarioMemo2;
    $row['cargoDestinatarioMemo'] = $cargoDestinatarioMemo2;
    $row['numeroMemo'] = $numeroMemo2;
    $row['fechaEnvioMemo'] = $fechaEnvioMemo2;
    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>