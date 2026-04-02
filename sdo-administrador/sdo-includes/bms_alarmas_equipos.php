<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';


$datos = array();

if(isset($_POST['sources'])){
    $sources = $_POST['sources'];

    if(is_array($sources)){
        $datos['estado'] = 'si';
    }else{
        $datos['estado'] = 'no';
    }
}else{
    $datos['estado'] = 'no';

}
echo json_encode($datos);


?>