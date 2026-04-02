<?php

include_once 'conexion.php';
include_once 'funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

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

if(isset($_POST['selectOT'])) {
    $selectOT = $_POST['selectOT'];
    $now = date('Y-m-d H:i:s');

    if($selectOT > 0){
        if (!empty($_FILES['archivoAdjunto'])) {
            $archivos = $_FILES['archivoAdjunto'];
            if ($archivos) {

                $directorio = "../archivos-OT-firmadas/OT-".$selectOT.'/';
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
            }

            if (isset($_FILES["archivoAdjunto"])) {

                $tmp_name = $_FILES["archivoAdjunto"]["tmp_name"];
                $name = $_FILES["archivoAdjunto"]["name"];

                if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                    echo 'sin nombre';
                    exit;
                } else {

                    $directorio = "../archivos-OT-firmadas/OT-".$selectOT.'/';

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
                            $sqlArchivo = "INSERT INTO archivos_ot_firmadas (idArchivosFirmadas,fecha,nomArchivo,rutaArchivo,tipoArchivo,idTG) VALUES (NULL,'".$now."','".$name."','".$ruta."','".$extImagen."',$selectOT)";
                            $resArchivo = mysqli_query($conexion,$sqlArchivo);
                            if($resArchivo){
                                header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?exitoSubirArchivo=true');
                            }else{
                                header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?errorSubirArchivo5=true');
                            }
                        } else {
                            echo 'No subi';
                            header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?errorUpload1=true');
                        }
                    }else{
                        echo $extImagen;
                    }
                }
            }else{
                header('Location: ../'.$rutaDireccion.'/trabajos_general_listado.php?errorUpload3=true');
            }

        } else {
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorSubirArchivo=true&mensaje=No se seleccionó ningún archivo para subir.');
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?errorSubirArchivo=true&mensaje=No se seleccionó ningún archivo para subir2.');
    }
}else{
    header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?errorSubirArchivo2=true');
}


/*foreach ($archivos['name'] as $indice => $nombreArchivo) {
            if (!empty($_FILES['archivoAdjunto']['name'])) {
                $directorio = "../sdo-administrador/archivos-OT-firmadas/OT-". $selectOT ."/";
                mkdir($directorio, 0777, true);
            }
            $tipoArchivo = $archivos['type'][$indice];
            $tamanioArchivo = $archivos['size'][$indice];
            $rutaTemporalArchivo = $archivos['tmp_name'][$indice];
            $name = $archivos['name'][$indice];

            if ($rutaTemporalArchivo == '' || $rutaTemporalArchivo == ' ' || empty($rutaTemporalArchivo)) {
                header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?archivoSinNombre=true');
            }else{
                $directorio = "../sdo-administrador/archivos-OT-firmadas/OT-". $selectOT ."/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                $dir = opendir($directorio);
                $ruta = $directorio . $name;
                $separarNombre = explode(".", $name);
                $extImagen = end($separarNombre);
                $extImagen = mb_strtolower($extImagen);
                if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                    if (!move_uploaded_file($rutaTemporalArchivo, $ruta)) {
                        $error = error_get_last();
                        echo "Error al mover el archivo: " . $error['message'];
                    }else{
                        $error = error_get_last();
                        echo "Error al mover el archivo: " . $error['message'];
                    }
                    if (move_uploaded_file($rutaTemporalArchivo, $ruta)) {
                        $sqlArchivo = "INSERT INTO archivos_ot_firmadas (idArchivosFirmadas,fecha,nomArchivo,rutaArchivo,tipoArchivo,idTG)
                            VALUES (NULL,'".$now."','".$nombreArchivo."','".$ruta."','".$extImagen."',$selectOT)";
                        $resArchivo = mysqli_query($conexion,$sqlArchivo);
                        if($resArchivo){
                            header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?exitoSubirArchivo=true');
                        }else{
                            header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?errorSubirArchivo5=true');
                        }
                    }else{
                        header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?errorSubirArchivo4=true');
                    }
                }else{
                    header('Location: ../' . $rutaDireccion. '/trabajos_general_listado.php?errorSubirArchivo3=true');
                }
            }
        }*/