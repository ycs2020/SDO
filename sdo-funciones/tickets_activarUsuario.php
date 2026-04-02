<?php

include_once 'conexion.php';
include_once 'funciones.php';

require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if(isset($_POST['idUsuariosT'])){

    $idUsuariosT = $_POST['idUsuariosT'];



    if(empty($idUsuariosT) || $idUsuariosT == 0){
        header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarUsuario=true');
    }else{
        $sql = "SELECT * FROM usuarios_tickets WHERE idUsuariosT = $idUsuariosT";
        $res = mysqli_query($conexion,$sql);
        while($row = $res->fetch_assoc()){
            $nomUsuario = $row['nomUsuariosT'];
            $email = $row['emailUsuariosT'];

        }


        date_default_timezone_set('America/Santiago');
        $now = date('Y-m-d H:i:s', time());

        $queryModificar = "UPDATE usuarios_tickets SET activoUsuariosT = 1, fechaActivoUsuariosT = '" . $now . "', usuarioDesacT = 1, personaDesac = NULL WHERE idUsuariosT = " . $idUsuariosT;

        $resModificar = mysqli_query($conexion, $queryModificar);

        if ($resModificar) {
            try{
                $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'saladecontrolhbqp@gmail.com';                     //SMTP username
                $mail->Password   = 'flekwyfqtojlqaht';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;
                $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
                $mail->addReplyTo('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                $mail->addAddress($email, $nomUsuario);
                //$mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
                $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');

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
                $message .= "<p style='font-size:20px;'> <br>Estimado(a) " . $nomUsuario . ": <br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico a UD que se ha logrado la activación de su usuario para el uso de la plataforma de tickets de la Subdirección  de Operaciones (SDO)<br></p>";
                $message .= "</td>";
                $message .= "</tr>";

               /* $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br>Al ingresar con sus credenciales en la <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/plataforma_index.php'>Plataforma SDO</a>, Ud. podra visualizar la informaci&oacute;n completa del requerimiento. <br></p>";
                $message .= "</td>";
                $message .= "</tr>";*/

              /*  $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br><b>Importante: </b>El seguimiento de este Ticket solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.<br></p>";
                $message .= "</td>";
                $message .= "</tr>";*/


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
                $subject = 'Sistema de Activacion de Usuario - Plataforma SDO';

                //$altMessage = 'Junto con saludar, le comunico que se le ha asignado la Solicitud N# ' . $folioSolicitud . ' .';

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
                $mail->Body    = $message;
              //  $mail->AltBody = $altMessage;

                if($mail-> send()){
                    header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?exitoActivarUsuario=true');
                }else{
                    header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarUsuario=true');
                }
            }catch (Exception $e){
                header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarUsuario=true');

            }
        } else {
            header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarUsuario=true');
        }

    }
}else{
    header('Location: ../'.$rutaDireccion . '/usuarios_tickets.php?errorActivarUsuario=true');
}