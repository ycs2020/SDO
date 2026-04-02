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
} else if($rolUsuario2 == "oficina tecnica"){
    $rutaDirrecion = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCategoria = $_POST['idCategoria'];
    $archivoOC = $_FILES['archivoOC'];

    $idEquipo = $_POST['idEquipo'];

    if($idEquipo == ''){
        $idEquipo = NULL;
    }

    if (!empty($idCategoria)) {

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

//        var_dump($nomMantenimiento) . "<br>";
//        var_dump($idHojaTecnica). "<br>";
//        var_dump($tipoMantencion). "<br>";
//        var_dump($ordenCompra). "<br>";
//        var_dump($codOrdenCompra). "<br>";
//        var_dump($costoTotal). "<br>";
//        var_dump($mesGasto). "<br>";
//        var_dump($encargadoMant). "<br>";
//        var_dump($fechaGasto). "<br>";

        echo $nomMantenimiento;
        echo $tipoMantencion;
        echo $costoTotal;

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
        if (empty($idEquipo)){
            $idEquipo = 'NULL';

        }




        if (!empty($nomMantenimiento) && !empty($tipoMantencion) ) {
            //SQL INSERT EN TABLA DE GASTOS DE EQUIPO
            $sql = "INSERT INTO gasto_equipo_carpeta (mesGasto, fechaGasto,nomMantenimiento,idHojaTecnica,tipoMantencion,solicitudCompra,codOrdenCompra,costoTotal,encargadoMant,idEquipo, idCategoria) 
            VALUES ('$mesGasto','$fechaGasto','$nomMantenimiento', $idHojaTecnica, '$tipoMantencion', $ordenCompra, '$codOrdenCompra', $costoTotal, '$encargadoMant', $idEquipo , $idCategoria)";
            $res = mysqli_query($conexion, $sql);

            $idGasto = mysqli_insert_id($conexion);

            if($idHojaTecnica !== ''){
                $sqlActualiza = "UPDATE ordenes_trabajos_general SET idCategoria = $idCategoria WHERE idOrden = $idHojaTecnica";
                $resActualiza = mysqli_query($conexion, $sqlActualiza);
            }

            if($ordenCompra !== ''){
                $sqlSoliCompra = "UPDATE solicitudes_compra_sdo SET idCategoria = $idCategoria WHERE idSoliCompra  = $ordenCompra";
                $resSoliCompra = mysqli_query($conexion, $sqlSoliCompra);
            }
            if($idEquipo !== ''){
                $sqlSoliCompraEquipo = "UPDATE solicitudes_compra_sdo SET idEquipo = $idEquipo WHERE idSoliCompra = $ordenCompra";
                $resSoliCompraEquipo = mysqli_query($conexion,$sqlSoliCompraEquipo);
            }
    

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
                                if (file_exists($ruta)) {
                                    header('Location: ../'. $rutaDirrecion .'/equipo-gastos.php?errorArchivoExiste=true');
                                }else{
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
                }

                if($idEquipo == ''){
                    header('Location: ../'.$rutaDirrecion.'/carpeta-gastos.php?exitoCrearGasto=true');
                }else{
                    header('Location: ../'.$rutaDirrecion.'/equipo-gastos.php?exitoCrearGasto=true');
                }
            } else {

                if($idEquipo == ''){
                    header('Location: ../'.$rutaDirrecion.'/carpeta-gastos.php?errorCrearGasto=true');
                }else{
                    header('Location: ../'.$rutaDirrecion.'/equipo-gastos.php?errorCrearGasto=true');
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
