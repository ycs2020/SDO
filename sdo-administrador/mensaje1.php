<?php
$message  = "<html><body>";

$message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

    $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                $message .= "<thead>";

                $message .= "</thead>";

                $message .= "<tbody>";

                $message .= "<tr>";

                    $message .= "<td><br><h2 align='center'>Sistema de Solicitudes de Soporte<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;'> <br>Estimado(a) " . $nomUsuario . ": <br></p>";
                    $message .= "</td>";
        $message .= "</tr>";

    $message .= "<tr>";
        $message .= "<td colspan='4'";
        $message .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico a UD que se ha logrado la activación de su usuario para el uso de la plataforma de tickets de la Subdirección de Operaciones (SDO)<br></p>";
        $message .= "</td>";
        $message .= "</tr>";

    $message .= "<tr>";
        $message .= "<td colspan='4'";
        $message .= "<p style='font-size:20px;' align='center'> <br>Saludos cordiales.<br><br>Administrador de Tickets de Soporte - SDO.</p>";
        $message .= "<br><br></td>";
        $message .= "</tr>";

    $message .= "</tbody>";

    $message .= "</table>";

$message .= "</td></tr>";

$message .= "</table>";

$message .= "</body></html>";

echo $message;