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
}else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
}else {

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

        if ($cargaSolicitante == 'Jefe' || $cargaSolicitante == 'Directivo' || $cargaSolicitante == 'JefeCC' || $cargaSolicitante == 'PerfilEncargado') {

            if ($cargaSolicitante == 'Jefe') {

                if ($_POST['nomTecnicoRecepcionador']) {
                    $idProfesional = $_POST['nomTecnicoRecepcionador'];

                    $idProfesional = trim($idProfesional);
                } else {
                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC1=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC9=true");
                }

                $dataSolicitante = array();
                $dataSolicitante = getDatosJefe($idProfesional);

                if($personalSC == 'Alejandro Cantillano' || $personalSC == 'Jorge Cantillano Sánchez' || $personalSC == 'Jorge Cantillano'){
                    $personalSC = 'Alejandro Cantillano';
                    $cargoSC = 'Jefe de Control Centralizado';
                    $deptoSC = 'Control Centralizado';
                    $emailSC = 'jorge.cantillano@redsalud.gob.cl';
                }else{

                    $personalSC = $dataSolicitante['nomJefe'];
                    $cargoSC = $dataSolicitante['cargoJefe'];
                    $deptoSC = getNomDepartamento($dataSolicitante['idDepartamento']);
                    $emailSC = $dataSolicitante['emailJefe'];
                }

            } else if ($cargaSolicitante == 'Directivo') {

                if ($_POST['directivo']) {
                    $idProfesional = $_POST['directivo'];

                    $idProfesional = trim($idProfesional);
                } else {

                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC8=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC7=true");
                }

                $dataSolicitante = array();
                $dataSolicitante = getDatosDirectivo($idProfesional);

                $personalSC = $dataSolicitante['nomDirectivo'];
                $cargoSC = $dataSolicitante['cargoDirectivo'];
                $deptoSC = 'Ingeniería Operacional';
                $emailSC = $dataSolicitante['emailDirectivo'];
            } else if ($cargaSolicitante == 'JefeCC') {

                $personalSC = 'Jorge Cantillano Sánchez';
                $cargoSC = 'Jefe de Control Centralizado';
                $deptoSC = 'Control Centralizado';
                $emailSC = 'jorge.cantillano@redsalud.gob.cl';
            } else if ($cargaSolicitante == 'PerfilEncargado') {

                $personalSC = trim($_POST['personalSC']);
                $cargoSC = trim($_POST['cargoSC']);
                $deptoSC = trim($_POST['deptoSC']);
                $direccion = $_POST['direccion'];
                $emailSC = trim($_POST['emailSC']);

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

            $ticketSIGH = $_POST['ticketSIGH'];

            if(empty($ticketSIGH)){
                $ticketSIGH = 'No Aplica';
            }


            $idSubAsigna = 0;

//            ECHO $idSubAsigna.'<br>';
//
//
//            if($idSubAsigna == 0 || $idSubAsigna == ''){
//               $idSubAsigna = 0;
//            }else{
//                $idSubAsigna2 = $idSubAsigna;
//            }

            if (isset($_POST['argumentosNecesidad'])) {

                $argumentosNecesidad = $_POST['argumentosNecesidad'];

                if ($argumentosNecesidad == '' || $argumentosNecesidad == ' ') {

                    if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC77=true');
                    }

                    header("Location: ../$ruta/soliCompra_sdo_administracion.php?errorCrearSC78=true");
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

                $querySC = "INSERT INTO solicitudes_compra_sdo(idSoliCompra,numSoliCompra,folioSoliCompra,descripcionSoliCompra,fechaSoliCompra,personalSoliCompra,unidadSoliCompra,emailSoliCompra,argumentosSoliCompra,adjuntaCotizacion,estadoSoliCompra, isCotizacion,idSolicitudSoporte,numTicketSIGH,idSubAsigna)
                VALUES (NULL,$numSoliC,'','".$descripcionSoliCompra."','" . $now . "','" . $nomCreadorSC . "','" . $deptoSC . "','" . $emailSC . "','" . $argumentosNecesidad . "','0','Solicitada a Secretaria SDO', $isCotizacion, $idSolicitudSoporte, '$ticketSIGH', $idSubAsigna)";

//                echo $querySC;
//                exit;
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

                    if(isset($_POST['cantidadFilas'])){
                        $cantidadFilas = $_POST['cantidadFilas'];

                        if($cantidadFilas > 0){

                            for ($i = 1; $i <= $cantidadFilas; $i++) {

                                $articuloSC = $_POST['articuloSC' . $i];
                                $cantidadSC = $_POST['cantidadSC' . $i];
                                $medidaSC = $_POST['medidaSC' . $i];
                                $valorSC = $_POST['valorSC' . $i];

                                if (!empty($articuloSC) && !empty($cantidadSC) && $cantidadSC > 0) {
                                    $queryArticuloSC = "INSERT INTO material_solicompra_sdo(idArticuloSC,nomArticuloSC,cantidadArticuloSC,medidaSC,valorSC,idSoliCompra, cantidadFaltante)  VALUES(NULL,'" . $articuloSC . "','" . $cantidadSC . "','" . $medidaSC . "','" . $valorSC . "'," . $idSolicitudCompra . ",'" . $cantidadSC . "')";

                                    $resArticuloSC = mysqli_query($conexion, $queryArticuloSC);

                                    if ($resArticuloSC) {
                                        $usoMaterial = "Con";
                                    } else {
                                        $usoMaterial = "Sin";
                                    }
                                }else{
                                    $usoMaterial = "Sin";
                                }

                            }

                        }else{
                            $usoMaterial = "Sin";
                        }
                    }else{
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


                                        if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml" || $extImagen == "jpeg" || $extImagen == "jpg" || $extImagen == "png") {

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
                        header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC1=true');
                    }

                    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC1=true");
                }
            } else {

                if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                    header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC2=true');
                }

                header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC2=true");
            }
        } else {

            if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
                header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC3=true');
            }

            header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC3=true");
        }
    } else {

        if($idSolicitudSoporte !== '' && $direccion == 'Admin'){
            header('Location: ../'. $ruta .'/tickets_solicitarCompras_sdo.php?errorCrearSC4=true');
        }

        header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC4=true");
    }
} else {
    header("Location: ../" . $ruta . "/soliCompra_sdo_administracion.php?errorCrearSC5=true");
}
