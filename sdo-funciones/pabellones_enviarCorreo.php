<?php

include_once 'conexion.php';
include_once 'funciones.php';

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
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

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

if(isset($_POST['idRegPab'])){

    $idRegPab = $_POST['idRegPab'];

    if(!empty($idRegPab) && $idRegPab > 0){

        $obtenerFechaPabCorreo = obtenerFechaPabCorreo($idRegPab);

        $asuntoo = '';
        if(!empty($obtenerFechaPabCorreo)){
            $fechaCorreo = date('d-m-Y',strtotime($obtenerFechaPabCorreo));
            $horaCorreo = date('H:i',strtotime($obtenerFechaPabCorreo));
            $textoFecha = $fechaCorreo.' a las '.$horaCorreo.' hrs';
            $asuntoo = 'Parametros de Pabellones - Fecha '.$fechaCorreo;
        }else{
            $asuntoo = 'Parametros de Pabellones';
        }

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
            $mail->addAddress('supervision.pabellon.hbqp@gmail.com', 'Supervision Pabellon');
            $mail->addAddress('coordinacionsdgchbqp@gmail.com', 'Coordinacion SDGC HBQP');
            $mail->addAddress('atencionabiertasdgc@gmail.com', 'Atencion Abierta Carolina Oyanadel');
            $mail->addAddress('bloquequirurgicosdgc@gmail.com', 'bloquequirurgicosdgc@gmail.com');
            $mail->addAddress('atencioncerradasdgc@gmail.com', 'Coordinación Atención Cerrada');
            $mail->addAddress('yanezlorena328@gmail.com', 'Lorena Yañez');
//            $mail->addAddress('pablotomas053@gmail.com', 'Pablo Sanchez');
//            $mail->addAddress('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
            $mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
            $mail->addCC('pablotomas053@gmail.com', 'Pablo Sanchez');
//            $mail->addCC('isaac.perez.rojas@gmail.com', 'Isaac Pérez Rojas');
            $mail->addCC('gustavo.loira.a@redsalud.gob.cl', 'Gustavo Loira');
            $mail->addCC('victor.fierro72@gmail.com', 'Victor Fierro');
            $mail->addCC('rafael.mendoza.saavedra@gmail.com', 'Rafael Mendoza');
            $mail->addCC('rafael.mendoza@redsalud.gob.cl', 'Rafael Mendoza');
            $mail->addCC('j.beltransaldias@gmail.com', 'Javier Beltran');
            $mail->addCC('jorge.cantillano@redsalud.gob.cl', 'Jorge Cantillano');
            $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

            //Set the subject line

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>Par&aacute;metros de Pabellones<br>&nbsp;<br><img src='cid:imagen_adjunta' alt='Control Centralizado HBQP' width='150' height='100'></h2><hr></td>";


            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='2'>";
            $message .= "<p style='font-size:12px;'>Estimados(as):</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='2'>";
            $message .= "<p style='font-size:12px;' align='justify'>Junto con saludar, de acuerdo a lo solicitado se env&iacute;a tabla con Par&aacute;metros de los Pabellones del d&iacute;a ".$textoFecha.".</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "</tbody>";

            $message .= "</table>";



            $message .= "<table align='center' width='100%' border='1' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "<tr>";

            $message .= "<td>";
            $message .= "<p style='font-size:12px;' align='center'>N° Pabell&oacute;n</p>";
            $message .= "</td>";

            $message .= "<td>";
            $message .= "<p style='font-size:12px;' align='center'>T° Pabell&oacute;n</p>";
            $message .= "</td>";

            $message .= "<td>";
            $message .= "<p style='font-size:12px;' align='center'>Humedad Pabell&oacute;n</p>";
            $message .= "</td>";

            $message .= "</tr>";

            $message .= "</thead>";

            $message .= "<tbody>";

            for ($i = 2; $i <= 10; $i++) {

                $paramTemp = "tempPabellon".$i;
                $humedadTemp = "humedadPabellon".$i;

                $tempe = obtenerParametroPabCorreo($paramTemp,$idRegPab);
                $humedad = obtenerParametroPabCorreo($humedadTemp,$idRegPab);

                if ($tempe > 1) {
                    $tempe = $tempe . ' °C';
                } else {
                    $tempe = 'No Aplica';
                }

                if ($humedad > 1) {
                    $humedad = $humedad . ' %RH';
                } else {
                    $humedad = 'No Aplica';
                }

                $message .= "<tr>";

                $message .= "<td>";
                $message .= "<p style='font-size:12px;' align='center'>".$i."</p>";
                $message .= "</td>";

                $message .= "<td>";
                $message .= "<p style='font-size:12px;' align='center'>".$tempe."</p>";
                $message .= "</td>";

                $message .= "<td>";
                $message .= "<p style='font-size:12px;' align='center'>".$humedad."</p>";
                $message .= "</td>";

                $message .= "</tr>";

            }

            $message .= "</tbody>";

            $message .= "</table>";


            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td colspan='2' style='padding:15px;'>";
            $message .= "<p style='font-size:12px;' align='center'>Atte.<br><br>Sala de Control Centralizado</p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";

            $message .= "</table>";


            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<tbody>";

            $message .= "</body></html>";


            // Adjuntar imagen
            $ruta_imagen = '../sdo-administrador/img/controlCent-HBQP.png';
            $mail->addEmbeddedImage($ruta_imagen, 'imagen_adjunta');
            //Content

            $subject = 'Parametros de Pabellones';
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asuntoo;
            $mail->Body    = $message;

            if ($mail->send()) {

                $sqlBloqueo = 'UPDATE pabellones_correo SET correoEnviado = 1 WHERE idRegistroPab = '.$idRegPab;

                if(mysqli_query($conexion, $sqlBloqueo)){
                    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoCorreoEnviado=true');
                }else{
                    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?exitoCorreoEnviado=true');
                }

            } else {
                header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorCorreoEnviado=true');
            }

//            echo $message;
        } catch (Exception $e) {
            header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorCorreoEnviado=true');
        }
    }else{
        header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorIDCorreoEnviado=true');
    }


}else{
    header('Location: ../' . $rutaDireccion . '/visualizardetalles.php?errorIDCorreoEnviado=true');
}

