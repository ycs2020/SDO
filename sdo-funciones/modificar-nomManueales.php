<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else if($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['id'], $_POST['nomArchivoModi'])){
    // Validar y sanear los datos
    $id = intval($_POST['id']);
    $nombreArchivo = mysqli_real_escape_string($conexion, $_POST['nomArchivoModi']);

    // Crear la consulta SQL
    $sql = "UPDATE archivos_equipamiento_hbqp SET nomArchivo = '$nombreArchivo' WHERE id = $id";
    // Ejecutar la consulta
    $res = mysqli_query($conexion, $sql);

    if($res){
        echo 'Actualización exitosa';
    }else{
        echo 'Error al actualizar el archivo';
    }
}else{
    echo 'pi pi';
}