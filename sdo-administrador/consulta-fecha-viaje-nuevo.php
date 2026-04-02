<?php

include '../sdo-funciones/conexion.php';

$fechaViaje = $_POST['valor'];



if($fechaViaje <= '1969-01-01'){
    $existe = 1;
}else{
    $existe = 0;
}
echo json_encode($existe);