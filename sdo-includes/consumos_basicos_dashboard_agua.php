<div class="card">
    <div class="card-body">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioAgua = date('Y', time());

                $sqlAniosAgua = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM agua_administracion ORDER BY fechaDocumento ASC;";
                $resAniosAgua = mysqli_query($conexion, $sqlAniosAgua);
                while ($rowAgua = $resAniosAgua->fetch_assoc()) {
                    if ($rowAgua['anio'] == $nowAnioAgua) {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabAgua<?php echo $rowAgua['anio']; ?>" data-toggle="tab"><?php echo $rowAgua['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabAgua<?php echo $rowAgua['anio']; ?>" data-toggle="tab"><?php echo $rowAgua['anio']; ?></a></li>
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
                $nowAnioAgua = date('Y', time());

                $sqlAniosAgua = "SELECT DISTINCT YEAR(fechaDocumento) AS anio FROM agua_administracion ORDER BY fechaDocumento ASC;";

                $resAniosAgua = mysqli_query($conexion, $sqlAniosAgua);

                while($rowAniosAgua = $resAniosAgua->fetch_assoc()){
                    $anioAgua = $rowAniosAgua['anio'];
                    $anioAnterior = $anioAgua - 1;
                    $nowMes = date('m', time());

                    if($anioAgua == $nowAnioAgua){
                         ?>
                        <div class="tab-pane" id="tabAgua<?php echo $rowAniosAgua['anio']; ?>">
                            <div class="row">
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <center>
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anioAgua?> HBQP</b></h3>
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
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                            $t1MesAGUA = obtenerConsumoMesAgua($anioAgua . '-' . $i);
                                                            $t1MesAGUA = number_format($t1MesAGUA, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1MesAGUA. ' (Mt<sup>3</sup>)' ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                            $t1Mesagua1 = obtenerMontodelMesAgua($anioAgua . '-' . $i);
                                                            $t1Mesagua1 = number_format($t1Mesagua1, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mesagua1. ' con IVA'.' <br>'; ?></b></p>

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
                                                        $totalAGUA = sumartablaagua($anioAgua);
                                                        $totalAGUA = number_format($totalAGUA, 0, ",", ".");

                                                        ?>

                                                        <p class="info-box-text text-center"><b><?php echo '' . $totalAGUA . ' (Mt<sup>3</sup>).<br>'; ?></b></p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $totalMontoAGUA = sumartotalmontoagua($anioAgua);
                                                        $totalMontoAGUA = number_format($totalMontoAGUA, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$'. $totalMontoAGUA . ' <br>'; ?></b></p>
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
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anioAgua?> HSMQ</b></h3>
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
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                            $t1MesConsumoAgua = obtenerSumaTotalAguaHSMQ($anioAgua . '-' . $i);
                                                            $t1MesConsumoAgua = number_format($t1MesConsumoAgua, 0, ",", ".");

                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1MesConsumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                            <?php

                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                            $t2MesMontoTotalAgua = obtenerSumaTotalMontoAguaHSMQ($anioAgua . '-' . $i);
                                                            $t2MesMontoTotalAgua = number_format($t2MesMontoTotalAgua, 0, ",", ".");

                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2MesMontoTotalAgua ?></b></p>
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
                                                        $SQL2agua = "SELECT SUM(consumoAgua) as TotalSumaConsumo1agua FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RES2agua = mysqli_query($conexion,$SQL2agua);
                                                        while($ROW2agua = $RES2agua->fetch_assoc()){
                                                            $TotalSumaConsumo1agua = $ROW2agua['TotalSumaConsumo1agua'];
                                                            if(empty($TotalSumaConsumo1agua)){
                                                                $TotalSumaConsumo1agua = 0;
                                                            }
                                                            $TotalSumaConsumo1agua = number_format($TotalSumaConsumo1agua, 0, ",", ".");

                                                        }
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo  $TotalSumaConsumo1agua . ' (Mt<sup>3</sup>) <br>'; ?></b></p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $SQL3agua = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2agua FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RES3agua = mysqli_query($conexion,$SQL3agua);
                                                        while($ROW3agua = $RES3agua->fetch_assoc()){
                                                            $TotalSumaConsumo2agua = $ROW3agua['TotalSumaConsumo2agua'];
                                                            if(empty($TotalSumaConsumo2agua)){
                                                                $TotalSumaConsumo2agua = 0;
                                                            }
                                                            $TotalSumaConsumo2agua = number_format($TotalSumaConsumo2agua, 0, ",", ".");

                                                        }
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaConsumo2agua ?></b></p>

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
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;TOTALES AÑO  <?php echo $anioAgua?> AMBOS HOSPITALES</b></h3>
                                            </center>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>

                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered cell-border" id="example3">
                                                <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES</th>
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                            $consumoAgua = obtenerSumaConsumodeTodoAgua($anioAgua . '-' . $i);
                                                            $consumoAgua = number_format($consumoAgua, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++){
                                                            $montoTotalAgua = obtenerSumaTotaldeTodoAgua($anioAgua . '-' . $i);
                                                            $montoTotalAgua = number_format($montoTotalAgua, 0, ",", ".");

                                                            ?>
                                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalAgua ?></b></p>
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
                                                        $SQLCONSUMOAGUA = "SELECT SUM(consumoAgua) as TotalSumaConsumoTotalAgua FROM agua_administracion WHERE YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RESCONSUMOAGUA = mysqli_query($conexion,$SQLCONSUMOAGUA);
                                                        while($ROWCONSUMOAGUA = $RESCONSUMOAGUA->fetch_assoc()){
                                                            $TotalSumaConsumoTotalAgua = $ROWCONSUMOAGUA['TotalSumaConsumoTotalAgua'];
                                                            if(empty($TotalSumaConsumoTotalAgua)){
                                                                $TotalSumaConsumoTotalAgua = 0;
                                                            }
                                                            $TotalSumaConsumoTotalAgua = number_format($TotalSumaConsumoTotalAgua, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotalAgua . '(Mt<sup>3</sup>) <br>'; ?></b></p>

                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $SQLMONTOTOTALAGUA = "SELECT SUM(montoTotalMes) as TotalSumaMontoTotalAGUA FROM agua_administracion WHERE YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RESMONTOAGUA = mysqli_query($conexion,$SQLMONTOTOTALAGUA);
                                                        while($ROWMONTOAGUA = $RESMONTOAGUA->fetch_assoc()){
                                                            $TotalSumaMontoTotalAGUA = $ROWMONTOAGUA['TotalSumaMontoTotalAGUA'];
                                                            if(empty($TotalSumaMontoTotalAGUA)){
                                                                $TotalSumaMontoTotalAGUA = 0;
                                                            }
                                                            $TotalSumaMontoTotalAGUA = number_format($TotalSumaMontoTotalAGUA, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotalAGUA; ?></b></p>
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
                            </div>
                        </div>
                         <?php
                    }else{
                         ?>
                        <div class="tab-pane" id="tabAgua<?php echo $rowAniosAgua['anio']; ?>">
                            <div class="row">
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <center>
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anioAgua?> HBQP</b></h3>
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
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                                $t1MesAGUA = obtenerConsumoMesAgua($anioAgua . '-' . $i);
                                                                $t1MesAGUA = number_format($t1MesAGUA, 0, ",", ".");
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo $t1MesAGUA. ' (Mt<sup>3</sup>)' ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                                $t1Mesagua1 = obtenerMontodelMesAgua($anioAgua . '-' . $i);
                                                                $t1Mesagua1 = number_format($t1Mesagua1, 0, ",", ".");
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px;"><b><?php echo '$'. $t1Mesagua1. ' con IVA'.' <br>'; ?></b></p>

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
                                                        $totalAGUA = sumartablaagua($anioAgua);
                                                        $totalAGUA = number_format($totalAGUA, 0, ",", ".");

                                                        ?>

                                                        <p class="info-box-text text-center"><b><?php echo '' . $totalAGUA . ' (Mt<sup>3</sup>).<br>'; ?></b></p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $totalMontoAGUA = sumartotalmontoagua($anioAgua);
                                                        $totalMontoAGUA = number_format($totalMontoAGUA, 0, ",", ".");
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$'. $totalMontoAGUA . ' <br>'; ?></b></p>
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
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;RESUMEN TOTAL AÑO <?php echo $anioAgua?> HSMQ</b></h3>
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
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                                $t1MesConsumoAgua = obtenerSumaTotalAguaHSMQ($anioAgua . '-' . $i);
                                                                $t1MesConsumoAgua = number_format($t1MesConsumoAgua, 0, ",", ".");

                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1MesConsumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                                <?php

                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++) {
                                                                $t2MesMontoTotalAgua = obtenerSumaTotalMontoAguaHSMQ($anioAgua . '-' . $i);
                                                                $t2MesMontoTotalAgua = number_format($t2MesMontoTotalAgua, 0, ",", ".");

                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t2MesMontoTotalAgua ?></b></p>
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
                                                        $SQL2agua = "SELECT SUM(consumoAgua) as TotalSumaConsumo1agua FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RES2agua = mysqli_query($conexion,$SQL2agua);
                                                        while($ROW2agua = $RES2agua->fetch_assoc()){
                                                            $TotalSumaConsumo1agua = $ROW2agua['TotalSumaConsumo1agua'];
                                                            if(empty($TotalSumaConsumo1agua)){
                                                                $TotalSumaConsumo1agua = 0;
                                                            }
                                                            $TotalSumaConsumo1agua = number_format($TotalSumaConsumo1agua, 0, ",", ".");

                                                        }
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo  $TotalSumaConsumo1agua . ' (Mt<sup>3</sup>) <br>'; ?></b></p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $SQL3agua = "SELECT SUM(montoTotalMes) as TotalSumaConsumo2agua FROM agua_administracion WHERE tipoHospital = 'HSMQ' AND YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RES3agua = mysqli_query($conexion,$SQL3agua);
                                                        while($ROW3agua = $RES3agua->fetch_assoc()){
                                                            $TotalSumaConsumo2agua = $ROW3agua['TotalSumaConsumo2agua'];
                                                            if(empty($TotalSumaConsumo2agua)){
                                                                $TotalSumaConsumo2agua = 0;
                                                            }
                                                            $TotalSumaConsumo2agua = number_format($TotalSumaConsumo2agua, 0, ",", ".");

                                                        }
                                                        ?>
                                                        <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaConsumo2agua ?></b></p>

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
                                                <h3 class="card-title"><i class="fas fa-tint"></i><b> &nbsp;TOTALES AÑO  <?php echo $anioAgua?> AMBOS HOSPITALES</b></h3>
                                            </center>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>

                                            <!-- /.card-tools -->
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered cell-border" id="example3">
                                                <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                                <tr>
                                                    <th scope="col" class="text-center">MESES</th>
                                                    <th scope="col" class="text-center">CONSUMO DE AGUA (Mt<sup>3</sup>)</th>
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
                                                                $consumoAgua = obtenerSumaConsumodeTodoAgua($anioAgua . '-' . $i);
                                                                $consumoAgua = number_format($consumoAgua, 0, ",", ".");
                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $consumoAgua.' (Mt<sup>3</sup>)' ?></b></p>
                                                                <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        for ($i = 1; $i < 13; $i++){
                                                                $montoTotalAgua = obtenerSumaTotaldeTodoAgua($anioAgua . '-' . $i);
                                                                $montoTotalAgua = number_format($montoTotalAgua, 0, ",", ".");

                                                                ?>
                                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$montoTotalAgua ?></b></p>
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
                                                        $SQLCONSUMOAGUA = "SELECT SUM(consumoAgua) as TotalSumaConsumoTotalAgua FROM agua_administracion WHERE YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RESCONSUMOAGUA = mysqli_query($conexion,$SQLCONSUMOAGUA);
                                                        while($ROWCONSUMOAGUA = $RESCONSUMOAGUA->fetch_assoc()){
                                                            $TotalSumaConsumoTotalAgua = $ROWCONSUMOAGUA['TotalSumaConsumoTotalAgua'];
                                                            if(empty($TotalSumaConsumoTotalAgua)){
                                                                $TotalSumaConsumoTotalAgua = 0;
                                                            }
                                                            $TotalSumaConsumoTotalAgua = number_format($TotalSumaConsumoTotalAgua, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center"><b><?php echo $TotalSumaConsumoTotalAgua . '(Mt<sup>3</sup>) <br>'; ?></b></p>

                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        $SQLMONTOTOTALAGUA = "SELECT SUM(montoTotalMes) as TotalSumaMontoTotalAGUA FROM agua_administracion WHERE YEAR(fechaDocumento) = '$anioAgua'";
                                                        $RESMONTOAGUA = mysqli_query($conexion,$SQLMONTOTOTALAGUA);
                                                        while($ROWMONTOAGUA = $RESMONTOAGUA->fetch_assoc()){
                                                            $TotalSumaMontoTotalAGUA = $ROWMONTOAGUA['TotalSumaMontoTotalAGUA'];
                                                            if(empty($TotalSumaMontoTotalAGUA)){
                                                                $TotalSumaMontoTotalAGUA = 0;
                                                            }
                                                            $TotalSumaMontoTotalAGUA = number_format($TotalSumaMontoTotalAGUA, 0, ",", ".");
                                                            ?>
                                                            <p class="info-box-text text-center"><b><?php echo '$' .$TotalSumaMontoTotalAGUA; ?></b></p>
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