<div class="card">
    <div class="card-header d-flex p-0 bg-success">
        <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
        <ul class="nav nav-pills ml-auto text-bold p-2 text-white">
            <?php
            date_default_timezone_set('America/Santiago');
            $nowAnio = date('Y', time());

            for ($i = 2; $i < 11; $i++) {
                ?>
                <li class="nav-item"><a class="nav-link text-white" href="#tabPabellon<?php echo $i; ?>" data-toggle="tab">Pabellon <?php echo $i; ?></a></li>

                <?php
            }

            ?>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <?php
            date_default_timezone_set('America/Santiago');
            $nowAnio = date('Y', time());

            for ($i = 2; $i < 11; $i++) {
                ?>

                <div class="tab-pane" id="tabPabellon<?php echo $i; ?>">
                    <div class="row">
                        <!-- HUMEDAD -->
                        <div class="col-12 col-md-4 col-sm-6 w-100 h-100">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <center>
                                        <h3 class="card-title"><i class="fas fa-cloud"></i><b> &nbsp;HUMEDAD</b></h3>
                                    </center>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width: 100%;">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th class="text-center w-50">FECHA</th>
                                                <th class="text-center w-50">PORCENTAJE %RH</th>
                                            </tr>
                                            </thead>
                                            <tbody style="width: 100%" class="table-bordered">
                                            <?php
                                            $sqlHumedad = "SELECT pabellon" . $i . "Humedad AS medicion , fechaHumedad FROM pabellones_humedades ORDER BY fechaHumedad DESC LIMIT 15";

                                            $resHumedad = mysqli_query($conexion, $sqlHumedad);

                                            while ($rowHumedad = $resHumedad->fetch_assoc()) {
                                                $fechaHumedad = $rowHumedad["fechaHumedad"];
                                                $medicion = $rowHumedad["medicion"];

                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaHumedad)));
                                                $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaHumedad)));

                                                $fechaOrden = $inicioFecha;
                                                $medicion = $medicion.' %RH';

                                                $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
                                                $medicion2 = mb_convert_case($medicion, MB_CASE_UPPER, 'UTF-8');

                                                ?>
                                                <tr style="width: 100%">
                                                    <td>
                                                        <?php echo $fechaOrden; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $medicion2; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN HUMEDAD -->

                        <!-- TEMPERATURA -->
                        <div class="col-12 col-md-4 col-sm-6 w-100 h-100">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <center>
                                        <h3 class="card-title"><i class="fas fa-thermometer-three-quarters"></i><b> &nbsp;TEMPERATURA</b></h3>
                                    </center>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width: 100%;">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th class="text-center w-50">FECHA</th>
                                                <th class="text-center w-50">TEMPERATURA °C</th>
                                            </tr>
                                            </thead>
                                            <tbody style="width: 100%" class="table-bordered">
                                            <?php
                                            $sqlTemperatura = "SELECT pabellon" . $i . "Temperatura AS medicion , fechaTemperatura FROM pabellones_temperatura ORDER BY fechaTemperatura DESC LIMIT 15";

                                            $resTemperatura = mysqli_query($conexion, $sqlTemperatura);

                                            while ($rowTemperatura = $resTemperatura->fetch_assoc()) {
                                                $fechaTemperatura = $rowTemperatura["fechaTemperatura"];
                                                $medicion = $rowTemperatura["medicion"];

                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTemperatura)));
                                                $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaTemperatura)));

                                                $fechaOrden = $inicioFecha;
                                                $medicion = $medicion.' °C';

                                                $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
                                                $medicion2 = mb_convert_case($medicion, MB_CASE_UPPER, 'UTF-8');

                                                ?>
                                                <tr style="width: 100%">
                                                    <td>
                                                        <?php echo $fechaOrden; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $medicion2; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN TEMPERATURA -->

                        <!-- PRESION -->
                        <div class="col-12 col-md-4 col-sm-6 w-100 h-100">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <center>
                                        <h3 class="card-title"><i class="fas fa-cogs"></i><b> &nbsp;PRESION</b></h3>
                                    </center>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width: 100%;">
                                            <thead style="font-size: 15px;background-color: #cf712d;color: white">
                                            <tr>
                                                <th class="text-center w-50">FECHA</th>
                                                <th class="text-center w-50">PRESION [Pa]</th>
                                            </tr>
                                            </thead>
                                            <tbody style="width: 100%" class="table-bordered">
                                            <?php
                                            $sqlPresion = "SELECT pabellon" . $i . "Presion AS medicion , fechaPresion FROM pabellones_presion ORDER BY fechaPresion DESC LIMIT 15";

                                            $resPresion = mysqli_query($conexion, $sqlPresion);

                                            while ($rowPresion = $resPresion->fetch_assoc()) {
                                                $fechaPresion = $rowPresion["fechaPresion"];
                                                $medicion = $rowPresion["medicion"];

                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $fechaPresion)));
                                                $inicioHora = date('H:i', strtotime(str_replace('/', '-', $fechaPresion)));

                                                $fechaOrden = $inicioFecha;
                                                $medicion = $medicion.' [Pa]';

                                                $fechaOrden = mb_convert_case($fechaOrden, MB_CASE_UPPER, 'UTF-8');
                                                $medicion2 = mb_convert_case($medicion, MB_CASE_UPPER, 'UTF-8');

                                                ?>
                                                <tr style="width: 100%">
                                                    <td>
                                                        <?php echo $fechaOrden; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $medicion2; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FIN PRESION -->
                    </div>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
</div>