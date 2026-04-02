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
$campoUpdate = "";

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


if (isset($_POST['actividadTareaPMod'], $_POST['inicioPlanTareaPMod'], $_POST['finPlanTareaPMod'], $_POST['idTareaPMod'], $_POST['nomCreadorTareaPMod'], $_POST['deptoCreadorTareaPMod'], $_POST['tipoDiasHabilMod'])) {

    $actividadTarea = $_POST['actividadTareaPMod'];
    $inicioPlanTareaP = $_POST['inicioPlanTareaPMod'];
    $finPlanTareaP = $_POST['finPlanTareaPMod'];
    $idTareaP = $_POST['idTareaPMod'];
    $nomCreadorTareaP = $_POST['nomCreadorTareaPMod'];
    $deptoCreadorTareaP = $_POST['deptoCreadorTareaPMod'];
    $tipoDiasHabil = $_POST['tipoDiasHabilMod'];

    if ($tipoDiasHabil == "Habiles") {
        $cantidadDiasTarea = obtenerDiasHabiles($inicioPlanTareaP,$finPlanTareaP,obtenerFeriadosChile());
    } else if ($tipoDiasHabil == "Inhabiles") {
        $cantidadDiasTarea = obtenerDiasCorridos($inicioPlanTareaP,$finPlanTareaP);
    } else {
        $cantidadDiasTarea = obtenerDiasCorridos($inicioPlanTareaP,$finPlanTareaP);

    }

    if($idTareaP > 0){

        $_SESSION['idTareaP'] = $idTareaP;

        $queryUpdate = "UPDATE tareas_carta_gantt SET actividadTareaP = '".$actividadTarea."', inicioPlanTareaP = '".$inicioPlanTareaP."',  diasPlanTareaP = '".$cantidadDiasTarea."',  tipoDiasHabil = '".$tipoDiasHabil."', finPlanTareaP = '".$finPlanTareaP."' WHERE idTareaP = ".$idTareaP;

//        echo $inicioPlanTareaP;
//        exit();
//        echo $queryUpdate;

        $resPlan = mysqli_query($conexion, $queryUpdate);

        if ($resPlan) {

            $obtenerOTSegunTareaPlanificada = obtenerOTSegunTareaPlanificada($idTareaP);

            if($obtenerOTSegunTareaPlanificada > 0){

                $checklistSeleccionado = obtenerCheckListOTSegunTareaPlanificada($obtenerOTSegunTareaPlanificada);

                if($checklistSeleccionado == 'Pantallas Graficas' || $checklistSeleccionado == 'Logica de Programacion' || $checklistSeleccionado == 'Controlador Modelo ECB' || $checklistSeleccionado == 'TCC de UMA'  || $checklistSeleccionado == 'VAV' ){

                    $campoUpdate = 'detalleTG';

                }else if($checklistSeleccionado == 'Unidades Control de Accesos'){

                    $campoUpdate = 'detalleTurnoCA';

                }else{

                    $campoUpdate = 'detalleOtros';

                }

                $sqlUpdate = "UPDATE trabajos_general SET $campoUpdate = '$actividadTarea', fechaProgramacion = '$inicioPlanTareaP' WHERE idTG = $obtenerOTSegunTareaPlanificada";
                $resUpdate = mysqli_query($conexion, $sqlUpdate);


            }

            $_SESSION['idTareaP'] = $idTareaP;
            header('Location:../' . $rutaDireccion . '/planificador_detalle.php?exitoGenerar=true');
        } else {
            $_SESSION['idTareaP'] = $idTareaP;
            header('Location: ../' . $rutaDireccion . '/planificador_detalle.php?errorGenerar=true');
        }

    }else{
        header('Location: ../'.$rutaDireccion . '/planificador_administracion.php?errorModificar=true');
    }

//    echo $actividadTarea . "<br><br>";
//    echo $inicioPlanTareaP . "<br><br>";
//    echo $finPlanTareaPMod . "<br><br>";
//    echo $idTareaP . "<br><br>";
//    echo $nomCreadorTareaP . "<br><br>";
//    echo $deptoCreadorTareaP . "<br><br>";
//    echo $tipoDiasHabil . "<br><br>";
//    echo $cantidadDiasTarea . '<br><br>';




} else {
//    var_dump($_POST);
    header('Location: ../'.$rutaDireccion . '/planificador_administracion.php?errorModificar=true');

}
