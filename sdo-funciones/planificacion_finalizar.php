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

if (isset($_POST['idTareaP'], $_POST['textoTareaP'], $_POST['nomInicia'], $_POST['deptoInicia'], $_POST['finPlanTareaPMod'])) {

    $idTareaP = $_POST['idTareaP'];
    $textoTareaP = $_POST['textoTareaP'];
    $nomInicia = $_POST['nomInicia'];
    $deptoInicia = $_POST['deptoInicia'];
    $finPlanTareaPMod = $_POST['finPlanTareaPMod'];

    $getNomDepartamento = getNomDepartamento($deptoInicia);


    $nomObsPlan = $nomInicia . ' (' . $getNomDepartamento . ')';

    if ($idTareaP < 0 || empty($idTareaP) || empty($deptoInicia) || empty($finPlanTareaPMod)) {
        header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorGenerar=true');
    } else {

        if ($textoTareaP == 'Finalizar') {
            $queryAccion = "UPDATE tareas_carta_gantt SET finRealTareaP = '" . $finPlanTareaPMod . "', porcentajeTareaP = 100, finalizadaTareaP = 1 WHERE idTareaP = " . $idTareaP;
            $textoObserv = $nomObsPlan . ' ha Finalizado la Tarea Planificada';
        } else {
            header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?errorIniciar=true');
        }

//        echo $queryAccion;

        $resPlan = mysqli_query($conexion, $queryAccion);

        if ($resPlan) {
            $_SESSION['idTareaP'] = $idTareaP;
            header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?exito' . $textoTareaP . '=true');


        } else {
            $_SESSION['idTareaP'] = $idTareaP;
            header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?error' . $textoTareaP . '=true');
        }

    }
} else {
    header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorModificacion=true');
}

