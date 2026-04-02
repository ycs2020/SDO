<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead class="bg-secondary">
                <tr>
                    <th>
                        <center>N°</center>
                    </th>
                    <th>
                        <center>Folio de Reporte
                            <center>
                    </th>
                    <th>
                        <center>Tecnico Encargado
                            <center>
                    </th>
                    <th>
                        <center>Fecha de Reporte
                            <center>
                    </th>
                    <th>
                        <center>Descargar Informe<br>de Turno
                            <center>
                    </th>
                    <th>
                        <center>Modificar Reporte
                            <center>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php
                $query = 'SELECT * FROM reportes_electricidad ORDER BY fechaReporte DESC';

                $res = mysqli_query($conexion, $query);

                $numReporte = 1;

                while ($row = $res->fetch_assoc()) {
                    ?>
                    <tr>
                        <td style="vertical-align:middle;">
                            <center><?php echo $numReporte; ?></center>
                        </td>
                        <td style="vertical-align:middle;" nowrap="nowrap" align="justify">
                            <?php
                            $nuevoNom = mb_strtoupper($row['folioReporte']);
                            echo $nuevoNom;

                            ?>
                        </td>
                        <td style="vertical-align:middle;" align="justify">
                            <?php
                            $nuevoNom = $row['nomEncargadoTurno'];
                            echo $nuevoNom;

                            ?>
                        </td>
                        <td style="vertical-align:middle;">
                            <CENTER><?php if ($row['fechaReporte'] == 0) {
                                    echo "No registra fecha";
                                } else {
                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaReporte'])));
                                    $inicioHora = date('H:i', strtotime($row['fechaReporte']));
                                    echo $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
                                } ?></CENTER>
                        </td>
                        <td style="vertical-align:middle;">
                            <center>
                                <?php
                                $idReporte = $row['idReporte'];
                                $idReporte = trim($idReporte);
                                if ($idReporte == 0 || $idReporte == "" || $idReporte == " " || $idReporte == "  ") {
                                    ?>
                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                    <?php
                                } else {

                                    ?>

                                    <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-Turno-Electrico.php" method="post">

                                        <input type="hidden" name="idReporte" value="<?php echo $idReporte; ?>">
                                        <button type="submit" id="inputMozi" class="btn btn-success rounded-circle rounded-sm"><i class="fas fa-download"></i></button>

                                    </form>

                                    <?php

                                }
                                ?>


                            </center>
                        </td>
                        <td style="vertical-align:middle;">
                            <center>
                                <?php
                                $idReporte = $row['idReporte'];
                                $idReporte = trim($idReporte);
                                $nomEncargadoTurno = $row['nomEncargadoTurno'];
                                $nomEncargadoTurnoLow = mb_convert_case($nomEncargadoTurno,MB_CASE_LOWER,'UTF-8');
                                $nomUsuarioLow = mb_convert_case($nomUsuario,MB_CASE_LOWER,'UTF-8');
                                if ($idReporte == 0 || $idReporte == "" || $idReporte == " " || $idReporte == "  ") {
                                    ?>
                                    <center><?php echo "Error al Cargar Datos."; ?></center>
                                    <?php
                                } else{

                                    if (strpos($nomEncargadoTurnoLow, $nomUsuarioLow) !== false) {

                                        ?>

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-personal/reportes_electricidad_modificar.php" method="post">

                                            <input type="hidden" name="idReporte" value="<?php echo $idReporte; ?>">
                                            <button type="submit" id="inputMozi" class="btn btn-warning rounded-circle rounded-sm"><i class="fas fa-cog"></i></button>


                                        </form>

                                        <?php
                                    } else {

                                        ?>

                                        <center><?php echo "Deshabilitado"; ?></center>

                                        <?php
                                    }

                                }
                                ?>


                            </center>
                        </td>

                    </tr>
                    <?php

                    $numReporte = $numReporte + 1;
                }
                ?>

                </tbody>
                <tfoot class="bg-secondary">
                <tr>
                    <th>
                        <center>N°</center>
                    </th>
                    <th>
                        <center>Folio de Reporte
                            <center>
                    </th>
                    <th>
                        <center>Tecnico Encargado
                            <center>
                    </th>
                    <th>
                        <center>Fecha de Reporte
                            <center>
                    </th>
                    <th>
                        <center>Descargar Informe<br>de Turno
                            <center>
                    </th>
                    <th>
                        <center>Modificar Informe
                            <center>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>