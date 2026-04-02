<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');


$sql = "SELECT * FROM formularios_acceso ORDER BY unidadFormCCAA ASC";
$res = mysqli_query($conexion, $sql);
if($res->num_rows > 0){
    $datos = array();

    while($row = $res->fetch_assoc()){
        $unidadFormCCAA = $row['unidadFormCCAA'];
        $idFormCCAA = $row['idFormCCAA'];
        $datos[] = $row['unidadFormCCAA'];
        $datos[] = $row['idFormCCAA'];
    }
    echo json_encode($datos);
}else{
    echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
}