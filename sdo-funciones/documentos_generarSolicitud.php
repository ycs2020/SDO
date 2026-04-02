<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');

$fechaNow = date('Y-m-d H:i:s');

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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['tituloDoc'],$_POST['generarDocSolic'],$_POST['nomCreadorDocs'],$_POST['rolCreadorDocs'])){

    $tituloDoc = $_POST['tituloDoc'];
    $generarDocSolic = $_POST['generarDocSolic'];
    $generarDocSolic = htmlspecialchars_decode($generarDocSolic);
//    $generarDocSolic = $conexion->real_escape_string($generarDocSolic);
    $nomCreadorDocs = $_POST['nomCreadorDocs'];
    $rolCreadorDocs = $_POST['rolCreadorDocs'];

    if(!empty($tituloDoc) && !empty($generarDocSolic) && !empty($nomCreadorDocs) && !empty($rolCreadorDocs)){

        $sql = "INSERT INTO documentos_generados(idDoc,tituloDoc,textoDoc,fechaDoc,creadorDoc,rolCreadorDoc) VALUES (NULL,'$tituloDoc','$generarDocSolic','$fechaNow','$nomCreadorDocs','$rolCreadorDocs')";

//        echo $sql;

        $resultado = mysqli_query($conexion, $sql);

        if($resultado){
            header('Location: ../'.$rutaDireccion . '/documentosGenerados_listado.php?exitoGenerar=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/documentosGenerados_listado.php?errorGenerar=true');
        }
    }else{
        header('Location: ../'.$rutaDireccion . '/documentosGenerados_listado.php?errorGenerar=true');
    }

}else{
    header('Location: ../'.$rutaDireccion . '/documentosGenerados_listado.php?errorGenerar=true');
}