<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

$query = 'SELECT * FROM acs_door ORDER BY idUnique ASC';

$res = mysqli_query($conexion, $query);

while ($row = $res->fetch_assoc()) {

    echo 'Channel idUnique="'.$row['idUnique'].'" idDevice="'.$row['idDevice'].'" idRegion="'.$row['idRegion'].'" name="'.$row['name'].'" no="'.$row['no'].'" type="'.$row['type'].'" /><br>';

}