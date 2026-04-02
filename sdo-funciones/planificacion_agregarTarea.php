<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

if (isset($_POST['actividadTareaP'], $_POST['inicioPlanTareaP'], $_POST['finPlanTareaP'], $_POST['idTareaP'], $_POST['nomCreadorTareaP'], $_POST['deptoCreadorTareaP'], $_POST['tipoDiasHabil'])) {

    $actividadTarea = $_POST['actividadTareaP'];
    $inicioPlanTareaP = $_POST['inicioPlanTareaP'];
    $finPlanTareaP = $_POST['finPlanTareaP'];
    $idTareaP = $_POST['idTareaP'];
    $nomCreadorTareaP = $_POST['nomCreadorTareaP'];
    $deptoCreadorTareaP = $_POST['deptoCreadorTareaP'];
    $tipoDiasHabil = $_POST['tipoDiasHabil'];
    $idEquipo = $_POST['idEquipo'];
    $esEquipamiento = $_POST['esEquipamiento'];
    if($esEquipamiento == '' || empty($esEquipamiento)){
        $esEquipamiento = 0;
    }
    if($idEquipo == '' || empty($idEquipo)){
        $idEquipo = 0;
    }

    $idEquipamiento = $_POST['idEquipamiento'];
    $idEquipamientoPadre = $_POST['idEquipamientoPadre'];
    if($idEquipamiento > 0){
        if($idEquipamientoPadre > 0){
            $_SESSION['idEquipo'] = $idEquipamientoPadre;
            $idEquipo = $idEquipamiento;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipamientoPadre);
        }else{
            $_SESSION['idEquipo'] = $idEquipamiento;
            $idEquipo = $idEquipamiento;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipamiento);

        }
        if($obtenerTipoMenu == 'nuevo'){
            $sitioWeb = 'equipo_menuNuevo.php';
        }else{
            $sitioWeb = 'equipo_menuAntiguo.php';
        }
        $headerTextCorrecto = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?exitoForzarVariablesEquipo=true';
        $headerTextIncorrecto = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?errorForzarVariablesEquipo=true';
        $headerSinObservacion = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?errorForzarEquipoSinObservacion=true';
    }



    if ($idTareaP < 0 || $tipoDiasHabil == 'Seleccione...' || empty($tipoDiasHabil) || $deptoCreadorTareaP < 1 || empty($nomCreadorTareaP)) {
        header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorGenerar=true');
    } else {
        $feriadosChile = obtenerFeriadosChile();

        if ($tipoDiasHabil == 'Habiles') {

            $cantidadDiasTarea = obtenerDiasHabiles($inicioPlanTareaP, $finPlanTareaP, $feriadosChile);

        } else if ($tipoDiasHabil == 'Inhabiles') {

            $cantidadDiasTarea = obtenerDiasCorridos($inicioPlanTareaP, $finPlanTareaP, $feriadosChile);

        } else {
            header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorGenerar=true');
        }

        $sqlPlan = 'INSERT INTO tareas_carta_gantt(idTareaP, actividadTareaP, inicioPlanTareaP,diasPlanTareaP,tipoDiasHabil, finPlanTareaP, inicioRealTareaP, finRealTareaP, porcentajeTareaP, idPadreTareaP, iniciadaTareaP, finalizadaTareaP, nomCreadorTareaP, deptoCreadorTareaP,idEquipamiento) ';
        $sqlPlan .= ' VALUES(NULL,"' . $actividadTarea . '","' . $inicioPlanTareaP . '","' . $cantidadDiasTarea . '","' . $tipoDiasHabil . '","' . $finPlanTareaP . '",NULL,NULL,0,"' . $idTareaP . '",0,0,"' . $nomCreadorTareaP . '","' . $deptoCreadorTareaP . '","'.$idEquipo.'")';
        $resPlan = mysqli_query($conexion, $sqlPlan);

        if ($resPlan) {
            $ultimoID = $conexion->insert_id;
            $_SESSION['idTareaP'] = $idTareaP;

            if($esEquipamiento == 1){
                header('Location:../' . $rutaDireccion . '/equipo_menuAntiguo.php?exitoGenerarPlanificador=true&idEquipo='.$idEquipo);
            }else{
                if($idTareaP == 0) {
                    header('Location:../' . $rutaDireccion . '/planificador_administracion.php?exitoGenerar=true');
                }else if($idTareaP > 0){
                    header('Location:../' . $rutaDireccion . '/planificador_detalle.php?exitoGenerar=true');
                }else{
                    header('Location:../' . $rutaDireccion . '/planificador_administracion.php?exitoGenerar=true');
                }
            }
        } else {
            header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorGenerar=true');
        }
    }
} else {
    header('Location: ../' . $rutaDireccion . '/planificador_administracion.php?errorGenerar=true');
}

