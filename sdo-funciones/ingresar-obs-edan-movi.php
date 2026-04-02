<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

date_default_timezone_set('America/Santiago');

$now = date('Y-m-d H:i:s');

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
} else if($rolUsuario2 == "oficina tecnica"){
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['id'],$_POST['idDepa'])) {
    $id = $_POST['id'];
    $idDepa = $_POST['idDepa'];
    $nomUsuario = $_POST['nomUsuario'];
    $obs = trim($_POST['obs_6-15']);

    $sql = "INSERT INTO `observacion_edan_movi`(`id`, `detalleObservacion6_15`, `nomCreador`, `fecha`, `especialidad`, `idEdan`) 
    VALUES (NULL,'$obs','$nomUsuario','$now',$idDepa,$id)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/listar_informe_edan_especialidades.php?exito=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/edan_detalle_especialidades.php?exito=true');
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/listar_informe_edan_especialidades.php?error1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/edan_detalle_especialidades.php?error1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/listar_informe_edan_especialidades.php?error2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/listar_informe_edan_especialidades.php?error2=true');
    }
}