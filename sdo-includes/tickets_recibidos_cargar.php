<?php
include_once '../sdo-funciones/conexion.php';
include_once '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

header('Content-Type: application/json; charset=utf-8');

function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha No Ingresada';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {

            $inicioFecha = date('d/m/Y', $timestamp);
            $inicioHora = date('H:i', $timestamp);
            $fechaCompleta = "$inicioFecha<br>$inicioHora";
        }
    }

    return $fechaCompleta;
}

// Validar que venga definido por POST el idDepto
if (!isset($_POST['idDepto']) || !is_numeric($_POST['idDepto'])) {
    echo json_encode([
        "data" => [],
        "error" => "Parámetro 'idDepto' no definido o inválido."
    ]);
    exit;
}

$idDepto = (int)$_POST['idDepto'];

$clausula = "";

if($idDepto == 6){
    $clausula = " AND deptoResponsable LIKE 'Equipos Médicos' ";
}

$sql = "SELECT 
            idSolicitud,
            folioSolicitud,
            nomSolicitante,
            deptoSolicitante,
            ubicacionSolicitud,
            deptoResponsable,
            tipoProblemaSolicitud,
            inicioSolicitud,
            tecnicoSolicitud,
            situacionSolicitud
        FROM solicitudes_soportes 
        WHERE (canceladaSolicitud = 0 
            AND finalizadaSolicitud = 0)
            $clausula
        ORDER BY inicioSolicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {

    // Variables originales
    $idSolicitud           = (int)$row["idSolicitud"];
    $folioSolicitud        = $row["folioSolicitud"];
    $nomSolicitante        = $row["nomSolicitante"];
    $deptoSolicitante      = $row["deptoSolicitante"];
    $ubicacionSolicitud    = $row["ubicacionSolicitud"];
    $deptoResponsable      = $row["deptoResponsable"];
    $tipoProblemaSolicitud = $row["tipoProblemaSolicitud"];
    $inicioSolicitud       = $row["inicioSolicitud"];
    $tecnicoSolicitud      = $row["tecnicoSolicitud"];
    $situacionSolicitud    = $row["situacionSolicitud"];

    $contarSolicitudComprasSC = contarSolicitudComprasSC($idSolicitud);
    $getNuevaObservacionSolicitud = getNuevaObservacionSolicitud($idSolicitud);

    // Variables en MAYÚSCULAS (sufijo 2)
    $folioSolicitud2        = mb_convert_case($folioSolicitud, MB_CASE_UPPER, 'UTF-8');
    $nomSolicitante2        = mb_convert_case($nomSolicitante, MB_CASE_UPPER, 'UTF-8');
    $deptoSolicitante2      = mb_convert_case($deptoSolicitante, MB_CASE_UPPER, 'UTF-8');
    $ubicacionSolicitud2    = mb_convert_case($ubicacionSolicitud, MB_CASE_UPPER, 'UTF-8');
    $deptoResponsable2      = mb_convert_case($deptoResponsable, MB_CASE_UPPER, 'UTF-8');
    $tipoProblemaSolicitud2 = mb_convert_case($tipoProblemaSolicitud, MB_CASE_UPPER, 'UTF-8');
    $tecnicoSolicitud2      = mb_convert_case($tecnicoSolicitud, MB_CASE_UPPER, 'UTF-8');
    $situacionSolicitud2    = mb_convert_case($situacionSolicitud, MB_CASE_UPPER, 'UTF-8');

    $tipoProblemaCompleto = "$deptoResponsable2<br>$tipoProblemaSolicitud2";


    if(empty($tecnicoSolicitud2)){
        $tecnicoAsignadoCompleto = 'SIN ASIGNAR';
    }else{
        $deptoTecnico = explode(' (', $tecnicoSolicitud2);
        if(empty($deptoTecnico[1])){
            $tecnicoAsignadoCompleto = $deptoTecnico[0];
        }else{
            $tecnicoAsignadoCompleto = $deptoTecnico[0].' ('.$deptoTecnico[1];
        }
    }

    $btnVisualizar = "DESHABILITADO";

    if($idSolicitud > 0){
        $btnVisualizar = "<button class='btn btn-block btn-primary btnVisualSolic' id='btnVisualSolic' name='btnVisualSolic' data-idticket='$idSolicitud'><i class='fas fa-eye'></i></button>";
    }

    // Fecha formateada
    $inicioSolicitudFormateada = formatearFechaCompletaAJAX($inicioSolicitud);

    $data[] = [
        // Campos originales
        "idSolicitud"            => $idSolicitud,
        "folioSolicitud"         => $folioSolicitud,
        "nomSolicitante"         => $nomSolicitante,
        "deptoSolicitante"       => $deptoSolicitante,
        "ubicacionSolicitud"     => $ubicacionSolicitud,
        "deptoResponsable"       => $deptoResponsable,
        "tipoProblemaSolicitud"  => $tipoProblemaSolicitud,
        "inicioSolicitud"        => $inicioSolicitud,
        "tecnicoSolicitud"       => $tecnicoSolicitud,
        "situacionSolicitud"     => $situacionSolicitud,

        // Campos en mayúsculas (sufijo 2)
        "folioSolicitud2"         => $folioSolicitud2,
        "nomSolicitante2"         => $nomSolicitante2,
        "deptoSolicitante2"       => $deptoSolicitante2,
        "ubicacionSolicitud2"     => $ubicacionSolicitud2,
        "deptoResponsable2"       => $deptoResponsable2,
        "tipoProblemaSolicitud2"  => $tipoProblemaSolicitud2,
        "inicioSolicitud2"        => $inicioSolicitudFormateada,
        "tecnicoSolicitud2"       => $tecnicoSolicitud2,
        "situacionSolicitud2"     => $situacionSolicitud2,
        "tipoProblemaCompleto"    => $tipoProblemaCompleto,
        "tecnicoAsignadoCompleto" => $tecnicoAsignadoCompleto,
        "contarSolicitudComprasSC"       => $contarSolicitudComprasSC,
        "getNuevaObservacionSolicitud"   => $getNuevaObservacionSolicitud,
        "btnVisualizar"              => $btnVisualizar
    ];
}



$stmt->close();
$conexion->close();

echo json_encode([
    "data" => $data,
//    "debug_sql" => $sql
]);
exit;
