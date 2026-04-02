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

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['cantidadEquipo'])){
    $cantidadEquipo = $_POST['cantidadEquipo'];
    $idInventario = $_POST['idInventario'];
    $fechaActualizacion = $_POST['fechaActualizacion'];

    $sql = "UPDATE inventario_equipos SET cantidadInventario = '$cantidadEquipo', fechaActualizacion = '$fechaActualizacion' WHERE idInventario = $idInventario";
    $res = mysqli_query($conexion,$sql);


    if($res){
        header('Location: ../'. $ruta .'/equipamiento_hbqp.php?exitoModificarInventario=true');
    }else{
        header('Location: ../'. $ruta .'/equipamiento_hbqp.php?errorModificarInventario=true');
    }



}

