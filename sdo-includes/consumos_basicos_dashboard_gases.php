<div class="card">
    <div class="card-body">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioAgua = date('Y', time());

                $sqlAniosAgua = "SELECT DISTINCT YEAR(fechaIngreso) AS anio FROM gases_clinicos ORDER BY fechaIngreso ASC;";
                $resAniosAgua = mysqli_query($conexion, $sqlAniosAgua);
                while ($rowAgua = $resAniosAgua->fetch_assoc()) {
                    if ($rowAgua['anio'] == $nowAnioAgua) {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabClinico<?php echo $rowAgua['anio']; ?>" data-toggle="tab"><?php echo $rowAgua['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabClinico<?php echo $rowAgua['anio']; ?>" data-toggle="tab"><?php echo $rowAgua['anio']; ?></a></li>
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

                $sqlAniosAgua = "SELECT DISTINCT YEAR(fechaIngreso) AS anio FROM gases_clinicos ORDER BY fechaIngreso ASC;";

                $resAniosAgua = mysqli_query($conexion, $sqlAniosAgua);

                while($rowAniosAgua = $resAniosAgua->fetch_assoc()){
                    $anioAgua = $rowAniosAgua['anio'];
                    $anioAnterior = $anioAgua - 1;
                    $nowMes = date('m', time());

                    if($anioAgua == $nowAnioAgua){
                        ?>
                        <div class="tab-pane" id="tabClinico<?php echo $rowAniosAgua['anio']; ?>">
                            <div class="card-body">
                                <table style="font-size:80%"  class="table table-bordered cell-border">
                                    <thead style="font-size: 12px;background-color: #ff653f;color: white">
                                    <tr>
                                        <th scope="col" class="text-center">MESES</th>
                                        <th scope="col" class="text-center">CONSUMO DE GASES CLINICOS (SUM/VALORES DE CILINDROS)</th>
                                        <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                    </tr>
                                    </thead>
                                    <tbody style="font-size: 12px" class="table-bordered">
                                    <tr>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                ?>
                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                $t1Mesgases = obtenerConsumoGasesClinicos($anioAgua . '-' . $i);
                                                $t1Mesgases = number_format($t1Mesgases, 0, ",", ".");
                                                ?>
                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t1Mesgases ?></b></p>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                $t2Mesgases = obtenerMontoIvaGasesClinicos($anioAgua . '-' . $i);
                                                //  $todoTotal = $t1Mes * 1.19;
                                                $todoTotalgases = number_format($t2Mesgases, 0, ",", ".");
                                                ?>
                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'. $todoTotalgases?></b></p>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot style="font-size: 15px;background-color: #ff653f;color: white">
                                    <tr>
                                        <td class="info-box-text text-center">Total</td>
                                        <td class="text-center">
                                            <?php
                                            $total = valorTotalNetogases($anioAgua);
                                            $total = number_format($total, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$total?></b></p>
                                        </td>
                                        <td>
                                            <?php
                                            $totalMonto = valorTotalMontoGases($anioAgua);
                                            $totalMonto = number_format($totalMonto, 0, ",", ".");
                                            //$sumaTotal = number_format($sumaTotal, 5, ",", ".");
                                            // $sumaTotal = round($sumaTotal,4,PHP_ROUND_HALF_UP);
                                            ?>
                                            <p class="info-box-text text-center"><b><?php echo '$'. $totalMonto . ' <br>'; ?></b></p>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                        <div class="tab-pane" id="tabClinico<?php echo $rowAniosAgua['anio']; ?>">
                            <div class="card-body">
                                <table style="font-size:80%"  class="table table-bordered cell-border">
                                    <thead style="font-size: 12px;background-color: #ff653f;color: white">
                                    <tr>
                                        <th scope="col" class="text-center">MESES</th>
                                        <th scope="col" class="text-center">CONSUMO DE GASES CLINICOS (SUM/VALORES DE CILINDROS)</th>
                                        <th scope="col" class="text-center">MONTO MENSUAL / INCLUYE IVA</th>
                                    </tr>
                                    </thead>
                                    <tbody style="font-size: 12px" class="table-bordered">
                                    <tr>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                    ?>
                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                    <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                    $t1Mesgases = obtenerConsumoGasesClinicos($anioAgua . '-' . $i);
                                                    $t1Mesgases = number_format($t1Mesgases, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$t1Mesgases ?></b></p>
                                                    <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {
                                                    $t2Mesgases = obtenerMontoIvaGasesClinicos($anioAgua . '-' . $i);
                                                    //  $todoTotal = $t1Mes * 1.19;
                                                    $todoTotalgases = number_format($t2Mesgases, 0, ",", ".");
                                                    ?>
                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'. $todoTotalgases?></b></p>
                                                    <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot style="font-size: 15px;background-color: #ff653f;color: white">
                                    <tr>
                                        <td class="info-box-text text-center">Total</td>
                                        <td class="text-center">
                                            <?php
                                            $total = valorTotalNetogases($anioAgua);
                                            $total = number_format($total, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'.$total?></b></p>
                                        </td>
                                        <td>
                                            <?php
                                            $totalMonto = valorTotalMontoGases($anioAgua);
                                            $totalMonto = number_format($totalMonto, 0, ",", ".");
                                            //$sumaTotal = number_format($sumaTotal, 5, ",", ".");
                                            // $sumaTotal = round($sumaTotal,4,PHP_ROUND_HALF_UP);
                                            ?>
                                            <p class="info-box-text text-center"><b><?php echo '$'. $totalMonto . ' <br>'; ?></b></p>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
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