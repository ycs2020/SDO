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

$headerTextCorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?exitoModificarSP=true';
$headerTextIncorrecto = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorModificarSP=true';
$headerSinObservacion = 'Location: ../' . $ruta . '/equipos-observaciones.php?errorModificarSPSinObservacion=true';

if (isset($_POST['idEquipo'], $_POST['personalObservacion'], $_POST['addNombre'], $_POST['addFecha'], $_POST['observacionEquipo'], $_POST['nuevoValorSP'])) {

    $idEquipo = trim($_POST['idEquipo']);
    $personalObservacion = trim($_POST['personalObservacion']);
    $addNombre = trim($_POST['addNombre']);
    $addFecha = trim($_POST['addFecha']);
    $observacionEquipo = trim($_POST['observacionEquipo']);
    $nuevoValorSP = $_POST['nuevoValorSP'];

    if (!empty($observacionEquipo)) {
        $observacionEquipo = str_replace("'", "\'", $observacionEquipo);
    }

    $idEquipamiento = $_POST['idEquipamiento'];
    if($idEquipamiento > 0){
        $_SESSION['idEquipo'] = $idEquipamiento;
        $headerTextCorrecto = 'Location: ../' . $ruta . '/equipo.php?exitoModificarSP=true';
        $headerTextIncorrecto = 'Location: ../' . $ruta . '/equipo.php?errorModificarSP=true';
        $headerSinObservacion = 'Location: ../' . $ruta . '/equipo.php?errorModificarSPSinObservacion=true';
    }

    if (!empty($idEquipo) || !empty($personalObservacion) || !empty($addNombre) || !empty($addFecha) || !empty($observacionEquipo) || !empty($nuevoValorSP)) {
        if ($idEquipo > 0 && $nuevoValorSP > 0) {

            $sqlEncendido = "UPDATE equipos SET forzadoVariable = '1', modoAutomatico = '0' WHERE idEquipo = '" . $idEquipo . "'";

            $resEncendido = mysqli_query($conexion, $sqlEncendido);

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());


            if ($resEncendido) {
                $sqlObservacionForzar = "INSERT INTO equipos_observaciones(idObservaciones,detalleObservaciones,fechaObservaciones,personalObservacion,porcentajeObservacion,idEquipo,esForzado)
                VALUES(NULL,'" . $observacionEquipo . "','".$now."','" . $personalObservacion . "',".$nuevoValorSP."," . $idEquipo . ",'Cambio de Set Point')";

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
//
//            echo $idEquipo.'<br><br>';
//            echo $personalObservacion.'<br><br>';
//            echo $nuevoValorSP.'<br><br>';

        }
    } else {
        header($headerTextIncorrecto.'3');
    }
} else {
    header($headerTextIncorrecto.'4');
}
