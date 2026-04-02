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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['idActa'])) {
    $idActa = $_POST['idActa'];
    $nomUsuario = $_POST['nomUsuario'];
    $fechaObs = $_POST['fechaObs'];
    $detalleObs = $_POST['detalleObs'];
    $rutFuncionario = $_POST['rutFuncionario'];
    $nomFuncionario = $_POST['nomFuncionario'];
    $calidadContractual = $_POST['calidadContractual'];
    $unidadFuncionario = $_POST['unidadFuncionario'];

    $sql = "INSERT INTO actas_observacion (idActasObs,fechaIngreso,nomFuncionario,rutFuncionario,detalleObservacion,calidadContractual,unidadFuncionario,creadorObs,idActa)
    VALUES (NULL,'".$fechaObs."','".$nomFuncionario."','".$rutFuncionario."','".$detalleObs."','".$calidadContractual."','".$unidadFuncionario."','".$nomUsuario."',$idActa)";
    $res = mysqli_query($conexion,$sql);
    if($res){
        header('Location: ../' . $rutaDireccion . '/actas-funcionario.php?exitoObservacion=true');
    }else{
        header('Location: ../' . $rutaDireccion . '/actas-funcionario.php?errorObservacion=true');
    }

}else{
    header('Location: ../' . $rutaDireccion . '/actas-funcionario.php?errorObservacion=true');
}