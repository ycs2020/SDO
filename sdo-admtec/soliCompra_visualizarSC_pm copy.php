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
    }
} else {
    header('Location: soliCompra_pm_administracion.php?errorSolicitudCompra=true');
}


if($numSoliCompra == 0){
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

        $html = '<table cellpadding="10" border="0" style="text-align: center; vertical-align: middle;">
		<tr style="text-align: center; vertical-align: middle;">
			<td style="text-align: left;"  width="50%;">
				<img src="../sdo-pdf/TCPDF/examples/images/gobChile1.jpg" style="text-align: center; width:100px; height:100px; margin-top:100px;">
			</td>
			<td width="50%;" style="text-align: right; font-weight: bold; font-size: 13px;" >
                MINISTERIO DE SALUD<br>
                SERVICIO DE SALUD<br>
                VIÑA DEL MAR - QUILLOTA<br>
                HOSPITAL SAN MARTIN QUILLOTA<br>
                UNIDAD DE PUESTA EN MARCHA<br>
                

                
			</td>
		</tr>';



        $html .= '
		
		
	</table>
	<br>
	<br>
	<br><br>';

        $this->writeHTML($html, 0, 1, 1, true, true, true);
    }

    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 1, $GLOBALS['folioSoliCompra'], 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
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

<table align="right" border="0">

<tr>

<td style="width: 65%;"></td>

<td style="width: 10%; text-align: left;">N°<br>Interno:</td>

<td style="width: 20%; line-height:30px" align="left"><h3>'.$numSoliCompra.'</h3></td>

</tr>

</table>

<br>
<h4 align="center"><br>&nbsp;<br>SOLICITUD DE COMPRA DE REQUERIMIENTOS PARA AUTORIZACION SANITARIA Y HABILITACIÓN HBQP<br><br></h4>


';

$content .= '


<table cellpadding="8" cellspacing="10" border="0">

	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Fecha Solicitud: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>' . $fechaSC . '</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Solicitado por: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>Jorge Cantillano S&aacute;nchez</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Unidad o Servicio: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>Control Centralizado</p></td>
	</tr>
	<tr>
		<td style="vertical-align:middle; width:28%"><p><b>Correo Electrónico: </b></p></td>
		<td border="1" style="vertical-align:middle; width:60%"><p>jorge.cantillano@redsalud.gov.cl</p></td>
	</tr>

</table>

<br>

';

$contarMaterialSC = contarMaterialesSCPM($idSoliCompra);

if ($contarMaterialSC > 0) {


    $content .= '

<p style="font-weight:bold;">Describa el bien y/o servicio a adquirir claramente:</p>


<table cellpadding="5" border="1">

	<tr>
        <td style="vertical-align:middle; text-align:center; width:35%; font-weight:bold; font-size: 14px;">Nombre</td>
        <td style="vertical-align:middle; text-align:center; width:15%; font-weight:bold; font-size: 14px;">Cantidad</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-weight:bold; font-size: 14px;">Observaci&oacute;n</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-weight:bold; font-size: 14px;">Referencia de Adquisici&oacute;n</td>
	</tr>

    ';


    $queryMaterialSC = 'SELECT * FROM material_solicompra_pm WHERE idSoliCompra = ' . $idSoliCompra . ' ORDER BY nomMaterialSC ASC';


    $resMaterialSC = mysqli_query($conexion, $queryMaterialSC);

    $numMaterialSC = 1;

    while ($rowMaterialSC = $resMaterialSC->fetch_assoc()) {

        $content .= '

    <tr>
        <td style="vertical-align:middle; text-align:center; width:35%; font-size: 14px;">' . $rowMaterialSC['nomMaterialSC'] . '</td>
        <td style="vertical-align:middle; text-align:center; width:15%; font-size: 14px;">' . $rowMaterialSC['cantidadMaterialSC'] . '</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-size: 14px;">' . $rowMaterialSC['observacionMaterialSC'] . '</td>
        <td style="vertical-align:middle; text-align:center; width:25%; font-size: 14px;">' . $rowMaterialSC['empresaMaterialSC'] . '</td>
	</tr>


    ';
    }


    $content .= '

</table>

<br><br>
';
}

$content .= '


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

<table cellpadding="0" border="0">

    <tr>
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Nombre y Firma de Referente</b><br><b>T&eacute;cnico de Puesta en Marcha</b><br>Solicitante</td>
        <td style="vertical-align:middle; text-align:center; width:50%;">___________________________<br><b>Puesta en Marcha</b><br><b>&nbsp;&nbsp;&nbsp;</b><br><b>V° B°</b></td>
    </tr>

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
// $pdf->Output($texto . '.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
