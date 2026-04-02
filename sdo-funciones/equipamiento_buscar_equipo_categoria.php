<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$texto = $_POST['texto'] ?? '';
$idCarpeta = $_POST['idCarpeta'] ?? 0;

$sql = "SELECT id, nombreEquipo, marca, modelo, idEquipoPadre, tipoMenu
        FROM equipamiento_hbqp
        WHERE idCategoria = '$idCarpeta'
        AND (
            nombreEquipo LIKE '%$texto%' OR
            marca LIKE '%$texto%' OR
            modelo LIKE '%$texto%'
        )";

$res = mysqli_query($conexion, $sql);

while ($row = mysqli_fetch_assoc($res)) {

    $id = $row['id'];
    $nombreEquipo = $row['nombreEquipo'];
    $marca = $row['marca'];
    $modelo = $row['modelo'];
    $idEquipoPadre = $row['idEquipoPadre'];
    $tipoMenu = $row['tipoMenu'];
    $tipoMenu2 = mb_convert_case($tipoMenu, MB_CASE_LOWER, 'UTF-8');
    $urlDestino = '';

    switch ($tipoMenu2) {
        case 'nuevo':
            $tipoMenu2 = 'nuevo';
            break;
        default:
            $tipoMenu2 = 'antiguo';
            break;
    }


    if (!empty($nombreEquipo)) {
        $nombreEquipo2 = mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8');
    } else {
        $nombreEquipo2 = 'NO APLICA.';
    }

    if (!empty($marca)) {
        $marca2 = mb_convert_case($marca, MB_CASE_UPPER, 'UTF-8');
    } else {
        $marca2 = 'NO APLICA.';
    }

    if (!empty($modelo)) {
        $modelo2 = mb_convert_case($modelo, MB_CASE_UPPER, 'UTF-8');
    } else {
        $modelo2 = 'NO APLICA.';
    }


    $rutaEquipo = $nombreEquipo2;

    if ($idEquipoPadre > 0) {

        while ($idEquipoPadre > 0) {

            $nomEquipamiento = buscarequipo($idEquipoPadre);

            $nomEquipamiento = trim($nomEquipamiento);

            $rutaEquipo = $nomEquipamiento . ' // ' . $rutaEquipo;

            $idEquipoPadre = obtenerIDEquipamientoPadre($idEquipoPadre);
        }

    }


    echo "
    <tr>

        <td>$nombreEquipo2</td>
        <td>$marca2</td>
        <td>$modelo2</td>
        <td>$rutaEquipo</td>
        <td>
            <button class='btn btn-block btn-warning btn-infoEquipo' data-idequipo='$id' data-idcategoria='$idCarpeta' data-tipomenu='$tipoMenu2'><i class='fas fa-eye'></i></button>
        </td>

    </tr>
    ";

}