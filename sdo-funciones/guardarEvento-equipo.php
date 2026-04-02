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

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //TRAEMOS EL ID DEL EQUIPO Y LO GUARDAMOS EN UNA VARIABLE
    $idEquipo = $_POST['idEquipoEvento'];

    //GUARDAMOS LOS DATOS EN VARIABLES
    $tituloEvento = $_POST['tituloEvento'];
    $descriptEvento = $_POST['descriptEvento'];
    $colorEvento = $_POST['colorEvento'];
    $desdeEvento = $_POST['desdeEvento'];
    $hastaEvento = $_POST['hastaEvento'];

    //IMPRIMIMOS PARA DEBUGUEAR
    echo  'ID Equipo: ' . $idEquipo . "<br>";
    echo  'Titulo: ' . $tituloEvento . "<br>";
    echo 'Descripción: ' .$descriptEvento . "<br>";
    echo 'Color: ' .$colorEvento . "<br>";
    echo 'Desde: ' .$desdeEvento . "<br>";
    echo 'Hasta: ' .$hastaEvento . "<br>";

    //INSETAMOS EN LA BASE DE DATOS
    $sqlInsert = "INSERT INTO evento_programado_equipo (titulo,descripcion,color,inicio,fin, idEquipo) VALUES ('$tituloEvento', '$descriptEvento', '$colorEvento', '$desdeEvento', '$hastaEvento', $idEquipo)";
    $resInsert = mysqli_query($conexion, $sqlInsert);


    //SI FUE EXITOSO SE REDIRECCIONA Y MUESTRA MENSAJE CON ASUNTO DE EXITO
    if($resInsert){
        header('Location: ../'. $ruta .'/visualizarEquipo.php?exitoAgendar=true&&idHoja=' . $idEquipo);
    }else{
    //SI NO FUE EXITOSO SE REDIRECCIONA Y MUESTRA MENSAJE CON ASUNTO DE ERROR
        header('Location: ../'. $ruta .'/visualizarEquipo.php?errorAgendar=true&&idHoja=' . $idEquipo);
    }


    exit;

}
