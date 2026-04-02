<?php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$sql = "SELECT * FROM informe_sdo ORDER BY fecha DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $titulo = $row['titulo'];
    $fecha = $row['fecha'];
    $id = $row['id'];
    $folio = $row['folio'];

    $sqlArchivos = "SELECT COUNT(*) as total FROM `archivos_informe_sdo` WHERE idInfo = $id AND folio = '$folio'";
    $resArchivos = mysqli_query($conexion,$sqlArchivos);
    while($rowArchivos = $resArchivos->fetch_assoc()){
        $total = $rowArchivos['total'];
        $row['total'] = $total;
    }

    $fechaCreacionMemo = strtotime($fecha);
    $fecha = date("d-m-Y",$fechaCreacionMemo);
    $hora = date("H:i",$fechaCreacionMemo);

    $fechaCreacionMemo2 = $fecha.' a las '.$hora.' hrs.';

    $row['titulo'] = $titulo;
    $row['fecha'] = $fechaCreacionMemo2;
    $row['id'] = $id;
    $row['total'] = $total;
    $row['folio'] = $folio;

    $datos[] = $row;
}
// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);
?>
