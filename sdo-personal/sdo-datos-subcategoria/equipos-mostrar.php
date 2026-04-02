<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idSubcategoria'])) {

    $idSubcategoria = trim($_POST['idSubcategoria']);

    $cadena = '';
    

    if (!empty($idSubcategoria)) {

        $query = "SELECT * FROM equipos WHERE idSubCategoria = '" . $idSubcategoria . "' ORDER BY nomEquipo ASC";

        $res = mysqli_query($conexion, $query);

        $numReporte = 1;

        while ($row = $res->fetch_assoc()) {

            $cadena .= '<tr>';

            $cadena .= '<td style="vertical-align:middle;" align="justify">'.$numReporte.'</td>';

            $cadena .= '<td style="vertical-align:middle;" align="justify">'.mb_strtoupper($row["nomEquipo"]).'</td>';

            $cadena .= '<td style="vertical-align:middle;">';

            $idEquipo = trim($row['idEquipo']);

            if(!empty($idEquipo) || $idEquipo > 0){

                $cadena .= '<form style="margin: 0; text-align: center;" class="" action="../sdo-personal/equipos-observaciones.php" method="post">';

                $cadena .= '<input type="hidden" name="idEquipo" value="'.$idEquipo.'">';

                $cadena .= '<input type="submit" id="inputObservaciones" class="btn btn-primary" value="Observaciones">'; 

                $cadena .= '</form>';

                $cadena .= '</td>';
            }else{
                $cadena .= 'Error al Cargar Datos</center></td>';
            }




            $cadena .= '</tr>';

            $numReporte++;
        }
    } else {
    }

    echo $cadena;
}
