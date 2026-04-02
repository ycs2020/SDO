<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

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
if(isset($_POST['folioTGSubir']) || isset($_FILES['archivosFuncResponsable']) || isset($_POST['idTGSubir'])) {
    $folioTG = $_POST['folioTGSubir'];
    $idTG = $_POST['idTGSubir'];

    if (empty($_FILES['archivosFuncResponsable'])) {
        header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorSubirArchivosVacio=true');
    }else{
        $directorio = "../sdo-archivo-ot-asignado/$folioTG".'/';
       if (!file_exists($directorio)) {
           mkdir($directorio, 0777, true);
       }
        $totalArchivos = count($_FILES['archivosFuncResponsable']['name']);
        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreArchivo = $_FILES['archivosFuncResponsable']['name'][$i];
            $rutaTemporal = $_FILES['archivosFuncResponsable']['tmp_name'][$i];
            $rutaDestino = $directorio . $nombreArchivo;

            $separarNombre = explode(".", $nombreArchivo);
            $extImagen = end($separarNombre);
            $extImagen = mb_strtolower($extImagen);

            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                $sqlInsert = "INSERT INTO `Archivos_Trabajo_General_Tecnico`(`idArchivoTecnico`, `nomArchivo`, `rutaArchivo`, `extArchivo`, `idTG`) 
                VALUES (NULL,'$nombreArchivo','$rutaDireccion','$extImagen',$idTG)";
                $res = mysqli_query($conexion,$sqlInsert);
                if($res){
                    header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?exitoSubirArchivos=true');
                }else{
                    header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorSubirArchivos=true');
                }
            }else{
                header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorSubirArchivos1=true');
            }
        }
    }

}else{
    header('Location: ../' . $rutaDireccion . '/detalle_ordenes_trabajo.php?errorSubirArchivosisset=true');
}

