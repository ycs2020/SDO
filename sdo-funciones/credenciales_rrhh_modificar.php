<?php
session_start();
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

// ─────────────────────────────────────────────
// Función limpiar
function limpiar($valor) {
    return trim(htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'));
}

// ─────────────────────────────────────────────
// Variables
$idCredencial    = intval($_POST['idCredencial'] ?? 0);
$nombre          = limpiar($_POST['nombreCredencial'] ?? '');
$apellido        = limpiar($_POST['apellidoCredencial'] ?? '');
$rut             = limpiar($_POST['rutCredencial'] ?? '');
$telefono        = limpiar($_POST['telefonoCredencial'] ?? '');
$esBecado = isset($_POST['valorBeca']) ? 1 : 0;
$esDiscapacitado = isset($_POST['valorDiscapacidad']) ? 1 : 0;


if($esBecado == 1){
    $textoBecadoAhora = "SI";
}else{
    $textoBecadoAhora = "NO";
}

if($esDiscapacitado == 1){
    $textoDiscapAhora = "SI";
}else{
    $textoDiscapAhora = "NO";
}

$usuarioModifica = $_SESSION['nomUsuario'] ?? 'Desconocido';

// ─────────────────────────────────────────────
// Validaciones
if ($idCredencial <= 0 || $nombre === '' || $apellido === '' || $rut === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Faltan campos obligatorios']);
    exit;
}

// ─────────────────────────────────────────────
// Obtener datos ANTERIORES
$sqlAnterior = "
    SELECT nombreCredencial, apellidoCredencial, rutCredencial, telefonoCredencial,
           esBecado, esDiscapacitado
    FROM credenciales_rrhh
    WHERE idCredencial = ?
";

$stmtAnterior = $conexion->prepare($sqlAnterior);
$stmtAnterior->bind_param("i", $idCredencial);
$stmtAnterior->execute();
$resultAnterior = $stmtAnterior->get_result();

if ($resultAnterior->num_rows === 0) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Credencial no encontrada']);
    exit;
}

$anterior = $resultAnterior->fetch_assoc();
$stmtAnterior->close();

$nombreAnt   = $anterior['nombreCredencial'];
$apellidoAnt = $anterior['apellidoCredencial'];
$rutAnt      = $anterior['rutCredencial'];
$fonoAnt     = $anterior['telefonoCredencial'];
$becadoAnt   = $anterior['esBecado'];
$discapAnt   = $anterior['esDiscapacitado'];

if($becadoAnt == 1){
    $textoBecadoAnt = "SI";
}else{
    $textoBecadoAnt = "NO";
}

if($discapAnt == 1){
    $textoDiscapAnt = "SI";
}else{
    $textoDiscapAnt = "NO";
}

// ─────────────────────────────────────────────
// Verificar RUT duplicado
$sqlVerifica = "
    SELECT idCredencial 
    FROM credenciales_rrhh 
    WHERE rutCredencial = ? AND idCredencial != ?
";
$stmtVerifica = $conexion->prepare($sqlVerifica);
$stmtVerifica->bind_param("si", $rut, $idCredencial);
$stmtVerifica->execute();
$stmtVerifica->store_result();

if ($stmtVerifica->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Ese RUT ya está asignado a otra credencial'
    ]);
    exit;
}
$stmtVerifica->close();

// ─────────────────────────────────────────────
// UPDATE
$sqlUpdate = "
    UPDATE credenciales_rrhh
    SET nombreCredencial = ?, 
        apellidoCredencial = ?, 
        rutCredencial = ?, 
        telefonoCredencial = ?,
        esBecado = ?,
        esDiscapacitado = ?
    WHERE idCredencial = ?
";
$stmt = $conexion->prepare($sqlUpdate);

$stmt->bind_param(
    "ssssiii",
    $nombre,
    $apellido,
    $rut,
    $telefono,
    $esBecado,
    $esDiscapacitado,
    $idCredencial
);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Error al modificar: ' . $stmt->error
    ]);
    exit;
}
$stmt->close();

// ─────────────────────────────────────────────
// Observación ANTES / DESPUÉS
$observacion = "MODIFICACIÓN DE CREDENCIAL\n\n" .
    "USUARIO: $usuarioModifica\n\n" .
    "ANTES:\n" .
    "Nombre   : $nombreAnt\n" .
    "Apellido : $apellidoAnt\n" .
    "RUT      : $rutAnt\n" .
    "Teléfono : $fonoAnt\n\n" .
    "Becado      : $textoBecadoAnt\n" .
    "Discapacidad: $textoDiscapAnt\n\n" .
    "AHORA:\n" .
    "Nombre   : $nombre\n" .
    "Apellido : $apellido\n" .
    "RUT      : $rut\n" .
    "Teléfono : $telefono\n".
    "Becado      : $textoBecadoAhora\n" .
    "Discapacidad: $textoDiscapAhora\n\n";

$sqlObs = "
    INSERT INTO credenciales_rrhh_observaciones
    (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion)
    VALUES (?, NOW(), ?, 'MODIFICACIÓN', ?)
";
$stmtObs = $conexion->prepare($sqlObs);
$stmtObs->bind_param("iss", $idCredencial, $usuarioModifica, $observacion);
$stmtObs->execute();
$stmtObs->close();

// ─────────────────────────────────────────────
// RESPUESTA FINAL
echo json_encode([
    'status'       => 'ok',
    'mensaje'      => 'Credencial modificada correctamente',
    'idCredencial' => $idCredencial
]);
exit;
