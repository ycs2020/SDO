<?php
// Archivo: tu_servidor.php


// Conexión a la base de datos
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');

if (isset($_POST['idMovilAjax'])) {


    $idMovilAjax = $_POST['idMovilAjax'];    //


    if ($idMovilAjax > 0) {

// Consulta SQL
        $sql = "SELECT * FROM historial_petroleo_moviles WHERE idMovil = ? ORDER BY fechaRegistro DESC;";

// Preparar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idMovilAjax);
        $stmt->execute();

// Obtener los resultados
        $result = $stmt->get_result();
        $datos = array();

// Iterar sobre los resultados y aplicar la lógica necesaria
        while ($row = $result->fetch_assoc()) {
            $idPetroleo = $row["idPetroleo"];
            $fechaRegistro = $row["fechaRegistro"];
            $numGuia = $row["numGuia"];
            $cantidadLitros = $row["cantidadLitros"];
            $valorUnitario = $row["valorUnitario"];
            $totalSinIva = $row['totalSinIva'];
            $IvaCalculado = $row['IvaCalculado'];
            $totalConIva = $row['totalConIva'];

            $fechaTime = strtotime($fechaRegistro);
            $fechaDate = date('d-m-Y',$fechaTime);

            $cantidadLitros2 = number_format($cantidadLitros, 3, ',', '.');
            $cantidadLitros2 = $cantidadLitros2.' Lts.';

            $valorUnitario2 = number_format($valorUnitario, 0, ',', '.');
            $valorUnitario2 = '$ '.$valorUnitario2;

            $totalSinIva2 = number_format($totalSinIva, 0, ',', '.');
            $totalSinIva2 = '$ '.$totalSinIva2;

            $IvaCalculado2 = number_format($IvaCalculado, 0, ',', '.');
            $IvaCalculado2 = '$ '.$IvaCalculado2;

            $totalConIva2 = number_format($totalConIva, 0, ',', '.');
            $totalConIva2 = '$ '.$totalConIva2;


            $row["idPetroleo"] = $idPetroleo;
            $row["fechaRegistro2"] = $fechaDate;
            $row["numGuia"] = $numGuia;
            $row["cantidadLitros2"] = $cantidadLitros2;
            $row["valorUnitario2"] = $valorUnitario2;
            $row["totalSinIva2"] = $totalSinIva2;
            $row["IvaCalculado2"] = $IvaCalculado2;
            $row["totalConIva2"] = $totalConIva2;


            // Agregar la fila modificada al array de resultados
            $datos[] = $row;
        }

// Cerrar la conexión y liberar recursos
        $stmt->close();
        $conexion->close();

// Enviar los resultados modificados como respuesta JSON
        echo json_encode($datos);
    } else {
        return '';
    }

} else {
    return '';
}

?>