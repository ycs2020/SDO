<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['nomUnidad'],$_POST['nomJefeSolicita'])) {

    $nomUnidad = trim($_POST['nomUnidad']);
    $nomJefeSolicita = trim($_POST['nomJefeSolicita']);

    $cadena = '';

    if (empty($nomUnidad) || empty($nomJefeSolicita)) {

    } else {

        if($nomUnidad == 'OTRO' || $nomUnidad == 'Otro' || $nomUnidad == 'otro'){

            $cadena .= 'Habilitar';

        }else{
            $cadena .= '<label for="exampleInputCodigoTarjeta">Nombre de Quien Solicita:<code>*</code></label>';
            $cadena .= '<div class="input-group mb-3">';
            $cadena .= '<div class="input-group-prepend">';
            $cadena .= '<span class="input-group-text"><i class="fas fa-user"></i></span>';
            $cadena .= '</div>';
            $cadena .= '<select id="nomSolicitante" class="form-control" name="nomSolicitante">';
    
            $cadena .= '<option value="">Seleccione...</option>';
    
            $queryJefeUnidad = "SELECT DISTINCT nomSolicitante FROM actas WHERE unidadTarjeta = '$nomUnidad' ORDER BY nomSolicitante ASC";
    
            $resJefeUnidad = mysqli_query($conexion, $queryJefeUnidad);
    
            while ($rowJefeUnidad = $resJefeUnidad->fetch_assoc()) {
    
                $nomJefe = mb_convert_case($rowJefeUnidad['nomSolicitante'], MB_CASE_UPPER, 'UTF-8');

                if($rowJefeUnidad['nomSolicitante'] == $nomJefeSolicita){

                    $cadena .= '<option value="' . $rowJefeUnidad['nomSolicitante'] . '" selected>' . $rowJefeUnidad['nomSolicitante'] . '</option>';
                }else{

                    $cadena .= '<option value="' . $rowJefeUnidad['nomSolicitante'] . '">' . $rowJefeUnidad['nomSolicitante'] . '</option>';
                }
    
            }
    
    
            $cadena .= '<option value="OtroNombre">AGREGAR OTRO NOMBRE...</option>';
    
            $cadena .= '</select>';
            $cadena .= '</div>';
        }

        
    }

    echo $cadena;
}
