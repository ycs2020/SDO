<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);
date_default_timezone_set('America/Santiago');

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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['nomModificaPab'], $_POST['ordenRegistroPab'], $_POST['idRegistroPab'])) {

    $idRegistroPab = $_POST['idRegistroPab'];
    $nomModificaPab = $_POST['nomModificaPab'];
    $ordenRegistroPab = $_POST['ordenRegistroPab'];
    $fechaRegistroPab = date("Y-m-d H:i:s");
    $valor = '';
    $cadenaVariables = '';
    $cadenaValores = '';
    $cadena = '';

    for ($i = 2; $i <= 10; $i++) {

        if (isset($_POST['tempPabellon' . $i])) {
            if ($_POST['tempPabellon' . $i] > -1) {
                if (isset($_POST['humedadPabellon' . $i])) {
                    if ($_POST['humedadPabellon' . $i] > -1) {
                        $valor = 'correcto';

                        $cadena .= 'tempPabellon'.$i.' = '.$_POST['tempPabellon' . $i].', humedadPabellon'.$i.' = '.$_POST['humedadPabellon' . $i];

                        if($i < 10){
                            $cadena .= ',';
                        }
                    } else {
                        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
                    }
                } else {
                    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
                }
            } else {
                header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
            }
        } else {
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
        }

    }


    if($idRegistroPab > 1 && !empty($nomModificaPab)){
        $sql = "UPDATE pabellones_correo SET nomRegistroPab = '".$nomModificaPab."',fechaRegistroPab = '".$fechaRegistroPab."', ordenRegistroPab ='".$ordenRegistroPab."',".$cadena.", correoEnviado = 0 WHERE idRegistroPab = ".$idRegistroPab;

        if (mysqli_query($conexion, $sql)) {
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoModificarParam=true');
        } else {
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorModificarParam=true');
        }

    }else{
        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
    }

}else{

    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?noModData=true');
}
?>
