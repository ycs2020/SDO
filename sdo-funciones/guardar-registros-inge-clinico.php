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

if(isset($_POST['cantidadFilasRegistros'],$_POST['idEquipo'])){
    $cantidadFilasRegistros = $_POST['cantidadFilasRegistros'];
    $idEquipo = $_POST['idEquipo'];
    if($cantidadFilasRegistros > 0){
        for($i = 1; $i <= $cantidadFilasRegistros; $i++){
            $fecha = $_POST['fecha'.$i];
            $tipoIntervencion = $_POST['tipoIntervencion'.$i];
            $empresa = $_POST['empresa'.$i];
            $oc = $_POST['oc'.$i];
            $numeroServicioTecnico = $_POST['numeroServicioTecnico'.$i];
            $realizado = $_POST['realizado'.$i];
            $obs = $_POST['obs'.$i];

            $sql = "INSERT INTO `registros_inge_clinica`(`id`, `fecha`, `tipoIntervencion`, `empresa`, `oc`, `numeroHojaServicio`, `realizado`, `obs`, `idEquipo`) 
            VALUES (NULL,'$fecha','$tipoIntervencion','$empresa','$oc','$numeroServicioTecnico','$realizado','$obs',$idEquipo)";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?exitoRegistro=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?exitoRegistro=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarRegistro=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarRegistro=true');
                }
            }
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarRegistros1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarRegistros1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarRegistros2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarRegistros2=true');
    }
}