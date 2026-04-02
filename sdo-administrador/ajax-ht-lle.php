<?php
// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


$sql = "SELECT * FROM ordenes_trabajos_general WHERE idCategoria = 1 AND anulada < 1 ORDER BY fechaOrden DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $idDepartamento);

$stmt->execute();
$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $idOrden = $row['idOrden'];
    $sqlRecepcion = "SELECT * FROM equipos_recepcion_trabajo WHERE idOrden = $idOrden";
    $resRecepcion = mysqli_query($conexion,$sqlRecepcion);
    while($rowRecepcion = $resRecepcion->fetch_assoc()){
        $equipo = $rowRecepcion['equipo'];
        $idEquipo = $rowRecepcion['idEquipo'];
        $sqlEquipos = "SELECT * FROM `equipamiento_hbqp` WHERE id = $idEquipo";
        $resEquipos = mysqli_query($conexion,$sqlEquipos);
        while($rowEquipos = $resEquipos->fetch_assoc()){
            $recinto = $rowEquipos['recinto'];
        }
    }

    $folioOrden = $row["folioOrden"];
    $tipoMantencion = $row["tipoMantencion"];

    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));

    $fechaOrden = $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
    $row['recinto'] = $recinto;
    $row['equipo'] = $equipo;
    $row['folioOrden'] = $folioOrden;
    $row['fechaOrden'] = $fechaOrden;
    $row["tipoMantencion"] = $tipoMantencion;
    $datos[] = $row;

}
$stmt->close();
$conexion->close();
echo json_encode($datos);
