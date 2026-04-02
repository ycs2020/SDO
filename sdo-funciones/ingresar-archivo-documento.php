<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = '';

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idRecepcion'],$_POST['folio'],$_POST['nomDocumento'],$_POST['numDocumento'],$_POST['tipoDocumento'],$_FILES['archivoDocumento'])){
    $idRecepcion = $_POST['idRecepcion'];
    $folio2 = $_POST['folio'];
    $nomDocumento = $_POST['nomDocumento'];
    $numDocumento = $_POST['numDocumento'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $archivoDocumento = $_FILES['archivoDocumento'];
    $fechaRecepcion = $_POST['fechaRecepcion'];

    if (!empty($_FILES['archivoDocumento']['name'])) {
        $directorio = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folio2 ."/numDocumento-" . $numDocumento . "/";
        mkdir($directorio, 0777, true);
    }
    if (isset($_FILES["archivoDocumento"]) || empty($_FILES['archivoDocumento']) || $_FILES['archivoDocumento'] == 'Array') {
        $tot = count($_FILES["archivoDocumento"]["name"]);
        for ($i = 0; $i < $tot; $i++) {
            $tmp_name = $_FILES["archivoDocumento"]["tmp_name"];
            $name = $_FILES["archivoDocumento"]["name"];
            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                header('Location: ../' . $rutaDireccion. '/listar_recepcion_documento.php?archivoSinNombre=true');
            }else{
                $directorio = "../sdo-administrador/archivos-recepcion-documentos/folio-". $folio2 ."/numDocumento-" . $numDocumento . "/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                $dir = opendir($directorio);
                $ruta = $directorio . $name;
                $separarNombre = explode(".", $name);
                $extImagen = end($separarNombre);
                $extImagen = mb_strtolower($extImagen);
                if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                    if(file_exists($ruta)){
                        header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
                    }else{
                        if (move_uploaded_file($tmp_name, $ruta)) {
                            $sqlArchivo = "INSERT INTO archivos_recepcion_documento (idArchivosDocu,nomDocumento,rutaDocumento,idRecepcion,fechaDocumento,tipoDocumento,numDocumento,folioDocumento)
                                VALUES (NULL,'".$nomDocumento."','".$ruta."',$idRecepcion,'".$fechaRecepcion."','".$tipoDocumento."','".$numDocumento."','".$folio2."')";
                            $resArchivo = mysqli_query($conexion,$sqlArchivo);
                            if($resArchivo){
                                header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?exitoAgregarDocumentoconArchivo=true');
                            }else{
                                header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
                            }
                        }else{
                            header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
                        }
                    }
                }
            }
        }
    }else{
        header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
    }
}else{
    header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
}