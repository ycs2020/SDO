<?php


include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


    $id = $_POST['id'];
    $cantidad = $_POST['cantidad'];

    $cantidad = intval($cantidad);

    var_dump($id);


    foreach ($_SESSION['CARRITOMATERETIRO'] as $indice => $material) {
        if ($material['id']==$id) {
            $_SESSION['CARRITOMATERETIRO'][$indice]['sacar'] = $cantidad;
            echo 'Actualizo el producto #'.$id;
        }
    }

