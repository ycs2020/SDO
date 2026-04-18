<?php
ob_start();
$LogoSSVQ = "TCPDF/examples/images/LogoSSVQ.png";
$LogoSSVQ2 = "data:image/png;base64," . base64_encode(file_get_contents($LogoSSVQ));

$controlCent = "TCPDF/examples/images/controlCent-HBQP-120.jpg";
$controlCent2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCent));

$controlCentFull = "TCPDF/examples/images/controlCent-HBQP-Full-2.jpg";
$controlCentFull2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCentFull));

date_default_timezone_set('America/Santiago');

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$respuestas = array();

for ($item_id = 1; $item_id <= 14; $item_id++) {
    // Consulta SQL para obtener las respuestas del item actual
    $sqlRespuesta = "SELECT * FROM `respuestas` WHERE item_id = $item_id AND idVAV = 6;";
    $resRespuesta = mysqli_query($conexion, $sqlRespuesta);

    // Verificamos si hay resultados
    if ($resRespuesta && $rowRespuesta = $resRespuesta->fetch_assoc()) {
        // Guardamos los valores en el array asociativo
        $respuestas[$item_id] = array(
            'f' => $rowRespuesta['f'],
            'si' => $rowRespuesta['si'],
            'na' => $rowRespuesta['na'],
            'no' => $rowRespuesta['no'],
            'condicion' => $rowRespuesta['condicion'],
            'parametros' => $rowRespuesta['parametros'],
            'observacion' => $rowRespuesta['observacion']
        );
    } else {
        // Si no se encontró la respuesta para este item_id, asignamos valores predeterminados
        $respuestas[$item_id] = array(
            'f' => '',
            'si' => 0,
            'na' => 0,
            'no' => 0,
            'condicion' => '',
            'parametros' => '',
            'observacion' => ''
        );
    }
}

// Ahora puedes acceder a los valores de cada item de la siguiente manera:
// item 1
$f1 = $respuestas[1]['f'];
$SI1 = $respuestas[1]['si'];
$NA1 = $respuestas[1]['na'];
$NO1 = $respuestas[1]['no'];
$condicion1 = $respuestas[1]['condicion'];
$parametros1 = $respuestas[1]['parametros'];
$observacion1 = $respuestas[1]['observacion'];

// item 2
$f2 = $respuestas[2]['f'];
$SI2 = $respuestas[2]['si'];
$NA2 = $respuestas[2]['na'];
$NO2 = $respuestas[2]['no'];
$condicion2 = $respuestas[2]['condicion'];
$parametros2 = $respuestas[2]['parametros'];
$observacion2 = $respuestas[2]['observacion'];

// item 3
$f3 = $respuestas[3]['f'];
$SI3 = $respuestas[3]['si'];
$NA3 = $respuestas[3]['na'];
$NO3 = $respuestas[3]['no'];
$condicion3 = $respuestas[3]['condicion'];
$parametros3 = $respuestas[3]['parametros'];
$observacion3 = $respuestas[3]['observacion'];

// item 4
$f4 = $respuestas[4]['f'];
$SI4 = $respuestas[4]['si'];
$NA4 = $respuestas[4]['na'];
$NO4 = $respuestas[4]['no'];
$condicion4 = $respuestas[4]['condicion'];
$parametros4 = $respuestas[4]['parametros'];
$observacion4 = $respuestas[4]['observacion'];

// item 5
$f5 = $respuestas[5]['f'];
$SI5 = $respuestas[5]['si'];
$NA5 = $respuestas[5]['na'];
$NO5 = $respuestas[5]['no'];
$condicion5 = $respuestas[5]['condicion'];
$parametros5 = $respuestas[5]['parametros'];
$observacion5 = $respuestas[5]['observacion'];

