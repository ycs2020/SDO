<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if(session_status() === PHP_SESSION_NONE) {
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

function normalizarEstado($estado)
{
    return mb_strtolower(trim((string)$estado), 'UTF-8');
}

function obtenerBadgeEstado($estado)
{
    $estadoNormalizado = normalizarEstado($estado);

    if ($estadoNormalizado === 'operativo') {
        return '<span class="badge badge-success px-3 py-1">Operativo</span>';
    }

    if ($estadoNormalizado === 'en mantención' || $estadoNormalizado === 'en mantencion') {
        return '<span class="badge badge-warning px-3 py-1">En Mantención</span>';
    }

    if ($estadoNormalizado === 'fuera de servicio') {
        return '<span class="badge badge-danger px-3 py-1">Fuera de Servicio</span>';
    }

    if ($estadoNormalizado === 'pendiente') {
        return '<span class="badge badge-secondary px-3 py-1">Pendiente</span>';
    }

    return '<span class="badge badge-info px-3 py-1">' . h(formatearVacioFilas($estado)) . '</span>';
}

function obtenerClaseBordeVencimiento($fechaUltimaMantencion)
{
    if (empty($fechaUltimaMantencion) || $fechaUltimaMantencion === '0') {
        return 'borde-vencido';
    }

    try {
        $fechaObj = new DateTime($fechaUltimaMantencion);

        $fecha3Meses = clone $fechaObj;
        $fecha3Meses->modify('+3 months');

        $fecha6Meses = clone $fechaObj;
        $fecha6Meses->modify('+6 months');

        $fechaActual = new DateTime();

        if ($fechaActual > $fecha6Meses) {
            return 'borde-vencido';
        }

        if ($fechaActual > $fecha3Meses) {
            return 'borde-proximo';
        }

        return 'borde-ok';
    } catch (Exception $e) {
        return 'borde-vencido';
    }
}



$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;

if ($idCarpeta <= 0) {
    echo '<div class="alert alert-warning">Categoría no válida.</div>';
    exit;
}

$sqlBuscarUcas = "
    SELECT 
        id,
        nombreEquipo,
        modelo,
        marca,
        estadoEquipo,
        recinto,
        pma,
        piso,
        tipomenu
    FROM equipamiento_hbqp
    WHERE idCategoria = ?
      AND nombreEquipo LIKE '%UCA-%'
    ORDER BY piso ASC, nombreEquipo ASC, CAST(pma AS UNSIGNED) ASC
";

$stmt = mysqli_prepare($conexion, $sqlBuscarUcas);

if (!$stmt) {
    echo '<div class="alert alert-danger">Error al preparar la consulta.</div>';
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idCarpeta);
mysqli_stmt_execute($stmt);
$resBuscarUcas = mysqli_stmt_get_result($stmt);

if (!$resBuscarUcas) {
    echo '<div class="alert alert-danger">Error al obtener los equipos.</div>';
    exit;
}

$countBuscarUcas = mysqli_num_rows($resBuscarUcas);

if ($countBuscarUcas > 0) {
    ?>
    <style>
        .filtro-equipos-box {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .card-equipo-wrap {
            display: flex;
        }

        .card-equipo-custom {
            width: 100%;
            min-height: 100%;
            border-radius: 12px;
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }

        .card-equipo-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .card-equipo-custom .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.25rem;
        }

        .borde-ok {
            border-top: 4px solid #28a745 !important;
        }

        .borde-proximo {
            border-top: 4px solid #ffc107 !important;
        }

        .borde-vencido {
            border-top: 4px solid #dc3545 !important;
        }

        .titulo-equipo {
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .info-label {
            display: block;
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .info-valor {
            font-weight: 700;
            word-break: break-word;
        }

        .bloque-imagen-equipo {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .img-equipo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .acciones-card {
            margin-top: auto;
            padding-top: 10px;
        }

        .btn-detalle-equipo,
        .btn-mpi-equipo,
        .btn-ver-ot-uca,
        .btn-ver-check-uca{
            border-radius: 8px;
        }

        .texto-mantencion {
            min-height: 50px;
        }
    </style>

    <div class="container-fluid">
        <h5 class="text-center font-weight-bold">UCAS</h5>

        <div class="filtro-equipos-box">
            <div class="row">
                <div class="col-lg-3 col-md-12 mb-2">
                    <label class="font-weight-bold mb-1">Buscar equipo</label>
                    <input
                            type="text"
                            id="buscadorEquipos"
                            class="form-control"
                            placeholder="Nombre, marca, modelo, recinto, PMA...">
                </div>

                <div class="col-lg-2 col-md-6 mb-2">
                    <label class="font-weight-bold mb-1">Filtrar por piso</label>
                    <select id="filtroPiso" class="form-control">
                        <option value="">Todos los pisos</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6 mb-2">
                    <label class="font-weight-bold mb-1">Filtrar por estado</label>
                    <select id="filtroEstado" class="form-control">
                        <option value="">Todos los estados</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-12 mb-2">
                    <label class="font-weight-bold mb-1">Ordenar por Próx. Mantención</label>
                    <select id="ordenarFecha" class="form-control">
                        <option value="default">Por defecto (Piso/Nombre)</option>
                        <option value="asc">Más cercana primero</option>
                        <option value="desc">Más lejana primero</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-12 mb-2 d-flex align-items-end">
                    <button type="button" id="btnLimpiarFiltros" class="btn btn-secondary btn-block">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </div>

            <div class="text-center mt-2">
                Equipos visibles: <span id="contadorEquipos"><?php echo (int)$countBuscarUcas; ?></span>
            </div>
        </div>

        <div id="mensajeSinResultados" class="alert alert-warning text-center" style="display:none;">
            No se encontraron equipos con los filtros aplicados.
        </div>

        <div class="row" id="contenedorUCAS">
            <?php
            while ($rowBuscarUcas = mysqli_fetch_assoc($resBuscarUcas)) {
                $idEquipoUcas   = (int)$rowBuscarUcas['id'];
                $nombreEquipo   = formatearVacioFilas($rowBuscarUcas['nombreEquipo']);
                $modeloUcas     = formatearVacioFilas($rowBuscarUcas['modelo']);
                $marcaUcas      = formatearVacioFilas($rowBuscarUcas['marca']);
                $estadoUcas     = formatearVacioFilas($rowBuscarUcas['estadoEquipo']);
                $recintoUcas    = formatearVacioFilas($rowBuscarUcas['recinto']);
                $pmaUcas        = formatearVacioFilas($rowBuscarUcas['pma']);
                $pisoUcas       = formatearVacioFilas($rowBuscarUcas['piso']);
                $tipomenu       = formatearVacioFilas($rowBuscarUcas['tipomenu']);

                $modeloMayus    = mb_convert_case($modeloUcas, MB_CASE_UPPER, 'UTF-8');
                $marcaMayus     = mb_convert_case($marcaUcas, MB_CASE_UPPER, 'UTF-8');

                $obtenerUltimoMantenimientoUCA = obtenerUltimoMantenimientoUCA($idEquipoUcas);

                $colorUltMant  = 'inherit';
                $colorProxima  = 'inherit';
                $fechaUltMant  = 'Sin mantención registrada';
                $fechaProxMant = 'Sin registro';
                $colorSpan     = 'text-muted';

                // Variable para el ordenamiento (Usamos un timestamp o string YYYYMMDD)
                $fechaOrdenValue = '99999999';

                if (!empty($obtenerUltimoMantenimientoUCA) && $obtenerUltimoMantenimientoUCA !== '0') {
                    try {
                        $fechaObj = new DateTime($obtenerUltimoMantenimientoUCA);
                        $fechaUltMant = $fechaObj->format('d-m-Y');

                        $fecha3Meses = clone $fechaObj;
                        $fecha3Meses->modify('+3 months');

                        $fecha6Meses = clone $fechaObj;
                        $fecha6Meses->modify('+6 months');

                        $fechaProxMant = $fecha6Meses->format('d-m-Y');
                        $fechaOrdenValue = $fecha6Meses->format('Ymd'); // Formato para ordenar

                        $fechaActual = new DateTime();

                        if ($fechaActual > $fecha6Meses) {
                            $colorProxima = 'red';
                            $colorSpan = 'text-danger';
                        } elseif ($fechaActual > $fecha3Meses) {
                            $colorProxima = 'orange';
                            $colorSpan = 'text-warning';
                        } else {
                            $colorProxima = 'green';
                            $colorSpan = 'text-success';
                        }
                    } catch (Exception $e) {
                        $fechaUltMant = 'Fecha inválida';
                        $fechaProxMant = 'No calculable';
                        $colorUltMant = 'red';
                        $colorProxima = 'red';
                        $colorSpan = 'text-danger';
                    }
                } else {
                    $colorUltMant = 'red';
                    $colorProxima = 'red';
                    $colorSpan = 'text-danger';
                }

                $rutaImagenUCAS = getRutaImagenEquipamiento($modeloMayus, $marcaMayus);
                $claseBorde = obtenerClaseBordeVencimiento($obtenerUltimoMantenimientoUCA);

                $estadoNormalizado = normalizarEstado($estadoUcas);

                $textoBusqueda = implode(' ', [
                    $nombreEquipo,
                    $marcaUcas,
                    $modeloUcas,
                    $recintoUcas,
                    $pmaUcas,
                    $pisoUcas,
                    $estadoUcas
                ]);
                ?>
                <div
                        class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4 card-equipo-wrap card-equipo"
                        data-search="<?php echo h(mb_strtolower($textoBusqueda, 'UTF-8')); ?>"
                        data-piso="<?php echo h($pisoUcas); ?>"
                        data-estado="<?php echo h($estadoNormalizado); ?>"
                        data-proxima="<?php echo $fechaOrdenValue; ?>">

                    <div class="card card-primary card-outline card-equipo-custom <?php echo h($claseBorde); ?>">
                        <div class="card-body">

                            <div>
                                <div class="bloque-imagen-equipo">
                                    <img
                                            class="img-fluid img-equipo"
                                            src="<?php echo h($rutaImagenUCAS); ?>"
                                            alt="<?php echo h($modeloUcas); ?>">
                                </div>

                                <h5 class="titulo-equipo mb-3">
                                    <b>Equipo:</b>&nbsp;<?php echo h($nombreEquipo); ?>
                                </h5>

                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <span class="info-label">Marca</span>
                                        <div class="info-valor"><?php echo h($marcaUcas); ?></div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <span class="info-label">Modelo</span>
                                        <div class="info-valor"><?php echo h($modeloUcas); ?></div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <span class="info-label">Recinto</span>
                                        <div class="info-valor"><?php echo h($recintoUcas); ?></div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <span class="info-label">PMA</span>
                                        <div class="info-valor"><?php echo h($pmaUcas); ?></div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <span class="info-label">Piso</span>
                                        <div class="info-valor"><?php echo h($pisoUcas); ?></div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <span class="info-label">Estado</span>
                                        <div><?php echo obtenerBadgeEstado($estadoUcas); ?></div>
                                    </div>
                                </div>

                                <div class="row text-center mt-2">
                                    <div class="col-6 mb-3 texto-mantencion">
                                        <span class="info-label">&Uacute;ltima Mantenci&oacute;n</span>
                                        <div class="info-valor" style="color: <?php echo h($colorUltMant); ?>;">
                                            <?php echo h($fechaUltMant); ?>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-3 texto-mantencion">
                                        <span class="info-label <?php echo h($colorSpan); ?>">Pr&oacute;xima Mantenci&oacute;n</span>
                                        <div class="info-valor" style="color: <?php echo h($colorProxima); ?>;">
                                            <?php echo h($fechaProxMant); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="acciones-card">
                                <div class="row">
                                    <div class="col-6 pr-1">
                                        <button
                                                class="btn btn-info btn-block btn-detalle-equipo"
                                                type="button"
                                                data-idequipo="<?php echo $idEquipoUcas; ?>"
                                                data-nomequipo="<?php echo h($nombreEquipo); ?>"
                                                data-menu="<?php echo h($tipomenu); ?>">
                                            <i class="fas fa-eye"></i> Ver detalle
                                        </button>
                                    </div>

                                    <div class="col-6 pl-1">
                                        <button
                                                class="btn bg-purple btn-block btn-mpi-equipo"
                                                type="button"
                                                data-iduca="<?php echo $idEquipoUcas; ?>"
                                                data-nomuca="<?php echo h($nombreEquipo); ?>">
                                            <i class="fas fa-plus"></i> Agregar MPI
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">

                                    <div class="col-6 pr-1">
                                        <button
                                                class="btn bg-warning btn-block btn-ver-ot-uca"
                                                type="button"
                                                data-iduca="<?php echo $idEquipoUcas; ?>">
                                            <i class="fas fa-eye"></i> Trabajos
                                        </button>
                                    </div>

                                    <div class="col-6 pl-1">
                                        <button
                                                class="btn bg-success btn-block btn-ver-check-uca"
                                                type="button"
                                                data-iduca="<?php echo $idEquipoUcas; ?>">
                                            <i class="fas fa-eye"></i> Check-List
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="modal fade" id="modalAgregarMPIUCA" tabindex="-1" role="dialog" aria-labelledby="modalAgregarMPIUCALabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header bg-purple">
                    <h5 class="modal-title text-white font-weight-bold" id="modalAgregarMPIUCALabel">
                        Agregar MPI a UCA
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formAgregarMPIUCA">
                    <div class="modal-body">
                        <input type="hidden" name="idEquipo" id="idEquipoMPIUCA">
                        <input type="hidden" name="nombreEquipo" id="nombreEquipoMPIUCA">

                        <div class="form-group">
                            <label for="tituloTareaMPIUCA" class="font-weight-bold">
                                Título de Tarea: <span class="text-danger">*</span>
                            </label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="tituloTareaMPIUCA"
                                    name="tituloTareaP"
                                    readonly>
                        </div>

                        <div class="form-group">
                            <label for="turnoAsignadoMPIUCA" class="font-weight-bold">
                                Turno Asignado <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="turnoAsignadoMPIUCA" name="turnoAsignado" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Personal Diurno</option>
                                <option value="2">Personal Nocturno</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="funcionarioMPIUCA" class="font-weight-bold">
                                Seleccione Funcionario <span class="text-danger">*</span>
                            </label>
                            <select class="custom-select" id="funcionarioMPIUCA" name="funcionarioMPIUCA" required>
                                <option value="">Seleccione...</option>

                                <?php
                                $sqlListadoFuncionarios = "SELECT jt.nomJefe AS nomPersonal 
                                           FROM jefes_tecnicos jt 
                                           WHERE (nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') AND nomJefe NOT LIKE('%Alejandro Cantillano%')) 
                                           AND (idDepartamento = 1 AND jt.userActivado = 1)
                                           AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                           UNION 
                                           SELECT p.nomPersonal AS nomPersonal
                                           FROM personal p 
                                           WHERE departamentoPersonal = 1
                                           AND activoPersonal = 1 
                                           AND (nomPersonal NOT LIKE('%Técnico Pruebas%') AND nomPersonal NOT LIKE('%Cynthia Latorre%'))
                                           ORDER BY nomPersonal";
                                $resListadoFuncionarios = mysqli_query($conexion, $sqlListadoFuncionarios);

                                if ($resListadoFuncionarios && mysqli_num_rows($resListadoFuncionarios) > 0) {
                                    while ($rowListadoFuncionarios = mysqli_fetch_assoc($resListadoFuncionarios)) {
                                        $nomPersonal = $rowListadoFuncionarios['nomPersonal'];
                                        ?>
                                        <option value="<?php echo h($nomPersonal); ?>"><?php echo h($nomPersonal); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fechaInicioMPIUCA" class="font-weight-bold">
                                Fecha Inicio - Planificación: <span class="text-danger">*</span>
                            </label>
                            <input
                                    type="date"
                                    class="form-control"
                                    id="fechaInicioMPIUCA"
                                    name="inicioPlanTareaP"
                                    required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo h($_SESSION['nomUsuario']); ?>">
                        <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                        <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="1">

                        <button type="submit" class="btn btn-primary" id="btnGuardarMPIUCA">
                            <i class="fas fa-save"></i>&nbsp;&nbsp;Agregar
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modalVerTrabajosUCAS" tabindex="-1" role="dialog" aria-labelledby="modalVerTrabajosUCASLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white font-weight-bold" id="modalVerTrabajosUCASLabel">
                        Trabajos Programados
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="contenedorTrabajosUCAS">
                        <div class="text-center text-muted py-3">
                            Seleccione una UCA para visualizar sus trabajos.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVerCheckListUCAS" tabindex="-1" role="dialog" aria-labelledby="modalVerCheckListUCASLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white font-weight-bold" id="modalVerCheckListUCASLabel">
                        Check-List de Mantención
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="contenedorCheckListUCAS">
                        <div class="text-center text-muted py-3">
                            Seleccione una UCA para visualizar sus checklist.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            const inputBuscador = document.getElementById('buscadorEquipos');
            const filtroPiso = document.getElementById('filtroPiso');
            const filtroEstado = document.getElementById('filtroEstado');
            const selectorOrden = document.getElementById('ordenarFecha');
            const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
            const contador = document.getElementById('contadorEquipos');
            const mensajeSinResultados = document.getElementById('mensajeSinResultados');
            const contenedorCards = document.getElementById('contenedorUCAS');
            const cards = document.querySelectorAll('#contenedorUCAS .card-equipo');

            if (!inputBuscador || !filtroPiso || !filtroEstado || !btnLimpiarFiltros || !contador || !mensajeSinResultados) {
                return;
            }

            function poblarFiltro(select, valores, textoDefault) {
                const valoresUnicos = Array.from(new Set(valores))
                    .filter(function(valor) {
                        return (valor || '').trim() !== '';
                    })
                    .sort(function(a, b) {
                        return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
                    });

                select.innerHTML = '<option value="">' + textoDefault + '</option>';

                valoresUnicos.forEach(function(valor) {
                    const option = document.createElement('option');
                    option.value = valor;
                    option.textContent = valor;
                    select.appendChild(option);
                });
            }

            function actualizarContador(totalVisible) {
                contador.textContent = totalVisible;
            }

            function filtrarYOrdenarEquipos() {
                const texto = (inputBuscador.value || '').toLowerCase().trim();
                const piso = (filtroPiso.value || '').toLowerCase().trim();
                const estado = (filtroEstado.value || '').toLowerCase().trim();
                const orden = selectorOrden.value;

                let visibles = [];
                let invisibles = [];

                // 1. Filtrado
                cards.forEach(function(card) {
                    const contenido = (card.getAttribute('data-search') || '').toLowerCase();
                    const pisoCard = (card.getAttribute('data-piso') || '').toLowerCase();
                    const estadoCard = (card.getAttribute('data-estado') || '').toLowerCase();

                    const coincideTexto = texto === '' || contenido.indexOf(texto) !== -1;
                    const coincidePiso = piso === '' || pisoCard === piso;
                    const coincideEstado = estado === '' || estadoCard === estado;

                    if (coincideTexto && coincidePiso && coincideEstado) {
                        card.style.display = '';
                        visibles.push(card);
                    } else {
                        card.style.display = 'none';
                        invisibles.push(card);
                    }
                });

                // 2. Ordenamiento (Solo de los visibles si se desea, o de todos para mantener coherencia)
                if (orden !== 'default') {
                    visibles.sort((a, b) => {
                        const valA = parseInt(a.getAttribute('data-proxima')) || 99999999;
                        const valB = parseInt(b.getAttribute('data-proxima')) || 99999999;

                        return (orden === 'asc') ? (valA - valB) : (valB - valA);
                    });

                    // Re-inyectar en el DOM
                    visibles.forEach(card => contenedorCards.appendChild(card));
                    invisibles.forEach(card => contenedorCards.appendChild(card));
                } else {
                    // Si es default, podrías recargar o dejar el orden original de PHP
                    // Para efectos prácticos, si no es default, el append ya cambió el orden.
                }

                actualizarContador(visibles.length);
                mensajeSinResultados.style.display = visibles.length === 0 ? 'block' : 'none';
            }

            const pisos = Array.from(cards).map(function(card) {
                return card.getAttribute('data-piso') || '';
            });

            const estados = Array.from(cards).map(function(card) {
                return card.getAttribute('data-estado') || '';
            });

            poblarFiltro(filtroPiso, pisos, 'Todos los pisos');
            poblarFiltro(filtroEstado, estados, 'Todos los estados');

            inputBuscador.addEventListener('input', filtrarYOrdenarEquipos);
            filtroPiso.addEventListener('change', filtrarYOrdenarEquipos);
            filtroEstado.addEventListener('change', filtrarYOrdenarEquipos);
            selectorOrden.addEventListener('change', filtrarYOrdenarEquipos);

            btnLimpiarFiltros.addEventListener('click', function() {
                inputBuscador.value = '';
                filtroPiso.value = '';
                filtroEstado.value = '';
                selectorOrden.value = 'default';
                // Para restaurar orden original PHP lo ideal sería recargar o guardar el orden inicial
                // Aquí simplemente aplicamos filtros vacíos.
                filtrarYOrdenarEquipos();
            });

            actualizarContador(cards.length);

            $(document).off('click', '.btn-detalle-equipo').on('click', '.btn-detalle-equipo', function () {
                const idEquipo = $(this).data('idequipo') || 0;
                const tipomenu  = $(this).data('menu') || 0;

                if (!idEquipo || parseInt(idEquipo, 10) <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Equipo no válido',
                        text: 'No fue posible identificar el equipo seleccionado.'
                    });
                    return;
                }

                const url = (tipomenu === 'nuevo')
                    ? 'equipo_menuNuevo.php'
                    : 'equipo_menuAntiguo.php';

                const target = (tipomenu === 'nuevo')
                    ? '_self'
                    : '_blank';

                const $form = $('<form>', {
                    action: url,
                    method: 'POST',
                    target: target,
                    style: 'display:none;'
                });

                $form.append($('<input>', {
                    type: 'hidden',
                    name: 'idEquipo',
                    value: idEquipo
                }));

                $('body').append($form);
                $form.trigger('submit');
            });

            $(document).off('click', '.btn-mpi-equipo').on('click', '.btn-mpi-equipo', function () {
                const idEquipo = $(this).data('iduca') || 0;
                const nomEquipo = $(this).data('nomuca') || '';

                if (!idEquipo || parseInt(idEquipo, 10) <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Equipo no válido',
                        text: 'No fue posible identificar el equipo seleccionado.'
                    });
                    return;
                }

                const tituloAutomatico = 'MANTENIMIENTO PREVENTIVO INTERNO UTILIZANDO PAUTA CHECK LIST DE ' + nomEquipo;

                $('#idEquipoMPIUCA').val(idEquipo);
                $('#nombreEquipoMPIUCA').val(nomEquipo);
                $('#tituloTareaMPIUCA').val(tituloAutomatico);
                $('#turnoAsignadoMPIUCA').val('');
                $('#funcionarioMPIUCA').val('');
                $('#fechaInicioMPIUCA').val('');

                $('#modalAgregarMPIUCA').modal('show');
            });

            $('#formAgregarMPIUCA').off('submit').on('submit', function (e) {
                e.preventDefault();

                const titulo = $('#tituloTareaMPIUCA').val().trim();
                const turno = $('#turnoAsignadoMPIUCA').val().trim();
                const funcionario = $('#funcionarioMPIUCA').val().trim();
                const fechaInicio = $('#fechaInicioMPIUCA').val().trim();

                if (titulo === '' || turno === '' || funcionario === '' || fechaInicio === '') {
                    Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'Complete todos los campos obligatorios.' });
                    return;
                }

                const $btn = $('#btnGuardarMPIUCA');
                const textoOriginal = $btn.html();

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                $.ajax({
                    url: '../sdo-funciones/planificacion_mpi_uca_nuevo.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response && response.status === 'success') {
                            $('#modalAgregarMPIUCA').modal('hide');
                            Swal.fire({ icon: 'success', title: 'MPI creada', text: response.message + ' Folio generado: ' + response.folioTG });
                            $('#formAgregarMPIUCA')[0].reset();
                        } else {
                            Swal.fire({ icon: 'error', title: 'No se pudo guardar', text: (response && response.message) ? response.message : 'Ocurrió un error.' });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Error de servidor', text: 'Problema al procesar solicitud.' });
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(textoOriginal);
                    }
                });
            });

            $(document).off('click', '.btn-ver-ot-uca').on('click', '.btn-ver-ot-uca', function () {
                const idEquipo = $(this).data('iduca') || 0;
                if (!idEquipo || parseInt(idEquipo, 10) <= 0) return;

                $('#contenedorTrabajosUCAS').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br><span>Cargando...</span></div>');
                $('#modalVerTrabajosUCAS').modal('show');

                $.ajax({
                    url: '../sdo-includes/equipamiento/trabajos_ucas_cargar.php',
                    type: 'POST',
                    data: { idEquipo: idEquipo },
                    success: function (response) { $('#contenedorTrabajosUCAS').html(response); }
                });
            });

            $(document).off('click', '.btn-ver-check-uca').on('click', '.btn-ver-check-uca', function () {
                const idEquipo = $(this).data('iduca') || 0;
                if (!idEquipo || parseInt(idEquipo, 10) <= 0) return;

                $('#contenedorCheckListUCAS').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br><span>Cargando...</span></div>');
                $('#modalVerCheckListUCAS').modal('show');

                $.ajax({
                    url: '../sdo-includes/equipamiento/checklist_ucas_cargar.php',
                    type: 'POST',
                    data: { idEquipo: idEquipo },
                    success: function (response) { $('#contenedorCheckListUCAS').html(response); }
                });
            });
        })();
    </script>
    <?php
} else {
    echo '<div class="alert alert-info">No se encontraron equipos para esta categoría.</div>';
}

if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}
?>