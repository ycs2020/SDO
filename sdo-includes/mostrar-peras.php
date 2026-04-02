<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Pulsadores de Cama (NX0881) Reparados por Control Centralizado </h3>
        </div>
        <div class="card-body d-flex justify-content-center" style="padding: 20px;">
            <div class="tab-content text-center">
                <table class="table-hover table-bordered text-center" style="width: 100%; border-collapse: collapse;">
                     <thead>
                            <tr>
                                <th style="padding: 8px;background-color: #f2f2f2;">Valor Unitario (Nuevo Incluye IVA)</th>
                                <th style="padding: 8px;background-color: #f2f2f2;">Cantidad de Pulsadores de Cama (NX0881) Reparados</th>
                                <th style="padding: 8px;background-color: #f2f2f2;">Cantidad Total Ahorrada al Hospital</th>
                            </tr>
                     </thead>
                    <tbody>
                            <?php
                                $valorUni = 107088;
                                $sqlPulsadores = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE ('%Pulsador de Cama%')";
                                $resPulsadores = mysqli_query($conexion,$sqlPulsadores);
                                $conteo = 0;
                                while($rowPulsadores = $resPulsadores->fetch_assoc()){
                                    $idEquiposPuls = $rowPulsadores['id'];
                                    $sqlTecnica = "SELECT COUNT(*) AS total FROM ordenes_trabajos_general WHERE idEquipo = $idEquiposPuls";
                                    $resTecnica = mysqli_query($conexion,$sqlTecnica);
                                    while($rowTecnica = $resTecnica->fetch_assoc()){
                                        $total = $rowTecnica['total'];
                                        $conteo = $conteo + $total;
                                    }
                                }
                                $total = $valorUni * $conteo;

                            ?>
                    <tr>
                        <td style="padding: 8px;"><?php echo '$' . number_format($valorUni, 0, ",", "."); ?></td>
                        <td style="padding: 8px;"><?php echo $conteo; ?></td>
                        <td style="padding: 8px;"><?php echo '$' . number_format($total, 0, ",", ".");?></td>
                    </tr>
                    </tbody>
                </table>
                <img class="text-center" src="../dist/img/NX0881-p.png" height="400" width="400">

                <?php
                // Consulta SQL para obtener los datos de los equipos de pulsadores de cama
                $sqlPulsadoresCantidad = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo LIKE '%Pulsador de Cama%'";
                $resPulsadoresCantidad = mysqli_query($conexion, $sqlPulsadoresCantidad);

                // Array para almacenar los datos y ordenarlos posteriormente
                $pulsadores = array();

                // Recorrer los resultados de la consulta y almacenarlos en el array
                while ($rowPulsadoresCantidad = $resPulsadoresCantidad->fetch_assoc()) {
                    $idEquiposPulsadores = $rowPulsadoresCantidad['id'];
                    $recinto = $rowPulsadoresCantidad['recinto'];

                    // Obtener el total de hojas técnicas hechas
                    $sqlTecnicaCantidad = "SELECT COUNT(*) AS total FROM ordenes_trabajos_general WHERE idEquipo = $idEquiposPulsadores";
                    $resTecnicaCantidad = mysqli_query($conexion, $sqlTecnicaCantidad);
                    $rowTecnicaCantidad = $resTecnicaCantidad->fetch_assoc();
                    $totalCantidad = $rowTecnicaCantidad['total'];

                    // Obtener las hojas técnicas y sus detalles
                    $sqlHojasTecnicas = "SELECT * FROM ordenes_trabajos_general WHERE idEquipo = $idEquiposPulsadores ORDER BY idEquipo ASC";
                    $resHojaTecnicas = mysqli_query($conexion, $sqlHojasTecnicas);

                    $hojasTecnicas = array();
                    while($rowHojasTecnicas = $resHojaTecnicas->fetch_assoc()){
                        $hojasTecnicas[] = array(
                            'idOrden' => $rowHojasTecnicas['idOrden'],
                            'folioOrden' => $rowHojasTecnicas['folioOrden']
                        );
                    }

                    // Almacenar los datos en el array de pulsadores
                    $pulsadores[] = array(
                        'recinto' => $recinto,
                        'totalCantidad' => $totalCantidad,
                        'hojasTecnicas' => $hojasTecnicas
                    );
                }

                // Ordenar el array por la cantidad de hojas técnicas hechas
                usort($pulsadores, function($a, $b) {
                    return $b['totalCantidad'] <=> $a['totalCantidad'];
                });
                ?>

                <table class="table-hover table-bordered text-center" style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th>Cama</th>
                        <th>Cantidad de hojas técnicas hechas</th>
                        <th>Detalles de Hojas Técnicas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pulsadores as $pulsador) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pulsador['recinto']); ?></td>
                            <td class="<?php echo ($pulsador['totalCantidad'] >= 5) ? 'bg-danger' : ''; ?>">
                                <?php echo $pulsador['totalCantidad']; ?>
                            </td>
                            <td>
                                <?php foreach ($pulsador['hojasTecnicas'] as $hoja) { ?>
                                    Folio: <?php echo htmlspecialchars($hoja['folioOrden']); ?><br>
                                    <form target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="POST">
                                        <input type="hidden" id="idOrden" name="idOrden" value="<?php echo htmlspecialchars($hoja['idOrden']); ?>">
                                        <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                    </form>
                                    <hr>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>