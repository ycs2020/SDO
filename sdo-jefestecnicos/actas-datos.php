
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
//fetch.php

$queryDatosActa = "SELECT * FROM actas ORDER BY fechaActa DESC";

$obtenerCantidadActas = obtenerCantidadActas();

$numActa = 1;

$resDatosActa = mysqli_query($conexion, $queryDatosActa);

echo "{";
echo "'data':";
echo "[";

while ($rowDatosActa = $resDatosActa->fetch_assoc()) {

    if ($obtenerCantidadActas == $numActa) {
        echo "[";
        echo "'" . $rowDatosActa['folioActa'] . "'" . ",";
        echo "'" . $rowDatosActa['nomTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['rutTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['codigoTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['fechaActa'] . "'" . ",";
        echo "'" . $rowDatosActa['unidadTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['nomSolicitante'] . "'" . ",";
        echo "'" . $rowDatosActa['nomControlCentralizado'] . "'";

        echo "]";
    } else {
        echo "[";
        echo "'" . $rowDatosActa['folioActa'] . "'" . ",";
        echo "'" . $rowDatosActa['nomTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['rutTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['codigoTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['fechaActa'] . "'" . ",";
        echo "'" . $rowDatosActa['unidadTarjeta'] . "'" . ",";
        echo "'" . $rowDatosActa['nomSolicitante'] . "'" . ",";
        echo "'" . $rowDatosActa['nomControlCentralizado'] . "'";

        echo "],";
    }

    $numActa++;
}

echo "]";
echo "}";
?>

