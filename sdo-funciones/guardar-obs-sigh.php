<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


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
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idTicketSIGH = $_POST['idTicketSIGH'];
    $obs = $_POST['obs'];
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `observaciones_ticket_sigh`(`idObs`, `detalle`, `fecha`, `creador`, `idSolicitudTicket`) 
    VALUES (null,'$obs','$now','$nomUsuario',$idTicketSIGH)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location: ../'.$ruta.'/tickets_sigh.php?exitoObs=true');
    }else{
        header('Location: ../'.$ruta.'/tickets_sigh.php?errorObs=true');
    }
}else{
    header('Location: ../'.$ruta.'/tickets_sigh.php?errorObs=true');
}