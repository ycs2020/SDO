<?php


//Load Composer's autoloader
require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

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
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoAsignarPersonal=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorIdSolicitud=true';
$headerError2 = 'Location: ../' . $ruta . '/solicitudes_recibidas.php?errorDepartamentoResponsable=true';
$headerError3 = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorAsignarPersonal=true';

if (isset($_POST['idSolicitud'])) {

    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud !== 0) {

        $_SESSION['idSolicitud'] = $idSolicitud;

        if (isset($_POST['nomPersonal'], $_POST['departamentoResponsable'], $_POST['tecnicoResponsable'])) {

            $nomPersonal = trim($_POST['nomPersonal']);
            $departamentoResponsable = trim($_POST['departamentoResponsable']);
            $tecnicoResponsable = trim($_POST['tecnicoResponsable']);


            if (empty($departamentoResponsable) || empty($tecnicoResponsable || empty($nomPersonal))) {
                header($headerError3.'1');
            } else {
                $esAdministrativo = strpos($tecnicoResponsable, 'Administrativo');

                $esJefe = strpos($tecnicoResponsable, 'Jefe');

                $esPersonal = strpos($tecnicoResponsable, 'Personal');


                $datosSolicitud = array();
                $datosSolicitud = getSolicitudSoporte($idSolicitud);

                $folioSolicitud = $datosSolicitud['folioSolicitud'];

                $nomSolicitante = $datosSolicitud['nomSolicitante'];

                $deptoSolicitante = $datosSolicitud['deptoSolicitante'];

                $ubicacionSolicitud = $datosSolicitud['ubicacionSolicitud'];

                $emailSolicitante = $datosSolicitud['emailSolicitante'];

                $nomAsignado = '';
                $cargoAsignado = '';
                $emailAsignado = '';

                //se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
                if ($esAdministrativo === false) {
                    if ($esJefe === false) {
                        if ($esPersonal === false) {
                            header($headerError3.'2');
                        } else {
                            $separada = explode('Personal', $tecnicoResponsable);
    
                            $datosPersonal = array();
                            $datosPersonal = getDatosPersonal($separada[1]);
    
                            $nomAsignado = $datosPersonal['nomPersonal'];
                            $nomAsignadoCompleto = $datosPersonal['nomPersonal'] . ' (' . $datosPersonal['cargoPersonal'] . ')';
    
                            $emailPersonal = $datosPersonal['emailPersonal'];
    
                            $emailAsignado .= $emailPersonal;
                        }
                    } else {
                        $separada = explode('Jefe', $tecnicoResponsable);

                        $datosJefe = array();
                        $datosJefe = getDatosJefe($separada[1]);

                        $nomAsignado = $datosJefe['nomJefe'];
                        $nomAsignadoCompleto = $datosJefe['nomJefe'] . ' (' . $datosJefe['cargoJefe'] . ')';

                        $emailJefe = $datosJefe['emailJefe'];

                        $emailAsignado .= $emailJefe;
                    }
                } else {
                    $separada = explode('Administrativo', $tecnicoResponsable);

                    $datosAdministrativo = array();
                    $datosAdministrativo = getDatosAdministrativo($separada[1]);

                    $nomAsignado = $datosAdministrativo['nomAdmTecnicos'];
                    $nomAsignadoCompleto = $datosAdministrativo['nomAdmTecnicos'] . ' (Administrativo)';

                    $emailAdministrativo = $datosAdministrativo['emailAdmTecnicos'];

                    $emailAsignado .= $emailAdministrativo;
                }


                $deptoResponsableSQL = getDeptoResponsableSolicitud($idSolicitud);

//                echo $deptoResponsableSQL.'<br><br>';
//                echo $departamentoResponsable.'<br><br>';
//                exit();

                $grupoEquivalente = ['Control Centralizado', 'Instrumentación'];

                if (
                    $deptoResponsableSQL == $departamentoResponsable ||
                    (in_array($deptoResponsableSQL, $grupoEquivalente) && in_array($departamentoResponsable, $grupoEquivalente))
                ){

                    $sqlAsignarTecnico = "UPDATE solicitudes_soportes SET tecnicoSolicitud = '" . $nomAsignadoCompleto . "', situacionSolicitud = 'Solicitud con Personal Asignado'  WHERE idSolicitud = '" . $idSolicitud . "'";
                    $resAsignarTecnico = mysqli_query($conexion, $sqlAsignarTecnico);

                    if ($resAsignarTecnico) {

                        $observacion = 'Se ha asignado a ' . $nomAsignadoCompleto . ' de ' . $departamentoResponsable . ' para Revisión de esta Solicitud';

                        $tipoObservaSolicitudes = "Asignación de Personal";
                        
                        date_default_timezone_set('America/Santiago');
                        $now = date('Y-m-d H:i:s', time());
        
                        $sqlObservacion = "INSERT INTO observaciones_tickets(idObservaSolicitudes,obsPrivada,detalleObservaSolicitudes,fechaObservaSolicitudes,tipoObservaSolicitudes,creadorObservaSolicitudes,idSolicitud)
                            VALUES(NULL,NULL,'" . $observacion . "','".$now."','" . $tipoObservaSolicitudes . "','" . $nomPersonal . "'," . $idSolicitud . ")";
                        $resObservacion = mysqli_query($conexion, $sqlObservacion);

                        if ($resObservacion) {

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
                                $mail->addAddress($emailAsignado, $nomAsignado);
                                //$mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
                                $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                                $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');

                                //Set the subject line

                                $message  = "<html><body>";

                                $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                                $message .= "<tr><td>";

                                $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                                $message .= "<thead>";

                                $message .= "</thead>";

                                $message .= "<tbody>";

                                $message .= "<tr>";

                                $message .= "<td><br><h2 align='center'>Sistema de Solicitudes de Soporte<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";


                                $message .= "</tr>";

                                $message .= "<tr>";
                                $message .= "<td colspan='4'";
                                $message .= "<p style='font-size:20px;'> <br>Estimado(a) " . $nomAsignado . ": <br></p>";
                                $message .= "</td>";
                                $message .= "</tr>";

                                $message .= "<tr>";
                                $message .= "<td colspan='4'";
                                $message .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico que se le ha asignado la Solicitud N# <b>" . $folioSolicitud . "</b>, la cual fue ingresada por " . $nomSolicitante . " perteneciente a " . $deptoSolicitante . ". <br></p>";
                                $message .= "</td>";
                                $message .= "</tr>";

                                $message .= "<tr>";
                                $message .= "<td colspan='4'";
                                $message .= "<p style='font-size:20px;' align='justify'> <br>Al ingresar con sus credenciales en la <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/plataforma_index.php'>Plataforma SDO</a>, Ud. podra visualizar la informaci&oacute;n completa del requerimiento. <br></p>";
                                $message .= "</td>";
                                $message .= "</tr>";

                                $message .= "<tr>";
                                $message .= "<td colspan='4'";
                                $message .= "<p style='font-size:20px;' align='justify'> <br><b>Importante: </b>El seguimiento de este Ticket solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.<br></p>";
                                $message .= "</td>";
                                $message .= "</tr>";


                                $message .= "<tr>";
                                $message .= "<td colspan='4'";
                                $message .= "<p style='font-size:20px;' align='center'> <br>Saludos cordiales.<br><br>Administrador de Tickets de Soporte - SDO.</p>";
                                $message .= "<br><br></td>";
                                $message .= "</tr>";

                                $message .= "</tbody>";

                                $message .= "</table>";

                                $message .= "</td></tr>";

                                $message .= "</table>";

                                $message .= "</body></html>";

                                //Content

                                $subject = 'Asignación de Solicitud de Soporte #' . $folioSolicitud;

                                $altMessage = 'Junto con saludar, le comunico que se le ha asignado la Solicitud N# ' . $folioSolicitud . ' .';

                                $mail->isHTML(true);
                                $mail->CharSet = 'UTF-8';
                                $mail->Subject = $subject;
                                $mail->Body    = $message;
                                $mail->AltBody = $altMessage;

                                if ($mail->send()) {
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
                                        $mail->addCC($emailAsignado, $nomAsignado);
                                       // $mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
                                        $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                                        $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');

                                        //Set the subject line

                                        $message2  = "<html><body>";

                                        $message2 .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                                        $message2 .= "<tr><td>";

                                        $message2 .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                                        $message2 .= "<thead>";

                                        $message2 .= "</thead>";

                                        $message2 .= "<tbody>";

                                        $message2 .= "<tr>";

                                        $message2 .= "<td><br><h2 align='center'>Sistema de Solicitudes de Soporte<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";


                                        $message2 .= "</tr>";

                                        $message2 .= "<tr>";
                                        $message2 .= "<td colspan='4'";
                                        $message2 .= "<p style='font-size:20px;'> <br>Estimado(a) " . $nomSolicitante . ": <br></p>";
                                        $message2 .= "</td>";
                                        $message2 .= "</tr>";

                                        $message2 .= "<tr>";
                                        $message2 .= "<td colspan='4'";
                                        $message2 .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico que su Solicitud N# <b>" . $folioSolicitud . "</b> ha sido derivada al &Aacute;rea T&eacute;cnica correspondiente.  <br></p>";
                                        $message2 .= "</td>";
                                        $message2 .= "</tr>";

                                        $message2 .= "<tr>";
                                        $message2 .= "<td colspan='4'";
                                        $message2 .= "<p style='font-size:20px;' align='justify'> <br>Prontamente sera contactado(a) para entregarle una soluci&oacute;n a su requerimiento.  <br></p>";
                                        $message2 .= "</td>";
                                        $message2 .= "</tr>";


                                        $message2 .= "<tr>";
                                        $message2 .= "<td colspan='4'";
                                        $message2 .= "<p style='font-size:20px;' align='center'> <br>Saludos cordiales.<br><br>Administrador de Tickets de Soporte - SDO.</p>";
                                        $message2 .= "<br><br></td>";
                                        $message2 .= "</tr>";

                                        $message2 .= "</tbody>";

                                        $message2 .= "</table>";

                                        $message2 .= "</td></tr>";

                                        $message2 .= "</table>";

                                        $message2 .= "</body></html>";

                                        //Content

                                        $subject2 = 'Actualización de Solicitud de Soporte #' . $folioSolicitud;

                                        $altMessage2 = 'Junto con saludar, le comunico que su Solicitud N# <b>' . $folioSolicitud . '</b> ha sido derivada al &Aacute;rea T&eacute;cnica correspondiente.';

                                        $mail->isHTML(true);
                                        $mail->CharSet = 'UTF-8';
                                        $mail->Subject = $subject2;
                                        $mail->Body    = $message2;
                                        $mail->AltBody = $altMessage2;

                                        if ($mail->send()) {
                                            header($headerTextCorrecto);
                                        } else {
                                            header($headerError3.'3');
                                        }
                                    } catch (Exception $e) {
                                        header($headerError3.'4');
                                    }


                                } else {
                                    header($headerError3.'5');
                                }
                            } catch (Exception $e) {
                                header($headerError3.'6');
                            }

                            // header($headerTextCorrecto);

                        } else {
                            header($headerError3.'7');
                        }
                        
                    } else {
                        header($headerError3.'8');
                    }
                } else {
                    header($headerError3.'9');
                }
            }
        } else {
            header($headerError3.'10');
        }
    } else {
        header($headerError.'11');
    }
} else {
    header($headerError.'12');
}
