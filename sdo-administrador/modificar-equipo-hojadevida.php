<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (
        !empty($_POST['fechaRecepcion']) && !empty($_POST['nombreEquipo']) && !empty($_POST['valorIva']) &&
        !empty($_POST['marcaEquipo']) && !empty($_POST['idLicitacion']) && !empty($_POST['ordenCompra']) && !empty($_POST['fechaCompra']) && !empty($_POST['fechaEnMarcha'])
        && !empty($_POST['idServicio'])  && !empty($_POST['nombreProveedor']) && !empty($_POST['telefonoProveedor']) || !empty($_POST['emailProveedor'])
    ) {

        //OBTIENE LOS DATOS ENVIADOS POR EL FORMULARIO Y LAS GUARDA EN VARIABLES
        $idHoja = $_POST['idHoja'];
        $numeroInventario = trim($_POST['numeroInventario']);
        $fechaRecepcion = $_POST['fechaRecepcion'];
        $nombreEquipo = trim($_POST['nombreEquipo']);
        $valorIva = trim($_POST['valorIva']);
        $marcaEquipo = trim($_POST['marcaEquipo']);
        $idLicitacion = trim($_POST['idLicitacion']);
        $modeloEquipo = trim($_POST['modeloEquipo']);
        $ordenCompra = trim($_POST['ordenCompra']);
        $numeroSerie = trim($_POST['numeroSerie']);
        $fechaCompra = $_POST['fechaCompra'];
        $fechaEnMarcha = $_POST['fechaEnMarcha'];
        $idServicio = trim($_POST['idServicio']);
        $nombreRecinto = trim($_POST['nombreRecinto']);
        $nombreProveedor = trim($_POST['nombreProveedor']);
        $direccionProveedor = trim($_POST['direccionProveedor']);
        $telefonoProveedor = trim($_POST['telefonoProveedor']);
        $emailProveedor = trim($_POST['emailProveedor']);
        $nombreContactoProveedor = trim($_POST['nombreContactoProveedor']);
        $observacionesContactoProveedor = trim($_POST['observacionesContactoProveedor']);

        //Inserta en la base de datos la información del equipo
        $sqlUpdateEquipo = "UPDATE hoja_de_vida SET numInventario = '$numeroInventario',fechaRecepcion = '$fechaRecepcion',nomEquipo = '$nombreEquipo',valorConIva = $valorIva,marca = '$marcaEquipo',idLicitacion = '$idLicitacion',modelo = '$modeloEquipo',ordenCompra = '$ordenCompra',numSerie = '$numeroSerie',fechaCompra = '$fechaCompra',fechaMarcha = '$fechaEnMarcha',idServicio = $idServicio,recinto = '$nombreRecinto',nomProveedor = '$nombreProveedor',direccionProveedor = '$direccionProveedor',telProveedor = '$telefonoProveedor',emailProveedor = '$emailProveedor',nomContacto = '$nombreContactoProveedor',observaciones = '$observacionesContactoProveedor' WHERE idHoja = $idHoja";

        var_dump($sqlUpdateEquipo);
        exit;
  

    } else {
        header('Location: ../' . $ruta . '/consultar-equipo.php?faltanCampos=true');
    }
}
