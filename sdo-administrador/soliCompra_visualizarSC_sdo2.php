<?php ob_start();

$LogoSSVQ = "TCPDF/examples/images/LogoSSVQ.png";
$LogoSSVQ2 = "data:image/png;base64," . base64_encode(file_get_contents($LogoSSVQ));

$controlCent = "TCPDF/examples/images/controlCent-HBQP-120.jpg";
$controlCent2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCent));

$controlCentFull = "TCPDF/examples/images/controlCent-HBQP-Full-2.jpg";
$controlCentFull2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCentFull));


include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra'])) {

    $idSoliCompra = trim($_POST['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion.php?errorSolicitudCompra=true');
}

$datosSoliCompra = array();
$datosSoliCompra = getSoliCompra_sdo($idSoliCompra);

$idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
$numSoliCompra = $datosSoliCompra['numSoliCompra'];
$folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
$fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
$personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
$datosPersonalSoliCompra = explode(' (', $personalSoliCompra);
$idSolicitudSoporte = $datosSoliCompra['idSolicitudSoporte'];

if (!empty($idSolicitudSoporte)) {
    $sqlTicket = "SELECT * FROM solicitudes_soportes WHERE idSolicitud  = $idSolicitudSoporte";
    $resTicket = mysqli_query($conexion, $sqlTicket);

    while ($rowTicket = $resTicket->fetch_assoc()) {
        $datos = [];
        $datos['folioSolicitud'] = $rowTicket['folioSolicitud'];
        $folioSolicitud = $datos['folioSolicitud'];
    }
}

$unidadSoliCompra = $datosSoliCompra['unidadSoliCompra'];
$emailSoliCompra = $datosSoliCompra['emailSoliCompra'];
$isCotizacion = $datosSoliCompra['isCotizacion'];

$argumentosSoliCompra = $datosSoliCompra['argumentosSoliCompra'];

$adjuntaCotizacion = $datosSoliCompra['adjuntaCotizacion'];

$siCotiza = '';

$noCotiza = '';

if ($adjuntaCotizacion == 1) {

    $siCotiza = 'X';
} else if ($adjuntaCotizacion == 0) {

    $noCotiza = 'X';
} else {

    $noCotiza = 'X';
}

$estadoSoliCompra = $datosSoliCompra['estadoSoliCompra'];

$fechaSC = strtotime($fechaSoliCompra);
$fechaSC = fechaEspañol2($fechaSC);

$horaSC = date('H:i', strtotime($fechaSoliCompra));


if ($numSoliCompra == 0) {
    $numSoliCompra = "";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $folioSoliCompra; ?></title>
    <style>
        /** Define the margins of your page **/
        @page {
            margin-left: 2cm;
            margin-right: 2cm;
            margin-top: 2.5cm;
            margin-bottom: 2.5cm;
        }

        header {
            position: fixed;
            top: -140px;
            left: 0;
            right: 0;
            height: 150px;
            /*background-color: #ccc;*/
        }

        /**
        * Define the width, height, margins and position of the watermark.
        **/

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: right;
        }

        table {
            border-left: 0.01em solid #000000;
            border-right: 0;
            border-top: 0.01em solid #000000;
            border-bottom: 0;
            border-collapse: collapse;
        }

        table td,
        table th {
            border-left: 0;
            border-right: 0.01em solid #000000;
            border-top: 0.01em solid #000000;
            border-bottom: 0.01em solid #000000;
        }

        footer .page:after {
            content: counter(page);
        }

    </style>
</head>

<footer style="font-size: 13px; text-align: left;">
    <?php echo $folioSoliCompra ?>
</footer>

<footer style="font-size: 13px;">
    <p class="page"></p>
</footer>
<body>
<!-- Define header and footer blocks before your content -->

