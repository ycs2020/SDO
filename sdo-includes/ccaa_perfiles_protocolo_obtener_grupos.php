<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

$id = $_POST['idPerfilProtocolo'] ?? null;

$response = [];

if (!$id || !is_numeric($id) || $id < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$stmt = $conexion->prepare("
    SELECT 
        pcpg.idPerfilGrupo AS idPerfilGrupo,
        pcpg.idGrupo AS idGrupo,
        gr.nomGrupo AS grupo,
        gr.descripGrupo AS descripcion
    FROM protoc_ccaa_perfil_grupo pcpg
    INNER JOIN protoc_ccaa_grupo gr ON pcpg.idGrupo = gr.idGrupo
    WHERE idPerfil = ?
    ORDER BY
        gr.nomGrupo ASC
");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    if (!empty($row["idPerfilGrupo"]) && $row["idPerfilGrupo"] > 0) {

        $row["botonVisualizar"] = '
         <button class="btn btn-block btn-success btn-sm" id="btnVerPuertas" data-idgrupo="'.$row["idGrupo"].'">
                   <i class="fas fa-eye"></i>
          </button>
       ';

        $row["botonEliminar"] = '
       <button class="btn btn-sm btn-danger btn-block" onclick="eliminarGrupo('.$row["idPerfilGrupo"].')">
            <i class="fas fa-trash-alt"></i>
       </button>
       ';
    } else {
        $row["botonVisualizar"] = 'Deshabilitado';

        $row["botonEliminar"] = 'Deshabilitado';
    }


    $response[] = $row;
}

$stmt->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode($response);
