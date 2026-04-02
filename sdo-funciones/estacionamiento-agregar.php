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

            $rutaRol = "sdo-admtec/actas-listado.php";

            $rutaParkingRol = 'sdo-admtec/actas-estacionamiento.php';
        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        }

        if (isset($_POST['patenteParking'], $_POST['modeloParking'], $_POST['idActa'], $_POST['rutTarjeta'], $_POST['nomControlCentralizado'])) {

            $patenteParking = trim($_POST['patenteParking']);

            $modeloParking = trim($_POST['modeloParking']);
            $rutTarjeta = trim($_POST['rutTarjeta']);
            $idActa = trim($_POST['idActa']);
            $nomControlCentralizado = trim($_POST['nomControlCentralizado']);
            $nomControlCentralizado = mb_convert_case($nomControlCentralizado, MB_CASE_TITLE, "UTF-8");

            if (empty($patenteParking) || empty($modeloParking) || empty($idActa) || empty($rutTarjeta) || empty($nomControlCentralizado)) {
                header('Location: ../' . $rutaParkingRol . '?errorModPatente=true');
            } else {


                $buscarPatente = buscarPatente($patenteParking);

                $contarPatente = contarPatente($rutTarjeta);

                // echo $buscarPatente;
                // echo '<br><br>';
                // echo buscarPatente($patenteParking);

                if ($buscarPatente == 0) {
                    if ($contarPatente < 21) {
                        $queryAgregar = "INSERT INTO parking(idParking,patenteParking,modeloParking,rutParking) ";
                        $queryAgregar .= "VALUES(NULL,'" . $patenteParking . "','" . $modeloParking . "','" . $rutTarjeta . "');";

                        $resAgregar = mysqli_query($conexion, $queryAgregar);

                        if ($resAgregar) {

                            $queryModificar = "UPDATE actas SET nomControlCentralizado = '" . $nomControlCentralizado . "', tipoActa = 'Actualización' WHERE idActa = " . $idActa;

                            $resModificar = mysqli_query($conexion, $queryModificar);

                            if ($resModificar) {

                                header('Location: ../' . $rutaParkingRol . '?exitoAgregarPatente=true');
                            } else {
                                header('Location: ../' . $rutaParkingRol . '?errorAgregarPatente=true');
                            }
                        } else {
                            header('Location: ../' . $rutaParkingRol . '?errorAgregarPatente=true');
                        }
                    } else {
                        header('Location: ../' . $rutaParkingRol . '?errorMaximoParking=true');
                    }
                } else {
                    header('Location: ../' . $rutaParkingRol . '?errorPatenteExiste=true');
                }
            }
        } else {
            header('Location: ../' . $rutaParkingRol . '?errorAgregarPatente=true');
        }
    } else {
        header('Location: sdo-flujo/login_index.php?errorLogin=true');
    }
} else {
    header('Location: sdo-flujo/login_index.php?errorLogin=true');
}
