<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());

                $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM electricidad_administracion WHERE tipohospital = 'HSMQ' ORDER BY fechaDocumento ASC;";
                $resAnios = mysqli_query($conexion, $sqlAnios);
                while ($row = $resAnios->fetch_assoc()) {
                    if ($row['anio'] == $nowAnio) {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tab<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tab<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
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

                $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM electricidad_administracion WHERE tipohospital = 'HSMQ' ORDER BY fechaDocumento ASC;";

                $resAnios = mysqli_query($conexion, $sqlAnios);

                while ($row = $resAnios->fetch_assoc()) {
                    $anio = $row['anio'];

                    $anioAnterior = $anio - 1;
                    $nowMes = date('m', time());

                    if ($anio == $nowAnio) {
                        ?>
                        <div class="tab-pane" id="tab<?php echo $row['anio']; ?>">
                            <div class="row">
                                <?php
                                $sqlTener = "SELECT * FROM cliente_electricidad WHERE tipoHospital = 'HSMQ'";
                                $resTener = mysqli_query($conexion,$sqlTener);
                                while($rowTener = $resTener->fetch_assoc()){
                                    $idCliente = $rowTener['idCliente'];
                                    ?>
                                    <div class="col-12 col-md-4 h-100">
                                        <div class="card card-success collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fas fa-bolt"></i>&nbsp;&nbsp;<b>Cantidad de Consumo y Monto del Mes Cliente N°: <?php echo $rowTener['nCliente']; ?></b></h3>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead style="font-size: 12px; background-color: indigo; color: white " class="table-bordered">
                                                    <tr>
                                                        <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                        <th scope="col" class="text-center">CONSUMO (KWH)</th>
                                                        <th scope="col" class="text-center">MONTO MENSUAL</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 12px">
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' <br>'; ?></b></p>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                // $t1Mes = obtenerConsumoHSMQ($nowAnio . '-' . $i);
                                                                $fecha = $anio. '-' .$i;
                                                                $inicio = $fecha . '-01';
                                                                $termino = $fecha . '-31';

                                                                $numAC = 1;
                                                                $numAnterior = 0;
                                                                $valorFinalAC = 0;
                                                                $consumoT1 = 0;

                                                                $query = "SELECT * FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                $res = mysqli_query($conexion,$query);
                                                                while ($row = $res->fetch_assoc()) {
                                                                    if ($numAC == 1) {
                                                                        $numAnterior = $row['consumoEnergia'];
                                                                        $valorFinalAC = $row['consumoEnergia'];
                                                                        $consumoT1 = $valorFinalAC;
                                                                        // echo 'a    '.$consumoT1.'<br><br>';
                                                                    }else{
                                                                        if ($row['tipoHospital'] == 'HSMQ') {
                                                                            $diferencia = $row['consumoEnergia'] - $numAnterior;
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC = '';
                                                                                $consumoT1 = $consumoT1 + $diferencia;
                                                                                // echo 'b    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '0';
                                                                            } else {
                                                                                $valorFinalAC = ' ( + ' . $diferencia . ')';
                                                                                // $consumoT1 = '+'.$diferencia;
                                                                                // echo 'c    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '+';
                                                                            }
                                                                            $numAnterior = $row['consumoEnergia'];
                                                                        }else{
                                                                            $diferencia = $numAnterior - $row['consumoEnergia'];
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC = '';
                                                                                // $identificadorAC1 = '0';
                                                                                // echo 'd    '.$consumoT1.'<br><br>';
                                                                            } else {
                                                                                $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                                if (strpos($valorFinalAC, '- -') !== false) {
                                                                                    $diferencia = 0;
                                                                                }
                                                                                $consumoT1 = $consumoT1 - $diferencia;
                                                                                // echo 'f    '.$consumoT1.'<br><br>';
                                                                            }
                                                                            $numAnterior = $row['consumoEnergia'];
                                                                        }
                                                                    }
                                                                    $numAC++;
                                                                }


                                                                ?>

                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoT1. ' kWh' ?></b></p>
                                                                <?php

                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                $fecha = $anio. '-' .$i;
                                                                $inicio = $fecha . '-01';
                                                                $termino = $fecha . '-31';

                                                                $numAC = 1;
                                                                $numAnterior = 0;
                                                                $valorFinalAC = 0;
                                                                $consumoT3 = 0;

                                                                $query = "SELECT * FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                $res = mysqli_query($conexion,$query);
                                                                while ($row = $res->fetch_assoc()) {
                                                                    if ($numAC == 1) {
                                                                        $numAnterior = $row['montoTotalMes'];
                                                                        $valorFinalAC = $row['montoTotalMes'];
                                                                        $consumoT3 = $valorFinalAC;
                                                                        // echo 'a    '.$consumoT1.'<br><br>';
                                                                    }else{
                                                                        if ($row['tipoHospital'] == 'HSMQ') {
                                                                            $diferencia = $row['montoTotalMes'] - $numAnterior;
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC = '';
                                                                                $consumoT3 = $consumoT3 + $diferencia;
                                                                                // echo 'b    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '0';
                                                                            } else {
                                                                                $valorFinalAC = ' ( + ' . $diferencia . ')';
                                                                                // $consumoT1 = '+'.$diferencia;
                                                                                // echo 'c    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '+';
                                                                            }
                                                                            $numAnterior = $row['montoTotalMes'];
                                                                        }else{
                                                                            $diferencia = $numAnterior - $row['montoTotalMes'];
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC = '';
                                                                                // $identificadorAC1 = '0';
                                                                                // echo 'd    '.$consumoT1.'<br><br>';
                                                                            } else {
                                                                                $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                                if (strpos($valorFinalAC, '- -') !== false) {
                                                                                    $diferencia = 0;
                                                                                }
                                                                                $consumoT3 = $consumoT3 - $diferencia;
                                                                                // echo 'f    '.$consumoT1.'<br><br>';
                                                                            }
                                                                            $numAnterior = $row['montoTotalMes'];
                                                                        }
                                                                    }
                                                                    $numAC++;
                                                                }
                                                                $consumoT3 = number_format($consumoT3, 0, ",", ".");
                                                                ?>

                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$consumoT3 ?></b></p>

                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot style="background-color: indigo; color: white">
                                                    <tr>
                                                        <td class="text-center">Total</td>
                                                        <td class="text-center">
                                                            <?php
                                                            $TotalSumaConsumo = sumarconsumohsmq($anio,$idCliente);
                                                            echo $TotalSumaConsumo.' kWh';
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $TotalSuma = sumarmontototalhsmq($anio,$idCliente);
                                                            $TotalSuma = number_format($TotalSuma, 0, ",", ".");
                                                            echo '$'. $TotalSuma;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-bolt"></i>&nbsp;&nbsp;<b>Total de Consumo y Monto del Mes de todos los clientes</b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table  cell-border table-bordered">
                                                <thead style="font-size: 12px;background-color: indigo; color: white">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                    <th scope="col" class="text-center">CONSUMO (KWH)</th>
                                                    <th scope="col" class="text-center">MONTO MENSUAL</th>
                                                </tr>
                                                </thead>
                                                <tbody style="font-size: 12px">
                                                <tr>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                            $t1Mes = obtenerSumaTotalHSMQ($anio . '-' . $i);
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' kWh' ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                            $t2Mes = obtenerSumaTotalMontoHSMQ($anio. '-' . $i);
                                                            $t2Mes = number_format($t2Mes, 0, ",", ".");

                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot style="background-color: indigo; color: white">
                                                <tr>
                                                    <td class="text-center">Total</td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo1 = sumatotalconsumohsmq($anio);
                                                        $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                        echo $TotalSumaConsumo1.' kWh';
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo2 = sumamontotalhsmq($anio);
                                                        $TotalSumaConsumo2 = number_format($TotalSumaConsumo2, 0, ",", ".");
                                                        echo '$'. $TotalSumaConsumo2;
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                        <div class="tab-pane" id="tab<?php echo $row['anio']; ?>">
                            <div class="row">
                                <?php
                                $sqlTener = "SELECT * FROM cliente_electricidad WHERE tipoHospital = 'HSMQ'";
                                $resTener = mysqli_query($conexion,$sqlTener);
                                while($rowTener = $resTener->fetch_assoc()){
                                    $idCliente = $rowTener['idCliente'];
                                    ?>
                                    <div class="col-12 col-md-4 h-100">
                                        <div class="card card-success collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fas fa-bolt"></i>&nbsp;&nbsp;<b>Cantidad de Consumo y Monto del Mes Cliente N°: <?php echo $rowTener['nCliente']; ?></b></h3>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead style="font-size: 12px; background-color: indigo; color: white " class="table-bordered">
                                                    <tr>
                                                        <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                        <th scope="col" class="text-center">CONSUMO (KWH)</th>
                                                        <th scope="col" class="text-center">MONTO MENSUAL</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 12px">
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' <br>'; ?></b></p>
                                                                    <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                    // $t1Mes = obtenerConsumoHSMQ($nowAnio . '-' . $i);
                                                                    $fecha = $anio. '-' .$i;
                                                                    $inicio = $fecha . '-01';
                                                                    $termino = $fecha . '-31';

                                                                    $numAC = 1;
                                                                    $numAnterior = 0;
                                                                    $valorFinalAC = 0;
                                                                    $consumoT1 = 0;

                                                                    $query = "SELECT * FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                    $res = mysqli_query($conexion,$query);
                                                                    while ($row = $res->fetch_assoc()) {
                                                                        if ($numAC == 1) {
                                                                            $numAnterior = $row['consumoEnergia'];
                                                                            $valorFinalAC = $row['consumoEnergia'];
                                                                            $consumoT1 = $valorFinalAC;
                                                                            // echo 'a    '.$consumoT1.'<br><br>';
                                                                        }else{
                                                                            if ($row['tipoHospital'] == 'HSMQ') {
                                                                                $diferencia = $row['consumoEnergia'] - $numAnterior;
                                                                                if ($diferencia == 0) {
                                                                                    $valorFinalAC = '';
                                                                                    $consumoT1 = $consumoT1 + $diferencia;
                                                                                    // echo 'b    '.$consumoT1.'<br><br>';
                                                                                    // $identificadorAC1 = '0';
                                                                                } else {
                                                                                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                                                                                    // $consumoT1 = '+'.$diferencia;
                                                                                    // echo 'c    '.$consumoT1.'<br><br>';
                                                                                    // $identificadorAC1 = '+';
                                                                                }
                                                                                $numAnterior = $row['consumoEnergia'];
                                                                            }else{
                                                                                $diferencia = $numAnterior - $row['consumoEnergia'];
                                                                                if ($diferencia == 0) {
                                                                                    $valorFinalAC = '';
                                                                                    // $identificadorAC1 = '0';
                                                                                    // echo 'd    '.$consumoT1.'<br><br>';
                                                                                } else {
                                                                                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                                    if (strpos($valorFinalAC, '- -') !== false) {
                                                                                        $diferencia = 0;
                                                                                    }
                                                                                    $consumoT1 = $consumoT1 - $diferencia;
                                                                                    // echo 'f    '.$consumoT1.'<br><br>';
                                                                                }
                                                                                $numAnterior = $row['consumoEnergia'];
                                                                            }
                                                                        }
                                                                        $numAC++;
                                                                    }


                                                                    ?>

                                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoT1. ' kWh' ?></b></p>
                                                                    <?php

                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            for ($i = 1; $i < 13; $i++) {
                                                                    $fecha = $anio. '-' .$i;
                                                                    $inicio = $fecha . '-01';
                                                                    $termino = $fecha . '-31';

                                                                    $numAC = 1;
                                                                    $numAnterior = 0;
                                                                    $valorFinalAC = 0;
                                                                    $consumoT3 = 0;

                                                                    $query = "SELECT * FROM electricidad_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                    $res = mysqli_query($conexion,$query);
                                                                    while ($row = $res->fetch_assoc()) {
                                                                        if ($numAC == 1) {
                                                                            $numAnterior = $row['montoTotalMes'];
                                                                            $valorFinalAC = $row['montoTotalMes'];
                                                                            $consumoT3 = $valorFinalAC;
                                                                            // echo 'a    '.$consumoT1.'<br><br>';
                                                                        }else{
                                                                            if ($row['tipoHospital'] == 'HSMQ') {
                                                                                $diferencia = $row['montoTotalMes'] - $numAnterior;
                                                                                if ($diferencia == 0) {
                                                                                    $valorFinalAC = '';
                                                                                    $consumoT3 = $consumoT3 + $diferencia;
                                                                                    // echo 'b    '.$consumoT1.'<br><br>';
                                                                                    // $identificadorAC1 = '0';
                                                                                } else {
                                                                                    $valorFinalAC = ' ( + ' . $diferencia . ')';
                                                                                    // $consumoT1 = '+'.$diferencia;
                                                                                    // echo 'c    '.$consumoT1.'<br><br>';
                                                                                    // $identificadorAC1 = '+';
                                                                                }
                                                                                $numAnterior = $row['montoTotalMes'];
                                                                            }else{
                                                                                $diferencia = $numAnterior - $row['montoTotalMes'];
                                                                                if ($diferencia == 0) {
                                                                                    $valorFinalAC = '';
                                                                                    // $identificadorAC1 = '0';
                                                                                    // echo 'd    '.$consumoT1.'<br><br>';
                                                                                } else {
                                                                                    $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                                    if (strpos($valorFinalAC, '- -') !== false) {
                                                                                        $diferencia = 0;
                                                                                    }
                                                                                    $consumoT3 = $consumoT3 - $diferencia;
                                                                                    // echo 'f    '.$consumoT1.'<br><br>';
                                                                                }
                                                                                $numAnterior = $row['montoTotalMes'];
                                                                            }
                                                                        }
                                                                        $numAC++;
                                                                    }
                                                                    $consumoT3 = number_format($consumoT3, 0, ",", ".");
                                                                    ?>

                                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$consumoT3 ?></b></p>

                                                                    <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                    <tfoot style="background-color: indigo; color: white">
                                                    <tr>
                                                        <td class="text-center">Total</td>
                                                        <td class="text-center">
                                                            <?php
                                                            $TotalSumaConsumo = sumarconsumohsmq($anio,$idCliente);
                                                            echo $TotalSumaConsumo.' kWh';
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $TotalSuma = sumarmontototalhsmq($anio,$idCliente);
                                                            $TotalSuma = number_format($TotalSuma, 0, ",", ".");
                                                            echo '$'. $TotalSuma;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-bolt"></i>&nbsp;&nbsp;<b>Total de Consumo y Monto del Mes de todos los clientes</b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table  cell-border table-bordered">
                                                <thead style="font-size: 12px;background-color: indigo; color: white">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                    <th scope="col" class="text-center">CONSUMO (KWH)</th>
                                                    <th scope="col" class="text-center">MONTO MENSUAL</th>
                                                </tr>
                                                </thead>
                                                <tbody style="font-size: 12px">
                                                <tr>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i).  ' <br>'; ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                                $t1Mes = obtenerSumaTotalHSMQ($anio . '-' . $i);
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' kWh' ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                                $t2Mes = obtenerSumaTotalMontoHSMQ($anio. '-' . $i);
                                                                $t2Mes = number_format($t2Mes, 0, ",", ".");

                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot style="background-color: indigo; color: white">
                                                <tr>
                                                    <td class="text-center">Total</td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo1 = sumatotalconsumohsmq($anio);
                                                        $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                        echo $TotalSumaConsumo1.' kWh';
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo2 = sumamontotalhsmq($anio);
                                                        $TotalSumaConsumo2 = number_format($TotalSumaConsumo2, 0, ",", ".");
                                                        echo '$'. $TotalSumaConsumo2;
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
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