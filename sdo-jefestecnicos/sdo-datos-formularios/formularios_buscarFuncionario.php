<?php
//
//include '../../sdo-funciones/conexion.php';
//include '../../sdo-funciones/funciones.php';
//
//if (isset($_POST['idDepartamento'])) {
//
//    $idDepartamento = trim($_POST['idDepartamento']);
//
//    $cadena = '';
//
//    if (!empty($_POST['idDepartamento'])) {
//
//    }
//
//}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dato = $_POST["dato"];

    // Aquí puedes procesar y almacenar el dato en una base de datos si es necesario
    // Puedes imprimir una respuesta en formato JSON, por ejemplo:
    $respuesta = array("mensaje" => "Dato recibido correctamente");
    echo json_encode($respuesta);
} else {
    // Manejar de alguna manera si la solicitud no es POST
}
