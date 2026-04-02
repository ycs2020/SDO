<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

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
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['turnoMC'], $_POST['terreno1MC'], $_POST['control1MC'], $_POST['terreno2MC'], $_POST['control2MC'], $_POST['terreno3MC'], $_POST['terreno4MC'], $_POST['control3MC'], $_POST['control4MC'])) {

    $turnoMC = trim($_POST['turnoMC']);

    $terreno1MC = trim($_POST['terreno1MC']);

    $control1MC = trim($_POST['control1MC']);

    $terreno2MC = trim($_POST['terreno2MC']);

    $control2MC = trim($_POST['control2MC']);

    $terreno3MC = trim($_POST['terreno3MC']);

    $terreno4MC = trim($_POST['terreno4MC']);

    $control3MC = trim($_POST['control3MC']);

    $control4MC = trim($_POST['control4MC']);

    $tanqueUtilizado = trim($_POST['tanqueMC']);


    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d H:i:s', time());

    if (!empty($turnoMC)) {
        if ($terreno1MC > -1 && $terreno2MC > -1 && $control1MC > -1 && $control2MC > -1 && $terreno3MC > -1 && $control3MC > -1 && $terreno4MC > -1 && $control4MC > -1 ) {
            $sqlMC = "INSERT INTO combustible_medicion(idMC,fechaMC,tecnicoMC,turnoMC,terreno1MC,control1MC,terreno2MC,control2MC,terreno3MC,terreno4MC,control3MC,control4MC,tanqueUtilizado) ";
            $sqlMC .= " VALUES(NULL,'" . $now . "','" . $nomCreador . "','" . $turnoMC . "'," . $terreno1MC . "," . $control1MC . "," . $terreno2MC . "," . $control2MC . "," . $terreno3MC . "," . $terreno4MC . "," . $control3MC . "," . $control4MC . ", '".$tanqueUtilizado."')";

            // echo $sql;

            $resMC = mysqli_query($conexion, $sqlMC);

            if ($resMC) {
                header('Location: ../' . $ruta . '/combustible_mediciones.php?exitoAgregarMC=true');
            } else {
                header('Location: ../' . $ruta . '/combustible_mediciones.php?errorAgregarMC=true');
            }
        } else {
            header('Location: ../' . $ruta . '/combustible_mediciones.php?errorAgregarMC=true');
        }
    } else {
        header('Location: ../' . $ruta . '/combustible_mediciones.php?errorAgregarMC=true');
    }


} else {
    header('Location: ../' . $ruta . '/combustible_mediciones.php?errorAgregarMC=true');
}
