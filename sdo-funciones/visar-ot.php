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


if(isset($_POST['idTG'])){
    $now = date('Y-m-d H:i:s');
    $idTG = $_POST['idTG'];
    $contra = $_POST['contra'];
    $jefe = obtenerPasswordJefeVisado($nomUsuario);
    if($jefe == $contra){
        $sql = "UPDATE trabajos_general SET isVisado = 1, nombreVisado = '$nomUsuario', fechaVisado = '$now' WHERE idTG = $idTG";
        $res = mysqli_query($conexion,$sql);

        if($res){
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarOT=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarJefeOT=true');
        }

    }else{
        $admin = obtenerPasswordAdminVisado($nomUsuario);
        if($admin == $contra){
        $sqlAdmin = "UPDATE trabajos_general SET isVisado = 1, nombreVisado = '$nomUsuario', fechaVisado = '$now' WHERE idTG = $idTG";
        $resAdmin = mysqli_query($conexion,$sqlAdmin);

        if($resAdmin){
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?exitoVisarOT=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarAdminOT=true');
        }

        }else{
            header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarOT=true');
        }
    }
}else{
    header('Location: ../'.$rutaDireccion . '/visualizardetallesterminadas.php?errorVisarOT=true');
}