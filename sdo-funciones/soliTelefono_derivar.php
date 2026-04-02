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


$headerExito = 'Location: ../' . $ruta . '/solicitudes_telefonicas.php?exitoDerivar=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_telefonicas.php?errorDerivar=true';

if (isset($_POST['selectPersonalDesignado'], $_POST['idSoliTelefono'])) {

    $selectPersonalDesignado = trim($_POST['selectPersonalDesignado']);
    $idSoliTelefono = trim($_POST['idSoliTelefono']);

    if (!empty($selectPersonalDesignado) && !empty($idSoliTelefono) && $idSoliTelefono > 0) {

        $queryModificar = "UPDATE solicitudes_telefonicas SET derivaSoliTelefono = '" . $selectPersonalDesignado . "' WHERE idSoliTelefono = " . $idSoliTelefono;

        $resModificar = mysqli_query($conexion, $queryModificar);

        if ($resModificar) {
            header($headerExito);
        } else {
            header($headerError);
        }
    } else {
        header($headerError);
    }
} else {
    header($headerError);
}
