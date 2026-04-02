<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
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

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.sistemasdo.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'tecnicos@sistemasdo.com';                     //SMTP username
    $mail->Password   = 'controlsdo2022';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Recipients
    $mail->setFrom('saladecontrolhbqp@gmail.com', 'Tecnicos - Control Centralizado');
    $mail->addCC('yorgo.casimis@redsalud.gob.cl', 'Yorgo Casimis');
    // $mail->addAddress('tecnicos@sistemasdo.com');
    // $mail->addAddress('saladecontrolhbqp@gmail.com');               
    $mail->addReplyTo('saladecontrolhbqp@gmail.com', 'Tecnicos - Control Centralizado');
    $mail->addCC('tecnicos@sistemasdo.com');



    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Probando Correo';
    $mail->Body    = $message;
    $mail->AltBody = 'Este es un mensaje de prueba para Not-HTML';

    $mail->send();
    echo 'Mensaje Enviado';
} catch (Exception $e) {
    echo "Error1: {$mail->ErrorInfo}";
}
