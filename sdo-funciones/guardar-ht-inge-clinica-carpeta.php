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

if(isset($_POST['idCategoria'])) {
    $idEquipo = $_POST['idEquipo'];
    $nombreArchivo = $_FILES['ht']['name'];
    $tipoArchivo = $_FILES['ht']['type'];
    $tamanoArchivo = $_FILES['ht']['size'];
    $tmpArchivo = $_FILES['ht']['tmp_name'];
    $fecha = date('Y-m-d H:i:s');
    $nomMostrar = $_POST['nomArchivo'];
    $idCategoria = $_POST['idCategoria'];

    $directorio = "../archivos-equipamiento-inge-clinica-ht/nomArchivo-" . $nombreArchivo . "/";

    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $ruta = $directorio . $nombreArchivo;
    if (file_exists($ruta)) {
        header('Location: ../'. $rutaDireccion .'/equipo-hojas-tecnicas-compra-inge-clinica.php?errorArchivoExiste=true');
    }else{
        if(move_uploaded_file($tmpArchivo, $directorio . $nombreArchivo)){
            $queryImagen = "INSERT INTO `hojas_tecnicas_inge_clinica`(`id`, `fecha`, `nombreCreador`, `rutaArchivo`, `nomArchivo`, `idEquipo`, `nomMostrar`,idCategoria) 
            VALUES (null,'$fecha','$nomUsuario','$ruta','$nombreArchivo',$idEquipo,'$nomMostrar',$idCategoria)";
            $resImagen = mysqli_query($conexion, $queryImagen);
            if($resImagen){
                header('Location: ../'. $rutaDireccion .'/carpeta-equipo-inge-clinica.php?exitoSubirArchivos=true');
            }else{
                header('Location: ../'. $rutaDireccion .'/carpeta-equipo-inge-clinica.php?errorSubirArchivos=true');
            }
        }else{
            echo "Ha ocurrido un error al subir el archivo.";
        }
    }

}else{
    echo 'malo';
}