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
$idDepartamento = isset($_SESSION['idDepartamento']);

$rolUsuario2 = strtolower($rolUsuario);

$rutaUsuario = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $idDepartamento = trim($_SESSION['idDepartamento']);
    $rutaUsuario = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaUsuario = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaUsuario = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaUsuario = "sdo-jefestecnicos";
    $idDepartamento = $_SESSION['idDepartamento'];
} else if ($rolUsuario2 == "administrador") {
    $idDepartamento = 1;
    $tipoUsuario = 'administrador';
    $rutaUsuario = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaUsuario = "sdo-directiva";
    $idDepartamento = 1;
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Comprueba que los input importante no esten vacios.
    if (
        !empty($_POST['id'])  && !empty($_POST['nombreEquipo'])  && !empty($_POST['marcaEquipo'])  && !empty($_POST['modeloEquipo']) && !empty($_POST['servicioOrden'])
    ) {

        //OBTIENE LOS DATOS ENVIADOS POR EL FORMULARIO SECCIÓN DATOS TÉCNICOS DEL EQUIPO Y LAS GUARDA EN VARIABLES
        $idUnico = $_POST['id'];
        $numeroInventario = trim($_POST['numeroInventario']);

        /* Fecha recepción*/
        $diaFechaRecepcion = $_POST['diaFechaRecepcion'];
        $mesFechaRecepcion = $_POST['mesFechaRecepcion'];
        $anhoFechaRecepcion = $_POST['anhoFechaRecepcion'];

        if ($diaFechaRecepcion !== '' && empty($mesFechaRecepcion) && empty($anhoFechaRecepcion)) {
            $fechaRecepcion = $diaFechaRecepcion;
        } else if ($diaFechaRecepcion !== '' && $mesFechaRecepcion !== '' && empty($anhoFechaRecepcion)) {
            $fechaRecepcion = $diaFechaRecepcion . '/' . $mesFechaRecepcion;
        } else if (empty($diaFechaRecepcion)  && $mesFechaRecepcion !== '' && $anhoFechaRecepcion !== '') {
            $fechaRecepcion =   $mesFechaRecepcion . '/' . $anhoFechaRecepcion;
        } else if ($diaFechaRecepcion !== '' && $mesFechaRecepcion !== '' && $anhoFechaRecepcion !== '') {
            $fechaRecepcion = $diaFechaRecepcion . '/' . $mesFechaRecepcion . '/' . $anhoFechaRecepcion;
        } else {
            $fechaRecepcion =  $anhoFechaRecepcion; 
        }

        /* Fecha de Compra*/
        $diaFechaCompra = $_POST['diaFechaCompra'];
        $mesFechaCompra = $_POST['mesFechaCompra'];
        $anhoFechaCompra = $_POST['anhoFechaCompra'];


        if ($diaFechaCompra !== '' && empty($mesFechaCompra) && empty($anhoFechaCompra)) {
            $fechaCompra = $diaFechaCompra;
        } else if ($diaFechaCompra !== '' && $mesFechaCompra !== '' && empty($anhoFechaCompra)) {
            $fechaCompra = $diaFechaCompra . '/' . $mesFechaCompra;
        } else if (empty($diaFechaCompra)  && $mesFechaCompra !== '' && $anhoFechaCompra !== '') {
            $fechaCompra =   $mesFechaCompra . '/' . $anhoFechaRecepcion;
        } else if ($diaFechaCompra !== '' && $mesFechaCompra !== '' && $anhoFechaCompra !== '') {
            $fechaCompra = $diaFechaCompra . '/' . $mesFechaCompra . '/' . $anhoFechaCompra;
        } else {
            $fechaCompra =  $anhoFechaRecepcion;
        }

        /* Fecha Puesta en Marcha*/
        $diaFechaMarcha = $_POST['diaFechaMarcha'];
        $mesFechaMarcha = $_POST['mesFechaMarcha'];
        $anhoFechaMarcha = $_POST['anhoFechaMarcha'];


        if ($diaFechaMarcha !== '' && empty($mesFechaMarcha) && empty($anhoFechaMarcha)) {
            $fechaEnMarcha = $diaFechaMarcha;
        } else if ($diaFechaMarcha !== '' && $mesFechaMarcha !== '' && empty($anhoFechaMarcha)) {
            $fechaEnMarcha = $diaFechaMarcha . '/' . $mesFechaMarcha;
        } else if (empty($diaFechaMarcha)  && $mesFechaMarcha !== '' && $anhoFechaMarcha !== '') {
            $fechaEnMarcha =   $mesFechaMarcha . '/' . $anhoFechaMarcha;
        } else if ($diaFechaMarcha !== '' && $mesFechaMarcha !== '' && $anhoFechaMarcha !== '') {
            $fechaEnMarcha = $diaFechaMarcha . '/' . $mesFechaMarcha . '/' . $anhoFechaMarcha;
        } else {
            $fechaEnMarcha =  $anhoFechaMarcha;
        }

        /* Fecha Termino de garantía:*/
        $diaFechaGarantia = $_POST['diaFechaGarantia'];
        $mesFechaGarantia = $_POST['mesFechaGarantia'];
        $anhoFechaGarantia = $_POST['anhoFechaGarantia'];


        if ($diaFechaGarantia !== '' && empty($mesFechaGarantia) && empty($anhoFechaGarantia)) {
            $terminoGarantia = $diaFechaGarantia;
        } else if ($diaFechaGarantia !== '' && $mesFechaGarantia !== '' && empty($anhoFechaGarantia)) {
            $terminoGarantia = $diaFechaGarantia . '/' . $mesFechaGarantia;
        } else if (empty($diaFechaGarantia)  && $mesFechaGarantia !== '' && $anhoFechaGarantia !== '') {
            $terminoGarantia =   $mesFechaGarantia . '/' . $anhoFechaGarantia;
        } else if ($diaFechaGarantia !== '' && $mesFechaGarantia !== '' && $anhoFechaGarantia !== '') {
            $terminoGarantia = $diaFechaGarantia . '/' . $mesFechaGarantia . '/' . $anhoFechaGarantia;
        } else {
            $terminoGarantia =  $anhoFechaGarantia;
        }

        echo "Fecha recepcion:" . $fechaRecepcion . " <br>";

        echo "Fecha compra:" . $fechaCompra . " <br>";

        echo "Fecha marcha:" . $fechaEnMarcha . " <br>";

        echo "Fecha termino garantía:" . $terminoGarantia . " <br>";

        $nombreEquipo = trim($_POST['nombreEquipo']);
        $valorIva = trim($_POST['valorIva']);
        $marcaEquipo = trim($_POST['marcaEquipo']);
        $idLicitacion = trim($_POST['idLicitacion']);
        $modeloEquipo = trim($_POST['modeloEquipo']);
        $ordenCompra = trim($_POST['ordenCompra']);
        $numeroSerie = trim($_POST['numeroSerie']);

        $idServicio = trim($_POST['servicioOrden']);

        var_dump($idServicio);

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

        // //FORMATEA EL NOMBRE DEL EQUIPO PARA INTRODUCIRLO EN UN FOLIO
        // $cadena = str_replace(' ', '', $nombreEquipo);
        // $folioTexto = substr($cadena, 0, 12);

        //CONSULTA LOS SERVICIOS PARA OBTENER SU NOMBRE DESDE SU ID
        $sqlServicio = "SELECT * FROM servicios WHERE idServicio = ${idServicio}";
        $resServicio = mysqli_query($conexion, $sqlServicio);

        if ($resServicio) {
            while ($row = $resServicio->fetch_assoc()) {
                $datos = [];
                $datos['nomServicio'] = $row['nomServicio'];
                $nomServicio = $datos['nomServicio'];
            }
        } else {
            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noServicio=true');
        }


        if (empty($valorIva)) {
            $valorIva = 0;
            echo $entre;
        }

        //Inserta en la base de datos la información del equipo
        $sqlEquipo = "INSERT INTO hoja_de_vida (idHoja ,idUnico, folio,numInventario,fechaRecepcion,nomEquipo,valorConIva,marca,idLicitacion,modelo,ordenCompra,numSerie,fechaCompra,fechaMarcha,idServicio,recinto,textoAccesorios,textoInsumos,nomProveedor,direccionProveedor,telProveedor,emailProveedor,nomContacto,observaciones,idCreadoPor,idDepaCreador,tipoUsuario,textoPautas,textoCertificados,textoManuales,textoOtros,claseEquipo,subClaseEquipo,compleEquipo,nivelRiesgo,ahnoFabricacion,vidaUtil,versionSoftware,paisOrigen,terminoGarantia,costoAnualMantenimiento,frecuenciaMantenimiento,estadoActual) VALUES (NULL,'" . $idUnico . "', 1,'" . $numeroInventario . "','" . $fechaRecepcion . "','" . $nombreEquipo . "','" . $valorIva . "','" . $marcaEquipo . "','" . $idLicitacion . "','" . $modeloEquipo . "','" . $ordenCompra . "','" . $numeroSerie . "','" . $fechaCompra . "','" . $fechaEnMarcha . "','" . $idServicio . "','" . $nombreRecinto . "','" . $textoAccesorios . "','" . $textoInsumos . "','" . $nombreProveedor . "','" . $direccionProveedor . "','" . $telefonoProveedor . "','" . $emailProveedor . "','" . $nombreContactoProveedor . "','" . $observacionesContactoProveedor . "','" . $idUsuario . "','" . $idDepartamento . "','" . $tipoUsuario . "','" . $textoPautas . "','" . $textoCertificados . "','" . $textoManuales . "','" . $textoOtros . "','" . $claseEquipo . "','" . $subClaseEquipo . "','" . $compleEquipo . "','" . $nivelRiesgo . "','" . $ahnoFabricacion . "','" . $vidaUtil . "','" . $versionSoftware . "','" . $paisOrigen . "','" . $terminoGarantia . "','" . $costoAnualMantenimiento . "','" . $frecuenciaMantenimiento . "','Operativo')";
        $resEquipo = mysqli_query($conexion, $sqlEquipo);


        //GENERAR FOLIO UNICO QUE SE USARA COMO IDENTIFICADOR DENTRO DEL SISTEMA PARA EL REGISTRO DEL EQUIPO.
        $id = mysqli_insert_id($conexion);

        if ($resEquipo) {

            if (isset($_FILES["archivoImagen"])) {
                $tot = count($_FILES["archivoImagen"]["name"]);
                for ($i = 0; $i < $tot; $i++) {
                    $tmp_name = $_FILES["archivoImagen"]["tmp_name"][$i];
                    $name = $_FILES["archivoImagen"]["name"][$i];

                    if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                        echo "VACIOS";
                    } else {

                        $directorio = "../sdo-imagenes-equipo/Equipo-" . $id . "/";

                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777);
                        }

                        $dir = opendir($directorio);

                        $ruta = $directorio . $name;

                        $separarNombre = explode(".", $name);

                        $extImagen = end($separarNombre);

                        $extImagen = mb_strtolower($extImagen);


                        if ($extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                            // if (is_dir($directorio) && is_writable($directorio)) {
                            //     echo 'hola';
                            // } else {
                            //     echo 'Upload directory is not writable, or does not exist.';
                            // }

                            if (move_uploaded_file($tmp_name, $ruta)) {

                                $queryImagen = "INSERT INTO imagenes_equipo(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idEquipo) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "',0,'" . $id . "')";

                                $resImagen = mysqli_query($conexion, $queryImagen);

                                if ($resImagen) {
                                   echo "subi imagen";
                                } else {

                                 echo "subi imagen";
                                }
                           
                            }
                        } else {
                            echo "ERROR FORMATO";
                        }
                    }
                }
            }
        }

        if ($id < 10) {
            $folioUnico = "000" . $id;
        } else if ($id > 9 && $id < 100) {
            $folioUnico = "00" . $id;
        } else if ($id > 99 && $id < 1000) {
            $folioUnico = "0" . $id;
        } else if ($id > 999) {
            $folioUnico = $id;
        } else {
            $folioUnico = $id;
        }

        $folio = "EQ-" . $idUnico;
        $sqlUpdateEquipo = "UPDATE hoja_de_vida SET folio = '${folio}' WHERE idHoja = ${id}";
        $resUpdateEquipo = mysqli_query($conexion, $sqlUpdateEquipo);

        if ($resUpdateEquipo) {
            echo "ok";
        } else {
            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noUpdateEquipo=true');
        }

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
                            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?formatoNoValido=true');
                        }
                    }
                }
            } else {

                header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noAdjunto=true');
            }
        }

        //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
        if (isset($_FILES["adjuntosCertificados"])) {

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
                            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?formatoNoValido=true');
                        }
                    }
                }
            } else {

                header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noAdjunto=true');
            }
        }

        //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
        if (isset($_FILES["adjuntosManuales"])) {

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
                            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?formatoNoValido=true');
                        }
                    }
                }
            } else {

                header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noAdjunto=true');
            }
        }


        //SUBE LOS ARCHIVOS DE DEL INPUT CERTIFICADOS
        if (isset($_FILES["adjuntosOtros"])) {

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
                            header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?formatoNoValido=true');
                        }
                    }
                }
            } else {

                header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?noAdjunto=true');
            }
        }

        if ($resEquipo) {
            header('Location: ../' . $rutaUsuario . '/administrar-equipos.php?exito=true');
        }
    } else {
        header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?faltan=true');
    }
} else {
    header('Location: ../' . $rutaUsuario . '/ingresar-equipo-hojadevida.php?errorInputVacios=true');
}



        // //MUESTRA EL CONTENIDO DE LAS VARIABLES
        // echo "Numero Inventario:" . $numeroInventario;
        // echo "<br>";
        // echo "Fecha Recepcion:" . $fechaRecepcion;
        // echo "<br>";
        // echo "Numero Equipo:" . $nombreEquipo;
        // echo "<br>";
        // echo "Valor Iva:" . $valorIva;
        // echo "<br>";
        // echo "Marca:" . $marcaEquipo;
        // echo "<br>";
        // echo "idLicitación: " . $idLicitacion;
        // echo "<br>";
        // echo "Modelo:" . $modeloEquipo;
        // echo "<br>";
        // echo "Orden Compra:" . $ordenCompra;
        // echo "<br>";
        // echo "Número Serie:" . $numeroSerie;
        // echo "<br>";
        // echo "Fecha Compra:" . $fechaCompra;
        // echo "<br>";
        // echo "Fecha en marcha:" . $fechaEnMarcha;
        // echo "<br>";
        // echo "idServicio:" . $idServicio;
        // echo "<br>";
        // echo "textoAccesorios:" . $textoAccesorios;
        // echo "<br>";
