<?php
include_once '../sdo-funciones/conexion.php';

$columns = ['nomFuncionario', 'rutFuncionario'];
$limit = $_POST['length'];
$start = $_POST['start'];
$search = $_POST['search']['value'];

$where = "";
if (!empty($search)) {
    $where = "WHERE nomFuncionario LIKE '%$search%' OR rutFuncionario LIKE '%$search%'";
}

// Obtener total sin filtros
$sqlTotal = "SELECT COUNT(DISTINCT rutFuncionario) AS total FROM memos_funcionarios";
$resTotal = mysqli_query($conexion, $sqlTotal);
$totalData = mysqli_fetch_assoc($resTotal)['total'];

// Obtener total con filtro
$sqlFiltered = "SELECT COUNT(DISTINCT rutFuncionario) AS total FROM memos_funcionarios $where";
$resFiltered = mysqli_query($conexion, $sqlFiltered);
$totalFiltered = mysqli_fetch_assoc($resFiltered)['total'];

$sql = "
    SELECT MIN(id) as id, nomFuncionario, rutFuncionario
    FROM memos_funcionarios
    $where
    GROUP BY rutFuncionario
    ORDER BY nomFuncionario ASC
    LIMIT $start, $limit
";
$res = mysqli_query($conexion, $sql);

$data = [];

while ($row = $res->fetch_assoc()) {
    $rut = $row['rutFuncionario'];

    // Buscar ID Acta
    $acta = "Sin Acta";
    $sqlActa = "SELECT idActa FROM actas WHERE rutTarjeta = '$rut' LIMIT 1";
    $resActa = mysqli_query($conexion, $sqlActa);
    if ($resActa && mysqli_num_rows($resActa) > 0) {
        $idActa = mysqli_fetch_assoc($resActa)['idActa'];
        $acta = '
            <form action="actas-funcionario.php" method="POST" class="mb-0">
                <input type="hidden" name="idActa" value="'.$idActa.'">
                <input type="hidden" name="rutActa2" value="'.$rut.'">
                <input type="hidden" name="origen" value="memo_control_acceso.php">
                <button type="submit" class="btn btn-block btn-sm btn-primary" title="Ver Acta"><i class="fas fa-eye"></i></button>
            </form>';
    }

    // Memos
    $memosHtml = '';
    $sqlMemos = "SELECT idMemo FROM memos_funcionarios WHERE rutFuncionario = '$rut'";
    $resMemos = mysqli_query($conexion, $sqlMemos);
    while ($memo = mysqli_fetch_assoc($resMemos)) {
        $memosHtml .= $memo['idMemo'] . '<br>';
    }

    $data[] = [
        'nombre' => $row['nomFuncionario'],
        'rut'    => $rut,
        'acta'   => $acta,
        'memos'  => $memosHtml
    ];
}

$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($response);
?>
