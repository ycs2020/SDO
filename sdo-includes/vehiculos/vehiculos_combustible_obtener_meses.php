<?php
include_once '../../sdo-funciones/conexion.php';
$anio = $_POST['anio'] ?? date('Y');
$nombresMeses = [1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"];

$sql = "SELECT DISTINCT MONTH(fechaRegistro) as mes 
        FROM historial_petroleo_moviles 
        WHERE YEAR(fechaRegistro) = ? 
        ORDER BY mes DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $anio);
$stmt->execute();
$res = $stmt->get_result();

echo '<option value="0">--- TODO EL AÑO ---</option>';
if($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $selected = ($row['mes'] == date('n')) ? 'selected' : '';
        echo "<option value='{$row['mes']}' $selected>{$nombresMeses[$row['mes']]}</option>";
    }
}