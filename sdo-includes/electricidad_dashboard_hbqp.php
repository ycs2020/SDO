<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());

                $sqlAnios = "SELECT DISTINCT YEAR(fechaLectura) AS anio FROM electricidad_administracion WHERE tipohospital = 'HBQP' ORDER BY fechaLectura ASC;";
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

                $sqlAnios = "SELECT DISTINCT YEAR(fechaLectura) AS anio FROM electricidad_administracion WHERE tipohospital = 'HBQP' ORDER BY fechaLectura ASC;";

                $resAnios = mysqli_query($conexion, $sqlAnios);

                while ($row = $resAnios->fetch_assoc()) {
                $anio = $row['anio'];

                $anioAnterior = $anio - 1;
                $nowMes = date('m', time());

                if ($anio == $nowAnio) {
                    ?>
                        <div class="tab-pane active" id="tab<?php echo $row['anio']; ?>">
                        <div class="row">
                            <div class="card-body">
                                <table style="font-size:80%"  class="table table-bordered cell-border">
                                    <thead style="font-size: 12px;background-color: indigo;color: white">
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
                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            for ($i = 1; $i < 13; $i++) {


                                                $t1Mes = obtenerConsumoMesEnergia1($conexion,$row['anio'] . '-' . $i);
                                               // $t1Mes = number_format($t1Mes, 0, ",", ".");
                                                ?>
                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes. ' kWh' ?></b></p>
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



                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'. $t1Mes. ' con IVA'.' <br>'; ?></b></p>

                                                <?php

                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot style="font-size: 15px;background-color: indigo;color: white">
                                    <tr>
                                        <td class="info-box-text text-center">Total</td>
                                        <td class="text-center">
                                            <?php
                                            $total = sumarconsumohbqp($anio);
                                            $total = number_format($total, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '' . $total . ' kWh.<br>'; ?></b></p>
                                        </td>
                                        <td>
                                            <?php
                                            $totalMonto = sumarmontototal($anio);
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
                    <?php
                    }else{
                        ?>
                        <div class="tab-pane" id="tab<?php echo $row['anio']; ?>">
                            <div class="row">
                                <div class="card-body">
                                    <table style="font-size:80%"  class="table table-bordered cell-border">
                                        <thead style="font-size: 12px;background-color: indigo;color: white">
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
                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo obtenerMes($i). ' '. ' <br>'; ?></b></p>
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
                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo $t1Mes. ' kWh' ?></b></p>
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



                                                    <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '$'. $t1Mes. ' con IVA'.' <br>'; ?></b></p>

                                                    <?php

                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot style="font-size: 15px;background-color: indigo;color: white">
                                        <tr>
                                            <td class="info-box-text text-center">Total</td>
                                            <td class="text-center">
                                                <?php
                                                $total = sumarconsumohbqp($anio);
                                                $total = number_format($total, 0, ",", ".");
                                                ?>

                                                <p class="info-box-text text-center" style="font-size: 15px"><b><?php echo '' . $total . ' kWh.<br>'; ?></b></p>
                                            </td>
                                            <td>
                                                <?php
                                                $totalMonto = sumarmontototal($anio);
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
                        <?php
                    }
                    }
                    ?>

            </div>
        </div>
</div>