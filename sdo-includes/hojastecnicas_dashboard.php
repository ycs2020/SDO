<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioHT = date('Y', time());
                $sqlAniosHT = "SELECT DISTINCT YEAR(fechaOrden) AS anio FROM ordenes_trabajos_general ORDER BY fechaOrden ASC;";
                $resAniosHT = mysqli_query($conexion, $sqlAniosHT);
                while ($rowHT = $resAniosHT->fetch_assoc()) {
                    $activeClass = ($rowHT['anio'] == $nowAnioHT) ? 'active' : '';
                    echo '<li class="nav-item"><a class="nav-link ' . $activeClass . '" href="#tabHT' . $rowHT['anio'] . '" data-toggle="tab">' . $rowHT['anio'] . '</a></li>';
                }
                ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioHT = date('Y', time());

                $sqlAniosHT = "SELECT DISTINCT YEAR(fechaOrden) AS anio FROM ordenes_trabajos_general ORDER BY fechaOrden ASC;";
                $resAniosHT = mysqli_query($conexion, $sqlAniosHT);
                while ($rowHT = $resAniosHT->fetch_assoc()) {
                    $anioHT = $rowHT['anio'];

                    $anioAnteriorHT = $anioHT - 1;
                    $nowMesHT = date('m', time());
                    if ($anioHT == $nowAnioHT) {
                       $tabPane = 'active';
                    } else {
                        $tabPane = '';
                    }
                    ?>

                    <div class="tab-pane <?php echo $tabPane; ?>" id="tabHT<?php echo $rowHT['anio']; ?>">
                        <div class="col-12">
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <?php
                                        date_default_timezone_set('America/Santiago');
                                        $nowHT = date('Y-m', time());
                                        $anioT = date('Y', time());

                                        $titulo1 = "Cantidad de Hojas Técnicas Realizadas en Año " . $anioHT . " por Departamento";

                                        $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                        ?>
                                        <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="bg-success">
                                                <tr>
                                                    <th scope="col">Departamento</th>
                                                    <?php
                                                    for ($mes = 1; $mes < 13; $mes++) {
                                                        ?>
                                                        <th scope="col" class="text-center"><?php echo mesEspañol2($mes); ?></th>
                                                        <?php
                                                    }
                                                    ?>
                                                    <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anioHT; ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if ($tipoUsuarioVer == 'unico') {
                                                    $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                } else if ($tipoUsuarioVer == 'todos') {
                                                    $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                }
                                                $resDeptoT = mysqli_query($conexion, $sqlDeptoT);

                                                $cantPorDepto = 0;

                                                $cantPorMesTotal = 0;

                                                $arrayCantTotal = array();

                                                $totalMes1 = 0;
                                                $totalMes2 = 0;
                                                $totalMes3 = 0;
                                                $totalMes4 = 0;
                                                $totalMes5 = 0;
                                                $totalMes6 = 0;
                                                $totalMes7 = 0;
                                                $totalMes8 = 0;
                                                $totalMes9 = 0;
                                                $totalMes10 = 0;
                                                $totalMes11 = 0;
                                                $totalMes12 = 0;
                                                while ($rowDeptoT = $resDeptoT->fetch_assoc()) {
                                                    $cantTotalFinal = 0;
                                                    $idDepartamentoT = $rowDeptoT['idDepartamento'];
                                                    $nomDepartamentoT = $rowDeptoT['nomDepartamento'];
                                                    $contarHojasTecnicasAnio = contarHojasTecnicasAnio($idDepartamentoT, $anioHT);
                                                    if ($nomDepartamentoT == 'Informática' || $nomDepartamentoT == 'Ingeniería Operacional') {
                                                    } else {
                                                        $cantTotalDepto = 0;
                                                        ?>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php echo $nomDepartamentoT; ?>
                                                            </th>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                ?>
                                                                <td class="text-center">
                                                                    <?php
                                                                    $mesHT = '';
                                                                    if ($i < 13) {
                                                                        $mesHT = '0' . $i;
                                                                    }

                                                                    $fechaT = $anioHT . '-' . $mesHT;

                                                                    $contarHojasTecnicasMesAnio = contarHojasTecnicasMesAnio($idDepartamentoT, $fechaT);

                                                                    $cantTotalDepto = $cantTotalDepto + $contarHojasTecnicasMesAnio;

                                                                    if ($contarHojasTecnicasMesAnio < 1) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo $contarHojasTecnicasMesAnio;
                                                                    }


                                                                    ?>
                                                                </td>
                                                                <?php

                                                                ${"totalMes$i"} = ${"totalMes$i"} + $contarHojasTecnicasMesAnio;


                                                                $cantTotalFinal = $cantTotalFinal + $cantTotalDepto;
                                                            }
                                                            ?>
                                                            <td class="text-center text-bold">
                                                                <?php echo $cantTotalDepto; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                for ($i = 1; $i <= 12; $i++) {
                                                    array_push($arrayCantTotal, ${"totalMes$i"});
                                                }

                                                ?>
                                                <tr class="bg-info">
                                                    <th scope="row">Totales</th>

                                                    <?php
                                                    for ($i = 0; $i < 12; $i++) {
                                                        ?>
                                                        <td class="text-center text-bold"><?php echo $arrayCantTotal[$i] ?></td>
                                                        <?php
                                                        $cantPorMesTotal = $cantPorMesTotal + $arrayCantTotal[$i];
                                                    }
                                                    ?>

                                                    <td class="text-center text-bold"><?php echo $cantPorMesTotal ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                <?php
                                $activeDash = '';
                                for ($i = 1; $i < 13; $i++) {

                                    $mesNow = date('m', time());
                                    if ($i == $mesNow) {
                                        $activeDash = 'active';
                                    } else {
                                        $activeDash = '';
                                    }
                                    ?>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-homea<?php echo $i; ?><?php echo $anioHT ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <?php
                                $activeDash2 = '';
                                for ($i = 1; $i < 13; $i++) {
                                    $mesAnioTab = date("$anioHT", time());
                                    if ($i < 10) {
                                        $mesAnioTab = $mesAnioTab . '-0' . $i;
                                    } else {
                                        $mesAnioTab = $mesAnioTab . '-' . $i;
                                    }
                                    $mesNow = date('m', time());
                                    $mesNow = ltrim(date('m'), '0');

                                    $mesEspañol2Dash = mesEspañol2($mesNow);
                                    if ($i == $mesNow) {
                                        $activeDash2 = 'show active';
                                    } else {
                                        $activeDash2 = '';
                                    }
                                    ?>
                                    <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?><?php echo $anioHT ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
                                        <div class="row">
                                            <div class="col-12 col-md-6 h-100">
                                                <div class="card card-purple collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><i class="fas fa-briefcase"></i>&nbsp;&nbsp;<b>Cantidad de Hojas Técnicas por Tipo de Mantenimiento</b></h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-hover">
                                                            <thead style="font-size: 12px">
                                                            <tr>
                                                                <th class="text-center">ESPECIALIDAD</th>
                                                                <th class="text-center">MPI</th>
                                                                <th class="text-center">MRI</th>
                                                                <th class="text-center">MPE</th>
                                                                <th class="text-center">MRE</th>
                                                                <th class="text-center">OM</th>
                                                                <th class="text-center">IS</th>
                                                                <th class="text-center">NO APLICA</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $sqlDeptoT2 = ($tipoUsuarioVer == 'unico') ?
                                                                "SELECT * FROM departamentos WHERE nomDepartamento = '$nomDepartamento' AND nomDepartamento NOT LIKE('%Control Centralizado%') ORDER BY nomDepartamento ASC" :
                                                                "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                            $resDeptoT2 = mysqli_query($conexion, $sqlDeptoT2);
                                                            while ($rowDeptoT2 = $resDeptoT2->fetch_assoc()) {
                                                                $nomDeptoT2 = $rowDeptoT2['nomDepartamento'];
                                                                $idDeptoT2 = $rowDeptoT2['idDepartamento'];
                                                                if (!in_array($nomDeptoT2, ['Informática', 'Ingeniería Operacional'])) {
                                                                    echo "<tr>
                                        <td class='text-center'>{$nomDeptoT2}</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'MPI', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'MRI', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'MPE', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'MRE', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'OM', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTTipo($idDeptoT2, 'IS', $mesAnioTab) . "</td>
                                        <td class='text-center'>" . obtieneHTSinTipo($idDeptoT2, $mesAnioTab) . "</td>
                                    </tr>";
                                                                }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $sqlDeptoT3 = ($tipoUsuarioVer == 'unico') ?
                                                "SELECT * FROM departamentos WHERE nomDepartamento = '$nomDepartamento' AND nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC" :
                                                "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE 'Control Centralizado' ORDER BY nomDepartamento ASC";
                                            $resDeptoT3 = mysqli_query($conexion, $sqlDeptoT3);

                                            $departamentoColores = [
                                                1 => 'card-success', 2 => 'card-purple', 3 => 'card-info', 4 => 'card-danger',
                                                5 => 'card-orange', 6 => 'card-yellow', 7 => 'card-success', 8 => 'card-purple',
                                                9 => 'card-info', 10 => 'card-danger', 11 => 'card-orange'
                                            ];

                                            while ($rowDeptoT3 = $resDeptoT3->fetch_assoc()) {
                                                $idDeptoT3 = $rowDeptoT3['idDepartamento'];
                                                $nomDeptoT3 = $rowDeptoT3['nomDepartamento'];
                                                $colorTipoDepartamento = $departamentoColores[$idDeptoT3] ?? '';

                                                echo "<div class='col-12 col-md-6 h-100'>
                <div class='card {$colorTipoDepartamento} collapsed-card'>
                    <div class='card-header'>
                        <h3 class='card-title'>&nbsp;&nbsp;<b>{$nomDeptoT3}</b></h3>
                        <div class='card-tools'>
                            <button type='button' class='btn btn-tool' data-card-widget='collapse'>
                                <i class='fas fa-plus'></i>
                            </button>
                        </div>
                    </div>
                    <div class='card-body'>
                        <table class='table table-hover'>
                            <thead style='font-size: 12px'>
                                <tr>
                                    <th class='text-center'>PERSONAL</th>
                                    <th class='text-center'>CANTIDAD DE DOCUMENTOS</th>
                                </tr>
                            </thead>
                            <tbody style='font-size: 12px'>";

                                                $sqlPersonal = "SELECT p.idPersonal, p.nomPersonal, p.cargoPersonal, COUNT(o.idOrden) AS total
                            FROM personal AS p
                            INNER JOIN ordenes_trabajos_general AS o ON p.idPersonal = o.idCreador
                            WHERE o.idDepartamento = $idDeptoT3 AND o.tipoUsuario = 'personal'
                            AND o.fechaOrden BETWEEN '$mesAnioTab-01 00:00:00' AND '$mesAnioTab-31 23:59:59'
                            GROUP BY p.idPersonal ORDER BY total DESC";
                                                $resPersonal = mysqli_query($conexion, $sqlPersonal);
                                                $totalDeptoFor = 0;
                                                while ($rowPersonal = $resPersonal->fetch_assoc()) {
                                                    $totalDeptoFor += $rowPersonal['total'];
                                                    echo "<tr>
                    <td class='text-center'>{$rowPersonal['nomPersonal']} ({$rowPersonal['cargoPersonal']})</td>
                    <td class='text-center'>{$rowPersonal['total']}</td>
                </tr>";
                                                }

                                                $sqlJefeDeptoFor = "SELECT p.idJefe, p.nomJefe, p.cargoJefe, COUNT(o.idOrden) AS total
                                FROM jefes_tecnicos AS p
                                INNER JOIN ordenes_trabajos_general AS o ON p.idJefe = o.idCreador
                                WHERE o.idDepartamento = $idDeptoT3 AND o.tipoUsuario = 'jefe tecnico'
                                AND o.fechaOrden BETWEEN '$mesAnioTab-01 00:00:00' AND '$mesAnioTab-31 23:59:59'
                                GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                                $resJefeDeptoFor = mysqli_query($conexion, $sqlJefeDeptoFor);
                                                while ($rowJefeDeptoFor = $resJefeDeptoFor->fetch_assoc()) {
                                                    $totalDeptoFor += $rowJefeDeptoFor['total'];
                                                    echo "<tr>
                    <td class='text-center'>{$rowJefeDeptoFor['nomJefe']} ({$rowJefeDeptoFor['cargoJefe']})</td>
                    <td class='text-center'>{$rowJefeDeptoFor['total']}</td>
                </tr>";
                                                }

                                                echo "</tbody>
                            <tfoot>
                                <tr>
                                    <td class='text-center'>Total</td>
                                    <td class='text-center'>{$totalDeptoFor}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>";
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
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

