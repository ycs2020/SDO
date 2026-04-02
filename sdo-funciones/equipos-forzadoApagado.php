<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    header('Location: ../sdo-flujo/sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?exitoForzarApagadoEquipo=true';
$headerTextIncorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorForzarApagadoEquipo=true';
$headerSinObservacion = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorForzarEquipoSinObservacion=true';

if (isset($_POST['apagadoEquipo'], $_POST['idEquipo'], $_POST['personalObservacion'], $_POST['addNombre'], $_POST['addFecha'], $_POST['razonesForzado'])) {

    $apagadoEquipo = trim($_POST['apagadoEquipo']);
    $idEquipo = trim($_POST['idEquipo']);
    $personalObservacion = trim($_POST['personalObservacion']);
    $addNombre = trim($_POST['addNombre']);
    $addFecha = trim($_POST['addFecha']);
    $razonesForzado = trim($_POST['razonesForzado']);

    if (!empty($razonesForzado)) {
        $razonesForzado = str_replace("'", "\'", $razonesForzado);
    }

    $idEquipamiento = $_POST['idEquipamiento'];
    $idEquipamientoPadre = $_POST['idEquipamientoPadre'];
    if($idEquipamiento > 0){
        if($idEquipamientoPadre > 0){
            $_SESSION['idEquipo'] = $idEquipamientoPadre;
        }else{
            $_SESSION['idEquipo'] = $idEquipamiento;
        }
        $headerTextCorrecto = 'Location: ../' . $ruta . '/equipo.php?exitoForzarApagadoEquipo=true';
        $headerTextIncorrecto = 'Location: ../' . $ruta . '/equipo.php?errorForzarApagadoEquipo=true';
        $headerSinObservacion = 'Location: ../' . $ruta . '/equipo.php?errorForzarEquipoSinObservacion=true';
    }

    if (!empty($apagadoEquipo) || !empty($idEquipo) || !empty($personalObservacion) || !empty($addNombre) || !empty($addFecha) || !empty($razonesForzado)) {
        if ($apagadoEquipo == "Apagar" || $idEquipo > 0 || $personalObservacion > 0) {

            $sqlApagado = "UPDATE equipos SET forzadoEquipo = '1', forzadoEncendido = '0', forzadoApagado = '1', modoAutomatico = '0' WHERE idEquipo = '" . $idEquipo . "'";

            $resApagado = mysqli_query($conexion, $sqlApagado);

            date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());


            if ($resApagado) {
                $sqlObservacionForzar = "INSERT INTO equipos_observaciones(idObservaciones,detalleObservaciones,fechaObservaciones,personalObservacion,idEquipo,esForzado)
                VALUES(NULL,'" . $razonesForzado . "','".$now."','" . $personalObservacion . "'," . $idEquipo . ",'Forzado - Apagado')";

                $resObservacionForzar = mysqli_query($conexion, $sqlObservacionForzar);

                if ($resObservacionForzar) {
                    header($headerTextCorrecto);
                } else {
                    header($headerSinObservacion);
                }
            } else {
                header($headerTextIncorrecto);
            }

        } else {
            header($headerTextIncorrecto);
        }
    } else {
        header($headerTextIncorrecto);
    }
} else {
    header($headerTextIncorrecto);
}
