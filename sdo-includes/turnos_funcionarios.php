<?php
include '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$query = "SELECT nombre FROM (
    SELECT nomPersonal AS nombre 
    FROM personal 
    WHERE departamentoPersonal = 1 AND activoPersonal = 1

    UNION ALL

    SELECT nomJefe AS nombre 
    FROM jefes_tecnicos 
    WHERE idDepartamento = 1 AND userActivado = 1
) AS nombres_combinados
ORDER BY nombre ASC
";

$result = $conexion->query($query);

$funcionarios = [];
while ($row = $result->fetch_assoc()) {
    $funcionarios[] = ['nombre' => $row['nombre']];
}

echo json_encode($funcionarios);
$conexion->close();
