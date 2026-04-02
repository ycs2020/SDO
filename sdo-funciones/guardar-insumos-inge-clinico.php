<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
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
} else if($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['cantidadFilasInsumos'],$_POST['idEquipo'])){
    $cantidadFilasInsumos = $_POST['cantidadFilasInsumos'];
    $idEquipo = $_POST['idEquipo'];
    if($cantidadFilasInsumos > 0){
        for ($i = 1; $i <= $cantidadFilasInsumos; $i++) {
            $insumos = $_POST['insumosSC'.$i];
            $tipo = $_POST['tiposSC'.$i];
            $marca = $_POST['marcainsumosSC'.$i];
            $cantidad = $_POST['cantidadinsumosSC'.$i];
            $observacion = $_POST['observacion'.$i];

            $sql = "INSERT INTO `detalle_insumos_inge_clinica`(`id`, `insumos`, `marca`, `tipo`, `cantidad`, `obs`, `idEquipo`) 
            VALUES (NULL,'$insumos','$marca','$tipo','$cantidad','$observacion',$idEquipo)";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?exitoInsumo=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?exitoInsumo=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarInsumo=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarInsumo=true');
                }
            }
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarInsumos1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarInsumos1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarInsumos2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarInsumos2=true');
    }
}