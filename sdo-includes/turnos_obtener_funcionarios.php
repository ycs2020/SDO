<?php
include_once '../sdo-funciones/conexion.php';

$sqlUsuarios = "
    SELECT nomPersonal AS nombre 
    FROM personal 
    WHERE departamentoPersonal = 1 
    AND activoPersonal = 1

    UNION ALL

    SELECT nomJefe AS nombre
    FROM jefes_tecnicos
    WHERE idDepartamento = 1
    AND userActivado = 1
    ORDER BY nombre ASC
";

$res = mysqli_query($conexion, $sqlUsuarios);
$data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row['nombre'];
}

echo json_encode($data);
