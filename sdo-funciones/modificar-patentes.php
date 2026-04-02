<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_ES");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    if (isset($_POST['otraPatenteModi'])) {
        $otraPatenteModi = $_POST['otraPatenteModi'];
        $otrotipovehiculoModi = $_POST['otrotipovehiculoModi'];
        $otroanovehiculoModi = $_POST['otroanovehiculoModi'];
        $otramarcavehiculoModi = $_POST['otramarcavehiculoModi'];
        $otromodeloavehiculoModi = $_POST['otromodeloavehiculoModi'];
        $otrocolorvehiculoModi = $_POST['otrocolorvehiculoModi'];
        $desactivar = $_POST['desactivar'];
        $idPatente = $_POST['idPatente'];
        if($desactivar == 'on'){
            $sql = "UPDATE `patentes_movilizacion` SET numPatente='$otraPatenteModi',`tipoMovil`='$otrotipovehiculoModi',`anoVehiculo`= $otroanovehiculoModi,`marcaVehiculo`='$otramarcavehiculoModi',`modeloVehiculo`='$otromodeloavehiculoModi',`colorVehiculo`='$otrocolorvehiculoModi',`patentesactivas`= 0 WHERE idPatente = $idPatente";
        }else{
            $sql = "UPDATE `patentes_movilizacion` SET numPatente='$otraPatenteModi',`tipoMovil`='$otrotipovehiculoModi',`anoVehiculo`= $otroanovehiculoModi,`marcaVehiculo`='$otramarcavehiculoModi',`modeloVehiculo`='$otromodeloavehiculoModi',`colorVehiculo`='$otrocolorvehiculoModi' WHERE idPatente = $idPatente";
        }

//        echo $sql;
//        exit();
        $res = mysqli_query($conexion,$sql);

        if($res){
            header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?exitoModificarPatente=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?errorModificarPatente1=true');
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?errorModificarPatente2=true');
    }
}else{
    header('Location: ../' . $rutaDireccion . '/listado-viajes-contra-nuevo.php?errorModificarPatente3=true');
}