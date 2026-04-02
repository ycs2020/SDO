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

if(isset($_POST['cantidadFilasModiAccesorios'])){
    $cantidadFilasModiAccesorios = $_POST['cantidadFilasModiAccesorios'];
    if($cantidadFilasModiAccesorios > 0){
        for($i = 1; $i <= $cantidadFilasModiAccesorios; $i++){
            $idAccesorio = $_POST['idAccesorio'.$i];
            $serie = $_POST['serieSCModi'.$i];
            $cantidad = $_POST['cantidadSCModi'.$i];
            $marca = $_POST['marcaSCModi'.$i];
            $modelo = $_POST['modeloSCModi'.$i];
            $observacion = $_POST['observacionModi'.$i];
            $articulo = $_POST['articuloSCModi'.$i];

            $sql = "UPDATE `detalle_accesorios_inge_clinica` SET `accesorios`='$articulo',`cantidad`='$cantidad',`marca`='$marca',`modelo`='$modelo',`serie`='$serie',`obs`='$observacion' WHERE id = $idAccesorio";
            $res = mysqli_query($conexion,$sql);
            if($res){
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?exitoModiAcce=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?exitoModiAcce=true');
                }
            }else{
                if($tipoUsuario == 'personal'){
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce=true');
                }
            }

        }
    }else{
        if($tipoUsuario == 'personal'){
            header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce1=true');
        }else{
            header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce1=true');
        }
    }
}else{
    if($tipoUsuario == 'personal'){
        header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce2=true');
    }else{
        header('Location: ../'.$rutaDireccion . '/modificar_accesorios.php?errorModiAcce2=true');
    }
}