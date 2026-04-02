<?php
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idSoliCompra = $_POST['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['archivoAdjunto'])) {
        $directorio = "../sdo-archivo-orden-de-compra-panol-cc/id-$idSoliCompra".'/';
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
                    $sqlInsertArchivo = "INSERT INTO `archivo-orden-de-compra-panol-cc`(`id`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idSoliCompra`) 
                            VALUES (NULL,'$nombreArchivo','$rutaDestino','$extImagen',$idSoliCompra)";
                    $resInsertArchivo = mysqli_query($conexion,$sqlInsertArchivo);
                }else{
                    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?noSubioCarpeta=true');
                }
            }else{
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorFormato=true');
            }
        }
    }else{
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?sinArchivo=true');
    }
}