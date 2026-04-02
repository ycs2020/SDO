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
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
    $cargoUsuario = $_SESSION['cargoUsuario'];
    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
    $cargoUsuario = $_SESSION['cargoUsuario'];
    $cargoUsuario = trim($cargoUsuario);
    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}



date_default_timezone_set('America/Santiago');

if(isset($_POST['fechaMC'], $_POST['idMC'], $_POST['turnoMC'],$_POST['control1MC'],$_POST['control2MC'], $_POST['control3MC'], $_POST['control4MC'])){
    $fechaMC = $_POST['fechaMC'];
    $idMC = $_POST['idMC'];
    $turnoMC = $_POST['turnoMC'];
    $terreno1MC = $_POST['terreno1MC'];
    $terreno2MC = $_POST['terreno2MC'];
    $terreno3MC = $_POST['terreno3MC'];
    $terreno4MC = $_POST['terreno4MC'];
    $control1MC = $_POST['control1MC'];
    $control2MC = $_POST['control2MC'];
    $control3MC = $_POST['control3MC'];
    $control4MC = $_POST['control4MC'];
    $tanqueUtilizado = $_POST['tanqueUtilizado'];

    if(empty($idMC) || empty($turnoMC) || empty($control1MC) || empty($control2MC) || empty($control3MC) || empty($control4MC)){
        header('Location: ../' . $ruta . '/combustible_mediciones.php?errorModificarMC1=true');
    } else{
        $sql = "UPDATE combustible_medicion SET fechaMC = '$fechaMC', turnoMC = '$turnoMC', terreno1MC = '$terreno1MC', terreno2MC = '$terreno2MC', terreno3MC = '$terreno3MC', terreno4MC = '$terreno4MC', control1MC = '$control1MC', control2MC = '$control2MC', control3MC = '$control3MC', control4MC = '$control4MC', tanqueUtilizado = '$tanqueUtilizado' WHERE idMC = '$idMC'";
        $res =  mysqli_query($conexion,$sql);

        if($res){
            header('Location: ../' . $ruta . '/combustible_mediciones.php?exitoModificarMC=true');
        } else{
            header('Location: ../' . $ruta . '/combustible_mediciones.php?errorModificarMC2=true');
        }
    }
}else{
    header('Location: ../' . $ruta . '/combustible_mediciones.php?errorModificarMC3=true');
}

