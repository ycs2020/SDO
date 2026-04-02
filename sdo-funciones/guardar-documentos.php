<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];
$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
}else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if($rolUsuario2 == "directiva"){
    $rutaDireccion = "sdo-directiva";
} else if($rolUsuario2 == 'oficina tecnica'){
    $rutaDireccion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if($_POST['nomArchivo']) {

    $nomArchivo = $_POST['nomArchivo'];
    $tipoArchivoAdjuntar = $_POST['tipoArchivo'];
    $archivoAdjunto = $_FILES['archivoAdjunto'];
    $now = date('Y-m-d H:i:s');
    $idCategoria = $_POST['idCategoria'];

    $sqlArchivo = "SELECT id FROM archivos_documentacion_general ORDER BY id DESC LIMIT 1;";
    $resArchivo = mysqli_query($conexion, $sqlArchivo);

    while($rowArchivo = $resArchivo->fetch_assoc()){
        $idArchivo = $rowArchivo['id'];  // Aquí usamos 'id' en lugar de 'idArchivo'
        if(empty($idArchivo)){
            $idArchivo = 1;
        }else{
            $idArchivo = $idArchivo++;
        }
    }


    $directorio = "../archivos-documentacion_general/carpeta-" . $idArchivo . "/";


    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    if(empty($nomArchivo) || empty($tipoArchivo) || empty($archivoAdjunto)) {
        if(isset($_FILES['archivoAdjunto'])) {
            $nombreArchivo = $_FILES['archivoAdjunto']['name'];

            $tipoArchivo = $_FILES['archivoAdjunto']['type'];
            $tamanoArchivo = $_FILES['archivoAdjunto']['size'];
            $tmpArchivo = $_FILES['archivoAdjunto']['tmp_name'];

            // Directorio de destino donde se guardarán los archivos subidos
            $directorio = "../archivos-documentacion_general/carpeta-" . $idArchivo . "/";

            $ruta = $directorio . $nombreArchivo;

            if (file_exists($ruta)) {
                header('Location: ../'. $rutaDireccion .'/documentacion_general.php?errorArchivoExiste=true');
            } else {
                // Mover el archivo del directorio temporal al directorio de destino
                if(move_uploaded_file($tmpArchivo, $directorio . $nombreArchivo)){
                    echo "El archivo se ha subido correctamente.";

                    $queryImagen = "INSERT INTO `archivos_documentacion_general`(`id`, `fecha`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idCategoria`) 
                    VALUES (NULL,'$now','$nomArchivo','$ruta','$tipoArchivo','$idCategoria')";
                    $resImagen = mysqli_query($conexion, $queryImagen);
                }else{
                    echo "Ha ocurrido un error al subir el archivo.";
                }
            }

            if($resImagen){
                header('Location: ../'. $rutaDireccion .'/documentacion_general.php?exitoSubirArchivos=true');
            }else{
                header('Location: ../'. $rutaDireccion .'/documentacion_general.php?errorSubirArchivos=true');
            }

        }
    }else{
        header('Location: ../'.$rutaDireccion.'/documentacion_general.php?vaciosCampos=true');
    }

}else{
    header('Location: ../'.$rutaDireccion.'/documentacion_general.php?errorGeneral=true');
}

