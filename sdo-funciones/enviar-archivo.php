<?php
include 'conexion.php';
include 'funciones.php';

if (!empty($_FILES['archivoAdjunto'])) {
    $directorio = "../sdo-archivo-orden-de-compra-panol/id-".'/';
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $totalArchivos = count($_FILES['archivoAdjunto']['name']);

    // Recorre todos los archivos subidos
    for ($i = 0; $i < $totalArchivos; $i++) {
        $nombreArchivo = $_FILES['archivoAdjunto']['name'][$i];
        $rutaTemporal = $_FILES['archivoAdjunto']['tmp_name'][$i];
        $rutaDestino = $directorio . $nombreArchivo;

        // Obtener la extensión del archivo
        $separarNombre = explode(".", $nombreArchivo);
        $extImagen = end($separarNombre);
        $extImagen = mb_strtolower($extImagen);

        if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                $sqlInsertArchivo = "INSERT INTO `archivo-orden-de-compra-panol`(`id`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idSoliCompra`) 
                VALUES (NULL,'$nombreArchivo','$rutaDestino','$extImagen',1)";
                $resInsertArchivo = mysqli_query($conexion,$sqlInsertArchivo);
                if($resInsertArchivo){
                    echo 'TODO SE SUBIO BIEN';
                }else{
                    echo 'ERROR AL SUBIR';
                }
            } else {
                echo 'ERROR 1';
            }
        }
        // Mueve el archivo de la ubicación temporal a la definitiva
    }
}