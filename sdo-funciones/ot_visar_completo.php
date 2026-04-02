<?php

include_once 'conexion.php';
include_once 'funciones.php';

$nomUsuarioOT = 'Yorgo Casimis Subiabre';

date_default_timezone_set('America/Santiago');

$sqlObtenerHTTerminadas = "SELECT idOrden, folioOrden, fechaOrden, noOrdenTrabajo FROM ordenes_trabajos_general WHERE isVisado = 0 AND nomSolicitante LIKE '$nomUsuarioOT' ORDER BY fechaOrden ASC";

//echo $sqlObtenerHTTerminadas.'<br>';

$resObtenerTerminadas = mysqli_query($conexion, $sqlObtenerHTTerminadas);

$countHTTerminadas = mysqli_num_rows($resObtenerTerminadas);

if($countHTTerminadas > 0){

    while ($row = mysqli_fetch_array($resObtenerTerminadas)) {
        $idOrden = $row["idOrden"];
        $folioOrden = $row['folioOrden'];
        $fechaOrden = $row['fechaOrden'];
        $noOrdenTrabajo = $row['noOrdenTrabajo'];

        $fechaOrdenTime = strtotime($fechaOrden);
        $fechaOrdenFormat = date('d/m/Y', $fechaOrdenTime);
        $horaOrdenFormat = date('H:i', $fechaOrdenTime);

        echo "-> $idOrden .- $fechaOrdenFormat $horaOrdenFormat Hrs. -- $folioOrden -- $noOrdenTrabajo<br><br>";

        $sqlObtenerOTTerminadas = "SELECT idTG, folioTG, fechaProgramacion FROM trabajos_general WHERE isVisado = 0 AND solicitanteTG LIKE '$nomUsuarioOT' AND folioTG LIKE '$noOrdenTrabajo' ORDER BY fechaProgramacion ASC";

//    echo "---> $sqlObtenerOTTerminadas<br><br>";

        $resObtenerOTTerminadas = mysqli_query($conexion, $sqlObtenerOTTerminadas);

        $countOTTerminadas = mysqli_num_rows($resObtenerOTTerminadas);

        if($countOTTerminadas > 0){

            while ($rowObtenerOTTerminadas = mysqli_fetch_array($resObtenerOTTerminadas)) {
                $idTG = $rowObtenerOTTerminadas["idTG"];
                $folioTG = $rowObtenerOTTerminadas["folioTG"];
                $fechaProgramacion = $rowObtenerOTTerminadas["fechaProgramacion"];

                $fechaProgramacionTime = strtotime($fechaProgramacion);
                $fechaProgramacionFormat = date('d/m/Y', $fechaProgramacionTime);
                $horaProgramacionFormat = date('H:i', $fechaProgramacionTime);

                echo "---> $idTG .- $fechaProgramacionFormat $horaProgramacionFormat Hrs. -- $folioTG<br><br>";


            }

            echo '<hr>';

        }else{
            echo 'Sin OTs';
        }

        /*uuuuy*/
    }

}else{
    echo 'Sin HT';
}



