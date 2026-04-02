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

if(isset($_POST['cantidadFilasFuncionario'],$_POST['idEquipo'])){
    $cantidadFilasFuncionario = $_POST['cantidadFilasFuncionario'];
    $idEquipo = $_POST['idEquipo'];

    if($cantidadFilasFuncionario > 0){
        for ($i = 1; $i <= $cantidadFilasFuncionario; $i++) {
            $articuloSC = $_POST['articuloSC'.$i];
            $cantidadSC = $_POST['cantidadSC'.$i];
            $marcaSC = $_POST['marcaSC'.$i];
            $modeloSC = $_POST['modeloSC'.$i];
            $serieSC = $_POST['serieSC'.$i];
            $observacion = $_POST['observacion'.$i];

            $sql = "INSERT INTO `detalle_accesorios_inge_clinica`(`id`, `accesorios`, `cantidad`, `marca`, `modelo`, `serie`, `obs`, `idEquipo`) 
            VALUES (null,'$articuloSC',$cantidadSC,'$marcaSC','$modeloSC','$serieSC','$observacion',$idEquipo)";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?exitoAccesorio=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?exitoAccesorio=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarAccesorio=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarAccesorio=true');
                }
            }
        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarAccesorio1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarAccesorio1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/equipo-inge-clinica.php?errorGenerarAccesorio2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/equipo.php?errorGenerarAccesorio2=true');
    }
}