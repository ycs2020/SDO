<?php


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

include("conexion.php");
include("funciones.php");


if (isset($_POST['usuarioRegistrado'])) {
    $usuarioRegistrado = trim($_POST['usuarioRegistrado']);

    if (empty($usuarioRegistrado) || $usuarioRegistrado == '' || $usuarioRegistrado == ' ') {
        header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
    } else {
        if (existePersonal($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Tecnico';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeJefe($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Profesional';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeAdministrador($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Administrador';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeAdmTecnicos($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Administrativo';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeDirectivo($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Directivo';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeMesaAyuda($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Mesa Ayuda';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else if (existeGestDoc($usuarioRegistrado) == 1) {

            $tipoUsuario = 'Gestión Documental';

            $emailUsuario = getEmaiConNomUsuario($usuarioRegistrado, $tipoUsuario);
        } else {

            header('Location: ../sdo-flujo/login_index.php?noExisteUsuario=true');
        }

        if ($tipoUsuario !== 'No Existe') {

            do {
                $numRandom = rand(10000, 9999999);
            } while (existeAleatorioPassword($numRandom) == 1);

            $sqlPassReco = "INSERT INTO password_recovery(idPassReco,userPassReco,tipoUserPassReco,fechaPassReco,aleatorioPassReco,estadoPassReco) ";

            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $sqlPassReco .= "VALUES (NULL,'" . $usuarioRegistrado . "','" . $tipoUsuario . "','".$now."','" . $numRandom . "','En Proceso de Cambio')";

            $resPassReco = mysqli_query($conexion, $sqlPassReco);

            if ($resPassReco) {

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

                    //Set who the message is to be sent to
                    $mail->addAddress($emailUsuario);


                    $mail->isHTML(true);

                    //Set the subject line

                    $message  = "<html><body>";

                    $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                    $message .= "<tr><td>";

                    $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                    $message .= "<thead>";

                    $message .= "</thead>";

                    $message .= "<tbody>";

                    $message .= "<tr>";
                    $message .= "<td><br><h2 align='center'>Sistema de Recuperaci&oacute;n de Contrase&ntilde;a<br>&nbsp;<br><img src='https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEipS9iX7-FEIv4bU732_ylBLZ8jDn9_Q69pauu5-zeyESgbnvXurjC7kk44EshNk5CxGEeonSJtbQsqC_PB-9bIN3uTgqYqFIPAy0iFVoF2YArbjYJuAvdoSzA1-Wl0wEm0mFIHdI9ecPvuU60E7Sci8VUS4vZjMzYsn_pRJoaMNeVODUG6AwXywFsiNQ/s320/controlCent-HBQP.png' alt='Control Centralizado' width='100' height='100'></h2><hr></td>";

                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;'>Estimado(a): </p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='justify'>Su Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a ha sido ingresada con &eacute;xito en nuestro sistema.</p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='justify'>Debe ingresar al siguiente enlace <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/password_restauracion.php?correoPW=true'>Restaurar Credenciales</a> e ingresar el codigo <b>" . $numRandom . "</b> .</p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='justify'><b>Importante: </b>Este proceso debe ser realizado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.</p>";
                    $message .= "</td>";
                    $message .= "</tr>";


                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Soporte de Recuperaci&oacute;n - SDO</p>";
                    $message .= "<br><br></td>";
                    $message .= "</tr>";

                    $message .= "</tbody>";

                    $message .= "</table>";

                    $message .= "</td></tr>";

                    $message .= "</table>";

                    $message .= "</body></html>";

                    //Content

                    $subject = 'Recuperación de Credenciales - Plataforma SDO';

                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = $subject;

                    $mail->Body    = $message;

                    if ($mail->send()) {
                        echo "<script>window.location.assign('../sdo-flujo/login_index.php?exitoEnvioRecuperacion=true')</script>";
                    } else {
                        header('Location: ../sdo-flujo/login_index.php?errorEnvioRecuperacion=true');
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            // echo $sqlPassReco;
        } else {
            header('Location: ../sdo-flujo/login_index.php?noExisteUsuario=true');
        }
    }
} else {
    header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
}
