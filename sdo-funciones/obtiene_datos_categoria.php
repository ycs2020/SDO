<?php
include 'conexion.php';

// Validar que el ID sea un entero antes de procesar
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$datos = [];

if ($id > 0) {
    // Uso de Prepared Statements para evitar Inyección SQL
    $consulta = "SELECT nomCategoria, imagenCategoria, idDepartamento FROM categorias_equipos WHERE idCategoria = ?";
    $stmt = mysqli_prepare($conexion, $consulta);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultado)) {
            $datos['nomCategoria']    = $row['nomCategoria'];
            $datos['imagenCategoria'] = $row['imagenCategoria'];
            $datos['idDepartamento']  = $row['idDepartamento'];
        }
        mysqli_stmt_close($stmt);
    }
}

header('Content-Type: application/json');
echo json_encode($datos);