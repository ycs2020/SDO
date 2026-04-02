<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$sql = "SELECT * FROM formularios_acceso ORDER BY unidadFormCCAA ASC";
$res = mysqli_query($conexion, $sql);

if ($res) {
    $datos = array();

    while ($row = $res->fetch_assoc()) {
        // Crea un nuevo objeto para cada fila de datos
        $fila = array(
            'idFormCCAA' => $row['idFormCCAA'],
            'unidadFormCCAA' => mb_convert_case($row['unidadFormCCAA'],MB_CASE_UPPER,'UTF-8')
        );
        // Agrega la fila al array de datos
        $datos[] = $fila;
    }

    // Devuelve los datos en formato JSON
    echo json_encode($datos);
} else {
    echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
}
?>
