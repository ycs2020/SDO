<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
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
$idTG = $_SESSION['idTG'];
$idTG = trim($idTG);

if(isset($_SESSION['idTG'])){
    $avanceTGT = trim($_POST['avanceTGT']);
    $observacionTGT = trim($_POST['observacionTGT']);
    $estadoCambio = $_POST['estadoCambio'];

    if($avanceTGT == '' || empty($avanceTGT || $avanceTGT == ' ')){
        $avanceTGT = 'No presenta un avance';
    }

    if($observacionTGT == ''|| empty($observacionTGT) || $observacionTGT == ' '){
        $observacionTGT = 'No presenta una observacion';
    }


    $sql = "UPDATE trabajos_general SET avanceTGT = '$avanceTGT', observacionTGT = '$observacionTGT', estadoTG = '$estadoCambio' WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);


    if($estadoCambio == 'Trabajo Terminado'){
        $sqlArchivo = "UPDATE Archivos_Trabajo_General_Tecnico SET trabajoTerminado = 1, trabajoObservacion = 0 WHERE idTG = $idTG";
        $resArchivo = mysqli_query($conexion,$sqlArchivo);
    }else{
        $sqlArchivo = "UPDATE Archivos_Trabajo_General_Tecnico SET trabajoTerminado = 0, trabajoObservacion = 1 WHERE idTG = $idTG";
        $resArchivo = mysqli_query($conexion,$sqlArchivo);
    }

    if($res){
        header('Location: ../'. $rutaDireccion .'/trabajo-orden-archivos.php?exitoModificar=true');
    }else{
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
    }
}else{
    header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');

}
