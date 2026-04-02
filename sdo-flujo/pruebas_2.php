<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');
/*
$sql = "SELECT * FROM formularios_funcionarios";
$res = mysqli_query($conexion,$sql);
while($row = $res->fetch_assoc()){
    $idForm = $row['idFormRegAcceso'];
    $sqlUpdate = "SELECT unidadFormRegAcceso FROM formularios_registro WHERE idFormRegAcceso = $idForm";
    $resUpdate = mysqli_query($conexion,$sqlUpdate);
    while($rowUpdate = $resUpdate->fetch_assoc()){
        $unidadFormRegAcceso = $rowUpdate['unidadFormRegAcceso'];
        $sqlUpdate11 = "UPDATE formularios_funcionarios SET idUnidad = $unidadFormRegAcceso WHERE idFormRegAcceso = $idForm";
        $resUpdate11 = mysqli_query($conexion,$sqlUpdate11);
        if($resUpdate11){
            echo 'TODO BIEN'.'<br>';
        }else{
            echo 'TODO MAL'.'<br>';
        }
    }

}
*/