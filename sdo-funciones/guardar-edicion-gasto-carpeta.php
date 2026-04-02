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
        $tipoMantencion = $_POST['tipoMantencion'];
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
            //SQL INSERT EN TABLA DE GASTOS DE EQUIPO
            $sql = "UPDATE gasto_equipo_carpeta SET mesGasto = '$mesGasto', fechaGasto = '$fechaGasto', nomMantenimiento = '$nomMantenimiento' , idHojaTecnica = $idHojaTecnica, tipoMantencion = '$tipoMantencion', solicitudCompra = $ordenCompra ,codOrdenCompra = '$codOrdenCompra',costoTotal = $costoTotal ,encargadoMant = '$encargadoMant' WHERE idGasto = $idGasto";
            $res = mysqli_query($conexion, $sql);

            if ($res) {
                //INSERT EN LA TABLA DE ARCHIVOS

                if (isset($_FILES['archivoOC'])) {
                    $directorio = "../sdo-administrador/archivos-equipamiento/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                }

                if (isset($_FILES["archivoOC"])) {

                    $tot = count($_FILES["archivoOC"]["name"]);
                    echo $tot;
                    for ($i = 0; $i < $tot; $i++) {
                        $tmp_name = $_FILES["archivoOC"]["tmp_name"];
                        $name = $_FILES["archivoOC"]["name"];
                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo 'sin nombre';
                        } else {

                            $directorio = "../sdo-administrador/archivos-equipamiento/carpeta-". $idCategoria ."/gasto-" . $idGasto ;
                          
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
                                $elimina = "DELETE FROM archivos_gasto_carpeta WHERE idGasto = $idGasto";
                                $resElimina = mysqli_query($conexion, $elimina);
                            }else{
                                echo 'error al entrar al directorio';
                            }

                            $directorio = "../sdo-administrador/archivos-equipamiento/carpeta-". $idCategoria ."/gasto-" . $idGasto . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777, true);
                            }

                            $dir = opendir($directorio);
                            $ruta = $directorio . $name;
                            $separarNombre = explode(".", $name);
                            $extImagen = end($separarNombre);
                            $extImagen = mb_strtolower($extImagen);
                            if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                                if (move_uploaded_file($tmp_name, $ruta)) {

                                    $query = "INSERT INTO archivos_gasto_carpeta( nomArchivoGE,rutaArchivoGE,extArchivo, idGasto)";
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



                if(!empty($idCategoria)) {
                    header('Location: ../' . $rutaDirrecion . '/carpeta-gastos.php?exitoEditarGasto=true');
                }

                if(!empty($idEquipo)){
                    header('Location: ../' . $rutaDirrecion . '/equipo-gastos.php?exitoEditarGasto=true');
                }

            } else {

                if($idEquipo == ''){
                    header('Location: ../'.$rutaDirrecion.'/carpeta-gastos.php?errorEditarGasto=true');
                }else{
                    header('Location: ../'.$rutaDirrecion.'/equipo-gastos.php?errorEditarGasto=true');
                }
            }
        } else {

            if($idEquipo == ''){
                header('Location: ../'.$rutaDirrecion.'/carpeta-gastos.php?vaciosEnGasto=true');
            }else{
                header('Location: ../'.$rutaDirrecion.'/equipo-gastos.php?vaciosEnGasto=true');
            }
        }
    } else {
        if($idEquipo == ''){
            header('Location: ../'.$rutaDirrecion.'/carpeta-gastos.php?noIdCategoria=true');
        }else{
            header('Location: ../'.$rutaDirrecion.'/equipo-gastos.php?noIdCategoria=true');
        }
    }
}
