<?php
include_once('conexion.php');
include_once('funciones.php');

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
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/tareas_administrar.php?exitoFinalizarSolicitud=true';
$headerError = 'Location: ../' . $ruta . '/tareas_administrar.php?errorCrearTarea=true';

if (isset($_POST['nomEjecutor'], $_POST['nomCreador'], $_POST['valorPrioridad'], $_POST['tituloTarea'], $_POST['limiteTarea'], $_POST['detalleTarea'])) {

    $nomEjecutor = trim($_POST['nomEjecutor']);
    $nomCreador = trim($_POST['nomCreador']);
    $valorPrioridad = trim($_POST['valorPrioridad']);
    $tituloTarea = trim($_POST['tituloTarea']);

    $limiteTarea = trim($_POST['limiteTarea']);
    $limiteTarea .= ' 23:59:59';


    $detalleTarea = trim($_POST['detalleTarea']);

    if (empty($nomEjecutor) || empty($nomCreador) || empty($tituloTarea) || empty($limiteTarea) || empty($detalleTarea)) {
        header($headerError);
    } else {

        if ($valorPrioridad == 0 || $valorPrioridad == 1) {

            if (!empty($detalleTarea)) {

                $detalleTarea = str_replace("'", "\'", $detalleTarea);
                $detalleTarea = nl2br($detalleTarea);
            }

            // echo $nomEjecutor.'<br><br>';
            // echo $nomCreador.'<br><br>';
            // echo $valorPrioridad.'<br><br>';
            // echo $tituloTarea.'<br><br>';
            // echo $limiteTarea.'<br><br>';
            // echo $detalleTarea.'<br><br>';

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $queryTarea = "INSERT INTO tareas_diarias(idTarea,tituloTarea,detalleTarea,nomSolicitante,nomEjecutor,estadoTarea,inicioTarea,terminoTarea,limiteFecha,prioridadTarea)
            VALUES (NULL,'" . $tituloTarea . "','" . $detalleTarea . "','" . $nomCreador . "','" . $nomEjecutor . "','Creado','" . $now . "',NULL,'" . $limiteTarea . "','" . $valorPrioridad . "')";

            // echo $queryTarea;
            $resTarea = mysqli_query($conexion, $queryTarea);

            if ($resTarea) {
                echo "Correcto";
            } else {
                echo "Error";
            }
        } else {
            header($headerError);
        }
    }
} else {
    header($headerError);
}
