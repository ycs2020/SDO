<?php

header('Content-Type: application/json');

include 'conexion.php';

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
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idEquipo = $_GET['idEquipo'];

$idEvento = $_GET['idEvento'];


switch ($_GET['accion']) {
    case 'listar':
        # code...
        $sqlConsultar = "SELECT idEvento as id, titulo as title, descripcion, inicio as start, fin as end, colorTexto as textColor, colorFondo as backgroundColor FROM eventos_programados_equipos WHERE idEquipo = $idEquipo ";
        $datos = mysqli_query($conexion, $sqlConsultar);
        $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;
    case 'agregar':
        
        # code...
        $respuesta = mysqli_query($conexion, "INSERT INTO eventos_programados_equipos (titulo, descripcion, inicio, fin, colorTexto, colorFondo, idEquipo) VALUES ('$_POST[titulo]', '$_POST[descripcion]', '$_POST[inicio]', '$_POST[fin]', '$_POST[colorTexto]','$_POST[colorFondo]','$_POST[id]')");
        echo json_encode($respuesta);

        break;
    case 'modificar':
        # code...
        $sqlActualizar = "UPDATE eventos_programados_equipos SET titulo = '$_POST[titulo]', descripcion = '$_POST[descripcion]', inicio = '$_POST[inicio]', fin = '$_POST[fin]',colorTexto = '$_POST[colorTexto]', colorFondo = '$_POST[colorFondo]' WHERE idEvento = '$_POST[idEvento]' ";
        $resUpdate = mysqli_query($conexion, $sqlActualizar);
        echo json_encode($resUpdate);
        break;
    case 'borrar':
        # code...
        $sqlBorrar = "DELETE FROM eventos_programados_equipos WHERE idEvento = $_POST[idEvento]";
        $resBorrar = mysqli_query($conexion, $sqlBorrar);
        echo json_encode($resBorrar);
        break;
    default:
        echo 'No selecciono opcion';
}


