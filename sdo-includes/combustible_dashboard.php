<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());

                $sqlAnios = "SELECT DISTINCT YEAR(fechaAC) AS anio FROM combustible_administracion ORDER BY fechaAC ASC;";

                $resAnios = mysqli_query($conexion, $sqlAnios);

                while ($row = $resAnios->fetch_assoc()) {
                    if ($row['anio'] == $nowAnio) {
                        ?>
                        <li class="nav-item"><a class="nav-link active" href="#tabComb<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabComb<?php echo $row['anio']; ?>" data-toggle="tab"><?php echo $row['anio']; ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnio = date('Y', time());

                $sqlAnios = "SELECT DISTINCT YEAR(fechaAC) AS anio FROM combustible_administracion ORDER BY fechaAC ASC;";

                $resAnios = mysqli_query($conexion, $sqlAnios);

                while ($row = $resAnios->fetch_assoc()) {
                    $anio = $row['anio'];

                    $anioAnterior = $anio - 1;
                    $nowMes = date('m', time());
                    if ($anio == $nowAnio) {
                        ?>
                        <div class="tab-pane active" id="tabComb<?php echo $row['anio']; ?>">
                            <div class="row">

                                <!-- CANTIDAD DE CARGAS -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;&nbsp;<b>Cantidad de Cargas en <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php
                                            for ($i = 1; $i < 13; $i++) {

                                                if ($i > $nowMes) {
                                                } else {
                                                    ?>
                                                    <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': ' . cargasMesSegunFecha($anio . '-0' . $i) . ' Cargas<br>';; ?></b></p>

                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <!-- FIN CANTIDAD DE CARGAS -->

                                <!-- CONSUMO DE COMBUSTIBLE -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Consumo Combustible Diesel <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php
                                            $sumaTotalConsumo = 0;

                                            for ($i = 1; $i < 13; $i++) {

                                                if ($i > $nowMes) {
                                                } else {

                                                    $t1Mes = obtenerConsumoMesT1($anio . '-' . $i);
                                                    $t2Mes = obtenerConsumoMesT2($anio . '-' . $i);
                                                    $t3Mes = obtenerConsumoMesT3($anio . '-' . $i);
                                                    $t4Mes = obtenerConsumoMesT4($anio . '-' . $i);


                                                    $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                    $sumaConsumo = -1 * $sumaConsumo;

                                                    $sumaTotalConsumo = $sumaTotalConsumo + $sumaConsumo;


                                                    $sumaConsumo = number_format($sumaConsumo, 0, ",", ".");
                                                    ?>


                                                    <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': ' . $sumaConsumo . ' Lts.<br>'; ?></b></p>

                                                    <?php
                                                }
                                            }


                                            ?>

                                            <br>

                                            <?php

                                            $sumaTotalConsumo = number_format($sumaTotalConsumo, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text"><b><?php echo 'Total Consumo: ' . $sumaTotalConsumo . ' Lts.<br>'; ?></b></p>

                                        </div>
                                    </div>
                                </div>
                                <!-- FIN CONSUMO DE COMBUSTIBLE -->

                                <!-- VALOR PROMEDIO -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Valor Promedio Gastado en <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">

                                            <?php

                                            $sumaTotalValor = 0;

                                            for ($i = 1; $i < 13; $i++) {

                                                if ($i > $nowMes) {
                                                } else {

                                                    $t1Mes = obtenerConsumoMesT1($anio . '-' . $i);
                                                    $t2Mes = obtenerConsumoMesT2($anio . '-' . $i);
                                                    $t3Mes = obtenerConsumoMesT3($anio . '-' . $i);
                                                    $t4Mes = obtenerConsumoMesT4($anio . '-' . $i);


                                                    $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                    $sumaConsumo = -1 * $sumaConsumo;

                                                    $valorPromConsumo = round(obtenerPromedioACMes2($anio . '-' . $i));

                                                    $valorTotalConsumo = round(obtenerPromedioACMes2($anio . '-' . $i)) * $sumaConsumo;

                                                    $sumaTotalValor = $sumaTotalValor + $valorTotalConsumo;

                                                    $valorTotalConsumo2 = number_format($valorTotalConsumo, 0, ",", ".");

                                                    $valorPromConsumo2 = number_format($valorPromConsumo, 0, ",", ".");

                                                    ?>


                                                    <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': $ ' . $valorTotalConsumo2 . ' con Valor Unitario $'.$valorPromConsumo2.'<br>'; ?></b></p>

                                                    <?php
                                                }
                                            }
                                            ?>

                                            <br>

                                            <?php

                                            $sumaTotalValor = number_format($sumaTotalValor, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text"><b><?php echo 'Gasto Total: $ ' . $sumaTotalValor . '<br>'; ?></b></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN VALOR PROMEDIO -->


                                <?php

                                date_default_timezone_set('America/Santiago');
                                $fechaActual = date('Y-m');
                                $fechaMesAnterior = date('Y-m', strtotime('-1 month', strtotime($fechaActual)));

                                $mesConsumo = date('m', strtotime($fechaActual));
                                $anioConsumo = date('Y', strtotime($fechaActual));

                                $mesAnteriorConsumo = date('m', strtotime($fechaMesAnterior));
                                $anioAnteriorConsumo = date('Y', strtotime($fechaMesAnterior));

                                ?>

                                <!-- CONSUMO MES ANTERIOR -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Consumo <?php echo obtenerMes($mesAnteriorConsumo) . " de " . $anioAnteriorConsumo; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php

                                            $now = date('Y-m', time());

                                            $anterior = date("Y-m", strtotime($now . "- 1 month"));

                                            $obtenerConsumoMesT1 = -1 * obtenerConsumoMesT1($anterior);
                                            $obtenerConsumoMesT2 = -1 * obtenerConsumoMesT2($anterior);
                                            $obtenerConsumoMesT3 = -1 * obtenerConsumoMesT3($anterior);
                                            $obtenerConsumoMesT4 = -1 * obtenerConsumoMesT4($anterior);

                                            $consumoT1 = number_format($obtenerConsumoMesT1, 0, ",", ".");
                                            $consumoT2 = number_format($obtenerConsumoMesT2, 0, ",", ".");
                                            $consumoT3 = number_format($obtenerConsumoMesT3, 0, ",", ".");
                                            $consumoT4 = number_format($obtenerConsumoMesT4, 0, ",", ".");

                                            echo '<p class="info-box-text"><b>Tanque 1: ' . $consumoT1 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 2: ' . $consumoT2 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 3: ' . $consumoT3 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 4: ' . $consumoT4 . ' Lts.</b></p>';

                                            ?>


                                        </div>
                                    </div>
                                </div>
                                <!-- FIN CONSUMO MES ANTERIOR -->

                                <!-- CONSUMO MES EN CURSO -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Consumo <?php echo obtenerMes($mesConsumo) . " de " . $anioConsumo; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php

                                            $obtenerConsumoMesT1 = -1 * obtenerConsumoMesT1($fechaActual);
                                            $obtenerConsumoMesT2 = -1 * obtenerConsumoMesT2($fechaActual);
                                            $obtenerConsumoMesT3 = -1 * obtenerConsumoMesT3($fechaActual);
                                            $obtenerConsumoMesT4 = -1 * obtenerConsumoMesT4($fechaActual);

                                            $consumoT1 = number_format($obtenerConsumoMesT1, 0, ",", ".");
                                            $consumoT2 = number_format($obtenerConsumoMesT2, 0, ",", ".");
                                            $consumoT3 = number_format($obtenerConsumoMesT3, 0, ",", ".");
                                            $consumoT4 = number_format($obtenerConsumoMesT4, 0, ",", ".");

                                            echo '<p class="info-box-text"><b>Tanque 1: ' . $consumoT1 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 2: ' . $consumoT2 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 3: ' . $consumoT3 . ' Lts.</b></p>';

                                            echo '<p class="info-box-text"><b>Tanque 4: ' . $consumoT4 . ' Lts.</b></p>';

                                            ?>


                                        </div>
                                    </div>
                                </div>
                                <!-- FIN CONSUMO MES EN CURSO -->

                                <!-- VARIACIÓN AÑO -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Variaci&oacute;n Combustible Año Anterior <?php echo $anioAnterior; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php
                                            $sumaTotalConsumo = 0;

                                            $anioAnteriorArr = array();

                                            for ($i = 1; $i < 13; $i++) {



                                                $t1Mes = obtenerConsumoMesT1($anioAnterior . '-' . $i);
                                                $t2Mes = obtenerConsumoMesT2($anioAnterior . '-' . $i);
                                                $t3Mes = obtenerConsumoMesT3($anioAnterior . '-' . $i);
                                                $t4Mes = obtenerConsumoMesT4($anioAnterior . '-' . $i);


                                                $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                $sumaConsumo = -1 * $sumaConsumo;

                                                $sumaTotalConsumo = $sumaTotalConsumo + $sumaConsumo;


                                                array_push($anioAnteriorArr, $sumaConsumo);
                                            }


                                            for ($i = 1; $i < 13; $i++) {

                                                if ($i > $nowMes) {
                                                } else {


                                                    $t1Mes = obtenerConsumoMesT1($anio . '-' . $i);
                                                    $t2Mes = obtenerConsumoMesT2($anio . '-' . $i);
                                                    $t3Mes = obtenerConsumoMesT3($anio . '-' . $i);
                                                    $t4Mes = obtenerConsumoMesT4($anio . '-' . $i);


                                                    $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                    $sumaConsumo = -1 * $sumaConsumo;

                                                    $sumaTotalConsumo = $sumaTotalConsumo + $sumaConsumo;

                                                    $valorArrayAnterior = $anioAnteriorArr[$i - 1];

//                                                                    $porcentAnioAnterior = (($sumaConsumo - $sumaAnterior)/$sumaAnterior)*100;
                                                    if ($sumaConsumo == 0) {
                                                        $porcentAnioAnterior = 0;
                                                    } else {
                                                        $porcentAnioAnterior = (($sumaConsumo - $valorArrayAnterior) / $valorArrayAnterior) * 100;


                                                    }

                                                    $colorPorcent = '';

                                                    $simboloSuma = '';

                                                    if ($porcentAnioAnterior > 0) {
                                                        $colorPorcent = 'text-blue';
                                                        $simboloSuma = '+ ';
                                                    } else if ($porcentAnioAnterior < 0) {
                                                        $colorPorcent = 'text-red';
                                                    } else {
                                                        $colorPorcent = '';
                                                    }


                                                    $sumaConsumo = number_format($sumaConsumo, 0, ",", ".");
                                                    ?>


                                                    <p class="info-box-text"><b><?php echo obtenerMes($i) . ': '; ?></b><b class=" <?php echo $colorPorcent; ?>"><?php echo $simboloSuma . round($porcentAnioAnterior, 2) . ' %.<br>'; ?></b></p>

                                                    <?php
                                                }
                                            }


                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN VARIACIÓN AÑO -->

                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="tab-pane" id="tabComb<?php echo $row['anio']; ?>">
                            <div class="row">

                                <!-- CANTIDAD DE CARGAS AÑOS ANTERIORES -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;&nbsp;<b>Cantidad de Cargas en <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php
                                            for ($i = 1; $i < 13; $i++) {

//
                                                ?>
                                                <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': ' . cargasMesSegunFecha($anio . '-0' . $i) . ' Cargas<br>';; ?></b></p>

                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <!-- FIN CANTIDAD DE CARGAS ANTERIORES -->

                                <!-- CONSUMO DE COMBUSTIBLE ANTERIORES -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Consumo Combustible Diesel <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">


                                            <?php
                                            $sumaTotalConsumo = 0;

                                            for ($i = 1; $i < 13; $i++) {



                                                $t1Mes = obtenerConsumoMesT1($anio . '-' . $i);
                                                $t2Mes = obtenerConsumoMesT2($anio . '-' . $i);
                                                $t3Mes = obtenerConsumoMesT3($anio . '-' . $i);
                                                $t4Mes = obtenerConsumoMesT4($anio . '-' . $i);


                                                $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                $sumaConsumo = -1 * $sumaConsumo;

                                                $sumaTotalConsumo = $sumaTotalConsumo + $sumaConsumo;


                                                $sumaConsumo = number_format($sumaConsumo, 0, ",", ".");
                                                ?>


                                                <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': ' . $sumaConsumo . ' Lts.<br>'; ?></b></p>

                                                <?php
                                            }


                                            ?>

                                            <br>

                                            <?php

                                            $sumaTotalConsumo = number_format($sumaTotalConsumo, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text"><b><?php echo 'Total Consumo: ' . $sumaTotalConsumo . ' Lts.<br>'; ?></b></p>

                                        </div>
                                    </div>
                                </div>
                                <!-- FIN CONSUMO DE COMBUSTIBLE ANTERIORES -->

                                <!-- VALOR PROMEDIO ANTERIORES -->
                                <div class="col-12 col-md-4 h-100">
                                    <div class="card card-success collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-gas-pump"></i>&nbsp;<b>Valor Promedio Gastado en <?php echo $anio; ?></b></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">

                                            <?php

                                            $sumaTotalValor = 0;

                                            for ($i = 1; $i < 13; $i++) {

                                                $t1Mes = obtenerConsumoMesT1($anio . '-' . $i);
                                                $t2Mes = obtenerConsumoMesT2($anio . '-' . $i);
                                                $t3Mes = obtenerConsumoMesT3($anio . '-' . $i);
                                                $t4Mes = obtenerConsumoMesT4($anio . '-' . $i);


                                                $sumaConsumo = $t1Mes + $t2Mes + $t3Mes + $t4Mes;

                                                $sumaConsumo = -1 * $sumaConsumo;

                                                $valorPromConsumo = round(obtenerPromedioACMes2($anio . '-' . $i));

                                                $valorTotalConsumo = round(obtenerPromedioACMes2($anio . '-' . $i)) * $sumaConsumo;

                                                $sumaTotalValor = $sumaTotalValor + $valorTotalConsumo;

                                                $valorTotalConsumo2 = number_format($valorTotalConsumo, 0, ",", ".");

                                                $valorPromConsumo2 = number_format($valorPromConsumo, 0, ",", ".");

                                                ?>


                                                <p class="info-box-text"><b><?php echo obtenerMes($i) . ' ' . $anio . ': $ ' . $valorTotalConsumo2 . ' con Valor Unitario $'.$valorPromConsumo2.'<br>'; ?></b></p>

                                                <?php
                                            }
                                            ?>

                                            <br>

                                            <?php

                                            $sumaTotalValor = number_format($sumaTotalValor, 0, ",", ".");
                                            ?>

                                            <p class="info-box-text"><b><?php echo 'Gasto Total: $ ' . $sumaTotalValor . '<br>'; ?></b></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIN VALOR PROMEDIO ANTERIORES -->


                            </div>
                        </div>
                        <?php
                    }

                }
                ?>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
        </div>
    </div>

</div>