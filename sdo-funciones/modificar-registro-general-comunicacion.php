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

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
}else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $responsable = $_POST['responsable'];
    $asistentes = $_POST['asistentes'];

    if (is_array($responsable)) {
        sort($responsable);
        // Unir los elementos del array con ' - ' como delimitador
        $responsable = implode(' - ', $responsable);
    } else {
        $responsable = '';
    }
    if (is_array($asistentes)) {
        sort($asistentes);
        // Unir los elementos del array con ' - ' como delimitador
        $asistentes = implode(' - ', $asistentes);
    } else {
        $asistentes = '';
    }
    $responsable = trim($responsable);
    $asistentes = trim($asistentes);
    $tema = $_POST['tema'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];
    $turno = $_POST['turno'];
    $obs = $_POST['obs'];
    if (!empty($obs)) {
        $obs = str_replace("'", "\'", $obs);
    }
    $update = "UPDATE `registro_general_comunicacion` SET inicio = '$inicio',fin = '$fin', asistentes = '$asistentes', tema = '$tema' ,`responsables`='$responsable',`obs`='$obs',`turno`='$turno' WHERE id = $id";
    $res = mysqli_query($conexion,$update);
    if($res){
        header('Location: ../'.$ruta.'/registros_comunicacion.php?exitoModificarRegistro=true');
    }else{
        header('Location: ../'.$ruta.'/registros_comunicacion.php?errorModificarRegistro=true');

    }
}


