<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Parámetros de DataTables
$draw   = intval($_POST['draw']);
$start  = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = trim($_POST['search']['value']);

// ====================== 1. Base de datos optimizada ==========================
// Subconsulta agregada con GROUP_CONCAT para unir unidades
$subconsulta = "
    (
        SELECT 
            fr.idFormRegAcceso,
            fr.folioFormRegAcceso,
            fr.fechaFormRegAcceso,
            fr.categoriaFormRegAcceso,
            fr.solicitanteFormRegAcceso,
            GROUP_CONCAT(DISTINCT fa.unidadFormCCAA ORDER BY fa.unidadFormCCAA SEPARATOR '\n') AS unidades
        FROM formularios_registro fr
        JOIN formularios_funcionarios ff ON ff.idFormRegAcceso = fr.idFormRegAcceso
        JOIN formularios_acceso fa ON fa.idFormCCAA = ff.idUnidad
        GROUP BY fr.idFormRegAcceso
        HAVING SUM(ff.permisosFormFuncCCAA = 1) < COUNT(*)
    ) AS sub
";

// ====================== 2. WHERE dinámico si hay búsqueda ==========================
$where = "";
$params = [];
$types = "";

if (!empty($searchValue)) {
    $where = " WHERE sub.folioFormRegAcceso LIKE ? OR sub.categoriaFormRegAcceso LIKE ? OR sub.unidades LIKE ? OR sub.solicitanteFormRegAcceso LIKE ?";
    $param = "%$searchValue%";
    $params = [&$param, &$param, &$param, &$param];
    $types = "ssss";
}

// ====================== 3. Obtener total ==========================
$sqlTotal = "SELECT COUNT(*) FROM $subconsulta $where";
$stmtTotal = $conexion->prepare($sqlTotal);
if (!empty($params)) {
    $stmtTotal->bind_param($types, ...$params);
}
$stmtTotal->execute();
$stmtTotal->bind_result($recordsTotal);
$stmtTotal->fetch();
$stmtTotal->close();

// ====================== 4. Consulta con LIMIT ==========================
$sqlData = "
    SELECT 
        sub.idFormRegAcceso,
        sub.folioFormRegAcceso,
        sub.fechaFormRegAcceso,
        sub.categoriaFormRegAcceso,
        sub.solicitanteFormRegAcceso,
        sub.unidades
    FROM $subconsulta
    $where
    ORDER BY sub.fechaFormRegAcceso DESC
    LIMIT ?, ?
";

$params[] = &$start;
$params[] = &$length;
$types .= "ii";

$stmtData = $conexion->prepare($sqlData);
$stmtData->bind_param($types, ...$params);
$stmtData->execute();
$res = $stmtData->get_result();

$datos = [];
while ($row = $res->fetch_assoc()) {
    $id = $row["idFormRegAcceso"];
    $contarExisteDocumento = contarExisteDocumento($id);

    $fechaTime = strtotime($row["fechaFormRegAcceso"]);

    $botonVisualizar = ($id > 0) ? '
        <form action="formulariosCCAA_administracion_2.php" method="post" target="_blank" style="margin:0;">
            <input type="hidden" name="idFormRegAcceso" value="' . $id . '">
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar
            </button>
        </form>' : '<span style="color: gray; font-weight: bold;">Deshabilitado</span>';

    $datos[] = [
        "idFormRegAcceso"         => $id,
        "folioFormRegAcceso"      => mb_strtoupper($row["folioFormRegAcceso"]),
        "categoriaFormRegAcceso"  => mb_strtoupper($row["categoriaFormRegAcceso"]),
        "solicitanteFormRegAcceso"=> mb_strtoupper($row["solicitanteFormRegAcceso"]),
        "fechaFormRegAcceso2"     => mb_strtoupper(fechaEspañol2($fechaTime) . "<br>" . date('H:i', $fechaTime) . " hrs."),
        "unidades"                => nl2br(mb_strtoupper($row["unidades"] ?? '')),
        "botonVisualizar"         => $botonVisualizar,
        "existeDocumento"         => $contarExisteDocumento
    ];
}

$stmtData->close();
$conexion->close();


// ====================== 5. Salida JSON ==========================
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsTotal,
    "data" => $datos
], JSON_UNESCAPED_UNICODE);
