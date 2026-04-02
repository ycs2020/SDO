<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
date_default_timezone_set('America/Santiago');

// Sesión y datos
$usuarioConectado = $_SESSION['userUsuario'] ?? 'usuario-desconocido';
$nombreUsuario = $_SESSION['nomUsuario'] ?? 'Nombre no disponible';
$ipCliente = $_SERVER['REMOTE_ADDR'];

$nombreSolicitante = $_POST['nombre'] ?? null;
if (!$nombreSolicitante) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta nombre']);
    exit;
}

// Conexión externa
require_once("../sdo-funciones/conexion.php");

$fechaHoy = date('Ymd');
$fechaHoy2 = date('Y-m-d H:i:s');

// Crear carpeta respaldo si no existe
$directorioRespaldo = __DIR__ . "/respaldo";
if (!is_dir($directorioRespaldo)) {
    mkdir($directorioRespaldo, 0775, true);
}

// Respaldar tablas
$tablas = [
    'trabajos_general', 'ordenes_trabajos_general',
    'datos_checklist', 'datos_checklist_controlador_ecb', 'datos_checklist_logica',
    'datos_checklist_ucas', 'datos_tcc_uma', 'datos_uma', 'datos_vav', 'datos_vf',
    'tareas_carta_gantt'
];

$archivoDump = "$directorioRespaldo/respaldo_visado_$fechaHoy.sql";
$cmdDump = "mysqldump -u $username -p'$password' $database " . implode(" ", $tablas) . " > $archivoDump";
exec($cmdDump);

// Contadores
$totalHT = 0;
$totalOT = 0;
$totalChecklist = 0;
$totalTareas = 0;

// Buscar trabajos no visados
$sql = "SELECT idTG, folioTG, idTareaProgramada 
        FROM trabajos_general 
        WHERE isVisado = 0 
          AND estadoTG = 'Trabajo Terminado' 
          AND solicitanteTG LIKE ?";
$stmt = $conexion->prepare($sql);
$likeNombre = "%$nombreSolicitante%";
$stmt->bind_param("s", $likeNombre);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $idTG = $row['idTG'];
    $folioTG = $row['folioTG'];
    $idTareaP = (int)$row['idTareaProgramada'];
    $totalHT++;

    foreach ([
                 'trabajos_general',
                 'datos_checklist',
                 'datos_checklist_controlador_ecb',
                 'datos_checklist_logica',
                 'datos_checklist_ucas',
                 'datos_tcc_uma',
                 'datos_uma',
                 'datos_vav',
                 'datos_vf'
             ] as $tabla) {
        $stmtU = $conexion->prepare("UPDATE $tabla 
            SET isVisado = 1, nombreVisado = ?, fechaVisado = ? 
            WHERE idTG = ? AND (isVisado IS NULL OR isVisado = 0)");
        $stmtU->bind_param("ssi", $nombreSolicitante, $fechaHoy2, $idTG);
        $stmtU->execute();
        $totalChecklist += $stmtU->affected_rows;
        $stmtU->close();
    }

    $stmtOT = $conexion->prepare("UPDATE ordenes_trabajos_general 
        SET isVisado = 1, nombreVisado = ?, fechaVisado = ? 
        WHERE noOrdenTrabajo = ? AND (isVisado IS NULL OR isVisado = 0)");
    $stmtOT->bind_param("sss", $nombreSolicitante, $fechaHoy2, $folioTG);
    $stmtOT->execute();
    $totalOT += $stmtOT->affected_rows;
    $stmtOT->close();

    // Tarea asociada
    if ($idTareaP > 0) {
        $consultaTarea = $conexion->prepare("SELECT inicioRealTareaP, inicioPlanTareaP 
                                             FROM tareas_carta_gantt 
                                             WHERE idTareaP = ?");
        $consultaTarea->bind_param("i", $idTareaP);
        $consultaTarea->execute();
        $consultaTarea->bind_result($inicioReal, $inicioPlan);
        $inicioRealTarea = null;
        if ($consultaTarea->fetch()) {
            $inicioRealTarea = empty($inicioReal) ? $inicioPlan : $inicioReal;
        }
        $consultaTarea->close();

        if (!empty($inicioRealTarea)) {
            $inicioDate = new DateTime($inicioRealTarea);
            $finDate = (clone $inicioDate)->modify('+1 day');
            $inicioStr = $inicioDate->format('Y-m-d');
            $finStr = $finDate->format('Y-m-d');

            $stmtT = $conexion->prepare("UPDATE tareas_carta_gantt 
                SET inicioRealTareaP = ?, finRealTareaP = ?, 
                    porcentajeTareaP = 100, finalizadaTareaP = 1 
                WHERE idTareaP = ?");
            $stmtT->bind_param("ssi", $inicioStr, $finStr, $idTareaP);
            $stmtT->execute();
            $totalTareas += $stmtT->affected_rows;
            $stmtT->close();
        }
    }
}

$conexion->close();

// Respuesta JSON
echo json_encode([
    'ht' => $totalHT,
    'ot' => $totalOT,
    'checklist' => $totalChecklist,
    'tareas' => $totalTareas,
    'dump' => basename($archivoDump)
]);
