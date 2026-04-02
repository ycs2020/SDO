<?php
// Archivo: tu_servidor.php

// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

// Consulta SQL Optimizada (Con EXISTS, UPPER y COALESCE)
$sql = "SELECT 
            UPPER(ao.OWNER_Name) AS OWNER_Name,
            UPPER(ao.OWNER_CODE) AS OWNER_CODE,
            UPPER(ao.CARD_NUMBER) AS CARD_NUMBER,
            UPPER(COALESCE(ac.calidadContractual, 'Sin Información')) AS calidadContractual,
            UPPER(COALESCE(dp.DEPT_FULL_NAME, 'Sin Información')) AS DEPT_FULL_NAME,
            ao.CARD_STATE,
            CASE 
                WHEN EXISTS (SELECT 1 FROM intercom_users ic WHERE ic.Name = ao.OWNER_CODE) THEN 'Si'
                ELSE 'No'
            END AS resultadoIntercom,
            ao.END_DATE
        FROM ACM_owner ao
        LEFT JOIN actas ac ON ao.OWNER_CODE = ac.rutTarjeta
        LEFT JOIN Department_table dp ON ao.DEPT_ID = dp.DEPT_ID
        ORDER BY ao.OWNER_Name ASC;";

// Preparar y ejecutar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados
while ($row = $result->fetch_assoc()) {

    // 1. Formateo de Fecha
    $fechaFormateada = "NO APLICA";
    $END_DATE = $row["END_DATE"];
    if (!empty($END_DATE) && $END_DATE !== '0000-00-00 00:00:00' && strtotime($END_DATE)) {
        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $END_DATE);
        if ($fecha) {
            $fechaFormateada = $fecha->format('d-m-Y');
        }
    }
    $row['fechaFormateada'] = $fechaFormateada;

    // 2. Lógica de Intercom (Textos ya en mayúsculas dentro de la etiqueta)
    if($row['resultadoIntercom'] === 'Si'){
        $row['resultadoIntercom'] = "<span style='color: #01ec01; font-weight: bold;'>INTERCOM ACTIVADO</span>";
    } else {
        $row['resultadoIntercom'] = "<span style='color: #ea0a0a; font-weight: bold;'>INTERCOM NO ACTIVADO</span>";
    }

    // 3. Lógica del Estado de la Tarjeta
    if (empty($row['CARD_NUMBER'])) {
        $row['estadoTarjeta'] = "<span style='color: #ea0a0a; font-weight: bold;'>CREDENCIAL BLOQUEADA EN SMARTPSS</span>";
    } else {
        switch ($row['CARD_STATE']) {
            case 0:
            case 2:
                $row['estadoTarjeta'] = "<span style='color: #01ec01; font-weight: bold;'>CREDENCIAL ACTIVADA EN SMARTPSS</span>";
                break;
            case 1:
            case 3:
                $row['estadoTarjeta'] = "<span style='color: #ea0a0a; font-weight: bold;'>CREDENCIAL BLOQUEADA EN SMARTPSS</span>";
                break;
            default:
                $row['estadoTarjeta'] = "<span style='color: #ffdf00; font-weight: bold;'>ESTADO DESCONOCIDO EN SMARTPSS</span>";
                break;
        }
    }

    // Agregar la fila directamente
    $datos[] = $row;
}

// Cerrar conexión
$stmt->close();
$conexion->close();

// Enviar respuesta
header('Content-Type: application/json; charset=utf-8'); // Buena práctica agregar el header
echo json_encode($datos);
?>