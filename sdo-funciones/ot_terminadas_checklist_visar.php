<?php
include_once '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');

// Recibir datos desde POST
$idOrdenVisador = $_POST['idot'] ?? null;
$idCheckVisador = $_POST['idcheck'] ?? null;
$tipoCheckVisador = $_POST['tipocheck'] ?? null;
$nombreVisador = $_POST['usuario'] ?? null;

// Validaciones iniciales
if (!$nombreVisador) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el nombre del usuario."]);
    exit;
}

if (!$idOrdenVisador || $idOrdenVisador < 1) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el ID de la OT."]);
    exit;
}

if (!$idCheckVisador || $idCheckVisador < 1) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el ID del Checklist."]);
    exit;
}

if (!$tipoCheckVisador || empty($tipoCheckVisador)) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el Tipo del Checklist."]);
    exit;
}


$conexion->begin_transaction();

$tipoTablaMap = [
    'Pantalla Gráfica' => [
        'tabla' => 'datos_checklist',
        'id'    => 'id'
    ],
    'UCAS' => [
        'tabla' => 'datos_checklist_ucas',
        'id'    => 'id'
    ],
    'UMA' => [
        'tabla' => 'datos_uma',
        'id'    => 'idUma'
    ],
    'Lógica de Programación' => [
        'tabla' => 'datos_checklist_logica',
        'id'    => 'id'
    ],
    'Controlador ECB' => [
        'tabla' => 'datos_checklist_controlador_ecb',
        'id'    => 'id'
    ],
    'TCC UMA' => [
        'tabla' => 'datos_tcc_uma',
        'id'    => 'id'
    ],
    'VAV' => [
        'tabla' => 'datos_vav',
        'id'    => 'idVav'
    ],
    'VF' => [
        'tabla' => 'datos_vf',
        'id'    => 'id'
    ]
];

if (isset($tipoTablaMap[$tipoCheckVisador])) {
    $tabla     = $tipoTablaMap[$tipoCheckVisador]['tabla']; // datos_checklist_ucas
    $campoID   = $tipoTablaMap[$tipoCheckVisador]['id'];    // id

    $stmt = $conexion->prepare("UPDATE $tabla 
        SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() 
        WHERE $campoID = ? AND (isVisado = 0 OR isVisado IS NULL)");
    $stmt->bind_param("si", $nombreVisador, $idCheckVisador);
    $stmt->execute();

    $filasAfectadas = $stmt->affected_rows;
    $stmt->close();


    if ($filasAfectadas > 0) {
        $conexion->commit();
        echo json_encode([
            "status" => "success",
            "msg" => "CheckList Visado Correctamente.",
        ]);
    } else {
        $conexion->rollback();

        // Mostrar la sentencia SQL con valores insertados para depuración
        $sentenciaSQL = sprintf(
            "UPDATE %s SET isVisado = 1, nombreVisado = '%s', fechaVisado = NOW() WHERE $campoID = %d AND (isVisado = 0 OR isVisado IS NULL)",
            $tabla,
            $nombreVisador,
            $idTG
        );

        echo json_encode([
            "status" => "warning",
            "msg" => "No se actualizó el Checklist: ya estaba visado o no existe.",
        ]);
    }


} else {
    $conexion->rollback();
    echo json_encode([
        "status" => "warning",
        "msg" => "No se actualizó el Checklist: Error al Obtener Tipo del Checklist.",
    ]);
}
$conexion->close();
