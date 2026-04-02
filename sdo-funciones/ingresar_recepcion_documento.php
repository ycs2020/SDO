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

if(isset($_POST['fechaRecepcion'],$_POST['nomRecepcion'],$_POST['fechaEmisor'],$_POST['nomEmisor'],$_POST['unidadEmisor'],$_POST['selectMetodo'],$_POST['estado'])){
    $fechaRecepcion = $_POST['fechaRecepcion'];
    $nomRecepcion = $_POST['nomRecepcion'];
    $fechaEmisor = $_POST['fechaEmisor'];
    $nomEmisor = $_POST['nomEmisor'];
    $unidadEmisor = $_POST['unidadEmisor'];
    $selectMetodo = $_POST['selectMetodo'];
    $email = $_POST['email'];
    $selectControl = $_POST['selectControl'];
    $estado = $_POST['estado'];
    $fechaIngresoDocu = $_POST['fechaIngresoDocu'];
    $nomDocumento = $_POST['nomDocumento'];
    $numDocumento = $_POST['numDocumento'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $archivoDocumento = $_FILES['archivoDocumento'];
    if($email == '' || empty($email)){
        $email = 'Correo no ingresado';
    }

    $selectControl = trim($selectControl);

    if(empty($selectControl)){
        $selectControl = 'Personal No Asignado';
    }else{
        $selectControl = $selectControl;
    }


    $folio1 = 'DC-';

    $sql = "INSERT INTO recepcion_documento (idRecepcion,folioRecep,nomRecepcion,fechaRecepcion,unidadEmisor,nomEmisor,fechaEmisor,metodoEmisor,correo,asignadoRecepcion,estado)
    VALUES (NULL,'".$folio1."','".$nomRecepcion."','".$fechaRecepcion."','".$unidadEmisor."','".$nomEmisor."','".$fechaEmisor."','".$selectMetodo."','".$email."','".$selectControl."','".$estado."')";

    $res = mysqli_query($conexion,$sql);

    if($res){
        $idRecepcion = mysqli_insert_id($conexion);

        if ($idRecepcion < 10) {
            $idRecepcion2 = "000" . $idRecepcion;
        } else if ($idRecepcion > 9 && $idRecepcion < 100) {
            $idRecepcion2 = "00" . $idRecepcion;
        } else if ($idRecepcion > 99 && $idRecepcion < 1000) {
            $idRecepcion2 = "0" . $idRecepcion;
        } else if ($idRecepcion > 999) {
            $idRecepcion2 = $idRecepcion;
        } else {
            $idRecepcion2 = $idRecepcion;
        }

        $folio2 = $folio1.$idRecepcion2;

        $sqlUpdate = "UPDATE recepcion_documento SET folioRecep = '$folio2' WHERE idRecepcion = $idRecepcion";
        $resUpdate = mysqli_query($conexion,$sqlUpdate);


        $detalle = $nomRecepcion . ' ha recepcionado el documento con folio: '. $folio2;

        $sqlObs = "INSERT INTO recepcion_observacion (idObsRecepcion,detalleObservacion,fechaObs,nomCreador,idRecepcion,estado)
        VALUES (NULL,'".$detalle."','".$fechaRecepcion."','".$nomUsuario."',$idRecepcion,'".$estado."')";
        $resObs = mysqli_query($conexion,$sqlObs);


        if($estado == 'Derivado'){
            $detalleObs = $nomUsuario . ' ha derivado el ingreso del documento a '. $selectControl;
            $sqlObs1 = "INSERT INTO recepcion_observacion (idObsRecepcion,detalleObservacion,fechaObs,nomCreador,idRecepcion,estado)
           VALUES (NULL,'".$detalleObs."','".$fechaRecepcion."','".$nomUsuario."',$idRecepcion,'".$estado."')";
            $resObs1 = mysqli_query($conexion,$sqlObs1);
        }

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
                    if ($extImagen == "docx" || $extImagen == "doc"  || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif" || $extImagen == "eml") {
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
                    }else{
                        header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
                    }
                }
            }
        }

        if($resUpdate){
            header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?exitoAgregarDocumento=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
        }
    }else{
        header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
    }
}else{
    header('Location: ../'.$rutaDireccion.'/listar_recepcion_documento.php?errorAgregarDocumento=true');
}

