<?php
// Conéctate a tu base de datos o procesa tus datos de alguna otra manera
// Ejemplo de conexión a una base de datos MySQL

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];

    $query = "SELECT CARD_NUMBER, OWNER_Name FROM ACM_owner WHERE OWNER_CODE = '".$busqueda."'"; // Personaliza la consulta SQL según tus necesidades

    $resultado = mysqli_query($conexion,$query);

    $resultados = array();

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $resultados[] = $fila;
        }
    }

    echo json_encode($resultados);

    $conexion->close();
}
?>

