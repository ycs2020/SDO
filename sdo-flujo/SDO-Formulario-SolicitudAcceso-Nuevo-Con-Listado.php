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

$fechaActual = date('d-m-Y');
$now2 = date('d-m-Y', time());

$fechaActual2 = strtotime($fechaActual);
$fechaActual2 = fechaEspañol2($fechaActual2);

$fechaActual3 = strtotime($fechaActual);
$fechaActual3 = fechaEspañol3($fechaActual3);

if (isset($_POST['idFormRegAcceso'])) {
    $idFormRegAcceso = $_POST['idFormRegAcceso'];
    $query = 'SELECT * FROM formularios_registro_pruebas WHERE idFormRegAcceso = ' . $idFormRegAcceso;
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $folioFormRegAcceso = $row['folioFormRegAcceso'];
        $unidadFormRegAcceso = $row['unidadFormRegAcceso'];
        $fechaFormRegAcceso = $row['fechaFormRegAcceso'];
        $fechaRecepcionFormRegAcceso = $row['fechaRecepcionFormRegAcceso'];
        $categoriaFormRegAcceso = $row['categoriaFormRegAcceso'];
        $solicitanteFormRegAcceso = $row['solicitanteFormRegAcceso'];
        $unidadSolicitaFormRegAcceso = $row['unidadSolicitaFormRegAcceso'];
        $emailSolicitaFormRegAcceso = $row['emailSolicitaFormRegAcceso'];
        $tipoDocumento = $row['tipoDocumento'];
        $numeroDocumento = $row['numeroDocumento'];
        $fechaFormRegAcceso2 = strtotime($fechaFormRegAcceso);
        $fechaFormRegAcceso2 = date('d-m-Y', $fechaFormRegAcceso2);
    }

    $_SESSION['idFormRegAcceso'] = $idFormRegAcceso;

} else if (isset($_SESSION['idFormRegAcceso'])) {
    $idFormRegAcceso = $_SESSION['idFormRegAcceso'];
    $query = 'SELECT * FROM formularios_registro_pruebas WHERE idFormRegAcceso = ' . $idFormRegAcceso;
    $res = mysqli_query($conexion, $query);
    while ($row = $res->fetch_assoc()) {
        $folioFormRegAcceso = $row['folioFormRegAcceso'];
        $unidadFormRegAcceso = $row['unidadFormRegAcceso'];
        $fechaFormRegAcceso = $row['fechaFormRegAcceso'];
        $fechaRecepcionFormRegAcceso = $row['fechaRecepcionFormRegAcceso'];
        $categoriaFormRegAcceso = $row['categoriaFormRegAcceso'];
        $solicitanteFormRegAcceso = $row['solicitanteFormRegAcceso'];
        $unidadSolicitaFormRegAcceso = $row['unidadSolicitaFormRegAcceso'];
        $emailSolicitaFormRegAcceso = $row['emailSolicitaFormRegAcceso'];
        $tipoDocumento = $row['tipoDocumento'];
        $numeroDocumento = $row['numeroDocumento'];
        $fechaFormRegAcceso2 = strtotime($fechaFormRegAcceso);
        $fechaFormRegAcceso2 = date('d-m-Y', $fechaFormRegAcceso2);
    }


    $_SESSION['idFormRegAcceso'] = $idFormRegAcceso;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!--    <title>--><?php //echo $folioReporte; ?><!--</title>-->
    <title>Formulario de Solicitud de Acceso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /** Define the margins of your page **/
        @page {
            margin-left: 2cm;
            margin-right: 2cm;
            margin-top: 180px;
            margin-bottom: 2.5cm;
        }

        header {
            position: fixed;
            top: -150px;
            left: 0;
            right: 0;
            height: 150px;
            /*background-color: #ccc;*/
        }

        /**
        * Define the width, height, margins and position of the watermark.
        **/
        #watermark {
            position: fixed;

            /**
                Set a position in the page for your image
                This should center it vertically
            **/
            top: 0cm;
            left: 8cm;

            /** Change image dimensions**/
            width: 32cm;
            height: 25cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;
            opacity: 0.3;
        }

        #footer {
            position: fixed;
            left: 620px;
            bottom: 0;
            text-align: right;
        }


        table.usuarios {
            width: 100%;
            border-left: 0.01em solid #000000;
            border-right: 0;
            border-top: 0.01em solid #000000;
            border-bottom: 0;
            border-collapse: collapse;

        }

        table.td,
        table.th {
            border-left: 0;
            border-right: 0.01em solid #000000;
            border-top: 0;
            border-bottom: 0.01em solid #000000;
            padding: 3px;
        }

    </style>
