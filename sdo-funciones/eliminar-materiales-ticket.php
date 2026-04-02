<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idArriendo = $_POST['idArriendo'];
    $idMaterial  = $_POST['idMaterial'];
    $cantidadSacar = $_POST['cantidadMaterial'];
    //$codSolicitud = $_POST['codSolicitud'];

    // var_dump($idArriendo);
    // var_dump($cantidadSacar);

    // exit;

    //ACTUALIZA STOCK DE LA HERRAMIENTA
    $material = "SELECT * FROM pañol_materiales WHERE idMaterial = ${idMaterial}";
    $resMaterial = mysqli_query($conexion, $material);

    while ($row = $resMaterial->fetch_assoc()) {

        $cantidadActual = $row['cantMaterial'];
    }

    $nuevaCantidad = $cantidadActual + $cantidadSacar;

    $updateMaterial = "UPDATE pañol_materiales SET cantMaterial = ${nuevaCantidad} WHERE idMaterial = ${idMaterial}";
    $res = mysqli_query($conexion, $updateMaterial);


    //ELIMINAR REGISTRO DE LA TABLA HERRA_EN_ARRIENDO
    $sqlDelete = "DELETE FROM material_retirado WHERE idMateRetirado = ${idArriendo}";
    $resDelete = mysqli_query($conexion, $sqlDelete);

    if ($resDelete) {

        header('Location: ../sdo-bodega-panol/listado-soli-ticket.php?mateEliminado=true');
    }
}
