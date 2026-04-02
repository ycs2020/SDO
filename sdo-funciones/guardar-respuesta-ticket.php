<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";

if ($rolUsuario2 == "personal") {

    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {

    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $rutaUsuario = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaUsuario = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responsableModal = $_POST['responsableModal'];
    $estadoModal = trim($_POST['estadoModal']);
    $subestadoModal = trim($_POST['subestadoModal']);
    $fecha = $_POST['fechaResolucionModal'];
    $respuestaModal  = trim($_POST['respuestaModal']);
    $respuestaModal = nl2br($respuestaModal);
    $idSoporte = $_POST['idModal'];




    if(empty($idSoporte) || empty($respuestaModal) || empty($responsableModal) || empty($subestadoModal) || empty($fecha) || empty($estadoModal) || $respuestaModal == '' || $responsableModal == '' || $estadoModal == '' || $fecha == '' || $subestadoModal == '' || $idSoporte == ''){
        header('Location: ../' . $rutaUsuario . '/soporte-sdo.php?errorTicket1=true');
    } else{

        $sqlUpdate = "UPDATE soportesdo SET estado = '$estadoModal', subestado = '$subestadoModal', fechaResolucion = '$fecha', respuesta = '$respuestaModal', idResponsable = '$responsableModal' WHERE idSoporte = $idSoporte";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);


        if($resUpdate){
            header('Location: ../' . $rutaUsuario . '/soporte-sdo.php?exito=true');
        } else{
            echo 'Todo Mal';
        }
    }


}else{
    header('Location: ../' . $rutaUsuario . '/soporte-sdo.php?errorAlMandarlosDatos=true');
}

