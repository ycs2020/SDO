<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


$mail = new PHPMailer(true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("conexion.php");
include("funciones.php");

if(isset($_POST['usuarioRegistrado'])){
    $usuarioRegistrado = trim($_POST['usuarioRegistrado']);
    if (empty($usuarioRegistrado) || $usuarioRegistrado == '' || $usuarioRegistrado == ' ') {
        header('Location: ../sdo-flujo/login_index.php?errorLogin=true');
    }else {
            $sqlActivo = "SELECT * FROM usuarios_tickets WHERE rutUsuariosT = '$usuarioRegistrado'";
            echo $sqlActivo;
            exit;
            $resActivo = mysqli_query($conexion,$sqlActivo);
            while($rowActivo = $resActivo->fetch_assoc()){
                    $activo = $rowActivo['activoUsuariosT'];

                if($activo == '0'){
                    header('Location: ../sdo-flujo/login_index.php?errorActivacion=true');
                }
            }


        if (existeUsuarioTicket($usuarioRegistrado) == '' || empty($usuarioRegistrado)) {
            header('Location: ../sdo-flujo/soporte_index.php?noExisteUsuario=true');
        }else{
            $sql = "SELECT * FROM usuarios_tickets WHERE rutUsuariosT = $usuarioRegistrado ";
            $res = mysqli_query($conexion,$sql);
            while($row = $res->fetch_assoc()){
                $email = $row['emailUsuariosT'];
                $nombre = $row['nomUsuariosT'];
                $cargo = $row['cargoUsuariosT'];
            }

            do {
                $numRandom = rand(10000, 9999999);
            } while (existeAleatorioPassword($numRandom) == 1);

            $sqlInsert = "INSERT INTO password_recovery_tickets(idPassTicket,rutPassTicket,userPassTicket,cargoPassTicket,fechaPassTicket,aleatorioPassTicket,estadoPassTicket)";
            date_default_timezone_set('America/Santiago');
            $now = date('Y-m-d H:i:s', time());

            $sqlInsert .= "VALUES (NULL,'" . $usuarioRegistrado . "','" . $nombre . "','" . $cargo . "','".$now."','" . $numRandom . "','En Proceso de Cambio')";

            $resInsert = mysqli_query($conexion,$sqlInsert);
            if ($resInsert) {
                try {
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'saladecontrolhbqp@gmail.com';                     //SMTP username
                    $mail->Password   = 'flekwyfqtojlqaht';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;

                    $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                    $mail->addAddress($email);

                    $mail->isHTML(true);

                    $message  = "<html><body>";

                    $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                    $message .= "<tr><td>";

                    $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                    $message .= "<thead>";

                    $message .= "</thead>";

                    $message .= "<tbody>";

                    $message .= "<tr>";
                    $message .= "<td><br><h2 align='center'>Sistema de Recuperaci&oacute;n de Contrase&ntilde;a para Usuario de Ticket<br>&nbsp;<br><img src='https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEipS9iX7-FEIv4bU732_ylBLZ8jDn9_Q69pauu5-zeyESgbnvXurjC7kk44EshNk5CxGEeonSJtbQsqC_PB-9bIN3uTgqYqFIPAy0iFVoF2YArbjYJuAvdoSzA1-Wl0wEm0mFIHdI9ecPvuU60E7Sci8VUS4vZjMzYsn_pRJoaMNeVODUG6AwXywFsiNQ/s320/controlCent-HBQP.png' alt='Control Centralizado' width='100' height='100'></h2><hr></td>";

                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;'>Estimado(a): $nombre</p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='justify'>Su Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a ha sido ingresada con &eacute;xito en nuestro sistema.</p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                    $message .= "<p style='font-size:20px;' align='justify'>Debe ingresar al siguiente enlace <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/password_restauracion_tickets.php?correoPWtickets=true'>Restaurar Constraseña</a> e ingresar el codigo <b>" . $numRandom . "</b> .</p>";
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

                    $subject = 'Recuperación de Cuenta de Tickets - Plataforma SDO';

                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = $subject;

                    $mail->Body    = $message;

                    if ($mail->send()) {
                        echo "<script>window.location.assign('../sdo-flujo/soporte_index.php?exitoEnvioRecuperacion=true')</script>";
                    } else {
                        header('Location: ../sdo-flujo/login_index.php?errorEnvioRecuperacion=true');
                    }

                }catch (Exception $e){
                    echo $e->getMessage();
                }
            }
        }

    }

}


