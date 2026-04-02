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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idConductor2'])){

    $idConductor = $_POST['idConductor2'];

    if($idConductor > 0){

        $sqlDelete = "DELETE FROM conductores_movilizacion WHERE idConductor = '".$idConductor."'";

        $resDelete = mysqli_query($conexion, $sqlDelete);

        if($resDelete){
            header('Location: ../'.$ruta.'/conductores_administracion.php?exitoEliminarConductor=true');
        }else{
            header('Location: ../'.$ruta.'/conductores_administracion.php?errorEliminarConductor=true');
        }

    }else{
        header('Location: ../'.$ruta.'/conductores_administracion.php?errorEliminarConductor=true');
    }

}else{
    header('Location: ../'.$ruta.'/conductores_administracion.php?errorEliminarConductor=true');
}
