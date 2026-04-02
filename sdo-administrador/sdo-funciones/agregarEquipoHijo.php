<?php 

include 'conexion.php';


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $listaEquipos = $_POST['listaEquipos'];
    $idHojaPadre = $_POST['idHojaPadre'];
 
    // var_dump($listaEquipos). "<br>";
    echo $idHojaPadre. "<br>";


    exit;
}else{
    echo "no entre al post";
}