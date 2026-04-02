<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if($_POST['id']){
    
    $idHoja = $_POST['id'];

    //Eliminar registro
    $sqlDelete = "DELETE FROM hoja_de_vida WHERE idHoja = '$idHoja'";
    $resDelete = mysqli_query($conexion ,$sqlDelete);
    
}