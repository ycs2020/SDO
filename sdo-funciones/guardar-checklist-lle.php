<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idTG = $_POST['idTG'];
    $recinto = $_POST['recinto'];
    $id_tirador_bano = $_POST['id_tirador_bano'];
    $id_modulo_bano = $_POST['id_modulo_bano'];
    $id_modulo_3_botones = $_POST['id_modulo_3_botones'];
    $id_luz_plafon = $_POST['id_luz_plafon'];
    $id_consola_enfermeria = $_POST['id_consola_enfermeria'];
    $fecha = $_POST['fecha'];


    $sql = "INSERT INTO `datos_checklist_lle`(`id`, `fecha`, `folio`, `habitacion`, `idTG`) 
    VALUES (NULL,'$fecha','NULL','$recinto',$idTG)";
    $res = mysqli_query($conexion, $sql);
    if($res){
        $idCheck = mysqli_insert_id($conexion);
        if ($idCheck < 10) {
            $idRecepcion2 = "000" . $idCheck;
        } else if ($idTG > 9 && $idCheck < 100) {
            $idRecepcion2 = "00" . $idCheck;
        } else if ($idTG > 99 && $idCheck < 1000) {
            $idRecepcion2 = "0" . $idCheck;
        } else if ($idTG > 999) {
            $idRecepcion2 = $idCheck;
        } else {
            $idRecepcion2 = $idCheck;
        }

        $folio = 'CHK-' . $idRecepcion2 . '-UCAS';

        $sqlUpdate = "UPDATE datos_checklist_lle SET folio = '$folio' WHERE id = $idCheck";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $sqlDatosGenerales = "INSERT INTO `datos_generales_lle`(`id`, `tiradorBaño`, `moduloBaño`, `modulo3Botones`, `luzAvisoPlafon`, `consolaEnfermeria`, `idCheck`) 
        VALUES (NULL,$id_tirador_bano,$id_modulo_bano,$id_modulo_3_botones,$id_luz_plafon,$id_consola_enfermeria,$idCheck)";



    }

}

