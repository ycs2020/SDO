<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL
$sql = 'SELECT * FROM solicitudes_soportes WHERE canceladaSolicitud = 0 AND finalizadaSolicitud = 0 ORDER BY inicioSolicitud DESC';

// Preparar la consulta
$stmt = $conexion->prepare($sql);
//$stmt->bind_param("s", $idDepartamento);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $folioSolicitud = $row["folioSolicitud"];
    $nomSolicitante = $row["nomSolicitante"];
    $ubicacionSolicitud = $row["ubicacionSolicitud"];
    $deptoResponsable = $row["deptoResponsable"];
    $tipoProblemaSolicitud = $row["tipoProblemaSolicitud"];
    $tecnicoSolicitud = $row["tecnicoSolicitud"];
    $situacionSolicitud = $row["situacionSolicitud"];
    $idSolicitud = $row["idSolicitud"];

    $inicioSolicitud = $row["inicioSolicitud"];

    $folioSolicitud = mb_convert_case($folioSolicitud, MB_CASE_UPPER, 'UTF-8');
    $nomSolicitante = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');
    $ubicacionSolicitud = mb_convert_case($ubicacionSolicitud, MB_CASE_UPPER, 'UTF-8');
    $deptoResponsable = mb_convert_case($deptoResponsable, MB_CASE_UPPER, 'UTF-8');
    $tipoProblemaSolicitud = mb_convert_case($tipoProblemaSolicitud, MB_CASE_UPPER, 'UTF-8');
    $tecnicoSolicitud = mb_convert_case($tecnicoSolicitud, MB_CASE_UPPER, 'UTF-8');
    $situacionSolicitud = mb_convert_case($situacionSolicitud, MB_CASE_UPPER, 'UTF-8');

    if ($inicioSolicitud && strtotime($inicioSolicitud) !== false) {

        $fechaTime = strtotime($inicioSolicitud);

        $fechaPalabra = fechaEspañol2($fechaTime);
        $horaPalabra = date('H:i', strtotime($inicioSolicitud));

        $inicioSolicitud2 = $fechaPalabra . '<br>' . $horaPalabra . ' hrs.';

    } else {
        $inicioSolicitud2 = "No Aplica";
    }

    if($tecnicoSolicitud == 'ALEJANDRO CANTILLANO'){
        $tecnicoSolicitud = 'JORGE CANTILLANO SANCHEZ';
    }

    $problemaCompleto = $deptoResponsable.':<br>'.$tipoProblemaSolicitud;

    $row['folioSolicitud'] = $folioSolicitud;
    $row['nomSolicitante'] = $nomSolicitante;
    $row['ubicacionSolicitud'] = $ubicacionSolicitud;
    $row['problemaCompleto'] = $problemaCompleto;
    $row['inicioSolicitud'] = $inicioSolicitud2;
    $row['tecnicoSolicitud'] = $tecnicoSolicitud;
    $row['situacionSolicitud'] = $situacionSolicitud;
    $row['idSolicitud'] = $idSolicitud;


    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>