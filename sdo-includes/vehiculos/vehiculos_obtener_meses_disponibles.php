<?php
include_once '../../sdo-funciones/conexion.php';
$anio = $_POST['anio'] ?? date('Y');
$nombresMeses = [1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"];

$sql = "SELECT DISTINCT MONTH(fechaRegistro) as mes 
        FROM historial_kilometraje_moviles 
        WHERE YEAR(fechaRegistro) = ? 
        ORDER BY mes DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $anio);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0) {
    echo '<option value="0">AÑO COMPLETO</option>'; // Opción para mostrar año
    while($row = $res->fetch_assoc()) {
        // Marcamos como seleccionado el mes actual si existe en la lista
        $selected = ($row['mes'] == date('n')) ? 'selected' : '';
        echo "<option value='{$row['mes']}' $selected>{$nombresMeses[$row['mes']]}</option>";
    }
} else {
    // Si no hay datos, mostramos el mes actual como fallback para que no quede vacío
    echo "<option value='".date('n')."'>".$nombresMeses[date('n')]." (Sin registros)</option>";
}