<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=utf-8');

$tipo = isset($_POST['tipo']) ? trim((string) $_POST['tipo']) : '';
$idCarpeta = isset($_POST['idCarpeta']) ? (int) $_POST['idCarpeta'] : 0;

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function formatearFechaCompletaAJAX($fechaRaw)
{
    if (empty($fechaRaw)) {
        return '-';
    }

    $timestamp = strtotime(str_replace('/', '-', (string) $fechaRaw));

    if ($timestamp === false || $timestamp <= 0) {
        return '-';
    }

    return date('d/m/Y', $timestamp);
}

function formatearMontoCLP($monto)
{
    $monto = (float) ($monto ?? 0);

    if ($monto <= 0) {
        return 'Sin Información';
    }

    return '$' . number_format($monto, 0, ',', '.');
}

function generarBotonVista($claseBadge, $claseBoton, array $dataAttrs = [], $valor = 0)
{
    $valor = (int) ($valor ?? 0);

    if ($valor <= 0) {
        return '<span class="text-muted small">-</span>';
    }

    $dataHtml = '';

    foreach ($dataAttrs as $key => $val) {
        $dataHtml .= ' data-' . h($key) . '="' . h($val) . '"';
    }

    return '
        <button 
            type="button"
            class="btn btn-block ' . h($claseBadge) . ' ' . h($claseBoton) . ' btn-sm"
            ' . $dataHtml . '
            title="Ver detalle"
        >
            <i class="fas fa-eye"></i>
        </button>
    ';
}

$botones = [
    'MPI' => ['bg-info', 'MANTENIMIENTO PREVENTIVO INTERNO'],
    'MRI' => ['bg-danger', 'MANTENIMIENTO REPARATIVO INTERNO'],
    'MPE' => ['bg-secondary', 'MANTENIMIENTO PREVENTIVO EXTERNO'],
    'MRE' => ['bg-purple', 'MANTENIMIENTO REPARATIVO EXTERNO'],
    'OM'  => ['bg-primary', 'OBRAS MENORES'],
    'IS'  => ['bg-orange', 'INSPECCIÓN Y SUPERVISIÓN']
];

if ($idCarpeta <= 0 || $tipo === '') {
    echo '<div class="alert alert-warning text-center mb-0">Parámetros inválidos para realizar la búsqueda.</div>';
    exit;
}

$claseBadge = $botones[$tipo][0] ?? 'bg-dark';
$textoTipo  = $botones[$tipo][1] ?? $tipo;

$filas = [];

/* =========================================================
 * 1) GASTOS REGISTRADOS
 * ========================================================= */
$sqlGasto = "
    SELECT 
        idGasto,
        fechaGasto,
        tipoMantencion,
        nomMantenimiento,
        solicitudCompra,
        idHojaTecnica,
        costoTotal,
        encargadoMant,
        idEquipo
    FROM gasto_equipo_carpeta
    WHERE idCategoria = ?
      AND tipoMantencion = ?
    ORDER BY fechaGasto DESC, idGasto DESC
";

$stmtGasto = $conexion->prepare($sqlGasto);

if (!$stmtGasto) {
    echo '<div class="alert alert-danger text-center mb-0">No fue posible preparar la consulta de gastos.</div>';
    exit;
}

$stmtGasto->bind_param('is', $idCarpeta, $tipo);

if (!$stmtGasto->execute()) {
    echo '<div class="alert alert-danger text-center mb-0">No fue posible ejecutar la consulta de gastos.</div>';
    $stmtGasto->close();
    exit;
}

$resGasto = $stmtGasto->get_result();

