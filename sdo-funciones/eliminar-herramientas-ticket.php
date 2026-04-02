<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idArriendo = $_POST['idArriendo'];
    $idHerramienta = $_POST['idHerramienta'];
    $cantidadSacar = $_POST['cantidadHerramienta'];

    // var_dump($idArriendo);
    // var_dump($cantidadSacar);

    // exit;

    //ACTUALIZA STOCK DE LA HERRAMIENTA
    $herra = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
    $resHerra = mysqli_query($conexion, $herra);

    while ($row = $resHerra->fetch_assoc()) {

        $cantidadActual = $row['cantHerramienta'];
    }

    $nuevaCantidad = $cantidadActual + $cantidadSacar;

    $updateHerra = "UPDATE pañol_herramientas SET cantHerramienta = ${nuevaCantidad} WHERE idHerramienta = ${idHerramienta}";
    $res = mysqli_query($conexion, $updateHerra);


    //ELIMINAR REGISTRO DE LA TABLA HERRA_EN_ARRIENDO
    $sqlDelete = "DELETE FROM herra_en_arriendo WHERE idArriendo = ${idArriendo}";
    $resDelete = mysqli_query($conexion, $sqlDelete);

    if ($resDelete) {

        header('Location: ../sdo-bodega-panol/listado-soli-ticket.php?herraEliminado=true');
    }
}
