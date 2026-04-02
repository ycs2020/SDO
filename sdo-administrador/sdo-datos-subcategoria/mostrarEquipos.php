<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['idSubcategoria'])) {

    $idSubcategoria = trim($_POST['idSubcategoria']);

    $cadena = '';

    if (!empty($idSubcategoria) && $idSubcategoria > 0) {

        $contarEquipos = contarEquipos($idSubcategoria);

        if ($idSubcategoria > 0) {
            $query = "SELECT * FROM equipos WHERE idSubCategoria = '" . $idSubcategoria . "' ORDER BY nomEquipo ASC";

            $res = mysqli_query($conexion, $query);

            $_SESSION['idSubCategoria'] = $idSubcategoria;	  
    
            $numReporte = 1;
    
            while ($row = $res->fetch_assoc()) {
    
                $cadena .= '<tr>';
    
                $cadena .= '<td nowrap="nowrap" style="vertical-align:middle;"><center>'.$numReporte.'</center></td>';
    
                $cadena .= '<td nowrap="nowrap"  style="vertical-align:middle;" align="justify">'.mb_strtoupper($row["nomEquipo"]).'</td>';
    
                $idEquipo = trim($row['idEquipo']);
    
                if(!empty($idEquipo) || $idEquipo > 0){
    
                    $cadena .= '<td style="vertical-align:middle;">';
    
                    $cadena .= '<form class="" action="../sdo-administrador/equipos-observaciones.php" method="post">';
    
                    $cadena .= '<input type="hidden" name="idEquipo" value="'.$idEquipo.'">';
    
                    $cadena .= '<center><input type="submit" id="inputObservaciones" class="btn btn-primary" value="Observaciones"></center>'; 
    
                    $cadena .= '</form>';
    
                    $cadena .= '</td>';
                }else{
    
                    $cadena .= '<td style="vertical-align:middle;">';
                    $cadena .= '<center>Error al Cargar Datos</center></td>';
                }
    
    
    
    
                $cadena .= '</tr>';
    
                $numReporte++;
            }
        }else{
            $cadena .= '<tr>';
            $cadena .= '<td colspan="3"><center>No Existen Registros.</center></td>';
            $cadena .= '</tr>';

        }

        
    } else {
        $cadena .= '<tr>';
        $cadena .= '<td colspan="3"><center>No Existen Registros.</center></td>';
        $cadena .= '</tr>';
    }

    echo $cadena;
}
