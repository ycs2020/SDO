<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());
                $sqlAnios = "SELECT DISTINCT YEAR(fechaTG) AS anio FROM trabajos_general ORDER BY fechaTG ASC;";
                $resAnios = mysqli_query($conexion, $sqlAnios);
                while ($row = $resAnios->fetch_assoc()) {
                    if ($row['anio'] == $nowAnio) {
                        ?>
                        <li class="nav-item"><a class="nav-link active" href="#tabOT<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabOT<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());

                $sqlAnios = "SELECT DISTINCT YEAR(fechaTG) AS anio FROM trabajos_general ORDER BY fechaTG ASC;";
                $resAnios = mysqli_query($conexion, $sqlAnios);

                while ($row = $resAnios->fetch_assoc()) {
                    $anio = $row['anio'];

                    $anioAnterior = $anio - 1;
                    $nowMes = date('m', time());
                    if ($anio == $nowAnio) {
                        ?>
                        <div class="tab-pane active" id="tabOT<?php echo $row['anio']; ?>">
                            <div class="col-12">
                                <br>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <?php

                                            date_default_timezone_set('America/Santiago');
                                            $nowT = date('Y-m', time());
                                            $anioT = date('Y', time());

                                            $titulo1 = "Cantidad de Ordenes de Trabajo Terminadas en Año " . $anio . " por Departamento";

                                            $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                            ?>
                                            <p class="badge-secondary w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead style="background-color: indianred; color:white">
                                                    <tr>
                                                        <th scope="col">Departamento</th>
                                                        <th scope="col" class="text-center">Enero</th>
                                                        <th scope="col" class="text-center">Febrero</th>
                                                        <th scope="col" class="text-center">Marzo</th>
                                                        <th scope="col" class="text-center">Abril</th>
                                                        <th scope="col" class="text-center">Mayo</th>
                                                        <th scope="col" class="text-center">Junio</th>
                                                        <th scope="col" class="text-center">Julio</th>
                                                        <th scope="col" class="text-center">Agosto</th>
                                                        <th scope="col" class="text-center">Septiembre</th>
                                                        <th scope="col" class="text-center">Octubre</th>
                                                        <th scope="col" class="text-center">Noviembre</th>
                                                        <th scope="col" class="text-center">Diciembre</th>
                                                        <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anio; ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if($tipoUsuarioVer == 'unico'){
                                                        $sqlDeptoTOT = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                    }else if($tipoUsuarioVer == 'todos'){
                                                        $sqlDeptoTOT = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                    }

                                                    $resDepaTOT = mysqli_query($conexion,$sqlDeptoTOT);
                                                    $cantPorDeptoOT = 0;

                                                    $cantPorMesTotalOT = 0;

                                                    $arrayCantTotalOT = array();

                                                    $totalMes1OT = 0;
                                                    $totalMes2OT = 0;
                                                    $totalMes3OT = 0;
                                                    $totalMes4OT = 0;
                                                    $totalMes5OT = 0;
                                                    $totalMes6OT = 0;
                                                    $totalMes7OT = 0;
                                                    $totalMes8OT = 0;
                                                    $totalMes9OT = 0;
                                                    $totalMes10OT = 0;
                                                    $totalMes11OT = 0;
                                                    $totalMes12OT = 0;

                                                    while($rowOTO = $resDepaTOT->fetch_assoc()){
                                                        $cantTotalFinalOT = 0;
                                                        $idDepartamentoOTO = $rowOTO['idDepartamento'];
                                                        $nomDepartamentoOTO = $rowOTO['nomDepartamento'];

                                                        $contarOTAnio = contarOTMesAnio($idDepartamentoOTO, $anio);

                                                        if ($nomDepartamentoOTO == 'Informática' || $nomDepartamentoOTO == 'Ingeniería Operacional') {
                                                        }else{
                                                            $cantTotalDeptoOTO = 0;
                                                            ?>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php echo $nomDepartamentoOTO; ?>
                                                                </th>

                                                                <?php
                                                                for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <td class="text-center">
                                                                        <?php
                                                                        $mesOT = '';
                                                                        if ($i < 13) {
                                                                            $mesOT = '0' . $i;
                                                                        }
                                                                        $fechaOT = $anio . '-' . $mesOT;

                                                                        $contarOTMesAnio = contarOTMesAnio($idDepartamentoOTO, $fechaOT);

                                                                        $cantTotalDeptoOTO = $cantTotalDeptoOTO + $contarOTMesAnio;

                                                                        if ($contarOTMesAnio < 1) {
                                                                            echo '-';
                                                                        } else {
                                                                            echo $contarOTMesAnio;
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                    if ($i == 1) {
                                                                        $totalMes1OT = $totalMes1OT + $contarOTMesAnio;
                                                                    } else if ($i == 2) {
                                                                        $totalMes2OT = $totalMes2OT + $contarOTMesAnio;
                                                                    } else if ($i == 3) {
                                                                        $totalMes3OT = $totalMes3OT + $contarOTMesAnio;
                                                                    } else if ($i == 4) {
                                                                        $totalMes4OT = $totalMes4OT + $contarOTMesAnio;
                                                                    } else if ($i == 5) {
                                                                        $totalMes5OT = $totalMes5OT + $contarOTMesAnio;
                                                                    } else if ($i == 6) {
                                                                        $totalMes6OT = $totalMes6OT + $contarOTMesAnio;
                                                                    } else if ($i == 7) {
                                                                        $totalMes7OT = $totalMes7OT + $contarOTMesAnio;
                                                                    } else if ($i == 8) {
                                                                        $totalMes8OT = $totalMes8OT + $contarOTMesAnio;
                                                                    } else if ($i == 9) {
                                                                        $totalMes9OT = $totalMes9OT + $contarOTMesAnio;
                                                                    } else if ($i == 10) {
                                                                        $totalMes10OT = $totalMes10OT + $contarOTMesAnio;
                                                                    } else if ($i == 11) {
                                                                        $totalMes11OT = $totalMes11OT + $contarOTMesAnio;
                                                                    } else if ($i == 12) {
                                                                        $totalMes12OT = $totalMes12OT + $contarOTMesAnio;
                                                                    }
                                                                    $cantTotalFinalOT = $cantTotalFinalOT + $cantTotalDeptoOTO;
                                                                }
                                                                ?>
                                                                <td class="text-center text-bold">
                                                                    <?php echo $cantTotalDeptoOTO; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }

                                                    array_push($arrayCantTotalOT, $totalMes1OT);
                                                    array_push($arrayCantTotalOT, $totalMes2OT);
                                                    array_push($arrayCantTotalOT, $totalMes3OT);
                                                    array_push($arrayCantTotalOT, $totalMes4OT);
                                                    array_push($arrayCantTotalOT, $totalMes5OT);
                                                    array_push($arrayCantTotalOT, $totalMes6OT);
                                                    array_push($arrayCantTotalOT, $totalMes7OT);
                                                    array_push($arrayCantTotalOT, $totalMes8OT);
                                                    array_push($arrayCantTotalOT, $totalMes9OT);
                                                    array_push($arrayCantTotalOT, $totalMes10OT);
                                                    array_push($arrayCantTotalOT, $totalMes11OT);
                                                    array_push($arrayCantTotalOT, $totalMes12OT);

                                                    ?>
                                                    <tr class="bg-secondary">
                                                        <th scope="row">Totales</th>

                                                        <?php
                                                        for ($i = 0; $i < 12; $i++) {
                                                            ?>
                                                            <td class="text-center text-bold"><?php echo $arrayCantTotalOT[$i] ?></td>
                                                            <?php
                                                            $cantPorMesTotalOT = $cantPorMesTotalOT + $arrayCantTotalOT[$i];
                                                        }
                                                        ?>

                                                        <td class="text-center text-bold"><?php echo $cantPorMesTotalOT ?></td>
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
                                    $activeDashOT = '';
                                    for ($i = 1; $i < 13; $i++) {

                                        $mesNowOT = date('m', time());
                                        if ($i == $mesNowOT) {
                                            $activeDashOT = 'active';
                                        } else {
                                            $activeDashOT = '';
                                        }
                                        ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link <?php echo $activeDashOT; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-home-orden<?php echo $i; ?><?php echo $anio?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <?php
                                    $activeDash2OT = '';
                                    for ($i = 1; $i < 13; $i++) {
                                        $mesAnioTabOT = date("$anio", time());

                                        if ($i < 10) {
                                            $mesAnioTabOT = $mesAnioTabOT . '-0' . $i;
                                        } else {
                                            $mesAnioTabOT = $mesAnioTabOT . '-' . $i;
                                        }

                                        $mesNowOT = date('m', time());
                                        $mesNowOT = ltrim(date('m'), '0');

                                        $mesEspañol2DashOT = mesEspañol2($mesNowOT);
                                        if ($i == $mesNowOT) {
                                            $activeDash2OT = 'show active';
                                        } else {
                                            $activeDash2OT = '';
                                        }
                                        ?>

                                        <div class="tab-pane fade <?php echo $activeDash2OT?>" id="pills-home-orden<?php echo $i?><?php echo $anio?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
                                            <div class="row">
                                                <div class="col-12 col-md-6 h-100">
                                                </div>
                                                <?php
                                                if($tipoUsuarioVer == 'unico'){
                                                    $sqlDeptoOTT3 = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE ('%Control Centralizado%')  ORDER BY nomDepartamento ASC";
                                                }else if($tipoUsuarioVer == 'todos'){
                                                    $sqlDeptoOTT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE 'Control Centralizado'  ORDER BY nomDepartamento ASC";
                                                }

                                                $resDeptoOTT3 = mysqli_query($conexion, $sqlDeptoOTT3);

                                                while ($rowDeptoOTT3 = $resDeptoOTT3->fetch_assoc()) {
                                                    $idDeptoOTT3 = $rowDeptoOTT3['idDepartamento'];
                                                    $nomDeptoOTT3 = $rowDeptoOTT3['nomDepartamento'];

                                                    switch ($idDeptoOTT3) {
                                                        case 1:
                                                            $colorTipoDepartamentoOT = 'card-success';
                                                            break;
                                                        case 2:
                                                            $colorTipoDepartamentoOT = 'card-purple';
                                                            break;
                                                        case 3:
                                                            $colorTipoDepartamentoOT = 'card-info';
                                                            break;
                                                        case 4:
                                                            $colorTipoDepartamentoOT = 'card-danger';
                                                            break;
                                                        case 5:
                                                            $colorTipoDepartamentoOT = 'card-orange';
                                                            break;
                                                        case 6:
                                                            $colorTipoDepartamentoOT = 'card-yellow';
                                                            break;
                                                        case 7:
                                                            $colorTipoDepartamentoOT = 'card-indigo';
                                                            break;
                                                        case 8:
                                                            $colorTipoDepartamentoOT = 'card-purple';
                                                            break;
                                                        case 9:
                                                            $colorTipoDepartamentoOT = 'card-info';
                                                            break;
                                                        case 10:
                                                            $colorTipoDepartamentoOT = 'card-danger';
                                                            break;
                                                        case 11:
                                                            $colorTipoDepartamentoOT = 'card-orange';
                                                            break;
                                                        case 11:
                                                            $colorTipoDepartamentoOT = 'card-yellow';
                                                            break;
                                                        default:
                                                            $colorTipoDepartamentoOT = '';
                                                    }

                                                    ?>
                                                    <div class="col-12 col-md-12 h-100">
                                                        <div class="card <?php echo $colorTipoDepartamentoOT; ?> collapsed-card">
                                                            <div class="card-header">
                                                                <h3 class="card-title">&nbsp;&nbsp;<b>
                                                                        <?php
                                                                        echo $nomDeptoOTT3;
                                                                        ?>
                                                                    </b></h3>

                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <!-- /.card-tools -->
                                                            </div>
                                                            <div class="card-body">
                                                                <table class="table table-hover">
                                                                    <thead style="font-size: 12px">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">PERSONAL</th>
                                                                        <th scope="col" class="text-center">CANTIDAD DE DOCUMENTOS</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody style="font-size: 12px">
                                                                    <?php
                                                                    $sqlOT = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idTG) as total   FROM personal as p INNER JOIN trabajos_general as o ON p.nomPersonal = o.tecnico1 WHERE o.departamento = " . $idDeptoOTT3 . " AND o.fechaTermino BETWEEN '" . $mesAnioTabOT . "-01 00:00:00' AND '" . $mesAnioTabOT . "-31 23:59:59' GROUP BY p.idPersonal ORDER BY COUNT(o.idTG) DESC;";
                                                                    //$sqlOT = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idTG) as total FROM personal as p INNER JOIN trabajos_general as o ON p.nomPersonal = o.tecnico1 WHERE o.departamento = "1" AND o.fechaTermino BETWEEN '".$mesAnioTabOT." 00:00:00' AND '2024-01-31 23:59:59' GROUP BY p.idPersonal ORDER BY COUNT(o.idTG) DESC;";
                                                                    $resOT = mysqli_query($conexion, $sqlOT);
                                                                    $totalDeptoForOT = 0;

                                                                    while ($rowOT = $resOT->fetch_assoc()) {
                                                                        $nomPersonalOT = $rowOT['nomPersonal'];
                                                                        $cargoPersonalOT = $rowOT['cargoPersonal'];
                                                                        $idPersonalOT = $rowOT['idPersonal'];
                                                                        $totalOT = $rowOT['total'];
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo $nomPersonalOT . " (" . $cargoPersonalOT . ")" ?></td>
                                                                            <td class="text-center"><?php echo $totalOT ?></td>
                                                                        </tr>

                                                                        <?php
                                                                        $totalDeptoForOT = $totalDeptoForOT + $totalOT;
                                                                    }

                                                                    $sqlJefeDeptoForOT = "SELECT p.idJefe, p.nomJefe,p.cargoJefe, COUNT(o.idTG) as total  FROM jefes_tecnicos as p INNER JOIN trabajos_general as o ON p.nomJefe = o.tecnico1 WHERE o.departamento = " . $idDeptoOTT3 . " AND o.fechaTermino BETWEEN '" . $mesAnioTabOT. "-01 00:00:00' AND '" . $mesAnioTabOT . "-31 23:59:59' GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                                                    $resJefeDeptoForOT = mysqli_query($conexion, $sqlJefeDeptoForOT);
                                                                    while ($rowJefeDeptoForOT = $resJefeDeptoForOT->fetch_assoc()) {
                                                                        $nomJefeOT = $rowJefeDeptoForOT['nomJefe'];
                                                                        $cargoJefeOT = $rowJefeDeptoForOT['cargoJefe'];
                                                                        $idJefeOT = $rowJefeDeptoForOT['idJefe'];
                                                                        $totalOT = $rowJefeDeptoForOT['total'];
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo $nomJefeOT . " (" . $cargoJefeOT . ")" ?></td>
                                                                            <td class="text-center"><?php echo $totalOT ?></td>
                                                                        </tr>

                                                                        <?php

                                                                        $totalDeptoForOT = $totalDeptoForOT + $totalOT;
                                                                    }

                                                                    ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <td class="text-center">Total</td>
                                                                        <td class="text-center"><?php echo $totalDeptoForOT ?> </td>
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
                                            <br>
                                            <?php

                                            $numCardOT = 1;

                                            $obtenerCantidadDepartamentosOT = obtenerCantidadDepartamentos();

                                            $colorTipoDepartamentoOT = '';

                                            ?>

                                            <div class="row">

                                            </div>
                                        </div>

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                            <div class="tab-pane" id="tabOT<?php echo $row['anio']; ?>">
                                <div class="col-12">
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    $nowT = date('Y-m', time());
                                                    $anioT = date('Y', time());
                                                $titulo1 = "Cantidad de Ordenes de Trabajo Terminadas en Año " . $anio . " por Departamento";
                                                $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                ?>
                                                <p class="badge-secondary w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead style="background-color: indianred; color:white">
                                                        <tr>
                                                            <th scope="col">Departamento</th>
                                                            <th scope="col" class="text-center">Enero</th>
                                                            <th scope="col" class="text-center">Febrero</th>
                                                            <th scope="col" class="text-center">Marzo</th>
                                                            <th scope="col" class="text-center">Abril</th>
                                                            <th scope="col" class="text-center">Mayo</th>
                                                            <th scope="col" class="text-center">Junio</th>
                                                            <th scope="col" class="text-center">Julio</th>
                                                            <th scope="col" class="text-center">Agosto</th>
                                                            <th scope="col" class="text-center">Septiembre</th>
                                                            <th scope="col" class="text-center">Octubre</th>
                                                            <th scope="col" class="text-center">Noviembre</th>
                                                            <th scope="col" class="text-center">Diciembre</th>
                                                            <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anio; ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if($tipoUsuarioVer == 'unico'){
                                                                $sqlDeptoTOT = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                            }else if($tipoUsuarioVer == 'todos'){
                                                                $sqlDeptoTOT = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') ORDER BY nomDepartamento ASC";
                                                            }
                                                            $resDepaTOT = mysqli_query($conexion,$sqlDeptoTOT);
                                                            $cantPorDeptoOT = 0;

                                                            $cantPorMesTotalOT = 0;

                                                            $arrayCantTotalOT = array();

                                                            $totalMes1OT = 0;
                                                            $totalMes2OT = 0;
                                                            $totalMes3OT = 0;
                                                            $totalMes4OT = 0;
                                                            $totalMes5OT = 0;
                                                            $totalMes6OT = 0;
                                                            $totalMes7OT = 0;
                                                            $totalMes8OT = 0;
                                                            $totalMes9OT = 0;
                                                            $totalMes10OT = 0;
                                                            $totalMes11OT = 0;
                                                            $totalMes12OT = 0;
                                                            while($rowOTO = $resDepaTOT->fetch_assoc()){
                                                                $cantTotalFinalOT = 0;
                                                                $idDepartamentoOTO = $rowOTO['idDepartamento'];
                                                                $nomDepartamentoOTO = $rowOTO['nomDepartamento'];
                                                                $contarOTAnio = contarOTMesAnio($idDepartamentoOTO, $anio);
                                                                if ($nomDepartamentoOTO == 'Informática' || $nomDepartamentoOTO == 'Ingeniería Operacional') {
                                                                }else{
                                                                    $cantTotalDeptoOTO = 0;
                                                                    ?>
                                                                    <tr>
                                                                        <th scope="row">
                                                                            <?php echo $nomDepartamentoOTO; ?>
                                                                        </th>
                                                                        <?php
                                                                        for ($i = 1; $i < 13; $i++) {
                                                                            ?>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    $mesOT = '';
                                                                                    if ($i < 13) {
                                                                                        $mesOT = '0' . $i;
                                                                                    }
                                                                                    $fechaOT = $anio . '-' . $mesOT;

                                                                                    $contarOTMesAnio = contarOTMesAnio($idDepartamentoOTO, $fechaOT);

                                                                                    $cantTotalDeptoOTO = $cantTotalDeptoOTO + $contarOTMesAnio;

                                                                                    if ($contarOTMesAnio < 1) {
                                                                                        echo '-';
                                                                                    } else {
                                                                                        echo $contarOTMesAnio;
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <?php
                                                                                if ($i == 1) {
                                                                                    $totalMes1OT = $totalMes1OT + $contarOTMesAnio;
                                                                                } else if ($i == 2) {
                                                                                    $totalMes2OT = $totalMes2OT + $contarOTMesAnio;
                                                                                } else if ($i == 3) {
                                                                                    $totalMes3OT = $totalMes3OT + $contarOTMesAnio;
                                                                                } else if ($i == 4) {
                                                                                    $totalMes4OT = $totalMes4OT + $contarOTMesAnio;
                                                                                } else if ($i == 5) {
                                                                                    $totalMes5OT = $totalMes5OT + $contarOTMesAnio;
                                                                                } else if ($i == 6) {
                                                                                    $totalMes6OT = $totalMes6OT + $contarOTMesAnio;
                                                                                } else if ($i == 7) {
                                                                                    $totalMes7OT = $totalMes7OT + $contarOTMesAnio;
                                                                                } else if ($i == 8) {
                                                                                    $totalMes8OT = $totalMes8OT + $contarOTMesAnio;
                                                                                } else if ($i == 9) {
                                                                                    $totalMes9OT = $totalMes9OT + $contarOTMesAnio;
                                                                                } else if ($i == 10) {
                                                                                    $totalMes10OT = $totalMes10OT + $contarOTMesAnio;
                                                                                } else if ($i == 11) {
                                                                                    $totalMes11OT = $totalMes11OT + $contarOTMesAnio;
                                                                                } else if ($i == 12) {
                                                                                    $totalMes12OT = $totalMes12OT + $contarOTMesAnio;
                                                                                }
                                                                                $cantTotalFinalOT = $cantTotalFinalOT + $cantTotalDeptoOTO;
                                                                                ?>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <td class="text-center text-bold">
                                                                            <?php echo $cantTotalDeptoOTO; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            array_push($arrayCantTotalOT, $totalMes1OT);
                                                            array_push($arrayCantTotalOT, $totalMes2OT);
                                                            array_push($arrayCantTotalOT, $totalMes3OT);
                                                            array_push($arrayCantTotalOT, $totalMes4OT);
                                                            array_push($arrayCantTotalOT, $totalMes5OT);
                                                            array_push($arrayCantTotalOT, $totalMes6OT);
                                                            array_push($arrayCantTotalOT, $totalMes7OT);
                                                            array_push($arrayCantTotalOT, $totalMes8OT);
                                                            array_push($arrayCantTotalOT, $totalMes9OT);
                                                            array_push($arrayCantTotalOT, $totalMes10OT);
                                                            array_push($arrayCantTotalOT, $totalMes11OT);
                                                            array_push($arrayCantTotalOT, $totalMes12OT);
                                                            ?>
                                                            <tr class="bg-secondary">
                                                                <th scope="row">Totales</th>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    ?>
                                                                    <td class="text-center text-bold"><?php echo $arrayCantTotalOT[$i] ?></td>
                                                                    <?php
                                                                    $cantPorMesTotalOT = $cantPorMesTotalOT + $arrayCantTotalOT[$i];
                                                                }
                                                                ?>
                                                                <td class="text-center text-bold"><?php echo $cantPorMesTotalOT ?></td>
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
                                        $activeDashOT = '';
                                        for ($i = 1; $i < 13; $i++) {

                                            $mesNowOT = date('m', time());
                                            if ($i == $mesNowOT) {
                                                $activeDashOT = 'active';
                                            } else {
                                                $activeDashOT = '';
                                            }
                                            ?>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link <?php echo $activeDashOT; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-home-orden<?php echo $i; ?><?php echo $anio?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <?php
                                        $activeDash2OT = '';
                                        for ($i = 1; $i < 13; $i++) {
                                            $mesAnioTabOT = date("$anio", time());
                                            if ($i < 10) {
                                                $mesAnioTabOT = $mesAnioTabOT . '-0' . $i;
                                            } else {
                                                $mesAnioTabOT = $mesAnioTabOT . '-' . $i;
                                            }
                                            $mesNowOT = date('m', time());
                                            $mesNowOT = ltrim(date('m'), '0');

                                            $mesEspañol2DashOT = mesEspañol2($mesNowOT);
                                            if ($i == $mesNowOT) {
                                                $activeDash2OT = 'show active';
                                            } else {
                                                $activeDash2OT = '';
                                            }
                                            ?>
                                            <div class="tab-pane fade <?php echo $activeDash2OT?>" id="pills-home-orden<?php echo $i?><?php echo $anio?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
                                                <div class="row">
                                                    <div class="col-12 col-md-12 h-100">
                                                    </div>
                                                    <?php
                                                    if($tipoUsuarioVer == 'unico'){
                                                        $sqlDeptoOTT3 = "SELECT * FROM departamentos WHERE nomDepartamento = '" . $nomDepartamento . "' AND nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE ('%Control Centralizado%')  ORDER BY nomDepartamento ASC";
                                                    }else if($tipoUsuarioVer == 'todos'){
                                                        $sqlDeptoOTT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Ingeniería Operacional' AND nomDepartamento NOT LIKE 'Control Centralizado'  ORDER BY nomDepartamento ASC";
                                                    }
                                                    $resDeptoOTT3 = mysqli_query($conexion, $sqlDeptoOTT3);
                                                    while ($rowDeptoOTT3 = $resDeptoOTT3->fetch_assoc()) {
                                                        $idDeptoOTT3 = $rowDeptoOTT3['idDepartamento'];
                                                        $nomDeptoOTT3 = $rowDeptoOTT3['nomDepartamento'];
                                                        switch ($idDeptoOTT3) {
                                                            case 1:
                                                                $colorTipoDepartamentoOT = 'card-success';
                                                                break;
                                                            case 2:
                                                                $colorTipoDepartamentoOT = 'card-purple';
                                                                break;
                                                            case 3:
                                                                $colorTipoDepartamentoOT = 'card-info';
                                                                break;
                                                            case 4:
                                                                $colorTipoDepartamentoOT = 'card-danger';
                                                                break;
                                                            case 5:
                                                                $colorTipoDepartamentoOT = 'card-orange';
                                                                break;
                                                            case 6:
                                                                $colorTipoDepartamentoOT = 'card-yellow';
                                                                break;
                                                            case 7:
                                                                $colorTipoDepartamentoOT = 'card-indigo';
                                                                break;
                                                            case 8:
                                                                $colorTipoDepartamentoOT = 'card-purple';
                                                                break;
                                                            case 9:
                                                                $colorTipoDepartamentoOT = 'card-info';
                                                                break;
                                                            case 10:
                                                                $colorTipoDepartamentoOT = 'card-danger';
                                                                break;
                                                            case 11:
                                                                $colorTipoDepartamentoOT = 'card-orange';
                                                                break;
                                                            case 11:
                                                                $colorTipoDepartamentoOT = 'card-yellow';
                                                                break;
                                                            default:
                                                                $colorTipoDepartamentoOT = '';
                                                        }
                                                        ?>
                                                        <div class="col-12 col-md-6 h-100">
                                                            <div class="card <?php echo $colorTipoDepartamentoOT; ?> collapsed-card">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">&nbsp;&nbsp;<b>
                                                                            <?php
                                                                            echo $nomDeptoOTT3;
                                                                            ?>
                                                                        </b></h3>

                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /.card-tools -->
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-hover">
                                                                        <thead style="font-size: 12px">
                                                                        <tr>
                                                                            <th scope="col" class="text-center">PERSONAL</th>
                                                                            <th scope="col" class="text-center">CANTIDAD DE DOCUMENTOS</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody style="font-size: 12px">
                                                                        <?php
                                                                        $sqlOT = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idTG) as total   FROM personal as p INNER JOIN trabajos_general as o ON p.nomPersonal = o.tecnico1 WHERE o.departamento = " . $idDeptoOTT3 . " AND o.fechaTermino BETWEEN '" . $mesAnioTabOT . "-01 00:00:00' AND '" . $mesAnioTabOT . "-31 23:59:59' GROUP BY p.idPersonal ORDER BY COUNT(o.idTG) DESC;";
                                                                        //$sqlOT = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idTG) as total FROM personal as p INNER JOIN trabajos_general as o ON p.nomPersonal = o.tecnico1 WHERE o.departamento = "1" AND o.fechaTermino BETWEEN '".$mesAnioTabOT." 00:00:00' AND '2024-01-31 23:59:59' GROUP BY p.idPersonal ORDER BY COUNT(o.idTG) DESC;";
                                                                        $resOT = mysqli_query($conexion, $sqlOT);
                                                                        $totalDeptoForOT = 0;
                                                                        while ($rowOT = $resOT->fetch_assoc()) {
                                                                            $nomPersonalOT = $rowOT['nomPersonal'];
                                                                            $cargoPersonalOT = $rowOT['cargoPersonal'];
                                                                            $idPersonalOT = $rowOT['idPersonal'];
                                                                            $totalOT = $rowOT['total'];
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $nomPersonalOT . " (" . $cargoPersonalOT . ")" ?></td>
                                                                                <td class="text-center"><?php echo $totalOT ?></td>
                                                                            </tr>

                                                                            <?php
                                                                            $totalDeptoForOT = $totalDeptoForOT + $totalOT;
                                                                        }
                                                                        $sqlJefeDeptoForOT = "SELECT p.idJefe, p.nomJefe,p.cargoJefe, COUNT(o.idTG) as total  FROM jefes_tecnicos as p INNER JOIN trabajos_general as o ON p.nomJefe = o.tecnico1 WHERE o.departamento = " . $idDeptoOTT3 . " AND o.fechaTermino BETWEEN '" . $mesAnioTabOT. "-01 00:00:00' AND '" . $mesAnioTabOT . "-31 23:59:59' GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                                                        $resJefeDeptoForOT = mysqli_query($conexion, $sqlJefeDeptoForOT);
                                                                        while ($rowJefeDeptoForOT = $resJefeDeptoForOT->fetch_assoc()) {
                                                                            $nomJefeOT = $rowJefeDeptoForOT['nomJefe'];
                                                                            $cargoJefeOT = $rowJefeDeptoForOT['cargoJefe'];
                                                                            $idJefeOT = $rowJefeDeptoForOT['idJefe'];
                                                                            $totalOT = $rowJefeDeptoForOT['total'];
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $nomJefeOT . " (" . $cargoJefeOT . ")" ?></td>
                                                                                <td class="text-center"><?php echo $totalOT ?></td>
                                                                            </tr>

                                                                            <?php

                                                                            $totalDeptoForOT = $totalDeptoForOT + $totalOT;
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td class="text-center">Total</td>
                                                                            <td class="text-center"><?php echo $totalDeptoForOT ?> </td>
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
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
