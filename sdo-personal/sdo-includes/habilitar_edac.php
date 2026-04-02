<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$now = date('Y-m-d H:i:s');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion  = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['fechaInicio'],$_POST['fechaTermino'])){
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];
    $nomUsuarioPost = $_POST['nomUsuario'];

    $sql = "INSERT INTO informes_edan(id, fecha,fechaTermino, folio,nomCreador)  VALUES (null,'$fechaInicio','$fechaTermino','NULL','$nomUsuarioPost')";
    $res = mysqli_query($conexion,$sql);
    if($res){
        $id = mysqli_insert_id($conexion);
        if ($id < 10) {
            $idRecepcion2 = "000" . $id;
        } else if ($id > 9 && $id < 100) {
            $idRecepcion2 = "00" . $id;
        } else if ($id > 99 && $id < 1000) {
            $idRecepcion2 = "0" . $id;
        } else if ($id > 999) {
            $idRecepcion2 = $id;
        } else {
            $idRecepcion2 = $id;
        }

        $folioTG = 'EDAN'.'-'. $idRecepcion2;

        $sqlUpdate = "UPDATE informes_edan SET folio = '$folioTG' WHERE id = $id";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);
        if($resUpdate){
            if($rolUsuario2 == 'personal'){
                header('Location: ../index.php?exitoHabilitarEDAN=true');
            }else{
                header('Location: ../'.$rutaDireccion.'/index.php?exito=true');
            }
        }else{
            if($rolUsuario2 == 'personal'){
                header('Location: ../index.php?errorHabilitarEDAN=true');
            }else{
                header('Location: ../'.$rutaDireccion.'/index.php?error1=true');
            }
        }
    }else{
        if($rolUsuario2 == 'personal'){
            header('Location: ../index.php?errorHabilitarEDAN=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/index.php?error2=true');
        }
    }
}else{
    if($rolUsuario2 == 'personal'){
        header('Location: ../index.php?errorHabilitarEDAN=true');
    }else{
        header('Location: ../'.$rutaDireccion.'/index.php?error3=true');
    }
}

?>
