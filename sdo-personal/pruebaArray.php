<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$query2 = 'SELECT idEquipo, detalleObservaciones, fechaObservaciones, personalObservacion, esForzado FROM equipos_observaciones WHERE esForzado NOT LIKE "%Observación Normal%" AND esForzado NOT LIKE "%Modo Automatico%" ORDER BY fechaObservaciones DESC;';
$res2 = mysqli_query($conexion, $query2);

/* obtener el array por índices */
while ($filaObservaciones = mysqli_fetch_assoc($res2)) {
    $arrayObservaciones[] = $filaObservaciones;
}

function unique_multidim_array($arrayObservaciones, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($arrayObservaciones as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

$details = unique_multidim_array($arrayObservaciones, 'idEquipo');

foreach ($details as $key => $value) {
    $fila = $value; // obtiene fila de array actual
    echo $fila["fechaObservaciones"] . "<br><br>"; //accediendo a propiedad

    //si quieres acceder a una propiedad que no esta en todas las filas debes preguntar si existe el indice 
}
