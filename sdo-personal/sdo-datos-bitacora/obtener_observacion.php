<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';
// Obtener el ID del POST


if (isset($_POST['id'])) {

    $id = $_POST['id'];

    if($id > 0){

        $getBitacora = array();
        $getBitacora = getBitacora($id);

        if($getBitacora == 'null' || empty($getBitacora)){
            echo 'null';
        }else{

            $idBitacora = $getBitacora['idBitacora'];
            $observacionBitacora = $getBitacora['observacionBitacora'];
            $fechaBitacora = $getBitacora['fechaBitacora'];
            $fecha2 = strtotime($fechaBitacora);
            $fecha3 = fechaEspañol($fecha2);
            $horaObs = date('H:i',$fecha2);

            $tecnicoBitacora = $getBitacora['tecnicoBitacora'];
            $idSubcategoria = $getBitacora['idSubcategoria'];

            $observacion = '<b>Tecnico: </b>'.$tecnicoBitacora.'<br>';
            if($idSubcategoria > 0){
                $nomSubcategoria = getNomSubCategoria($idSubcategoria);
                $observacion .= '<b>Categoria: </b>'.$nomSubcategoria.'<br>';
            }
            if($fechaBitacora > 0 || empty($fechaBitacora)){
                $observacion .= '<b>Fecha: </b>'.$fecha3.' a las '.$horaObs.' hrs.<br>';
            }
            $observacion .= '<br><b>Observacion:</b><br><br>'.$observacionBitacora.'<br>';

            $observacion = str_replace("<br>", "\n", $observacion); // Reemplaza los saltos de línea con etiquetas HTML de salto de línea
            echo $observacion;

        }


    }else{
        echo 'null';
    }

}else{
    echo 'null';
}