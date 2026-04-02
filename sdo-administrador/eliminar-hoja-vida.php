<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if($_POST['idHoja']){
    
    $idHoja = $_POST['idHoja'];

    //Eliminar registro
    $sqlDelete = "DELETE FROM hoja_de_vida WHERE idHoja = '$idHoja'";
    $resDelete = mysqli_query($conexion ,$sqlDelete);
    
}