// item 6
$f6 = $respuestas[6]['f'];
$SI6 = $respuestas[6]['si'];
$NA6 = $respuestas[6]['na'];
$NO6 = $respuestas[6]['no'];
$condicion6 = $respuestas[6]['condicion'];
$parametros6 = $respuestas[6]['parametros'];
$observacion6 = $respuestas[6]['observacion'];

// item 7
$f7 = $respuestas[7]['f'];
$SI7 = $respuestas[7]['si'];
$NA7 = $respuestas[7]['na'];
$NO7 = $respuestas[7]['no'];
$condicion7 = $respuestas[7]['condicion'];
$parametros7 = $respuestas[7]['parametros'];
$observacion7 = $respuestas[7]['observacion'];

// item 8
$f8 = $respuestas[8]['f'];
$SI8 = $respuestas[8]['si'];
$NA8 = $respuestas[8]['na'];
$NO8 = $respuestas[8]['no'];
$condicion8 = $respuestas[8]['condicion'];
$parametros8 = $respuestas[8]['parametros'];
$observacion8 = $respuestas[8]['observacion'];

// item 9
$f9 = $respuestas[9]['f'];
$SI9 = $respuestas[9]['si'];
$NA9 = $respuestas[9]['na'];
$NO9 = $respuestas[9]['no'];
$condicion9 = $respuestas[9]['condicion'];
$parametros9 = $respuestas[9]['parametros'];
$observacion9 = $respuestas[9]['observacion'];

// item 10
$f10 = $respuestas[10]['f'];
$SI10 = $respuestas[10]['si'];
$NA10 = $respuestas[10]['na'];
$NO10 = $respuestas[10]['no'];
$condicion10 = $respuestas[10]['condicion'];
$parametros10 = $respuestas[10]['parametros'];
$observacion10 = $respuestas[10]['observacion'];

// item 11
$f11 = $respuestas[11]['f'];
$SI11 = $respuestas[11]['si'];
$NA11 = $respuestas[11]['na'];
$NO11 = $respuestas[11]['no'];
$condicion11 = $respuestas[11]['condicion'];
$parametros11 = $respuestas[11]['parametros'];
$observacion11 = $respuestas[11]['observacion'];

// item 12
$f12 = $respuestas[12]['f'];
$SI12 = $respuestas[12]['si'];
$NA12 = $respuestas[12]['na'];
$NO12 = $respuestas[12]['no'];
$condicion12 = $respuestas[12]['condicion'];
$parametros12 = $respuestas[12]['parametros'];
$observacion12 = $respuestas[12]['observacion'];

// item 13
$f13 = $respuestas[13]['f'];
$SI13 = $respuestas[13]['si'];
$NA13 = $respuestas[13]['na'];
$NO13 = $respuestas[13]['no'];
$condicion13 = $respuestas[13]['condicion'];
$parametros13 = $respuestas[13]['parametros'];
$observacion13 = $respuestas[13]['observacion'];

// item 14
$f14 = $respuestas[14]['f'];
$SI14 = $respuestas[14]['si'];
$NA14 = $respuestas[14]['na'];
$NO14 = $respuestas[14]['no'];
$condicion14 = $respuestas[14]['condicion'];
$parametros14 = $respuestas[14]['parametros'];
$observacion14 = $respuestas[14]['observacion'];

?>
    <!DOCTYPE html>
<html>
<head>
    <title>Check List - VAV: <?php echo $folio?></title>
    <style>
        /** Define the margins of your page **/
        @page {
            margin-left: 2cm;
            margin-right: 2cm;
            margin-top: 190px;
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
        #watermark {
            position: fixed;

            /**
                            Set a position in the page for your image
                            This should center it vertically
                        **/
            top: 5cm;
            left: 1cm;

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
            border-top: 0;
            border-bottom: 0.01em solid #000000;
        }

        .fieldbms {
            text-align: justify;
            page-break-before: always;
        }

        .fieldca {
            text-align: justify;
        }
    </style>
