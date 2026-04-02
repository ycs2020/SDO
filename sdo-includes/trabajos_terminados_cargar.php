<?php
include_once('../sdo-funciones/conexion.php');
include_once('../sdo-funciones/funciones.php');

// Recibir parámetros del DataTables
$draw = $_GET['draw'];
$start = $_GET['start'];
$length = $_GET['length'];
$searchValue = $_GET['search']['value'];

// Columnas permitidas para ordenar
$columns = ['fechaProgramacion', 'folioTG', 'turnoTG', 'solicitanteTG', 'tecnico1', 'estadoTG'];

// Armado dinámico de cláusula ORDER BY para múltiples columnas
$orderClause = [];
if (isset($_GET['order']) && is_array($_GET['order'])) {
    foreach ($_GET['order'] as $order) {
        $colIndex = intval($order['column']);
        $dir = ($order['dir'] === 'desc') ? 'DESC' : 'ASC';

        if (isset($columns[$colIndex])) {
            $colName = $columns[$colIndex];
            $orderClause[] = "$colName $dir";
        }
    }
}
$orderBySQL = !empty($orderClause) ? implode(', ', $orderClause) : 'fechaProgramacion DESC';

// Consulta base
$baseQuery = "FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado'";

// Filtro por búsqueda global
if (!empty($searchValue)) {
    $baseQuery .= " AND (folioTG LIKE '%$searchValue%' OR tecnico1 LIKE '%$searchValue%' OR solicitanteTG LIKE '%$searchValue%' OR turnoTG LIKE '%$searchValue%')";
}

// Obtener total de registros filtrados
$totalFilteredQuery = "SELECT COUNT(*) $baseQuery";
$resFiltered = mysqli_query($conexion, $totalFilteredQuery);
$totalFiltered = mysqli_fetch_row($resFiltered)[0];

// Obtener total sin filtros
$totalQuery = "SELECT COUNT(*) FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado'";
$resTotal = mysqli_query($conexion, $totalQuery);
$totalRecords = mysqli_fetch_row($resTotal)[0];

// Obtener datos paginados
$dataQuery = "SELECT * $baseQuery ORDER BY $orderBySQL LIMIT $start, $length";
$resData = mysqli_query($conexion, $dataQuery);

$data = [];

while ($row = mysqli_fetch_assoc($resData)) {
    $idTG = trim($row['idTG']);
    $folioTG = $row['folioTG'];
    $fechaProgramacion = $row['fechaProgramacion'];
    $turnoTG = trim($row['turnoTG']);
    $solicitanteTG = trim($row['solicitanteTG']);
    $tecnico = $row['tecnico1'];
    $estadoTG = trim($row['estadoTG']);

    $contarVisadoHT = contarVisadoHT($folioTG);
    $contarVisadoHTTodo = contarVisadoHTTodo($folioTG);
    $contarVisadoOT = contarVisadoOT($idTG);
    $contarVisadoOTTodo = contarVisadoOTTodo($idTG);
    $contarCheckList = contarChecklistGeneral($idTG);

    $colorTR = ($contarVisadoHTTodo == $contarVisadoHT && $contarVisadoOTTodo == $contarVisadoOT)
        ? 'background-color: indigo; color: white;' : '';

    $poseeChecklist = ($contarCheckList > 0) ? 'Si posee Checklist' : 'No posee Checklist';

    $detalleHT = ($contarVisadoHTTodo == $contarVisadoHT)
        ? 'Todas las Hojas Técnicas Están Visadas'
        : "Tiene: $contarVisadoHT de $contarVisadoHTTodo Hojas Técnicas Visadas";

    $detalleOT = ($contarVisadoOT == $contarVisadoOTTodo)
        ? 'La OT ya se encuentra visada'
        : "Tiene: $contarVisadoOT de $contarVisadoOTTodo Ordenes de Trabajo Visadas";

    $fecha = $fechaProgramacion ? date('d/m/Y', strtotime($fechaProgramacion)) : 'No registra fecha';
    $turno = $turnoTG ?: 'No registra Turno';
    $solicitante = $solicitanteTG ?: 'No registra Solicitante';
    $tecnico = $tecnico ?: 'No registra funcionario';
    $estado = $estadoTG ?: 'No registra Estado';

    $detalleFinal = "$detalleHT<br>$detalleOT<br>$poseeChecklist";

    $verBtn = '<form target="_blank" method="post" action="visualizardetallesterminadas.php">
                <input type="hidden" name="idTG" value="' . $idTG . '">
                <button class="btn btn-success btn-sm btn-block"><i class="fa fa-eye"></i></button>
              </form>';

    $data[] = [
        'DT_RowAttr' => ['style' => $colorTR],
        $fecha,
        $folioTG,
        $turno,
        $solicitante,
        $tecnico,
        $estado,
        $detalleFinal,
        $verBtn
    ];
}

echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
