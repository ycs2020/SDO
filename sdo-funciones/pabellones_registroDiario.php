<?php
include '../sdo-funciones/conexion.php';

date_default_timezone_set('America/Santiago');
// Verificar si se ha enviado un formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fechaActual = date('Y-m-d H:i:s');
    // Recuperar datos del formulario
    $nomUsuario = isset($_POST['personalRegistro']) ? $_POST['personalRegistro'] : null;

    // Verificar si $nomUsuario está definido antes de continuar
    if (isset($nomUsuario)) {

        // Preparar una declaración SQL para insertar datos en la base de datos
        $data = "";
        $valores = "";

        for ($j = 2; $j <= 10; $j++) {
            // Verificar si los campos específicos están definidos antes de acceder a ellos
            $tempPabellon = isset($_POST['inputTempPab' . $j]) ? $_POST['inputTempPab' . $j] : null;
            $humedadPabellon = isset($_POST['inputHumedadPab' . $j]) ? $_POST['inputHumedadPab' . $j] : null;

            // Verificar si ambos $tempPabellon y $humedadPabellon están definidos antes de agregar a la declaración SQL
            if (isset($tempPabellon, $humedadPabellon)) {

                // Obtener la fecha actual (puedes ajustar esto según tus necesidades)
                $fechaActual = date('Y-m-d H:i:s');

                $data .= "tempPabellon$j,humedadPabellon$j,";
                // Agregar los datos a la declaración SQL
                $valores .= "'$tempPabellon', '$humedadPabellon',";
            }
        }

        // Eliminar la coma extra al final de la cadena SQL
        $data = rtrim($data, ',');
        $valores = rtrim($valores, ',');

        if(!empty($valores) && !empty($data)){

            $sql = "INSERT INTO pabellones_correo (idRegistroPab,nomRegistroPab,fechaRegistroPab,$data) VALUES (NULL,'$nomUsuario','$fechaActual',$valores)";

            echo $sql;

        }else{
            echo 'Sin Info';
        }

    } else {
        echo "Error: El campo 'personalRegistro' no está definido.";
    }

    // Cerrar la conexión a la base de datos
//    mysqli_close($conexion);
} else {
    // Si no es una solicitud POST, redirigir o realizar otras acciones según sea necesario
    echo 'Sin Info';
}
?>
