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

if(isset($_POST['idEquipo'])){
    $idEquipo = $_POST['idEquipo'];
    $nombreArchivo = $_FILES['soliCompra']['name'];
    $tipoArchivo = $_FILES['soliCompra']['type'];
    $tamanoArchivo = $_FILES['soliCompra']['size'];
    $tmpArchivo = $_FILES['soliCompra']['tmp_name'];
    $fecha = date('Y-m-d H:i:s');
    $nomArchivo = $_POST['nomArchivo'];
    $idCategoria = $_POST['idCategoria'];

    $directorio = "../archivos-equipamiento-inge-clinica-solicompra/archivos-adjuntos/nomArchivo-" . $nombreArchivo . "/";

    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $ruta = $directorio . $nombreArchivo;
    if (file_exists($ruta)) {
        header('Location: ../'. $rutaDireccion .'/equipo-solicitudes-compra-inge-clinica.php?errorArchivoExiste=true');
    }else{
        if(move_uploaded_file($tmpArchivo, $directorio . $nombreArchivo)){
            $queryImagen = "INSERT INTO `solicitudes_compra_inge_clinica`(`id`, `fecha`, `nombreCreador`, `rutaArchivo`, `nomArchivo`,idEquipo,nomMostrar,idCategoria) 
            VALUES (null,'$fecha','$nomUsuario','$ruta','$nombreArchivo',$idEquipo,'$nomArchivo',$idCategoria)";
            $resImagen = mysqli_query($conexion, $queryImagen);
            if($resImagen){
                if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                    header('Location: ../'. $rutaDireccion .'/equipo.php?exitoSubirArchivos=true');
                }else{
                    header('Location: ../'. $rutaDireccion .'/equipo-inge-clinica.php?exitoSubirArchivos=true');
                }
            }else{
                if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                    header('Location: ../'. $rutaDireccion .'/equipo.php?errorSubirArchivos=true');
                }else{
                    header('Location: ../'. $rutaDireccion .'/equipo-inge-clinica.php?errorSubirArchivos=true');
                }
            }
        }else{
            echo "Ha ocurrido un error al subir el archivo.";
        }
    }
}