</head>
<body>
<div id="watermark">
    <img src="<?php echo $controlCentFull2 ?>" style="text-align: center; width:50%; height:50%; ">
</div>
<header>
    <table style="font-size: 13px; " border="0" width="100%">
        <tr>
            <td style="text-align: left;" rowspan="3" width="20%;">
                <img src="<?php echo $LogoSSVQ2 ?>" style="text-align: center; width:100px; height:100px;">
            </td>
            <td style="text-align: right;" rowspan="3" width="20%;">
                <?php
                $rutaLogo = $_SERVER['DOCUMENT_ROOT'] . '/sdo-pdf/HBQP.png';
                if (file_exists($rutaLogo)) {
                    $logoHospitalBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($rutaLogo));
                    ?>
                    <img src="<?php echo $logoHospitalBase64; ?>" style="text-align: center; width:250px; height:100px;">
                    <?php
                } else {
                    echo "<small>Logo no encontrado: $rutaLogo</small>";
                }
                ?>
            </td>

        </tr>
    </table>
</header>
<main>
    <h3  style="text-align:center;">SOLICITUD DE ACCESO PARA FUNCIONARIOS</h3>

    <div style="page-break-after: always;">
        <table style="vertical-align:middle; text-align:left; font-size: 18px; width: 100%; border-collapse: collapse;" border="0">
            <tr>

                <td style=" width: 50%">

                    <h4 style="text-align:right; font-weight: normal; font-size: 16px;"><b>FOLIO:</b>&nbsp;&nbsp;<u><?php echo $folioFormRegAcceso; ?></u></h4>

                </td>
                <td style=" width: 10%">

                    <h4 style="text-align:right; font-weight: normal; font-size: 16px;"><b>FECHA:</b>&nbsp;&nbsp;<u><?php echo $fechaFormRegAcceso2; ?></u></h4>

                </td>
            <tr></tr>
        </table>
        <table id="usuarios" class="text-center" style="font-size: 13px;float: left;vertical-align: middle;border-collapse: collapse;width: 35%">
            <thead>
            <tr>
                <th colspan="2">DATOS DEL SOLICITANTE:</th>
            </tr>
            </thead>
            <br>
            <tbody>
            <tr>
                <th>Solicitante:</th>
                <th style="font-weight: normal;">
                    <?php
                    $solicitanteFormRegAcceso2 = mb_convert_case($solicitanteFormRegAcceso, MB_CASE_UPPER, 'UTF-8');
                    ?>
                    <?php echo $solicitanteFormRegAcceso2; ?>
                </th>
            </tr>
            <tr>
                <th><b>E-Mail Solicitante:</b></th>
                <th style="font-weight: normal;">
                    <?php
                    $emailSolicitaFormRegAcceso2 = mb_convert_case($emailSolicitaFormRegAcceso, MB_CASE_UPPER, 'UTF-8');
                    ?>
                    <?php echo $emailSolicitaFormRegAcceso2; ?>
                </th>
            </tr>
            <br>
            &nbsp;
            <br>
            &nbsp;
            <br>
            &nbsp;
            <br>
            &nbsp;
            <br>
            <tr>
                <th>Firma del Solicitante:</th>
                <th>___________________________________</th>
            </tr>
            </tbody>
        </table>

        <table style="float: right;vertical-align: auto; text-align:left; font-size: 14px; width: 50%; border-collapse: collapse;" border="0">
            <thead>
            <tr>
                <th colspan="2">FIRMAS A SOLICITAR</th>
            </tr>
            </thead>
            <br>
            <tbody>
            <?php
            $buscarUnidad = "SELECT idUnidad FROM formularios_funcionarios_prueba WHERE idFormRegAcceso = $idFormRegAcceso GROUP BY idUnidad";
            $resUnidad = mysqli_query($conexion, $buscarUnidad);
            while ($rowUnidad = $resUnidad->fetch_assoc()) {
                $idUnidad = $rowUnidad['idUnidad'];
                $buscarNomUnidad = buscarnombreunidad($idUnidad);

                if (!empty($buscarNomUnidad)) { // Verifica si $buscarNomUnidad no está vacío
                    if($buscarNomUnidad == 'Accesos Comunes'){
                    }else{
                        ?>
                        <tr>
                            <th>Nombre, Firma y Timbre de la Jefatura<br>Unidad <?php echo $buscarNomUnidad ?></th>
                            <th>___________________________________</th>
                        </tr>
                        <br>
                        <?php
                    }
                    ?>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    $sqlFuncionarios = "SELECT idUnidad FROM formularios_funcionarios_prueba WHERE idFormRegAcceso = $idFormRegAcceso GROUP BY idUnidad";
    $resFuncionarios = mysqli_query($conexion, $sqlFuncionarios);
    $totalUnidades = mysqli_num_rows($resFuncionarios);
    $totalParaSalto = 1;

    while ($rowFuncionarios = $resFuncionarios->fetch_assoc()) {
        $unidadFuncionario = $rowFuncionarios['idUnidad'];
        $buscarNomUnidad = buscarnombreunidad($unidadFuncionario);
        if (!empty($buscarNomUnidad)) {
            $sqlAccesos = "SELECT * FROM formularios_acceso WHERE unidadFormCCAA = '$buscarNomUnidad'";
            $resAccesos = mysqli_query($conexion, $sqlAccesos);
            $buscarNomUnidad = mb_convert_case($buscarNomUnidad, MB_CASE_UPPER, 'UTF-8');
            while ($rowAccesos = $resAccesos->fetch_assoc()) {
                $cantidadFormCCAA = $rowAccesos['cantidadFormCCAA'];
                $arrayNumeros = array();

                for ($i = 1; $i <= $cantidadFormCCAA; $i++) {
                    $arrayNumeros[] = $i;
                }

                $grupos = array_chunk($arrayNumeros, 4); // Dividir el array en grupos de 4

                foreach ($grupos as $grupo) {
                    $largoColspan = 0;
                    foreach ($grupo as $grupo2) {

                        $largoColspan++;
                    }

                    $largoFinal = 4 + $largoColspan;

                    $divisor = 100 / $largoFinal;
//
//                    echo $largoColspan.'<br>';
//                    echo $largoFinal.'<br>';
//                    echo $totalUnidades.'<br>';
//                    echo $totalParaSalto.'<br>';
                    ?>

                    <table border="1" style="width: 100%;vertical-align:middle; text-align:left; font-size: 10pt; border-collapse: collapse; border-color: black;border-width: 2px;">
                        <thead>
                        <tr>
                            <th colspan="<?php echo $largoFinal; ?>" style=" text-align: center; background-color: lightgrey;">


                                <b>ACCESOS A UNIDAD: <?php echo trim($buscarNomUnidad); ?></b>

                            </th>

                        </tr>

                        <tr>

                            <td style="width: <?php echo $divisor . '%'; ?>; text-align: center; background-color: lightgrey;">

                                <b>NOMBRE:</b>

                            </td>

                            <td style="width: <?php echo $divisor . '%'; ?>; text-align: center; background-color: lightgrey;">

                                <b>RUT:</b>

                            </td>

                            <td style="width: <?php echo $divisor . '%'; ?>; text-align: center; background-color: lightgrey;">

                                <b>CALIDAD JURIDICA:</b>

                            </td>


                            <td style="width: <?php echo $divisor . '%'; ?>; text-align: center; background-color: lightgrey;">

                                <b>UNIDAD:</b>

                            </td>

                            <?php
                            foreach ($grupo as $grupo2) {

                                $nombrePuerta = $rowAccesos['door' . $grupo2 . 'FormCCAA'];
                                $nombrePuertaMayus = mb_convert_case($nombrePuerta, MB_CASE_UPPER, 'UTF-8');
                                ?>
                                <td style="width: <?php echo $divisor . '%'; ?>; text-align: center; background-color: lightgrey;">

                                    <b><?php echo $nombrePuertaMayus; ?>:</b>

                                </td>
                                <?php
                            }
                            ?>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlBuscarFuncionarios = "SELECT * FROM formularios_funcionarios_prueba WHERE idFormRegAcceso =  $idFormRegAcceso AND idUnidad = $unidadFuncionario";

                        $resBuscarFuncionarios = mysqli_query($conexion, $sqlBuscarFuncionarios);

                        while ($rowBuscarFuncionarios = $resBuscarFuncionarios->fetch_assoc()) {
                            ?>
                            <tr>
                                <td style=" width: <?php echo $divisor; ?>%; text-align: center;">
                                    <?php
                                    $nomFormFuncCCAA = $rowBuscarFuncionarios['nomFormFuncCCAA'];

                                    $nomFormFuncCCAA2 = mb_convert_case($nomFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                                    echo $nomFormFuncCCAA2;
                                    ?>
                                </td>

                                <td style=" width: <?php echo $divisor; ?>%; text-align: center;">
                                    <?php
                                    $rutFormFuncCCAA = $rowBuscarFuncionarios['rutFormFuncCCAA'];

                                    $rutFormFuncCCAA2 = mb_convert_case($rutFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                                    echo $rutFormFuncCCAA2;
                                    ?>
                                </td>

                                <td style=" width: <?php echo $divisor; ?>%; text-align: center;">
                                    <?php
                                    $calidadFormFuncCCAA = $rowBuscarFuncionarios['calidadFormFuncCCAA'];

                                    $calidadFormFuncCCAA2 = mb_convert_case($calidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                                    echo $calidadFormFuncCCAA2;
                                    ?>
                                </td>

                                <td style=" width: <?php echo $divisor; ?>%; text-align: center;">
                                    <?php
                                    $unidadFormFuncCCAA = $rowBuscarFuncionarios['unidadFormFuncCCAA'];

                                    $unidadFormFuncCCAA2 = mb_convert_case($unidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');

                                    echo $unidadFormFuncCCAA2;
                                    ?>
                                </td>
                                <?php
                                foreach ($grupo as $grupo2) {

                                    $nombrePuerta = $rowAccesos['door' . $grupo2 . 'FormCCAA'];
                                    $nombrePuertaMayus = mb_convert_case($nombrePuerta, MB_CASE_UPPER, 'UTF-8');
                                    ?>

                                    <td style=" width: <?php echo $divisor; ?>%; text-align: center;">

                                        <b>
                                            <?php

                                            $opcion = $rowBuscarFuncionarios['door' . $grupo2 . 'FormCCAA'];

                                            if ($opcion == 1) {
                                                ?>
                                                <p><i class="fas fa-check"></i></p>
                                                <?php
                                            }
                                            ?>
                                        </b>

                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                    <?php


                    echo "<br><br><br>";
                }

                $totalParaSalto++;
                if ($totalParaSalto <= $totalUnidades) {
                    ?>
                    <div style="page-break-after:always;"></div>
                    <?php
                }
            }
        } else {
            $totalUnidades = $totalUnidades - 1;
        }
    }
    ?>


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
$dompdf->set_paper("8.5x13", "landscape");
$filename = $folioFormRegAcceso . ".pdf";
$dompdf->add_info('Title', $filename);
$dompdf->render();


$pdf = $dompdf->output();
file_put_contents($filename, $pdf);
$dompdf->stream($filename, array("Attachment" => false));
unlink($filename);
?>




