<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomUnidad'])) {

    $nomUnidad = trim($_POST['nomUnidad']);

    $cadena = '';

    if (empty($nomUnidad)) {

    } else {

        if($nomUnidad == 'OTRO' || $nomUnidad == 'Otro' || $nomUnidad == 'otro'){

            $cadena .= 'Habilitar';

        }else{
            $cadena .= '<label for="exampleInputCodigoTarjeta">Nombre de Quien Solicita:<code>*</code></label>';
            $cadena .= '<div class="input-group mb-3">';
            $cadena .= '<div class="input-group-prepend">';
            $cadena .= '<span class="input-group-text"><i class="fas fa-user"></i></span>';
            $cadena .= '</div>';
            $cadena .= '<select id="nomSolicitante1" class="form-control" name="nomSolicitante1">';
    
            $cadena .= '<option value="">Seleccione...</option>';
    
            $queryJefeUnidad = "SELECT DISTINCT nomSolicitante FROM actas WHERE unidadTarjeta = '$nomUnidad' ORDER BY nomSolicitante ASC";
    
            $resJefeUnidad = mysqli_query($conexion, $queryJefeUnidad);
    
            while ($rowJefeUnidad = $resJefeUnidad->fetch_assoc()) {
    
                $nomJefe = mb_convert_case($rowJefeUnidad['nomSolicitante'], MB_CASE_UPPER, 'UTF-8');
    
                $cadena .= '<option value="' . $rowJefeUnidad['nomSolicitante'] . '">' . $rowJefeUnidad['nomSolicitante'] . '</option>';
            }
    
    
            $cadena .= '<option value="OTRONOMBRE">AGREGAR OTRO NOMBRE...</option>';
    
            $cadena .= '</select>';

            $cadena .= '</div>';
        }

        
    }

    echo $cadena;
}
