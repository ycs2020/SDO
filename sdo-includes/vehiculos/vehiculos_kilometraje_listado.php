<?php
require_once("../../sdo-funciones/conexion.php");
require_once("../../sdo-funciones/funciones.php");
header('Content-Type: application/json');

$idMovil = isset($_POST['idMovil']) ? (int)$_POST['idMovil'] : 0;
$response = ["data" => []];


function formatearFechaCompletaAJAX($fechaRaw)
{
    $fechaCompleta = 'Fecha inválida';

    if (!empty($fechaRaw)) {
        $timestamp = strtotime(str_replace('/', '-', $fechaRaw));

        if ($timestamp !== false && $timestamp > 0) {
            $inicioFecha = fechaEspañol2($timestamp);
            $fechaCompleta = $inicioFecha;
        }
    }

    return $fechaCompleta;
}

function h($valor)
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function m($valor)
{
    return mb_convert_case($valor, MB_CASE_UPPER, 'UTF-8');
}


if ($idMovil > 0) {
    $sql = "SELECT idKilometraje, fechaRegistro, cantKilometraje, registroMantenimiento 
            FROM historial_kilometraje_moviles 
            WHERE idMovil = ? 
            ORDER BY fechaRegistro DESC";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $idMovil);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            // Guardamos el original para procesos (como editar)
            $idKilometraje = (int)$row['idKilometraje'];
            $valorLimpio = $row['cantKilometraje'];
            $fechaRegistro = $row['fechaRegistro'];

            $btnModificar = 'Deshabilitado';
            $btnEliminar = 'Deshabilitado';

            if($idKilometraje > 0 ){
                $btnModificar = "<button class='btn btn-block btn-warning btn-mod-km' data-id='".h($idKilometraje)."'><i class='fas fa-pen'></i></button>";

                $btnEliminar = "<button class='btn btn-block btn-danger btn-delete-km' data-id='".h($idKilometraje)."'><i class='fas fa-trash'></i></button>";

            }



             // Formateamos la fecha para mostrar en la tabla
            $fechaRegistroTime = formatearFechaCompletaAJAX($fechaRegistro);

            // Creamos una versión formateada para mostrar en la tabla (ej: 150.000)
            $row['cantKilometraje'] = number_format($valorLimpio, 0, ',', '.');

            // Pasamos el valor numérico real en una llave oculta para que el botón de editar no falle
            $row['valorNumerico'] = $valorLimpio;
            $row['fechaRegistroFormateada'] = $fechaRegistroTime;
            $row['btnModificar'] = $btnModificar;
            $row['btnEliminar'] = $btnEliminar;

            $response['data'][] = $row;
        }
        $stmt->close();
    }
}

echo json_encode($response);