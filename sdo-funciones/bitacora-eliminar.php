<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

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
} else if($rolUsuario2 == "oficina tecnica"){
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['idBitacora'])) {

    $idBitacora = trim($_POST['idBitacora']);

    if (empty($idBitacora) || $idBitacora == "" || $idBitacora == " ") {
        header('Location: ../'.$rutaDireccion . '/bitacora-listado.php?errorEliminarObservacion=true');
    } else {

        $queryBitacora = "DELETE FROM bitacora WHERE idBitacora=" . $idBitacora;
        $resBitacora = mysqli_query($conexion, $queryBitacora);

        if ($resBitacora) {
            header('Location: ../'.$rutaDireccion . '/bitacora-listado.php?exitoEliminarObservacion=true');
        } else {
            header('Location: ../'.$rutaDireccion . '/bitacora-listado.php?errorEliminarObservacion=true');
        }
    }
} else {
    header('Location: ../'.$rutaDireccion . '/bitacora-listado.php?errorEliminarObservacion=true');
}
