<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los datos del formulario
    $fechaProtocolo = date('Y-m-d H:i:s'); // Fecha actual
    $ejecutanteProtocolo = empty($_POST['nomFuncionario'])
        ? "No Aplica"
        : mysqli_real_escape_string($conexion, $_POST['nomFuncionario']);
    $idEquipo = intval($_POST['select2equipo']);
    $estadoEquipo = ($_POST['estadoEquipo'] === 'Operativo') ? 1 : 0;
    $entornoEquipo = ($_POST['entornoEquipo'] === 'Apto') ? 1 : 0;
    $tempEquipo = floatval($_POST['tempEquipo']);
    $sonidoEquipo = ($_POST['sonidoAlarma'] === 'Activado') ? 1 : 0;
    $luzEquipo = ($_POST['luzAlarma'] === 'Activado') ? 1 : 0;
    $observacionApoyoClinico = empty($_POST['observacionesApoyo'])
        ? "No Aplica"
        : mysqli_real_escape_string($conexion, $_POST['observacionesApoyo']);
    $creadorApoyoClinico = $ejecutanteProtocolo;

    $getUltimoIDProtocoloRefrigerador = getUltimoIDProtocoloRefrigerador();

    if($getUltimoIDProtocoloRefrigerador >= 0){
        $getUltimoIDProtocoloRefrigerador++;
    }else{
        header('Location: ../'.$rutaDireccion.'/protocolo_refrigeradores.php?errorGenerarProtocolo=true');

    }

    $getUltimoIDProtocoloRefrigerador = str_pad($getUltimoIDProtocoloRefrigerador, 4, "0", STR_PAD_LEFT);

    // Genera un folio único para el protocolo (puedes ajustarlo según tu necesidad)
    $folioProtocolo = 'PRR-'.$getUltimoIDProtocoloRefrigerador;



    if($idEquipo > 0){
        $sql = "INSERT INTO protocolo_refrigeradores (
                fechaProtocolo,
                ejecutanteProtocolo,
                folioProtocolo,
                idEquipo,
                estadoEquipo,
                entornoEquipo,
                tempEquipo,
                sonidoEquipo,
                luzEquipo,
                observacionApoyoClinico,
                creadorApoyoClinico
            ) VALUES (
                '$fechaProtocolo',
                '$ejecutanteProtocolo',
                '$folioProtocolo',
                $idEquipo,
                $estadoEquipo,
                $entornoEquipo,
                $tempEquipo,
                $sonidoEquipo,
                $luzEquipo,
                '$observacionApoyoClinico',
                '$creadorApoyoClinico'
            )";

        if (mysqli_query($conexion, $sql)) {

            header('Location: ../'.$rutaDireccion.'/protocolo_refrigeradores.php?exitoGenerarProtocolo=true');
        } else {

            echo $sql;
            exit();
            header('Location: ../'.$rutaDireccion.'/protocolo_refrigeradores.php?errorGenerarProtocolo=true');
        }

    }else{
        header('Location: ../'.$rutaDireccion.'/protocolo_refrigeradores.php?errorGenerarProtocolo=true');
    }


}else{
    header('Location: ../'.$rutaDireccion.'/protocolo_refrigeradores.php?errorGenerarProtocolo=true');

}