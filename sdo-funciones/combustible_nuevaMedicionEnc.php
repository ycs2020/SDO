<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

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

if(isset($_POST['ordenCompraAC'],$_POST['noCargaAC'],$_POST['tanque1AC'],$_POST['tanque2AC'],$_POST['tanque3AC'],$_POST['tanque4AC'],$_POST['precioAC'], $_POST['observacionMedAC'], $_POST['fechaCargaAC'])){

    $ordenCompraAC = trim($_POST['ordenCompraAC']);

    $facturaAC = trim($_POST['facturaAC']);

    $boletaAC = trim($_POST['boletaAC']);

    $guiaDespachoAC = trim($_POST['guiaDespachoAC']);

    $noCargaAC = trim($_POST['noCargaAC']);

    $tanque1AC = trim($_POST['tanque1AC']);

    $tanque2AC = trim($_POST['tanque2AC']);

    $tanque3AC = trim($_POST['tanque3AC']);

    $tanque4AC = trim($_POST['tanque4AC']);

    $precioAC = trim($_POST['precioAC']);

    $observacionMedAC = trim($_POST['observacionMedAC']);

    $fechaCargaAC = trim($_POST['fechaCargaAC']);

    if($observacionMedAC == "<p><br></p>"){
        $observacionMedAC = "";
    }


    $now = date('Y-m-d H:i:s', time());

    if(!empty($ordenCompraAC) && !empty($noCargaAC) && !empty($tanque1AC) && !empty($tanque2AC) && !empty($tanque3AC) && !empty($tanque4AC) && !empty($fechaCargaAC)){

        if($tanque1AC > -1 && $tanque2AC > -1 && $tanque3AC > -1 && $tanque4AC  > -1){

            $queryAC = "INSERT INTO combustible_administracion(idAC, fechaAC, ordenCompraAC, facturaAC, boletaAC, guiaDespachoAC, noCargaAC, tanque1AC, tanque2AC, tanque3AC, tanque4AC, precioAC, usuarioAC, observacionAC, tipoAC) ";
            $queryAC .= " VALUES(NULL, '" . $fechaCargaAC . "', '".$ordenCompraAC."', '".$facturaAC."', '".$boletaAC."', '".$guiaDespachoAC."', '".$noCargaAC."',".$tanque1AC.",".$tanque2AC.",".$tanque3AC.",".$tanque4AC.",".$precioAC.", '".$nomCreador."', '".$observacionMedAC."', 'Medición Normal')";
            
            $resAC = mysqli_query($conexion, $queryAC);

            if($resAC){
                header('Location: ../' . $ruta . '/combustible_administracion.php?exitoAgregarAC=true');
            }else{
                header('Location: ../' . $ruta . '/combustible_administracion.php?errorAgregarAC=true');
            }

        }else{
            header('Location: ../' . $ruta . '/combustible_administracion.php?errorAgregarAC=true');
        }

    }else{
        header('Location: ../' . $ruta . '/combustible_administracion.php?errorAgregarAC=true');
    }


}else{
    header('Location: ../' . $ruta . '/combustible_administracion.php?errorAgregarAC=true');

    // $ordenCompraAC = trim($_POST['ordenCompraAC']);

    // $facturaAC = trim($_POST['facturaAC']);

    // $boletaAC = trim($_POST['boletaAC']);

    // $guiaDespachoAC = trim($_POST['guiaDespachoAC']);

    // $noCargaAC = trim($_POST['noCargaAC']);

    // $tanque1AC = trim($_POST['tanque1AC']);

    // $tanque2AC = trim($_POST['tanque2AC']);

    // $tanque3AC = trim($_POST['tanque3AC']);

    // $tanque4AC = trim($_POST['tanque4AC']);

    // $precioAC = trim($_POST['precioAC']);

    // $observacionMedAC = trim($_POST['observacionMedAC']);




    // echo '1'.$ordenCompraAC .' <br><br>';

    // echo '2'.$facturaAC.' <br><br>';

    // echo '3'.$noCargaAC.' <br><br>';

    // echo '4'.$boletaAC.' <br><br>';

    // echo '5'.$guiaDespachoAC.' <br><br>';

    // echo '6'.$tanque1AC.' <br><br>';

    // echo '7'.$tanque2AC.' <br><br>';

    // echo '8'.$tanque3AC.' <br><br>';

    // echo '9'.$tanque4AC.' <br><br>';

    // echo '10'.$precioAC.' <br><br>';

    // echo '11'.$observacionMedAC.' <br><br>';

    // echo '12'.$tipoAC.' <br><br>';

    // echo '13'.$usuarioAC.' <br><br>';
    
    // echo '14'.$noCargaAC.' <br><br>';
}