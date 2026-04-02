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
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


$idConvenio = $_POST['idConvenio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreConvenio = $_POST['nombreConvenio'] ?? '';
    $idLicitacion   = $_POST['idLicitacion'] ?? '';
    $duracion       = $_POST['duracion'] ?? '';
    $totalConvenio  = $_POST['totalConvenio'] ?? 0;
    $idConvenio     = $_POST['idConvenio'] ?? 0;

    // Asegura que la ruta esté definida
    $ruta = $ruta ?? '';

    $sql = "UPDATE convenios_carola 
            SET convenio='$nombreConvenio',
                id_licitacion='$idLicitacion',
                duracion='$duracion',
                total_convenio=$totalConvenio
            WHERE id=$idConvenio";

    $res = mysqli_query($conexion, $sql);

    if ($res) {
        header("Location: ../$ruta/convenios_listado.php?exitoRegistro=true");
        exit;
    } else {
        header("Location: ../$ruta/convenios_listado.php?errorRegistro=true");
        exit;
    }

} else {
    $ruta = $ruta ?? '';
    header("Location: ../$ruta/convenios_listado.php?errorRegistroGeneral=true");
    exit;
}