<!-- Wrap the content of your PDF inside a main tag -->
<main>

    <h4 style="text-align:left;">HOSPITAL SAN MARTIN<br>QUILLOTA</h4>

    <br>
    <h4 style="text-align:center;">SOLICITUD DE COMPRA N° <?php echo $numSoliCompra; ?></h4>

    <br>
    <table style="vertical-align:middle; text-align:left; font-size: 17px; width:100%;" cellpadding="8" border="0">

        <tr>
            <td style="vertical-align:middle; width:30%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff;"><b>Fecha Solicitud: </b></td>
            <td style="vertical-align:middle; width:70%; text-align:justify;"><?php echo $fechaSC; ?></td>
            <br>
        </tr>
    </table>
    <br>
    <table style="vertical-align:middle; text-align:left; font-size: 16px; width:100%;" cellpadding="8" border="0">
        <tr>
            <td style="vertical-align:middle; width:30%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff;"><b>Solicitado por: </b></td>
            <td style="vertical-align:middle; width:70%; text-align:justify;"><?php echo $datosPersonalSoliCompra[0]; ?></td>
            <br>
        </tr>
    </table>
    <br>
    <table style="vertical-align:middle; text-align:left; font-size: 16px; width:100%;" cellpadding="8" border="0">
        <tr>
            <td style="vertical-align:middle; width:30%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff;"><b>Unidad o Servicio: </b></td>
            <td style="vertical-align:middle; width:70%; text-align:justify;"><?php echo $unidadSoliCompra; ?></td>
            <br>
        </tr>
    </table>
    <br>
    <table style="vertical-align:middle; text-align:left; font-size: 16px; width:100%;" cellpadding="8" border="0">
        <tr>
            <td style="vertical-align:middle; width:30%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff;"><b>Correo Electrónico: </b></td>
            <td style="vertical-align:middle; width:70%; text-align:justify;">secretaria.suboperacioneshbqp2@gmail.com</td>
            <br>
        </tr>
    </table>

    <?php


    $contarArticuloSC = contarMaterialesSCSDO($idSoliCompra);

    if ($contarArticuloSC > 0) {
        ?>
        <br>
        <h4 style="text-align:justify;">Describa el bien y/o servicio a adquirir claramente:</h4>


        <table style="vertical-align:middle; text-align:left; font-size: 17px; width:100%;" cellpadding="8" border="0">

            <tr>
                <td style="vertical-align:middle; text-align:center; width:15%; font-weight:bold; font-size: 14px;">Cantidad</td>
                <td style="vertical-align:middle; text-align:center; width:40%; font-weight:bold; font-size: 14px;">Nombre</td>
                <td style="vertical-align:middle; text-align:center; width:25%; font-weight:bold; font-size: 14px;">Medida</td>
                <td style="vertical-align:middle; text-align:center; width:20%; font-weight:bold; font-size: 14px;">Valor Total<br>Referencial</td>
            </tr>
            <?php


            $queryArticuloSC = 'SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = ' . $idSoliCompra . ' ORDER BY nomArticuloSC ASC';


            $resArticuloSC = mysqli_query($conexion, $queryArticuloSC);

            $numMaterialSC = 1;

            while ($rowArticuloSC = $resArticuloSC->fetch_assoc()) {
                $valorSC = number_format($rowArticuloSC['valorSC'], 0, ",", ".");
                ?>

                <tr nobr="true">
                    <td style="vertical-align:middle; text-align:center; width:15%; font-size: 14px;"><?php echo $rowArticuloSC['cantidadArticuloSC']; ?></td>
                    <td style="vertical-align:middle; text-align:center; width:40%; font-size: 14px;"><?php echo nl2br($rowArticuloSC['nomArticuloSC']); ?></td>
                    <td style="vertical-align:middle; text-align:center; width:25%; font-size: 14px;"><?php echo $rowArticuloSC['medidaSC']; ?></td>
                    <td style="vertical-align:middle; text-align:center; width:20%; font-size: 14px;"><?php echo $valorSC; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    ?>

    <div style="page-break-before: always;">
        <table style="width: 50%;  border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">

            <tr>

                <td style="width:25%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">&nbsp;</td>
                <td style="width:15%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">S&iacute;</td>
                <td style="width:5%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">&nbsp;</td>
                <td style="width:15%; text-align:center; border-top: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">No</td>

            </tr>

            <?php
            if ($adjuntaCotizacion == 1) {
                ?>
                <td style="width:25%; font-size:15px; vertical-align:middle; text-align:left; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; ">Adjunta Archivos</td>
                <td style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
                <td style="width:5%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; ">&nbsp;</td>
                <td style="width:15%;"></td>
                <?php
            } else {
                ?>
                <td style="width:25%; font-size:15px; vertical-align:middle; text-align:left; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; ">Adjunta Archivos</td>
                <td style="width:15%; font-size:20px;"></td>
                <td style="width:5%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; ">&nbsp;</td>
                <td style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
                <?php
            }
            ?>

        </table>
        <br>
        <table style="width: 50%;  border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">

            <tr>

                <td style="width:25%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">&nbsp;</td>
                <td style="width:15%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">S&iacute;</td>
                <td style="width:5%; text-align:center; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">&nbsp;</td>
                <td style="width:15%; text-align:center; border-top: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">No</td>

            </tr>

            <?php
            if ($isCotizacion == 1) {
                ?>
                <td style="width:25%; font-size:15px; vertical-align:middle; text-align:left; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; ">Adjunta Cotización</td>
                <td style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
                <td style="width:5%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; ">&nbsp;</td>
                <td style="width:15%;"></td>
                <?php
            } else {
                ?>
                <td style="width:25%; font-size:15px; vertical-align:middle; text-align:left; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; ">Adjunta Cotización</td>
                <td style="width:15%; font-size:20px;"></td>
                <td style="width:5%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; ">&nbsp;</td>
                <td style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
                <?php
            }
            ?>

        </table>

        <br>
        <h4 style="text-align:justify;">Argumente los motivos y necesidad de la compra:</h4>


        <table cellpadding="5" border="1" style="width: 100%;">

            <tr>
                <td style="vertical-align:middle; text-align:justify;"><?php echo nl2br($argumentosSoliCompra); ?></td>
            </tr>

        </table>

    </div>

    <div style="page-break-before: always;">
        <br>&nbsp;&nbsp;&nbsp;<br>
        <br>&nbsp;&nbsp;&nbsp;<br><br><br>

        <table cellpadding="5" style="width: 100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">

            <tr>
                <?php
                if ($unidadSoliCompra == 'Infraestructura') {
                    if ($datosPersonalSoliCompra[0] == 'Luis Andrades Miranda') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
                        <?php
                    } else {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Infraestructura</td>
                        <?php
                    }
                } else if ($unidadSoliCompra == 'Incendio y Seguridad') {
                    ?>
                    <td style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Encargado(a) de Equipos<br>Industriales</td>
                    <?php
                } else if ($unidadSoliCompra == 'Movilización') {
                    if ($datosPersonalSoliCompra[0] == 'Johnny Jara') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
                        <?php
                    } else {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Movilizaci&oacute;n</td>
                        <?php
                    }
                } else if ($unidadSoliCompra == 'Equipos Médicos') {
                    if ($datosPersonalSoliCompra[0] == 'María Fernanda Fuentes') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    } else {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    }
                } else if ($unidadSoliCompra == 'Logística') {
                    if ($datosPersonalSoliCompra[0] == 'Luis Vargas') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    } else {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de <?php echo $unidadSoliCompra; ?></td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    }
                } else if ($unidadSoliCompra == 'Subdirección de Operaciones' || $unidadSoliCompra == 'Ingeniería Operacional') {
                    if ($datosPersonalSoliCompra[0] == 'Paola Nuñez Cardenas') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirector(a) de Operaciones</td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Director Hospital Biprovincial<br>Quillota Petorca</td>
                        <?php
                    } else if ($datosPersonalSoliCompra[0] == 'Rafael Mendoza Saavedra') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    } else if ($datosPersonalSoliCompra[0] == 'Haroll Álvarez Barrera') {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>

                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    } else {
                        ?>
                        <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        <?php
                    }
                } else if ($unidadSoliCompra == 'Jefe de Control Centralizado') {
                    ?>
                    <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Control Centralizado</td>

                    <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
                    <?php
                } else {

                    if ($unidadSoliCompra == 'Instrumentación') {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Control Centralizado</td>
                        <?php
                    } else if ($unidadSoliCompra == 'Equipos Industriales' || $unidadSoliCompra == 'Equipos de Apoyo Clínico') {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Encargado(a) de Equipos Industriales</td>
                        <?php
                    } else if ($unidadSoliCompra == 'Inst. Térmicas, Climatización y Refrigeración') {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Encargado(a) de Inst. Térmicas<br>Climatización y Refrigeración</td>
                        <?php
                    } else if ($unidadSoliCompra == 'Electricidad') {
                        ?>
                        <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;">___________________________<br><b>Nombre y Firma</b><br>Encargado(a) de Electricidad</td>
                        <?php
                    }
                }
                ?>
            </tr>

            <?php
            if ($unidadSoliCompra == 'Equipos Médicos' || $unidadSoliCompra == 'Logística' || $unidadSoliCompra == 'Gestión Documental') {
                echo '';
            } else {
                if ($datosPersonalSoliCompra[0] == 'Rafael Mendoza Saavedra' || $datosPersonalSoliCompra[0] == 'Paola Nuñez Cardenas' || $datosPersonalSoliCompra[0] == 'Haroll Álvarez Barrera') {
                    echo '';
                } else {
                    if ($datosPersonalSoliCompra[0] == 'Johnny Jara' || $datosPersonalSoliCompra[0] == 'Luis Andrades Miranda' || $datosPersonalSoliCompra[0] == 'Alejandro Cantillano') {

                        ?>
                        <tr>
                            <td colspan="2" style="vertical-align:middle; text-align:center; width:100%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;"><br><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de<br>Operaciones</td>
                        </tr>
                        <?php
                    } else {

                        ?>
                        <tr>
                            <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;"><br><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>

                            <td style="vertical-align:middle; text-align:center; width:50%; border-top: 0.01em solid #ffffff; border-bottom: 0.01em solid #ffffff; border-left: 0.01em solid #ffffff; border-right: 0.01em solid #ffffff;"><br><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>

        </table>
    </div>

</main>


</body>

</html>

<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new DOMPDF();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);


$dompdf->load_html(ob_get_clean());
// $customPaper = array(0,0,215.9,360);
$dompdf->set_paper("letter");
$filename = $folioSoliCompra . ".pdf";
$dompdf->add_info('Title', $filename);
$dompdf->render();


$pdf = $dompdf->output();
file_put_contents($filename, $pdf);
$dompdf->stream($filename, array("Attachment" => false));
unlink($filename);
?>


