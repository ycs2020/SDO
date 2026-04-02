
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
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;'>Estimado(a): " . $nomUsuariosT . "</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'>Junto con saludar, comentarle que sus datos se han ingresado con &eacute;xito.</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'>Dentro de <a style='color:#f5b043'>24 a 48 horas</a> h&aacute;biles, se habilitara su acceso para la creaci&oacute;n de tickets.</p>";
                        $message .= "</td>";
                    $message .= "</tr>";

                $message .= "<tr>";
                    $message .= "<td colspan='2' style='padding:15px;'>";
                        $message .= "<p style='font-size:20px;' align='justify'>Ante cualquier novedad, lo(a) mantendremos informado(a).</p>";
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

echo $message;