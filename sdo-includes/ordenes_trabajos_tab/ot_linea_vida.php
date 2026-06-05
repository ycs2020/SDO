<div class="card card-tabs card-secondary card-outline shadow-sm">
    <div class="card-header p-0 pt-1 bg-light">
        <ul class="nav nav-tabs px-3 font-weight-bold" style="font-size:13px;">
            <li class="nav-item"><a class="nav-link active text-primary" href="#paneAvance" data-toggle="tab">Avance de OT</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="#paneObsG" data-toggle="tab">Observaciones Generales</a></li>
            <li class="nav-item"><a class="nav-link text-secondary" href="#paneVida" data-toggle="tab">Línea de Vida Interna</a></li>
        </ul>
    </div>
    <div class="card-body p-2">
        <div class="tab-content">

            <div class="tab-pane fade show active" id="paneAvance">
                <div class="table-responsive">
                    <table id="observacionTotal" class="table table-bordered table-striped table-sm w-100" style="font-size: 13px;">
                        <thead style="background-color: indianred; color: white" class="text-center">
                        <tr><th>Fecha Ingreso</th><th>Creador</th><th>Estado</th><th>Avance</th><th>Obs</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlAv = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND detalleObs NOT LIKE ('%Se ha ingresado la orden de trabajo%')";
                        $resAv = mysqli_query($conexion, $sqlAv);
                        while ($rowAv = $resAv->fetch_assoc()) {
                            echo '<tr>
                                    <td class="text-center">'.$rowAv['fechaSubida'].'</td>
                                    <td class="text-center">'.$rowAv['nomCreador'].'</td>
                                    <td class="text-center">'.$rowAv['estadoAvance'].'</td>
                                    <td>'.nl2br(htmlspecialchars($rowAv['detalleObs'], ENT_QUOTES, 'UTF-8')).'</td>
                                    <td>'.(empty($rowAv['observacion']) ? 'NO APLICA' : nl2br(htmlspecialchars($rowAv['observacion'], ENT_QUOTES, 'UTF-8'))).'</td>
                                  </tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="paneObsG">
                <div class="table-responsive">
                    <table id="obsercacion2" class="table table-bordered table-striped table-sm w-100" style="font-size: 13px;">
                        <thead style="background-color: lightslategray; color: white" class="text-center">
                        <tr><th>Fecha</th><th>Creador</th><th>Obs</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlObs = "SELECT * FROM observacion_ot WHERE idTG = $idTG";
                        $resObs = mysqli_query($conexion, $sqlObs);
                        while ($rowObs = $resObs->fetch_assoc()) {
                            echo '<tr class="text-center">
                                    <td>'.$rowObs['fecha'].'</td>
                                    <td>'.$rowObs['nomCreador'].'</td>
                                    <td class="text-left">'.nl2br(htmlspecialchars($rowObs['detalleObs'], ENT_QUOTES, 'UTF-8')).'</td>
                                  </tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="paneVida">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm text-center w-100" style="font-size: 13px;">
                        <thead style="background-color: lightslategray; color: white">
                        <tr><th>Fecha</th><th>Creador</th><th>Detalle</th><th>Pedido a Pañol</th><th>Soli. Compra</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        $sqlLinea = "SELECT * FROM linea_vida_ot WHERE idTG = $idTG";
                        $resLinea = mysqli_query($conexion, $sqlLinea);
                        while ($rowLinea = $resLinea->fetch_assoc()) {
                            $idLineaVida = $rowLinea['idLineaVida'];
                            $respuestaPanol = $rowLinea['respuestaPanol'];
                            $rechazadoPanol = $rowLinea['rechazadoPanol'];

                            $pPanol = 'NO';
                            if ($respuestaPanol == 1) $pPanol = 'ENTREGA EXITOSA';
                            else if ($rechazadoPanol == 1) $pPanol = 'RECHAZADO POR PAÑOL';
                            else if ($rowLinea['isPanol'] == 1) $pPanol = 'SI';
                            ?>
                            <tr>
                                <td><?php echo $rowLinea['fechaSubida'] ?></td>
                                <td><?php echo $rowLinea['nomCreador'] ?></td>
                                <td class="text-left"><?php echo nl2br(htmlspecialchars($rowLinea['detalle'], ENT_QUOTES, 'UTF-8')) ?></td>
                                <td><?php echo $pPanol ?></td>
                                <td><?php echo ($rowLinea['isMaterialSoliCompra'] == 1) ? 'SI' : 'NO'; ?></td>
                                <td>
                                    <?php if ($respuestaPanol == 1) { ?>
                                        <button type="button" class="btn btn-success btn-xs px-2" data-toggle="modal" data-target="#exampleModalEyecheck<?php echo $idLineaVida ?>"><i class="fas fa-eye"></i></button>
                                        <div class="modal fade" id="exampleModalEyecheck<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content text-left">
                                                    <div class="modal-header bg-gradient-success py-2"><h6 class="modal-title text-white font-weight-bold">Entrega de Materiales</h6><button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
                                                    <div class="modal-body p-2">
                                                        <?php
                                                        $sqlAc = "SELECT * FROM aceptados_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                        $resAc = mysqli_query($conexion, $sqlAc);
                                                        while ($rowAc = $resAc->fetch_assoc()) { echo '<textarea readonly class="form-control form-control-sm" rows="10">' . $rowAc['detalle'] . '</textarea>'; }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } elseif ($rechazadoPanol == 1) { ?>
                                        <button type="button" class="btn btn-primary btn-xs px-2" data-toggle="modal" data-target="#exampleModalEye<?php echo $idLineaVida ?>"><i class="fas fa-eye"></i></button>
                                        <div class="modal fade" id="exampleModalEye<?php echo $idLineaVida ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content text-left">
                                                    <div class="modal-header bg-gradient-primary py-2"><h6 class="modal-title text-white font-weight-bold">Motivos del Rechazo</h6><button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
                                                    <div class="modal-body p-2">
                                                        <?php
                                                        $sqlRe = "SELECT * FROM rechazo_panol WHERE idTG = $idTG AND idLineaVida = $idLineaVida";
                                                        $resRe = mysqli_query($conexion, $sqlRe);
                                                        while ($rowRe = $resRe->fetch_assoc()) { echo '<textarea readonly class="form-control form-control-sm" rows="10">' . $rowRe['detalle'] . '</textarea>'; }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { echo 'NO APLICA'; } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>