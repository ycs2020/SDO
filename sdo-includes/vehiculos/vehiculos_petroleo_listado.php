<?php
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

$idMovil = $_POST['idMovilAjax'] ?? null;

// Función optimizada para mostrar Fecha y Hora en la tabla
function formatearFechaCompletaAJAX($fechaRaw)
{
    if (empty($fechaRaw) || $fechaRaw == '0000-00-00 00:00:00' || $fechaRaw == '0000-00-00') {
        return 'Sin fecha';
    }

    $timestamp = strtotime($fechaRaw);

    if ($timestamp !== false) {
        // Usamos tu función fechaEspañol2 para el día y date para la hora
        $inicioFecha = fechaEspañol2($timestamp);
        $horaFecha = date('H:i', $timestamp);
        return "<b>$inicioFecha</b><br><small><i class='far fa-clock mr-1'></i>$horaFecha hrs.</small>";
    }

    return 'Fecha inválida';
}

function formatearSoloFechaAJAX($fechaRaw)
{
    if (empty($fechaRaw) || $fechaRaw == '0000-00-00 00:00:00' || $fechaRaw == '0000-00-00') {
        return 'Sin fecha';
    }

    $timestamp = strtotime($fechaRaw);

    if ($timestamp !== false) {
        // Usamos tu función fechaEspañol2 para el día y date para la hora
        $inicioFecha = fechaEspañol2($timestamp);
        return "<b>$inicioFecha</b>";
    }

    return 'Fecha inválida';
}

function h($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
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

        // 1. Guardamos la fecha original para que el JS la use en el modal de edición
        // Esto evita problemas de formato al editar.
        $row['fechaRegistro'] = $row['fechaRegistro'];

        // 2. Formateamos la visualización para la tabla
        $row['fechaFormateada'] = formatearFechaCompletaAJAX($row['fechaRegistro']);
        $row['fechaSubidaFormateada'] = formatearSoloFechaAJAX($row['fechaSubida']);

        // 3. Formateo de valores monetarios y litros
        $row['numGuiaFormateada'] = h($row['numGuia']);
        $row['nomConductorFormateada'] = h(!empty($row['nomConductor']) ? $row['nomConductor'] : 'Sin Información');
        $row['cantidadLitrosFormateada'] = number_format($row['cantidadLitros'], 2, ',', '.') . ' Lts.';
        $row['valorUnitarioFormateada'] = '$' . number_format($row['valorUnitario'], 0, ',', '.');
        $row['totalSinIvaFormateada'] = '$' . number_format($row['totalSinIva'], 0, ',', '.');
        $row['IvaCalculadoFormateada'] = '$' . number_format($row['IvaCalculado'], 0, ',', '.');
        $row['totalConIvaFormateada'] = '<strong class="text-dark">$' . number_format($row['totalConIva'], 0, ',', '.') . '</strong>';

        // 4. Botones de acción
        $btnModificar = 'Deshabilitado';
        $btnEliminar = 'Deshabilitado';

        if($idPetroleo > 0 ){
            $btnModificar = "<button class='btn btn-sm btn-warning btn-mod-petro' data-id='".h($idPetroleo)."' title='Editar'><i class='fas fa-pen'></i></button>";
            $btnEliminar = "<button class='btn btn-sm btn-danger btn-delete-petro' data-id='".h($idPetroleo)."' title='Eliminar'><i class='fas fa-trash'></i></button>";
        }

        $row['btnModificar'] = $btnModificar;
        $row['btnEliminar'] = $btnEliminar;

        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>