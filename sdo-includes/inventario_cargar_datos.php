<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

try {
    // CONSULTA CON MÍNIMO Y MÁXIMO POR EQUIPO
    $sql = "
        SELECT 
            equipoInventario,
            MIN(valorUnitario) AS valor_minimo,
            MAX(valorUnitario) AS valor_maximo,
            AVG(valorUnitario) AS valor_promedio,
            SUM(cantidadInventario) AS total_cantidad,
            GROUP_CONCAT(DISTINCT unidad SEPARATOR ', ') AS unidades
        FROM 
            inventario_equipos
        GROUP BY 
            equipoInventario
        ORDER BY 
            equipoInventario ASC
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $datos = [];

    while ($row = $result->fetch_assoc()) {
        $equipo = !empty($row['equipoInventario']) ? mb_convert_case($row['equipoInventario'], MB_CASE_UPPER, 'UTF-8') : 'NO APLICA';

        $cantidadTotal = is_numeric($row['total_cantidad']) ? $row['total_cantidad'] : 0;

        $unidades = !empty($row['unidades']) ? mb_convert_case($row['unidades'], MB_CASE_UPPER, 'UTF-8') : 'NO APLICA';

        $valorMin = is_numeric($row['valor_minimo']) ? '$' . number_format($row['valor_minimo'], 0, ',', '.') : '$0';
        $valorMax = is_numeric($row['valor_maximo']) ? '$' . number_format($row['valor_maximo'], 0, ',', '.') : '$0';
        $valorProm = is_numeric($row['valor_promedio']) ? '$' . number_format($row['valor_promedio'], 0, ',', '.') : '$0';


        $valorTexto = "<span style='color: red;'>Minimo: $valorMin</span><br><span style='color: green;'>Maximo: $valorMax</span>";

        $btnVerEquipos = "<button id='btnVerEquipos' name='btnVerEquipos' class='btn btn-block btn-primary' data-nomequipo=\"" . htmlspecialchars($equipo, ENT_QUOTES, 'UTF-8') . "\"><i class='fas fa-eye'></i></button>";

        $datos[] = [
            'equipoInventario' => $equipo,
            'valor_minimo' => $valorMin,
            'valor_maximo' => $valorMax,
            'valor_texto' => $valorTexto,
            'valor_promedio' => $valorProm,
            'total_cantidad' => $cantidadTotal,
            'unidades' => $unidades,
            'btnVerEquipos' => $btnVerEquipos,
        ];
    }

    $stmt->close();
    $conexion->close();

    ob_clean();
    echo json_encode(['data' => $datos], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
