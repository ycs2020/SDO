<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_ES");
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
} else if ($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
} else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otraPatente = $_POST['otraPatente'];
    $otrotipovehiculo = $_POST['otrotipovehiculo'];
    $otroanovehiculo = $_POST['otroanovehiculo'];
    $spa = $_POST['spa'];
    $activopatente = 0;
    if($spa == 'spa'){
        $activopatente = 2;
    }else{
        $activopatente = 1;
    }
    $otramarcavehiculo = $_POST['otramarcavehiculo'];
    $otromodelovehiculo = $_POST['otromodelovehiculo'];
    $otrocolorvehiculo = $_POST['otrocolorvehiculo'];


    $buscarPatente = buscarPatente1($otraPatente);

    if($buscarPatente == 0){
        $sql = "INSERT INTO patentes_movilizacion(idPatente, numPatente, tipoMovil, anoVehiculo,marcaVehiculo,modeloVehiculo,colorVehiculo,patentesactivas) 
    VALUES (NULL,'$otraPatente','$otrotipovehiculo',$otroanovehiculo,'$otramarcavehiculo','$otromodelovehiculo','$otrocolorvehiculo',$activopatente)";
        $res = mysqli_query($conexion,$sql);
        if($res){
            if($spa == 'spa'){
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?exitoIngresoPatente=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?exitoIngresoPatente=true');
            }
        }else{
            if($spa == 'spa'){
                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?noseIngresoPatente=true');
            }else{
                header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?noseIngresoPatente=true');
            }
        }
//        $sqlUpdate = "UPDATE patentes_movilizacion SET colorVehiculo = '$otrocolorvehiculo',modeloVehiculo = '$otromodelovehiculo',marcaVehiculo = '$otramarcavehiculo',tipoMovil = '$otrotipovehiculo', anoVehiculo = $otroanovehiculo, pantentesactivas = $activopatente WHERE numPatente = '$otraPatente'";
//        $resUpdate = mysqli_query($conexion,$sqlUpdate);
//        if($resUpdate){
//            if($spa == 'spa' ){
//                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?exitoModificacionPatente=true');
//            }else{
//                header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?exitoModificacionPatente=true');
//            }
//        }else{
//            if($spa == 'spa' ){
//                header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?noseModificoPatente=true');
//            }else{
//                header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?noseModificoPatente=true');
//            }
//        }
    }else{
        if($spa == 'spa' ){
            header('Location: ../' . $rutaDireccion . '/listado-viajes-vamoos-spa.php?patenteExiste=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?patenteExiste=true');
        }
    }


}else{
    header('Location: ../' . $rutaDireccion . '/viajes-1.php?noseIngresoPatente=true');
}
