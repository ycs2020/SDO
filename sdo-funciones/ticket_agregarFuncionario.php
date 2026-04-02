<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

include('funciones.php');
include_once('conexion.php');

if(isset($_POST['rutUsuariosT'],$_POST['nomUsuariosT'],$_POST['passUsuariosT'],$_POST['unidadUsuariosT'],$_POST['cargoUsuariosT'],$_POST['emailUsuariosT'],$_POST['telefonoUsuariosT'])){

    $rutUsuariosT = trim($_POST['rutUsuariosT']);
    $nomUsuariosT = trim($_POST['nomUsuariosT']);
    $passUsuariosT = trim($_POST['passUsuariosT']);
    $unidadUsuariosT = trim($_POST['unidadUsuariosT']);
    $cargoUsuariosT = trim($_POST['cargoUsuariosT']);
    $emailUsuariosT = trim($_POST['emailUsuariosT']);
    $telefonoUsuariosT = trim($_POST['telefonoUsuariosT']);

    if(!empty($rutUsuariosT) && !empty($nomUsuariosT) && !empty($passUsuariosT) && !empty($unidadUsuariosT) && !empty($cargoUsuariosT) && !empty($emailUsuariosT) && !empty($telefonoUsuariosT)){

        if($unidadUsuariosT == 'OTRO'){

            $otraUnidadUsuariosT = $_POST['otraUnidadUsuariosT'];

            if(empty($otraUnidadUsuariosT)){
                header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT1=true');
            }


            $unidadUsuariosT = $otraUnidadUsuariosT;

        }

        $unidadUsuariosT = mb_convert_case($unidadUsuariosT,MB_CASE_UPPER,'UTF-8');

        $sql = "INSERT INTO usuarios_tickets(idUsuariosT, nomUsuariosT, rutUsuariosT, passUsuariosT, emailUsuariosT, cargoUsuariosT, unidadUsuariosT, telefonoUsuariosT, ccaaUsuariosT, personalUsuariosT, activoUsuariosT, fechaCCAAUsuariosT, fechaPersonalUsuariosT, fechaActivoUsuariosT,fechaDesacUsuarioT,usuarioDesacT,personaDesac) VALUES (NULL,'".$nomUsuariosT."','".$rutUsuariosT."','".$passUsuariosT."','".$emailUsuariosT."','".$cargoUsuariosT."','".$unidadUsuariosT."','".$telefonoUsuariosT."',0,0,0,0,0,0,0,0,NULL)";

        $res = mysqli_query($conexion, $sql);

        if ($res) {
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
                $mail->addAddress($emailUsuariosT, $nomUsuariosT);
                $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');

                //Set the subject line

                $message  = "<html><body>";

                $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                $message .= "<tr><td>";

                $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                $message .= "<thead>";

                $message .= "</thead>";

                $message .= "<tbody>";

                $message .= "<tr>";
                $message .= "<td><br><h2 align='center'>Sistema de Tickets de Soporte<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";


                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='2' style='padding:15px;'>";
                $message .= "<p style='font-size:20px;'>Estimado(a): " . $nomUsuariosT . "</p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='2' style='padding:15px;'>";
                $message .= "<p style='font-size:20px;' align='justify'>Junto con saludar, comentarle que sus datos se han ingresado con &eacute;xito.</p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='2' style='padding:15px;'>";
                $message .= "<p style='font-size:20px;' align='justify'>Dentro de 24 a 48 horas h&aacute;biles, se habilitara su acceso para la creaci&oacute;n de tickets.</p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='2' style='padding:15px;'>";
                $message .= "<p style='font-size:20px;' align='justify'>Ante cualquier novedad, lo(a) mantendremos informado(a).</p>";
                $message .= "</td>";
                $message .= "</tr>";


                $message .= "<tr>";
                $message .= "<td colspan='2' style='padding:15px;'>";
                $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Administrador de Tickets de Soporte - SDO</p>";
                $message .= "<br><br></td>";
                $message .= "</tr>";

                $message .= "</tbody>";

                $message .= "</table>";

                $message .= "</td></tr>";

                $message .= "</table>";

                $message .= "</body></html>";

                //Content

                $subject = 'Ingreso de Datos en Sistema de Tickets';

                $altMessage = 'Estimado(a): Junto con saludar, comentarle que sus datos se han ingresado con exito. Dentro de 24 a 48 horas habiles, se habilitara su acceso para la creacion de tickets, debido a que se debe verificar su vonculo con el Hospital Biprovincial Quillota Petorca."';

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = $altMessage;

                if ($mail->send()) {
                    header('Location: ../sdo-flujo/soporte_verificarRUT.php?exitoGenerarUsuariosT=true');
                } else {
                    header('Location: ../sdo-flujo/soporte_verificarRUT.php?exitoGenerarUsuariosTSinMail=true');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                header('Location: ../sdo-flujo/soporte_verificarRUT.php?exitoGenerarUsuariosTSinMail=true');
            }
        } else {
           /* header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT2=true');*/
            echo $sql;

        }
    }else{
        header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT3=true');
    }


}else{
    header('Location: ../sdo-flujo/soporte_verificarRUT.php?errorGenerarUsuariosT4=true');
}