<?php

include('funciones.php');
include_once('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = '';
$rutaRol = '';
$rutaParkingRol = '';

if (isset($_POST['rolUsuario'])) {

    $rolUsuario = trim($_POST['rolUsuario']);

    if (!empty($rolUsuario)) {
        if ($rolUsuario == "Administrador") {

            $rutaRol = "sdo-administrador/actas-listado.php";

            $rutaParkingRol = 'sdo-administrador/actas-funcionario.php';
        } else if ($rolUsuario == "Personal") {

            $rutaRol = "sdo-personal/actas-listado.php";

            $rutaParkingRol = 'sdo-personal/actas-funcionario.php';
        } else if ($rolUsuario == "Directiva") {

            $rutaRol = "sdo-directiva";

            $rutaParkingRol = 'sdo-directiva/actas-estacionamiento.php';
        } else if ($rolUsuario == "Jefe Tecnico") {

            $rutaRol = "sdo-jefestecnicos/actas-funcionario.php";

            $rutaParkingRol = 'sdo-jefestecnicos/actas-funcionario.php';
        } else if ($rolUsuario == "Administrativo") {

            $rutaRol = "sdo-admtec";

            $rutaParkingRol = 'sdo-admtec/actas-estacionamiento.php';
        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        }

        if (isset($_POST['patenteParking'], $_POST['antiguaPatente'], $_POST['modeloParking'], $_POST['idParking'], $_POST['rutActa2'], $_POST['idActa'], $_POST['nomControlCentralizado'])) {

            $patenteParking = trim($_POST['patenteParking']);
            $antiguaPatente = trim($_POST['antiguaPatente']);
            $modeloParking = trim($_POST['modeloParking']);
            $idParking = trim($_POST['idParking']);
            $rutActa2 = trim($_POST['rutActa2']);
            $idActa = trim($_POST['idActa']);
            $nomControlCentralizado = trim($_POST['nomControlCentralizado']);
            $nomControlCentralizado = mb_convert_case($nomControlCentralizado, MB_CASE_TITLE, "UTF-8");

            if (empty($patenteParking) || empty($antiguaPatente) || empty($modeloParking) || empty($idParking) || empty($rutActa2) || empty($idActa) || empty($nomControlCentralizado)) {
                header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
            } else {

                if ($antiguaPatente == $patenteParking) {

                    $buscarPatente = 0;
                } else {
                    $buscarPatente = buscarPatente($patenteParking);
                }

                // echo $buscarPatente;
                // echo '<br><br>';
                // echo buscarPatente($patenteParking);

                if ($buscarPatente == 0) {
                    $queryModificar = "UPDATE parking SET patenteParking = '" . $patenteParking . "', modeloParking = '" . $modeloParking . "' WHERE idParking = " . $idParking;

                    $resModificar = mysqli_query($conexion, $queryModificar);

                    if ($resModificar) {

                        $queryModificarActa = "UPDATE actas SET nomControlCentralizado = '" . $nomControlCentralizado . "', tipoActa = 'Actualización' WHERE idActa = " . $idActa;

                        $resModificarActa = mysqli_query($conexion, $queryModificarActa);

                        if ($resModificarActa) {

                            header('Location: ../' . $rutaParkingRol . '?exitoModPatente=true');
                        } else {
                            header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
                        }
                    } else {
                        header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
                    }
                } else {
                    header('Location: ../' . $rutaParkingRol . '?errorPatenteExiste=true');
                }
            }
        } else {
            header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
        }
    } else {
        header('Location: sdo-flujo/login_index.php?errorLogin=true');
    }
} else if (isset($_SESSION['rolUsuario'])) {

    $rolUsuario = $_SESSION['rolUsuario'];

    if (!empty($rolUsuario)) {
        if ($rolUsuario == "Administrador") {

            $rutaRol = "sdo-administrador";

            $rutaParkingRol = 'sdo-administrador/actas-estacionamiento.php';
        } else if ($rolUsuario == "Personal") {

            $rutaRol = "sdo-personal/actas-listado.php";

            $rutaParkingRol = 'sdo-personal/actas-funcionario.php';
        } else if ($rolUsuario == "Directiva") {

            $rutaRol = "sdo-directiva";

            $rutaParkingRol = 'sdo-directiva/actas-estacionamiento.php';
        } else if ($rolUsuario == "Jefe Tecnico") {

            $rutaRol = "sdo-jefestecnicos/actas-funcionario.php";

            $rutaParkingRol = 'sdo-jefestecnicos/actas-funcionario.php';
        } else if ($rolUsuario == "Administrativo") {

            $rutaRol = "sdo-admtec/actas-listado.php";

            $rutaParkingRol = 'sdo-admtec/actas-estacionamiento.php';
        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        }

        if (isset($_POST['patenteParking'], $_POST['antiguaPatente'], $_POST['modeloParking'], $_POST['idParking'], $_POST['rutActa2'], $_POST['idActa'], $_POST['nomControlCentralizado'])) {

            $patenteParking = trim($_POST['patenteParking']);
            $antiguaPatente = trim($_POST['antiguaPatente']);
            $modeloParking = trim($_POST['modeloParking']);
            $idParking = trim($_POST['idParking']);
            $rutActa2 = trim($_POST['rutActa2']);
            $idActa = trim($_POST['idActa']);
            $nomControlCentralizado = trim($_POST['nomControlCentralizado']);
            $nomControlCentralizado = mb_convert_case($nomControlCentralizado, MB_CASE_TITLE, "UTF-8");

            if (empty($patenteParking) || empty($antiguaPatente) || empty($modeloParking) || empty($idParking) || empty($rutActa2) || empty($idActa) || empty($nomControlCentralizado)) {
                header('Location: ../' . $rutaRol . '?errorModPatente=true');
            } else {

                if ($antiguaPatente == $patenteParking) {

                    $buscarPatente = 0;
                } else {
                    $buscarPatente = buscarPatente($patenteParking);
                }

                // echo $buscarPatente;
                // echo '<br><br>';
                // echo buscarPatente($patenteParking);

                if ($buscarPatente == 0) {
                    $queryModificar = "UPDATE parking SET patenteParking = '" . $patenteParking . "', modeloParking = '" . $modeloParking . "' WHERE idParking = " . $idParking;

                    $resModificar = mysqli_query($conexion, $queryModificar);

                    if ($resModificar) {

                        $queryModificarActa = "UPDATE actas SET nomControlCentralizado = '" . $nomControlCentralizado . "' WHERE idActa = " . $idActa;

                        $resModificarActa = mysqli_query($conexion, $queryModificarActa);

                        if ($resModificarActa) {

                            header('Location: ../' . $rutaParkingRol . '?exitoModPatente=true');
                        } else {
                            header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
                        }
                    } else {
                        header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
                    }
                } else {
                    header('Location: ../' . $rutaParkingRol . '?errorPatenteExiste=true');
                }
            }
        } else {
            header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
        }
    } else {
        header('Location: sdo-flujo/login_index.php?errorLogin=true');
    }
} else {
    header('Location: sdo-flujo/login_index.php?errorLogin=true');
}
