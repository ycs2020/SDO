<?php


$message  = "<html><body>";

$message .= "<table height='100%' width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

$message .= "<tr><td>";

$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

$message .= "<thead>";

$message .= "</thead>";

$message .= "<tbody>";

$message .= "<tr>";
$message .= "<td><br><h2 align='center'>SISTEMAS DE SOLICITUDES DE SOPORTE<br>&nbsp;<br> <input type=image src='../dist/imgcontrolCent-HBQP-80.png'></h2><hr></td>";


$message .= "</tr>";



$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<h2 align='center'>Estado de Solicitud de Soporte - SDO</h2><br>";
$message .= "<p style='font-size:20px;'><b>Estimado(a): </b></p>";
$message .= "<p style='font-size:20px;'>Su Solicitud de Soporte N° se encuentra en el siguiente estado: </p>";

$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td>";
$message .= "<p style='font-size:20px;'>&nbsp;</p>";
$message .= "</td>";
$message .= "</tr>";

$message .= "<tr>";
$message .= "<td colspan='4' style='padding:15px;'>";
$message .= "<p style='font-size:20px;' align='center'>''</p>";
$message .= "<br><br></td>";
$message .= "</tr>";

    
$message .= "</tbody>";

$message .= "</table>";

$message .= "</td></tr>";

$message .= "</table>";


$message .= "</body></html>";

echo $message;
