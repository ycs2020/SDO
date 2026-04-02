<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="equipos.csv"');

$output = fopen('php://output', 'w');

$idCarpeta = 1;

fputcsv($output, array('ID', 'Nombre Equipo', 'Recinto', 'PMA', 'Piso', 'Estado'));

$getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
$getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();

if($idCarpeta == $getIDCategoriaSistemaCCTV){
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta ORDER BY piso ASC, nombreEquipo ASC";
} else if($idCarpeta == $getIDCategoriaSistemaCCAA){
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND (modelo = 'ASC1208C-S' OR  modelo = 'ASC1204C-S') ORDER BY piso ASC, nombreEquipo ASC";
} else {
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta ORDER BY  ordenEquipo ASC,piso ASC, pma ASC, nombreEquipo ASC";
}

$resEquipo = mysqli_query($conexion, $sqlEquipo);

while ($rowEquipo = $resEquipo->fetch_assoc()) {
    $datos = [];
    $datos['id'] = $rowEquipo['id'];
    $datos['nombreEquipo'] = $rowEquipo['nombreEquipo'];
    $datos['recinto'] = $rowEquipo['recinto'];
    $datos['pma'] = $rowEquipo['pma'];
    $datos['piso'] = $rowEquipo['piso'];
    $datos['estadoEquipo'] = $rowEquipo['estadoEquipo'];

    $idEquipo = $datos['id'];
    $nombreEquipo = $datos['nombreEquipo'];
    $nombreEquipo = mb_convert_case($nombreEquipo, MB_CASE_UPPER, 'UTF-8');
    $recinto = $datos['recinto'];
    $recinto = mb_convert_case($recinto, MB_CASE_UPPER, 'UTF-8');
    $pma = $datos['pma'];
    $pma = mb_convert_case($pma, MB_CASE_UPPER, 'UTF-8');
    $piso = $datos['piso'];
    $estadoEquipo = $datos['estadoEquipo'];

    if ($recinto == '') {
        $recinto = '-';
    }

    if ($pma == '') {
        $pma = '-';
    }

    if ($piso == '' || $piso == 0) {
        $piso = '-';
    }

    fputcsv($output, array($idEquipo, $nombreEquipo, $recinto, $pma, $piso, $estadoEquipo));
}

fclose($output);
exit;
?>
