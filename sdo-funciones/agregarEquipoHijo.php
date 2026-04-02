<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $ruta = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEquipoPadre = $_POST['idEquipoPadre'];
    $idEquipoHijo = $_POST['idEquipoHijo'];
    $isMenuHijo = $_POST['isMenuHijo'];

    if($isMenuHijo == 'hijo'){
        $obtenerIDEquipamientoPadre = obtenerIDEquipamientoPadre($idEquipoPadre);
        if($obtenerIDEquipamientoPadre > 0) {
            $_SESSION['idEquipo'] = $obtenerIDEquipamientoPadre;
            $obtenerTipoMenu = obtenerTipoMenu($obtenerIDEquipamientoPadre);
        }else{
            $_SESSION['idEquipo'] = $idEquipoPadre;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipoPadre);
        }
        if($obtenerTipoMenu == 'nuevo'){
            $sitioWeb = 'equipo_menuNuevo.php';
        }else{
            $sitioWeb = 'equipo_menuAntiguo.php';
        }
    }else{
        $_SESSION['idEquipo'] = $idEquipoPadre;
        $sitioWeb = 'equipo_menuAntiguo.php';
    }


    if (isset($_POST['idEquipoPadre']) && isset($_POST['idEquipoHijo'])) {

        $sqlUpdate = "UPDATE equipamiento_hbqp SET idEquipoPadre = $idEquipoPadre WHERE id = $idEquipoHijo";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $_SESSION['idEquipo'] = $idEquipoPadre;

        if ($resUpdate) {
            header('Location: ../'.$ruta .'/'.$sitioWeb.'?exitoHijos=true');
        } else {
            header('Location: ../'.$ruta .'/'.$sitioWeb.'?errorHijos=true');
        }
    } else {
        header('Location: ../'.$ruta .'/'.$sitioWeb.'?noHijos=true');
    }
} else {
    header('Location: ../'.$ruta .'/equipamiento_hbqp.php?error=true');
}