</head>
<body>
<header>
    <table style="font-size:  12px; " cellpadding="5" width="100%">
        <tr>
            <td style="text-align: center;" rowspan="3" width="20%;">
                <img src="<?php echo $LogoSSVQ2 ?>"
                     style="text-align: center; font-weight: bold; width:90px; height:90px;">
            </td>
            <td width="60%;" style="text-align: center; vertical-align: middle; font-weight: bold;">
                HOSPITAL BIPROVINCIAL QUILLOTA - PETORCA
            </td>
            <td style="text-align: center; vertical-align: middle; font-weight: bold;" width="20%">
                Version: V1
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: middle; font-weight: bold;">
                SUBDIRECCI&Oacute;N DE OPERACIONES
            </td>
            <td style="text-align: center; vertical-align: middle; font-weight: bold;" width="20%">
                Elaboración: Marzo 2025
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: middle; font-weight: bold;">
                CHECK LIST CONTROL CENTRALIZADO E INSTRUMENTACIÓN
                VAV<br>
                Folio: <?php echo $folio?>
            </td>
            <td style="text-align: center; vertical-align: middle; font-weight: bold;" width="20%">
                Version: V1
            </td>
        </tr>
    </table>
</header>
<main>
    <br>
    <table style="font-size:  15px;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;" cellpadding="2" width="100%">
        <tr>
            <td style="text-align: center;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;"  width="20%;">
                <label>EC-BOS:</label> <label style="text-decoration: underline;"s><?php echo $ecbos; ?></label>
            </td>
            <td style="text-align: center;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;"  width="20%;">
                <label>MAC-ADDRESS:</label> <label style="text-decoration: underline;"s><?php echo $macaddress; ?></label>
            </td>
        </tr>
    </table>
    <br>
    <table style="font-size:  15px;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;" cellpadding="2" width="100%">
        <tr>
            <td style="text-align: center;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;"  width="20%;">
                <label>CONTROLADOR:</label> <label style="text-decoration: underline;"s><?php echo $controlador; ?></label>
            </td>
            <td style="text-align: center;border-top: 0 solid #FFFFFF;border-bottom: 0 solid #FFFFFF;border-left: 0 solid #FFFFFF;border-right: 0 solid #FFFFFF;"  width="20%;">
                <label>RED DE COMUNICACION:</label> <label style="text-decoration: underline;"s><?php echo $redComunicacion; ?></label>
            </td>
        </tr>
    </table>
    <br>
    <input style="width: 100%;font-size: 11px" class="form-control" value="SISTEMA O SUB-SISTEMA: <?php echo $sistemaosub?>">
    <br>
    <br>
    <?php
    $datos = 'f=FRECUENCIA    M=Mensual    T=Trimestral     S=Semestral     A=Anual     A+=5 AÑOS O MAS     O=OPERATIVO';
    ?>
    <input type="text" disabled style="text-align: center;width: 100%;font-size: 11px;background-color: grey;color: black" class="form-control;" value="<?php echo $datos?>">
    <br>
    <br>
    <table style="border: 1px solid #000000;" cellpadding="2" width="100%">
        <thead style="font-size: 10px">
        <tr>
            <th style="text-align: center; width: 30%">ITEMIZADO DE MANTENCIÓN</th>
            <th style="text-align: center;">f</th>
            <th style="text-align: center;">SI</th>
            <th style="text-align: center;">N/A</th>
            <th style="text-align: center;">NO</th>
            <th style="text-align: center;">INDICAR<br>CONDICION</th>
            <th style="text-align: center;">INDICAR<br>PARAMETROS</th>
            <th style="text-align: center;">OBSERVACION</th>
        </tr>
        </thead>
        <tbody style="font-size: 12px">
        <tr>
            <td>¿Existe comunicación entre el BMS y el controlador?</td>
            <td style="text-align: center"><?php echo $f1?></td>
            <td style="text-align: center">
                <?php
                if($SI1 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA1 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO1 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion1?>
            </td>
            <td>
                <?php echo $parametros1?>
            </td>
            <td>
                <?php echo nl2br($observacion1)?>
            </td>
        </tr>
        <tr>
            <td>¿Las terminales en el controlador se encuentran bien apretadas y libres de sulfato?</td>
            <td style="text-align: center">
                <?php echo $f2?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI2 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA2 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO2 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion2?>
            </td>
            <td>
                <?php echo $parametros2?>
            </td>
            <td>
                <?php echo nl2br($observacion2)?>
            </td>
        </tr>
        <tr>
            <td>¿Las mangueras se encuentran en buenas condiciones?</td>
            <td style="text-align: center">
                <?php echo $f3?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI3 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA3 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO3 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion3?>
            </td>
            <td>
                <?php echo $parametros3?>
            </td>
            <td>
                <?php echo nl2br($observacion3)?>
            </td>
        </tr>
        <tr>
            <td>¿El voltaje de C.A de alimentación es el adecuado?</td>
            <td style="text-align: center">
                <?php echo $f4?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI4 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA4 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO4 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion4?>
            </td>
            <td>
                <?php echo $parametros4?>
            </td>
            <td>
                <?php echo nl2br($observacion4)?>
            </td>
        </tr>
        <tr>
            <td>¿El termostato se encuentra en buenas condiciones?</td>
            <td style="text-align: center">
                <?php echo $f5?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI5 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA5 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO5 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion5?>
            </td>
            <td>
                <?php echo $parametros5?>
            </td>
            <td>
                <?php echo nl2br($observacion5)?>
            </td>
        </tr>
        <tr>
            <td>¿Se refleja el estado del termostato en BMS?</td>
            <td style="text-align: center">
                <?php echo $f6?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI6 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA6 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO6 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion6?>
            </td>
            <td>
                <?php echo $parametros6?>
            </td>
            <td>
                <?php echo nl2br($observacion6)?>
            </td>
        </tr>
        <tr>
            <td>¿Se puede modificar el Set de temperatura?</td>
            <td style="text-align: center">
                <?php echo $f7?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI7 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA7 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO7 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion7?>
            </td>
            <td>
                <?php echo $parametros7?>
            </td>
            <td>
                <?php echo nl2br($observacion7)?>
            </td>
        </tr>
        <tr>
            <td>¿Se refleja el Set de temperatura en BMS?s</td>
            <td style="text-align: center">
                <?php echo $f8?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI8 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA8 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO8 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion8?>
            </td>
            <td>
                <?php echo $parametros8?>
            </td>
            <td>
                <?php echo nl2br($observacion8)?>
            </td>
        </tr>
        <tr>
            <td>¿Funciona la apertura y cierre del equipo?</td>
            <td style="text-align: center">
                <?php echo $f9?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI9 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA9 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO9 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion9?>
            </td>
            <td>
                <?php echo $parametros9?>
            </td>
            <td>
                <?php echo nl2br($observacion9)?>
            </td>
        </tr>
        <tr>
            <td>¿Se refleja la apertura y cierre de la VAV en el BMS?</td>
            <td style="text-align: center">
                <?php echo $f10?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI10 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA10 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO10 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion10?>
            </td>
            <td>
                <?php echo $parametros10?>
            </td>
            <td>
                <?php echo nl2br($observacion10)?>
            </td>
        </tr>
        <tr>
            <td>¿La lógica de programación opera adecuadamente?</td>
            <td style="text-align: center">
                <?php echo $f11?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI11 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA11 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO11 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion11?>
            </td>
            <td>
                <?php echo $parametros11?>
            </td>
            <td>
                <?php echo nl2br($observacion11)?>
            </td>
        </tr>
        <tr>
            <td>¿El voltaje de la red de comunicación MSTP/Bacnet es el adecuado?</td>
            <td style="text-align: center">
                <?php echo $f12?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI12 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA12 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO12 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion12?>
            </td>
            <td>
                <?php echo $parametros12?>
            </td>
            <td>
                <?php echo nl2br($observacion12)?>
            </td>
        </tr>
        <tr>
            <td>¿La medición de la presión diferencial del equipo VAV es el adecuado?</td>
            <td style="text-align: center">
                <?php echo $f13?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI13 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA13 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO13 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion13?>
            </td>
            <td>
                <?php echo $parametros13?>
            </td>
            <td>
                <?php echo nl2br($observacion13)?>
            </td>
        </tr>
        <tr>
            <td>¿Se realiza limpieza completa a la carcasa y placa?</td>
            <td style="text-align: center">
                <?php echo $f14?>
            </td>
            <td style="text-align: center">
                <?php
                if($SI14 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NA14 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                if($NO14 == 1){
                    ?>
                    X
                    <?php
                }
                ?>
            </td>
            <td>
                <?php echo $condicion14?>
            </td>
            <td>
                <?php echo $parametros14?>
            </td>
            <td>
                <?php echo nl2br($observacion14)?>
            </td>
        </tr>
        </tbody>
    </table>
    <div style="page-break-before: always">
        <label>Observaciones:</label>
        <br>
        <textarea rows="1000"><?php echo $obsGeneral?></textarea>
        <br>
        <table style="font-size:  15px;" cellpadding="3" width="100%" class="text-center">
            <thead>
            <th>
                Ejecutado por: <?php echo $tecnico1?>
            </th>
            <th>
                Revisado por: <?php echo $personaSoli?>
            </th>
            </thead>
            <tbody style="text-align: center">
            <tr>
                <td style="height: 70px">
                    <?php
                    $getRutaFirmaVisado = getRutaFirmaVisado($tecnico1);
                    if (!empty($getRutaFirmaVisado)) {
                        $logoVisado = "https://" . $_SERVER['SERVER_NAME'] . '/'.$getRutaFirmaVisado;
                        //                        $controlCent2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCent));
                        $logoVisado2 = str_replace(" ", "%20", $logoVisado);
                        ?>
                        <img src="<?php echo $logoVisado2 ?>" alt="<?php echo $tecnico1; ?>" style="text-align: center; width:200px; height:90px; ">

                        <?php
                    }
                    ?>
                </td>
                <td style="height: 70px">
                    <?php
                    if($isVisadoCheck == 1) {
                        $getRutaFirmaVisado = getRutaFirmaVisado($personaSoli);
                        if (!empty($getRutaFirmaVisado)) {
                            $logoVisado = "https://" . $_SERVER['SERVER_NAME'] . '/'.$getRutaFirmaVisado;
                            //                        $controlCent2 = "data:image/png;base64," . base64_encode(file_get_contents($controlCent));
                            $logoVisado2 = str_replace(" ", "%20", $logoVisado);
                            ?>
                            <img src="<?php echo $logoVisado2 ?>" alt="<?php echo $personaSoli; ?>" style="text-align: center; width:200px; height:90px; ">

                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
            </tbody>
            <tfoot style="font-size: 11px">
            <th>
                Fecha: <?php echo date('d/m/Y', strtotime($fechaCheck));?>
            </th>
            <th>
                Fecha:
                <?php
                if($fechaVisadoCheck == '' || empty($fechaVisadoCheck)){
                }else{
                    echo date('d/m/Y', strtotime($fechaVisadoCheck));
                }
                ?>
            </th>
            </tfoot>
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
$dompdf->set_paper("8.5x13");
$filename = 'Check List - VAV -' . $idVav . ".pdf";
$dompdf->add_info('Title', $filename);
$dompdf->render();


$pdf = $dompdf->output();
file_put_contents($filename, $pdf);
$dompdf->stream($filename, array("Attachment" => false));

unlink($filename);
