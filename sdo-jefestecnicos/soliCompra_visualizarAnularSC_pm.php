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
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
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

        $datosAnulacion = array();

        $datosAnulacion = getAnulacionSCPM($idSoliCompra);

        $idAnulacion2 = $datosAnulacion['idAnulacion'];
        $detalleAnulacion = $datosAnulacion['detalleAnulacion'];
        $personalAnulacion = $datosAnulacion['personalAnulacion'];
        $unidadAnulacion = $datosAnulacion['unidadAnulacion'];
        $fechaAnulacion = $datosAnulacion['fechaAnulacion'];
        $idSoliCompraPM = $datosAnulacion['idSoliCompraPM'];
    }
} else if (isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = trim($_SESSION['idSoliCompra']);

    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: solicitudes_recibidas.php?errorCargarSolicitud=true');
    } else {
        $datosSoliCompra = array();
        $datosSoliCompra = getSoliCompra_pm($idSoliCompra);

        $idSoliCompra2 = $datosSoliCompra['idSoliCompra'];
        $numSoliCompra = $datosSoliCompra['numSoliCompra'];
        $folioSoliCompra = $datosSoliCompra['folioSoliCompra'];
        $fechaSoliCompra = $datosSoliCompra['fechaSoliCompra'];
        $personalSoliCompra = $datosSoliCompra['personalSoliCompra'];
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

        $datosAnulacion = array();

        $datosAnulacion = getAnulacionSCPM($idSoliCompra2);

        $idAnulacion2 = $datosAnulacion['idAnulacion'];
        $detalleAnulacion = $datosAnulacion['detalleAnulacion'];
        $personalAnulacion = $datosAnulacion['personalAnulacion'];
        $unidadAnulacion = $datosAnulacion['unidadAnulacion'];
        $fechaAnulacion = $datosAnulacion['fechaAnulacion'];
        $idSoliCompraPM = $datosAnulacion['idSoliCompraPM'];
    }
} else {
    header('Location: soliCompra_pm_administracion.php?errorSolicitudCompra=true');
}


if ($numSoliCompra == 0) {
    $numSoliCompra = "";
}


// Include the main TCPDF library (search for installation path).
require_once('../sdo-pdf/TCPDF/tcpdf.php');

$GLOBALS['folioSoliCompra'] = $folioSoliCompra;


// create new PDF document
class MYPDF extends TCPDF
{
    public function Header()
    {

        $html = '

        
		<table cellpadding="10" border="1">
		<tr>
			<td style="text-align: center;" rowspan="3" width="20%;">
				<img src="../sdo-pdf/TCPDF/examples/images/LogoSSVQ.png" style="text-align: center; width:100px; height:100px; margin-top:100px;">
			</td>
			<td width="55%;" style="text-align: center; vertical-align: middle;">
				<b>HOSPITAL BIPROVINCIAL QUILLOTA - PETORCA</b>
			</td>
			<td rowspan="3" width="25%;" style="text-align: center; vertical-align: middle;">
			<h2 style="text-align: center; vertical-align: middle;"><center>Folio N°<br></center></h2>
			
			<h3 style="text-align: center; vertical-align: middle;"><center>' . $GLOBALS['folioSoliCompra'] . '</center></h3>
			</td>
		</tr>
		<tr>
			<td style="text-align: center; vertical-align: middle;">
			SUBDIRECCIÓN DE OPERACIONES
            <br>
            CONTROL CENTRALIZADO
			</td>
		</tr>
		<tr>
			<td style="text-align: center; vertical-align: middle;">
            ANULACI&Oacute;N DE SOLICITUD DE COMPRA
			</td>
		</tr>
		</table>
		<br>
		<br>
		<br>';
        $this->writeHTML($html, 0, 1, 1, true, true, true);
    }
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(true);
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
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
<h3 align="center">INFORME DE ANULACI&Oacute;N DE SOLICITUD DE COMPRA<br><br></h3>


';

$content .= '

<h4>1.- INFORMACI&Oacute;N DE SOLICITUD DE COMPRA ANULADA:</h4>
<ul>
<li style="text-align:justify;"><b>Fecha Solicitud: </b>' . $fechaSC . '</li><br>

<li style="text-align:justify;"><b>Folio Solicitud: </b>' . $folioSoliCompra . '</li><br>

';

if ($unidadSoliCompra == "Subdirección de Operaciones") {

    $findme1   = 'Haroll Álvarez';
    $pos1 = strpos($personalSoliCompra, $findme1);

    if ($pos1 === false) {

        $personalSoliCompra = explode(' (', $personalSoliCompra);

        $content .= '<li style="text-align:justify;"><b>Solicitado por: </b>' . $personalSoliCompra[0] . '</li><br>';
    } else {

        $content .= '<li style="text-align:justify;"><b>Solicitado por: </b>Rafael Mendoza Saavedra</li><br>';
    }

    $content .= '<li style="text-align:justify;"><b>Unidad o Servicio: </b>Subdirecci&oacute;n de Operaciones</li><br>';

    if ($pos1 === false) {

        $content .= '<li style="text-align:justify;"><b>Correo Electrónico: </b>' . $emailSoliCompra . '</li><br>';
    } else {

        $content .= '<li style="text-align:justify;"><b>Correo Electrónico: </b>rafael.mendoza.saavedra@gmail.com</li><br>';
    }
} else {

    $content .= '<li style="text-align:justify;"><b>Solicitado por: </b>Jorge Cantillano S&aacute;nchez</li><br>';

    $content .= '<li style="text-align:justify;"><b>Solicitado por: </b>Control Centralizado</li><br>';

    $content .= '<li style="text-align:justify;"><b>Solicitado por: </b>jorge.cantillano@redsalud.gov.cl</li><br>';
}

$content .= '

</ul>

<br>

<br>

';

$content .= '

<h4>2.- RAZONES DE ANULACI&Oacute;N DE SOLICITUD:</h4>

<ul>

<li style="text-align:justify;"><b>Personal que hizo la Anulaci&oacute;n: </b>' . $personalAnulacion . '</li><br>

<li style="text-align:justify;"><b>Unidad a la que pertenece el Personal: </b>' . $unidadAnulacion . '</li><br>

<li style="text-align:justify;"><b>Fecha de Anulaci&oacute;n: </b>' . $fechaSoliCompra . '</li><br>

<li style="text-align:justify;"><b>Detalle de Anulaci&oacute;n: </b><br><br>' . nl2br($detalleAnulacion) . '</li><br>

</ul>

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
