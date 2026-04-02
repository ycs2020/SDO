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
$message .= "<p style='font-size:20px;'>Estimado(a): </p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Junto con saludar, notificarle que a Ud. le fue asignada la Solicitud N# '...', la cual fue ingresada por '...' perteneciente a la Unidad '...', debido a '...' en Ubicaci&oacute;n '...'.</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Al ingresar con sus credenciales en la <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/plataforma_index.php'>Plataforma SDO</a>, Ud. podra visualizar la informaci&oacute;n completa del requerimiento.</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='justify'>Se debe destacar que Ud. debe estar conectado a la Red Administrativa del Hospital Biprovincial Quillota Petorca para verificar la informaci&oacute;n.</p>";
$message .= "</td>";
$message .= "</tr>";


$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='center'>Saludos cordiales.<br><br>'...'</p>";
$message .= "<br><br></td>";
$message .= "</tr>";

$message .= "</tbody>";

$message .= "</table>";

$message .= "</td></tr>";

$message .= "</table>";

$message .= "</body></html>";

echo $message;
