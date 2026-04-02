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
$idDepartamento = $_SESSION['idDepartamento'];
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

$idEquipo = $_POST['idEquipo'];

$idEquipamiento = $_POST['idEquipamiento'];
$idEquipamientoPadre = $_POST['idEquipamientoPadre'];
if ($idEquipamiento > 0) {
    if ($idEquipamientoPadre > 0) {
        $_SESSION['idEquipo'] = $idEquipamientoPadre;
        $idEquipo = $idEquipamiento;
        $obtenerTipoMenu = obtenerTipoMenu($idEquipamientoPadre);
    } else {
        $_SESSION['idEquipo'] = $idEquipamiento;
        $idEquipo = $idEquipamiento;
        $obtenerTipoMenu = obtenerTipoMenu($idEquipamiento);

    }
    if($rolUsuario2 == "jefes tecnico" && $idDepartamento == 6){
        $sitioWeb = "equipo.php";
    }else if($idDepartamento == 6){
        $sitioWeb = "equipo-inge-clinica.php";
    }else if ($rolUsuario2 == "administrador") {
        if ($obtenerTipoMenu == 'nuevo') {
            $sitioWeb = 'equipo_menuNuevo.php';
        } else {
            $sitioWeb = 'equipo_menuAntiguo.php';
        }
    } else {
        $sitioWeb = 'equipo.php';
    }
    $headerTextCorrecto = 'Location: ../' . $ruta . '/' . $sitioWeb . '?exitoHallazgo=true';
    $headerTextIncorrecto = 'Location: ../' . $ruta . '/' . $sitioWeb . '?errorHallazgo=true';
    $headerTextExtension = 'Location: ../' . $ruta . '/' . $sitioWeb . '?errorExtensionHallazgo=true';

}else{
    echo 'malo';
}

if ($idEquipo == '') {
    $idEquipo = 0;
}

$idCategoria = $_POST['idCarpeta'];
//$nomArchivoHallazgos = $_POST['nomArchivoHallazgos'];
$now = date('Y-m-d H:i:s');
$directorio = "../sdo-archivos-hallazgos/id-$idEquipo" . '/';
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

if (isset($_FILES['archivoAdjuntoHallazgo']) && $_FILES['archivoAdjuntoHallazgo']['error'] === UPLOAD_ERR_OK) {
    // Obtener la información del archivo subido
    $nombreArchivo = $_FILES['archivoAdjuntoHallazgo']['name'];
    $rutaTemporal = $_FILES['archivoAdjuntoHallazgo']['tmp_name'];
    $rutaDestino = $directorio . $nombreArchivo;

    // Obtener la extensión del archivo
    $separarNombre = explode(".", $nombreArchivo);
    $extImagen = end($separarNombre);
    $extImagen = mb_strtolower($extImagen);

    // Verificar si la extensión del archivo es válida
    if ($extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png") {
        // Mover el archivo a la ruta destino
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            // Insertar la información del archivo en la base de datos
            $sqlInsertArchivo = "INSERT INTO archivos_equipamiento_hbqp_hallazgos (id, fecha, nomArchivo, rutaArchivo, tipoArchivo, idEquipo, idCategoria) 
                    VALUES (NULL, '$now', '$nombreArchivo', '$rutaDestino', '$extImagen', $idEquipo, $idCategoria)";
            $resInsertArchivo = mysqli_query($conexion, $sqlInsertArchivo);

            // Verificar si la inserción fue exitosa
            if ($resInsertArchivo) {
                header($headerTextCorrecto);
            } else {
                header($headerTextIncorrecto.'1');
            }
        } else {
            header($headerTextIncorrecto.'2');
        }
    } else {
        header($headerTextExtension);
    }
} else {
    header($headerTextIncorrecto.'3');
}
