<?php
include_once __DIR__ . '/../../sdo-funciones/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$anio = $_POST['anio'] ?? date('Y');
$mes  = $_POST['mes'] ?? 'TODOS';
$accion = $_POST['accion'] ?? 'cargarDatos';

// ---------------------------------------------------------
// ACCIÓN 1: CARGAR MESES PERMITIDOS (Para no mostrar meses futuros)
// ---------------------------------------------------------
if ($accion === 'cargarMeses') {
    $mesesGlobal = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
    $mesMaximo = 12;

    if ((int)$anio === (int)date('Y')) {
        $mesMaximo = (int)date('n');
    }

    $html = '<option value="TODOS">-- AÑO COMPLETO --</option>';
    for ($i = 0; $i < $mesMaximo; $i++) {
        $numMesStr = str_pad($i + 1, 2, "0", STR_PAD_LEFT);
        $nombreMes = $mesesGlobal[$i];

        // Mantener seleccionado el mes actual si corresponde
        $selected = ((int)date('m') == ($i + 1)) ? 'selected' : '';

        $html .= "<option value='$numMesStr' $selected>$nombreMes</option>";
    }

    echo json_encode(['html' => $html]);
    exit;
}


// ---------------------------------------------------------
// ACCIÓN 2: CARGAR DATOS PARA EL GRÁFICO Y LA TABLA
// ---------------------------------------------------------
if ($accion === 'cargarDatos') {

    if ($mes === 'TODOS') {
        $sql = "SELECT 
                    CASE 
                        WHEN d.idDepartamento IN (1, 13) THEN 'CONTROL CENTRALIZADO'
                        WHEN d.nomDepartamento LIKE '%Climatización%' THEN 'CLIMATIZACIÓN'
                        ELSE d.nomDepartamento 
                    END AS nombre_agrupado,
                    COUNT(tg.idTG) AS total 
                FROM departamentos d
                LEFT JOIN trabajos_general tg ON d.idDepartamento = tg.departamento 
                    AND YEAR(tg.fechaProgramacion) = ?
                WHERE d.idDepartamento NOT IN (9, 11, 14, 15)
                GROUP BY nombre_agrupado
                ORDER BY total DESC";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $anio);
    } else {
        $sql = "SELECT 
                    CASE 
                        WHEN d.idDepartamento IN (1, 13) THEN 'CONTROL CENTRALIZADO'
                        WHEN d.nomDepartamento LIKE '%Climatización%' THEN 'CLIMATIZACIÓN'
                        ELSE d.nomDepartamento 
                    END AS nombre_agrupado,
                    COUNT(tg.idTG) AS total 
                FROM departamentos d
                LEFT JOIN trabajos_general tg ON d.idDepartamento = tg.departamento 
                    AND YEAR(tg.fechaProgramacion) = ? 
                    AND MONTH(tg.fechaProgramacion) = ?
                WHERE d.idDepartamento NOT IN (9, 11, 14, 15)
                GROUP BY nombre_agrupado
                ORDER BY total DESC";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $anio, $mes);
    }

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    $datos_respuesta = [];
    $total_general = 0;

    while ($row = mysqli_fetch_assoc($resultado)) {
        // Solo enviamos áreas que sí hayan tenido trabajos para limpiar la tabla/gráfico
        if ((int)$row['total'] > 0) {
            $datos_respuesta[] = [
                'departamento' => mb_strtoupper($row['nombre_agrupado'], 'UTF-8'),
                'total' => (int)$row['total']
            ];
            $total_general += (int)$row['total'];
        }
    }
    mysqli_stmt_close($stmt);

    echo json_encode([
        'status' => 'success',
        'data' => $datos_respuesta,
        'total_general' => $total_general
    ]);
    exit;
}
?>