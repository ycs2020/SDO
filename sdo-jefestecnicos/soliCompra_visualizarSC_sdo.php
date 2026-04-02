<?php
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
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $datosPersonalSoliCompra = explode(' (', $personalSoliCompra);
        $isCotizacion = $datosSoliCompra['isCotizacion'];

        $idSolicitudSoporte = $datosSoliCompra['idSolicitudSoporte'];

        if ($idSolicitudSoporte == '') {
        } else {
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

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_sdo($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
        $datosPersonalSoliCompra = explode(' (', $personalSoliCompra);
        $isCotizacion = $datosSoliCompra['isCotizacion'];

        $idSolicitudSoporte = $datosSoliCompra['idSolicitudSoporte'];

        if ($idSolicitudSoporte == '') {
        } else {
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

        $fechaSC = trim($fechaSC);

        $horaSC = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion.php?errorSolicitudCompra=true');
}


if ($numSoliCompra == 0) {
    $numSoliCompra = "";
}


// Include the main TCPDF library (search for installation path).
require_once('../sdo-pdf/TCPDF/tcpdf.php');

$GLOBALS['folioSoliCompra'] = $folioSoliCompra;
$GLOBALS['estadoSoliCompra'] = $estadoSoliCompra;

// create new PDF document
class MYPDF extends TCPDF
{

    public function Header()
    {
        if ($GLOBALS['estadoSoliCompra'] == 'Solicitud Anulada') {
            $this->SetFont('helvetica', '', 9);

            // Render the image
            $this->Image('../sdo-administrador/Nula.jpg', 0, 100, 0, 0, '', '', 'RTLM', false, 0, 'C');
        }
    }


    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 1, $GLOBALS['folioSoliCompra'], 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 1, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', 'FOLIO', 'UTF-8', false);
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set document information
$folioSolicitudGuardar = $folioSoliCompra;
$no_permitidas2 = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã", "Ã", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "Ò", "Ã", "Ã„", "Ã‹", "à", "è", "ì", "ò", "ù");
$permitidas2 = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "ñ", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "Ñ", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", "a", "e", "i", "o", "u");
$texto2 = str_replace($no_permitidas2, $permitidas2, $folioSolicitudGuardar);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Administrador de Solicitudes de Compra');
$pdf->SetTitle($texto2);
$pdf->SetSubject($texto2);
$pdf->SetKeywords('Solicitud de Compra, PDF, SC, SDO');


// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));


// set header and footer fonts
// $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/spa.php')) {
    require_once(dirname(__FILE__) . '/lang/spa.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('Helvetica', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();


// Set some content to print
//CAMBIAR NOMBRE ADMIN
$content = '

<table align="left" border="0">

<tr>

<td><b>HOSPITAL SAN MARTIN<br>QUILLOTA</b></td>

</tr>

</table>

<br>
<h4 align="center"><br>&nbsp;<br>SOLICITUD DE COMPRA <b>N° ' . $numSoliCompra . '</b><br><br></h4>


';

$content .= '


<table cellpadding="8" cellspacing="10" border="0">

';


$content .= ' 
 
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Fecha Solicitud: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>' . $fechaSC . '</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Solicitado por: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>' . $datosPersonalSoliCompra[0] . '</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Unidad o Servicio: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>' . $unidadSoliCompra . '</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Correo Electrónico: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>secretaria.suboperacioneshbqp2@gmail.com</p></td>
	</tr>

</table>

<br>

';

$contarArticuloSC = contarMaterialesSCSDO($idSoliCompra);

if ($contarArticuloSC > 0) {


    $content .= '

<p style="font-weight:bold;">Describa el bien y/o servicio a adquirir claramente:</p>


<table cellpadding="5" border="1">

	<tr>
        <td style="vertical-align:middle; text-align:center; width:15%; font-weight:bold; font-size: 14px;">Cantidad</td>
        <td style="vertical-align:middle; text-align:center; width:35%; font-weight:bold; font-size: 14px;">Nombre</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-weight:bold; font-size: 14px;">Medida</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-weight:bold; font-size: 14px;">Valor Total<br>Referencial</td>
	</tr>

    ';


    $queryArticuloSC = 'SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = ' . $idSoliCompra . ' ORDER BY nomArticuloSC ASC';


    $resArticuloSC = mysqli_query($conexion, $queryArticuloSC);

    $numMaterialSC = 1;

    while ($rowArticuloSC = $resArticuloSC->fetch_assoc()) {

        $valorSC = number_format($rowArticuloSC['valorSC'], 0, ",", ".");

        if($valorSC > 0) {
            $valorSC = '$' . $valorSC;
        }
        $content .= '


    <tr nobr="true">
        <td style="vertical-align:middle; text-align:center; width:15%; font-size: 14px;">' . $rowArticuloSC['cantidadArticuloSC'] . '</td>
        <td style="vertical-align:middle; text-align:center; width:35%; font-size: 14px;">' . nl2br($rowArticuloSC['nomArticuloSC']) . '</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-size: 14px;">' . $rowArticuloSC['medidaSC'] . '</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-size: 14px;">' . $valorSC . '</td>
	</tr>


    ';
    }


    $content .= '

</table>

<br><br>
';
}


$content .= '

    <br>

    <table>
    
    <tr>
    
    <td style="width:20%; text-align:center;">&nbsp;</td>
    <td style="width:15%; text-align:center;">S&iacute;</td>
    <td style="width:5%; text-align:center;">&nbsp;</td>
    <td style="width:15%; text-align:center;">No</td>

    </tr>
    <tr>
    ';

if ($adjuntaCotizacion == 1) {
    $content .= '
    <td style="width:20%; font-size:14px;">Adjunta<br>Archivos</td>
    <td border="1" style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
    <td style="width:5%;">&nbsp;</td>
    <td border="1"  style="width:15%;"></td>
';
} else {
    $content .= '
    <td style="width:20%; font-size:14px;">Adjunta<br>Archivos</td>
    <td border="1" style="width:15%;"></td>
    <td style="width:5%;">&nbsp;</td>
    <td border="1"  style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
';
}


$content .= '
    </tr>

    </table>

    <br>
';


$content .= '

    <br>

    <table>
    <tr>
    <td style="width:20%; text-align:center;">&nbsp;</td>
    <td style="width:15%; text-align:center;">S&iacute;</td>
    <td style="width:5%; text-align:center;">&nbsp;</td>
    <td style="width:15%; text-align:center;">No</td>

    </tr>
    <tr>
    ';

if ($isCotizacion == 1) {
    $content .= '
    <td style="width:20%; font-size:14px;">Adjunta<br>Cotización</td>
    <td border="1" style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
    <td style="width:5%;">&nbsp;</td>
    <td border="1"  style="width:15%;"></td>



 
';
} else {
    $content .= '
    <td style="width:20%; font-size:14px;">Adjunta<br>Cotización</td>
    <td border="1" style="width:15%;"></td>
    <td style="width:5%;">&nbsp;</td>
    <td border="1"  style="width:15%; vertical-align:middle; text-align:center; font-size:20px;"><b>X</b></td>
';
}


$content .= '
    </tr>

    </table>

    <br>
';


$content .= '

<br pagebreak="true" />


<p style="font-weight:bold;"><u>Argumente los motivos y necesidad de la compra</u></p>

<table cellpadding="5" border="1">

	<tr>
		<td style="vertical-align:middle; text-align:justify;">' . nl2br($argumentosSoliCompra) . '</td>
	</tr>

</table>


';

$content .= '

<br pagebreak="true" />

<br>&nbsp;&nbsp;&nbsp;<br>
<br>&nbsp;&nbsp;&nbsp;<br>
<br>&nbsp;&nbsp;&nbsp;<br>

<table cellpadding="0" border="0">

    <tr>

        ';

if ($unidadSoliCompra == 'Infraestructura') {
    if ($datosPersonalSoliCompra[0] == 'Luis Andrades Miranda') {
        $content .= '
        
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
        ';
    } else {
        $content .= '
        <td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Infraestructura</td>
        ';
    }
} else if ($unidadSoliCompra == 'Incendio y Seguridad') {

        $content .= '
        <td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Encargado(a) de Equipos<br>Industriales</td>
        ';
} else if ($unidadSoliCompra == 'Movilización') {
    if ($datosPersonalSoliCompra[0] == 'Johnny Jara') {
        $content .= '
        
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
        ';
    } else {
        $content .= '
        <td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Movilizaci&oacute;n</td>
        ';
    }
} else if ($unidadSoliCompra == 'Equipos Médicos') {
    if ($datosPersonalSoliCompra[0] == 'María Fernanda Fuentes') {
        $content .= '
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    } else {
        $content .= '
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    }
} else if ($unidadSoliCompra == 'Logística') {
    if ($datosPersonalSoliCompra[0] == 'Luis Vargas') {
        $content .= '

        
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    } else {
        $content .= '

        
        <td style="vertical-align:middle; text-align:center; width:50%">___________________________<br><b>Nombre y Firma</b><br>Jefe(a) de ' . $unidadSoliCompra . '</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    }
} else if ($unidadSoliCompra == 'Subdirección de Operaciones') {
    if ($datosPersonalSoliCompra[0] == 'Paola Nuñez Cardenas') {
        $content .= '

        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirector(a) de Operaciones</td>

        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Director Hospital Biprovincial<br>Quillota Petorca</td>
        ';
    } else if ($datosPersonalSoliCompra[0] == 'Rafael Mendoza Saavedra') {
        $content .= '

        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    } else if ($datosPersonalSoliCompra[0] == 'Haroll Álvarez Barrera') {
        $content .= '

        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    } else {
        $content .= '
        
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
        ';
    }
} else if ($unidadSoliCompra == 'Jefe de Control Centralizado') {
    $content .= '

        
    <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Control Centralizado</td>
    
    <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a Operacional</td>
    ';
} else if ($unidadSoliCompra == 'Gestión Documental') {

    $content .= '
    <td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de Operaciones</td>
    
    
    ';
} else {

    if ($unidadSoliCompra == 'Instrumentación' || $unidadSoliCompra == 'Incendio y Seguridad') {
        $content .= '<td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Jefe de Control Centralizado</td>';
    } else if ($unidadSoliCompra == 'Equipos Industriales' || $unidadSoliCompra == 'Equipos de Apoyo Clínico') {
        $content .= '<td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Encargado Departamento de<br>Equipos Industriales</td>';
    } else if ($unidadSoliCompra == 'Inst. Térmicas, Climatización y Refrigeración') {
        $content .= '<td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Encargado Departamento de Inst. Térmicas<br>Climatización y Refrigeración</td>';
    } else if ($unidadSoliCompra == 'Electricidad') {
        $content .= '<td style="vertical-align:middle; text-align:center; width:100%;">___________________________<br><b>Nombre y Firma</b><br>Encargado Departamento<br>de Electricidad</td>';
    } else {

    }

}

$content .= '

    </tr>

    
';


if ($unidadSoliCompra == 'Equipos Médicos' || $unidadSoliCompra == 'Logística' || $unidadSoliCompra == 'Gestión Documental') {


} else {
    if ($datosPersonalSoliCompra[0] == 'Rafael Mendoza Saavedra' || $datosPersonalSoliCompra[0] == 'Paola Nuñez Cardenas' || $datosPersonalSoliCompra[0] == 'Haroll Álvarez Barrera') {
    } else {


        if ($datosPersonalSoliCompra[0] == 'Johnny Jara' || $datosPersonalSoliCompra[0] == 'Luis Andrades Miranda' || $datosPersonalSoliCompra[0] == 'Alejandro Cantillano') {

            $content .= '
                <tr>
                    <td colspan="2" style="vertical-align:middle; text-align:center; "><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de<br>Operaciones</td>
                </tr>
            
                
            ';
        } else {

            $content .= '
                <tr>
                    <td style="vertical-align:middle; text-align:center; width:50%;"><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Jefe de Ingenier&iacute;a<br>Operacional</td>
                    <td style="vertical-align:middle; text-align:center; width:50%;"><br><br><br><br><br><br><br><br>___________________________<br><b>Nombre y Firma</b><br>Subdirecci&oacute;n de<br>Operaciones</td>
                </tr>
            
                
            ';
        }
    }
}


$content .= '

</table>

<br>



<p style="font-weight:bold;">Uso Exclusivo Direcci&oacute;n Econ&oacute;mica</p>

<table cellpadding="5" border="1">

	<tr>
		<td style="vertical-align:middle; text-align:justify;">
        
        &nbsp;&nbsp;&nbsp;<br>Fecha recepción SDA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________<br>

        <br>&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;<br>

        <p style="vertical-align:middle; text-align:center; ">___________________________<br><b>Nombre y Firma</b><br>Subdirector Administrativo</p>

        </td>

        

	</tr>

</table>
';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã", "Ã", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "Ò", "Ã", "Ã‹", "à", "è", "ì", "ò", "ù");
$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "ñ", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "Ñ", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", "a", "e", "i", "o", "u");
$texto = str_replace($no_permitidas, $permitidas, $folioSoliCompra);

$pdf->Output($texto . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
