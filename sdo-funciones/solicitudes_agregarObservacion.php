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
} else if ($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
}else if($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoAgregarObservacion=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorIdSolicitud=true';
$headerError2 = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorDepartamentoResponsable=true';
$headerError3 = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorAgregarObservacion=true';


// $headerErrorA = 'Location: ../' . $ruta . '/solicitudes_detalle.php?A=true';
// $headerErrorB = 'Location: ../' . $ruta . '/solicitudes_detalle.php?B=true';
// $headerErrorC = 'Location: ../' . $ruta . '/solicitudes_detalle.php?C=true';

if (isset($_POST['idSolicitud'])) {

    $idSolicitud = trim($_POST['idSolicitud']);
    $obsPrivada = trim($_POST['obsPrivada']);


    if ($idSolicitud !== 0) {

        if (isset($_POST['nomPersonal'], $_POST['observacionSolicitud'])) {

            $nomPersonal = trim($_POST['nomPersonal']);
            $observacionSolicitud = $_POST['observacionSolicitud'];

            if (empty($nomPersonal) || empty($observacionSolicitud)) {
                header($headerError3);
            } else {

                $_SESSION['idSolicitud'] = $idSolicitud;

                if (!empty($observacionSolicitud)) {
                    $observacionSolicitud = str_replace("'", "\'", $observacionSolicitud);
                }

                $observacion = $nomPersonal . ' ha dejado la siguiente observación: \n\n ' . $observacionSolicitud;

                $tipoObservaSolicitudes = "Observación";

                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());

                $sqlObservacion = "INSERT INTO observaciones_tickets(idObservaSolicitudes,obsPrivada,detalleObservaSolicitudes,fechaObservaSolicitudes,tipoObservaSolicitudes,creadorObservaSolicitudes,idSolicitud)
                        VALUES(NULL,'" . $obsPrivada . "','" . $observacion . "','".$now."','" . $tipoObservaSolicitudes . "','" . $nomPersonal . "'," . $idSolicitud . ")";

                $resObservacion = mysqli_query($conexion, $sqlObservacion);

                if ($resObservacion) {
                    if($rolUsuario2 == "personal"){
                        $sqlRegObs = "UPDATE solicitudes_soportes SET nuevaObservacion = 1 WHERE idSolicitud = '" . $idSolicitud . "'";

                        $resRegObs = mysqli_query($conexion, $sqlRegObs);

                        $sqlRegObs = "UPDATE solicitudes_soportes SET situacionSolicitud = 'Solicitud con Observaciones Nuevas' WHERE idSolicitud = '" . $idSolicitud . "'";

                        $resRegObs = mysqli_query($conexion, $sqlRegObs);
    
                        if ($resRegObs) {
                            header($headerTextCorrecto);
                        } else {
                            header($headerError3);
                        }
                    }else{
                        $sqlRegObs = "UPDATE solicitudes_soportes SET nuevaObservacion = 1 WHERE idSolicitud = '" . $idSolicitud . "'";

                        $resRegObs = mysqli_query($conexion, $sqlRegObs);

                        $sqlRegObs = "UPDATE solicitudes_soportes SET situacionSolicitud = 'Solicitud con Observaciones Nuevas' WHERE idSolicitud = '" . $idSolicitud . "'";

                        $resRegObs = mysqli_query($conexion, $sqlRegObs);

                        if($resRegObs){
                            header($headerTextCorrecto);
                        } else{
                            header($headerError3);

                        }
                    }
                } else {
                    header($headerError3);
                }
            }
        } else {
            header($headerError3);
        }
    } else {
        header($headerError);
    }
} else {
    header($headerError);
}
