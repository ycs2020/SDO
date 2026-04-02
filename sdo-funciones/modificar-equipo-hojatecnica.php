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

    if(!empty($_POST['idHojaTec'])){

        $id = $_POST['idHojaTec'];

        $tipoMantencion = $_POST['tipoMantencion'];
        $ejecutador = $_POST['ejecutador'];
        $trabajo = $_POST['trabajo'];
        $problema = $_POST['problema'];
        $acciones = $_POST['acciones'];
        $radio = $_POST['radio'];
        $ordenCompra = $_POST['ordenCompraTec'];
        $observaciones = $_POST['observaciones'];
        $otroTipoTrabajo = $_POST['otroTipoTrabajo'];

        $sqlUpdate = "UPDATE hoja_tecnica SET otroTipoTrabajo = '$otroTipoTrabajo' ,ejecutadoPor = '${ejecutador}',tipoMantencion = '${tipoMantencion}',trabajoRealizado = '${trabajo}',problema = '${problema}',accionesRealizadas = '${acciones}', tipoTrabajo = '${radio}',ordenCompra = '${ordenCompra}',observaciones = '${observaciones}' WHERE idHojaTec = '${id}'" ;
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        if($resUpdate){
            header('Location: ../' . $ruta . '/consultar-equipo.php?exitoModTec=true');
        }else{
            header('Location: ../' . $ruta . '/consultar-equipo.php?errorModTec=true');
        }

    }else{
        header('Location: ../' . $ruta . '/consultar-equipo.php?noidTec=true');
    }


}
