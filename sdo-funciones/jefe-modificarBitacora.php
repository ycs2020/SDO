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

if (isset($_POST['idBitacoraMod'], $_POST['obserMod'], $_POST['idSubCateg'])) {

    $idBitacora = $_POST['idBitacoraMod'];
    $modObservacionBitacora = $_POST['obserMod'];
    $subcategoriaModificar = $_POST['idSubCateg'];

    if (!empty($modObservacionBitacora)) {

        $modObservacionBitacora = str_replace("'", "\'", $modObservacionBitacora);
    }

    if ($idBitacora > 0) {

        if ($idBitacora == '' || empty($idBitacora) || $modObservacionBitacora == '' || empty($modObservacionBitacora) || $subcategoriaModificar == '' || empty($subcategoriaModificar)) {
            header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorModificarObservacion=true');
        } else {

            $queryModificar = "UPDATE bitacora SET observacionBitacora = '" . $modObservacionBitacora . "', idSubcategoria = '" . $subcategoriaModificar . "' WHERE idBitacora = " . $idBitacora;

            $resModificar = mysqli_query($conexion, $queryModificar);

            if ($resModificar) {
                header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?exitoModificarObservacion=true');
            } else {
                header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorModificarObservacion=true');
            }
        }
    } else {
        header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorModificarObservacion=true');
    }
} else {
    header('Location: ../'.$rutaDireccion.'/bitacora-listado.php?errorModificarObservacion=true');
}
