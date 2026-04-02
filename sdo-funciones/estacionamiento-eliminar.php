<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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

            $rutaParkingRol = 'sdo-directiva/actas-funcionario.php';
        } else if ($rolUsuario == "Jefe Tecnico") {

            $rutaRol = "sdo-jefestecnicos/actas-funcionario.php";

            $rutaParkingRol = 'sdo-jefestecnicos/actas-funcionario.php';
        } else if ($rolUsuario == "Administrativo") {

            $rutaRol = "sdo-admtec/actas-listado.php";

            $rutaParkingRol = 'sdo-admtec/actas-funcionario.php';
        } else {

            header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
        }

        if (isset($_POST['idParking'])) {

            $idParking = trim($_POST['idParking']);

            $cadena = '';

            if (!empty($idParking)) {

                $queryBorrar = "DELETE FROM parking WHERE idParking=" . $idParking;

                if (mysqli_query($conexion, $queryBorrar)) {

                    header('Location: ../' . $rutaParkingRol . '?exitoEliminarPatente=true');
                } else {
                    header('Location: ../' . $rutaParkingRol . '?errorEliminarPatente=true');
                }
            } else {
                header('Location: ../' . $rutaParkingRol . '?errorEliminarPatente=true');
            }
        } else {
            header('Location: ../' . $rutaParkingRol . '?errorEliminarPatente=true');
        }
    } else {
        header('Location: ../sdo-flujo/login1.php?errorLogin1=true');
    }
} else {
    header('Location: ../sdo-flujo/login2.php?errorLogin2=true');
}
