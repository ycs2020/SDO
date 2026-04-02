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


$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idHoja = $_POST['idHoja'];
    $archivoOC = $_FILES['archivoOC'];
    $carpeta = $_POST['carpeta'];
    $isEquipo = $_POST['isEquipo'];

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($isEquipo == ''){
        $idHoja = 0;
    }

    if (!empty($idHoja) || !empty($carpeta)) {

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

        if (!empty($nomMantenimiento) && !empty($tipoMantencion)  && !empty($costoTotal)) {

            //SQL INSERT EN TABLA DE GASTOS DE EQUIPO
            $sql = "INSERT INTO gastoEquipos2 (mesGasto, fechaGasto,nomMantenimiento,idHojaTecnica,tipoMantencion,solicitudCompra,codOrdenCompra,costoTotal,encargadoMant,idEquipo, carpeta) VALUES ('$mesGasto','$fechaGasto','$nomMantenimiento', $idHojaTecnica, '$tipoMantencion', $ordenCompra, '$codOrdenCompra', $costoTotal, '$encargadoMant',$idHoja, '$carpeta')";
            $res = mysqli_query($conexion, $sql);

            if ($res) {
                //INSERT EN LA TABLA DE ARCHIVOS
                $idGasto = mysqli_insert_id($conexion);

                if (isset($_FILES['archivoOC'])) {

                    $directorio = "../sdo-archivos-gastoEquipos2/EQUIPO-" . $idHoja . "/Gasto-" . $idGasto . "/";
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

                            $directorio = "../sdo-archivos-gastoEquipos2/EQUIPO-" . $idHoja . "/Gasto-" . $idGasto . "/";

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

                                    $query = "INSERT INTO archivos_gasto_equipos2( nomArchivoGE,rutaArchivoGE,extArchivo, idGasto)";
                                    $query .=  "VALUES('$name', '$ruta','$extImagen', $idGasto )";
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


                if($isEquipo == ''){
                    header('Location: ../sdo-administrador/gastoEquipo-carpeta.php?exitoCrearGasto=true&&id=' . $carpeta);
                }else{
                    header('Location: ../sdo-administrador/gastoEquipo-cc.php?exitoCrearGasto=true&&id=' . $idHoja);

                }

            } else {
                if($isEquipo !== ''){
                    header('Location: ../sdo-administrador/gastoEquipo-cc.php?noInsertGasto=true&&id=' . $idHoja);
                }else{
                    header('Location: ../sdo-administrador/gastoEquipo-carpeta.php?noInsertGasto=true&&id=' . $carpeta);
                }
            }
        } else {
            header('Location: ../sdo-administrador/gastoEquipo-cc.php?vaciosEnGasto=true&&id=' . $idHoja);
        }
    } else {
        header('Location: ../sdo-administrador/gastoEquipo-cc.php?noIdEnGasto=true&&id=' . $idHoja);
    }
}
