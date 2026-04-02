<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if ($id > 0) {
        $getParamPabellonID = getParamPabellonID($id);

        if ($getParamPabellonID == 'null' || empty($getParamPabellonID)) {
            echo 'null';
        } else {
            $data = $getParamPabellonID; // Usar directamente el array ya obtenido
            echo json_encode($data);
        }
    } else {
        echo 'null';
    }
} else {
    echo 'null';
}
?>
