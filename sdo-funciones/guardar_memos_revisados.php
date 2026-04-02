<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
    $paginaURL = 'visualizardetalles.php';
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
    $paginaURL = 'detalle_ordenes_trabajo.php';
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
    $paginaURL = 'detalle_ordenes_trabajo.php';
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
    $paginaURL = 'detalle_ordenes_trabajo.php';
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
    $paginaURL = 'detalle_ordenes_trabajo.php';
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
    $paginaURL = 'detalle_ordenes_trabajo.php';
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit();
}

if (isset($_POST['idTG'])) {
    include("conexion.php"); // Asegúrate de tener tu conexión activa

    $idTG = $_POST['idTG'];
    $nombreUsuario = $_POST['nombreUsuario']; // Este no lo usas aquí, pero lo puedes guardar si quieres
    $now = date('Y-m-d H:i:s');

    // Recoger datos desde el formulario
    $memos = $_POST['listadoUsuarios'] ?? [];      // Se refiere a MEMOS
    $formatos = $_POST['listadoFormatos'] ?? [];

    // Convertir arrays en strings separados por " - "
    $memos_string = implode(" - ", $memos);
    $formatos_string = implode(" - ", $formatos);

    // Preparar consulta
    $sql = "INSERT INTO permisos_revisados (memos, formatos, creado_por, fecha_creacion, idTG) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    // Aquí asumimos que creado_por = nombreUsuario (puedes cambiar por ID de sesión si es mejor)
    $stmt->bind_param("ssssi", $memos_string, $formatos_string, $nombreUsuario, $now, $idTG);

    if ($stmt->execute()) {
        header('Location: ../'.$rutaDireccion.'/'.$paginaURL.'?logroPermisos=true');
    } else {
        header('Location: ../'.$rutaDireccion.'/'.$paginaURL.'?errorPermisos=true');
    }

    $stmt->close();
    $conexion->close();
}else{
    echo 'Error 1';
}



