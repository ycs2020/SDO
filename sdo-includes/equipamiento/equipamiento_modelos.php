<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

function formatearVacioFilas($valor)
{
    $valorLimpio = trim($valor ?? '');
    return $valorLimpio !== '' ? $valorLimpio : '-';
}

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;

if ($idCarpeta <= 0) {
    echo '<div class="alert alert-warning">Categoría no válida.</div>';
    exit;
}

$sqlInfoEquipo = "SELECT DISTINCT modelo, marca 
                  FROM equipamiento_hbqp 
                  WHERE idCategoria = $idCarpeta
                    AND modelo IS NOT NULL
                    AND TRIM(modelo) <> ''
                  ORDER BY modelo ASC, marca ASC";

$resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);

if ($resInfoEquipo && mysqli_num_rows($resInfoEquipo) > 0) {
    ?>
    <div class="container-fluid">
        <h5 class="text-center">Modelos de Equipamiento</h5>
        <hr>

        <div class="row mb-3">
            <div class="col-md-8 col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input
                            type="text"
                            id="buscadorEquipos"
                            class="form-control"
                            placeholder="Buscar por modelo, marca o valor..."
                            autocomplete="off"
                    >
                </div>
            </div>

            <div class="col-md-4 col-12 mb-2 text-md-right text-left">
                <span class="badge badge-primary p-2" id="contadorResultados">
                    Resultados: 0
                </span>
            </div>
        </div>

        <div id="mensajeSinResultados" class="alert alert-warning text-center" style="display:none;">
            No se encontraron equipos con ese criterio de búsqueda.
        </div>

        <div class="row" id="contenedorEquipos">
            <?php
            $totalRegistros = 0;

            while ($rowInfoEquipo = mysqli_fetch_assoc($resInfoEquipo)) :
                $modelo = trim($rowInfoEquipo['modelo'] ?? '');
                $marca  = trim($rowInfoEquipo['marca'] ?? '');

                $modelo2 = $modelo !== '' ? $modelo : 'No Aplica';
                $marca2  = $marca !== '' ? $marca : 'No Aplica';

                $modelo2 = mb_convert_case($modelo2, MB_CASE_UPPER, 'UTF-8');
                $marca2  = mb_convert_case($marca2, MB_CASE_UPPER, 'UTF-8');

                $rutaImagen = '';
                if (function_exists('getRutaImagenEquipamiento')) {
                    $rutaImagen = getRutaImagenEquipamiento($modelo2, $marca2) ?? '';
                }

                $valorEquipamiento = 0;
                if (function_exists('getValorEquipamiento')) {
                    $valorEquipamiento = (float)(getValorEquipamiento($modelo, $marca) ?? 0);
                }

                $valorMostrado = $valorEquipamiento > 0
                    ? moneda_chilena($valorEquipamiento)
                    : 'Sin Información';

                $srcImagen = !empty($rutaImagen)
                    ? $rutaImagen
                    : 'img/controlCent-HBQP.png';

                $textoBusqueda = mb_strtolower(
                    $modelo2 . ' ' . $marca2 . ' ' . $valorMostrado,
                    'UTF-8'
                );

                $totalRegistros++;
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3 card-equipo"
                        data-search="<?= h($textoBusqueda) ?>">
                    <div class="card card-primary card-outline h-100">
                        <div class="card-body box-profile">
                            <div class="text-center mb-3">
                                <img class="img-fluid"
                                        style="width: 200px; height: 150px; object-fit: contain;"
                                        src="<?= h($srcImagen) ?>"
                                        alt="<?= h($modelo2) ?>">
                            </div>

                            <h6 class="text-center text-bold mb-0">
                                <?= 'Equipo: ' . h($modelo2) . '<br>Marca: ' . h($marca2) . '<br>Valor Unitario: ' . h($valorMostrado) ?>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        (function () {
            const inputBuscador = document.getElementById('buscadorEquipos');
            const cards = document.querySelectorAll('.card-equipo');
            const contador = document.getElementById('contadorResultados');
            const mensajeSinResultados = document.getElementById('mensajeSinResultados');

            function actualizarContador(totalVisible) {
                contador.textContent = 'Resultados: ' + totalVisible;
            }

            function filtrarEquipos() {
                const texto = (inputBuscador.value || '').toLowerCase().trim();
                let visibles = 0;

                cards.forEach(function(card) {
                    const contenido = (card.getAttribute('data-search') || '').toLowerCase();

                    if (contenido.indexOf(texto) !== -1) {
                        card.style.display = '';
                        visibles++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                actualizarContador(visibles);

                if (visibles === 0) {
                    mensajeSinResultados.style.display = 'block';
                } else {
                    mensajeSinResultados.style.display = 'none';
                }
            }

            actualizarContador(document.querySelectorAll('.card-equipo').length);

            inputBuscador.addEventListener('input', filtrarEquipos);
        })();
    </script>
    <?php
} else {
    echo '<div class="alert alert-info">No se encontraron equipos para esta categoría.</div>';
}
?>