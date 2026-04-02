<?php
include 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda"){
    $rutaDireccion = "sdo-mesa-ayuda";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if($_SERVER['REQUEST_METHOD'] == true){

    $id = $_POST['id'];
    $direccion = $_POST['direccion'];
    $nomArchivo = $_POST['nomArchivo'];
    $tipoArchivo = $_POST['tipoArchivo'];
    $archivoAdjunto = $_FILES['archivoAdjunto'];
    $nomCarpeta = $_POST['nomCarpeta'];

    $nomCarpeta = str_replace(' ', '', $nomCarpeta);
    $nomCarpeta = mb_strtolower($nomCarpeta);

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }


    if(!empty($id)){

        if ($tipoArchivo == 0 || $tipoArchivo == 'Otro' || $tipoArchivo == 'otro') if (isset($_POST['otroTipoArchivo'])) {

            $otroTipoArchivo = trim($_POST['otroTipoArchivo']);

            if (!empty($otroTipoArchivo)) {
                $otroTipoArchivo = str_replace("'", "\'", $otroTipoArchivo);

                $buscarTipoArchivo = buscarTipoArchivo($otroTipoArchivo);

                if ($buscarTipoArchivo == 0) {

                    $queryOtroServicio = "INSERT INTO tiposarchivos(id ,nomTipo) VALUES (NULL,'" . $otroTipoArchivo . "')";
                    $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                    $tipoArchivo = $otroTipoArchivo;

                } else {
                    $tipoArchivo = $buscarTipoArchivo;
                }
            } else {
            }
        } else {
        }

        $sql = "UPDATE archivos_equipamiento_hbqp  SET nomArchivo = '$nomArchivo', tipoArchivo = '$tipoArchivo' WHERE id = $id";
        $res = mysqli_query($conexion, $sql);

        if(isset($_FILES['archivoAdjunto'])){

            $rutaArchivo = $_POST['rutaArchivo'];

            $codigoAleatorio = generate_string($permitted_chars);


            if(file_exists($rutaArchivo)) {
                if (unlink($rutaArchivo)) {
                    $queryImagen = "DELETE FROM archivos_equipamiento_hbqp WHERE rutaArchivo = $rutaArchivo ";
                    $resImagen = mysqli_query($conexion, $queryImagen);
                } else {
                }
            } else {
            }

            $directorio = "../sdo-administrador/archivos-equipamiento/archivos-adjuntos/carpeta-" . $nomCarpeta . "/";

            $nombreArchivo = $_FILES['archivoAdjunto']['name'];
            $tipoArchivo = $_FILES['archivoAdjunto']['type'];
            $tamanoArchivo = $_FILES['archivoAdjunto']['size'];
            $tmpArchivo = $_FILES['archivoAdjunto']['tmp_name'];

            // Directorio  destino donde se guardarán los archivos subidos

            $ruta = $directorio . $codigoAleatorio;

            // Mover el archivo del directorio temporal al directorio de destino
            if(move_uploaded_file($tmpArchivo, $directorio . $codigoAleatorio)){
                echo "El archivo se ha subido correctamente.";

                $queryImagen = "INSERT INTO  archivos_equipamiento_hbqp(id ,nomArchivo,rutaArchivo,tipoArchivo,idEquipo,idCategoria) VALUES (NULL,'" . $nomArchivo . "','" . $ruta . "','" . $tipoArchivoAdjuntar . "',$idEquipo,'" . $idCarpeta . "')";
                $resImagen = mysqli_query($conexion, $queryImagen);
            }else{
                echo "Ha ocurrido un error al subir el archivo.";
            }
        }

        if($res){
            if($direccion == 'carpeta'){
                header('Location: ../'. $rutaDireccion.'/carpeta-archivos.php?exitoEditar=true');
            }else{
                header('Location: ../'. $rutaDireccion.'/equipo-archivos.php?exitoEditar=true');
            }
        }else{
            if($direccion == 'carpeta'){
                header('Location: ../'. $rutaDireccion.'/carpeta-archivos.php?errorEditar=true');
            }else{
                header('Location: ../'. $rutaDireccion.'/equipo-archivos.php?errorEditar=true');
            }
        }
    }
}