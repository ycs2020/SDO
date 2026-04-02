<?php

include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Load Composer's autoloader

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

// Include the main TCPDF library (search for installation path).
require_once('../sdo-pdf/TCPDF/tcpdf.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {

    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {

    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "mesa ayuda") {

    $ruta = "sdo-mesa-ayuda";
} else if ($rolUsuario2 == "bodega pañol") {

    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {

    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {

    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idSoliCompra = $_POST['id'];

// TRAE LOS DATOS DE LAS SOLICITUDES DE COMPRA

$sqlSelect = "SELECT * FROM solicitudes_compra_sdo WHERE idSoliCompra = $idSoliCompra";
$resSelect = mysqli_query($conexion, $sqlSelect);

while ($rowSelect = $resSelect->fetch_assoc()) {
    $datos = [];
    $datos['numSoliCompra'] = $rowSelect['numSoliCompra'];
    $datos['folioSoliCompra'] = $rowSelect['folioSoliCompra'];
    $datos['fechaSoliCompra'] = $rowSelect['fechaSoliCompra'];
    $datos['personalSoliCompra'] = $rowSelect['personalSoliCompra'];
    $datos['unidadSoliCompra'] = $rowSelect['unidadSoliCompra'];
    $datos['emailSoliCompra'] = $rowSelect['emailSoliCompra'];
    $datos['argumentosSoliCompra'] = $rowSelect['argumentosSoliCompra'];
    $datos['adjuntaCotizacion'] = $rowSelect['adjuntaCotizacion'];
    $datos['estadoSoliCompra'] = $rowSelect['estadoSoliCompra'];

    $numSoliCompra = $datos['numSoliCompra'];
    $folioSoliCompra = $datos['folioSoliCompra'];
    $fechaSoliCompra = $datos['fechaSoliCompra'];
    $personalSoliCompra = $datos['personalSoliCompra'];
    $unidadSoliCompra = $datos['unidadSoliCompra'];
    $email = $datos['emailSoliCompra'];
    $argumentosSoliCompra = $datos['argumentosSoliCompra'];
    $adjuntaCotizacion = $datos['adjuntaCotizacion'];
    $estadoSoliCompra = $datos['estadoSoliCompra'];
}

$fechaActual = date("d-m-Y");
$fechaSoliCompra = strtotime($fechaSoliCompra);
$fechaSoliCompra = fechaEspañol($fechaSoliCompra);

$GLOBALS['folio'] = $folioSoliCompra;

//MATERIALES DE SOLICITUD DE COMPRA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id'])) {

        $opcion = $_POST['opcion'];

        if ($opcion == 'recibidoPanol') {

            $nuevoEstado = 'Recibido por pañol';
            $observacion = "Orden de compra recibida por pañol";
            $fecha = $_POST['fechaEntrega'];

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (fechaActualizacion,observacion, tipoActualizacion, idSoliCompra) VALUES ('$fecha','$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);

            if ($resInsertSeguimiento) {
                $sqlUpdateSC = "UPDATE solicitudes_compra_sdo SET estadoSoliCompra = '$nuevoEstado' WHERE idSoliCompra = $idSoliCompra";
                $resUpdateSC = mysqli_query($conexion, $sqlUpdateSC);
            } else {
                echo "error insertar en la tabla de seguimiento";
            }

            if ($resUpdateSC) {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoRecibido=true');
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorRecibido=true');
            }
        } else if ($opcion == 'ordenesCompra') {

            $num = 0;
            $nuevoArrayCantidad = [];

            $ordenCompra = $_POST['ordenCompra'];
            $ordenCompra = mb_strtoupper($ordenCompra);

            $checks = $_POST['checks'];
            $cantidadLlegoArray = $_POST['cantidadLlego'];

            $cantidad = count($cantidadLlegoArray);

            for ($i = 0; $i < count($cantidadLlegoArray); $i++) {

                if ($cantidadLlegoArray[$i] > 0) {
                    $valor = $cantidadLlegoArray[$i];
                    array_push($nuevoArrayCantidad, $valor);
                }
            }

            foreach ($cantidadLlegoArray as $valor) {
                echo $valor . "<br>";
            }

            echo "<br><br>";

            foreach ($checks as $valor) {
                echo $valor . "<br>";
            }

            if (!empty($_FILES['archivoAdjunto'])) {
                $directorio = "../sdo-archivo-orden-de-compra-panol/id-$idSoliCompra".'/';
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                $totalArchivos = count($_FILES['archivoAdjunto']['name']);

                // Recorre todos los archivos subidos
                for ($i = 0; $i < $totalArchivos; $i++) {
                    $nombreArchivo = $_FILES['archivoAdjunto']['name'][$i];
                    $rutaTemporal = $_FILES['archivoAdjunto']['tmp_name'][$i];
                    $rutaDestino = $directorio . $nombreArchivo;

                    // Obtener la extensión del archivo
                    $separarNombre = explode(".", $nombreArchivo);
                    $extImagen = end($separarNombre);
                    $extImagen = mb_strtolower($extImagen);

                    if ($extImagen == "docx" || $extImagen == "xlsx" || $extImagen == "pdf" || $extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {
                        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                            $sqlInsertArchivo = "INSERT INTO `archivo-orden-de-compra-panol`(`id`, `nomArchivo`, `rutaArchivo`, `tipoArchivo`, `idSoliCompra`) 
                            VALUES (NULL,'$nombreArchivo','$rutaDestino','$extImagen',$idSoliCompra)";
                            $resInsertArchivo = mysqli_query($conexion,$sqlInsertArchivo);
                        }
                    }
                    // Mueve el archivo de la ubicación temporal a la definitiva
                }
            }

            $nuevoEstado = 'Actualizacion Ordenes de Compra';
            $observacion = $ordenCompra;

            $sqlInsertSeguimiento = "INSERT INTO seguimiento_sc_sdo (observacion, tipoActualizacion, idSoliCompra) 
            VALUES ('$observacion','$nuevoEstado', $idSoliCompra)";
            $resInsertSeguimiento = mysqli_query($conexion, $sqlInsertSeguimiento);


            foreach ($checks as $valor) {

                $sqlSelect = "SELECT * FROM material_solicompra_sdo WHERE  idArticuloSC = $valor";
                $resSelect = mysqli_query($conexion, $sqlSelect);

                while ($row = $resSelect->fetch_assoc()) {
                    $datos = [];
                    $datos['entregado'] = $row['entregado'];
                    $datos['cantidadLlego'] = $row['cantidadLlego'];
                    $datos['cantidadFaltante'] = $row['cantidadFaltante'];
                    $datos['cantidadArticuloSC'] = $row['cantidadArticuloSC'];
                    $entregado = $datos['entregado'];
                    $cantidadLlego = $datos['cantidadLlego'];
                    $cantidadFaltante = $datos['cantidadFaltante'];
                    $cantidadArticuloSC = $datos['cantidadArticuloSC'];
                }

                if ($entregado == 1) {
                    $cantidadLlego = intval($nuevoArrayCantidad[$num]) + $cantidadLlego;
                    var_dump(intval($nuevoArrayCantidad[$num])) . "<br>";
                    echo $cantidadLlego . "<br>";

                    $cantidadFaltante = $cantidadFaltante - $nuevoArrayCantidad[$num];

                    $sql = "UPDATE material_solicompra_sdo SET mostrarEnTabla = 1,cantidadLlego = '$cantidadLlego',cantidadLlegoActual = $nuevoArrayCantidad[$num], cantidadFaltante = $cantidadFaltante  WHERE idArticuloSC = $valor";
                    $res = mysqli_query($conexion, $sql);
                } else {
                    $cantidadFaltante = $cantidadArticuloSC - $nuevoArrayCantidad[$num];
                    $sql = "UPDATE material_solicompra_sdo SET entregado = 1, mostrarEnTabla = 1, cantidadLlegoActual = $nuevoArrayCantidad[$num], cantidadLlego = '$nuevoArrayCantidad[$num]', cantidadFaltante = $cantidadFaltante  WHERE idArticuloSC = $valor";
                    $res = mysqli_query($conexion, $sql);
                }

                $num++;
            }

            if ($res) {

                /* SE CREA EL CORREO */
                class MYPDF extends TCPDF
                {
                    public function Header()
                    {
                        $this->SetFont('helvetica', '', 9);

                        // Render the image
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
                            <h2 style="text-align: center; vertical-align: middle;"><center>N° FOLIO</center></h2>
                            
                            <h3 style="text-align: center; vertical-align: middle;font-size: 15px"><center>' .  $GLOBALS['folio'] . '</center></h3>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">
                            SUBDIRECCIÓN DE OPERACIONES
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">
                            <b>INGENIERÍA OPERACIONAL </b><br>			
                            <b>ACTA ENTREGA MATERIALES</b><br>		
                            </td>
                        </tr>
                    
                        </table>
                        <br>
                        <br>
                        <br>
                        <br>';
                        $this->writeHTML($html, 0, 1, 1, true, true, true);
                    }
                }

                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215.9, 355.6), true, 'UTF-8', false);
                $pdf->setPrintHeader(true);
                $pdf->SetHeaderMargin(15);
                $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

                // set document information
                $folioSoliCompra = $folioSoliCompra;
                $no_permitidas2 = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã", "Ã", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "Ò", "Ã", "Ã„", "Ã‹", "à", "è", "ì", "ò", "ù");
                $permitidas2 = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "ñ", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "Ñ", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", "a", "e", "i", "o", "u");
                $texto2 = str_replace($no_permitidas2, $permitidas2, $folioSoliCompra);

                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Administrador de Control Centralizado');
                $pdf->SetTitle($texto2);
                $pdf->SetSubject($texto2);
                $pdf->SetKeywords('Acta, PDF, SDO');

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
                // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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
                $pdf->SetFont('helvetica', '', 10, '', true);

                // Add a page
                // This method has several options, check the source code documentation for more information.
                $pdf->AddPage();
                $pdf->setPrintHeader(false);


                // Set some content to print
                //CAMBIAR NOMBRE ADMIN
                $content = '<br><br>';
                $content = '<br><br>';

                $content .= '
                <br>
                <table cellpadding="1" style="border-spacing:0.5px;" border="1">
                <tr>
                    <td style="vertical-align:middle; width:100%; text-align:center;font-size:10px;  background-color: #CDCDCD;font-weight:bold"><p >INFORMACIÓN SOLICITUD</p></td>
                </tr>
                <tr>
                    <td style="vertical-align:middle; width:20%; font-size: 10px;font-weight:bold"><p>N° FOLIO: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px"><p>' . mb_strtoupper($folioSoliCompra, 'UTF-8') . '</p></td>
                    <td style="vertical-align:middle; width:20%; font-size: 10px;font-weight:bold"><p>FECHA SOLICITUD: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px"><p>' . mb_strtoupper($fechaSoliCompra, 'UTF-8') . '</p></td>
                </tr>
                <tr>
                    <td style="vertical-align:middle; width:20%; font-size: 10px;font-weight:bold"><p>SOLICITANTE: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px"><p>' . mb_strtoupper($personalSoliCompra, 'UTF-8') . '</p></td>
                    <td style="vertical-align:middle; width:20%; font-size:10px;font-weight:bold "><p>UNIDAD SOLICITANTE: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px; "><p>' . mb_strtoupper($unidadSoliCompra, 'UTF-8') . '</p></td>
                    
                </tr>
                <tr>
                    <td style="vertical-align:middle; width:20%; font-size: 10px;font-weight:bold"><p>N° SOLICITUD: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px"><p>' . mb_strtoupper($idSoliCompra, 'UTF-8') . '</p></td>
                    <td style="vertical-align:middle; width:20%; font-size:10px;font-weight:bold "><p>FECHA ENTREGA: </p></td>
                    <td style="vertical-align:middle; width:30%; text-align:center; font-size:10px; "><p>' . mb_strtoupper($fechaActual, 'UTF-8') . '</p></td>
                </tr>
            ';

                $content .= '
                </table>
            ';
                $content .= '<br><br>';


                $content .= ' 
                    <p style="font-size: 13px; font-weight:bold; text-align: center;vertical-align:middle; background-color: #CDCDCD">ORDEN DE COMPRA: ' . $ordenCompra . ' </p><br>
                    ';

                $content .= ' 

                    <p style="font-size: 12px; font-weight:bold;">MATERIAL EN BODEGA: </p>';
                $content .= '
                
                    <table cellpadding="3" style="border-spacing:0.5px; font-size:12px" border="1">
                    
                    <tr style="text-align:center; font-weight:bold;background-color: #CDCDCD">
                    <th>Nombre Producto</th>
                    <th>Cantidad Solicitada/Faltante</th>
                    <th>Llegaron</th>
                    </tr>';

                $sqlSelect = "SELECT * FROM material_solicompra_sdo WHERE  idSoliCompra = $idSoliCompra and mostrarEnTabla = 1";
                $resSelect = mysqli_query($conexion, $sqlSelect);

                while ($rowMaterial = $resSelect->fetch_assoc()) {
                    $datos = [];

                    $datos['nomArticuloSC'] = $rowMaterial['nomArticuloSC'];
                    $datos['cantidadArticuloSC'] = $rowMaterial['cantidadArticuloSC'];
                    $datos['cantidadLlego'] = $rowMaterial['cantidadLlego'];
                    $datos['cantidadLlegoActual'] = $rowMaterial['cantidadLlegoActual'];
                    $datos['cantidadFaltante'] = $rowMaterial['cantidadFaltante'];

                    $nomArticuloSC = $datos['nomArticuloSC'];
                    $cantidadArticuloSC = $datos['cantidadArticuloSC'];
                    $cantidadLlego = $datos['cantidadLlego'];
                    $cantidadLlegoActual = $datos['cantidadLlegoActual'];
                    $cantidadFaltante = $datos['cantidadFaltante'];

                    $content .= ' 
                        <tr style="text-align:center" nobr="true"> 
                        <td>' .  mb_strtoupper($nomArticuloSC, 'UTF-8') . '</td>
                        <td>' . mb_strtoupper($cantidadArticuloSC, 'UTF-8') . '/' . mb_strtoupper($cantidadFaltante, 'UTF-8')  . '</td>
                        <td>' .  mb_strtoupper($cantidadLlegoActual, 'UTF-8')  . '</td>    
                        </tr>
                        ';
                }
                $content .= '
                        </table>
                ';

                $content .= '
                    &nbsp;
                    <p style="text-align: justify; vertical-align: middle;">
                    <br>
                    <br>
                    <b>IMPORTANTE:</b>
                    <ul>
                ';

                $content .= '
	
                    <li>
                    En este documento se describen los productos que llegaron junto a la solicitud de compra antes mencionada.
                    </li>
                    <li>
                    Puede visualizar el estado completo de su solicitud ingresando a la plataforma SDO con sus credenciales, recuerde que debe ingresar desde un computador instalado en las dependencias del hospital.
                    </li>
                    <li>
                    Cualquier duda o consulta, comunicarse con Bodega Pañol al Número (Anexo Minsal): 335065. Tambien puede contactarnos vía Correo Electronico a gonz.tdr09@gmail.com, recuerde indicar el folio de su solicitud de compra.
                    </li>

                    ';

                $content .= '</ul>';

                $content .= '
                <br><br>
                ';

                $content .= '
                </p>
                <table style="text-align: center; border-spacing:0.5px;">
                <tr>
                <td style="vertical-align:middle; "><h5>_________________________________</h5><h5><br>SOLICITANTE</h5></td>
                <td style="vertical-align:middle; "><h5>_________________________________</h5><h5><br> GONZALO JORQUERA<br>ENCARGADO DE PAÑOL </h5></td>
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

                $rutaPDF = __DIR__ . '/pdfs/' . $ordenCompra;
                $rutaPDFInsert = __DIR__ . '/pdfs/' . $ordenCompra . '.pdf';


                $pdf->Output($rutaPDF . '.pdf', 'F');

                //============================================================+
                // END OF FILE
                //============================================================+

                $ordenCompraInsert = $ordenCompra . '.pdf';
                $origenArchivo = $rutaPDFInsert;

                $sqlArchivos = "INSERT INTO archivos_solicompra_material_resumen (nomArchivoRE, rutaArchivoRE, extArchivo, idSoliCompra) VALUES ('$ordenCompraInsert' , '$rutaPDFInsert', 'pdf', $idSoliCompra)";
                $resArchivos = mysqli_query($conexion, $sqlArchivos);

                try {

                    $origenArchivo = $rutaPDF . '.pdf';

                    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'saladecontrolhbqp@gmail.com';                     //SMTP username
                    $mail->Password   = 'flekwyfqtojlqaht';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;

                    //Set who the message is to be sent from
                    //Note that with gmail you can only use your account address (same as `Username`)
                    //or predefined aliases that you have configured within your account.
                    //Do not use user-submitted addresses in here
                    $mail->setFrom('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                    //Set an alternative reply-to address
                    //This is a good place to put user-submitted addresses
                    $mail->addReplyTo('saladecontrolhbqp@gmail.com', 'Sala de Control Centralizado - HBQP');

                    //Set who the message is to be sent to
                    $mail->addAddress($email);
                    $mail->addCC('haroll.alvarez@redsalud.gob.cl', 'Haroll Alvarez');
                    $mail->addCC('oficinatecnica.sdo@gmail.com', 'Oficina Técnica SDO');
                    $mail->addCC('gonz.tdr09@gmail.com', 'Gonzalo Jorquera');
                    $mail->AddAttachment($origenArchivo, "acta-entrega");


                    //Set the subject line

                    $message  = "<html><body>";

                    $message .= "<table height='100%' width='100%' cellpadding='0' cellspacing='0' border='0'>";

                    $message .= "<tr><td>";

                    $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";

                    $message .= "<thead>";

                    $message .= "</thead>";

                    $message .= "<tbody>";

                    $message .= "<tr>";

                    $message .= "<td><br><h2 align='center'>Sistema de Solicitudes de Compra<br>&nbsp;<br><img src='https://cdnimg.bnamericas.com/QNBIixQugonFwhWLMWquGdAuLNTdViLJxwJUeVCETPAVucFxvIjNFgkxIIkUJUbK.png' alt='SSVQ' width='100' height='100'></h2><hr></td>";

                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;'> <br>Estimado(a) " . $personalSoliCompra . ": <br></p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='justify'> <br>Junto con saludar, le comunico que su solicitud folio: <b>" . $folioSoliCompra . "</b>, tiene una nueva actualización creada por <b>Encargado Bodega Pañol</b> <br></p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='justify'> <br>Detalles:<br></p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='center'> <br> <span style='background:yellow;'>Los materiales de su solicitud ya se encuentran en pañol, puede revisar el detalle a continuación (Solo puede visualizar el documento desde un computador instalado en el hospital):</span><br></p>";
                    $message .= "<p style='font-size:20px;' align='center'> <br> <a href='sdo.hospitalquillota.cl/sdo-funciones/pdfs/${ordenCompra}.pdf' > Descargar PDF</a> </span><br></p>";

                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='justify'> <br>Al ingresar con sus credenciales en la <a style='color:blue;' href='http://sdo.hospitalquillota.cl/sdo-flujo/plataforma_index.php'>Plataforma SDO</a>, Ud. podra visualizar la informaci&oacute;n completa de su solicitud de compra. <br></p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='justify'> <br><b>Importante: </b>El seguimiento de la solicitud de compra  solo debe ser revisado en un Computador instalado en el Hospital Biprovincial Quillota Petorca.<br></p>";
                    $message .= "</td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan='4'";
                    $message .= "<p style='font-size:20px;' align='center'> <br>Saludos cordiales.<br><br>Administrador de Solicitudes de Compra - SDO.</p>";
                    $message .= "<br><br></td>";
                    $message .= "</tr>";

                    $message .= "</tbody>";

                    $message .= "</table>";

                    $message .= "</td></tr>";

                    $message .= "</table>";

                    $message .= "</body></html>";

                    //Content
                    $subject = 'Nueva Estado de la solicitud de compra #' . $folioSoliCompra;
                    $altMessage = 'Actualización Estado';

                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    $mail->AltBody = $altMessage;
                    $mail->send();
                } catch (Exception $e) {
                    header($headerError3 . '6');
                }

                //ACTUALIZAMOS LA TABLA
                foreach ($checks as $valor) {
                    $sqlActualiza = "UPDATE material_solicompra_sdo SET mostrarEnTabla = 0, cantidadLlegoActual = 0 WHERE idArticuloSC = $valor";
                    $resActualiza = mysqli_query($conexion, $sqlActualiza);
                }


                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoOrdena=true');
            } else {
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?noOrdena=true');
            }
        }else if($opcion == "valorOrdenCompra"){

            $ordenCompra = $_POST['ordenCompra'];
            $fechaEnvio = $_POST['fechaEnvio'];
            $valorOrdenCompra = $_POST['valorOrdenCompra'];

            //INSERTAMOS EN EL DETALLE ORDENES DE COMPRA
            $sql = "INSERT INTO detalle_ordencompra (codOrdenCompra, fechaEnvioProv, valorTotal, unidadSoliCompra,idSoliCompra) VALUES ('$ordenCompra', '$fechaEnvio', '$valorOrdenCompra', '$unidadSoliCompra' ,$idSoliCompra)";
            $res = mysqli_query($conexion, $sql);

            if($res){
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?exitoOrdenCompra=true');
            }else{
                header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?errorOrdenCompra=true');
            }

        }
    }
} else {
    header('Location: ../' . $ruta . '/soliCompra_sdo_seguimiento.php?idNoValido=true');
}
