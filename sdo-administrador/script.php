<?php 

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$sql = "SELECT * FROM solicitudes_compra_sdo";
$res = mysqli_query($conexion, $sql);

while($row = $res->fetch_assoc()) {

    $datos = [];

    $datos['idSoliCompra'] = $row['idSoliCompra'];
    $datos['unidadSoliCompra'] = $row['unidadSoliCompra'];


    $idSoliCompra = $datos['idSoliCompra'];
    $unidadSoliCompra = $datos['unidadSoliCompra'];

    $insertSql = "UPDATE  solicitudes_sc_sdo_resumen SET unidadSoliCompra = '$unidadSoliCompra' WHERE idSoliCompra = $idSoliCompra";
    $resInsert = mysqli_query($conexion, $insertSql);

    if($insertSql){
        echo "TODO ACTUALIZADO";
    }else{
        echo "EROR";
    }






}

