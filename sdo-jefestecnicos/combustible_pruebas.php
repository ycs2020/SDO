<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


$queryComb = "SELECT * FROM combustible_administracion WHERE fechaAC BETWEEN '2023-03-01' AND '2023-03-31'";

$resComb = mysqli_query($conexion, $queryComb);

$numComb = 1;

$restMedNormal = -1;

$diaAnteriorComb = 0;

$opera = '';

while ($rowComb = $resComb->fetch_assoc()) {

    if ($rowComb['tipoAC'] == 'Carga de Combustible') {

        $opera = 'RestMedNormal ' . $restMedNormal . ' = tanque1AC ' . $rowComb['tanque1AC']. ' - restMedNormal '. $restMedNormal;

        $restMedNormal = $rowComb['tanque1AC'] - $restMedNormal;

        echo $opera . '<br><br>';

        echo $restMedNormal . '<br><br>';

    } else if ($rowComb['tipoAC'] == 'Medición Normal') {

        if ($restMedNormal == -1) {

            $opera = 'RestMedNormal ' . $restMedNormal . ' = tanque1AC ' . $rowComb['tanque1AC'];

            $restMedNormal = $rowComb['tanque1AC'];

            echo $opera . '<br><br>';

            echo $restMedNormal . '<br><br>';
        } else {

            $opera = 'RestMedNormal ' . $restMedNormal . ' = RestMedNormal' . $restMedNormal . ' - diaAnteriorComb ' . $diaAnteriorComb;

            $diaAnteriorComb = $rowComb['tanque1AC'];

            $restMedNormal = $restMedNormal - $diaAnteriorComb;

            echo $opera . '<br><br>';

            echo $restMedNormal . '<br><br>';
        }
    } else {
    }
}
