<?php
include_once '../../sdo-funciones/conexion.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $idMovil = mysqli_real_escape_string($conexion, $_POST['id']);

    // 1. Verificación de registros existentes en otras tablas
    $tablasCheck = [
        'historial_estados_moviles'    => 'Historial de Estados',
        'historial_kilometraje_moviles' => 'Historial de Kilometraje',
        'historial_petroleo_moviles'    => 'Historial de Petróleo/Combustible'
    ];

    $registrosEncontrados = [];

    foreach ($tablasCheck as $tabla => $nombreVisible) {
        $queryCheck = "SELECT COUNT(*) as total FROM $tabla WHERE idMovil = '$idMovil'";
        $resCheck = mysqli_query($conexion, $queryCheck);
        $dataCheck = mysqli_fetch_assoc($resCheck);

        if ($dataCheck['total'] > 0) {
            $registrosEncontrados[] = $nombreVisible;
        }
    }

    // 2. Si existen registros, enviamos error y no eliminamos nada
    if (!empty($registrosEncontrados)) {
        echo json_encode([
            "status" => "error",
            "message" => "No se puede eliminar: <br> <b>Existen registros en " . implode(", ", $registrosEncontrados) . ".</b>"
        ]);
        exit;
    }

    // 3. Si las tablas están vacías, procedemos a eliminar de la tabla principal
    $sqlPrincipal = "DELETE FROM moviles_movilizacion WHERE idMovil = '$idMovil'";

    if (mysqli_query($conexion, $sqlPrincipal)) {
        echo json_encode([
            "status" => "success",
            "message" => "El vehículo ha sido eliminado correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al intentar eliminar el registro principal: " . mysqli_error($conexion)
        ]);
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => "ID de vehículo no recibido."
    ]);
}

mysqli_close($conexion);