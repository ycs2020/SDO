<?php

include '../sdo-funciones/conexion.php';


$sql = 'SELECT * FROM viajes_movilizacion WHERE unidadViaje = ""   ';
$res = mysqli_query($conexion, $sql);

$contador = 0;

while($row = $res->fetch_assoc()){
    $datos = [];
    $datos['idViaje'] = $row['idViaje'];
    $datos['motivoViaje'] = $row['motivoViaje'];
    $motivoViaje = $datos['motivoViaje'];
    $idViaje = $datos['idViaje'];

    $motivoViaje = mb_convert_case($motivoViaje, MB_CASE_UPPER, 'UTF-8');

    echo $motivoViaje . "<br/>";
    $sqlUpdate = "UPDATE viajes_movilizacion SET unidadViaje = '$motivoViaje' WHERE idViaje = '$idViaje'  " ;

    echo $sqlUpdate;
    $resUpdate = mysqli_query($conexion, $sqlUpdate);

    $contador++;

}

echo "LISTONGO";

echo $contador;

