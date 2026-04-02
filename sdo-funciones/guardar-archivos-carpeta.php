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

if($_POST['nomArchivo']){

    $nomArchivo = $_POST['nomArchivo'];
    $tipoArchivoAdjuntar = $_POST['tipoArchivo'];
    $archivoAdjunto = $_FILES['archivoAdjunto'];
    $nomCarpeta = $_POST['nomCarpeta'];
    $idCarpeta = $_POST['idCarpeta'];

    $tipoMantenimiento = $_POST['tipoMantenimiento'];

//   $equipoHijo = $_POST['equipoHijo'];
//
//    if($equipoHijo == ''){
//        $equipoHijo = NULL;
//    }
    $idEquipo = $_POST['idEquipo'];


    $idEquipamiento = $_POST['idEquipamiento'];
    $idEquipamientoPadre = $_POST['idEquipamientoPadre'];
    if($idEquipamiento > 0){
        if($idEquipamientoPadre > 0){
            $_SESSION['idEquipo'] = $idEquipamientoPadre;
            $idEquipo = $idEquipamiento;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipamientoPadre);
        }else{
            $_SESSION['idEquipo'] = $idEquipamiento;
            $idEquipo = $idEquipamiento;
            $obtenerTipoMenu = obtenerTipoMenu($idEquipamiento);

        }
        if($obtenerTipoMenu == 'nuevo'){
            $sitioWeb = 'equipo_menuNuevo.php';
        }else{
            $sitioWeb = 'equipo_menuAntiguo.php';
        }
        $headerTextCorrecto = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?exitoForzarVariablesEquipo=true';
        $headerTextIncorrecto = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?errorForzarVariablesEquipo=true';
        $headerSinObservacion = 'Location: ../' . $rutaDireccion . '/'.$sitioWeb.'?errorForzarEquipoSinObservacion=true';
    }

    if($idEquipo == ''){
        $idEquipo = 0;
    }
//
//    echo 'Equipamiento = '.$idEquipamiento.'<br><br>';
//    echo 'Equipamiento Padre = '.$idEquipamientoPadre.'<br><br>';
//    exit();

    $nomCarpeta = str_replace(' ', '', $nomCarpeta);
    $nomCarpeta = trim($nomCarpeta);
    $nomCarpeta = mb_strtolower($nomCarpeta);

    $directorio = "../sdo-administrador/archivos-equipamiento/archivos-adjuntos/carpeta-" . $nomCarpeta . "/";
//    echo $directorio;
//
//    exit;

    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    if(empty($nomArchivo) || empty($tipoArchivo) || empty($archivoAdjunto)){

        if ($tipoArchivo == 0 || $tipoArchivo == 'Otro' || $tipoArchivo == 'otro') if (isset($_POST['otroTipoArchivo'])) {

            $otroTipoArchivo = trim($_POST['otroTipoArchivo']);

            if (!empty($otroTipoArchivo)) {
                $otroTipoArchivo = str_replace("'", "\'", $otroTipoArchivo);

                $buscarTipoArchivo = buscarTipoArchivo($otroTipoArchivo);

                if ($buscarTipoArchivo == 0) {

                    $queryOtroServicio = "INSERT INTO tiposarchivos(id ,nomTipo) VALUES (NULL,'" . $otroTipoArchivo . "')";
                    $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                    $tipoArchivoAdjuntar = $otroTipoArchivo;

                } else {
                    $tipoArchivoAdjuntar = $buscarTipoArchivo;
                }
            } else {
            }
        } else {
        }

        if(isset($_FILES['archivoAdjunto'])){
            $nombreArchivo = $_FILES['archivoAdjunto']['name'];

            $tipoArchivo = $_FILES['archivoAdjunto']['type'];
            $tamanoArchivo = $_FILES['archivoAdjunto']['size'];
            $tmpArchivo = $_FILES['archivoAdjunto']['tmp_name'];

            // Directorio de destino donde se guardarán los archivos subidos
            $directorio = "../sdo-administrador/archivos-equipamiento/archivos-adjuntos/carpeta-" . $nomCarpeta . "/";

            $ruta = $directorio . $nombreArchivo;


            if (file_exists($ruta)) {
                header('Location: ../'. $rutaDireccion .'/equipo-archivos.php?errorArchivoExiste=true');
            } else {
                // Mover el archivo del directorio temporal al directorio de destino
                if(move_uploaded_file($tmpArchivo, $directorio . $nombreArchivo)){
                    echo "El archivo se ha subido correctamente.";

                    $queryImagen = "INSERT INTO  archivos_equipamiento_hbqp(id ,nomArchivo,rutaArchivo,tipoArchivo,idEquipo,idCategoria) VALUES (NULL,'" . $nomArchivo . "','" . $ruta . "','" . $tipoArchivoAdjuntar . "',$idEquipo,'" . $idCarpeta . "')";
                    $resImagen = mysqli_query($conexion, $queryImagen);
                }else{
                    echo "Ha ocurrido un error al subir el archivo.";
                }
            }


        }

        if($sitioWeb == '' || empty($sitioWeb)){
            $sitioWeb = 'equipo_menuAntiguo.php';
        }

        if($resImagen &&  mysqli_affected_rows($conexion) > 0 ){
            if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                header('Location: ../'. $rutaDireccion .'/equipo.php?exitoSubirArchivos=true');
            }else if($idDepartamento == 6){
                header('Location: ../'. $rutaDireccion .'/equipo-inge-clinica.php?exitoSubirArchivos=true');
            }else if($idEquipo == 0){
                header('Location: ../'. $rutaDireccion .'/carpeta-archivos.php?exitoSubirArchivos=true');
            }else{
                header('Location: ../'. $rutaDireccion .'/'.$sitioWeb.'?exitoSubirArchivos=true');
            }
        }else{
            if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico"){
                header('Location: ../'. $rutaDireccion .'/equipo.php?errorSubirArchivos=true');
            }else if($idDepartamento == 6){
                header('Location: ../'. $rutaDireccion .'/equipo-inge-clinica.php?errorSubirArchivos=true');
            }else if($idEquipo == 0){
                header('Location: ../'. $rutaDireccion .'/carpeta-archivos.php?errorSubirArchivos=true');
            }else{
                header('Location: ../'. $rutaDireccion .'/'.$sitioWeb.'?errorSubirArchivos=true');
            }
        }
    }else{
        if($idDepartamento == 6 && $rolUsuario2 == "jefe tecnico") {
            header('Location: ../'.$rutaDireccion.'/equipo.php?vaciosCampos=true');
        }else if($idDepartamento == 6){
            header('Location: ../'. $rutaDireccion .'/equipo-inge-clinica.php?vaciosCampos=true');
        }else if($idEquipo == 0){
            header('Location: ../'.$rutaDireccion.'/carpeta-archivos.php?vaciosCampos=true');
        }else{
            header('Location: ../'.$rutaDireccion.'/'.$sitioWeb.'?vaciosCampos=true');
        }
    }
}