while ($row = $resGasto->fetch_assoc()) {
    $trabajo         = trim((string) ($row['nomMantenimiento'] ?? ''));
    $personal        = trim((string) ($row['encargadoMant'] ?? ''));
    $idEquipo        = (int) ($row['idEquipo'] ?? 0);
    $tipoMenuEquipo  = $idEquipo > 0 ? obtenerTipoMenu($idEquipo) : 'antiguo';
    $nomEquipamiento = $idEquipo > 0 ? trim((string) buscarequipo($idEquipo)) : '';

    $filas[] = [
        'nomEquipamiento' => $nomEquipamiento !== '' ? $nomEquipamiento : '-',
        'fecha'           => formatearFechaCompletaAJAX($row['fechaGasto'] ?? ''),
        'tipoMantencion'  => $row['tipoMantencion'] ?? '',
        'trabajo'         => $trabajo !== '' ? $trabajo : $textoTipo,
        'btnCompra'       => generarBotonVista(
            $claseBadge,
            'btn-abrirsc',
            [
                'idsc' => $row['solicitudCompra'] ?? 0
            ],
            $row['solicitudCompra'] ?? 0
        ),
        'btnHojaTecnica'  => generarBotonVista(
            $claseBadge,
            'btn-abrirht',
            [
                'idht' => $row['idHojaTecnica'] ?? 0
            ],
            $row['idHojaTecnica'] ?? 0
        ),
        'monto'           => formatearMontoCLP($row['costoTotal'] ?? 0),
        'personal'        => $personal !== '' ? mb_convert_case($personal, MB_CASE_UPPER, 'UTF-8') : '-',
        'btnEquipo'       => generarBotonVista(
            $claseBadge,
            'btn-abrirequipo',
            [
                'idequipo' => $idEquipo,
                'tipomenu' => $tipoMenuEquipo
            ],
            $idEquipo
        ),
        'ordenFechaReal'  => $row['fechaGasto'] ?? ''
    ];
}

$stmtGasto->close();

/* =========================================================
 * 2) HOJAS TÉCNICAS SIN GASTO
 * =========================================================
 * Excluye las hojas técnicas que ya estén registradas en gasto_equipo_carpeta
 */
$sqlHojasTecnicas = "
    SELECT 
        otg.idOrden,
        otg.fechaOrden,
        otg.tipoMantencion,
        otg.folioOrden,
        otg.ticketAsociado,
        otg.nomTecnicoRecepcionador,
        otg.trabajoRealizado,
        otg.idEquipo,
        COALESCE(mat.totalMateriales, 0) AS totalMateriales
    FROM ordenes_trabajos_general otg
    LEFT JOIN (
        SELECT 
            mog.idOrden,
            SUM(COALESCE(ie.valorUnitario, 0)) AS totalMateriales
        FROM material_orden_general mog
        LEFT JOIN inventario_equipos ie 
            ON ie.equipoInventario = mog.nomMaterial
        GROUP BY mog.idOrden
    ) mat ON mat.idOrden = otg.idOrden
    WHERE otg.idCategoria = ?
      AND otg.tipoMantencion = ?
      AND otg.idOrden NOT IN (
            SELECT COALESCE(gec.idHojaTecnica, 0)
            FROM gasto_equipo_carpeta gec
            WHERE gec.idCategoria = ?
              AND gec.tipoMantencion = ?
              AND COALESCE(gec.idHojaTecnica, 0) > 0
      )
    ORDER BY otg.fechaOrden DESC, otg.idOrden DESC
";

$stmtHojasTecnicas = $conexion->prepare($sqlHojasTecnicas);

if (!$stmtHojasTecnicas) {
    echo '<div class="alert alert-danger text-center mb-0">No fue posible preparar la consulta de hojas técnicas.</div>';
    $conexion->close();
    exit;
}

$stmtHojasTecnicas->bind_param('isis', $idCarpeta, $tipo, $idCarpeta, $tipo);

if (!$stmtHojasTecnicas->execute()) {
    echo '<div class="alert alert-danger text-center mb-0">No fue posible ejecutar la consulta de hojas técnicas.</div>';
    $stmtHojasTecnicas->close();
    $conexion->close();
    exit;
}

$resHojasTecnicas = $stmtHojasTecnicas->get_result();

