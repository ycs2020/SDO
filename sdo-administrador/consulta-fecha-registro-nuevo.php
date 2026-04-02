<?php

include '../sdo-funciones/conexion.php';

$fechaRegistroVale = $_POST['valor'];



if($fechaRegistroVale <= '1969-01-01'){
    $existe = 1;
}else{
    $existe = 0;
}
echo json_encode($existe);