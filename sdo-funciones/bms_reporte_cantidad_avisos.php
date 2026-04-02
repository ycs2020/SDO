<?php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

$datoEnviar = 0;

if (isset($_GET['idRegistro'])) {
    $idRegistro = (int)$_GET['idRegistro'];

    if($idRegistro > 0){
        $datoEnviar = countAvisosPorReporteAlarmas($idRegistro);
    }else{
        $datoEnviar = 0;
    }

}else{
    $datoEnviar = 0;
}

echo $datoEnviar;