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

if(isset($_POST['cantidadFilasModiRegistros'])) {
    $cantidadFilasModiRegistros = $_POST['cantidadFilasModiRegistros'];
    if($cantidadFilasModiRegistros > 0){
        for($i = 1; $i <= $cantidadFilasModiRegistros; $i++) {
            $fecha = $_POST['fecha'.$i];
            $tipoIntervencion = $_POST['tipoIntervencion'.$i];
            $empresa = $_POST['empresa'.$i];
            $oc = $_POST['oc'.$i];
            $numeroServicioTecnico = $_POST['numeroServicioTecnico'.$i];
            $realizado = $_POST['realizado'.$i];
            $obs = $_POST['obs'.$i];
            $idInsumos = $_POST['idInsumos'.$i];

            $sql = "UPDATE `registros_inge_clinica` SET `fecha`='$fecha',`tipoIntervencion`= '$tipoIntervencion',`empresa`= '$empresa',`oc`='$oc',`numeroHojaServicio`='$numeroServicioTecnico',`realizado`='$realizado',`obs`='$obs' WHERE id = $idInsumos";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_registros.php?exitoModiRegistro=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_registros.php?exitoModiRegistro=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro=true');
                }
            }
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/modificar_registros.php?errorModiRegistro2=true');
    }
}