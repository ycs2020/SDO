<?php

include '../sdo-funciones/conexion.php';

// Función para generar código aleatorio
function generarCodigoAleatorio()
{
    // Longitud del código aleatorio
    $longitud = 6;

    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    $max = strlen($caracteres) - 1;
    // Generar caracteres aleatorios
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[mt_rand(0, $max)];
    }
    return $codigo;
}


$contador = 49;

for($i = 1; $i < $contador; $i++ ){

    $codigoAleatorio = generarCodigoAleatorio();
    // Construir ID completo
        $idGenerado = 'EQUIP-HBQP-' . $codigoAleatorio;
    echo  $idGenerado . "<br>";

//    $sql = "UPDATE equipamiento_hbqp set identificadorUnico = '$idGenerado' WHERE id = $i ";
//    $res = mysqli_query($conexion, $sql);

}
