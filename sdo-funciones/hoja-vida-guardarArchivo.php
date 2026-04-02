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

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $sqlDepa = "SELECT departamentoPersonal FROM personal WHERE idUsuario = '$idUsuario'";
    $resDepa = mysqli_query($conexion, $sqlDepa);
    while ($rowDepa = $resDepa->fetch_assoc()) {
        $datos = [];
        $datos['departamentoPersonal'] = $rowDepa['departamentoPersonal'];
        $idDepa = $datos['departamentoPersonal'];
    }
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $sqlDepa = "SELECT idDepartamento FROM jefes_tecnicos WHERE idJefe = '$idUsuario'";
    $resDepa = mysqli_query($conexion, $sqlDepa);
    while ($rowDepa = $resDepa->fetch_assoc()) {
        $datos = [];
        $datos['idDepartamento'] = $rowDepa['idDepartamento'];
        $idDepa = $datos['idDepartamento'];
    }
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $idDepa = 1;
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_POST['idHoja']) {
    /* GUARDAMOS EL IDHOJA RECIBIDOS POR POST EN UNA VARIABLE */
    $idHoja = $_POST['idHoja'];

    /* RECIBIMOS LOS DATOS POR MÉTODO POST */
    $nomArchivo = $_POST['nomArchivo'];
    $tipoArchivo = $_POST['tipoArchivo'];
    $archivoAD = $_FILES['archivoAD'];

    /* VALIDAMOS QUE LAS VARIABLES TENGAN CONTENIDO */
    if ($nomArchivo == '' || $nomArchivo == ' ' || empty($nomArchivo) || $tipoArchivo == '' || $tipoArchivo == ' ' || empty($tipoArchivo) || empty($archivoAD)) {
        header('Location: ../' . $rutaDireccion . '/archivosAdjuntosEquipos.php?varVacios=true&&idHoja=' . $idHoja);
    } else {

        /* GUARDARMOS EL CONTENIDO DE LAS VARIABLES EN LA BASE DE DATOS */
        $sqlInserta = "INSERT INTO archivos_adjunto_equipo (nomArchivo,tipoArchivo,creadoPor,depaCreadoPor,idEquipo) VALUES ('$nomArchivo', '$tipoArchivo', $idUsuario, $idDepa, $idHoja)";
        $resInserta = mysqli_query($conexion, $sqlInserta);

        $idInsert = mysqli_insert_id($conexion);

        if ($resInserta) {

            if (isset($archivoAD)) {

                //CREA CARPETA 
                $carpetaArchivos = '../sdo-archivos-adjuntosEquipo/' . $idHoja . '/';
              
                if (!file_exists($carpetaArchivos)) {
                    mkdir($carpetaArchivos, 0777, true);
                }

                //CONTAMOS LA CANTIDAD DE ARCHIVOS
                $cantidad = count($_FILES["archivoAD"]["name"]);

                for($i = 0; $i < $cantidad; $i++){
                    $tmp_name = $_FILES["archivoAD"]["tmp_name"];
                    $name = $_FILES["archivoAD"]["name"];

                    if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                        echo 'sin nombre';
                    }else{

                        $dir = opendir($carpetaArchivos);

                        $ruta = $carpetaArchivos . $name;

                        $separarNombre = explode(".", $name);

                        $extImagen = end($separarNombre);

                        $extImagen = mb_strtolower($extImagen);

                        
                        if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                            if (move_uploaded_file($tmp_name, $ruta)) {

                                $query = "UPDATE archivos_adjunto_equipo SET ruta = $ruta , extImagen = $extImagen WHERE idRegArchivo = $idInsert";
                                $resArchivo = mysqli_query($conexion, $query);

                                if ($resArchivo) {
                                    echo "GOOD SUBIDA";
                                }
                            } else {
                                echo 'No subi';
                                exit;
                            }
                        }
                    }
                }
            }

            header('Location: ../' . $rutaDireccion . '/archivosAdjuntosEquipos.php?exitoCrear=true&&idHoja=' . $idHoja);

        } else {
            header('Location: ../' . $rutaDireccion . '/archivosAdjuntosEquipos.php?noInsert=true&&idHoja=' . $idHoja);
        }
    }
} else {
    header('Location: ../' . $rutaDireccion . '/archivosAdjuntosEquipos.php?noId=true&&idHoja=' . $idHoja);
}
