<?php
// MODIFICAR PARA LICITACION

include("conexion.php");
include("funciones.php");


if (isset($_POST['usuarioRegistrado']) && isset($_POST['passwordRegistrado'])) {
    $usuarioRegistrado = trim($_POST['usuarioRegistrado']);
    $passwordRegistrado = trim($_POST['passwordRegistrado']);

    if (empty($usuarioRegistrado) || empty($passwordRegistrado) || $usuarioRegistrado == '' || $passwordRegistrado == '' || $usuarioRegistrado == ' ' || $passwordRegistrado == ' ') {
        header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT=true');
    } else {

        $sqlAdmin = "SELECT * FROM usuarios_tickets WHERE rutUsuariosT = '" . $usuarioRegistrado . "' AND passUsuariosT = '" . $passwordRegistrado . "'";
        $resAdmin = mysqli_query($conexion, $sqlAdmin);
        $rowAdmin = $resAdmin->fetch_assoc();
        if ($rowAdmin) {

            // iniciar la sesion
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['idUT'] = $rowAdmin['idUsuariosT'];
            $_SESSION['nomUT'] = $rowAdmin['nomUsuariosT'];
            $_SESSION['rutUT'] = $rowAdmin['rutUsuariosT'];
            $_SESSION['passUT'] = $rowAdmin['passUsuariosT'];
            $_SESSION['emailUT'] = $rowAdmin['emailUsuariosT'];
            $_SESSION['cargoUT'] = $rowAdmin['cargoUsuariosT'];
            $_SESSION['unidadUT'] = $rowAdmin['unidadUsuariosT'];
            $_SESSION['telefonoUT'] = $rowAdmin['telefonoUsuariosT'];
            $_SESSION['activoUT'] = $rowAdmin['activoUsuariosT'];

            $activoUT = $rowAdmin['activoUsuariosT'];

            if($activoUT == 1){
                header('Location: ../sdo-flujo/ticket_formularioUT.php');
            }else{
                header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorActivoUsuariosT=true');
            }



        } else {
            header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT=true');

        }
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
}
