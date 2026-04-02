<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function formatearVacioFilas($valor)
{
    $valorLimpio = trim((string)($valor ?? ''));
    return $valorLimpio !== '' ? $valorLimpio : '-';
}

function h($string)
{
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;

if ($idCarpeta <= 0) {
    echo '<div class="alert alert-warning">Categoría no válida.</div>';
    exit;
}

/*
 * Si quieres filtrar por categoría real en equipamiento_hbqp,
 * esta consulta toma solo años donde existan registros de checklist
 * asociados a equipos UCA de esa categoría.
 */
$sqlBuscarAnios = "
    SELECT DISTINCT YEAR(dcu.fecha) AS anios
    FROM datos_checklist_ucas dcu
    INNER JOIN equipamiento_hbqp eh 
        ON eh.nombreEquipo = dcu.uca
    WHERE eh.idCategoria = $idCarpeta
      AND eh.nombreEquipo LIKE '%UCA-%'
      AND dcu.fecha IS NOT NULL
    ORDER BY anios DESC
";
$resBuscarAnios = mysqli_query($conexion, $sqlBuscarAnios);

if (!$resBuscarAnios) {
    echo '<div class="alert alert-danger">Error al obtener los años.</div>';
    exit;
}

$anios = array();

while ($row = mysqli_fetch_assoc($resBuscarAnios)) {
    $anios[] = (int)$row['anios'];
}

if (empty($anios)) {
    echo '<div class="alert alert-info">No se encontraron registros para esta categoría.</div>';
    exit;
}
?>

<div class="card">
    <div class="card-body p-0">
        <ul class="nav nav-tabs">
            <?php
            $primero = true;
            foreach ($anios as $year) {
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $primero ? 'active' : '' ?>"
                            data-toggle="tab"
                            href="#tab<?= (int)$year ?>">
                        <?= (int)$year ?>
                    </a>
                </li>
                <?php
                $primero = false;
            }
            ?>
        </ul>
    </div>
</div>

<?php
$pisos = array();

$sqlPisos = "
    SELECT DISTINCT piso
    FROM equipamiento_hbqp
    WHERE idCategoria = $idCarpeta
      AND nombreEquipo LIKE '%UCA-%'
      AND piso IS NOT NULL
      AND TRIM(piso) <> ''
    ORDER BY piso ASC
";
$resPisos = mysqli_query($conexion, $sqlPisos);

if ($resPisos) {
    while ($rowPiso = mysqli_fetch_assoc($resPisos)) {
        $pisos[] = $rowPiso['piso'];
    }
}
?>

<div class="tab-content mt-3">
    <?php
    $primero = true;

    foreach ($anios as $year) {
        ?>
        <div id="tab<?= (int)$year ?>" class="tab-pane fade <?= $primero ? 'show active' : '' ?>">
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="text-center mb-0">TABLA DEL AÑO <?= (int)$year ?></h4>
                </div>

                <hr>
                <div class="card-body border-bottom">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label><strong>Buscar por UCA</strong></label>
                            <input type="text"
                                    class="form-control filtro-uca"
                                    data-target="tabla<?= (int)$year ?>"
                                    placeholder="Ej: UCA-01">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label><strong>Filtrar por piso</strong></label>
                            <select class="form-control filtro-piso" data-target="tabla<?= (int)$year ?>">
                                <option value="">Todos los pisos</option>
                                <?php
                                foreach ($pisos as $piso) {
                                    ?>
                                    <option value="<?= h($piso) ?>"><?= h($piso) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="table-responsive" style="max-height: 1000px; overflow-y: auto;">
                    <table id="tabla<?= (int)$year ?>" class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                        <tr class="text-center">
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">DISPOSITIVO</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">ENERO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">FEBRERO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">MARZO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">ABRIL (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">MAYO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">JUNIO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">JULIO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">AGOSTO (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">SEPTIEMBRE (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">OCTUBRE (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">NOVIEMBRE (<?= (int)$year ?>)</th>
                            <th style="position: sticky; top: 0; background: #fff; z-index: 10;">DICIEMBRE (<?= (int)$year ?>)</th>

                            <?php if ((int)$year === (int)date('Y')) { ?>
                                <th style="position: sticky; top: 0; background: #fff; z-index: 10;">PRÓXIMO MANTENIMIENTO</th>
                            <?php } ?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $sqlUcasEquipos = "
                                SELECT *
                                FROM equipamiento_hbqp
                                WHERE idCategoria = $idCarpeta
                                  AND nombreEquipo LIKE '%UCA-%'
                                ORDER BY piso ASC, nombreEquipo ASC
                            ";
                        $resUcasEquipos = mysqli_query($conexion, $sqlUcasEquipos);

                        if ($resUcasEquipos && mysqli_num_rows($resUcasEquipos) > 0) {
                            while ($rowUcasEquipos = mysqli_fetch_assoc($resUcasEquipos)) {
                                $nombreEquipoUC = $rowUcasEquipos['nombreEquipo'];
                                $nombreEquipoUC_safe = mysqli_real_escape_string($conexion, $nombreEquipoUC);

                                $sqlDATOSUCA = "
                                        SELECT 
                                            dcu.*,
                                            tg.tecnico1,
                                            MONTH(dcu.fecha) AS mes,
                                            DATE(dcu.fecha) AS fechaMant
                                        FROM datos_checklist_ucas dcu
                                        LEFT JOIN trabajos_general tg 
                                            ON dcu.idTG = tg.idTG
                                        WHERE dcu.uca = '$nombreEquipoUC_safe'
                                          AND YEAR(dcu.fecha) = $year
                                        ORDER BY dcu.fecha ASC
                                    ";
                                $resDatosUCA = mysqli_query($conexion, $sqlDATOSUCA);

                                $tecnicosPorMes = array_fill(1, 12, array());
                                $ultimaFecha = null;

                                if ($resDatosUCA) {
                                    while ($rowDato = mysqli_fetch_assoc($resDatosUCA)) {
                                        $mes = (int)$rowDato['mes'];
                                        $fechaMant = !empty($rowDato['fechaMant']) ? date('Y-m-d', strtotime($rowDato['fechaMant'])) : null;
                                        $tecnico = formatearVacioFilas($rowDato['tecnico1']);

                                        if ($mes >= 1 && $mes <= 12 && $fechaMant) {
                                            $tecnicosPorMes[$mes][] = $tecnico . ' (' . date('d/m/Y', strtotime($fechaMant)) . ')';
                                        }

                                        if ($fechaMant && (!$ultimaFecha || strtotime($fechaMant) > strtotime($ultimaFecha))) {
                                            $ultimaFecha = $fechaMant;
                                        }
                                    }
                                }
                                ?>
                                <tr data-uca="<?= h(mb_strtolower($nombreEquipoUC, 'UTF-8')) ?>"
                                        data-piso="<?= h(mb_strtolower((string)$rowUcasEquipos['piso'], 'UTF-8')) ?>"
                                        data-busqueda="<?= h(mb_strtolower($nombreEquipoUC . ' ' . $rowUcasEquipos['piso'], 'UTF-8')) ?>">
                                    <td><?= h($nombreEquipoUC) ?></td>

                                    <?php
                                    for ($m = 1; $m <= 12; $m++) {
                                        $listaTecnicos = !empty($tecnicosPorMes[$m])
                                            ? implode('<br>', array_map('h', $tecnicosPorMes[$m]))
                                            : '-';
                                        ?>
                                        <td><?= $listaTecnicos ?></td>
                                        <?php
                                    }
                                    ?>

                                    <?php if ((int)$year === (int)date('Y')) { ?>
                                        <td class="text-center">
                                            <?php
                                            if ($ultimaFecha) {
                                                $proxMantFecha = date('Y-m-d', strtotime('+6 months', strtotime($ultimaFecha)));
                                                $proxMantMostrar = date('d/m/Y', strtotime($proxMantFecha));
                                                $hoy = date('Y-m-d');

                                                $style = ($proxMantFecha < $hoy)
                                                    ? 'color:red; font-weight:bold;'
                                                    : 'color:#28a745; font-weight:bold;';

                                                echo '<span style="' . $style . '">' . h($proxMantMostrar) . '</span>';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="<?= ((int)$year === (int)date('Y')) ? 14 : 13 ?>" class="text-center">
                                    No se encontraron equipos UCA para esta categoría.
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
        <?php
        $primero = false;
    }
    ?>
</div>

<script>
    (function () {

        function aplicarFiltros(tablaId) {
            var tabla = document.getElementById(tablaId);
            if (!tabla) {
                return;
            }

            var inputUca = document.querySelector('.filtro-uca[data-target="' + tablaId + '"]');
            var selectPiso = document.querySelector('.filtro-piso[data-target="' + tablaId + '"]');

            var textoUca = inputUca ? inputUca.value.toLowerCase().trim() : '';
            var piso = selectPiso ? selectPiso.value.toLowerCase().trim() : '';

            var filas = tabla.querySelectorAll('tbody tr');

            filas.forEach(function (fila) {
                var ucaFila = (fila.getAttribute('data-uca') || '').toLowerCase();
                var pisoFila = (fila.getAttribute('data-piso') || '').toLowerCase();

                var coincideUca = textoUca === '' || ucaFila.indexOf(textoUca) !== -1;
                var coincidePiso = piso === '' || pisoFila === piso;

                fila.style.display = (coincideUca && coincidePiso) ? '' : 'none';
            });
        }

        document.querySelectorAll('.filtro-uca').forEach(function (input) {
            input.onkeyup = function () {
                aplicarFiltros(this.getAttribute('data-target'));
            };
        });

        document.querySelectorAll('.filtro-piso').forEach(function (select) {
            select.onchange = function () {
                aplicarFiltros(this.getAttribute('data-target'));
            };
        });

    })();
</script>