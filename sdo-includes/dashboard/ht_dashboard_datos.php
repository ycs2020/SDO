<?php
include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

$anioHT = isset($_POST['anio']) ? (int)$_POST['anio'] : date('Y');
$tipoUsuarioVer = $_POST['tipoUsuarioVer'] ?? 'todos';
$nomDepartamento = $_POST['nomDepartamento'] ?? '';

$titulo1 = "Cantidad de Hojas Técnicas Realizadas en Año " . $anioHT . " por Departamento";
$titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
?>

<div class="row animate__animated animate__fadeIn justify-content-center">
    <div class="col-12 mb-3">
        <div class="pb-2 border-bottom d-flex align-items-center">
            <div class="bg-primary text-white rounded p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h5 class="text-dark font-weight-bold mb-0" style="font-size: 16px; letter-spacing: 0.5px;">
                <?php echo $titulo2; ?>
            </h5>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="table-responsive border rounded" style="border-color: #e3e6f0 !important;">
            <table class="table table-hover table-striped align-middle mb-0 table-sm" style="font-size: 13px; min-width: 1000px;">
                <thead class="bg-secondary text-white" style="background-color: #4e73df !important;">
                <tr>
                    <th scope="col" class="align-middle py-3 font-weight-bold pl-3" style="width: 220px;">Departamento</th>
                    <?php
                    for ($mes = 1; $mes < 13; $mes++) {
                        ?>
                        <th scope="col" class="text-center align-middle py-3 font-weight-bold"><?php echo substr(mesEspañol2($mes), 0, 3); ?></th>
                        <?php
                    }
                    ?>
                    <th scope="col" class="text-center align-middle py-3 font-weight-bold bg-gray-dark text-white" style="width: 120px;">Total <?php echo $anioHT; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sqlDeptoT = "";

                if ($tipoUsuarioVer == 'unico') {
                    $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento = ? AND nomDepartamento NOT LIKE '%Control Centralizado%'";
                } else {
                    $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE '%Control Centralizado%'";
                }

                $deptosProcesados = [];

                if ($stmtDepto = $conexion->prepare($sqlDeptoT)) {
                    if ($tipoUsuarioVer == 'unico') {
                        $stmtDepto->bind_param("s", $nomDepartamento);
                    }

                    $stmtDepto->execute();
                    $resDeptoT = $stmtDepto->get_result();

                    while ($rowDeptoT = $resDeptoT->fetch_assoc()) {
                        $nomDeptoOriginal = $rowDeptoT['nomDepartamento'];

                        if ($nomDeptoOriginal != 'Informática' && $nomDeptoOriginal != 'Ingeniería Operacional') {

                            // PASO 1: Reemplazo de los nombres solicitados
                            if ($nomDeptoOriginal == 'Inst. Térmicas, Climatización y Refrigeración') {
                                $nomDeptoMostrar = 'Climatización';
                            } else if ($nomDeptoOriginal == 'Instrumentación') {
                                $nomDeptoMostrar = 'Control Centralizado';
                            } else {
                                $nomDeptoMostrar = $nomDeptoOriginal;
                            }

                            $deptosProcesados[] = [
                                'id' => $rowDeptoT['idDepartamento'],
                                'nombre' => $nomDeptoMostrar
                            ];
                        }
                    }
                    $stmtDepto->close();
                }

                // PASO 2: Reordenar alfabéticamente
                usort($deptosProcesados, function($a, $b) {
                    return strcoll($a['nombre'], $b['nombre']);
                });

                $cantPorMesTotal = 0;
                $totalMesesAcumulado = array_fill(1, 12, 0);

                foreach ($deptosProcesados as $depto) {
                    $cantTotalDepto = 0;
                    ?>
                    <tr>
                        <td class="align-middle font-weight-bold text-dark pl-3"><?php echo $depto['nombre']; ?></td>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            $mesHT = sprintf('%02d', $i);
                            $fechaT = $anioHT . '-' . $mesHT;

                            $contarHojasTecnicasMesAnio = contarHojasTecnicasMesAnio($depto['id'], $fechaT);

                            $cantTotalDepto = $cantTotalDepto + $contarHojasTecnicasMesAnio;
                            $totalMesesAcumulado[$i] = $totalMesesAcumulado[$i] + $contarHojasTecnicasMesAnio;

                            $claseCelda = ($contarHojasTecnicasMesAnio < 1) ? 'text-muted font-weight-light text-md' : 'text-dark font-weight-normal';
                            $valorCelda = ($contarHojasTecnicasMesAnio < 1) ? '<span style="opacity: 0.3;">0</span>' : $contarHojasTecnicasMesAnio;
                            ?>
                            <td class="text-center align-middle <?php echo $claseCelda; ?>">
                                <?php echo $valorCelda; ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td class="text-center align-middle font-weight-bold text-primary bg-light" style="font-size: 14px;">
                            <?php echo ($cantTotalDepto < 1) ? '<span class="text-muted" style="opacity: 0.5;">0</span>' : $cantTotalDepto; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <tr class="bg-light" style="border-top: 2px solid #b7b9cc !important;">
                    <th scope="row" class="align-middle py-3 text-dark font-weight-bold pl-3" style="font-size: 14px;">TOTALES</th>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        ?>
                        <td class="text-center align-middle py-3 font-weight-bold text-dark" style="font-size: 14px;"><?php echo $totalMesesAcumulado[$i]; ?></td>
                        <?php
                        $cantPorMesTotal = $cantPorMesTotal + $totalMesesAcumulado[$i];
                    }
                    ?>
                    <td class="text-center align-middle py-3 font-weight-bolder text-white bg-primary shadow-sm" style="font-size: 15px;">
                        <?php echo $cantPorMesTotal; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12 mb-3">

        <ul class="nav nav-pills mb-3 justify-content-center custom-pills" id="pills-tab" role="tablist">
            <?php
            for ($i = 1; $i < 13; $i++) {
                $mesNow = date('m', time());
                $activeDash = ($i == $mesNow) ? 'active shadow-sm' : 'bg-light text-secondary';
                ?>
                <li class="nav-item mx-1" role="presentation">
                    <a class="nav-link font-weight-bold <?php echo $activeDash; ?>"
                            id="pills-home<?php echo $i; ?>"
                            data-toggle="pill"
                            href="#pills-homea<?php echo $i; ?><?php echo $anioHT; ?>"
                            role="tab"
                            style="border-radius: 8px; font-size: 13px;">
                        <b><?php echo mesEspañol2($i); ?></b>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <?php
            for ($i = 1; $i < 13; $i++) {
                $mesHT = sprintf('%02d', $i);
                $mesAnioTab = $anioHT . '-' . $mesHT;

                $mesNow = intval(date('m', time()));
                $activeDash2 = ($i == $mesNow) ? 'show active' : '';
                ?>
                <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?><?php echo $anioHT; ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
                    <div class="row">

                        <div class="col-12 col-xl-6 mb-4">
                            <div class="card card-purple collapsed-card card-outline shadow-sm h-100 mb-0">
                                <div class="card-header bg-gradient-purple pt-3 pb-3">
                                    <h3 class="card-title font-weight-bold text-white" style="font-size: 14px;">
                                        <i class="fas fa-briefcase mr-2"></i>CANTIDAD DE HOJAS TÉCNICAS POR TIPO DE MANTENIMIENTO
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 12px;">
                                            <thead class="bg-light text-muted">
                                            <tr>
                                                <th class="pl-3 font-weight-bold">ESPECIALIDAD</th>
                                                <th class="text-center font-weight-bold">MPI</th>
                                                <th class="text-center font-weight-bold">MRI</th>
                                                <th class="text-center font-weight-bold">MPE</th>
                                                <th class="text-center font-weight-bold">MRE</th>
                                                <th class="text-center font-weight-bold">OM</th>
                                                <th class="text-center font-weight-bold">IS</th>
                                                <th class="text-center font-weight-bold">N/A</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($deptosProcesados as $depto) {
                                                ?>
                                                <tr>
                                                    <td class="align-middle font-weight-bold text-dark pl-3"><?php echo $depto['nombre']; ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'MPI', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'MRI', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'MPE', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'MRE', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'OM', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTTipo($depto['id'], 'IS', $mesAnioTab); ?></td>
                                                    <td class="text-center align-middle"><?php echo obtieneHTSinTipo($depto['id'], $mesAnioTab); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Mapeamos los ID de los departamentos a clases de color con gradiente de AdminLTE
                        $departamentoColores = [
                            1 => 'bg-gradient-success',  // Climatización / Eq. Industriales / etc (según ID)
                            2 => 'bg-gradient-purple',   // Cambia dinámicamente según el ID asignado en BD
                            3 => 'bg-gradient-info',
                            4 => 'bg-gradient-danger',
                            5 => 'bg-gradient-orange',
                            6 => 'bg-gradient-yellow',
                            7 => 'bg-gradient-teal',
                            8 => 'bg-gradient-indigo',
                            9 => 'bg-gradient-maroon',
                            10 => 'bg-gradient-pink',
                            11 => 'bg-gradient-navy'
                        ];

                        // Clases del borde superior de la tarjeta para acompañar el diseño
                        $departamentoBordes = [
                            1 => 'card-success', 2 => 'card-purple', 3 => 'card-info', 4 => 'card-danger',
                            5 => 'card-orange', 6 => 'card-yellow', 7 => 'card-teal', 8 => 'card-indigo',
                            9 => 'card-maroon', 10 => 'card-pink', 11 => 'card-navy'
                        ];

                        foreach ($deptosProcesados as $depto) {
                            $idDeptoT3 = $depto['id'];
                            $nomDeptoT3 = $depto['nombre'];
                            $colorFondoHeader = $departamentoColores[$idDeptoT3] ?? 'bg-gradient-primary';
                            $colorBordeCard = $departamentoBordes[$idDeptoT3] ?? 'card-primary';
                            ?>
                            <div class="col-12 col-md-6 mb-3">
                                <div class="card <?php echo $colorBordeCard; ?> collapsed-card card-outline shadow-sm mb-0">
                                    <div class="card-header <?php echo $colorFondoHeader; ?> pt-3 pb-3">
                                        <h3 class="card-title font-weight-bold text-white" style="font-size: 14px;">
                                            <i class="fas fa-users mr-2"></i><?php echo $nomDeptoT3; ?>
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0" style="display: none;">
                                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 12px;">
                                            <thead class="bg-light text-muted">
                                            <tr>
                                                <th class="pl-3 font-weight-bold">PERSONAL</th>
                                                <th class="text-center font-weight-bold" style="width: 140px;">DOCUMENTOS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $totalDeptoFor = 0;
                                            $fechaInicioBusqueda = $mesAnioTab . '-01 00:00:00';
                                            $fechaTerminoBusqueda = $mesAnioTab . '-31 23:59:59';

                                            // Consulta segura de Personal
                                            $sqlPersonal = "SELECT p.idPersonal, p.nomPersonal, p.cargoPersonal, COUNT(o.idOrden) AS total
                                                    FROM personal AS p
                                                    INNER JOIN ordenes_trabajos_general AS o ON p.idPersonal = o.idCreador
                                                    WHERE o.idDepartamento = ? AND o.tipoUsuario = 'personal'
                                                    AND o.fechaOrden BETWEEN ? AND ?
                                                    GROUP BY p.idPersonal ORDER BY total DESC";

                                            if ($stmtPers = $conexion->prepare($sqlPersonal)) {
                                                $stmtPers->bind_param("iss", $idDeptoT3, $fechaInicioBusqueda, $fechaTerminoBusqueda);
                                                $stmtPers->execute();
                                                $resPersonal = $stmtPers->get_result();
                                                while ($rowPersonal = $resPersonal->fetch_assoc()) {
                                                    $totalDeptoFor = $totalDeptoFor + $rowPersonal['total'];
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle pl-3 text-dark"><?php echo $rowPersonal['nomPersonal']; ?> <span class="text-muted text-xs">(<?php echo $rowPersonal['cargoPersonal']; ?>)</span></td>
                                                        <td class="text-center align-middle font-weight-bold"><?php echo $rowPersonal['total']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                $stmtPers->close();
                                            }

                                            // Consulta segura de Jefes Técnicos
                                            $sqlJefeDeptoFor = "SELECT p.idJefe, p.nomJefe, p.cargoJefe, COUNT(o.idOrden) AS total
                                                        FROM jefes_tecnicos AS p
                                                        INNER JOIN ordenes_trabajos_general AS o ON p.idJefe = o.idCreador
                                                        WHERE o.idDepartamento = ? AND o.tipoUsuario = 'jefe tecnico'
                                                        AND o.fechaOrden BETWEEN ? AND ?
                                                        GROUP BY p.idJefe ORDER BY p.nomJefe ASC";

                                            if ($stmtJefe = $conexion->prepare($sqlJefeDeptoFor)) {
                                                $stmtJefe->bind_param("iss", $idDeptoT3, $fechaInicioBusqueda, $fechaTerminoBusqueda);
                                                $stmtJefe->execute();
                                                $resJefeDeptoFor = $stmtJefe->get_result();
                                                while ($rowJefeDeptoFor = $resJefeDeptoFor->fetch_assoc()) {
                                                    $totalDeptoFor = $totalDeptoFor + $rowJefeDeptoFor['total'];
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle pl-3 text-dark"><?php echo $rowJefeDeptoFor['nomJefe']; ?> <span class="text-muted text-xs">(<?php echo $rowJefeDeptoFor['cargoJefe']; ?>)</span></td>
                                                        <td class="text-center align-middle font-weight-bold"><?php echo $rowJefeDeptoFor['total']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                $stmtJefe->close();
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-light">
                                            <tr>
                                                <td class="text-right font-weight-bold pr-3 text-muted">Total General:</td>
                                                <td class="text-center align-middle font-weight-bold text-primary" style="font-size: 13px;"><?php echo $totalDeptoFor; ?></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

    </div>
</div>