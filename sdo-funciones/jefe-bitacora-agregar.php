<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

if (isset($_POST['categoriaBitacora'], $_POST['observacionBitacora'], $_POST['tecnicoBitacora'])) {

    $categoriaBitacora = $_POST['categoriaBitacora'];
    $observacionBitacora = $_POST['observacionBitacora'];
    $tecnicoBitacora = $_POST['tecnicoBitacora'];

    if (!empty($observacionBitacora)) {

        $observacionBitacora = str_replace("'", "\'", $observacionBitacora);
    }


    if (!empty($categoriaBitacora) && !empty($observacionBitacora)  && !empty($tecnicoBitacora) && $categoriaBitacora > 0 && $tecnicoBitacora > 0) {

        $nomTecnico = getNomJefeTecnico($tecnicoBitacora);
        if ($nomTecnico == null || $nomTecnico == "Error") {
            header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorAgregarObservacion=true');
            exit();
        }
        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s', time());


        $query = "INSERT INTO bitacora(idBitacora,observacionBitacora,fechaBitacora,tecnicoBitacora,idSubcategoria)";
        $query .=  "VALUES (NULL,'" . $observacionBitacora . "','" . $now . "','" . $nomTecnico . "'," . $categoriaBitacora . ")";

        // echo $query;

        $res = mysqli_query($conexion, $query);

        if ($res) {
            header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?exitoAgregarObservacion=true');
        } else {
            header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorAgregarObservacion=true');
        }
    } else {
        header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorAgregarObservacion=true');
    }
} else {
    header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorAgregarObservacion=true');
}

mysqli_close($conexion);