while ($row = $resHojasTecnicas->fetch_assoc()) {
    $personal        = trim((string) ($row['nomTecnicoRecepcionador'] ?? ''));
    $trabajo         = trim((string) ($row['trabajoRealizado'] ?? ''));
    $monto           = (float) ($row['totalMateriales'] ?? 0);
    $idEquipo        = (int) ($row['idEquipo'] ?? 0);
    $tipoMenuEquipo  = $idEquipo > 0 ? obtenerTipoMenu($idEquipo) : 'antiguo';
    $nomEquipamiento = $idEquipo > 0 ? trim((string) buscarequipo($idEquipo)) : '';

    $filas[] = [
        'nomEquipamiento' => $nomEquipamiento !== '' ? $nomEquipamiento : '-',
        'fecha'           => formatearFechaCompletaAJAX($row['fechaOrden'] ?? ''),
        'tipoMantencion'  => $row['tipoMantencion'] ?? '',
        'trabajo'         => $trabajo !== '' ? $trabajo : $textoTipo,
        'btnCompra'       => '<span class="text-muted small">-</span>',
        'btnHojaTecnica'  => generarBotonVista(
            $claseBadge,
            'btn-abrirht',
            [
                'idht' => $row['idOrden'] ?? 0
            ],
            $row['idOrden'] ?? 0
        ),
        'monto'           => formatearMontoCLP($monto),
        'personal'        => $personal !== '' ? mb_convert_case($personal, MB_CASE_UPPER, 'UTF-8') : '-',
        'btnEquipo'       => generarBotonVista(
            $claseBadge,
            'btn-abrirequipo',
            [
                'idequipo' => $idEquipo,
                'tipomenu' => $tipoMenuEquipo
            ],
            $idEquipo
        ),
        'ordenFechaReal'  => $row['fechaOrden'] ?? ''
    ];
}

$stmtHojasTecnicas->close();

/* =========================================================
 * 3) ORDENAR TODO POR FECHA DESC
 * ========================================================= */
