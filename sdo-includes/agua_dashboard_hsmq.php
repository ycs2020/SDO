<div class="col-12 col-md-12">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
        <ul class="nav nav-pills ml-auto p-2">
            <?php
            date_default_timezone_set('America/Santiago');
            $nowAnio = date('Y', time());

            $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM agua_administracion WHERE tipohospital = 'HSMQ' ORDER BY fechaDocumento ASC;";
            $resAnios = mysqli_query($conexion, $sqlAnios);
            while ($row = $resAnios->fetch_assoc()) {
                if ($row['anio'] == $nowAnio) {
                    ?>
                    <li class="nav-item"><a class="nav-link active" href="#tab<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
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

            $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM agua_administracion WHERE tipohospital = 'HSMQ' ORDER BY fechaDocumento ASC;";
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
                            $sqlTener = "SELECT * FROM cliente_agua WHERE tipoHospital = 'HSMQ'";
                            $resTener = mysqli_query($conexion,$sqlTener);
                            while($rowTener = $resTener->fetch_assoc()){
                                $idCliente = $rowTener['idCliente'];
                                ?>
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-tint"></i>&nbsp;<b>Consumo De Agua (Mt<sup>3</sup>) y Monto del Mes del Cliente N°: <?php echo $rowTener['nCliente']; ?> </b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <thead style="font-size: 12px; background-color: indianred; color: white " class="table-bordered">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (MT3)</th>
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
                                                        for($i = 1; $i < 13; $i++){

                                                            $fecha = $anio. '-' .$i;
                                                            $inicio = $fecha . '-01';
                                                            $termino = $fecha . '-31';

                                                            $numAC = 1;
                                                            $numAnterior = 0;
                                                            $valorFinalAC = 0;
                                                            $consumoT1 = 0;

                                                            $query = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                            $res = mysqli_query($conexion,$query);
                                                            while ($row = $res->fetch_assoc()) {
                                                                if ($numAC == 1) {
                                                                    $numAnterior = $row['consumoAgua'];
                                                                    $valorFinalAC = $row['consumoAgua'];
                                                                    $consumoT1 = $valorFinalAC;
                                                                    // echo 'a    '.$consumoT1.'<br><br>';
                                                                }else{
                                                                    if ($row['tipoHospital'] == 'HSMQ') {
                                                                        $diferencia = $row['consumoAgua'] - $numAnterior;
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
                                                                        $numAnterior = $row['consumoAgua'];
                                                                    }else{
                                                                        $diferencia = $numAnterior - $row['consumoAgua'];
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
                                                                        $numAnterior = $row['consumoAgua'];
                                                                    }
                                                                }
                                                                $numAC++;
                                                            }
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoT1. ' (Mt<sup>3</sup>)' ?></b></p>

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
                                                            $consumoT2 = 0;

                                                            $queryMonto = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                            $resMonto = mysqli_query($conexion,$queryMonto);
                                                            while ($rowMonto = $resMonto->fetch_assoc()) {
                                                                if ($numAC == 1) {
                                                                    $numAnterior = $rowMonto['montoTotalMes'];
                                                                    $valorFinalAC = $rowMonto['montoTotalMes'];
                                                                    $consumoT2 = $valorFinalAC;
                                                                    // echo 'a    '.$consumoT1.'<br><br>';
                                                                }else{
                                                                    if ($rowMonto['tipoHospital'] == 'HSMQ') {
                                                                        $diferencia = $rowMonto['montoTotalMes'] - $numAnterior;
                                                                        if ($diferencia == 0) {
                                                                            $valorFinalAC1 = '';
                                                                            $consumoT2 = $consumoT2 + $diferencia;
                                                                            // echo 'b    '.$consumoT1.'<br><br>';
                                                                            // $identificadorAC1 = '0';
                                                                        } else {
                                                                            $valorFinalAC1 = ' ( + ' . $diferencia . ')';
                                                                            // $consumoT1 = '+'.$diferencia;
                                                                            // echo 'c    '.$consumoT1.'<br><br>';
                                                                            // $identificadorAC1 = '+';
                                                                        }
                                                                        $numAnterior = $rowMonto['montoTotalMes'];
                                                                    }else{
                                                                        $diferencia = $numAnterior - $rowMonto['montoTotalMes'];
                                                                        if ($diferencia == 0) {
                                                                            $valorFinalAC1 = '';
                                                                            // $identificadorAC1 = '0';
                                                                            // echo 'd    '.$consumoT1.'<br><br>';
                                                                        } else {
                                                                            $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                            if (strpos($valorFinalAC, '- -') !== false) {
                                                                                $diferencia = 0;
                                                                            }
                                                                            $consumoT2 = $consumoT2 - $diferencia;
                                                                            // echo 'f    '.$consumoT1.'<br><br>';
                                                                        }
                                                                        $numAnterior = $rowMonto['montoTotalMes'];
                                                                    }
                                                                }
                                                                $numAC++;
                                                            }
                                                            $consumoT2 = number_format($consumoT2, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$' .$consumoT2 ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot style="background-color: indianred; color: white">
                                                <tr>
                                                    <td class="text-center">Total</td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo = sumarconsumoaguahsmq($anio,$idCliente);
                                                        echo $TotalSumaConsumo.' (Mt<sup>3</sup>)';
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSuma = sumamontotalAguahsmq($anio,$idCliente);
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
                                        <h3 class="card-title"><i class="fas fa-tint"></i>&nbsp;&nbsp;<b>Total de Consumo y Monto del Mes de todos los clientes</b></h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table  cell-border table-bordered">
                                            <thead style="font-size: 12px;background-color: indianred; color: white  ">
                                            <tr>
                                                <th scope="col" class="text-center">MESES (AÑO <?php echo $anio ?>)</th>
                                                <th scope="col" class="text-center">CONSUMO (Mt<sup>3</sup>)</th>
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
                                                        $t1Mes = obtenerSumaTotalAguaHSMQ($anio . '-' . $i);
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' (Mt<sup>3</sup>)' ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t2Mes = obtenerSumaTotalMontoAguaHSMQ($anio . '-' . $i);
                                                        $t2Mes = number_format($t2Mes, 0, ",", ".");

                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="background-color: indianred; color: white">
                                            <tr>
                                                <td class="text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumo1 = totalsumaconsumoagua($anio);
                                                    $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                    echo $TotalSumaConsumo1.' (Mt<sup>3</sup>)';
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumo2 = totalsumamontototalagua($anio);
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
                            $sqlTener = "SELECT * FROM cliente_agua WHERE tipoHospital = 'HSMQ'";
                            $resTener = mysqli_query($conexion,$sqlTener);
                            while($rowTener = $resTener->fetch_assoc()){
                                $idCliente = $rowTener['idCliente'];
                                ?>
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-tint"></i>&nbsp;<b>Consumo De Agua (Mt<sup>3</sup>) y Monto del Mes del Cliente N°: <?php echo $rowTener['nCliente']; ?> </b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <thead style="font-size: 12px; background-color: indianred; color: white " class="table-bordered">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES (AÑO <?php echo $anio?>)</th>
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (MT3)</th>
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
                                                        for($i = 1; $i < 13; $i++){

                                                                $fecha = $anio. '-' .$i;
                                                                $inicio = $fecha . '-01';
                                                                $termino = $fecha . '-31';

                                                                $numAC = 1;
                                                                $numAnterior = 0;
                                                                $valorFinalAC = 0;
                                                                $consumoT1 = 0;

                                                                $query = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                $res = mysqli_query($conexion,$query);
                                                                while ($row = $res->fetch_assoc()) {
                                                                    if ($numAC == 1) {
                                                                        $numAnterior = $row['consumoAgua'];
                                                                        $valorFinalAC = $row['consumoAgua'];
                                                                        $consumoT1 = $valorFinalAC;
                                                                        // echo 'a    '.$consumoT1.'<br><br>';
                                                                    }else{
                                                                        if ($row['tipoHospital'] == 'HSMQ') {
                                                                            $diferencia = $row['consumoAgua'] - $numAnterior;
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
                                                                            $numAnterior = $row['consumoAgua'];
                                                                        }else{
                                                                            $diferencia = $numAnterior - $row['consumoAgua'];
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
                                                                            $numAnterior = $row['consumoAgua'];
                                                                        }
                                                                    }
                                                                    $numAC++;
                                                                }
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoT1. ' (Mt<sup>3</sup>)' ?></b></p>

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
                                                                $consumoT2 = 0;

                                                                $queryMonto = "SELECT * FROM agua_administracion WHERE fechaDocumento BETWEEN '" . $inicio . " 00:00:00' AND '" . $termino . " 23:59:59' AND nCliente = $idCliente ORDER BY fechaDocumento ASC";
                                                                $resMonto = mysqli_query($conexion,$queryMonto);
                                                                while ($rowMonto = $resMonto->fetch_assoc()) {
                                                                    if ($numAC == 1) {
                                                                        $numAnterior = $rowMonto['montoTotalMes'];
                                                                        $valorFinalAC = $rowMonto['montoTotalMes'];
                                                                        $consumoT2 = $valorFinalAC;
                                                                        // echo 'a    '.$consumoT1.'<br><br>';
                                                                    }else{
                                                                        if ($rowMonto['tipoHospital'] == 'HSMQ') {
                                                                            $diferencia = $rowMonto['montoTotalMes'] - $numAnterior;
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC1 = '';
                                                                                $consumoT2 = $consumoT2 + $diferencia;
                                                                                // echo 'b    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '0';
                                                                            } else {
                                                                                $valorFinalAC1 = ' ( + ' . $diferencia . ')';
                                                                                // $consumoT1 = '+'.$diferencia;
                                                                                // echo 'c    '.$consumoT1.'<br><br>';
                                                                                // $identificadorAC1 = '+';
                                                                            }
                                                                            $numAnterior = $rowMonto['montoTotalMes'];
                                                                        }else{
                                                                            $diferencia = $numAnterior - $rowMonto['montoTotalMes'];
                                                                            if ($diferencia == 0) {
                                                                                $valorFinalAC1 = '';
                                                                                // $identificadorAC1 = '0';
                                                                                // echo 'd    '.$consumoT1.'<br><br>';
                                                                            } else {
                                                                                $valorFinalAC = ' ( - ' . $diferencia . ')';
                                                                                if (strpos($valorFinalAC, '- -') !== false) {
                                                                                    $diferencia = 0;
                                                                                }
                                                                                $consumoT2 = $consumoT2 - $diferencia;
                                                                                // echo 'f    '.$consumoT1.'<br><br>';
                                                                            }
                                                                            $numAnterior = $rowMonto['montoTotalMes'];
                                                                        }
                                                                    }
                                                                    $numAC++;
                                                                }
                                                                $consumoT2 = number_format($consumoT2, 0, ",", ".");
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$' .$consumoT2 ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot style="background-color: indianred; color: white">
                                                <tr>
                                                    <td class="text-center">Total</td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSumaConsumo = sumarconsumoaguahsmq($anio,$idCliente);
                                                        echo $TotalSumaConsumo.' (Mt<sup>3</sup>)';
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $TotalSuma = sumamontotalAguahsmq($anio,$idCliente);
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
                                        <h3 class="card-title"><i class="fas fa-tint"></i>&nbsp;&nbsp;<b>Total de Consumo y Monto del Mes de todos los clientes</b></h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table  cell-border table-bordered">
                                            <thead style="font-size: 12px;background-color: indianred; color: white  ">
                                            <tr>
                                                <th scope="col" class="text-center">MESES (AÑO <?php echo $anio ?>)</th>
                                                <th scope="col" class="text-center">CONSUMO (Mt<sup>3</sup>)</th>
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
                                                            $t1Mes = obtenerSumaTotalAguaHSMQ($anio . '-' . $i);
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' (Mt<sup>3</sup>)' ?></b></p>
                                                            <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                            $t2Mes = obtenerSumaTotalMontoAguaHSMQ($anio . '-' . $i);
                                                            $t2Mes = number_format($t2Mes, 0, ",", ".");

                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                            <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="background-color: indianred; color: white">
                                            <tr>
                                                <td class="text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumo1 = totalsumaconsumoagua($anio);
                                                    $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                    echo $TotalSumaConsumo1.' (Mt<sup>3</sup>)';
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumo2 = totalsumamontototalagua($anio);
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