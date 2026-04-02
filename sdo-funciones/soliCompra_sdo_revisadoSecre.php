<?php

include 'conexion.php';

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

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "mesa ayuda") {

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id'])) {

        $opcion = $_POST['opcion'];
        $idSoliCompra = $_POST['id'];

        if ($opcion == 'revisadoSecre') {
            $nuevoEstado = 'Revisada por Secretaría SDO';
            $observacion = "";
            $fecha = $_POST['fechaRevision'];

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (fechaActualizacion,observacion, tipoActualizacion, idSoliCompra) VALUES ('$fecha','$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

            if ($resInsertSeguimiento) {
                $sqlUpdateSC = "UPDATE solicitudes_compra_sdo SET estadoSoliCompra = '" . $nuevoEstado . "', isRevisado = 1 WHERE idSoliCompra = " . $idSoliCompra;
                    $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
                    if ($resUpdateSC) {
                        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoRevisado=true');
                    } else {
                        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorRevisado=true');
                    }
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorRevisado=true');
            }






        }
    } else {
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorRevisado=true');
    }
}
