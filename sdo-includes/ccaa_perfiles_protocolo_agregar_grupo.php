<?php
include '../sdo-funciones/conexion.php';


date_default_timezone_set('America/Santiago');
header('Content-Type: application/json');

// Recibir datos desde AJAX
$idPerfil = $_POST['idPerfil'] ?? null;
$grupoJSON = $_POST['grupo'] ?? '[]';
$grupos = json_decode($grupoJSON, true);

if (!$idPerfil || !is_numeric($idPerfil)) {
    echo json_encode(['exito' => false, 'mensaje' => 'ID de Perfil Inválido.']);
    exit;
}

if (!is_array($grupos) || count($grupos) === 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se recibieron grupos.']);
    exit;
}

$insertados = 0;
$duplicados = 0;
$erroresFormato = 0;
$erroresInsert = 0;

foreach ($grupos as $item) {

    // Permite JSON del tipo [5,10,18] o [{"grupo":5},{"grupo":10}]
    $idGrupo = is_array($item) ? ($item['grupo'] ?? null) : $item;

    if (!is_numeric($idGrupo)) {
        $erroresFormato++;
        continue;
    }

    // Verificar duplicado
    $check = $conexion->prepare("
        SELECT COUNT(*) 
        FROM protoc_ccaa_perfil_grupo 
        WHERE idPerfil = ? AND idGrupo = ?
    ");
    $check->bind_param('ii', $idPerfil, $idGrupo);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->close();

    if ($existe > 0) {
        $duplicados++;
        continue;
    }

    // Insertar
    $stmt = $conexion->prepare("
        INSERT INTO protoc_ccaa_perfil_grupo (idPerfil, idGrupo, fechaPerfilGrupo)
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param('ii', $idPerfil, $idGrupo);

    if ($stmt->execute()) {
        $insertados++;
    } else {
        $erroresInsert++;
    }

    $stmt->close();
}

$conexion->close();

echo json_encode([
    'exito'         => true,
    'insertados'    => $insertados,
    'duplicados'    => $duplicados,
    'erroresFormato'=> $erroresFormato,
    'erroresInsert' => $erroresInsert,
    'mensaje'       => "Insertados: $insertados | Duplicados: $duplicados | Errores Formato: $erroresFormato | Errores Insert: $erroresInsert"
]);
