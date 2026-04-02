<?php
// Archivo: tu_servidor.php

include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

if (!isset($_POST['idCarpetaAjax']) || intval($_POST['idCarpetaAjax']) <= 0) {
    echo json_encode([]);
    exit;
}

$idCarpetaAjax = intval($_POST['idCarpetaAjax']);

$getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
$getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();
$getIDCategoriaSistemaBMS  = getIDCategoriaSistemaBMS();

$params = [];
$sqlEquipo = "";

switch ($idCarpetaAjax) {
    case $getIDCategoriaSistemaCCTV:
        $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = ? ORDER BY piso ASC, nombreEquipo ASC";
        $params = [$idCarpetaAjax];
        break;

    case $getIDCategoriaSistemaCCAA:
        $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = ? AND (modelo = 'ASC1208C-S' OR modelo = 'ASC1204C-S') ORDER BY piso ASC, nombreEquipo ASC";
        $params = [$idCarpetaAjax];
        break;

    case $getIDCategoriaSistemaBMS:
        $sqlEquipo = "SELECT * FROM equipamiento_hbqp 
                      WHERE idCategoria = ? 
                      AND modelo LIKE 'BMS'
                      AND nombreEquipo NOT LIKE '%UMA-%'
                      AND nombreEquipo NOT LIKE '%Equipos HBQP%'
                      ORDER BY nombreEquipo ASC";
        $params = [$idCarpetaAjax];
        break;

    default:
        $sqlEquipo = "SELECT * FROM equipamiento_hbqp 
                      WHERE idCategoria = ? 
                      ORDER BY ordenEquipo ASC, piso ASC, pma ASC, nombreEquipo ASC";
        $params = [$idCarpetaAjax];
        break;
}

$stmt = $conexion->prepare($sqlEquipo);

// Solo si hay parámetros que bindear (todas las consultas anteriores los tienen)
if (!empty($params)) {
    $types = str_repeat("i", count($params)); // todos los parámetros son int (idCategoria)
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$datos = [];

while ($row = $result->fetch_assoc()) {

    $idEquipo = (int)$row['id'];
    $nombreEquipo = $row['nombreEquipo'];
    $nombreEquipoAttr = htmlspecialchars($nombreEquipo, ENT_QUOTES, 'UTF-8');

    $idCategoria = (int)$row['idCategoria'];
    $tipoMenu = $row['tipoMenu'];
    $estadoEquipo = $row['estadoEquipo'];

    $btnEstado = "Error al Obtener Información";
    $btnHojaVida = "Deshabilitado";
    $btnInfo = "Deshabilitado";
    $btnEliminar = "Deshabilitado";

    if($idEquipo > 0){
        $btnHojaVida = "<button class='btn btn-primary btn-hojaVida btn-block' id='btnHojaVida' data-idequipover='$idEquipo'><i class='fas fa-file'></i></button>";
        $btnInfo = "<button class='btn btn-warning btn-infoEquipo btn-block' id='btnInformacion' data-idequipoinfo='$idEquipo' data-idcategoriainfo='$idCategoria' data-tipomenu='$tipoMenu'><i class='fas fa-search'></i></button>";
        $btnEliminar = "<button class='btn btn-danger btn-eliminarEquipo btn-block' id='btnEliminar' data-ideliminar='$idEquipo'><i class='fas fa-trash'></i></button>";

    }

    switch ($estadoEquipo) {
        case 'Operativo':
            $btnEstado = "<span class='text-success font-weight-bold'><i class='fas fa-check'></i>&nbsp;&nbsp;OPERATIVO</span>";
            break;

        case 'Mantenimiento':
            $btnEstado = "<span class='text-warning font-weight-bold'><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;EN MANTENIMIENTO</span>";
            break;

        case 'OperativoObs':
            $btnEstado = "<span class='text-secondary font-weight-bold'><i class='fas fa-exclamation-circle'></i>&nbsp;&nbsp;OPERATIVO CON OBSERVACIONES</span>";
            break;

        case 'Extraviado':
            // No existe text-purple en Bootstrap 4, así que usamos color personalizado
            $btnEstado = "<span style='color: #6f42c1; font-weight: bold;'><i class='fas fa-question'></i>&nbsp;&nbsp;EXTRAVIADO</span>";
            break;

        default:
            $btnEstado = "<span class='text-danger font-weight-bold'><i class='fas fa-skull-crossbones'></i>&nbsp;&nbsp;FUERA DE SERVICIO</span>";
            break;
    }

    $btnEstado .= "<br><button class='btn btn-sm btn-secondary btn-estadoEquipo mt-1' data-idequipoest='$idEquipo' data-idcategest='$idCategoria' data-nomequipoest='$nombreEquipoAttr'><i class='fas fa-edit'></i></button>";


    $datos[] = [
        'id'                 => max(0, $idEquipo),
        'identificadorUnico' => $row['identificadorUnico'],
        'nombreEquipo'      => !empty($nombreEquipo) ? $nombreEquipo : '-',
        'recinto'           => !empty($row['recinto']) ? $row['recinto'] : '-',
        'pma'               => !empty($row['pma']) ? $row['pma'] : '-',
        'piso'              => !empty($row['piso']) ? $row['piso'] : '-',
        'estadoEquipo'      => !empty($estadoEquipo) ? $estadoEquipo : '-',
        'idCategoria'       => max(0, $idCategoria),
        'tipoMenu'          => !empty($tipoMenu) ? $tipoMenu : '-',
        'btnHojaVida'        => $btnHojaVida,
        'btnInfo'            => $btnInfo,
        'btnEliminar'        => $btnEliminar,
        'btnEstado'          => $btnEstado
    ];
}

$stmt->close();
$conexion->close();

echo json_encode($datos);
?>
