<?php
include_once '../sdo-funciones/conexion.php';

function normalizarRutSinPuntosNiGuion($rut) {
    $rut = str_replace(['.', '-'], '', $rut);
    $rut = preg_replace('/[kK]$/', '0', $rut);
    return str_pad($rut, 9, '0', STR_PAD_LEFT);
}

function fechaMinimizadoSinHoras($FechaStamp)
{
    $FechaStamp2 = strtotime($FechaStamp);
    $ano = date('Y', $FechaStamp2);
    $mes = date('n', $FechaStamp2);
    $dia = date('d', $FechaStamp2);
    $mesesN = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    return " $dia " . $mesesN[$mes] . " $ano";
}

$sql = "SELECT * FROM memos_control_acceso ORDER BY id DESC";
$res = mysqli_query($conexion, $sql);

$data = [];
$modales = '';

while ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
    $nomEmisor = $row['nomEmisor'];
    $fechaRecepcion = $row['fechaRecepcion'];

    $fechaEmision = $row['fechaEmision'];

    // Solo convertir si es una fecha válida tipo Y-m-d
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaRecepcion)) {
        $fechaRecepcion = fechaMinimizadoSinHoras($fechaRecepcion);
    } else {
        $fechaRecepcion = 'Sin Fecha';
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaEmision)) {
        $fechaEmision = fechaMinimizadoSinHoras($fechaEmision);
    } else {
        $fechaEmision = 'Sin Fecha';
    }

    $nombreUnidad = $row['unidad'];
    $numeroMemo = $row['numeroMemo'];

    // Buscar archivo asociado
    $sqlArchivo = "SELECT rutaArchivo FROM archivos_memo_control_acceso WHERE idMemo = $id LIMIT 1";
    $resArchivo = mysqli_query($conexion, $sqlArchivo);
    $rutaArchivo = mysqli_num_rows($resArchivo) ? mysqli_fetch_assoc($resArchivo)['rutaArchivo'] : '';

    $archivoHtml = empty($rutaArchivo) ? 'Sin Archivo' : '
        <form action="memo_control_acceso.php" target="_blank" method="POST" style="display: inline">
            <input type="hidden" name="ruta" value="' . $rutaArchivo . '">
            <button type="submit" class="btn btn-block btn-sm btn-success"><i class="fas fa-file-download"></i>&nbsp;&nbsp;Descargar Memorandum</button>
        </form>';

    $botonFuncionarios = '<button type="button" class="btn btn-block btn-sm btn-warning" data-toggle="modal" data-target="#modalFuncionarios' . $id . '"><i class="fas fa-users"></i>&nbsp;&nbsp;Ver Funcionarios</button>';

    // Modal de funcionarios
    $modalHtml = '
    <div class="modal fade" id="modalFuncionarios' . $id . '" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning ">
                    <h5 class="modal-title"><i class="fas fa-users mr-2"></i>Funcionarios</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tablaFuncionariosMemos" class="table table-bordered table-sm text-center">
                        <thead class="thead-dark">
                            <tr><th>Nombre</th><th>RUT</th><th>Ver Acta</th></tr>
                        </thead>
                        <tbody>';

    $sqlFunc = "SELECT * FROM memos_funcionarios WHERE idMemo = $id";
    $resFunc = mysqli_query($conexion, $sqlFunc);
    while ($func = mysqli_fetch_assoc($resFunc)) {
        $nombre = $func['nomFuncionario'];
        $rut = $func['rutFuncionario'];
        $rutNormalizado = normalizarRutSinPuntosNiGuion($rut);

        $idActa = null;
        $sqlActa = "SELECT idActa FROM actas WHERE rutTarjeta LIKE '$rutNormalizado' LIMIT 1";
        $resActa = mysqli_query($conexion, $sqlActa);
        if ($resActa && mysqli_num_rows($resActa) > 0) {
            $idActa = mysqli_fetch_assoc($resActa)['idActa'];
        }

        $actaBtn = empty($idActa) ? 'Sin Acta' : '
            <form action="actas-funcionario.php" method="POST" class="mb-0">
                <input type="hidden" name="idActa" value="' . $idActa . '">
                <input type="hidden" name="rutActa2" value="' . $rut . '">
                <input type="hidden" name="origen" value="memo_control_acceso.php">
                <button type="submit" class="btn btn-block btn-sm btn-primary"><i class="fas fa-eye"></i></button>
            </form>';

        $modalHtml .= '<tr><td>' . $nombre . '</td><td>' . $rut . '</td><td>' . $actaBtn . '</td></tr>';
    }

    $modalHtml .= '
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>';

    // Agrega al acumulador
    $modales .= $modalHtml;

    $data[] = [
        'id' => $id,
        'fechaRecepcion' => $fechaRecepcion,
        'unidad' => $nombreUnidad,
        'emisor' => $nomEmisor,
        'fechaEmision' => $fechaEmision,
        'numeroMemo' => $numeroMemo,
        'archivo' => $archivoHtml,
        'funcionarios' => $botonFuncionarios
    ];
}

// Enviar HTML de modales aparte
echo json_encode([
    'data' => $data,
    'modales' => $modales
]);
?>
