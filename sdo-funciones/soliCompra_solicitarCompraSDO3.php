<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "directiva") {

    $ruta = "sdo-directiva";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";

    $cargoUsuario = $_SESSION['cargoUsuario'];

    $cargoUsuario = trim($cargoUsuario);

    if (empty($cargoUsuario)) {
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    }
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['personalSC'], $_POST['cargoSC'], $_POST['deptoSC'])) {

    $personalSC = trim($_POST['personalSC']);
    $cargoSC = trim($_POST['cargoSC']);
    $deptoSC = trim($_POST['deptoSC']);

    $deptoParaFolio = '';

    if ($deptoSC == 'Instrumentación') {

        $deptoSC = 'Control Centralizado';
        $deptoParaFolio = 'CC';
    } else if ($deptoSC == 'Equipos Industriales') {
        $deptoParaFolio = 'EQINDUS';
    } else if ($deptoSC == 'Inst. Térmicas, Climatización y Refrigeración') {
        $deptoParaFolio = 'CLIMA';
    } else if ($deptoSC == 'Electricidad') {
        $deptoParaFolio = 'ELECT';
    } else if ($deptoSC == 'Infraestructura') {
        $deptoParaFolio = 'INFRA';
    } else if ($deptoSC == 'Equipos Médicos') {
        $deptoParaFolio = 'EQMED';
    } else if ($deptoSC == 'Logística') {
        $deptoParaFolio = 'LOG';
    } else if ($deptoSC == 'Movilización') {
        $deptoParaFolio = 'MOV';
    } else {
        $deptoParaFolio = 'SDO';
    }

    $emailSC = trim($_POST['emailSC']);
    $descripcionSoliCompra = trim($_POST['descripcionSoliCompra']);

    $archivoCotizacion = '';

    if ($rolUsuario2 == "administrador") {
        $nomCreadorSC = $personalSC;

        $deptoSC = $cargoSC;
    } else {
        $nomCreadorSC = $personalSC . ' (' . $cargoSC . ')';
    }


    if (!empty($personalSC) && !empty($cargoSC) && !empty($deptoSC) && !empty($emailSC)) {

        if (isset($_POST['argumentosNecesidad'])) {

            $argumentosNecesidad = $_POST['argumentosNecesidad'];

            if ($argumentosNecesidad == '' || $argumentosNecesidad == ' ') {
                header("Location: ../$ruta/soliCompra_sdo_administracion.php?errorCrearSC=true");
            } else {
                $argumentosNecesidad = str_replace("'", "\'", $argumentosNecesidad);
            }
            
            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $querySC = "INSERT INTO solicitudes_compra_sdo(idSoliCompra,numSoliCompra,folioSoliCompra,descripcionSoliCompra,fechaSoliCompra,personalSoliCompra,unidadSoliCompra,emailSoliCompra,argumentosSoliCompra,adjuntaCotizacion,estadoSoliCompra)
            VALUES (NULL,0,'','".$descripcionSoliCompra."','".$now."','" . $nomCreadorSC . "','" . $deptoSC . "','" . $emailSC . "','" . $argumentosNecesidad . "','0','Solicitada a Mesa de Ayuda')";

            $resSC = mysqli_query($conexion, $querySC);

            if ($resSC) {
                $idSolicitudCompra = mysqli_insert_id($conexion);

                $_SESSION['idSolicitudCompra'] = $idSolicitudCompra;


                echo '<br><br>';

                if (isset($_POST['articuloSC'], $_POST['cantidadSC'], $_POST['medidaSC'], $_POST['valorSC'])) {

                    $articuloSC = $_POST['articuloSC'];
                    $cantidadSC = $_POST['cantidadSC'];
                    $medidaSC = $_POST['medidaSC'];
                    $valorSC = $_POST['valorSC'];

                    if (!empty($articuloSC) && !empty($cantidadSC) && $cantidadSC > 0) {

                        for ($i = 0; $i < count($articuloSC); $i++) {
                            if (!empty($articuloSC[$i])) {
                                $articuloSCFor = $articuloSC[$i];
                                $cantidadSCFor = $cantidadSC[$i];
                                $medidaSCFor = $medidaSC[$i];
                                $valorSCFor = $valorSC[$i];

                                $queryArticuloSC = "INSERT INTO material_solicompra_sdo(idArticuloSC,nomArticuloSC,cantidadArticuloSC,medidaSC,valorSC,idSoliCompra)
                                VALUES(NULL,'" . $articuloSCFor . "','" . $cantidadSCFor . "','" . $medidaSCFor . "','" . $valorSCFor . "'," . $idSolicitudCompra . ")";

                                $resArticuloSC = mysqli_query($conexion, $queryArticuloSC);

                                if ($resArticuloSC) {
                                    $usoMaterial = "Con";
                                } else {
                                    $usoMaterial = "Sin";
                                }

                                // echo $queryMaterialSC;
                            } else {
                                $usoMaterial = "Sin";
                            }
                        }
                    } else {
                        $usoMaterial = "Sin";
                    }
                } else {
                    $usoMaterial = "Sin";
                }

                if ($usoMaterial == "Con") {
                    echo "Creado Con Material!";
                } else {
                    echo "Creado Sin Material!";
                }

                if ($idSolicitudCompra < 10) {
                    $idSolicitudCompra = "000" . $idSolicitudCompra;
                } else if ($idSolicitudCompra > 9 && $idSolicitudCompra < 100) {
                    $idSolicitudCompra = "00" . $idSolicitudCompra;
                } else if ($idSolicitudCompra > 99 && $idSolicitudCompra < 1000) {
                    $idSolicitudCompra = "0" . $idSolicitudCompra;
                } else if ($idSolicitudCompra > 999) {
                    $idSolicitudCompra = $idSolicitudCompra;
                } else {
                    $idSolicitudCompra = $idSolicitudCompra;
                }

                //SUBIR ARCHIVOS

                if (isset($_FILES["adjuntosSC"])) {

                    $count = 0;
                    foreach ($_FILES['adjuntosSC']['tmp_name'] as $tmpFilee) {
                        if (is_uploaded_file($tmpFilee)) {
                            $count++;
                        }
                    }


                    if ($count > 0) {
                        $queryCotizacion1 = "UPDATE solicitudes_compra_sdo SET adjuntaCotizacion = '1' WHERE idSoliCompra =" . $idSolicitudCompra;

                        $resCotizacion1 = mysqli_query($conexion, $queryCotizacion1);

                        if ($resCotizacion1) {
                            for ($i = 0; $i < $count; $i++) {
                                $tmp_name = $_FILES["adjuntosSC"]["tmp_name"][$i];
                                $name = $_FILES["adjuntosSC"]["name"][$i];

                                if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                    $archivoCotizacion = "Vacio";
                                } else {

                                    $directorio = "../sdo-solicCompra/SC-SDO-" . $idSolicitudCompra . "/";

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

                                            $queryCotizacion = "INSERT INTO cotizacion_solicompra_sdo(idCotizacionSC,nomCotizacionSC,rutaCotizacionSC,tipoSoliCompra,destinoSoliCompra,idSoliCompra) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','Normal','Subdirección de Operaciones','" . $idSolicitudCompra . "')";

                                            $resCotizacion = mysqli_query($conexion, $queryCotizacion);

                                            if ($resCotizacion) {
                                                $archivoCotizacion = 'Usando';
                                            } else {

                                                $archivoCotizacion = 'Error';
                                            }
                                        } else {
                                            $archivoCotizacion = 'Error';
                                        }
                                    } else {
                                        $archivoCotizacion = 'formatoIncorrecto';
                                    }
                                }
                            }
                        } else {
                            $archivoCotizacion = "Vacio";
                        }
                    } else {
                        $archivoCotizacion = "Vacio";
                    }
                } else {
                    $archivoCotizacion = "Vacio";
                }

                //FIN SUBIR ARCHIVOS


                $iniciales = getIniciales($personalSC);

                $folioSoliCompra = "SC-SDO-" . $deptoParaFolio . "-" . $idSolicitudCompra . "-" . $iniciales;

                $queryUpdateSC = "UPDATE solicitudes_compra_sdo SET folioSoliCompra = '" . $folioSoliCompra . "', numSoliCompra = '".$idSolicitudCompra."' WHERE idSoliCompra = '" . $idSolicitudCompra . "'";

                $resUpdateSC = mysqli_query($conexion, $queryUpdateSC);

                if ($resUpdateSC) {

                    $rutaCorrecta = "Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?exitoCrearSC=true&material=" . $usoMaterial . "";

                    header($rutaCorrecta);
                } else {
                    $rutaSinFolio = "Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?exitoCrearSC=true&material=" . $usoMaterial . "&sinFolio=true";

                    header($rutaSinFolio);
                }
            } else {
                header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
            }
        } else {
            header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
        }
    } else {
        header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
    }
} else {
    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
}
