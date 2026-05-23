<?php
include '../../sdo-funciones/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['status' => 'error', 'data' => []];

if (isset($_POST['idDepartamento']) && !empty($_POST['idDepartamento'])) {
    $idDepartamento = (int)$_POST['idDepartamento'];

    // 1. Definimos la Query usando "?" como marcadores de posición seguros
    $sqlBuscar = "
        SELECT jt.idJefe AS id, jt.nomJefe AS nombre 
        FROM jefes_tecnicos jt 
        WHERE jt.nomJefe NOT LIKE '%Jaime Amador Estay Jorquera%' 
          AND jt.idDepartamento = ?
          AND jt.userActivado = 1
        
        UNION 
        
        SELECT p.idPersonal AS id, p.nomPersonal AS nombre 
        FROM personal p 
        WHERE p.departamentoPersonal = ?
            AND p.activoPersonal = 1
        
        ORDER BY nombre ASC";

    // 2. Preparamos la consulta en el servidor de BD
    if ($stmt = mysqli_prepare($conexion, $sqlBuscar)) {

        // 3. Vinculamos los parámetros de forma segura ("ii" indica que ambos son Integers)
        mysqli_stmt_bind_param($stmt, "ii", $idDepartamento, $idDepartamento);

        // 4. Ejecutamos
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = [
                    'id' => $row['id'],
                    // Sanitizamos la salida para evitar XSS en el cliente JS
                    'nombre' => htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8')
                ];
            }

            $response['status'] = 'success';
            $response['data'] = $usuarios;
        }

        mysqli_stmt_close($stmt);
    }
}

echo json_encode($response);
exit;