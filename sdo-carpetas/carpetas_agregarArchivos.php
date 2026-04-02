<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

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
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['idCarpetaPadre'],$_POST['idDepartamentoCarpeta'])) {

    $idCarpetaPadre = $_POST['idCarpetaPadre'];
    $idDepartamentoCarpeta = trim($_POST['idDepartamentoCarpeta']);

    if ($idCarpetaPadre > -1) {

        if (isset($_FILES["adjuntosArchivosC"])) {

            $count = 0;
            foreach ($_FILES['adjuntosArchivosC']['tmp_name'] as $tmpFilee) {
                if (is_uploaded_file($tmpFilee)) {
                    $count++;
                }
            }


            if ($count > 0) {

                for ($i = 0; $i < $count; $i++) {
                    $tmp_name = $_FILES["adjuntosArchivosC"]["tmp_name"][$i];
                    $name = $_FILES["adjuntosArchivosC"]["name"][$i];

                    if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                        $archivoCarpetas = "Vacio";
                    } else {

                        if($idCarpetaPadre == 0){
                            $directorio = '../sdo-carpetas/';
                        }else{
                            $directorio = getRutaArchivosCarpetas($idCarpetaPadre). "/";
                        }

                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777);
                        }

                        $dir = opendir($directorio);

                        $rutaArchivo = $directorio . $name;

                        $separarNombre = explode(".", $name);

                        $extImagen = end($separarNombre);

                        $extImagen = mb_strtolower($extImagen);


                        if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml" || $extImagen == "ppt" || $extImagen == "pptx" || $extImagen == "png" || $extImagen == "jpg" || $extImagen == "jpeg") {

                            if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                chmod($rutaArchivo, 0777);

                                $queryArchivos = "INSERT INTO carpeta_archivos(idArchivoC,nomArchivoC,rutaArchivoC,idCarpeta,idDepartamento) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $idCarpetaPadre . "','".$idDepartamentoCarpeta."')";

//                                echo $queryArchivos .'<br><br>';
                                $resArchivos = mysqli_query($conexion, $queryArchivos);

                                if ($resArchivos) {
                                    $archivoCarpetas = 'Usando';
                                } else {

                                    $archivoCarpetas = 'Error';
                                }
                            } else {
                                $archivoCarpetas = 'Error';
                            }
                        } else {
                            $archivoCarpetas = 'formatoIncorrecto';
                        }
                    }
                }

            } else {
                $archivoCarpetas = "Vacio";
            }
        } else {
            $archivoCarpetas = "Vacio";
        }


    }else{
        $archivoCarpetas = "Error2";
    }

}else{
    $archivoCarpetas = "Error3";
}

//echo $archivoCarpetas;
if($archivoCarpetas == 'Usando'){
    header('Location: ../' . $ruta . '/carpetas_listado.php?exitoAgregarArchivo=true');
}else if($archivoCarpetas == 'Error'){
    header('Location: ../' . $ruta . '/carpetas_listado.php?errorAgregarArchivo=true');
}else if($archivoCarpetas == 'Vacio'){
    header('Location: ../' . $ruta . '/carpetas_listado.php?vacioAgregarArchivo=true');
}else if($archivoCarpetas == 'formatoIncorrecto'){
    header('Location: ../' . $ruta . '/carpetas_listado.php?formatoAgregarArchivo=true');
}else{
    header('Location: ../' . $ruta . '/carpetas_listado.php?errorAgregarArchivo=true');
}