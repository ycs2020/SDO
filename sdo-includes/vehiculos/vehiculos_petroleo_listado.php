<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

$idMovil = $_POST['idMovilAjax'] ?? null;

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

if ($idMovil) {
    $sql = "SELECT * FROM historial_petroleo_moviles WHERE idMovil = ? ORDER BY fechaRegistro DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idMovil);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];

    while ($row = $result->fetch_assoc()) {

        $idPetroleo = (int)$row["idPetroleo"];
        $fechaRegistroTime = formatearFechaCompletaAJAX($row['fechaRegistro']);
        $numGuia = h($row['numGuia']);
        $cantidadLitros = number_format($row['cantidadLitros'], 3, ',', '.').' Lts.';
        $valorUnitario = '$ '.number_format($row['valorUnitario'], 0, ',', '.');
        $totalSinIva = '$ '.number_format($row['totalSinIva'], 0, ',', '.');
        $IvaCalculado = '$ '.number_format($row['IvaCalculado'], 0, ',', '.');
        $totalConIva = '$ '.number_format($row['totalConIva'], 0, ',', '.');

        $btnModificar = 'Deshabilitado';
        $btnEliminar = 'Deshabilitado';

        if($idPetroleo > 0 ){
            $btnModificar = "<button class='btn btn-block btn-warning btn-mod-petro' data-id='".h($idPetroleo)."'><i class='fas fa-pen'></i></button>";

            $btnEliminar = "<button class='btn btn-block btn-danger btn-delete-petro' data-id='".h($idPetroleo)."'><i class='fas fa-trash'></i></button>";

        }


        $row['fechaFormateada'] = $fechaRegistroTime;
        $row['numGuiaFormateada'] = $numGuia;
        $row['cantidadLitrosFormateada'] = $cantidadLitros;
        $row['valorUnitarioFormateada'] = $valorUnitario;
        $row['totalSinIvaFormateada'] = $totalSinIva;
        $row['IvaCalculadoFormateada'] = $IvaCalculado;
        $row['totalConIvaFormateada'] = $totalConIva;
        $row['btnModificar'] = $btnModificar;
        $row['btnEliminar'] = $btnEliminar;


        $data[] = $row;
    }
    echo json_encode($data);


}
?>