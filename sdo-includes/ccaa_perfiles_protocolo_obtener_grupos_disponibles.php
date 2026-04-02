<?php
include '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$idPerfil = $_GET['idPerfil'] ?? null;

if (!$idPerfil || !is_numeric($idPerfil)) {
    echo json_encode([]);
    exit;
}

// obtener grupos ya asociados
$stmt = $conexion->prepare("SELECT idGrupo FROM protoc_ccaa_perfil_grupo WHERE idPerfil = ?");
$stmt->bind_param("i", $idPerfil);
$stmt->execute();
$res = $stmt->get_result();

$gruposAsignados = [];
while ($row = $res->fetch_assoc()) {
    $gruposAsignados[] = $row['idGrupo'];
}
$stmt->close();

// obtener TODOS los grupos
$sql = "SELECT idGrupo, nomGrupo FROM protoc_ccaa_grupo ORDER BY nomGrupo ASC";
$res2 = $conexion->query($sql);

$gruposDisponibles = [];

while ($row = $res2->fetch_assoc()) {

    if (in_array($row['idGrupo'], $gruposAsignados)) {
        continue; // omitir los ya usados (exactamente como tu función PHP)
    }

    $gruposDisponibles[] = [
        "idGrupo" => $row['idGrupo'],
        "nomGrupo" => mb_convert_case($row['nomGrupo'], MB_CASE_UPPER, "UTF-8")
    ];
}

echo json_encode($gruposDisponibles);
