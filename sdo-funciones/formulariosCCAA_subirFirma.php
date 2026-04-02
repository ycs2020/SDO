<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set("America/Santiago");
setlocale(LC_TIME, 'es_CL.UTF-8', 'esp');

$now = date('Y-m-d H:i:s', time());

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
}else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $ruta = "sdo-directiva";
} else if($rolUsuario2 == 'ofcina tecnica'){
    $ruta = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idFormRegAcceso'], $_POST['nomCreador'], $_POST['folioFormRegAcceso'])){
    $idFormRegAcceso = $_POST['idFormRegAcceso'];
    $nomCreador = $_POST['nomCreador'];
    $folioFormRegAcceso = $_POST['folioFormRegAcceso'];

    if ($idFormRegAcceso > 0 && !empty($nomCreador) && !empty($folioFormRegAcceso)){
        $nomCreador2 = mb_convert_case($nomCreador,MB_CASE_TITLE,'UTF-8');
        $archivoFirma = '';

        $targetDirectory = "../sdo-formulariosCCAA/" . $folioFormRegAcceso . "/"; // Directorio de destino para guardar el archivo
        // Verificar si el directorio de destino existe, si no, créalo
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true); // Puedes ajustar los permisos según tus necesidades
        }
        $targetFile = $targetDirectory . basename($_FILES["documentoFirmado"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $nomArchivo = basename($_FILES["documentoFirmado"]["name"]);

        // Verificar si el archivo es un PDF
        if ($fileType != "pdf") {
            $archivoFirma = 'formatoIncorrecto';
        } else {
            // Mover el archivo al directorio de destino
            if (move_uploaded_file($_FILES["documentoFirmado"]["tmp_name"], $targetFile)) {
                chmod($targetFile, 0777);

                $queryCotizacion = "INSERT INTO formularios_documentacion(idDocForm,nomDocForm,rutaDocForm,fechaDocForm,idFormRegAcceso) VALUES (NULL,'" . $nomArchivo . "','" . $targetFile . "','".$now."',".$idFormRegAcceso.")";

                $resCotizacion = mysqli_query($conexion, $queryCotizacion);

                if ($resCotizacion) {
                    $archivoFirma = 'exitoFirmaDocumento';
                } else {
                    $archivoFirma = 'errorFirmaDocumento';
                }
            } else {
                $archivoFirma = 'errorFirmaDocumento';
            }
        }

        // Mover el archivo al directorio de destino



        header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?'.$archivoFirma.'=true');

    }else{
        header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorFirmaDocumento=true');
    }


}else{
    header('Location: ../'.$ruta.'/formulariosCCAA_administracion_2.php?errorFirmaDocumento=true');
}