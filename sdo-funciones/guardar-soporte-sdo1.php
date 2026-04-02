<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria = trim($_POST['categoria']);
    $tipoUsuario = trim($_POST['tipoUsuario']);
    $idSolicitante = $_POST['idSolicitante'];
    $departamento = $_POST['departamentoSolicitante'];
    $nombre = trim($_POST['nomTecnicoRecepcionador']);
    $comentarios = trim($_POST['comentarios']);
    $estado = 'En progreso';

    if(empty($categoria) || empty($tipoUsuario) || empty($idSolicitante) || empty($departamento) || empty($nombre) || empty($comentarios) || $categoria == '' || $tipoUsuario == '' || $idSolicitante == ''){
        echo 'Todo Mal';
    } else{
        $sql = "INSERT INTO soportesdo(categoria, tipoUsuario,idSolicitante,departamentoSolicitante,estado,comentarios) VALUES('$categoria', '$tipoUsuario', '$idSolicitante', '$departamento', '$estado', '$comentarios')";
        echo $sql;
        exit;
    }





}


