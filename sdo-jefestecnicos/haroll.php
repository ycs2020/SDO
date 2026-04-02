<?php

// ASIGNAR ID
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

require_once '../sdo-excelphp/Classes/PHPExcel.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('USE_GD', 0);

date_default_timezone_set('America/Santiago');
setlocale(LC_ALL, "es_CL", 'Spanish_Chile', 'Spanish');

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
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-mesa-ayuda";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

if (isset($_POST['noinicioSC'], $_POST['noterminoSC'])) {

    $noInicioSC = $_POST['noinicioSC'];
    $noTerminoSC = $_POST['noterminoSC'];

    if ($noInicioSC <= 0 || $noTerminoSC <= 0) {
        header('Location: ../'.$ruta.'/soliCompra_sdo_administracion.php?errorDescargaMaterial1=true');
    } else {

        $queryExcel = "SELECT * FROM solicitudes_compra_sdo WHERE estadoSoliCompra NOT LIKE '%Solicitud Anulada%' AND numSoliCompra BETWEEN ".$noInicioSC." AND ".$noTerminoSC." ORDER BY fechaSoliCompra ASC ";
        $resExcel = mysqli_query($conexion, $queryExcel);

        if (mysqli_num_rows($resExcel) > 0) {

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('Solicitudes de Compra');
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Folio Solicitud');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Material');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Medida');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Valor');

            $numFila = 2;

            while ($rowExcel = $resExcel->fetch_assoc()) {

                $idSoliCompra = $rowExcel['idSoliCompra'];
                $folioSoliCompra = $rowExcel['folioSoliCompra'];

                $queryExcel2 = "SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = ".$idSoliCompra;
                $resExcel2 = mysqli_query($conexion, $queryExcel2);

                while ($rowExcel2 = $resExcel2->fetch_assoc()) {

                    $nomArticuloSC = $rowExcel2['nomArticuloSC'];
                    $cantidadArticuloSC = $rowExcel2['cantidadArticuloSC'];
                    $medidaSC = $rowExcel2['medidaSC'];
                    $valorSC = $rowExcel2['valorSC'];

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $numFila, $folioSoliCompra);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $numFila, nl2br($nomArticuloSC));
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $numFila, $cantidadArticuloSC);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $numFila, $medidaSC);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $numFila, $valorSC);
                    $numFila++;
                }
            }

            $nombreDelDocumento = "Materiales_Solicitud_de_Compra.xlsx";

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');





            exit();
        } else {
            header('Location: ../'.$ruta.'/soliCompra_sdo_administracion.php?errorDescargaMaterial2=true');
        }
    }
} else {
    header('Location: ../'.$ruta.'/soliCompra_sdo_administracion.php?errorDescargaMaterial3=true');
}
