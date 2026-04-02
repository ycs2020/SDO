<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idArchivoC'])) {

    $idArchivoC = trim($_POST['idArchivoC']);

    if ($idArchivoC > 0) {

        $nomArchivoC = getNomArchivoC($idArchivoC);

        $rutaArchivoC = getRutaArchivoC($idArchivoC);

        $porciones = explode("../sdo-carpetas", $rutaArchivoC);

        $rutaArchivo = __DIR__ . $porciones[1];

# Obtener nombre sin ruta completa, únicamente para sugerirlo al guardar
        $nombreArchivo = basename($rutaArchivo);

# Algunos encabezados que son justamente los que fuerzan la descarga
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=$nombreArchivo");
# Leer el archivo y sacarlo al navegador
        readfile($rutaArchivo);
# No recomiendo imprimir más cosas después de esto

    } else {
        header('Location: ../' . $ruta . '/carpetas_listado.php?errorDescargaArchivo=true');
    }


} else {
    header('Location: ../' . $ruta . '/carpetas_listado.php?errorDescargaArchivo=true');
}
?>