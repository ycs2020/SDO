<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

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
    $tipoUsuario = 'personal';
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $idDepartamento = 1;
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idHoja = $_POST['idHoja'];

    if ($_POST['idHoja']) {

        if (
            !empty($_POST['nombreEquipo'])  && !empty($_POST['marcaEquipo'])  && !empty($_POST['modeloEquipo']) && !empty($_POST['servicioOrden'])
        ) {
            //OBTIENE LOS DATOS ENVIADOS POR EL FORMULARIO SECCIÓN DATOS TÉCNICOS DEL EQUIPO Y LAS GUARDA EN VARIABLES
            $numeroInventario = trim($_POST['numeroInventario']);
            $fechaRecepcion = $_POST['fechaRecepcion'];
            $nombreEquipo = trim($_POST['nombreEquipo']);
            $valorIva = trim($_POST['valorIva']);
            $marcaEquipo = trim($_POST['marcaEquipo']);
            $idLicitacion = trim($_POST['idLicitacion']);
            $modeloEquipo = trim($_POST['modeloEquipo']);
            $ordenCompra = trim($_POST['ordenCompra']);
            $numeroSerie = trim($_POST['numeroSerie']);
            $fechaCompra = $_POST['fechaCompra'];

            $fechaEnMarcha = $_POST['fechaEnMarcha'];
            $idServicio = trim($_POST['servicioOrden']);

            if ($idServicio == 0 || $idServicio == 'Otro' || $idServicio == 'otro' ||  $idServicio == '') {
                if (isset($_POST['otroServicio'])) {

                    $otroServicio = trim($_POST['otroServicio']);

                    if (!empty($otroServicio)) {
                        $otroServicio = str_replace("'", "\'", $otroServicio);

                        $buscarServicio = buscarServicio($otroServicio);

                        if ($buscarServicio == 0) {

                            $queryOtroServicio = "INSERT INTO servicios(idServicio,nomServicio) VALUES (NULL,'" . $otroServicio . "')";

                            // echo $queryOtroServicio;

                            $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                            $idServicio = mysqli_insert_id($conexion);
                        } else {
                            $idServicio = $buscarServicio;
                        }
                    } else {
                    }
                } else {
                }
            }


            $nombreRecinto = trim($_POST['nombreRecinto']);

            //OBTIENE LOS DATOS ENVIADOS POR EL FORMULARIO SECCIÓN INFORMACIÓN EXTRA Y LAS GUARDA EN VARIABLES
            $claseEquipo = $_POST['claseEquipo'];
            $subClaseEquipo = $_POST['subClaseEquipo'];
            $compleEquipo = $_POST['compleEquipo'];
            $nivelRiesgo = $_POST['nivelRiesgo'];
            $ahnoFabricacion = $_POST['ahnoFabricacion'];
            $vidaUtil = $_POST['vidaUtil'];
            $versionSoftware = $_POST['versionSoftware'];
            $paisOrigen = $_POST['paisOrigen'];
            $terminoGarantia = $_POST['terminoGarantia'];
            $costoAnualMantenimiento = $_POST['costoAnualMantenimiento'];
            $frecuenciaMantenimiento = $_POST['frecuenciaMantenimiento'];

            //OBTIENE LOS ARCHIVOS SUBIDOS POR EL FORMULARIO SECCIÓN INFORMACIÓN TÉCNICA EXISTENTE Y LAS GUARDA EN VARIABLES
            $adjuntosPauta = $_FILES['adjuntosPauta'];
            $adjuntosCertificados = $_FILES['adjuntosCertificados'];
            $adjuntosManuales = $_FILES['adjuntosManuales'];
            $adjuntosOtros = $_FILES['adjuntosOtros'];

            $textoPautas = $_POST['textoPautas'];
            $textoCertificados = $_POST['textoCertificados'];
            $textoManuales = $_POST['textoManuales'];
            $textoOtros = $_POST['textoOtros'];

            $textoAccesorios = $_POST['textoAccesorios'];
            $textoInsumos = $_POST['textoInsumos'];

            //OBTIENE LOS DATOS ENVIADOS POR EL FORMULARIO SECCIÓN INFORMACIÓN SERVICIO TÉCNICO Y LAS GUARDA EN VARIABLES
            $nombreProveedor = trim($_POST['nombreProveedor']);
            $direccionProveedor = trim($_POST['direccionProveedor']);
            $telefonoProveedor = trim($_POST['telefonoProveedor']);
            $emailProveedor = trim($_POST['emailProveedor']);
            $nombreContactoProveedor = trim($_POST['nombreContactoProveedor']);
            $observacionesContactoProveedor = trim($_POST['observacionesContactoProveedor']);

            //ACTUALIZA INFORMACION DEL EQUIPO EN LA BASE DE DATOS
            $sqlUpdateEquipo = "UPDATE hoja_de_vida SET numInventario = '$numeroInventario' , nomEquipo = '$nombreEquipo', fechaRecepcion = '$fechaRecepcion', valorConIva = '${valorIva}', marca = '$marcaEquipo', idLicitacion = '$idLicitacion',  modelo = '$modeloEquipo', ordenCompra = '$ordenCompra', numSerie = '$numeroSerie', fechaCompra = '$fechaCompra', fechaMarcha = '$fechaEnMarcha', idServicio = ${idServicio}, recinto = '$nombreRecinto', textoAccesorios = '$textoAccesorios', textoInsumos = '$textoInsumos' , nomProveedor = '$nombreProveedor', direccionProveedor = '$direccionProveedor', telProveedor = '$telefonoProveedor', emailProveedor = '$emailProveedor', nomContacto = '$nombreContactoProveedor', observaciones = '$observacionesContactoProveedor', textoPautas = '$textoPautas', textoCertificados = '$textoCertificados', textoManuales = '$textoManuales' , textoOtros = '$textoOtros' , claseEquipo = '$claseEquipo' , subClaseEquipo = '$subClaseEquipo', compleEquipo = '$compleEquipo', nivelRiesgo = '$nivelRiesgo', ahnoFabricacion = '$ahnoFabricacion', vidaUtil = '$vidaUtil' , versionSoftware = '$versionSoftware' , paisOrigen = '$paisOrigen' , terminoGarantia = '$terminoGarantia', costoAnualMantenimiento = '$costoAnualMantenimiento', frecuenciaMantenimiento = '$frecuenciaMantenimiento'  WHERE idHoja = ${idHoja}";
            $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);

            if (!empty($_FILES["adjuntosPauta"]) || !empty($_FILES["adjuntosCertificados"]) || !empty($_FILES["adjuntosManuales"]) || !empty($_FILES["adjuntosOtros"])) {

                $directorio = "../sdo-archivosHojasVida/EQUIPO-" . $idUnico . "/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777);
                }
            }

            //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
            if (!empty($_FILES["adjuntosPauta"])) {

                $count = 0;
                foreach ($_FILES['adjuntosPauta']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }

                if ($count > 0) {

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["adjuntosPauta"]["tmp_name"][$i];
                        $name = $_FILES["adjuntosPauta"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                            exit;
                        } else {

                            $directorio = "../sdo-archivosHojasVida/EQUIPO-" . $idUnico . "/pautas/";
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                                if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                    chmod($rutaArchivo, 0777);
                                    $tipoArchivo = "pauta";
                                    $queryArchivos = "INSERT INTO archivos_hojas_vida(idArchivoHJ,nomArchivoHJ,rutaArchivoHJ, idHoja,tipoArchivo) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','" . $tipoArchivo . "')";
                                    $resArchivos = mysqli_query($conexion, $queryArchivos);
                                    // echo $queryArchivos;
                                    // exit;
                                } else {
                                    echo "FALLO EN LA SUBIDA DE PAUTAS";
                                }
                            } else {
                                header('Location: ../' . $ruta . '/visualizarEquipo.php?formatoNoValido=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $ruta . '/visualizarEquipo.php?noAdjunto=true');
                }
            } else {
                echo "no entra";
                exit;
            }

            //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
            if (!empty($_FILES["adjuntosCertificados"])) {

                $count = 0;
                foreach ($_FILES['adjuntosCertificados']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }

                if ($count > 0) {

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["adjuntosCertificados"]["tmp_name"][$i];
                        $name = $_FILES["adjuntosCertificados"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                            exit;
                        } else {

                            $directorio = "../sdo-archivosHojasVida/EQUIPO-" . $idUnico . "/certificados/";
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                                if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                    chmod($rutaArchivo, 0777);
                                    $tipoArchivo = "certificado";
                                    $queryArchivos = "INSERT INTO archivos_hojas_vida(idArchivoHJ,nomArchivoHJ,rutaArchivoHJ, idHoja,tipoArchivo) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','" . $tipoArchivo . "')";
                                    $resArchivos = mysqli_query($conexion, $queryArchivos);
                                    // echo $queryArchivos;
                                    // exit;
                                } else {
                                    echo "FALLO EN LA SUBIDA DE CERTIFICADOS";
                                }
                            } else {
                                header('Location: ../' . $ruta . '/visualizarEquipo.php?formatoNoValido=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $ruta . '/visualizarEquipo.php?noAdjunto=true');
                }
            } else {
                echo "no entra";
                exit;
            }

            //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
            if (!empty($_FILES["adjuntosManuales"])) {

                $count = 0;
                foreach ($_FILES['adjuntosManuales']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }

                if ($count > 0) {

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["adjuntosManuales"]["tmp_name"][$i];
                        $name = $_FILES["adjuntosManuales"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                            exit;
                        } else {

                            $directorio = "../sdo-archivosHojasVida/EQUIPO-" . $idUnico . "/manuales/";
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                                if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                    chmod($rutaArchivo, 0777);
                                    $tipoArchivo = "manual";
                                    $queryArchivos = "INSERT INTO archivos_hojas_vida(idArchivoHJ,nomArchivoHJ,rutaArchivoHJ, idHoja,tipoArchivo) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','" . $tipoArchivo . "')";
                                    $resArchivos = mysqli_query($conexion, $queryArchivos);
                                    // echo $queryArchivos;
                                    // exit;
                                } else {
                                    echo "FALLO EN LA SUBIDA DE MANUALES";
                                }
                            } else {
                                header('Location: ../' . $ruta . '/visualizarEquipo.php?formatoNoValido=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $ruta . '/visualizarEquipo.php?noAdjunto=true');
                }
            } else {
                echo "no entra";
                exit;
            }


            //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
            if (!empty($_FILES["adjuntosOtros"])) {

                $count = 0;
                foreach ($_FILES['adjuntosOtros']['tmp_name'] as $tmpFilee) {
                    if (is_uploaded_file($tmpFilee)) {
                        $count++;
                    }
                }

                if ($count > 0) {

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_name = $_FILES["adjuntosOtros"]["tmp_name"][$i];
                        $name = $_FILES["adjuntosOtros"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo "Vacios";
                            exit;
                        } else {

                            $directorio = "../sdo-archivosHojasVida/EQUIPO-" . $idUnico . "/otros/";
                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $rutaArchivo = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml" || $extImagen == "png" || $extImagen == "jpg" || $extImagen == "jpeg" || $extImagen == "svg" || $extImagen == "gif") {

                                if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                    chmod($rutaArchivo, 0777);
                                    $tipoArchivo = "otros";
                                    $queryArchivos = "INSERT INTO archivos_hojas_vida(idArchivoHJ,nomArchivoHJ,rutaArchivoHJ, idHoja,tipoArchivo) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $id . "','" . $tipoArchivo . "')";
                                    $resArchivos = mysqli_query($conexion, $queryArchivos);
                                    // echo $queryArchivos;
                                    // exit;
                                } else {
                                    echo "FALLO EN LA SUBIDA DE OTROS";
                                }
                            } else {
                                header('Location: ../' . $ruta . '/visualizarEquipo.php?formatoNoValido=true');
                            }
                        }
                    }
                } else {
                    header('Location: ../' . $ruta . '/visualizarEquipo.php?noAdjunto=true');
                }
            } else {
                echo "no entra";
                exit;
            }

            if ($resUpdateEquipo) {
                header('Location: ../' . $ruta . '/visualizarEquipo.php?exito=true&&idHoja=' . $idHoja);
            } else {
                header('Location: ../' . $ruta . '/visualizarEquipo.php?errorModificar=true');
            }
        } else {
            header('Location: ../' . $ruta . '/visualizarEquipo.php?faltanCampos=true');
        }
    } else {
        header('Location: ../' . $ruta . '/visualizarEquipo.php?noId=true');
    }
}
