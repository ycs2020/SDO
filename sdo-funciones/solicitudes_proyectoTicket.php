<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');

$fecha_actual = date("d-m-Y");

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
}else if($rolUsuario2 == "oficina tecnica"){
    $ruta = "sdo-oficinaTecnica";
}else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$headerTextCorrecto = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoProyectoTicket=true';
$headerTextCorrectoSinMail = 'Location: ../' . $ruta . '/solicitudes_detalle.php?exitoProyectoTicketSinMail=true';
$headerError = 'Location: ../' . $ruta . '/solicitudes_detalle.php?errorProyectoTicket=true';



if (isset($_POST['idSolicitud'])) {

    $idSolicitud = trim($_POST['idSolicitud']);

    if ($idSolicitud !== 0) {

        if (isset($_POST['nomPersonal'], $_POST['observacionSolicitud'])) {

            $nomPersonal = trim($_POST['nomPersonal']);
            $observacionSolicitud = trim($_POST['observacionSolicitud']);

            if (empty($nomPersonal) || empty($observacionSolicitud) ) {
                header($headerError.'1');
            } else {

                    $_SESSION['idSolicitud'] = $idSolicitud;

                    $getFolioSolicitud = getFolioSolicitud($idSolicitud);

                    $correoSolicitud = getEmailSolicitud($idSolicitud);

                    $nomSolicitud = getNomSolicitanteTicket($idSolicitud);

                    if (!empty($observacionSolicitud)) {
                        $observacionSolicitud = str_replace("'", "\'", $observacionSolicitud);
                    }

                    $observacion = $nomPersonal . ' ha derivado este Ticket a Comisi&oacute;n debido a que se trata de un Proyecto. <br><br>Las razones de esta decisi&oacute;n son las siguientes: <br><br>' . nl2br($observacionSolicitud);

                    $tipoObservaSolicitudes = "Ticket de Proyecto";

                    date_default_timezone_set('America/Santiago');
                    $now = date('Y-m-d H:i:s', time());

                    $sqlObservacion = "INSERT INTO observaciones_tickets(idObservaSolicitudes,detalleObservaSolicitudes,fechaObservaSolicitudes,tipoObservaSolicitudes,creadorObservaSolicitudes,idSolicitud)
                        VALUES(NULL,'" . $observacion . "','" . $now . "','" . $tipoObservaSolicitudes . "','" . $nomPersonal . "'," . $idSolicitud . ")";

                    $resObservacion = mysqli_query($conexion, $sqlObservacion);

                    if ($resObservacion) {

                        $sqlRegObs = "UPDATE solicitudes_soportes SET situacionSolicitud = 'Ticket de Proyecto', detalleTicketProyecto = '".$observacionSolicitud."' , ticketProyecto = 1 WHERE idSolicitud = '" . $idSolicitud . "'";

                        $resRegObs = mysqli_query($conexion, $sqlRegObs);

                        if ($resRegObs) {

//                            try {
//
//                                $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
//                                $mail->isSMTP();                                            //Send using SMTP
//                                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
//                                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//                                $mail->Username   = 'saladecontrolhbqp@gmail.com';                     //SMTP username
//                                $mail->Password   = 'flekwyfqtojlqaht';                               //SMTP password
//                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//                                $mail->Port       = 465;
//
//                                //Set who the message is to be sent from
//                                //Note that with gmail you can only use your account address (same as `Username`)
//                                //or predefined aliases that you have configured within your account.
//                                //Do not use user-submitted addresses in here
//                                $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//
//                                //Set an alternative reply-to address
//                                //This is a good place to put user-submitted addresses
//                                $mail->addReplyTo('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//
//                                //Set who the message is to be sent to
//                                $mail->addAddress($correoSolicitud, $nomSolicitud);
//                                $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
////                                $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');
//                                // $mail->addCC('jorge.cantillano@redsalud.gov.cl','Alejandro Cantillano - Jefe de Control Centralizado');
//
//                                //Set the subject line
//
//                                $message  = "<html><body>";
//
//                                $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";
//
//                                $message .= "<tr><td>";
//
//                                $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
//
//                                $message .= "<thead>";
//
//                                $message .= "</thead>";
//
//                                $message .= "<tbody>";
//
//                                $message .= "<tr>";
//                                $message .= "<td><br><h2 align='center'>Sistema de Tickets de Soporte<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";
//
//
//                                $message .= "</tr>";
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;'>Estimado(a): " . $nomSolicitud . "</p>";
//                                $message .= "</td>";
//                                $message .= "</tr>";
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;' align='justify'>Su Solicitud ha sido catalogada como un Proyecto, por lo cual ha sido derivada a C&oacute;mite.</p>";
//                                $message .= "</td>";
//                                $message .= "</tr>";
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;' align='justify'>Para consultar las razones, debe ingresar al siguiente enlace: <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/soporte_consultaEstado.php'>Comprobar Estado de Solicitud</a></p>";
//                                $message .= "</td>";
//                                $message .= "</tr>";
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;' align='justify'><b>Importante: </b>El seguimiento de este Ticket solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.</p>";
//                                $message .= "</td>";
//                                $message .= "</tr>";
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;' align='justify'>Ante cualquier novedad, lo mantendremos informado.</p>";
//                                $message .= "</td>";
//                                $message .= "</tr>";
//
//
//                                $message .= "<tr>";
//                                $message .= "<td colspan='2' style='padding:15px;'>";
//                                $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Administrador de Tickets de Soporte - SDO</p>";
//                                $message .= "<br><br></td>";
//                                $message .= "</tr>";
//
//                                $message .= "</tbody>";
//
//                                $message .= "</table>";
//
//                                $message .= "</td></tr>";
//
//                                $message .= "</table>";
//
//                                $message .= "</body></html>";
//
//                                //Content
//
//                                $subject = 'Ticket #' . $getFolioSolicitud.' - Proyecto';
//
//                                $altMessage = 'Estimado(a): Su Ticket ' . $getFolioSolicitud . ' fue catalogado como Proyecto, por lo cual se deriva a Comite. Se le notificaran las novedades oportunamente."';
//
//                                $mail->isHTML(true);
//                                $mail->CharSet = 'UTF-8';
//                                $mail->Subject = $subject;
//                                $mail->Body    = $message;
//                                $mail->AltBody = $altMessage;
//
//                                if ($mail->send()) {
//                                    header($headerTextCorrecto);
//                                } else {
//                                    header($headerTextCorrectoSinMail);
//                                }
//                            } catch (Exception $e) {
//                                echo $e->getMessage();
//                            }
                            header($headerTextCorrecto);
                        } else {
                            header($headerError.'2');
                        }
                    } else {
                        header($headerError.'3');
                    }

            }
        } else {
            header($headerError.'4');
        }
    } else {
        header($headerError.'5');
    }
} else {
    header($headerError.'6');
}
