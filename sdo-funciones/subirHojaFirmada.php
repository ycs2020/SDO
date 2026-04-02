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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_FILES['imagenHoja'] && $_POST['idHoja']) {

    $idHoja = $_POST['idHoja'];
    $imagenHoja = $_FILES['imagenHoja'];

    if($idHoja < 1){
        header('Location: ../'.$rutaDireccion.'/ordenes-subir-firmas.php?errorUpload=true');
    }else{
        $_SESSION['idOrden'] = $idHoja;
    }

    //SUBIR IMAGEN
    if ($imagenHoja) {

        $directorio = "../sdo-hojaTec-firmadas/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
    }

    if (isset($_FILES["imagenHoja"])) {

            $tmp_name = $_FILES["imagenHoja"]["tmp_name"];
            $name = $_FILES["imagenHoja"]["name"];

            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                echo 'sin nombre';
                exit;
            } else {

                $directorio = "../sdo-hojaTec-firmadas/";

                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                $dir = opendir($directorio);

                $ruta = $directorio . $name;

                $separarNombre = explode(".", $name);

                $extImagen = end($separarNombre);

                $extImagen = mb_strtolower($extImagen);

                if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                    if (move_uploaded_file($tmp_name, $ruta)) {
                        $query = "INSERT INTO archivos_hoja_firmada( nomArchivoHT,rutaArchivoHT,extArchivo, idHoja)";
                        $query .=  "VALUES('$name', '$ruta','$extImagen', $idHoja )";
                        $resArchivo = mysqli_query($conexion, $query);
                        if ($resArchivo) {
                            header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?exitoUpload=true');
                        }else{
                            header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?errorUpload=true');
                        }
                    } else {
                        echo 'No subi';
                        header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?errorUpload=true');
                    }
                }else{
                    header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?errorUpload=true');
                }
            }
    }else{
        header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?errorUpload=true');
    }
}else{

    header('Location: ../'.$rutaDireccion.'/ordenes-menu-admin.php?errorUpload=true');
}
