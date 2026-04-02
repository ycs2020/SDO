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

$headerTextCorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?exitoForzarVariablesEquipo=true';
$headerTextIncorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorForzarVariablesEquipo=true';
$headerSinObservacion = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorForzarEquipoSinObservacion=true';

if (isset($_POST['idEquipo'], $_POST['personalObservacion'], $_POST['addNombre'], $_POST['addFecha'], $_POST['razonesForzado'])) {

    $forzadoVariable = trim($_POST['forzadoVariable']);
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
        $headerTextCorrecto = 'Location: ../' . $ruta . '/equipo.php?exitoForzarVariablesEquipo=true';
        $headerTextIncorrecto = 'Location: ../' . $ruta . '/equipo.php?errorForzarVariablesEquipo=true';
        $headerSinObservacion = 'Location: ../' . $ruta . '/equipo.php?errorForzarEquipoSinObservacion=true';
    }

    if (!empty($forzadoVariable) || !empty($idEquipo) || !empty($personalObservacion) || !empty($addNombre) || !empty($addFecha) || !empty($razonesForzado)) {
        if ($forzadoVariable == "Variables" || $idEquipo > 0 || $personalObservacion > 0) {

            $sqlEncendido = "UPDATE equipos SET forzadoVariable = '1', modoAutomatico = '0' WHERE idEquipo = '" . $idEquipo . "'";

            $resEncendido = mysqli_query($conexion, $sqlEncendido);

            date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());


            if ($resEncendido) {
                $sqlObservacionForzar = "INSERT INTO equipos_observaciones(idObservaciones,detalleObservaciones,fechaObservaciones,personalObservacion,idEquipo,esForzado)
                VALUES(NULL,'" . $razonesForzado . "','".$now."','" . $personalObservacion . "'," . $idEquipo . ",'Forzado - Variables')";

                $resObservacionForzar = mysqli_query($conexion, $sqlObservacionForzar);

                if ($resObservacionForzar) {
                    header($headerTextCorrecto);
                } else {
                    header($headerSinObservacion);
                }
            } else {
                header($headerTextIncorrecto.'1');
            }

        } else {
            header($headerTextIncorrecto.'2');
        }
    } else {
        header($headerTextIncorrecto.'3');
    }
} else {
    header($headerTextIncorrecto.'4');
}
