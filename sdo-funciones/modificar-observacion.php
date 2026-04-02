<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
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
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{

}

if(isset($_POST['idCambio'],$_POST['fechaEstado'],$_POST['estadoCambiar'],$_POST['idEquipo'],$_POST['idSeleccionar'],$_POST['observacion'],$_POST['nombreEquipo'])){
    $idCambio = $_POST['idCambio'];
    $fechaEstado = $_POST['fechaEstado'];
    $estadoCambiar = $_POST['estadoCambiar'];
    $idEquipo = $_POST['idEquipo'];
    $idSeleccionar = $_POST['idSeleccionar'];
    $observacion = $_POST['observacion'];
    $nombreEquipo = $_POST['nombreEquipo'];


    if($idSeleccionar == 'Seleccionar'){
        $sql = "UPDATE historial_cambios_equipos SET fecha = '$fechaEstado', estado = '$estadoCambiar', observaciones = '$observacion', idEquipo = '$idEquipo' WHERE idCambio = $idCambio";
        $res = mysqli_query($conexion,$sql);

        $sql = "UPDATE historial_cambios_equipos_respaldo SET fecha = '$fechaEstado', estado = '$estadoCambiar', observaciones = '$observacion', idEquipo = '$idEquipo' WHERE idCambio = $idCambio ";
        $res = mysqli_query($conexion,$sql);

    }else{
        $sql = "UPDATE historial_cambios_equipos SET nombreEquipo = '$nombreEquipo', fecha = '$fechaEstado', estado = '$estadoCambiar', observaciones = '$observacion', idEquipo = '$idSeleccionar' WHERE idCambio = $idCambio";
        $res = mysqli_query($conexion,$sql);

        $sql = "UPDATE historial_cambios_equipos_respaldo SET nombreEquipo = '$nombreEquipo', fecha = '$fechaEstado', estado = '$estadoCambiar', observaciones = '$observacion', idEquipo = '$idSeleccionar' WHERE idCambio = $idCambio ";
        $res = mysqli_query($conexion,$sql);
    }

    if($res){
        header('Location: ../'.$ruta.'/carpeta-equipo.php?exitoModi=true');
    }else{
        header('Location: ../'.$ruta.'/carpeta-equipo.php?error1=true');
    }

}else{
    header('Location: ../'.$ruta.'/carpeta-equipo.php?error0=true');
}