<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut = $_POST["rut"];
    $rut = trim($rut);

    if (!empty($rut)) {

        // Realizar la búsqueda en la tabla SQL
        // Aquí deberías realizar la conexión a tu base de datos y la consulta SQL
        // La siguiente línea es solo un ejemplo y puede variar según el sistema de base de datos que estés utilizando
        // $resultado = mysqli_query($conexion, "SELECT * FROM ACM_owner WHERE rut = '$rut'");

        $rutSin = quitarCaracteresRUT($rut);

        $getFuncionarioACM = array();
        $getFuncionarioACM = getFuncionarioACM($rutSin);

        if ($getFuncionarioACM == 'No Existe') {
            echo json_encode(array("success" => false));
        } else {

            $nomFuncionarioACM = $getFuncionarioACM['OWNER_Name'];
            $nomIDACM = $getFuncionarioACM['ID'];
            $nomRUTACM = $getFuncionarioACM['OWNER_CODE'];
            $nomCardACM = $getFuncionarioACM['CARD_NUMBER'];
            $nomDeptoACM = $getFuncionarioACM['nomDepartament'];
            $posicion = strrpos($nomDeptoACM, '\\');

            // Obtiene el texto después del último backslash
            $texto_despues_backslash = substr($nomDeptoACM, $posicion + 1);

            $obtenerCalidadJuridica = obtenerCalidadJuridica($nomRUTACM);


            $rut = verificarYFormatearRUT($rut);

            // Ejemplo de resultado (simulado)
            $resultado = array("success" => true, "nombre" => $nomFuncionarioACM, "rut" => $rut, "id" => $nomIDACM, "calidad" => $obtenerCalidadJuridica, "nomDepartamento" => $texto_despues_backslash);

            if ($resultado) {
                // Devolver los datos encontrados
                echo json_encode(array_merge($resultado, array("success" => true)));
            } else {
                echo json_encode(array("success" => false));
            }
        }
    } else {
        echo json_encode(array("success" => false));
    }
} else {
    // Manejar de alguna manera si la solicitud no es POST
    echo json_encode(array("success" => false));
}