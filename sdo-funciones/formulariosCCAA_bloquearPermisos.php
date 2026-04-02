<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//echo $_SESSION['rolUsuario'];

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');
$now = date('Y-m-d H:i:s', time());

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = mb_convert_case($rolUsuario, MB_CASE_LOWER, 'UTF-8');

//echo $rolUsuario2;

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == 'ofcina tecnica') {
    $ruta = "sdo-oficinaTecnica";
} else {
    echo 'Error 1<br><br>';
}


if (isset($_POST['idFormRegAcceso'], $_POST['idFormFuncCCAA'], $_POST['nomCreador'], $_POST['detalleObs'], $_POST['folioFormRegAcceso'], $_POST['paraObserva'])) {
    $idFormRegAcceso = $_POST['idFormRegAcceso'];
    $nomCreador = $_POST['nomCreador'];
    $nomCreador2 = mb_convert_case($nomCreador, MB_CASE_TITLE, 'UTF-8');
    $detalleObs = $_POST['detalleObs'];
    $idFormFuncCCAA = $_POST['idFormFuncCCAA'];
    $paraObserva = $_POST['paraObserva'];

    if ($idFormFuncCCAA > 0) {
        $_SESSION['idFormRegAcceso'] = getIDFormBloqueo($idFormFuncCCAA);

        $fechaObservacion = $_POST['fechaObservacion'];
        $folioFormRegAcceso = $_POST['folioFormRegAcceso'];

        if (empty($nomCreador) || empty($detalleObs)) {

            header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?errorObservacion=true');
        } else {

            $sqlUpdatePermisos = "UPDATE formularios_funcionarios_bloqueo SET estadoFormRegBloqueo = 1, fechaBloqueoFormFunc = '" . $now . "' WHERE idFormFuncCCAA  = " . $idFormFuncCCAA;

//        echo $sqlUpdatePermisos;
            $resUpdatePermisos = mysqli_query($conexion, $sqlUpdatePermisos);
            if ($resUpdatePermisos) {
                $nowFormat = strtotime($now);
                $nowFormat2 = fechaEspañol($nowFormat);
                $horaFormat = date('H:i', strtotime($now));


                if ($paraObserva == 'Bloqueo') {

                    $observacion = $nomCreador2 . " realiza Bloqueo de Permisos de Funcionario con Fecha: " . $nowFormat2 . ' a las ' . $horaFormat . ' hrs.';

                } else if ($paraObserva == 'Perdida') {

                    $observacion = $nomCreador2 . " realiza Bloqueo de Tarjeta de Funcionario con Fecha: " . $nowFormat2 . ' a las ' . $horaFormat . ' hrs.';

                } else if ($paraObserva == 'Desvinculacion') {

                    $observacion = $nomCreador2 . " realiza Bloqueo por Desvinculación de Funcionario con Fecha: " . $nowFormat2 . ' a las ' . $horaFormat . ' hrs.';
                } else {

                    $observacion = $nomCreador2 . " realiza Bloqueo de Permisos de Funcionario con Fecha: " . $nowFormat2 . ' a las ' . $horaFormat . ' hrs.';
                }
//            echo $observacion;

                $sqlObserva = "INSERT INTO formularios_observaciones (idObservacion,detalleObs,fechaObs,creador,idFormRegAcceso,idFormFuncCCAA,folioFormRegAcceso) VALUES (NULL,'" . $detalleObs . "','" . $now . "','" . $nomCreador2 . "',$idFormRegAcceso,$idFormFuncCCAA,'" . $folioFormRegAcceso . "')";

//            echo $sqlObserva;
                $resObserva = mysqli_query($conexion, $sqlObserva);

                if ($resObserva) {
                    header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?exitoObservacion=true');
                } else {
                    header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?exitoSinObservacion=true');
                }
            } else {
                header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?errorObservacion=true');
            }
        }

    } else {

        header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?errorObservacion=true');
    }


} else {
    header('Location: ../' . $ruta . '/formulariosCCAA_administracion_2.php?errorObservacion=true');
}



