<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


// Consulta SQL
$sql = "SELECT * FROM ACM_owner ORDER BY OWNER_Name, DEPT_ID ASC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $OWNER_Name = $row['OWNER_Name'];
    $rutAcceso = $row['OWNER_CODE'];
    $tarjetaAcceso = $row['CARD_NUMBER'];
    $nomDepartamento = $row['DEPT_ID'];


    if (empty($OWNER_Name)) {
        $nuevoNom = 'NO APLICA';
    } else {
        $nuevoNom = mb_convert_case($OWNER_Name, MB_CASE_UPPER, "UTF-8");
    }

    if (empty($rutAcceso)) {
        $rutAcceso = 'NO APLICA';
    } else {
        $rutAcceso = mb_convert_case($rutAcceso, MB_CASE_UPPER, "UTF-8");

        $existeIntercom = existeIntercom($rutAcceso);

        if ($existeIntercom == 1) {
            $fraseExisteIntercom = "Posee Derecho a Estacionamiento";
        } else {
            $fraseExisteIntercom = "No Posee Derecho a Estacionamiento";
        }

        $fraseExisteIntercom = mb_convert_case($fraseExisteIntercom, MB_CASE_UPPER, "UTF-8");

        $obtenerCalidadJuridica = obtenerCalidadJuridica($rutAcceso);

        $obtenerCalidadJuridica = trim($obtenerCalidadJuridica);

        $obtenerCalidadJuridica = mb_convert_case($obtenerCalidadJuridica, MB_CASE_UPPER, "UTF-8");

        if (empty($obtenerCalidadJuridica)) {
            $obtenerCalidadJuridica = 'Sin Información';
        }
    }

    if (empty($tarjetaAcceso)) {
        $tarjetaAcceso = 'SIN TARJETA';
    } else {
        $tarjetaAcceso = mb_convert_case($tarjetaAcceso, MB_CASE_UPPER, "UTF-8");
    }

    if (empty($nomDepartamento)) {
        $nomDepartamento = 'NO APLICA';
    } else {

        $nomDepartamento = getNomDepartamentoCCAA($nomDepartamento);
        $nomDepartamento = mb_convert_case($nomDepartamento, MB_CASE_UPPER, "UTF-8");
    }

    $row['OWNER_Name'] = $nuevoNom;
    $row['OWNER_CODE'] = $rutAcceso;
    $row['tarjetaAcceso'] = $tarjetaAcceso;
    $row['nomDepartamento'] = $nomDepartamento;
    $row['existeIntercom'] = $fraseExisteIntercom;
    $row['calidadJuridica'] = $obtenerCalidadJuridica;
    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>