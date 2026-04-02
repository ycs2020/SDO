<?php

include '../sdo-funciones/conexion.php';

$numeroViaje = $_POST['valor'];

$sql = "SELECT numeroViaje FROM viajes_movilizacion_vamoos_spa WHERE numeroViaje = '$numeroViaje'";
$result = mysqli_query($conexion,$sql);



if($result->num_rows > 0){
    $existe = 1;
}else{
    $existe = 0;
}
echo json_encode($existe);