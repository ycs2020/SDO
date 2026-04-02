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
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "gestión documental") {
    $ruta = "sdo-gestionDocumental";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/soliCompra_pm_administracion.php?exitoAnularSolicitud=true';
$headerTextCorrecto2 = 'Location: ../' . $ruta . '/soliCompra_pm_administracion.php?exitoAnularSolicitudSinObservacion=true';
$headerError = 'Location: ../' . $ruta . '/soliCompra_pm_administracion.php?errorAnularSolicitud=true';

if (isset($_POST['idSoliCompra2'])) {

    $idSoliCompra2 = trim($_POST['idSoliCompra2']);

    if ($idSoliCompra2 !== 0) {

        if (isset($_POST['personalSC'], $_POST['cargoSC'], $_POST['deptoSC'], $_POST['razonesAnulacion'])) {

            $personalSC = trim($_POST['personalSC']);
            $cargoSC = trim($_POST['cargoSC']);

            $deptoSC = trim($_POST['deptoSC']);
            $razonesAnulacion = trim($_POST['razonesAnulacion']);

            if ($razonesAnulacion == '') {
            } else {
                $razonesAnulacion = str_replace("'", "\'", $razonesAnulacion);
            }

            if (empty($personalSC) || empty($razonesAnulacion) || empty($cargoSC) || empty($deptoSC) || empty($idSoliCompra2) || $idSoliCompra2 < 1) {
                header($headerError . '0');
            } else {

                $_SESSION['idSoliCompra'] = $idSoliCompra2;

                $nomPersonal = $personalSC . ' (' . $cargoSC . ')';

                $sqlSCPM = "UPDATE solicitudes_compra_pm SET estadoSoliCompra = 'Solicitud Anulada' WHERE idSoliCompra = '" . $idSoliCompra2 . "'";

                $resSCPM = mysqli_query($conexion, $sqlSCPM);

                if ($resSCPM) {

                    date_default_timezone_set('America/Santiago');
                    $now = date('Y-m-d H:i:s', time());

                    $sqlObservacion = "INSERT INTO anulaciones_solicompra_pm(idAnulacion,detalleAnulacion,personalAnulacion,unidadAnulacion,fechaAnulacion,idSoliCompraPM)
                        VALUES(NULL,'" . $razonesAnulacion . "','" . $nomPersonal . "','" . $deptoSC . "','" . $now . "','" . $idSoliCompra2 . "')";

                    $resObservacion = mysqli_query($conexion, $sqlObservacion);

                    if ($resObservacion) {
                        header($headerTextCorrecto);
                    } else {
                        header($headerTextCorrecto2);
                    }
                } else {
                    header($headerError);
                }
            }
        } else {
            header($headerError . '1');
        }
    } else {
        header($headerError . '2');
    }
} else {
    header($headerError . '3');
}
