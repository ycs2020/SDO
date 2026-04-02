<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

// 🔍 Validación de entrada
if (!isset($_POST['idDepartamento'])) {
    echo json_encode(['error' => 'ID de departamento no proporcionado']);
    exit;
}

$idDepartamento = intval($_POST['idDepartamento']);
if ($idDepartamento <= 0) {
    echo json_encode(['error' => 'ID de departamento no válido']);
    exit;
}

// 📄 Consulta principal de trabajos terminados
$sql = "SELECT * FROM trabajos_general 
        WHERE departamento = ? AND estadoTG = 'Trabajo Terminado' 
        ORDER BY fechaProgramacion DESC";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

$stmt->bind_param("i", $idDepartamento);
$stmt->execute();
$result = $stmt->get_result();

$datos = [];

while ($row = $result->fetch_assoc()) {
    $idTG = $row['idTG'];
    $folioTG = $row['folioTG'];

    // 🗓️ Formateo de fechas
    $fechaTG = date('d/m/Y \a \l\a\s H:i', strtotime($row['fechaTG'])) . ' hrs.';
    $fechaProgra = date('d/m/Y', strtotime($row['fechaProgramacion']));

    // 🧮 Cálculos de visado
    $contarVisadoHT = contarConsultaParaVisado(
        $conexion,
        "SELECT COUNT(*) FROM ordenes_trabajos_general WHERE isVisado = 1 AND noOrdenTrabajo = ?",
        "s",
        $folioTG
    );

    $contarVisadoHTTodo = contarConsultaParaVisado(
        $conexion,
        "SELECT COUNT(*) FROM ordenes_trabajos_general WHERE noOrdenTrabajo = ?",
        "s",
        $folioTG
    );

    $contarVisadoOT = contarConsultaParaVisado(
        $conexion,
        "SELECT isVisado FROM trabajos_general WHERE idTG = ?",
        "i",
        $idTG
    );

    $contarVisadoOTTodo = contarConsultaParaVisado(
        $conexion,
        "SELECT COUNT(*) FROM trabajos_general WHERE idTG = ?",
        "i",
        $idTG
    );

    // 🧾 Armado del resultado por fila
    $datos[] = [
        'folioTG' => $folioTG,
        'fechaTG' => $fechaTG,
        'fechaProgramacion2' => $fechaProgra,
        'turnoTG' => $row['turnoTG'],
        'solicitanteTG' => $row['solicitanteTG'],
        'tecnico1' => $row['tecnico1'],
        'descripcion' => $row['descripcion'],
        'idTG' => $idTG,
        'departamento' => $row['departamento'],
        'contarVisadoHT' => $contarVisadoHT,
        'contarVisadoHTTodo' => $contarVisadoHTTodo,
        'contarVisadoOT' => $contarVisadoOT,
        'contarVisadoOTTodo' => $contarVisadoOTTodo
    ];
}

$stmt->close();
$conexion->close();

// 📤 Respuesta JSON para DataTable
echo json_encode($datos);
