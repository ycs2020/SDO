<?php

include 'funciones.php';
include 'conexion.php';

//Load Composer's autoloader

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];


$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
}else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['detalleObservacion'])) {

        $detalleObservacion = $_POST['detalleObservacion'];
        $idSoliCompra = $_POST['idSoliCompra'];

        //insetamos en la base de datos
        $sqlInsert = "INSERT INTO solicitudes_compra_sdo_observaciones (detalleObservacion,creadorObservacion,idSoliCompra) VALUES ('$detalleObservacion', '$nomUsuario', $idSoliCompra)";
        $resInsert = mysqli_query($conexion, $sqlInsert);

        //Obtenemos el el nombre y correo del usuario que creo la solicitud
        $sqlSoli = "SELECT * FROM solicitudes_compra_sdo WHERE idSoliCompra = '$idSoliCompra'";
        $resSoli = mysqli_query($conexion, $sqlSoli);

        while ($rowSoli = $resSoli->fetch_assoc()) {
            $datos = [];

            $datos['personalSoliCompra'] = $rowSoli['personalSoliCompra'];
            $datos['folioSoliCompra'] = $rowSoli['folioSoliCompra'];

            $datos['emailSoliCompra'] = $rowSoli['emailSoliCompra'];

            $personalSoliCompra = $datos['personalSoliCompra'];
            $folioSoliCompra = $datos['folioSoliCompra'];
            $email = $datos['emailSoliCompra'];
        }

        $cantidadNotificación = $notificacion + 1;

        $sqlUpdate = "UPDATE solicitudes_compra_sdo SET notificacion = $cantidadNotificación WHERE idSoliCompra = $idSoliCompra";
        $resUpdate = mysqli_query($conexion, $sqlUpdate);

        $id = mysqli_insert_id($conexion);

        $sqlSelect = "SELECT * FROM solicitudes_compra_sdo_observaciones WHERE idObservacion  = $id";
        $resSelect = mysqli_query($conexion, $sqlSelect);

        while($rowSelect = $resSelect->fetch_assoc()){
            $datos = [];
            $datos['detalleObservacion'] = $rowSelect['detalleObservacion'];

            $detalleObservacion = $datos['detalleObservacion'];

        }

        if ($resSoli) {

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
                $mail->addAddress($email);
//                $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
                

                //Set the subject line

                $message  = "<html><body>";

                $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                $message .= "<tr><td>";

                $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                $message .= "<thead>";

                $message .= "</thead>";

                $message .= "<tbody>";

                $message .= "<tr>";

                $message .= "<td><br><h2 align='center'>Sistema de Solicitudes de Compra<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;'> <br>Estimado(a) " . $personalSoliCompra . ": <br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico que su solicitud folio: <b>" . $folioSoliCompra . "</b>, tiene una nueva observación creada por <b>" .strtoupper($nomUsuario) .  ".</b> <br></p>";
                $message .= "</td>";
                $message .= "</tr>";

            
                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br>Detalles:<br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:25px;' align='center'> <br> <span style='background:yellow;'>' ". $detalleObservacion ." '</span><br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br>Al ingresar con sus credenciales en la <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/plataforma_index.php'>Plataforma SDO</a>, Ud. podra visualizar la informaci&oacute;n completa de su solicitud de compra. <br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='justify'> <br><b>Importante: </b>El seguimiento de la solicitud de compra  solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.<br></p>";
                $message .= "</td>";
                $message .= "</tr>";

                $message .= "<tr>";
                $message .= "<td colspan='4'";
                $message .= "<p style='font-size:20px;' align='center'> <br>Saludos cordiales.<br><br>Administrador de Solicitudes de Compra - SDO.</p>";
                $message .= "<br><br></td>";
                $message .= "</tr>";

                $message .= "</tbody>";

                $message .= "</table>";

                $message .= "</td></tr>";

                $message .= "</table>";

                $message .= "</body></html>";

                //Content
                $subject = 'Nueva Observación de la solicitud de compra #' . $folioSoliCompra;
                $altMessage = 'Nueva observacion';

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = $altMessage;
                $mail->send();
            } catch (Exception $e) {
                header($headerError3 . '6');
            }
            header('Location: ../' . $ruta . '/soliCompra_sdo_administracion.php?exitoObs=true');
        } else {
            header('Location: ../' . $ruta . '/soliCompra_sdo_administracion.php?errorObs=true');
        }
    } else {
        header('Location: ../' . $ruta . '/soliCompra_sdo_administracion.php?noObs=true');
    }
}
