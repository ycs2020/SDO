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

if (isset($_POST['cargaSolicitante'])) {

    $cargaSolicitante = $_POST['cargaSolicitante'];
    $cargaSolicitante = trim($cargaSolicitante);


    $idSolicitudSoporte = $_POST['idSolicitudSoporte'];

    if($idSolicitudSoporte == ''){
        $idSolicitudSoporte = '0';
    }

    $isCotizacion = $_POST['isCotizacion'];

    if($isCotizacion == ''){
        $isCotizacion = 0;
    }

    $idProfesional = '';
    $direccion = $_POST['dirrecion'];

    $personalSC = '';
    $cargoSC = '';
    $deptoSC = '';
    $emailSC = '';

    $deptoParaFolio = '';

    if (!empty($cargaSolicitante)) {

        if ($cargaSolicitante == 'Jefe' || $cargaSolicitante == 'Directivo' || $cargaSolicitante == 'JefeCC') {

            if ($cargaSolicitante == 'Jefe') {

                if ($_POST['nomTecnicoRecepcionador']) {
                    $idProfesional = $_POST['nomTecnicoRecepcionador'];

                    $idProfesional = trim($idProfesional);
                } else {
                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC1=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
                }

                $dataSolicitante = array();
                $dataSolicitante = getDatosJefe($idProfesional);

                $personalSC = $dataSolicitante['nomJefe'];
                $cargoSC = $dataSolicitante['cargoJefe'];
                $deptoSC = getNomDepartamento($dataSolicitante['idDepartamento']);
                $emailSC = $dataSolicitante['emailJefe'];
            } else if ($cargaSolicitante == 'Directivo') {

                if ($_POST['directivo']) {
                    $idProfesional = $_POST['directivo'];

                    $idProfesional = trim($idProfesional);
                } else {

                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
                }

                $dataSolicitante = array();
                $dataSolicitante = getDatosDirectivo($idProfesional);

                $personalSC = $dataSolicitante['nomDirectivo'];
                $cargoSC = $dataSolicitante['cargoDirectivo'];
                $deptoSC = 'Ingeniería Operacional';
                $emailSC = $dataSolicitante['emailDirectivo'];

                if($personalSC == 'Paola Nuñez Cardenas' || $personalSC == 'María Fernanda Fuentes'){
                    $deptoSC = 'Subdirección de Operaciones';
                }else{
                    $deptoSC = 'Ingeniería Operacional';
                }
            } else if ($cargaSolicitante == 'JefeCC') {

                $personalSC = 'Jorge Cantillano Sánchez';
                $cargoSC = 'Jefe de Control Centralizado';
                $deptoSC = 'Control Centralizado';
                $emailSC = 'jorge.cantillano@redsalud.gob.cl';
            }

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

            $archivoCotizacion = '';

            if ($rolUsuario2 == "administrador") {
                $nomCreadorSC = $personalSC;

                $deptoSC = $cargoSC;
            } else {
                $nomCreadorSC = $personalSC . ' (' . $cargoSC . ')';
            }

            $descripcionSoliCompra = trim($_POST['descripcionSoliCompra']);

            if (isset($_POST['argumentosNecesidad'])) {

                $argumentosNecesidad = $_POST['argumentosNecesidad'];

                if ($argumentosNecesidad == '' || $argumentosNecesidad == ' ') {

                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
                    }

                    header("Location: ../$ruta/soliCompra_sdo_administracion.php?errorCrearSC=true");
                } else {
                    $argumentosNecesidad = str_replace("'", "\'", $argumentosNecesidad);
                }

                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());

                $año = date('Y');
                $selectAct = "SELECT numSoliCompra FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = ".$año." ORDER BY fechaSoliCompra DESC LIMIT 1";
                $resAct = mysqli_query($conexion,$selectAct);
                if ($resAct->num_rows == 0) {
                    $numSoliC = 1;
                    echo $numSoliC;

                    $iniciales = getIniciales($personalSC);

                    if ($numSoliC < 10) {
                        $numSoliC = "000" . $numSoliC;
                    } else if ($numSoliC > 9 && $numSoliC < 100) {
                        $numSoliC = "00" . $numSoliC;
                    } else if ($numSoliC > 99 && $numSoliC < 1000) {
                        $numSoliC = "0" . $numSoliC;
                    } else if ($numSoliC > 999) {
                        $numSoliC = $numSoliC;
                    } else {
                        $numSoliC = $numSoliC;
                    }

                    $folioSoliCompra = "SC-SDO-" . $año . "-". $deptoParaFolio . "-" . $numSoliC . "-". $iniciales;
                }else{
                    $fila = $resAct->fetch_assoc();
                    $numSoliC = $fila['numSoliCompra'];
                    $numSoliC = $numSoliC + 1;
                    echo $numSoliC;
                    $iniciales = getIniciales($personalSC);

                    $año = date('Y');


                    if ($numSoliC < 10) {
                        $numSoliC = "000" . $numSoliC;
                    } else if ($numSoliC > 9 && $numSoliC < 100) {
                        $numSoliC = "00" . $numSoliC;
                    } else if ($numSoliC > 99 && $numSoliC < 1000) {
                        $numSoliC = "0" . $numSoliC;
                    } else if ($numSoliC > 999) {
                        $numSoliC = $numSoliC;
                    } else {
                        $numSoliC = $numSoliC;
                    }


                    $folioSoliCompra = "SC-SDO-" . $año . "-". $deptoParaFolio . "-" . $numSoliC . "-". $iniciales;
                }

                $querySC = "INSERT INTO solicitudes_compra_sdo(idSoliCompra,numSoliCompra,folioSoliCompra,descripcionSoliCompra,fechaSoliCompra,personalSoliCompra,unidadSoliCompra,emailSoliCompra,argumentosSoliCompra,adjuntaCotizacion,estadoSoliCompra, isCotizacion,idSolicitudSoporte)
            VALUES (NULL,$numSoliC,'','".$descripcionSoliCompra."','" . $now . "','" . $nomCreadorSC . "','" . $deptoSC . "','" . $emailSC . "','" . $argumentosNecesidad . "','0','Solicitada a Secretaria SDO', $isCotizacion, $idSolicitudSoporte)";

                $resSC = mysqli_query($conexion, $querySC);

                if ($resSC) {
                    $idSolicitudCompra = mysqli_insert_id($conexion);

                    $queryResumen = "INSERT INTO solicitudes_sc_sdo_resumen (descripcion,fechaSoliCompra,solicitante,unidadSoliCompra, idSoliCompra) VALUES ('$argumentosNecesidad','" . $now . "','$nomCreadorSC', '$deptoSC',$idSolicitudCompra)";
                    $resResumen = mysqli_query($conexion, $queryResumen);

                    //INSERTA EN LA TABLA SEGUIMIENTO SC SDO
                    $tipoActualizacion = "SOLICITADA A SECRETARIA SDO";

                    $querySeguimiento = "INSERT INTO seguimiento_sc_sdo (tipoActualizacion, idSoliCompra) VALUES ('$tipoActualizacion', $idSolicitudCompra) ";
                    $resSeguimiento = mysqli_query($conexion, $querySeguimiento);

                    $querySituacion = "UPDATE solicitudes_soportes SET situacionSolicitud = 'Se encuentra con Solicitud de Compra' WHERE idSolicitud = $idSolicitudSoporte";
                    $resSituacion = mysqli_query($conexion, $querySituacion);

                    $queryObservacion = "INSERT INTO observaciones_tickets (idObservaSolicitudes, obsPrivada, detalleObservaSolicitudes, fechaObservaSolicitudes, tipoObservaSolicitudes, creadorObservaSolicitudes, idSolicitud)
                    VALUES (NULL,NULL,'Se ha generado una solicitud de compra con el numero: $idSolicitudCompra', '".$now."','Creacion de Solicitud de Compra','".$nomCreadorSC."',$idSolicitudSoporte)";
                    $resObservacion = mysqli_query($conexion, $queryObservacion);

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

                                    $queryArticuloSC = "INSERT INTO material_solicompra_sdo(idArticuloSC,nomArticuloSC,cantidadArticuloSC,medidaSC,valorSC,idSoliCompra, cantidadFaltante)
                                VALUES(NULL,'" . $articuloSCFor . "','" . $cantidadSCFor . "','" . $medidaSCFor . "','" . $valorSCFor . "'," . $idSolicitudCompra . ",'" . $cantidadSCFor . "')";

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

                    $queryUpdateSC = "UPDATE solicitudes_compra_sdo SET folioSoliCompra = '" . $folioSoliCompra . "', numSoliCompra = '" .  $numSoliC . "' WHERE idSoliCompra = '" . $idSolicitudCompra . "'";


                    $resUpdateSC = mysqli_query($conexion, $queryUpdateSC);

                    if ($resUpdateSC) {

                        if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                            header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?exitoCrearSC=true&material=' . $usoMaterial);
                        }

                        $rutaCorrecta = "Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?exitoCrearSC=true&material=" . $usoMaterial . "";

                        header($rutaCorrecta);
                    } else {

                        if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                            header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?exitoCrearSC=true&material=' . $usoMaterial . '&sinFolio=true');
                        }

                        $rutaSinFolio = "Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?exitoCrearSC=true&material=" . $usoMaterial . "&sinFolio=true";

                        header($rutaSinFolio);
                    }
                } else {

                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
                }
            } else {

                if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                    header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
                }

                header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
            }
        } else {

            if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
            }

            header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
        }
    } else {

        if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
            header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC=true');
        }

        header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
    }
} else {
    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC=true");
}
