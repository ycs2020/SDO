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


date_default_timezone_set('America/Santiago'); // Asegura la hora local

$horaActual = date('H:i');
$fechaActual = date('Y-m-d H:i:s');
$log = "";
// Función para registrar en historial
function registrarHistorial($mensaje) {
    $archivo = __DIR__ . '/historial_tareas.log';
    file_put_contents($archivo, $mensaje . PHP_EOL, FILE_APPEND);
}

// Función para mostrar tabla según turno
function enviarCorreoTurnos($conexion, $turno) {
    $fechaSQL = date('Y-m-d');
    $fechaSQL2 = date('d-m-Y');
    $fechaTime = strtotime($fechaSQL);
    $fechaEspañol = fechaEspañol($fechaTime);
    $fechaEspañol2 = fechaEspañol2($fechaTime);
    $textoTurno = '';

    if($turno == 'Turno Dia'){
        $textoTurno = '08:30 hrs.';
    }else if($turno == 'Turno Noche'){
        $textoTurno = '20:30 hrs.';
    }else{
        $textoTurno = '00:00 hrs.';
    }

    $sqlBuscar = "SELECT * FROM asistencia_sdo 
                  WHERE fechaAsistencia = '$fechaSQL' 
                  AND turnoAsistencia = '$turno' 
                  ORDER BY deptoAsistencia ASC, personalAsistencia ASC";

    $result = mysqli_query($conexion, $sqlBuscar);

    if (mysqli_num_rows($result) > 0) {
        // Hay registros
        $datos = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $depto = $row['deptoAsistencia'];
            $nombre = $row['personalAsistencia'];

            if (!isset($datos[$depto])) {
                $datos[$depto] = [];
            }

            $datos[$depto][] = $nombre;
        }

//Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);


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
            $mail->addAddress("yorgo.casimis@redsalud.gob.cl", "Yorgo Casimis");
             $mail->addCC('j.acantillano@gmail.com','Jorge Cantillano');
//            $mail->addCC('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');
//                                $mail->addCC('tecnicos.cc.hbqp@redsalud.gob.cl', 'Técnicos de Control Centralizado');
            $mail->AddEmbeddedImage('../dist/img/controlCent-HBQP-120-Orig.png', 'imagenCID');

            //Set the subject line

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "</thead>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>Sistema - Plataforma SDO<br>&nbsp;<br><img src='cid:imagenCID' alt='CCSDO' width='120' height='100'></h2><hr></td>";


            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='2' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;'>Estimados:</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='2' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='justify'>Junto con saludar cordialmente, se adjunta el listado de funcionarios que notificaron su asistencia antes de las <b>$textoTurno</b> correspondientes al <b>$turno</b> del <b>$fechaEspañol</b>.</p>";
            $message .= "<p style='font-size:20px;' align='justify'>A continuación, se detalla la información por departamento:.</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "</tbody></table><br>";


            $message .= "<table align='center' width='100%' border='1' cellpadding='8' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
            $message .= '<tr><th>Departamento</th><th>Personal</th></tr>';

            foreach ($datos as $depto => $nombres) {
                $message .= '<tr>';
                $message .= '<td>' . htmlspecialchars($depto) . '</td>';
                $message .= '<td>';
                $data = implode('<br>', array_map(function($nombre) {
                    return '&#8226; ' . htmlspecialchars($nombre);
                }, $nombres));

                $message .= $data;
                $message .= '</td>';
                $message .= '</tr>';
            }

            $message .= '</table>';

            $message .= "<br><table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td colspan='2' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='justify'>Saludos cordiales.</p>";
            $message .= "</td>";
            $message .= "</tr>";


            $message .= "<tr>";
            $message .= "<td colspan='2' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Administraci&oacute;n - Plataforma SDO</p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";

            $message .= "</table>";

            $message .= "</td></tr>";

            $message .= "</table>";

            $message .= "</body></html>";

            $subject = "$turno - $fechaEspañol2";

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $message;

            if ($mail->send()) {
                return "Correo Enviado";
            } else {
                return "Correo No Enviado";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

    } else {
        // No hay registros
        return "<p>No hay registros para el turno <strong>$turno - $fechaSQL2</strong> en la fecha actual.</p>";
    }
}

// Ejecutar según la hora actual
//switch ($horaActual) {
//    case '09:20':
//    case '21:20':
//        $log = accionGeneral();
//        registrarHistorial($log);
//        break;
//    default:
//        // No hacer nada fuera de horario
//        break;
//}

$log = enviarCorreoTurnos($conexion, 'Turno Dia');

echo $log;
?>