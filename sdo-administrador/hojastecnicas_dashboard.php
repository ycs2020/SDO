<div class="col-12">
    <br>
    <div class="row">
        <div class="col-12">

            <div class="row">
                <?php

                date_default_timezone_set('America/Santiago');
                $nowT = date('Y-m', time());
                $anioT = date('Y', time());

                $titulo1 = "Cantidad de Hojas Técnicas Realizadas en Año " . $anioT . " por Departamento";

                $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                ?>
                <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-success">
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
                                <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anioT; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $sqlDeptoT = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN ('Control Centralizado') ORDER BY nomDepartamento ASC";

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

                                $contarHojasTecnicasAnio = contarHojasTecnicasAnio($idDepartamentoT, $anioT);


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

                                                $fechaT = $anioT . '-' . $mesHT;

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

                                            if ($i == 1) {
                                                $totalMes1 = $totalMes1 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 2) {
                                                $totalMes2 = $totalMes2 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 3) {
                                                $totalMes3 = $totalMes3 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 4) {
                                                $totalMes4 = $totalMes4 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 5) {
                                                $totalMes5 = $totalMes5 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 6) {
                                                $totalMes6 = $totalMes6 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 7) {
                                                $totalMes7 = $totalMes7 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 8) {
                                                $totalMes8 = $totalMes8 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 9) {
                                                $totalMes9 = $totalMes9 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 10) {
                                                $totalMes10 = $totalMes10 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 11) {
                                                $totalMes11 = $totalMes11 + $contarHojasTecnicasMesAnio;
                                            } else if ($i == 12) {
                                                $totalMes12 = $totalMes12 + $contarHojasTecnicasMesAnio;
                                            }


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


                            array_push($arrayCantTotal, $totalMes1);
                            array_push($arrayCantTotal, $totalMes2);
                            array_push($arrayCantTotal, $totalMes3);
                            array_push($arrayCantTotal, $totalMes4);
                            array_push($arrayCantTotal, $totalMes5);
                            array_push($arrayCantTotal, $totalMes6);
                            array_push($arrayCantTotal, $totalMes7);
                            array_push($arrayCantTotal, $totalMes8);
                            array_push($arrayCantTotal, $totalMes9);
                            array_push($arrayCantTotal, $totalMes10);
                            array_push($arrayCantTotal, $totalMes11);
                            array_push($arrayCantTotal, $totalMes12);


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
                <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-homea<?php echo $i; ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
            </li>
        <?php
        }
        ?>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <?php
        $activeDash2 = '';
        for ($i = 1; $i < 13; $i++) {
            $mesAnioTab = date('Y', time());

            if ($i < 10) {
                $mesAnioTab = $mesAnioTab . '-0' . $i;
            } else {
                $mesAnioTab = $mesAnioTab . '-' . $i;
            }

            $mesNow = date('m', time());
            $mesEspañol2Dash = mesEspañol2($mesNow);
            if ($i == $mesNow) {
                $activeDash2 = 'show active';
            } else {
                $activeDash2 = '';
            }

        ?>
            <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">
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
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead style="font-size: 12px">
                                        <tr>
                                            <th scope="col" class="text-center ">ESPECIALIDAD</th>
                                            <th scope="col" class="text-center">MPI</th>
                                            <th scope="col" class="text-center">MRI</th>
                                            <th scope="col" class="text-center">MPE</th>
                                            <th scope="col" class="text-center">MRE</th>
                                            <th scope="col" class="text-center">OM</th>
                                            <th scope="col" class="text-center">IS</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px">
                                        <?php
                                        $sqlDeptoT2 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN ('Control Centralizado') ORDER BY nomDepartamento ASC";

                                        $resDeptoT2 = mysqli_query($conexion, $sqlDeptoT2);

                                        $mesEspañol2Dash2 = mesEspañol2($i);

                                        


                                        while ($rowDeptoT2 = $resDeptoT2->fetch_assoc()) {

                                            $nomDeptoT2 = $rowDeptoT2['nomDepartamento'];

                                            $idDeptoT2 = $rowDeptoT2['idDepartamento'];

                                            if ($nomDeptoT2 == 'Informática' || $nomDeptoT2 == 'Ingeniería Operacional') {
                                            } else {
                                        ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $nomDeptoT2; ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPI', $mesAnioTab); ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRI', $mesAnioTab); ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPE', $mesAnioTab); ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRE', $mesAnioTab); ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'OM', $mesAnioTab); ?></td>
                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'IS', $mesAnioTab); ?></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center">Totales</td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', $mesAnioTab); ?> </td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', $mesAnioTab); ?> </td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', $mesAnioTab); ?> </td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', $mesAnioTab); ?> </td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('OM', $mesAnioTab); ?> </td>
                                            <td class="text-center"><?php echo obtieneTotalHTTipo('IS', $mesAnioTab); ?> </td>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>


                </div>

                <br>

                <br>

                <?php

                $numCardHT = 1;

                $obtenerCantidadDepartamentos = obtenerCantidadDepartamentos();

                $colorTipoDepartamento = '';

                ?>
                <div class="row">
                    <?php


                    $sqlDeptoT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional'  AND nomDepartamento NOT LIKE 'Informática' AND nomDepartamento NOT LIKE 'Control Centralizado' ORDER BY nomDepartamento ASC";

                    $resDeptoT3 = mysqli_query($conexion, $sqlDeptoT3);

                    while ($rowDeptoT3 = $resDeptoT3->fetch_assoc()) {

                        $idDeptoT3 = $rowDeptoT3['idDepartamento'];
                        $nomDeptoT3 = $rowDeptoT3['nomDepartamento'];

                        switch ($numCardHT) {
                            case 1:
                                $colorTipoDepartamento = 'card-success';
                                break;
                            case 2:
                                $colorTipoDepartamento = 'card-purple';
                                break;
                            case 3:
                                $colorTipoDepartamento = 'card-info';
                                break;
                            case 4:
                                $colorTipoDepartamento = 'card-danger';
                                break;
                            case 5:
                                $colorTipoDepartamento = 'card-orange';
                                break;
                            case 6:
                                $colorTipoDepartamento = 'card-yellow';
                                break;
                            case 7:
                                $colorTipoDepartamento = 'card-success';
                                break;
                            case 8:
                                $colorTipoDepartamento = 'card-purple';
                                break;
                            case 9:
                                $colorTipoDepartamento = 'card-info';
                                break;
                            case 10:
                                $colorTipoDepartamento = 'card-danger';
                                break;
                            case 11:
                                $colorTipoDepartamento = 'card-orange';
                                break;
                            case 11:
                                $colorTipoDepartamento = 'card-yellow';
                                break;
                            default:
                                $colorTipoDepartamento = '';
                        }

                    ?>


                        <div class="col-12 col-md-6 h-100">
                            <div class="card <?php echo $colorTipoDepartamento; ?> collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">&nbsp;&nbsp;<b>
                                            <?php
                                            echo $nomDeptoT3;

                                            $numCardHT = $numCardHT + 1;
                                            ?>
                                        </b></h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
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


                                            $sql = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idPersonal ORDER BY p.nomPersonal ASC;";
                                            $res = mysqli_query($conexion, $sql);
                                            $totalDeptoFor = 0;

                                            while ($row = $res->fetch_assoc()) {
                                                $datos = [];
                                                $datos['nomPersonal'] = $row['nomPersonal'];
                                                $datos['cargoPersonal'] = $row['cargoPersonal'];
                                                $datos['idPersonal'] = $row['idPersonal'];
                                                $datos['total'] = $row['total'];
                                                $nomPersonal = $datos['nomPersonal'];
                                                $cargoPersonal = $datos['cargoPersonal'];
                                                $idPersonal = $datos['idPersonal'];
                                                $total = $datos['total'];

                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $nomPersonal . " (" . $cargoPersonal . ")" ?></td>
                                                    <td class="text-center"><?php echo $total ?></td>
                                                </tr>

                                            <?php

                                                $totalDeptoFor = $totalDeptoFor + $total;
                                            }

                                            $sqlJefeDeptoFor = "SELECT p.idJefe, p.nomJefe,p.cargoJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                            $resJefeDeptoFor = mysqli_query($conexion, $sqlJefeDeptoFor);

                                            while ($rowJefeDeptoFor = $resJefeDeptoFor->fetch_assoc()) {
                                                $datos = [];
                                                $datos['nomJefe'] = $rowJefeDeptoFor['nomJefe'];
                                                $datos['cargoJefe'] = $rowJefeDeptoFor['cargoJefe'];
                                                $datos['idJefe'] = $rowJefeDeptoFor['idJefe'];
                                                $datos['total'] = $rowJefeDeptoFor['total'];
                                                $nomJefe = $datos['nomJefe'];
                                                $cargoJefe = $datos['cargoJefe'];
                                                $idJefe = $datos['idJefe'];
                                                $total = $datos['total'];
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $nomJefe . " (" . $cargoJefe . ")" ?></td>
                                                    <td class="text-center"><?php echo $total ?></td>
                                                </tr>

                                            <?php

                                                $totalDeptoFor = $totalDeptoFor + $total;
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-center">Total</td>
                                                <td class="text-center"><?php echo $totalDeptoFor ?> </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>

                    <?php


                    }

                    ?>
                </div>

            </div><!-- fin pestaña -->
        <?php
        }
        ?>
    </div>


</div>