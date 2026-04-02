<?php
// Conéctate a tu base de datos o procesa tus datos de alguna otra manera
// Ejemplo de conexión a una base de datos MySQL

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

$query = "SELECT CARD_NUMBER, OWNER_Name FROM ACM_owner WHERE OWNER_CODE = '".$_POST["buscador"]."'"; // Personaliza la consulta SQL según tus necesidades

$resultado = mysqli_query($conexion,$query);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila["CARD_NUMBER"] . "</td>";
        echo "<td>" . $fila["OWNER_Name"] . "</td>";
        // Agrega más celdas según tus necesidades
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No se encontraron resultados.</td></tr>";
}

$conexion->close();
?>