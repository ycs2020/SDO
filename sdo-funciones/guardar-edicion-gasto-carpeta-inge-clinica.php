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
$rutaDirrecion = "";

if ($rolUsuario2 == "personal") {
    $rutaDirrecion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $rutaDirrecion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $rutaDirrecion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $rutaDirrecion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $rutaDirrecion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $rutaDirrecion = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda"){
    $rutaDirrecion = "sdo-mesa-ayuda";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $idGasto = $_POST['idGasto'];
    $archivoOC = $_FILES['archivoOC'];

    if (!empty($idCategoria) || !empty($idEquipo)) {
        /** GUARDA LOS DATOS ENVIADOS POR MÉTODO POST EN VARIABLES */
        $nomMantenimiento = trim($_POST['nomMantenimiento']);
        $idHojaTecnica = $_POST['idHojaTecnica'];
        $tipoMantencion = $_POST['tipoIntervencion1'];
        $ordenCompra = $_POST['ordenCompra'];
        $codOrdenCompra = $_POST['codOrdenCompra'];
        $costoTotal = $_POST['costoTotal'];
        $mesGasto = $_POST['mesGasto'];
        $encargadoMant = $_POST['encargadoMant'];
        $fechaGasto = $_POST['fechaGasto'];

        $files = $_FILES['archivoOC'];

        $numMes = date("m", strtotime($fechaGasto));

        if ($numMes == '01') {
            $mesGasto =  'Enero';
        } else if ($numMes == '02') {
            $mesGasto =  'Febrero';
        } else if ($numMes == '03') {
            $mesGasto =  'Marzo';
        } else if ($numMes == '04') {
            $mesGasto =  'Abril';
        } else if ($numMes == '05') {
            $mesGasto =  'Mayo';
        } else if ($numMes == '06') {
            $mesGasto =  'Junio';
        } else if ($numMes == '07') {
            $mesGasto =  'Julio';
        } else if ($numMes == '08') {
            $mesGasto =  'Agosto';
        } else if ($numMes == '09') {
            $mesGasto =  'Septiembre';
        } else if ($numMes == '10') {
            $mesGasto =  'Octubre';
        } else if ($numMes == '11') {
            $mesGasto =  'Noviembre';
        } else if ($numMes == '12') {
            $mesGasto =  'Diciembre';
        }

        if (empty($idHojaTecnica)) {
            $idHojaTecnica = 'NULL';
        }
        if (empty($ordenCompra)) {
            $ordenCompra = 'NULL';
        }

        if (!empty($nomMantenimiento) && !empty($tipoMantencion)  ) {
            $sql = "UPDATE `gasto_equipo_carpeta_inge_clinica` SET `mesGasto`='$mesGasto',`fechaGasto`='$fechaGasto',`nombreMantenimiento`='$nomMantenimiento',`tipoIntervencion`='$tipoMantencion',`codigoOrdenCompra`='$codOrdenCompra',`costoTotal`='$costoTotal',`encargadoMant`='$encargadoMant' WHERE id = $idGasto";
            $res = mysqli_query($conexion, $sql);
            if ($res) {
                if (isset($_FILES['archivoOC'])) {
                    $directorio = "../archivos-equipamiento-oc-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                }
                if (isset($_FILES["archivoOC"])) {
                    $tot = count($_FILES["archivoOC"]["name"]);
                    for ($i = 0; $i < $tot; $i++) {
                        $tmp_name = $_FILES["archivoOC"]["tmp_name"];
                        $name = $_FILES["archivoOC"]["name"];
                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo 'sin nombre';
                        }else{
                            $directorio = "../archivos-equipamiento-oc-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";

                            if(is_dir($directorio)){

                                $archivos = glob($directorio . '/*');

                                foreach($archivos as $archivo){
                                    if(is_file($archivo)){
                                        $nombreArchivo = basename($archivo);

                                        if(file_exists($archivo)){
                                            unlink($archivo);
                                        }else{
                                            echo 'no elimina';
                                        }
                                    }
                                }

                                //ELIMINA REGISTRO
                                $elimina = "DELETE FROM gastos_oc_archivo_inge_clinica WHERE idGasto = $idGasto";
                                $resElimina = mysqli_query($conexion, $elimina);
                            }else{
                                echo 'error al entrar al directorio';
                            }

                            $directorio = "../archivos-equipamiento-oc-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }

                            $dir = opendir($directorio);
                            $ruta = $directorio . $name;
                            $separarNombre = explode(".", $name);
                            $extImagen = end($separarNombre);
                            $extImagen = mb_strtolower($extImagen);
                            if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                                if (file_exists($ruta)) {
                                    header('Location: ../'. $rutaDirrecion .'/equipo-gastos-inge-clinica.php?errorArchivoExiste=true');
                                }else{
                                    if (move_uploaded_file($tmp_name, $ruta)) {
                                        $query = "INSERT INTO gastos_oc_archivo_inge_clinica( nombre,ruta,extArchivo, idGasto)";
                                        $query .=  "VALUES('$name', '$ruta','$extImagen', $idGasto )";
                                        $resArchivo = mysqli_query($conexion, $query);
                                        if ($resArchivo) {
                                            echo "GOOD SUBIDA";
                                        }
                                    } else {
                                        echo 'No subi';
                                    }
                                }

                            }


                        }
                    }
                }

                if(isset($_FILES['registrosAc'])){
                    $totHT = count($_FILES["registrosAc"]["name"]);
                    for ($j = 0; $j < $totHT; $j++) {
                        $tmp_nameHT = $_FILES["registrosAc"]["tmp_name"];
                        $nameHT = $_FILES["registrosAc"]["name"];
                        if ($tmp_nameHT == '' || $tmp_nameHT == ' ' || empty($tmp_nameHT)) {
                            echo 'sin nombre';
                        }else{
                            $directorioHT = "../archivos-equipamiento-ht-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";
                            if(is_dir($directorioHT)){

                                $archivosHT = glob($directorioHT . '/*');

                                foreach($archivosHT as $archivoHT){
                                    if(is_file($archivoHT)){
                                        $nombreArchivoHT = basename($archivoHT);

                                        if(file_exists($archivoHT)){
                                            unlink($archivoHT);
                                        }else{
                                            echo 'no elimina';
                                        }
                                    }
                                }

                                //ELIMINA REGISTRO
                                $eliminaHT = "DELETE FROM gastos_ht_archivo_inge_clinica WHERE idGasto = $idGasto";
                                $resEliminaHT = mysqli_query($conexion, $eliminaHT);
                            }else{
                                echo 'error al entrar al directorio';
                            }
                            $directorioHT = "../archivos-equipamiento-ht-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";

                            if (!file_exists($directorioHT)) {
                                mkdir($directorioHT, 0777, true);
                            }

                            $dirHT = opendir($directorioHT);
                            $rutaHT = $directorioHT . $nameHT;
                            $separarNombreHT = explode(".", $nameHT);
                            $extImagenHT = end($separarNombreHT);
                            $extImagenHT = mb_strtolower($extImagenHT);
                            if ($extImagenHT == "docx" || $extImagenHT == "xlsx" || $extImagenHT == "pdf" || $extImagenHT == "jpg" || $extImagenHT == "png" || $extImagenHT == "jpeg" || $extImagenHT == "bmp" || $extImagenHT == "tif" || $extImagenHT == "webp" || $extImagenHT == "svg" || $extImagenHT == "jfif") {
                                if (file_exists($rutaHT)) {
                                    header('Location: ../'. $rutaDirrecion .'/equipo-gastos-inge-clinica.php?errorArchivoExiste=true');
                                }else{
                                    if (move_uploaded_file($tmp_nameHT, $rutaHT)) {
                                        $queryHT = "INSERT INTO gastos_ht_archivo_inge_clinica( nombre,ruta,extArchivo, idGasto)";
                                        $queryHT .=  "VALUES('$nameHT', '$rutaHT','$extImagenHT', $idGasto )";
                                        $resArchivoHT = mysqli_query($conexion, $queryHT);
                                        if ($resArchivoHT) {
                                            echo "GOOD SUBIDA";
                                        }
                                    } else {
                                        echo 'No subi';
                                    }
                                }

                            }
                        }
                    }
                }

                if(isset($_FILES['solicitudCompraAc'])){
                    $totSC = count($_FILES["solicitudCompraAc"]["name"]);
                    for ($h = 0; $h < $totSC; $h++) {
                        $tmp_nameSC = $_FILES["solicitudCompraAc"]["tmp_name"];
                        $nameSC = $_FILES["solicitudCompraAc"]["name"];
                        if ($tmp_nameSC == '' || $tmp_nameSC == ' ' || empty($tmp_nameSC)) {
                            echo 'sin nombre';
                        }else{
                            $directorioSC = "../archivos-equipamiento-sc-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";
                            if(is_dir($directorioSC)){

                                $archivosSC = glob($directorioSC . '/*');

                                foreach($archivosSC as $archivoSC){
                                    if(is_file($archivoSC)){
                                        $nombreArchivoSC = basename($archivoSC);

                                        if(file_exists($archivoSC)){
                                            unlink($archivoSC);
                                        }else{
                                            echo 'no elimina';
                                        }
                                    }
                                }

                                //ELIMINA REGISTRO
                                $eliminaSC = "DELETE FROM gastos_sc_archivo_inge_clinica WHERE idGasto = $idGasto";
                                $resEliminaSC = mysqli_query($conexion, $eliminaSC);
                            }else{
                                echo 'error al entrar al directorio';
                            }
                            $directorioSC = "../archivos-equipamiento-sc-inge-clinica/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";

                            if (!file_exists($directorioSC)) {
                                mkdir($directorioSC, 0777, true);
                            }

                            $dirSC = opendir($directorioSC);
                            $rutaSC = $directorioSC . $nameSC;
                            $separarNombreSC = explode(".", $nameSC);
                            $extImagenSC = end($separarNombreSC);
                            $extImagenSC = mb_strtolower($extImagenSC);
                            if ($extImagenSC == "docx" || $extImagenSC == "xlsx" || $extImagenSC == "pdf" || $extImagenSC == "jpg" || $extImagenSC == "png" || $extImagenSC == "jpeg" || $extImagenSC == "bmp" || $extImagenSC == "tif" || $extImagenSC == "webp" || $extImagenSC == "svg" || $extImagenSC == "jfif") {
                                if (file_exists($rutaSC)) {
                                    header('Location: ../'. $rutaDirrecion .'/equipo-gastos-inge-clinica.php?errorArchivoExiste=true');
                                }else{
                                    if (move_uploaded_file($tmp_nameSC, $rutaSC)) {
                                        $querySC = "INSERT INTO gastos_sc_archivo_inge_clinica( nombre,ruta,extArchivo, idGasto)";
                                        $querySC .=  "VALUES('$nameSC', '$rutaSC','$extImagenSC', $idGasto )";
                                        $resArchivoSC = mysqli_query($conexion, $querySC);
                                        if ($resArchivoSC) {
                                            echo "GOOD SUBIDA";
                                        }
                                    } else {
                                        echo 'No subi';
                                    }
                                }

                            }

                        }
                    }
                }

                if(!empty($idCategoria)) {
                    header('Location: ../' . $rutaDirrecion . '/carpeta-gastos-inge-clinica.php?exitoEditarGasto=true');
                }

                if(!empty($idEquipo)){
                    header('Location: ../' . $rutaDirrecion . '/equipo-gastos-inge-clinica.php?exitoEditarGasto=true');
                }

            }else{
                if($idEquipo == ''){
                    header('Location: ../'.$rutaDirrecion.'/carpeta-gastos-inge-clinica.php?errorEditarGasto=true');
                }else{
                    header('Location: ../'.$rutaDirrecion.'/equipo-gastos-inge-clinica.php?errorEditarGasto=true');
                }
            }
        }else{
            if($idEquipo == ''){
                header('Location: ../'.$rutaDirrecion.'/carpeta-gastos-inge-clinica.php?vaciosEnGasto=true');
            }else{
                header('Location: ../'.$rutaDirrecion.'/equipo-gastos-inge-clinica.php?vaciosEnGasto=true');
            }
        }
    }else{
        if($idEquipo == ''){
            header('Location: ../'.$rutaDirrecion.'/carpeta-gastos-inge-clinica.php?noIdCategoria=true');
        }else{
            header('Location: ../'.$rutaDirrecion.'/equipo-gastos-inge-clinica.php?noIdCategoria=true');
        }
    }
}