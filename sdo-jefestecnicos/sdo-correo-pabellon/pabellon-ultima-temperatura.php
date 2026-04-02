<?php


$fecha = obtenerFechaTempPab();
$inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
$inicioHora = date('H:i', strtotime(str_replace('/', '-', $fecha)));

$fechaOrden = $inicioFecha;


$message = "";

$message .= "<html><body>";

$message .= "<p>Estimado(a): </p>";

$message .= "<p>Junto con saludar, de acuerdo a lo solicitado se envía tabla con parámetros de los pabellones con fecha ".$fechaOrden.".</p>";

$message .= "<table cellpadding='5' border='1' style='text-align: center'>";

$message .= "<tr>";

$message .= "<td>N° Pabellon</td>";

$message .= "<td>T° Pabellon</td>";

$message .= "<td>Humedad Pabellon</td>";

$message .= "</tr>";

$fecha = obtenerFechaTempPab();

if($fecha == 'Sin Resultados' || empty($fecha)){
    echo '';
}else{

    for ($i = 2; $i <= 10; $i++) {

        $message .= "<tr>";

        $message .= "<td>".$i."</td>";

        $message .= "<td>".obtenerTempPabxFecha($fecha,$i)." °C</td>";

        $message .= "<td>".obtenerHumedadPabxFecha($fecha,$i)." %RH</td>";

        $message .= "</tr>";

    }
}

$message .= "</table>";

$message .= "<br><p>Saludos cordiales.</p>";

$message .= "<p>".$nomUsuario."<br>".$cargoUsuario." de ".$nomDepartamento."<br>Hospital Biprovincial Quillota Petorca<br>O'Higgins 2200, Quillota. V Región de Valparaíso</p>";

$message .= "</body></html>";