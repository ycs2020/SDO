<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require '../sdo-funciones/conexion.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
date_default_timezone_set('America/Santiago');

// ===============================================
// 📌 DATOS INICIALES
// ===============================================
$idCredencial   = intval($_POST['idCredencialTarjetaModal']);
$codigoTarjeta  = trim($_POST['codigoTarjeta']);
$tipoTarjeta    = trim($_POST['tipoTarjeta']);
$unidadTarjeta  = trim($_POST['unidadTarjeta']);
$textoTarjeta   = trim($_POST['textoTarjeta']);
$fechaHoy       = date('Y-m-d');
$usuario        = $_SESSION['nomUsuario'] ?? 'Plataforma SDO';


// ===============================================
// 🔒 VALIDACIÓN: tarjeta ya asociada a otra credencial
// ===============================================
$sqlSolape = "SELECT d.rutCredencial AS rut, CONCAT(d.nombreCredencial, ' ', d.apellidoCredencial) AS nombreCompleto
              FROM credenciales_rrhh_registro_tarjetas crrt
              INNER JOIN credenciales_rrhh_tarjetas crt ON crrt.idTarjeta = crt.idTarjeta
              INNER JOIN credenciales_rrhh d ON crrt.idCredencial = d.idCredencial
              WHERE crt.codigoTarjeta = ? 
                AND crrt.idCredencial != ? 
                AND crrt.activacionCredencial IN (1, 2, 3)
              LIMIT 1";

$stmtSolape = $conexion->prepare($sqlSolape);
$stmtSolape->bind_param("si", $codigoTarjeta, $idCredencial);
$stmtSolape->execute();
$resSolape = $stmtSolape->get_result();

if ($resSolape->num_rows > 0) {
    $row = $resSolape->fetch_assoc();
    $rutFuncionario = $row['rut'];
    $nombreFuncionario = $row['nombreCompleto'];

    echo json_encode([
        'status' => 'error',
        'mensaje' => "Esta tarjeta ya está asignada a <strong>$nombreFuncionario ($rutFuncionario)</strong> y se encuentra activa o por habilitar."
    ]);
    exit;
}
$stmtSolape->close();


// ===============================================
// 🔍 BUSCAR O CREAR TARJETA
// ===============================================
$sqlBuscar = "SELECT idTarjeta FROM credenciales_rrhh_tarjetas WHERE codigoTarjeta = ?";
$stmtBuscar = $conexion->prepare($sqlBuscar);
$stmtBuscar->bind_param("s", $codigoTarjeta);
$stmtBuscar->execute();
$stmtBuscar->store_result();

if ($stmtBuscar->num_rows > 0) {
    $stmtBuscar->bind_result($idTarjeta);
    $stmtBuscar->fetch();
    $stmtBuscar->close();
} else {
    $stmtBuscar->close();
    $sqlInsert = "INSERT INTO credenciales_rrhh_tarjetas (codigoTarjeta, tipoTarjeta, unidadTarjeta, textoTarjeta) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conexion->prepare($sqlInsert);
    $stmtInsert->bind_param("ssss", $codigoTarjeta, $tipoTarjeta, $unidadTarjeta, $textoTarjeta);
    if (!$stmtInsert->execute()) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al crear tarjeta']);
        exit;
    }
    $idTarjeta = $stmtInsert->insert_id;
    $stmtInsert->close();
}


// ===============================================
// 🔁 AÑADIR NUEVO REGISTRO (nueva o reactivada)
// ===============================================
$sqlAsociar = "INSERT INTO credenciales_rrhh_registro_tarjetas 
               (idCredencial, idTarjeta, inicioTarjeta, terminoTarjeta, activacionCredencial) 
               VALUES (?, ?, ?, NULL, 2)";
$stmtAsociar = $conexion->prepare($sqlAsociar);
$stmtAsociar->bind_param("iis", $idCredencial, $idTarjeta, $fechaHoy);
if (!$stmtAsociar->execute()) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al asociar tarjeta']);
    exit;
}
$idRegistroNuevo = $stmtAsociar->insert_id;
$stmtAsociar->close();


// ===============================================
// ❌ DESACTIVAR OTRAS TARJETAS DE ESTA CREDENCIAL
// ===============================================
$sqlSelect = "SELECT crrt.idRegistro, crt.codigoTarjeta
              FROM credenciales_rrhh_registro_tarjetas crrt
              INNER JOIN credenciales_rrhh_tarjetas crt 
                      ON crrt.idTarjeta = crt.idTarjeta
              WHERE crrt.idCredencial = ?
                AND crrt.idRegistro != ?
                AND crrt.activacionCredencial != 0
                AND crrt.activacionCredencial != 3";

$stmtSelect = $conexion->prepare($sqlSelect);
$stmtSelect->bind_param("ii", $idCredencial, $idRegistroNuevo);
$stmtSelect->execute();
$resultAnteriores = $stmtSelect->get_result();

$tarjetasDesactivadas = [];
while ($row = $resultAnteriores->fetch_assoc()) {
    $tarjetasDesactivadas[] = $row['codigoTarjeta'];
    $sqlUpdate = "UPDATE credenciales_rrhh_registro_tarjetas 
                  SET activacionCredencial = 0, terminoTarjeta = ? 
                  WHERE idRegistro = ?";
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $fechaHoy, $row['idRegistro']);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}
$stmtSelect->close();


// ===============================================
// 📝 OBSERVACIONES (timestamp escalado)
// ===============================================
$fechaObs1 = date('Y-m-d H:i:s');
$fechaObs2 = date('Y-m-d H:i:s', strtotime($fechaObs1 . ' +1 second'));

if (!empty($tarjetasDesactivadas)) {
    $lista = implode(', ', array_map(fn($c) => "[$c]", $tarjetasDesactivadas));
    $texto = "Se desactivaron las siguientes tarjetas por nueva asignación: $lista.";
    $sqlObs1 = "INSERT INTO credenciales_rrhh_observaciones 
                (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion)
                VALUES (?, ?, ?, 'Desactivación de tarjetas', ?)";
    $stmtObs1 = $conexion->prepare($sqlObs1);
    $stmtObs1->bind_param("isss", $idCredencial, $fechaObs1, $usuario, $texto);
    $stmtObs1->execute();
    $stmtObs1->close();
}

$textoAsignacion = "Se asoció tarjeta código [$codigoTarjeta] al(la) funcionario(a).";
$sqlObs2 = "INSERT INTO credenciales_rrhh_observaciones 
            (idCredencial, fechaObservacion, usuarioObservacion, tipoObservacion, observacion)
            VALUES (?, ?, ?, 'Asignación de tarjeta', ?)";
$stmtObs2 = $conexion->prepare($sqlObs2);
$stmtObs2->bind_param("isss", $idCredencial, $fechaObs2, $usuario, $textoAsignacion);
$stmtObs2->execute();
$stmtObs2->close();


// ===============================================
// ✅ RESPUESTA FINAL
// ===============================================
echo json_encode([
    'status' => 'ok',
    'mensaje' => 'Tarjeta asociada correctamente'
]);
