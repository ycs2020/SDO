<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

$id = $_POST['idGrupoProtocolo'] ?? null;

$response = [];

if (!$id || !is_numeric($id) || $id < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$stmt = $conexion->prepare("
    SELECT 
        pcgp.idGrupoPuerta AS idGrupoPuerta,
        ad.name AS puerta,
        ul.name AS uca
    FROM protoc_ccaa_grupo_puerta pcgp
    INNER JOIN acs_door ad ON (pcgp.numPuerta - 1) = ad.no AND pcgp.numUCA = ad.idDevice
    INNER JOIN ucas_listado ul ON pcgp.numUCA = ul.idUnique
    WHERE idGrupo = ?
    ORDER BY
        CASE
            WHEN ul.name LIKE '%-P0' THEN 0
            WHEN ul.name LIKE '%-P1' THEN 1
            WHEN ul.name LIKE '%-P2' THEN 2
            WHEN ul.name LIKE '%-P3' THEN 3
            WHEN ul.name LIKE '%-P4' THEN 4
            WHEN ul.name LIKE '%-P5' THEN 5
            WHEN ul.name LIKE '%-P6' THEN 6
            ELSE 99
        END,
        ul.name ASC,
        ad.name ASC
");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    if (!empty($row["idGrupoPuerta"]) && $row["idGrupoPuerta"] > 0) {
        $row["botonGrupo"] = '
       <button class="btn btn-sm btn-danger btn-block" onclick="eliminarPuerta('.$row["idGrupoPuerta"].')">
            <i class="fas fa-trash-alt"></i>
       </button>
       ';
    } else {
        $row["botonGrupo"] = 'Deshabilitado';
    }


    $response[] = $row;
}

$stmt->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode($response);
