<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
        <ul class="nav nav-pills ml-auto p-2">
            <?php
            date_default_timezone_set('America/Santiago');
            $nowAnio = date('Y', time());

            $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM electricidad_administracion ORDER BY fechaDocumento ASC;";
            $resAnios = mysqli_query($conexion, $sqlAnios);
            while ($row = $resAnios->fetch_assoc()) {
                if ($row['anio'] == $nowAnio) {
                    ?>
                    <li class="nav-item"><a class="nav-link" href="#tabElect<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item"><a class="nav-link" href="#tabElect<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
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

            $sqlAnios = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM electricidad_administracion ORDER BY fechaDocumento ASC;";

            $resAnios = mysqli_query($conexion, $sqlAnios);
            while ($row = $resAnios->fetch_assoc()) {
                $anio = $row['anio'];

                $anioAnterior = $anio - 1;
                $nowMes = date('m', time());
                if ($anio == $nowAnio) {
                    ?>
                    <div class="tab-pane" id="tabElect<?php echo $row['anio']; ?>">
                        <div class="row">
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anio?> HBQP</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border table-responsive">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
                                            <tr>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t1Mes = obtenerConsumoMesEnergia($anio . '-' . $i);
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1Mes. ' kWh' ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t1Mes = obtenerMontodelMes($anio . '-' . $i);
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mes. ' con IVA'.' <br>'; ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $total = sumarcosumotodo($anio);
                                                    $total = number_format($total, 0, ",", ".");
                                                    ?>

                                                    <p class="info-box-text text-center"><b><?php echo '' . $total . ' kWh.<br>'; ?></b></p>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalMonto = sumarmontotodo($anio);
                                                    $totalMonto = number_format($totalMonto, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo '$'. $totalMonto . ' <br>'; ?></b></p>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anio?> HSMQ</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
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
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");

                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' kWh' ?></b></p>
                                                        <?php

                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t2Mes = obtenerSumaTotalMontoHSMQ($anio . '-' . $i);
                                                        $t2Mes = number_format($t2Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $SQL2 = "SELECT SUM(consumoEnergia) as TotalSumaConsumo1 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
                                                    $RES2 = mysqli_query($conexion,$SQL2);
                                                    while($ROW2 = $RES2->fetch_assoc()){
                                                        $TotalSumaConsumo1 = $ROW2['TotalSumaConsumo1'];
                                                        if(empty($TotalSumaConsumo1)){
                                                            $TotalSumaConsumo1 = 0;
                                                        }
                                                        $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumo1 . 'kWh <br>'; ?></b></p>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $SQL3 = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio' ";
                                                    $RES3 = mysqli_query($conexion,$SQL3);
                                                    while($ROW3 = $RES3->fetch_assoc()){
                                                        $TotalSumaConsumo2 = $ROW3['TotalSumaConsumo2'];
                                                        if(empty($TotalSumaConsumo2)){
                                                            $TotalSumaConsumo2 = 0;
                                                        }
                                                        $TotalSumaConsumo2 = number_format($TotalSumaConsumo2, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$'. $TotalSumaConsumo2 . ' <br>'; ?></b></p>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;TOTALES AÑO  <?php echo $anio?> AMBOS HOSPITALES</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>

                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
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
                                                    for ($i = 1; $i < 13; $i++){
                                                        $consumoEnergia = obtenerSumaCosumodeTodo($anio . '-' . $i);
                                                        $consumoEnergia = number_format($consumoEnergia, 0, ",", ".");

                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoEnergia.' kWh' ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++){
                                                        $montoTotalElectrico = obtenerSumaTotaldeTodo($anio . '-' . $i);
                                                        $montoTotalElectrico = number_format($montoTotalElectrico, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalElectrico ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumoTotal = totalsumaconsumototal($anio);
                                                    $TotalSumaConsumoTotal = number_format($TotalSumaConsumoTotal, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotal . 'kWh <br>'; ?></b></p>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaMontoTotal = totalsumamontototal($anio);
                                                    $TotalSumaMontoTotal = number_format($TotalSumaMontoTotal, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotal; ?></b></p>
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
                    <div class="tab-pane" id="tabElect<?php echo $row['anio']; ?>">
                        <div class="row">
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anio?> HBQP</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border table-responsive">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
                                            <tr>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t1Mes = obtenerConsumoMesEnergia($anio . '-' . $i);
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1Mes. ' kWh' ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t1Mes = obtenerMontodelMes($anio . '-' . $i);
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mes. ' con IVA'.' <br>'; ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $total = sumarcosumotodo($anio);
                                                    $total = number_format($total, 0, ",", ".");
                                                    ?>

                                                    <p class="info-box-text text-center"><b><?php echo '' . $total . ' kWh.<br>'; ?></b></p>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalMonto = sumarmontotodo($anio);
                                                    $totalMonto = number_format($totalMonto, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo '$'. $totalMonto . ' <br>'; ?></b></p>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anio?> HSMQ</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
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
                                                        $t1Mes = number_format($t1Mes, 0, ",", ".");

                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes.' kWh' ?></b></p>
                                                        <?php

                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++) {
                                                        $t2Mes = obtenerSumaTotalMontoHSMQ($anio . '-' . $i);
                                                        $t2Mes = number_format($t2Mes, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2Mes ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $SQL2 = "SELECT SUM(consumoEnergia) as TotalSumaConsumo1 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio'";
                                                    $RES2 = mysqli_query($conexion,$SQL2);
                                                    while($ROW2 = $RES2->fetch_assoc()){
                                                        $TotalSumaConsumo1 = $ROW2['TotalSumaConsumo1'];
                                                        if(empty($TotalSumaConsumo1)){
                                                            $TotalSumaConsumo1 = 0;
                                                        }
                                                        $TotalSumaConsumo1 = number_format($TotalSumaConsumo1, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumo1 . 'kWh <br>'; ?></b></p>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $SQL3 = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2 FROM electricidad_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anio' ";
                                                    $RES3 = mysqli_query($conexion,$SQL3);
                                                    while($ROW3 = $RES3->fetch_assoc()){
                                                        $TotalSumaConsumo2 = $ROW3['TotalSumaConsumo2'];
                                                        if(empty($TotalSumaConsumo2)){
                                                            $TotalSumaConsumo2 = 0;
                                                        }
                                                        $TotalSumaConsumo2 = number_format($TotalSumaConsumo2, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$'. $TotalSumaConsumo2 . ' <br>'; ?></b></p>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 h-100">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <center>
                                            <h3 class="card-title"><i class="fas fa-bolt"></i><b> &nbsp;TOTALES AÑO  <?php echo $anio?> AMBOS HOSPITALES</b></h3>
                                        </center>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>

                                        <!-- /.card-tools -->
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered cell-border">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th scope="col" class="text-center">MESES</th>
                                                <th scope="col" class="text-center">CONSUMO DE ENERGIA (KWH)</th>
                                                <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                            </tr>
                                            </thead>
                                            <tbody style="font-size: 12px" class="table-bordered">
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
                                                    for ($i = 1; $i < 13; $i++){
                                                        $consumoEnergia = obtenerSumaCosumodeTodo($anio . '-' . $i);
                                                        $consumoEnergia = number_format($consumoEnergia, 0, ",", ".");

                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoEnergia.' kWh' ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    for ($i = 1; $i < 13; $i++){
                                                        $montoTotalElectrico = obtenerSumaTotaldeTodo($anio . '-' . $i);
                                                        $montoTotalElectrico = number_format($montoTotalElectrico, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalElectrico ?></b></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <td class="info-box-text text-center">Total</td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaConsumoTotal = totalsumaconsumototal($anio);
                                                    $TotalSumaConsumoTotal = number_format($TotalSumaConsumoTotal, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotal . 'kWh <br>'; ?></b></p>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalSumaMontoTotal = totalsumamontototal($anio);
                                                    $TotalSumaMontoTotal = number_format($TotalSumaMontoTotal, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotal; ?></b></p>
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