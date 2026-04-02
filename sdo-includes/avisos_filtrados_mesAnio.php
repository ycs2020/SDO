<?php
require_once '../../sdo-funciones/conexion.php'; // Ajusta la ruta
require_once '../../sdo-funciones/funciones.php'; // Ajusta la ruta

$mes = $_POST['mes'] ?? '';
$anio = $_POST['anio'] ?? '';

$condiciones = [];

if (!empty($mes)) {
    $condiciones[] = "MONTH(fechaAviso) = " . intval($mes);
}
if (!empty($anio)) {
    $condiciones[] = "YEAR(fechaAviso) = " . intval($anio);
}

$sql = "SELECT * FROM avisos_relevantes";
if ($condiciones) {
    $sql .= " WHERE " . implode(' AND ', $condiciones);
}
$sql .= " ORDER BY fechaAviso DESC";

$res = mysqli_query($conexion, $sql);

$html = '';
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $id = $row['idAviso'];
        $titulo = $row['tituloAviso'];
        $fecha = strtotime($row['fechaAviso']);
        $fechaEsp = fechaEspañol2($fecha) . ' a las ' . date('H:i', $fecha) . ' hrs.';

        $html .= '
        <div class="col-xl-2 col-lg-3 col-md-4 col-12 mb-3">
            <button class="btn w-100 h-100 d-flex flex-column align-items-center justify-start abrirModalAviso"
                style="background-color: #fff475; box-shadow: 5px 5px 10px rgba(0,0,0,0.3);"
                data-idaviso="' . $id . '" data-toggle="modal" data-target="#modalAvisoUnico">
                <i class="fas fa-thumbtack"></i><br>
                <b>' . $fechaEsp . '</b><br>
                <b>' . $titulo . '</b>
            </button>
        </div>';
    }
} else {
    $html = '<div class="col-12 text-center"><h4>No Existen Avisos Relevantes</h4></div>';
}

echo $html;
