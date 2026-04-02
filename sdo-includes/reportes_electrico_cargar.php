<?php
// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'FECHA INVÁLIDA';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $fechaCompleta = date('d/m/Y', $timestamp);
        }
    }

    return $fechaCompleta;
}

// Consulta SQL
$sql = "SELECT * FROM reportes_electricos_sdo ORDER BY fecha DESC, folio DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {

    $idReporte = $row["id"];
    $folioReporte = mb_strtoupper($row["folio"], 'UTF-8');
    $nomEncargadoTurno = mb_strtoupper($row["turno"], 'UTF-8');
    $tipoReporte = mb_strtoupper($row["tipoTurno"], 'UTF-8');
    $fechaReporte = $row["fecha"];
    $fechaReporte2 = formatearFechaCompletaAJAX($fechaReporte);

    // Botón
    if ($idReporte > 0) {
        $buttonDescarga = "<button class='btn btn-success btn-sm btn-block btn-visualelect' data-id='$idReporte'>
                            <i class='fas fa-eye'></i>
                          </button>";
    } else {
        $buttonDescarga = "<span class='btn btn-danger btn-sm'>ID REPORTE NO VÁLIDO</span>";
    }

    // Sobrescribir valores en mayúsculas
    $row['idReporte'] = $idReporte;
    $row['folioReporte'] = $folioReporte;
    $row['nomEncargadoTurno'] = $nomEncargadoTurno;
    $row['tipoReporte'] = $tipoReporte;
    $row['fechaReporte'] = mb_strtoupper($fechaReporte2, 'UTF-8');
    $row['buttonDescarga'] = $buttonDescarga;

    $datos[] = $row;
}

// Cerrar la conexión
$stmt->close();
$conexion->close();

// Enviar JSON
echo json_encode($datos, JSON_UNESCAPED_UNICODE);

?>
