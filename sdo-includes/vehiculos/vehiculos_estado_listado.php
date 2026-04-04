<?php
include_once '../../sdo-funciones/conexion.php';

$idMovil = $_POST['idMovilAjax'] ?? null;

if ($idMovil) {
    $sql = "SELECT * FROM historial_estados_moviles WHERE idMovil = ? ORDER BY fechaReporte DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idMovil);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Formatear fecha y badges aquí o en el JS
        $row['fechaFormateada'] = date('d/m/Y H:i', strtotime($row['fechaReporte']));
        $data[] = $row;
    }
    echo json_encode($data);


}
?>