<?php

include_once 'conexion.php';
include_once 'funciones.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$nomCreador = $_SESSION['nomUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


$ruta = "";

if ($rolUsuario2 == "personal") {

    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuario ";
    $res = mysqli_query($conexion, $sql);

    $tipoUsuario = 'personal';

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $ruta = "sdo-administrador";
} else {

    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$now = date('Y-m-d H:i:s');
$nowYear = date('Y-m-d');

$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoFinalizarSolicitud=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorIdSolicitud=true';
$headerError2 = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorDepartamentoResponsable=true';
$headerError3 = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorFinalizarSolicitud=true';


if (isset($_POST['idSolicitud'], $_POST['conclusionProblema'], $_POST['datosFinalizador'], $_POST['nomSolicitante'])) {
    $idSolicitud = $_POST['idSolicitud'];
    $conclusionProblema = $_POST['conclusionProblema'];
    $conclusionProblema = mysqli_real_escape_string($conexion, $conclusionProblema);
    $datosFinalizador = trim($_POST['datosFinalizador']);
    $nomSolicitante = trim($_POST['nomSolicitante']);
    $folioSolicitud = getFolioSolicitud($idSolicitud);
    $deptoSolicitante = $_POST['deptoSolicitante'];
    $pmaSolicitud = $_POST['pmaSolicitud'];
    $detalleSolicitud = $_POST['detalleSolicitud'];
    $servicioOrden = trim($_POST['servicios']);
    $deptoResponsable = trim($_POST['departamentoResponsable']);
    $idDepartamento = $_POST['idDepartamento'];
    $nomDepartamentoSolicitante = trim(getNomDepartamento($idDepartamento));
    $idHoja = $_POST['idHoja'];
    $idCategoria = $_POST['idCategoria'];
    $idEquipo = $_POST['idEquipo'];
    $horaInicio = $_POST['horarioInicio'];
    $horarioTermino = $_POST['horarioTermino'];
    $noOrdenTrabajo = $_POST['noOrdenTrabajo'];
    $tipoMantencion = $_POST['tipoMantencion'];
    $nomEquipo = $_POST['nomEquipo'];
    $numInventario = $_POST['numInventario'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $numSerie = $_POST['numSerie'];
    $recintoOrden = $_POST['recintoOrden'];
    $oc = $_POST['oc'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $comentariosTrabajos = $_POST['comentariosTrabajos'];
    $tipoAcuerdo = $_POST['tipoAcuerdo'];
    $accionesRealiza = trim($_POST['accionesRealiza']);
    $coordenadasOrden = trim($_POST['coordenadasOrden']);

    if (!empty($accionesRealiza)) {

        $accionesRealiza = str_replace("'", "\'", $accionesRealiza);
    }

    if($horaInicio == '' || empty($horaInicio)){
        $horaInicio = '0000-00-00 00:00:00';
    }

    if($horarioTermino == '' || empty($horarioTermino)){
        $horarioTermino = '0000-00-00 00:00:00';
    }

    if($idHoja == ''){
        $idHoja = 0;
    }

    if($idCategoria == ''){
        $idCategoria = 0;
    }

    if (!empty($conclusionProblema)) {

        $conclusionProblema = str_replace("'", "\'", $conclusionProblema);
    }

    if (!empty($detalleSolicitud)) {

        $detalleSolicitud = str_replace("'", "\'", $detalleSolicitud);
    }



    if ($servicioOrden == 0 || $servicioOrden == 'Otro' || $servicioOrden == 'otro') {
        if (isset($_POST['otroServicio'])) {

            $otroServicio = trim($_POST['otroServicio']);

            if (!empty($otroServicio)) {
                $otroServicio = str_replace("'", "\'", $otroServicio);

                $buscarServicio = buscarServicio($otroServicio);

                if ($buscarServicio == 0) {

                    $queryOtroServicio = "INSERT INTO servicios(idServicio,nomServicio) VALUES (NULL,'" . $otroServicio . "')";

                    // echo $queryOtroServicio;

                    $resOtroServicio = mysqli_query($conexion, $queryOtroServicio);

                    $servicioOrden = mysqli_insert_id($conexion);
                } else {
                    $servicioOrden = $buscarServicio;
                }
            } else {
            }
        } else {
        }
    }

    $año = date('Y');
//            $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = 2024 ORDER BY fechaOrden DESC LIMIT 1";
    $selectAct = "SELECT numOrden FROM ordenes_trabajos_general WHERE YEAR(fechaOrden) = ".$año." ORDER BY fechaOrden DESC LIMIT 1";
    $resAct = mysqli_query($conexion,$selectAct);
    if ($resAct->num_rows == 0) {
        $numOrden = 1;
    }else{

        $fila = $resAct->fetch_assoc();
        $numOrden = $fila['numOrden'];
        $numOrden = $numOrden + 1;
    }

    $pieces = explode(" ", $nomDepartamentoSolicitante);

    $str = "";
    foreach ($pieces as $piece) {
        $str .= mb_strtoupper($piece[0], 'UTF-8');
    }

    $folioOrden = "HBQP-HT-A".$año."-" . $str . '-' . $numOrden;

    $queryOrden = "INSERT INTO ordenes_trabajos_general(idOrden,numOrden,fechaOrden,nomSolicitante,nomTecnicoRecepcionador,idDepartamento,servicioOrden,recintoOrden,pmaOrden,coordenadasOrden,tipoMantencion,trabajoRealizado,problemaPresenta,accionesRealiza, comentariosTrabajos, ticketAsociado, idCreador, tipoUsuario, noOrdenTrabajo, idEquipo, idCategoria,horaInicio,horaTermino,tipoAcuerdo,estadoEquipo,oc)
                VALUES (NULL,".$numOrden.",'".$now."','" . $nomSolicitante . "','" . $datosFinalizador . "','" . $idDepartamento . "','" . $servicioOrden . "','" . $recintoOrden . "','" . $pmaSolicitud . "','" . $coordenadasOrden . "','" . $tipoMantencion . "','" . $conclusionProblema . "','" . $detalleSolicitud . "','" . $accionesRealiza . "','" . $comentariosTrabajos . "','" .$folioSolicitud . "', $idUsuario, '$tipoUsuario','" . $noOrdenTrabajo . "', $idHoja, $idCategoria,'".$horaInicio."','".$horarioTermino."','".$tipoAcuerdo."','".$estadoEquipo."','".$oc."')";
    $resOrdem = mysqli_query($conexion,$queryOrden);
    if($resOrdem){
        $idOrden = mysqli_insert_id($conexion);

        if (!empty($_POST['nomEquipo']) || !empty($_POST['marcaEquipo']) || !empty($_POST['numInventario'])) {
            $queryEquipoOrden = "INSERT INTO equipos_recepcion_trabajo (equipo,numInventario,marca,modelo,numSerie,idOrden,idEquipo,idCategoria) 
            VALUES ('$nomEquipo', '$numInventario', '$marcaEquipo', '$modeloEquipo', '$numSerie', $idOrden,$idEquipo,$idCategoria)";
            $resEquipoOrden = mysqli_query($conexion, $queryEquipoOrden);
        }

        $_SESSION['idOrden'] = $idOrden;

        $nomOrden = "Hoja Técnica: " . $folioOrden . ' - ' . $nowYear;

        $idSubDepartamento = '';

        if (isset($_POST['idSubDepartamento'])) {
            $idSubDepartamento = trim($_POST['idSubDepartamento']);

            if ($idSubDepartamento < 1 || empty($idSubDepartamento) || $idSubDepartamento == '') {

                $idSubDepartamento = 0;
            } else {
                $idSubDepartamento = trim($_POST['idSubDepartamento']);
            }
        } else {
            $idSubDepartamento = 0;
        }

        $queryFolio = "UPDATE ordenes_trabajos_general SET folioOrden = '" . $folioOrden . "', nomOrden = '" . $nomOrden . "', idSubDepartamento = '" . $idSubDepartamento . "' WHERE idOrden =" . $idOrden;

        $resFolio = mysqli_query($conexion, $queryFolio);

        $queryFinalizar = "UPDATE solicitudes_soportes SET terminoSolicitud = '" . $now . "' , finalizadaSolicitud = 1 , situacionSolicitud = 'Solicitud Finalizada', conclusionSolicitud = '" . $conclusionProblema . "' WHERE idSolicitud = " . $idSolicitud;

        $resFinalizar = mysqli_query($conexion, $queryFinalizar);

        if($resFolio && $resFinalizar){

            $observacion = $datosFinalizador . ' ha finalizado la Solicitud de Soporte # ' . getFolioSolicitud($idSolicitud) . ' .';

            $tipoObservaSolicitudes = "Finalización de Solicitud";

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $sqlObservacion = "INSERT INTO observaciones_tickets(idObservaSolicitudes,detalleObservaSolicitudes,fechaObservaSolicitudes,tipoObservaSolicitudes,creadorObservaSolicitudes,idSolicitud)
            VALUES(NULL,'" . $observacion . "','" . $now . "','" . $tipoObservaSolicitudes . "','" . $datosFinalizador . "'," . $idSolicitud . ")";

            $resObservacion = mysqli_query($conexion, $sqlObservacion);


            if($resObservacion){
                $usoMaterial = '';

                $usoFoto = '';

                if (isset($_POST['materialUsado'], $_POST['unidad'], $_POST['cantidad'])) {
                    $materialUsado = $_POST['materialUsado'];
                    $unidad = $_POST['unidad'];
                    $cantidad = $_POST['cantidad'];
                    if (!empty($materialUsado) && !empty($unidad) && !empty($cantidad)) {
                        for ($i = 0; $i < count($materialUsado); $i++) {
                            if (!empty($materialUsado[$i])) {
                                $materialFor = $materialUsado[$i];
                                $unidadFor = $unidad[$i];
                                if(empty($unidadFor)){
                                    $unidadFor = NULL;
                                }
                                $cantidadFor = $cantidad[$i];
                                if(empty($cantidadFor)){
                                    $cantidadFor = 0;
                                }
                                $queryMaterial = "INSERT INTO material_orden_general(idMaterial,nomMaterial,unidadMaterial,cantidadMaterial,idOrden) 
                                VALUES(NULL,'" . $materialFor . "','" . $unidadFor . "'," . $cantidadFor . "," . $idOrden . ")";
                                $resMaterial = mysqli_query($conexion, $queryMaterial);

                                if ($resMaterial) {
                                    $_SESSION['usoMaterial'] = 'Usando';
                                } else {
                                    $_SESSION['usoMaterial'] = 'Vacio';
                                }
                            }else {
                                $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                            }
                        }
                    }else {
                        $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                    }
                }else {
                    $_SESSION['usoMaterialSolicitud'] = 'Vacio';
                }

                $emailSolicitante = getEmailSolicitud($idSolicitud);
                if (!empty($emailSolicitante)) {
                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'saladecontrolhbqp@gmail.com';                     //SMTP username
                        $mail->Password   = 'flekwyfqtojlqaht';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;

                        //Set who the message is to be sent from
                        //Note that with gmail you can only use your account address (same as `Username`)
                        //or predefined aliases that you have configured within your account.
                        //Do not use user-submitted addresses in here
                        $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                        //Set an alternative reply-to address
                        //This is a good place to put user-submitted addresses
                        $mail->addReplyTo('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                        //Set who the message is to be sent to
                        $mail->addAddress($emailSolicitante, $nomSolicitante);
                        //$mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
                        $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                        $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');
                        // $mail->addCC('jorge.cantillano@redsalud.gov.cl','Alejandro Cantillano - Jefe de Control Centralizado');

                        //Set the subject line

                        $message  = "<html><body>";

                        $message .= "<table height='100%' width='100%' border='0'>";

                        $message .= "<tr><td>";

                        $message .= "<table width='100%' border='0' style='background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                        $message .= "<thead>";

                        $message .= "</thead>";

                        $message .= "<tbody>";

                        $message .= "<tr>";
                        $message .= "<td><br><h2 align='center'>SISTEMA DE TICKETS DE SOPORTE<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='150' height='150'></h2><hr></td>";


                        $message .= "</tr>";

                        $message .= "<tr>";
                        $message .= "<td>";
                        $message .= "<p style='font-size:20px;' align='center'><b>Estado de Solicitud de Soporte - SDO</b><br></p><br>";
                        $message .= "<p style='font-size:20px;'><b>Estimado(a): " . $nomSolicitante . "</b></p>";
                        $message .= "<p style='font-size:20px;' align='justify'>Su Solicitud de Soporte N# <b>" . $folioSolicitud . "</b> fue Finalizada por " . $datosFinalizador . ".</p>";

                        $message .= "</td>";
                        $message .= "</tr>";

                        $message .= "<tr>";
                        $message .= "<td>";
                        $message .= "<p style='font-size:20px;' align='justify'>Las razones de finalizaci&oacute;n son las siguientes: <br></p>";
                        $message .= "<br></td>";
                        $message .= "</tr>";

                        $message .= "<tr>";
                        $message .= "<td>";
                        $message .= "<p style='font-size:20px;' align='justify'>" . nl2br($conclusionProblema) . "</p>";
                        $message .= "<br></td>";
                        $message .= "</tr>";

                        $message .= "<tr>";
                        $message .= "<td colspan='4'>";
                        $message .= "<p style='font-size:20px;' align='justify'>En caso de no aceptar estos fundamentos, Ud. podra generar otro requerimiento para que sea revisado nuevamente.</p>";
                        $message .= "</td>";
                        $message .= "</tr>";

                        $message .= "<tr>";
                        $message .= "<td>";
                        $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Administrador de Tickets de Soporte - SDO</p>";
                        $message .= "<br></td>";
                        $message .= "</tr>";

                        $message .= "</tbody>";

                        $message .= "</table>";

                        $message .= "</td></tr>";

                        $message .= "</table>";

                        $message .= "</body></html>";

                        //Content

                        $subject = 'Finalización de Solicitud de Soporte #' . $folioSolicitud;

                        $altMessage = 'Estimado(a): Su Solicitud ' . $folioSolicitud . ' se ha sido finalizada.';

                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = $subject;
                        $mail->Body    = $message;
                        $mail->AltBody = $altMessage;

                        if ($mail->send()) {
                            $_SESSION['enviarMail'] = 'Correcto';
                        } else {
                            $_SESSION['enviarMail'] = 'Error';
                        }
                    } catch (Exception $e) {
                        $_SESSION['enviarMail'] = 'Error';
                    }
                } else {
                    $_SESSION['enviarMail'] = 'Error';
                }
                if (!empty($_FILES["archivosOrden"])) {

                    $count = 0;
                    foreach ($_FILES['archivosOrden']['tmp_name'] as $tmpFilee) {
                        if (is_uploaded_file($tmpFilee)) {
                            $count++;
                        }
                    }
                    if ($count > 0) {

                        for ($i = 0; $i < $count; $i++) {
                            $tmp_name = $_FILES["archivosOrden"]["tmp_name"][$i];
                            $name = $_FILES["archivosOrden"]["name"][$i];

                            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                echo "Vacios";
                            } else {

                                $directorio = "../sdo-archivos-orden/" . $folioOrden. '/';
                                if (!file_exists($directorio)) {
                                    mkdir($directorio, 0777, true);
                                }

                                $dir = opendir($directorio);

                                $rutaArchivo = $directorio . $name;

                                $separarNombre = explode(".", $name);

                                $extImagen = end($separarNombre);

                                $extImagen = mb_strtolower($extImagen);

                                if ($extImagen == "pdf" || $extImagen == "doc" || $extImagen == "docx" || $extImagen == "xls" || $extImagen == "xlsx" || $extImagen == "eml") {

                                    if (move_uploaded_file($tmp_name, $rutaArchivo)) {

                                        chmod($rutaArchivo, 0777);
                                        $queryArchivos = "INSERT INTO archivos_orden_general(idArchivo,nomArchivo,rutaArchivo, idOrden) VALUES (NULL,'" . $name . "','" . $rutaArchivo . "','" . $idOrden . "')";
                                        $resArchivos = mysqli_query($conexion, $queryArchivos);
                                        // echo $queryArchivos;
                                        // exit;
                                    } else {
                                        echo "FALLO EN LA SUBIDA DE archivos";
                                    }
                                }
                            }
                        }
                    }
                }
                if (isset($_FILES["archivoImagenOrden"])) {
                    $tot = count($_FILES["archivoImagenOrden"]["name"]);
                    for ($i = 0; $i < $tot; $i++) {
                        $tmp_name = $_FILES["archivoImagenOrden"]["tmp_name"][$i];
                        $name = $_FILES["archivoImagenOrden"]["name"][$i];

                        if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                            echo 'Vacios';
                        } else {

                            $directorio = "../sdo-imagenes-orden/" . $folioOrden . "/";

                            if (!file_exists($directorio)) {
                                mkdir($directorio, 0777);
                            }

                            $dir = opendir($directorio);

                            $ruta = $directorio . $name;

                            $separarNombre = explode(".", $name);

                            $extImagen = end($separarNombre);

                            $extImagen = mb_strtolower($extImagen);

                            if ($extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif" || $extImagen == "pdf" || $extImagen == "docx" || $extImagen == "xlsx") {

                                // if (is_dir($directorio) && is_writable($directorio)) {
                                //     echo 'hola';
                                // } else {
                                //     echo 'Upload directory is not writable, or does not exist.';
                                // }

                                if (move_uploaded_file($tmp_name, $ruta)) {

                                    $queryImagen = "INSERT INTO imagenes_orden_general(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idOrden) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "',0,'" . $idOrden . "')";

                                    $resImagen = mysqli_query($conexion, $queryImagen);

                                    if ($resImagen) {
                                        echo "SI SUBI";

                                    }
                                } else {
                                    echo 'NO SUBI';
                                }
                            } else {
                                echo "FORMATO CORRECTO";
                            }
                        }
                    }
                }else {
                    $_SESSION['usoFotoSolicitud'] = 'Vacio';
                }
                header($headerTextCorrecto);
            }else {
                header($headerError3);
            }
        }else{
            header($headerError3);
        }
    }else{
        header($headerError);
    }
}else{
    header($headerError);
}