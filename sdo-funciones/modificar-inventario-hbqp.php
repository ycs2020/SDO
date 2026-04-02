<?php
include 'conexion.php';
include 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $selectInventario = $_POST['selectInventario'];
    $nombreEquipo = $_POST['nombreEquipo'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $cantidadEquipo = $_POST['cantidadEquipo'];
    $precioUnitario = $_POST['precioUnitario'];
    $unidadEquipo = $_POST['unidadEquipo'];
    $obs = $_POST['obs'];
    $idEquipoModificar = $_POST['idEquipoModificar'];

    $sql = "UPDATE inventario_equipos SET equipoInventario = '$nombreEquipo', marcaInventario = '$marcaEquipo', modeloInventario = '$modeloEquipo', cantidadInventario = $cantidadEquipo,categoriaInventario = $selectInventario,observacion = '$obs',valorUnitario = $precioUnitario,unidad = '$unidadEquipo' WHERE idInventario = $idEquipoModificar";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location: ../'.$rutaDireccion . '/inventario-hbqp.php?exitoModificar=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/inventario-hbqp.php?errorModificar=true');
    }
}