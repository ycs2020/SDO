<?php
include 'conexion.php';
include 'funciones.php';

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

if(isset($_POST['idTareaP'])){

    $idTareaP = $_POST['idTareaP'];
    $idEquipo = $_POST['idEquipo'];

    if($idTareaP > 0){

        $obtenerIDPadreTarea = obtenerIDPadreTarea($idTareaP);

        if($idEquipo > 0){
            $rutaTieneEquipo = 'equipo_menuAntiguo.php' ;
            $_SESSION['idEquipo'] = $idEquipo;

        }else if($obtenerIDPadreTarea > 0){
            $rutaTieneEquipo = 'planificador_detalle.php' ;

        }else{
            $rutaTieneEquipo = 'planificador_administracion.php' ;

        }


        $sqlDelete = "DELETE FROM tareas_carta_gantt WHERE idTareaP = '".$idTareaP."'";

        $resDelete = mysqli_query($conexion, $sqlDelete);

        if($resDelete){

            if($obtenerIDPadreTarea > 0){
                $_SESSION['idTareaP'] = $obtenerIDPadreTarea;
                header('Location: ../'.$ruta.'/'.$rutaTieneEquipo.'?exitoEliminarTarea=true');
            }else{
                header('Location: ../'.$ruta.'/'.$rutaTieneEquipo.'?exitoEliminarTarea=true');
            }
        }else{
            if($obtenerIDPadreTarea > 0){
                $_SESSION['idTareaP'] = $obtenerIDPadreTarea;
                header('Location: ../'.$ruta.'/'.$rutaTieneEquipo.'?errorEliminarTarea=true');
            }else{
                header('Location: ../'.$ruta.'/'.$rutaTieneEquipo.'?errorEliminarTarea=true');
            }
        }

    }else{
        header('Location: ../'.$ruta.'/planificador_administracion.php?errorEliminarTarea=true');
    }

}else{
    header('Location: ../'.$ruta.'/planificador_administracion.php?errorEliminarTarea=true');
}

