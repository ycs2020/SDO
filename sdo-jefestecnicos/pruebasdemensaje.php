<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


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
$message .= "<p style='font-size:20px;'>Estimado(a): " . $nomSolicitud . "</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='2' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Su Solicitud ha pasado al estado de Programaci&oacute;n de Trabajo.</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='2' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>El día que se Programó su Ticket es el siguiente:</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='2' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Para consultar las razones, debe ingresar al siguiente enlace: <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/soporte_consultaEstado.php'>Comprobar Estado de Solicitud</a></p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='2' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'><b>Importante: </b>El seguimiento de este Ticket solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='2' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Ante cualquier novedad, lo mantendremos informado.</p>";
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



echo $message;
