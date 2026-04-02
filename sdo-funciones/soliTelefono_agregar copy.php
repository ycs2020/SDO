<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";

} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";

} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";

} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if (isset($_POST['nomSoliTelefono'], $_POST['cargoSoliTelefono'], $_POST['unidadSoliTelefono'], $_POST['recintoSoliTelefono'], $_POST['detalleSoliTelefono'])) {

    $nomSoliTelefono = trim($_POST['nomSoliTelefono']);
    $nomSoliTelefono = mb_convert_case($nomSoliTelefono,MB_CASE_UPPER,'UTF-8');

    $cargoSoliTelefono = trim($_POST['cargoSoliTelefono']);
    $cargoSoliTelefono = mb_convert_case($cargoSoliTelefono,MB_CASE_UPPER,'UTF-8');

    $unidadSoliTelefono = trim($_POST['unidadSoliTelefono']);
    $unidadSoliTelefono = mb_convert_case($unidadSoliTelefono,MB_CASE_UPPER,'UTF-8');

    $recintoSoliTelefono = trim($_POST['recintoSoliTelefono']);
    $recintoSoliTelefono = mb_convert_case($recintoSoliTelefono,MB_CASE_UPPER,'UTF-8');

    $detalleSoliTelefono = trim($_POST['detalleSoliTelefono']);
    if(!empty($detalleSoliTelefono)){
        $detalleSoliTelefono = str_replace("'", "\'", $detalleSoliTelefono);
    }

    date_default_timezone_set('America/Santiago');
    $now = date('Y-m-d H:i:s', time());

    if(!empty($nomSoliTelefono) && !empty($cargoSoliTelefono) && !empty($unidadSoliTelefono) && !empty($recintoSoliTelefono) && !empty($detalleSoliTelefono)){
        $sql = "INSERT INTO solicitudes_telefonicas(idSoliTelefono,nomSoliTelefono,fechaSoliTelefono,cargoSoliTelefono,unidadSoliTelefono,recintoSoliTelefono,detalleSoliTelefono,derivaSoliTelefono) VALUES(NULL,'".$nomSoliTelefono."','".$now."','".$cargoSoliTelefono."','".$unidadSoliTelefono."','".$recintoSoliTelefono."','".$detalleSoliTelefono."','')";

        // echo $sql;

        $resSoliTelefono = mysqli_query($conexion,$sql);
        
        if($resSoliTelefono){
            header('Location: ../'.$ruta.'/solicitudes_telefonicas.php?exitoAgregarSolicitud=true');
        }else{
            header('Location: ../'.$ruta.'/solicitudes_telefonicas.php?errorAgregarSolicitud1=true');
        }
    }else{
        header('Location: ../'.$ruta.'/solicitudes_telefonicas.php?errorAgregarSolicitud2=true');
    }
    
} else {
    header('Location: ../'.$ruta.'/solicitudes_telefonicas.php?errorAgregarSolicitud3=true');
}