usort($filas, function ($a, $b) {
    $fechaA = strtotime(str_replace('/', '-', (string) ($a['ordenFechaReal'] ?? '')));
    $fechaB = strtotime(str_replace('/', '-', (string) ($b['ordenFechaReal'] ?? '')));

    $fechaA = $fechaA ?: 0;
    $fechaB = $fechaB ?: 0;

    return $fechaB <=> $fechaA;
});
?>

    <div class="card card-outline card-secondary shadow-sm mb-0">
        <div class="card-header">
            <h3 class="card-title mb-0">
            <span class="badge <?= h($claseBadge); ?>">
                <?= h($textoTipo); ?>
            </span>
            </h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table id="tablaDetalleGastosMantencion"
                    class="table table-sm table-bordered table-hover mb-0 text-center align-middle w-100">
                <thead class="thead-dark">
                <tr>
                    <th style="width: 60px;">N°</th>
                    <th style="width: 110px;">Fecha</th>
                    <th style="width: 120px;">Tipo</th>
                    <th>Trabajo Realizado</th>
                    <th style="width: 120px;">Solicitud Compra</th>
                    <th style="width: 110px;">Hoja Técnica</th>
                    <th style="width: 120px;">Monto</th>
                    <th>Personal</th>
                    <th>Equipo</th>
                    <th style="width: 90px;">Ver Equipo</th>
                </tr>
                </thead>

                <tbody>
                <?php if (!empty($filas)) { ?>
                    <?php foreach ($filas as $index => $fila) { ?>
                        <tr>
                            <td class="text-center align-middle" style="font-size: 10pt;"><?= $index + 1; ?></td>
                            <td class="text-center align-middle" style="font-size: 10pt;"><?= h($fila['fecha']); ?></td>
                            <td class="text-center align-middle">
                            <span class="badge <?= h($claseBadge); ?>">
                                <?= h($fila['tipoMantencion']); ?>
                            </span>
                            </td>
                            <td class="align-middle" style="font-size: 10pt;"><?= h($textoTipo); ?></td>
                            <td class="text-center align-middle"><?= $fila['btnCompra']; ?></td>
                            <td class="text-center align-middle"><?= $fila['btnHojaTecnica']; ?></td>
                            <td class="text-center align-middle font-weight-bold" style="font-size: 10pt;">
                                <?php
                                if ($fila['monto'] !== 'Sin Información') {
                                    echo '<span class="font-weight-bold text-success">' . h($fila['monto']) . '</span>';
                                } else {
                                    echo '<span class="text-muted font-italic">' . h($fila['monto']) . '</span>';
                                }
                                ?>
                            </td>
                            <td class="align-middle" style="font-size: 10pt;"><?= h($fila['personal']); ?></td>
                            <td class="text-center align-middle" style="font-size: 10pt;"><?= h($fila['nomEquipamiento']); ?></td>
                            <td class="text-center align-middle"><?= $fila['btnEquipo']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No se encontraron registros para este tipo de mantención.
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <script>
                (function () {
                    const $tabla = $('#tablaDetalleGastosMantencion');

                    if ($.fn.DataTable.isDataTable($tabla)) {
                        $tabla.DataTable().destroy();
                    }

                    $tabla.DataTable({
                        destroy: true,
                        lengthChange: false,
                        searching: true,
                        autoWidth: false,
                        pageLength: 7,
                        responsive: true,
                        ordering: false,
                        processing: true,
                        columnDefs: [
                            {
                                targets: '_all',
                                className: 'text-center align-middle'
                            },
                            {
                                targets: [3, 7, 8],
                                className: 'align-middle'
                            }
                        ],
                        language: {
                            lengthMenu: "Visualizando _MENU_ Registros por Página",
                            zeroRecords: "No se han encontrado registros.",
                            info: "Mostrando página _PAGE_ de _PAGES_",
                            infoEmpty: "No existen registros.",
                            infoFiltered: "(Filtrado desde _MAX_ registros totales)",
                            search: "Buscar:",
                            processing: "Procesando...",
                            paginate: {
                                previous: "Anterior",
                                next: "Siguiente"
                            }
                        }
                    });

                    $(document)
                        .off('click', '.btn-abrirht')
                        .on('click', '.btn-abrirht', function () {
                            let idht = $(this).data('idht');

                            let form = $('<form>', {
                                action: '../sdo-pdf/SDO-OrdenTrabajoGeneral.php',
                                method: 'POST',
                                target: '_blank'
                            });

                            form.append($('<input>', {
                                type: 'hidden',
                                name: 'idOrden',
                                value: idht
                            }));

                            $('body').append(form);
                            form.trigger('submit');
                            form.remove();
                        });

                    $(document)
                        .off('click', '.btn-abrirsc')
                        .on('click', '.btn-abrirsc', function () {
                            let idsc = $(this).data('idsc');

                            let form = $('<form>', {
                                action: '../sdo-pdf/SDO-SoliCompra-SDO.php',
                                method: 'POST',
                                target: '_blank'
                            });

                            form.append($('<input>', {
                                type: 'hidden',
                                name: 'idSoliCompra',
                                value: idsc
                            }));

                            $('body').append(form);
                            form.trigger('submit');
                            form.remove();
                        });

                    $(document)
                        .off('click', '.btn-abrirequipo')
                        .on('click', '.btn-abrirequipo', function () {
                            let idequipo = $(this).data('idequipo');
                            let tipomenu = $(this).data('tipomenu');
                            let urlDestino = (tipomenu === 'nuevo')
                                ? 'equipo_menuNuevo.php'
                                : 'equipo_menuAntiguo.php';

                            let form = $('<form>', {
                                action: urlDestino,
                                method: 'POST',
                                target: '_blank'
                            });

                            form.append($('<input>', {
                                type: 'hidden',
                                name: 'idEquipo',
                                value: idequipo
                            }));

                            $('body').append(form);
                            form.trigger('submit');
                            form.remove();
                        });
                })();
            </script>
        </div>
    </div>

<?php
$conexion->close();
?>