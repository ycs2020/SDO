<?php
// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if(isset($_POST['idPresup1'])){
    $id = $_POST['idPresup1'];
    $sql = "SELECT * FROM equipos_presupuesto_equipos WHERE idPresup = $id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

// Obtener los resultados
    $result = $stmt->get_result();
    $datos = array();

    while ($row = $result->fetch_assoc()) {
        $idEquipo = $row['idEquipo'];
        $idInventarioEquipo = $row['idInventarioEquipo'];
        $nomEquipo = $row['nomEquipo'];
        $unidadEquipo = $row['unidadEquipo'];
        $marcaEquipo = $row['marcaEquipo'];
        $modeloEquipo = $row['modeloEquipo'];
        $cantidadEquipo = $row['cantidadEquipo'];
        $valorUnitarioEquipo = $row['valorUnitarioEquipo'];
        $valorTotalEquipo = $row['valorTotalEquipo'];

        $row['idEquipo'] = $idEquipo;
        $row['idInventarioEquipo'] = $idInventarioEquipo;
        $row['nomEquipo'] = $nomEquipo;
        $row['unidadEquipo'] = $unidadEquipo;
        $row['marcaEquipo'] = $marcaEquipo;
        $row['modeloEquipo'] = $modeloEquipo;
        $row['cantidadEquipo'] = $cantidadEquipo;
        $row['valorUnitarioEquipo'] = $valorUnitarioEquipo;
        $row['valorTotalEquipo'] = $valorTotalEquipo;

        $datos[] = $row;

    }

    $stmt->close();
    $conexion->close();
    // Devolver los datos como JSON
    echo json_encode($datos);
} else {
    // Devolver un mensaje de error si no se proporcionó el ID del presupuesto
    echo json_encode(array("error" => "No se proporcionó el ID del presupuesto"));
}
?>
