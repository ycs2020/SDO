<?php
$message  = "<html><body>";

$message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

    $message .= "<tr><td>";

            $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                $message .= "<thead>";

                $message .= "</thead>";

                $message .= "<tbody>";

                $message .= "<tr>";
                    $message .= "<td><br><h2 align='center'>Sistema de Recuperaci&oacute;n de Contrase&ntilde;a para Usuario de Ticket<br>&nbsp;<br><img src='https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEipS9iX7-FEIv4bU732_ylBLZ8jDn9_Q69pauu5-zeyESgbnvXurjC7kk44EshNk5CxGEeonSJtbQsqC_PB-9bIN3uTgqYqFIPAy0iFVoF2YArbjYJuAvdoSzA1-Wl0wEm0mFIHdI9ecPvuU60E7Sci8VUS4vZjMzYsn_pRJoaMNeVODUG6AwXywFsiNQ/s320/controlCent-HBQP.png' alt='Control Centralizado' width='100' height='100'></h2><hr></td>";

                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;'>Estimado(a): $nombre</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'>Su Solicitud de Recuperaci&oacute;n de Contrase&ntilde;a ha sido ingresada con &eacute;xito en nuestro sistema.</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'>Debe ingresar al siguiente enlace <a style='color:#2198a3;' href='http://sdo.hospitalquillota.cl/sdo-flujo/password_restauracion_tickets.php?correoPWtickets=true'>Restaurar Constraseña</a> e ingresar el codigo <b>" . $numRandom . "</b> .</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'><b>Importante: </b>Este proceso debe ser realizado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.</p>";
                        $message .= "</td>";
                    $message .= "</tr>";


                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='center'>Atte.<br><br>Soporte de Recuperaci&oacute;n - SDO</p>";
                        $message .= "<br><br></td>";
                    $message .= "</tr>";

                $message .= "</tbody>";

                $message .= "</table>";

            $message .= "</td></tr>";

    $message .= "</table>";

$message .= "</body></html>";

echo $message;

