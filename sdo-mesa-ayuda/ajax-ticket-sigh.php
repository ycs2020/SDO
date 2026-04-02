<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$sql = "SELECT * FROM ticket_sigh ORDER BY numTicketSIGH DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $idTicketSIGH = $row['idTicketSIGH'];
    $numeroTicketSIGH = $row['numTicketSIGH'];
    $rutTicketSIGH = $row['rutTicketSIGH'];
    $nomTicketSIGH = $row['nomTicketSIGH'];
    $unidadTicketSIGH = $row['unidadTicketSIGH'];
    $destinoTicketSIGH = $row['destinoTicketSIGH'];
    $fechaSolicTicketSIGH = $row['fechaSolicTicketSIGH'];
    $tipoSolicTicketSIGH = $row['tipoSolicTicketSIGH'];
    $tipoProblemaTicketSIGH = $row['tipoProblemaTicketSIGH'];
    $nomTecnicoTicketSIGH = $row['nomTecnicoTicketSIGH'];
    $fechaCierreTicketSIGH = $row['fechaCierreTicketSIGH'];
    $estadoTicketSIGH = $row['estadoTicketSIGH'];
    $detalleTicketSIGH = $row['detalleTicketSIGH'];
    $detalleTicketSIGH = nl2br($detalleTicketSIGH);

    if($fechaCierreTicketSIGH == '1970-01-01 00:00:00'){
        $fechaCierreTicketSIGH = 'Sin información';
    }

    if($tipoSolicTicketSIGH == 'EquipoMedicos'){
           $tipoSolicTicketSIGH = 'Equipos Médicos';
    }

    $tipos = $tipoSolicTicketSIGH.'<br>'.$tipoProblemaTicketSIGH;

    $row['tipos'] = $tipos;
    $row['detalleTicketSIGH'] = $detalleTicketSIGH;
    $row['idTicketSIGH'] = $idTicketSIGH;
    $row['estadoTicketSIGH'] = $estadoTicketSIGH;
    $row['fechaCierreTicketSIGH'] = $fechaCierreTicketSIGH;
    $row['nomTecnicoTicketSIGH'] = $nomTecnicoTicketSIGH;
    $row['tipoProblemaTicketSIGH'] = $tipoProblemaTicketSIGH;
    $row['tipoSolicTicketSIGH'] = $tipoSolicTicketSIGH;
    $row['fechaSolicTicketSIGH'] = $fechaSolicTicketSIGH;
    $row['destinoTicketSIGH'] = $destinoTicketSIGH;
    $row['unidadTicketSIGH'] = $unidadTicketSIGH;
    $row['nomTicketSIGH'] = $nomTicketSIGH;
    $row['rutTicketSIGH'] = $rutTicketSIGH;
    $row['numTicketSIGH'] = $numeroTicketSIGH;
    $datos[] = $row;
}
$stmt->close();
$conexion->close();
echo json_encode($datos);
