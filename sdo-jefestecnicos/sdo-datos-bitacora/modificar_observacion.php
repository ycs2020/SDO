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

            $data = array(
                'nomTecnico' => $tecnicoBitacora,
                'obserMod' => $observacionBitacora,
                'idSubCateg' => $idSubcategoria,
                'idBitacoraMod' => $idBitacora,
                // ... más campos
            );

            echo json_encode($data);

        }


    }else{
        echo 'null';
    }

}else{
    echo 'null';
}