<?php
include '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');
header('Content-Type: application/json');

// Recibir datos desde AJAX
$idGrupo = $_POST['idGrupo'] ?? null;
$puertasJSON = $_POST['puertas'] ?? '[]';
$puertas = json_decode($puertasJSON, true);

if (!$idGrupo || !is_numeric($idGrupo)) {
    echo json_encode(['exito' => false, 'mensaje' => 'ID de grupo inválido.']);
    exit;
}

if (!is_array($puertas) || count($puertas) === 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se recibieron puertas.']);
    exit;
}

$insertados = 0;
$errores = 0;

foreach ($puertas as $puerta) {
    $numUCA = $puerta['uca'] ?? null;
    $numPuerta = $puerta['puerta'] ?? null;

    if (!is_numeric($numUCA) || !is_numeric($numPuerta)) {
        $errores++;
        continue;
    }

    $numPuerta++;

    // Verificar si ya existe esa combinación
    $check = $conexion->prepare("SELECT COUNT(*) FROM protoc_ccaa_grupo_puerta WHERE idGrupo = ? AND numUCA = ? AND numPuerta = ?");
    $check->bind_param('iii', $idGrupo, $numUCA, $numPuerta);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->close();

    if ($existe > 0) {
        continue; // duplicado
    }

    // Insertar nuevo registro
    $stmt = $conexion->prepare("INSERT INTO protoc_ccaa_grupo_puerta (idGrupo, numUCA, numPuerta, fechaGrupoPuerta) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('iii', $idGrupo, $numUCA, $numPuerta);

    if ($stmt->execute()) {
        $insertados++;
    } else {
        $errores++;
    }

    $stmt->close();
}

$conexion->close();

echo json_encode([
    'exito' => true,
    'mensaje' => "Se agregaron $insertados puertas. $errores errores."
]);
