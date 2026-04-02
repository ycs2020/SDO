<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// Consulta SQL
$sql = "SELECT * FROM pabellones_correo ORDER BY fechaRegistroPab DESC";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
while ($row = $result->fetch_assoc()) {
    $fechaRegistroPab = $row["fechaRegistroPab"];
    $nomRegistroPab = $row["nomRegistroPab"];


    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'tempPabellon' . $i;
        ${$nombreVariable} = $row[$nombreVariable];

        $nombreVariable2 = 'humedadPabellon' . $i;
        ${$nombreVariable2} = $row[$nombreVariable2];

        if (${$nombreVariable} > 1) {
            ${$nombreVariable} = ${$nombreVariable} . ' °C';
        } else {
            ${$nombreVariable} = 'No Aplica';
        }

        if (${$nombreVariable2} > 1) {
            ${$nombreVariable2} = ${$nombreVariable2} . ' %RH';
        } else {
            ${$nombreVariable2} = 'No Aplica';
        }

        $nombreVariable3 = 'valorTotal' . $i;
        ${$nombreVariable3} = 'T°: '.${$nombreVariable} . '<br>Hr: '.${$nombreVariable2};

    }


    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaRegistroPab)));
    $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaRegistroPab)));

    $fechaOrden = $inicioFecha . '<br>' . $inicioHora . ' hrs.';

    $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
    $nomRegistroPab = mb_convert_case($nomRegistroPab, MB_CASE_UPPER, 'UTF-8');

    $row['fechaRegistroPab'] = $fechaOrden;
    $row['nomRegistroPab'] = $nomRegistroPab;

    for ($i = 2; $i <= 10; $i++) {
        $nombreVariable = 'tempPabellon' . $i;
        $nombreVariable2 = 'humedadPabellon' . $i;
        $nombreVariable3 = 'valorTotal' . $i;
        $row[$nombreVariable] = ${$nombreVariable};
        $row[$nombreVariable2] = ${$nombreVariable2};
        $row[$nombreVariable3] = ${$nombreVariable3};



    }

    // Agregar la fila modificada al array de resultados
    $datos[] = $row;
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conexion->close();

// Enviar los resultados modificados como respuesta JSON
echo json_encode($datos);

?>