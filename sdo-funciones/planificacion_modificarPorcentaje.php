<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i', time());

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idTareaPMod'], $_POST['nuevoPorcentaje'], $_POST['nomCreadorTareaPMod'], $_POST['deptoCreadorTareaPMod'])) {

    $idTareaP = $_POST['idTareaPMod'];
    $nuevoPorcentaje = $_POST['nuevoPorcentaje'];
    $nomCreadorTareaPMod = $_POST['nomCreadorTareaPMod'];
    $deptoCreadorTareaPMod = $_POST['deptoCreadorTareaPMod'];
    $getNomDepartamento = getNomDepartamento($deptoCreadorTareaPMod);
    $nomObsPlan = $nomCreadorTareaPMod . ' (' . $getNomDepartamento . ')';

    $getPorcentajeAnterior = getPorcentajeTareaP($idTareaP);

    if($idTareaP > 0 && !empty($nomCreadorTareaPMod) && !empty($deptoCreadorTareaPMod) && $nuevoPorcentaje >= 0 && $nuevoPorcentaje <= 100){

        $queryAccion = "UPDATE tareas_carta_gantt SET porcentajeTareaP = ".$nuevoPorcentaje." WHERE idTareaP = " . $idTareaP;
        $textoObserv = $nomObsPlan . ' ha cambiado el Porcentaje de la Tarea Planificada. Valor Anterior: " '. $getPorcentajeAnterior. ' " y Valor Nuevo: " '. $nuevoPorcentaje.' ".';


        $resPlan = mysqli_query($conexion, $queryAccion);

        if ($resPlan) {
            $_SESSION['idTareaP'] = $idTareaP;
            $queryObserv = "INSERT INTO observaciones_carta_gantt(idObsPlan,nomObsPlan,fechaObsPlan,detalleObsPlan,idTareaP) VALUES (NULL,'" . $nomObsPlan . "','" . $now . "','" . $textoObserv . "'," . $idTareaP . ")";
            $resObs = mysqli_query($conexion, $queryObserv);

            if ($resObs) {
                header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?exitoModPorcentaje=true');
            } else {
                header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?exitoModPorcentajeSinObservacion=true');
            }
        }else{
            header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?errorModPorcentaje=true');
        }

    }else{
        header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorModPorcentaje=true');
    }

}else{
    header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorModPorcentaje=true');
}
