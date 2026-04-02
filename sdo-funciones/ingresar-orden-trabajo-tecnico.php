<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $rutaDireccion  = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDireccion  = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDireccion  = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDireccion  = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDireccion  = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDireccion  = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_POST['idTG'], $_POST['fechaSubidaTecnico'], $_POST['estadoAvance'])){
    $idTG = $_POST['idTG'];
    $fechaSubidaTecnico = $_POST['fechaSubidaTecnico'];
    $estadoAvance = $_POST['estadoAvance'];
    $avanceTG = trim($_POST['avanceTG']);
    $observacionesTG = trim($_POST['observacionesTG']);


    $tecnicoResponsable = $_POST['tecnicoResponsable'];

    $ordenArchivoPersonal = $_FILES['ordenArchivoPersonal'];

    if(empty($avanceTG) || $avanceTG == ''){
        $avanceTG = "No se registro un avance";
    }

    if(empty($observacionesTG) || $observacionesTG == ''){
        $observacionesTG = "No se registro una observacion";
    }



    /*$sql = "UPDATE trabajos_general SET fechaSubidaTecnico = '$fechaSubidaTecnico', avanceTGT = '$avanceTG', observacionTGT = '$observacionesTG', estadoTG = '$estadoAvance' WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);*/
/*
    $sql = "INSERT INTO avances_trabajo_general (idAvance,nomCreador,detalleObs,estadoAvance,fechaSubida)
    VALUES ()";
*/



    if (!empty($_FILES['archivoAdjunto']["name"])) {
        $directorio = "../sdo-administrador/archivos-trabajo-general-tecnicos/carpeta-". $idTG ."/tecnico-" . $tecnicoResponsable . "/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

    }

    if (!empty($_FILES["ordenArchivoPersonal"]["name"])) {
        $tot = count($_FILES["ordenArchivoPersonal"]["name"]);
        for($i = 0; $i < $tot; $i++){
            $tmp_name = $_FILES["ordenArchivoPersonal"]["tmp_name"];
            $name = $_FILES["ordenArchivoPersonal"]["name"];
            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?archivoSinNombre=true');
            }else{
                $directorio = "../sdo-administrador/archivos-trabajo-general-tecnicos/carpeta-". $idTG ."/tecnico-" . $tecnicoResponsable . "/";    ;
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                $dir = opendir($directorio);
                $ruta = $directorio . $name;
                $separarNombre = explode(".", $name);
                $extImagen = end($separarNombre);
                $extImagen = mb_strtolower($extImagen);

                if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                    if(file_exists($ruta)){
                        header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
                    }else{
                        if (move_uploaded_file($tmp_name, $ruta)) {
                            $query = "INSERT INTO Archivos_Trabajo_General_Tecnico( nomArchivo,rutaArchivo,extArchivo,fechaSubida,idTG)";
                            $query .=  "VALUES('$name', '$ruta','$extImagen','$fechaSubidaTecnico', $idTG )";

                            $resArchivo = mysqli_query($conexion, $query);

                            if($resArchivo){
                                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?archivoNoHabilitado=true');
                            }else{
                                header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?archivoNoHabilitado=true');
                            }
                        }
                    }
                }else{
                    header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?archivoNoHabilitado=true');
                }
            }
        }
    }else{
        if($res){
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?exito=true');
        }else{
            header('Location: ../' . $rutaDireccion . '/trabajos_general_listado.php?error=true');
        }
    }


}
