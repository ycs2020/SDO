<?php
include_once '../../sdo-funciones/conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

$usuario_actual = $_SESSION['nomUsuario'];


function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = date('d/m/Y', $timestamp);
            $inicioHora  = date('H:i', $timestamp);
            $fechaCompleta = $inicioFecha . '<br>' . $inicioHora;
        }
    }

    return $fechaCompleta;
}

// 1. Recibir parámetros de DataTables
$draw   = isset($_POST['draw'])   ? (int)$_POST['draw'] : 0;
$start  = isset($_POST['start'])  ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// 2. Filtro de Seguridad: Si NO es el admin, solo ve lo suyo
$where = "WHERE 1=1";
if ($usuario_actual !== "Yorgo Casimis Subiabre") {
    // Usamos el nombre real para filtrar
    $where .= " AND usuario_solicita = '" . $conexion->real_escape_string($usuario_actual) . "'";
}

// 3. Si hay búsqueda en el input de DataTables
if (!empty($search)) {
    $s = $conexion->real_escape_string($search);
    $where .= " AND (asunto LIKE '%$s%' OR tipo_solicitud LIKE '%$s%' OR estado LIKE '%$s%')";
}

// 4. Conteo Total (Sin filtros de búsqueda, solo seguridad)
$totalRes = $conexion->query("SELECT COUNT(*) as total FROM sdo_requerimientos " . ($usuario_actual !== "Yorgo Casimis Subiabre" ? "WHERE usuario_solicita = '$usuario_actual'" : ""));
$totalData = $totalRes->fetch_assoc()['total'];

// 5. Conteo Filtrado (Con la búsqueda aplicada)
$totalFiltradoRes = $conexion->query("SELECT COUNT(*) as total FROM sdo_requerimientos $where");
$totalFiltrado = $totalFiltradoRes->fetch_assoc()['total'];

// 6. Obtener los datos reales
$sql = "SELECT id_requerimiento, fecha_solicitud, tipo_solicitud, usuario_solicita, asunto, estado 
        FROM sdo_requerimientos 
        $where 
        ORDER BY id_requerimiento DESC 
        LIMIT $start, $length";

$res = $conexion->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $estado = $row['estado'];
    $badge = "";

    switch ($estado) {
        case 'Pendiente':
            $badge = '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>';
            break;
        case 'En Desarrollo':
            $badge = '<span class="badge badge-primary"><i class="fas fa-spinner fa-spin"></i> En Desarrollo</span>';
            break;
        case 'Finalizado':
            $badge = '<span class="badge badge-success"><i class="fas fa-check"></i> Finalizado</span>';
            break;
        default:
            $badge = '<span class="badge badge-secondary">'.$estado.'</span>';
            break;
    }

    $row['fecha_solicitud_format'] = formatearFechaCompletaAJAX($row['fecha_solicitud']);
    $row['estado_html'] = $badge; // Creamos un nuevo índice con el HTML

    // Creamos el botón desde PHP
    $id = $row['id_requerimiento'];
    $btn_html = "<button class='btn btn-sm btn-primary btn-ver-solicitud' data-id='$id'><i class='fas fa-search-plus'></i> Abrir</button>";
    $row['boton_accion'] = $btn_html;

    $data[] = $row;
}

// 7. Salida JSON
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => (int)$totalData,
    "recordsFiltered" => (int)$totalFiltrado,
    "data"            => $data
]);