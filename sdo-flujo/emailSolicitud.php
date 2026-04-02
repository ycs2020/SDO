<?php

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
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;'>Estimado(a): " . $nomSolicitud . "</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Su Solicitud ha sido ingresada con &eacute;xito en nuestro sistema y se le ha asignado el N# <b>" . $folioSolicitud . "</b>.</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Con este n&uacute;mero podra consultar las actualizaciones en el siguiente enlace: <a href='http://sdo.hospitalquillota.cl/sdo-flujo/soporte_consultaEstado.php'>Comprobar Estado de Solicitud</a></p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Se debe destacar que Ud. debe estar conectado a la Red Administrativa del Hospital Biprovincial Quillota Petorca para verificar la informaci&oacute;n.</p>";
$message .= "</td>";
$message .= "</tr>";


$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Administrador de Tickets de Soporte - SDO</p>";
$message .= "<br><br></td>";
$message .= "</tr>";

$message .= "</tbody>";

$message .= "</table>";

$message .= "</td></tr>";

$message .= "</table>";

$message .= "</body></html>";

echo $message;
