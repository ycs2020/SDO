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

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
    $cargoUsuario = $_SESSION['cargoUsuario'];
    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
    $cargoUsuario = $_SESSION['cargoUsuario'];
    $cargoUsuario = trim($cargoUsuario);
    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == 'oficina tecnica'){
   $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['fechaMedicion'], $_POST['idAC'], $_POST['facturaAC'], $_POST['boletaAC'], $_POST['guiaDespachoAC'], $_POST['noCargaAC'], $_POST['tanque1AC'], $_POST['tanque2AC'], $_POST['tanque3AC'], $_POST['tanque4AC'], $_POST['precioAC'], $_POST['selectProce'], $_POST['usuarioAC'] )){
    $fechaMedicion = trim($_POST['fechaMedicion']);
    date_default_timezone_set('America/Santiago');
    $idAC = trim($_POST['idAC']);
    $facturaAC = trim($_POST['facturaAC']);
    $boletaAC = trim($_POST['boletaAC']);
    $guiaDespachoAC = trim($_POST['guiaDespachoAC']);
    $noCargaAC = trim($_POST['noCargaAC']);
    $tanque1AC = trim($_POST['tanque1AC']);
    $tanque2AC = trim($_POST['tanque2AC']);
    $tanque3AC = trim($_POST['tanque3AC']);
    $tanque4AC = trim($_POST['tanque4AC']);
    $precioAC = trim($_POST['precioAC']);
    $selectProce = trim($_POST['selectProce']);
    $observacion = trim($_POST['observacion']);
    $userUsuario1 = trim($_POST['usuarioAC']);

    $sql = "UPDATE combustible_administracion SET fechaAC = '$fechaMedicion', facturaAC = '$facturaAC', boletaAC = '$boletaAC', guiaDespachoAC = '$guiaDespachoAC', noCargaAC = '$noCargaAC', tanque1AC = '$tanque1AC', tanque2AC = '$tanque2AC', tanque3AC = '$tanque3AC', tanque4AC = '$tanque4AC', precioAC = '$precioAC', usuarioAC = '$userUsuario1', observacionAC = '$observacion', tipoAC = '$selectProce' WHERE idAC = $idAC";
    $res = mysqli_query($conexion,$sql);

    if($res){
        header('Location: ../' . $ruta . '/combustible_administracion.php?exitoModificarAC=true');
    } else{
        header('Location: ../' . $ruta . '/combustible_administracion.php?errorModificarAC=true');

    }

}else{
    header('Location: ../' . $ruta . '/combustible_administracion.php?error1=true');
}