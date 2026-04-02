<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Load Composer's autoloader

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

// Include the main TCPDF library (search for installation path).
require_once('../sdo-pdf/TCPDF/tcpdf.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

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
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
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

$idSoliCompra = $_POST['id'];

// TRAE LOS DATOS DE LAS SOLICITUDES DE COMPRA

$sqlSelect = "SELECT * FROM solicitudes_compra_sdo_cc WHERE idSoliCompra = $idSoliCompra";
$resSelect = mysqli_query($conexion, $sqlSelect);

while ($rowSelect = $resSelect->fetch_assoc()) {
    $datos = [];
    $datos['numSoliCompra'] = $rowSelect['numSoliCompra'];
    $datos['folioSoliCompra'] = $rowSelect['folioSoliCompra'];
    $datos['fechaSoliCompra'] = $rowSelect['fechaSoliCompra'];
    $datos['personalSoliCompra'] = $rowSelect['personalSoliCompra'];
    $datos['unidadSoliCompra'] = $rowSelect['unidadSoliCompra'];
    $datos['emailSoliCompra'] = $rowSelect['emailSoliCompra'];
    $datos['argumentosSoliCompra'] = $rowSelect['argumentosSoliCompra'];
    $datos['adjuntaCotizacion'] = $rowSelect['adjuntaCotizacion'];
    $datos['estadoSoliCompra'] = $rowSelect['estadoSoliCompra'];

    $numSoliCompra = $datos['numSoliCompra'];
    $folioSoliCompra = $datos['folioSoliCompra'];
    $fechaSoliCompra = $datos['fechaSoliCompra'];
    $personalSoliCompra = $datos['personalSoliCompra'];
    $unidadSoliCompra = $datos['unidadSoliCompra'];
    $email = $datos['emailSoliCompra'];
    $argumentosSoliCompra = $datos['argumentosSoliCompra'];
    $adjuntaCotizacion = $datos['adjuntaCotizacion'];
    $estadoSoliCompra = $datos['estadoSoliCompra'];
}

$fechaActual = date("d-m-Y");
$fechaSoliCompra = strtotime($fechaSoliCompra);
$fechaSoliCompra = fechaEspañol($fechaSoliCompra);

$GLOBALS['folio'] = $folioSoliCompra;

//MATERIALES DE SOLICITUD DE COMPRA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id'])) {

        $opcion = $_POST['opcion'];

        if ($opcion == 'recibidoPanol') {

            $nuevoEstado = 'Recibido por pañol';
            $observacion = "Orden de compra recibida por pañol";
            $fecha = $_POST['fechaEntrega'];

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo_cc (fechaActualizacion,observacion, tipoActualizacion, idSoliCompra) VALUES ('$fecha','$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

            if ($resInsertSeguimiento) {
                $sqlUpdateSC = "UPDATE solicitudes_compra_sdo_cc SET estadoSoliCompra = '$nuevoEstado' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
            } else {
                echo "error insertar en la tabla de seguimiento";
            }

            if ($resUpdateSC) {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?exitoRecibido=true');
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorRecibido=true');
            }
        } else if ($opcion == 'ordenesCompra') {

            $num = 0;
            $nuevoArrayCantidad = [];

            $ordenCompra = $_POST['ordenCompra'];
            $ordenCompra = mb_strtoupper($ordenCompra);

            $checks = $_POST['checks'];
            $cantidadLlegoArray = $_POST['cantidadLlego'];

            $cantidad = count($cantidadLlegoArray);

            for ($i = 0; $i < count($cantidadLlegoArray); $i++) {

                if ($cantidadLlegoArray[$i] > 0) {
                    $valor = $cantidadLlegoArray[$i];
                    array_push($nuevoArrayCantidad, $valor);
                }
            }

            foreach ($cantidadLlegoArray as $valor) {
                echo $valor . "<br>";
            }

            echo "<br><br>";

            foreach ($checks as $valor) {
                echo $valor . "<br>";
            }

            if (!empty($_FILES['archivoAdjunto'])) {
                $directorio = "../sdo-archivo-orden-de-compra-panol-cc/id-$idSoliCompra".'/';
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                $totalArchivos = count($_FILES['archivoAdjunto']['name']);

                // Recorre todos los archivos subidos
                for ($i = 0; $i < $totalArchivos; $i++) {
                    $nombreArchivo = $_FILES['archivoAdjunto']['name'][$i];
                    $rutaTemporal = $_FILES['archivoAdjunto']['tmp_name'][$i];
                    $rutaDestino = $directorio . $nombreArchivo;

                    // Obtener la extensión del archivo
                    $separarNombre = explode(".", $nombreArchivo);
                    $extImagen = end($separarNombre);
                    $extImagen = mb_strtolower($extImagen);

                    if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                            $sqlInsertArchivo = "INSERT INTO `archivo-orden-de-compra-panol-cc`(`id`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idSoliCompra`) 
                            VALUES (NULL,'$nombreArchivo','$rutaDestino','$extImagen',$idSoliCompra)";
                            $resInsertArchivo = mysqli_query($conexion,$sqlInsertArchivo);
                            if(!$resInsertArchivo){
                                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorSubirArchivo=true');
                            }
                        }else{
                            header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorSubirArchivoFile=true');
                        }
                    }else{
                        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorSubirArchivoFormato=true');
                    }
                    // Mueve el archivo de la ubicación temporal a la definitiva
                }
            }

            $nuevoEstado = 'Actualizacion Ordenes de Compra';
            $observacion = $ordenCompra;

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo_cc (observacion, tipoActualizacion, idSoliCompra) 
            VALUES ('$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);


            $todoCorrecto = true; // Variable para controlar si todas las consultas se ejecutan correctamente

            foreach ($checks as $valor) {
                $sqlSelect = "SELECT * FROM material_solicompra_sdo_cc WHERE idArticuloSC = $valor";
                $resSelect = mysqli_query($conexion, $sqlSelect);

                if (!$resSelect) {
                    $todoCorrecto = false; // Si la consulta falla, marcamos como incorrecto
                    break; // Terminamos el bucle si ocurre un error
                }

                while ($row = $resSelect->fetch_assoc()) {
                    $datos = [];
                    $datos['entregado'] = $row['entregado'];
                    $datos['cantidadLlego'] = $row['cantidadLlego'];
                    $datos['cantidadFaltante'] = $row['cantidadFaltante'];
                    $datos['cantidadArticuloSC'] = $row['cantidadArticuloSC'];
                    $entregado = $datos['entregado'];
                    $cantidadLlego = $datos['cantidadLlego'];
                    $cantidadFaltante = $datos['cantidadFaltante'];
                    $cantidadArticuloSC = $datos['cantidadArticuloSC'];
                }

                if ($entregado == 1) {
                    $cantidadLlego = intval($nuevoArrayCantidad[$num]) + $cantidadLlego;
                    $cantidadFaltante = $cantidadFaltante - $nuevoArrayCantidad[$num];

                    $sql = "UPDATE material_solicompra_sdo_cc SET mostrarEnTabla = 1, cantidadLlego = '$cantidadLlego', cantidadLlegoActual = $nuevoArrayCantidad[$num], cantidadFaltante = $cantidadFaltante WHERE idArticuloSC = $valor";
                } else {
                    $cantidadFaltante = $cantidadArticuloSC - $nuevoArrayCantidad[$num];

                    $sql = "UPDATE material_solicompra_sdo_cc SET entregado = 1, mostrarEnTabla = 1, cantidadLlegoActual = $nuevoArrayCantidad[$num], cantidadLlego = '$nuevoArrayCantidad[$num]', cantidadFaltante = $cantidadFaltante WHERE idArticuloSC = $valor";
                }

                $res = mysqli_query($conexion, $sql);

                if (!$res) {
                    $todoCorrecto = false; // Si alguna consulta de actualización falla, marcamos como incorrecto
                    break; // Terminamos el bucle si ocurre un error
                }

                $num++;
            }

            if ($todoCorrecto) {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?exitoOrdenCompra=true');
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorOrdenCompra=true');
            }

        }else if($opcion == "valorOrdenCompra"){

            $ordenCompra = $_POST['ordenCompra'];
            $fechaEnvio = $_POST['fechaEnvio'];
            $valorOrdenCompra = $_POST['valorOrdenCompra'];

            //INSERTAMOS EN EL DETALLE ORDENES DE COMPRA
            $sql = "INSERT INTO detalle_ordencompra_cc (codOrdenCompra, fechaEnvioProv, valorTotal, unidadSoliCompra,idSoliCompra) VALUES ('$ordenCompra', '$fechaEnvio', '$valorOrdenCompra', '$unidadSoliCompra' ,$idSoliCompra)";
            $res = mysqli_query($conexion, $sql);

            if($res){
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?exitoOrdenCompra=true');
            }else{
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?errorOrdenCompra=true');
            }

        }else{
            header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?error=true');
        }
    }else{
        header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?error=true');
    }
} else {
    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento_nuevo.php?idNoValido=true');
}
