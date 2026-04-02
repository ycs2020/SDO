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
if(isset($_POST['cantidadFilasModiInsumos'])){
    $cantidadFilasModiInsumos = $_POST['cantidadFilasModiInsumos'];
    if($cantidadFilasModiInsumos > 0){
        for($i = 1; $i <= $cantidadFilasModiInsumos; $i++) {
            $insumos = $_POST['insumosSC'.$i];
            $idInsumos = $_POST['idInsumos'.$i];
            $tipos = $_POST['tiposSC'.$i];
            $marcainsumos = $_POST['marcainsumosSC'.$i];
            $cantidadinsumos = $_POST['cantidadinsumosSC'.$i];
            $observacion = $_POST['observacion'.$i];

            $sql = "UPDATE `detalle_insumos_inge_clinica` SET insumos = '$insumos',`marca`='$marcainsumos',`tipo`='$tipos',`cantidad`='$cantidadinsumos',`obs`='$observacion' WHERE id = $idInsumos";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_insumos.php?exitoModiInsu=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_insumos.php?exitoModiInsu=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu=true');
                }
            }
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiInsu2=true');
    }
}

