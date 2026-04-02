<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Santiago');
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion  = "sdo-directiva";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $detalleCancelar = $_POST['detalleCancelar'];
    $now = date('Y-m-d H:i:s');
    $nomCreador = $_POST['nomCreador'];
    $idLineaVida = $_POST['idLineaVida'];

    $sql = "INSERT INTO `rechazo_panol`(`idRechazo`, `nomCreador`, `fecha`, `detalle`, `idTG`, `idLineaVida`) 
    VALUES (NULL,'".$nomCreador."','".$now."','".$detalleCancelar."',$idTG,$idLineaVida)";
    $res = mysqli_query($conexion,$sql);

    if($res){
        $sqlUpdate = "UPDATE linea_vida_ot SET rechazadoPanol = 1 AND isPanol = 0 WHERE idLineaVida = $idLineaVida";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);
        if($resUpdate){
            if($tipoUsuario !== 'personal'){
                header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?exitoCancelar=true');
            }else{
                header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?exitoCancelar=true');
            }
        }else{
            if($tipoUsuario !== 'personal'){
                header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorcancelar=true');
            }else{
                header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorcancelar=true');
            }
        }
    }else{
        if($tipoUsuario !== 'personal'){
            header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorcancelar=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorcancelar=true');
        }
    }
}else{
    if($tipoUsuario !== 'personal'){
        header('Location: ../'.$rutaDireccion.'/detalle_ordenes_trabajo.php?errorcancelar=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/visualizardetalles.php?errorcancelar=true');
    }
}