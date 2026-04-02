<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

// Consulta SQL
//$sql = "SELECT * FROM ACM_owner WHERE END_DATE <= '2025-01-16 23:59:59' ORDER BY END_DATE ASC";
$sql = "SELECT * FROM ACM_owner ORDER BY END_DATE ASC";


date_default_timezone_set('America/Santiago');

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$datos = array();

while ($row = $result->fetch_assoc()) {
    $idCA = $row["ID"];
    $rutCA = $row["OWNER_CODE"];
    $nombreCA = $row["OWNER_Name"];
    $idDepartamento = $row["DEPT_ID"];
    $codigoCA = $row["CARD_NUMBER"];
    $estadoCA = $row['CARD_STATE'];
    $bloqueoAutomatico = $row['END_DATE'];

    $bloqueoTime = strtotime($bloqueoAutomatico);

    $bloqueoFecha = date('d-m-Y H:i:s',$bloqueoTime);


    $calidadContractual = obtenerCalidadJuridica($rutCA);
    $calidadContractual = mb_convert_case($calidadContractual, MB_CASE_UPPER, "UTF-8");


    $nomDepartamento = getNomDepartamentoCCAA($idDepartamento);
    $departamentoAcceso = mb_convert_case($nomDepartamento, MB_CASE_UPPER, "UTF-8");

    $estadoAcceso = obtenerStatusCredencialCCAA($rutCA);
    $estadoAcceso = mb_convert_case($estadoAcceso, MB_CASE_UPPER, "UTF-8");

    $existeIntercom = existeIntercom($rutCA);

    if ($existeIntercom == 1) {
        $fraseExisteIntercom = "Estacionamiento Activado";
    } else {
        $fraseExisteIntercom = "Estacionamiento No Activo";
    }

    $fraseExisteIntercom = mb_convert_case($fraseExisteIntercom, MB_CASE_UPPER, "UTF-8");

    $row['ID'] = $idCA;
    $row['OWNER_CODE'] = $rutCA;
    $row['OWNER_Name'] = $nombreCA;
    $row['DEPT_ID'] = $departamentoAcceso;
    $row['CARD_NUMBER'] = $codigoCA;
    $row['CARD_STATE'] = $estadoCA;
    $row['calidad_contractual'] = $calidadContractual;
    $row['estadoAcceso'] = $estadoAcceso;
    $row['intercom'] = $fraseExisteIntercom;
    $row['bloqueoAutomatico'] = $bloqueoFecha;



    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
    //$datos1[] = $rowArchivo;
}

$stmt->close();
$conexion->close();

echo json_encode($datos);

?>