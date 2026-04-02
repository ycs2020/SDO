<?php

include('funciones.php');
include_once('conexion.php');


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}
$headerTextCorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?exitoAgregarObservacion=true';
$headerTextIncorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorAgregarObservacion=true';

if (isset($_POST['observacionEquipo'], $_POST['idEquipo'], $_POST['personalObservacion'])) {

    $observacionEquipo = $_POST['observacionEquipo'];
    $idEquipo = $_POST['idEquipo'];
    $personalObservacion = $_POST['personalObservacion'];

    if (!empty($observacionEquipo)) {

        $observacionEquipo = str_replace("'", "\'", $observacionEquipo);
    }

    $idEquipamiento = $_POST['idEquipamiento'];
    $idEquipamientoPadre = $_POST['idEquipamientoPadre'];
    if($idEquipamiento > 0){
        if($idEquipamientoPadre > 0){
            $_SESSION['idEquipo'] = $idEquipamientoPadre;
        }else{
            $_SESSION['idEquipo'] = $idEquipamiento;
        }
        $headerTextCorrecto = 'Location: ../' . $ruta . '/equipo.php?exitoAgregarObservacion=true';
        $headerTextIncorrecto = 'Location: ../' . $ruta . '/equipo.php?errorAgregarObservacion=true';
    }

    if (!empty($observacionEquipo) && $idEquipo > 0 && !empty($personalObservacion)) {
        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s', time());
        $query = "INSERT INTO equipos_observaciones(idObservaciones,detalleObservaciones,fechaObservaciones,personalObservacion,idEquipo,esForzado)";
        $query .=  "VALUES (NULL,'" . $observacionEquipo . "','".$now."','" . $personalObservacion . "'," . $idEquipo . ",'Observación Normal')";
        
        $res = mysqli_query($conexion, $query);

        if ($res) {
            header($headerTextCorrecto);
        } else {
            header($headerTextIncorrecto);
        }
    } else {
        header($headerTextIncorrecto);
    }
} else {
    header($headerTextIncorrecto);
}

mysqli_close($conexion);
