<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_GET['idSoliCompra'])){

    $idSoliCompra = $_GET['idSoliCompra'];

    if(empty($idSoliCompra) || $idSoliCompra < 1){
        echo '';
    }else{
        // Consulta SQL
        $sql = "SELECT * FROM solicitudes_compra_sdo_observaciones WHERE idSoliCompra = ".$idSoliCompra." ORDER BY idSoliCompra DESC";

        $resObs = mysqli_query($conexion, $sql);

// Comprobar si hay resultados
        if (mysqli_num_rows($resObs) > 0) {
            // Generar el HTML de las filas de la tabla de observaciones
            while ($rowObs = $resObs->fetch_assoc()) {
                $inicioFecha = date('d/m/Y', strtotime($rowObs['fechaObservacion']));
                $inicioHora = date('H:i', strtotime($rowObs['fechaObservacion']));
                $cadena = '1';
                $cadena .= '<tr>';
                $cadena .=  '<td style="vertical-align:middle;" nowrap="nowrap">' . $inicioFecha . '<br>' . $inicioHora . '</td>';
                $cadena .=  '<td style="vertical-align:middle;" nowrap="nowrap">' . $rowObs['creadorObservacion'] . '</td>';
                $cadena .=  '<td style="vertical-align:middle;" nowrap="nowrap">' . $rowObs['detalleObservacion'] . '</td>';
                $cadena .=  '</tr>';
                echo $cadena;
            }
        } else {
            // Si no hay resultados, imprimir una fila con un mensaje indicando que no hay observaciones
            echo '<tr><td colspan="3" style="text-align:center;">No hay observaciones para esta solicitud de compra.</td></tr>';
        }
    }

}else{
    echo '';
}

?>