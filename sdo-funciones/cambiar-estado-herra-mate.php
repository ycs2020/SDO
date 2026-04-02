<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mail = new PHPMailer(true);

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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idSoli = $_POST['idSoli'];
    $nuevoEstado = $_POST['nuevoEstado'];
    $observaciones = $_POST['observaciones'];
    $codigo = $_POST['codigo'];
    $idDepa  = $_POST['idDepa'];
    $idSolicitante = $_POST['idSolicitante'];
    $fechaTermino = $_POST['fechaTermino'];
    $tipoUsuario = $_POST['tipoUsuario'];

   

    //CONDICIONA LAS CONSULTA SEGÚN TIPO USUARIO
    if($tipoUsuario == 'personal'){

        $tabla = 'personal';
        $condicion = 'idPersonal';
        $nombre = 'nomPersonal';

    } else if($tipoUsuario == 'jefe tecnico'){

        $tabla = 'jefes_tecnicos';
        $condicion = 'idJefe';
        $nombre = 'nomJefe';
    
    }

    //OBTIENES NOMBRE DE SOLICITANTE
    $querySolicitante = "SELECT  * FROM ${tabla} WHERE ${condicion} = ${idSolicitante}";
    $res = mysqli_query($conexion, $querySolicitante);

    while ($row = $res->fetch_assoc()) {

        $datos = [$nombre];

        $datos[$nombre] = $row[$nombre];

        $nomPersonal = $datos[$nombre];

       
    }

    //OBTIENES NOMBRE DE SOLICITANTE
    $queryDepa = "SELECT  * FROM departamentos WHERE idDepartamento = ${idDepa}";
    $resDepa = mysqli_query($conexion, $queryDepa);

    while ($row = $resDepa->fetch_assoc()) {

        $datos = [];

        $datos['nomDepartamento'] = $row['nomDepartamento'];

        $nomDepartamento = $datos['nomDepartamento'];
    }


    $fechaIngreso = $_POST['fecha'];

    //CAMBIA ESTADO

    if ($nuevoEstado == 'Preparado') {

        $estado = 'Preparado';

        $sql = "UPDATE solicitudes_arriendo_retiro SET estadoSolicitud = '${estado}'  , observaciones = '${observaciones}' WHERE idSoli = ${idSoli} ";
        $res = mysqli_query($conexion, $sql);

        $email = getEmail($idSolicitante, $tipoUsuario);


        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saladecontrolhbqp@gmail.com';
            $mail->Password = 'flekwyfqtojlqaht';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

            $mail->addAddress($email);

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "</thead>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>  Estado Solicitud <br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px; ' align= 'center'>Estimado(a): " . $nomPersonal . "</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Junto con saludar, se le notifica el avance de estado de su solicitud: <span style='background-color: yellow;'>" . $codigo . "</span> </p>";
            $message .= "<p style='font-size:20px; font-weight:bold' align='center'>Nuevo Estado: Preparado  </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'> Las herramientas y/o materiales que solicito ya se encuentran preparadas para su retiro, recuerde indicarle su nombre y departamento al pañolero al momento de retirar. </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Saludos cordiales.<br></p>";
            $message .= "<p style='font-size:20px;' align='center'>Bodega Pañol - HBQP<br><br></p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";
            $message .= "</table>";
            $message .= "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Estado Solicitud Arriendo Herramienta';
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo 'error1';
        }

        header('Location: ../' . $ruta . '/listado-arriendo-retiro.php?preparado=true');
    }

    if ($nuevoEstado == 'En Ejecucion') {

        $estado = 'En Ejecucion';

        $sql = "UPDATE solicitudes_arriendo_retiro SET estadoSolicitud = '${estado}'  , observaciones = '${observaciones}' WHERE idSoli = ${idSoli} ";
        $res = mysqli_query($conexion, $sql);
        $tipoUsuario = 'personal';

        $email = getEmail($idSolicitante, $tipoUsuario);

        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saladecontrolhbqp@gmail.com';
            $mail->Password = 'flekwyfqtojlqaht';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

            $mail->addAddress($email);

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "</thead>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>  Estado Solicitud <br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px; ' align= 'center'>Estimado(a): " . $nomPersonal . "</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Junto con saludar, se le notifica el avance de estado de su solicitud: <span style='background-color: yellow;'>" . $codigo . "</span> </p>";
            $message .= "<p style='font-size:20px; font-weight:bold' align='center'>Nuevo Estado: En Ejecucion  </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'> Las herramientas y/o Materiales que usted solicito ya se encuentran en su poder, recuerde que tiene como maximo 5 dias de prestamo en el caso de arrendar la herramienta. </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Saludos cordiales.<br></p>";
            $message .= "<p style='font-size:20px;' align='center'>Bodega Pañol - HBQP<br><br></p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";
            $message .= "</table>";
            $message .= "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Estado Solicitud Arriendo Herramienta';
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo 'error1';
        }

        header('Location: ../' . $ruta . '/listado-arriendo-retiro.php?ejecucion=true');
    }

    if ($nuevoEstado == 'Devolucion Pendiente') {

        $estado = 'Devolucion Pendiente';

        $fechaExpiracion = date("Y-m-d", strtotime($fechaTermino . "+ 2 days"));

        $sql = "UPDATE solicitudes_arriendo_retiro SET estadoSolicitud = '${estado}'  , observaciones = '${observaciones}', fechaTermino = '${fechaExpiracion}' WHERE idSoli = ${idSoli} ";
        $res = mysqli_query($conexion, $sql);

        $tipoUsuario = 'personal';

        //Formatea la fecha
        $fechaExpiracion = date("d/m/Y", strtotime($fechaExpiracion));

        $email = getEmail($idSolicitante, $tipoUsuario);

        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saladecontrolhbqp@gmail.com';
            $mail->Password = 'flekwyfqtojlqaht';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

            $mail->addAddress($email);

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "</thead>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>  Estado Solicitud <br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px; ' align= 'center'>Estimado(a): " . $nomPersonal . "</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Junto con saludar, se le notifica el avance de estado de su solicitud: <span style='background-color: yellow;'>" . $codigo . "</span> </p>";
            $message .= "<p style='font-size:20px; font-weight:bold' align='center'>Nuevo Estado: Devolución Pendiente  </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'> Su solicitud se mantiene con entrega parcial, esto quiere decir que aún no entrega todas las herramientas solicitadas o recurrió a la bodega para solicitar más tiempo. </p>";
            $message .= "<p style='font-size:20px;background-color: yellow' align='center'> Nueva Fecha limite: " . $fechaExpiracion . " </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Saludos cordiales.<br></p>";
            $message .= "<p style='font-size:20px;' align='center'>Bodega Pañol - HBQP<br><br></p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";
            $message .= "</table>";
            $message .= "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Estado Solicitud Arriendo Herramienta';
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo 'error1';
        }


        header('Location: ../' . $ruta . '/listado-arriendo-retiro.php?inconclusa=true');
    }

    if ($nuevoEstado == 'Entrega Definitiva') {

        $estado = 'Entrega Definitiva';
        $fecha = date('Y-m-d');
        $fechaFormateada = date("d/m/Y", strtotime($fecha));
        $tipoUsuario = 'personal';


        //Actualiza el estado de la solicitud
        $sql = "UPDATE solicitudes_arriendo_retiro SET estadoSolicitud = '${estado}'  , observaciones = '${observaciones}' WHERE idSoli = ${idSoli} ";
        $res = mysqli_query($conexion, $sql);

        //OBTIENE LAS HERRAMIENTAS A ACTUALIZAR SU CANTIDAD PARA DEJAR EN -1
        $sqlHerra = "SELECT * FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
        $resHerra = mysqli_query($conexion, $sqlHerra);

        while ($rowHerra = $resHerra->fetch_assoc()) {

            $datos = [];

            $datos['idHerramienta'] = $rowHerra['idHerramienta'];

            $idHerramienta = $datos['idHerramienta'];

            //SI LA CANTIDAD ES -1 QUIERE DECIR QUE LA HERRAMIENTA FUE ENTREGADA DEFINITIVAMENTE
            $nuevaCantidad = -1;
            $tipoActualizacion = 'Entrega Definitiva';

            //INGRESA REGISTRO DE RETIRO PERMANENTE AL HISTORIAL DE LA HERRAMIENTA.
            $detalles = " 
            Datos Entrega Definitiva de la herramienta:
                ->Entregado Por : ${nomUsuario}
                ->Departamento: ${rolUsuario}
                ->Entregado a: ${nomPersonal}
                ->Departamento Solicitante: ${nomDepartamento}
                ->Fecha: ${fechaFormateada}                  
            ";

            $sqlInsert = "INSERT INTO historial_estado_herramienta ( tipoActualizacion,cantidad,idHerramienta,detalles) VALUES ('${tipoActualizacion}', $nuevaCantidad, $idHerramienta, '${detalles}')";
            $resInsert = mysqli_query($conexion, $sqlInsert);


            $queryUpdate = "UPDATE pañol_herramientas SET cantHerramienta = '${nuevaCantidad}' WHERE idHerramienta = '${idHerramienta}'";
            $resUpdate = mysqli_query($conexion, $queryUpdate);
        }

        $email = getEmail($idSolicitante, $tipoUsuario);

        try {

            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saladecontrolhbqp@gmail.com';
            $mail->Password = 'flekwyfqtojlqaht';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

            $mail->addAddress($email);

            $message  = "<html><body>";

            $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

            $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

            $message .= "<thead>";

            $message .= "</thead>";

            $message .= "<tbody>";

            $message .= "<tr>";
            $message .= "<td><br><h2 align='center'>  Estado Solicitud <br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px; ' align= 'center'>Estimado(a): " . $nomPersonal . "</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Junto con saludar, se le notifica el avance de estado de su solicitud: <span style='background-color: yellow;'>" . $codigo . "</span> </p>";
            $message .= "<p style='font-size:20px; font-weight:bold' align='center'>Nuevo Estado: Entrega Definitiva  </p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Las herramientas fueron entregadas definitivamente para su uso personal y de unidad para ejecutar tareas en la institución, lo cual ya no es necesario que las devuelva.</p>";
            $message .= "</td>";
            $message .= "</tr>";

            $message .= "<tr>";
            $message .= "<td colspan='4' style='padding:15px;'>";
            $message .= "<p style='font-size:20px;' align='center'>Saludos cordiales.<br></p>";
            $message .= "<p style='font-size:20px;' align='center'>Bodega Pañol - HBQP<br><br></p>";
            $message .= "<br><br></td>";
            $message .= "</tr>";

            $message .= "</tbody>";
            $message .= "</table>";
            $message .= "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Estado Solicitud Arriendo Herramienta';
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo 'error1';
        }


        header('Location: ../' . $ruta . '/listado-arriendo-retiro.php?entrega=true');
    }

    if ($nuevoEstado == 'Completado') {


        $selectHerra = "SELECT * FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
        $resSelectHerra = mysqli_query($conexion, $selectHerra);


        if ($resSelectHerra) {
            foreach ($resSelectHerra as $indice => $herramienta) {

                $cantidadSacar = $herramienta['cantidadHerramienta'];
                $idHerramienta = $herramienta['idHerramienta'];
                $stockActual = $herramienta['stockActual'];

                //$nuevaCantidad = $stockActual +  $cantidadSacar;

                $herra = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
                $resHerra = mysqli_query($conexion, $herra);

                while ($row = $resHerra->fetch_assoc()) {

                    $cantidadActual = $row['cantHerramienta'];
                }

                $nuevaCantidad = $cantidadActual + $cantidadSacar;

                $updateHerra = "UPDATE pañol_herramientas SET cantHerramienta = ${nuevaCantidad} WHERE idHerramienta = ${idHerramienta}";
                $res = mysqli_query($conexion, $updateHerra);

                ?>
                    <input type="text" value="<?php echo $herramienta['idHerramienta'] ?>">
                <?php
            }



            $estadoSolicitud = "Completado";

            //para ingresar los datos a tabla historial
            $insertHistorial = "INSERT INTO solicitudes_arriendo_retiro_finalizadas (idSoli,codSolicitud, fechaSolicitud, idSolicitante,idDepaSolicitante ,estadoSolicitud, observaciones)  
                VALUES ('" . $idSoli . "','" . $codigo . "','" . $fechaIngreso . "', '" . $idSolicitante . "','" . $idDepa . "','" . $estadoSolicitud . "','" . $observaciones . "')";
            $res1 = mysqli_query($conexion, $insertHistorial);

            $queryEliminar = "UPDATE  solicitudes_arriendo_retiro  SET estadoSolicitud = '$estadoSolicitud', observaciones = '${observaciones}' WHERE idSoli = ${idSoli}";

            // var_dump($queryEliminar);
            // exit;
            $resQueryEliminar = mysqli_query($conexion, $queryEliminar);

            header('Location: ../' . $ruta . '/listado-arriendo-retiro.php?exito=true');
        } else {
            header('Location: ../' . $ruta . '/listado-soli-ticket.php?errorEliminar=true');
        }
    }
}
