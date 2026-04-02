<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idEquipoEliminar'])){

    $idEquipoEliminar = $_POST['idEquipoEliminar'];
    $selectInventario = $_POST['selectInventario'];
    $idCategoria = $_POST['idCategoria'];

    if($idCategoria == 0 || empty($idCategoria)){
        $volver = 'inventario-hbqp.php';
    }else{
        $volver = 'equipamiento_hbqp.php';
    }

    if($idEquipoEliminar > 0){

        $sqlEliminarEquipo = "DELETE FROM inventario_equipos WHERE idInventario = ".$idEquipoEliminar;

        $resEliminarEquipo = mysqli_query($conexion,$sqlEliminarEquipo);

        if($resEliminarEquipo){


            header('Location: ../' . $rutaDireccion . '/'.$volver.'?exitoEliminarEquipo=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/'.$volver.'?errorEliminarEquipo=true');
        }

    }else{
        header('Location: ../' . $rutaDireccion . '/'.$volver.'?errorEliminarEquipo=true');
    }

}else{
    header('Location: ../' . $rutaDireccion . '/presupuesto-equipo.php?errorEliminarEquipo=true');
}