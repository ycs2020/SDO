    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?> - N° Inventario - <?php echo $numeroInventario?> - N° Serie - <?php echo $numeroSerie?></h1>
                <?php
                if ($idEquipoPadre > 0) {

                    ?>
                    <form method="post" action="equipo.php" class="text-right">
                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipoPadre; ?>"/>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="post" action="directorio-equipo-inge-clinica.php" class="text-right">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                    </form>
                    <?php
                }

                $nomDetalleEquipo = $nombreEquipo;

                $nomDetalleCategoria = $nomCategoria;

                $nomDetalleCompleto = $nomDetalleEquipo . ' - ' . $nomDetalleCategoria;

                ?>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile w">
                <div class="text-center">
                    <?php

                    if (!empty($rutaImagen)) {
                        ?>
                        <img class=" img-fluid" src="<?php echo $rutaImagen; ?>" width="200px" alt="ECBOS">
                        <?php
                    } else {
                        ?>
                        <img class=" img-fluid" src="../dist/img/HBQP.jpg" width="200px" alt="Control Centralizado">
                        <?php
                    }

                    ?>

                </div>
                <br>
                <div class=" text-center">
                    <button type="button" data-toggle="modal" data-target="#modal-modImagen" id="inputMozi" class="btn bg-blue btn-sm"><i class="fas fa-edit"></i>&nbsp;&nbsp;Modificar Imagen</button>
                    <form method="post" action="../sdo-funciones/equipamiento_cambiarImagen.php" enctype="multipart/form-data">
                        <!-- Modal -->
                        <div class="modal fade" id="modal-modImagen" name="modal-modImagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title" id="exampleModalLabel"><b>Modificar Imagen de </b><?php echo $nombreEquipo; ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div style="text-align:left;" class="form-group">
                                            <label for="exampleInputBorder">Nombre de Equipo: <code>*</code></label>
                                            <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Equipo" value="<?php echo $nombreEquipo; ?>" required>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" required class="custom-file-input" id="imagenCategoriaEdita" name="imagenCategoriaEdita" aria-describedby="inputGroupFileAddon01" accept="image/*" onchange="mostrarNombreArchivo()">
                                                <label class="custom-file-label" for="imagenCategoriaEdita" id="labelImagen">Subir Imagen</label>
                                            </div>
                                        </div>

                                        <script>
                                            function mostrarNombreArchivo() {
                                                // Obtener el nombre del archivo seleccionado
                                                var nombreArchivo = document.getElementById("imagenCategoriaEdita").files[0].name;
                                                // Mostrar el nombre del archivo en el label
                                                document.getElementById("labelImagen").innerHTML = nombreArchivo;
                                            }
                                        </script>
                                        <div class="mt-4">
                                            <hr>
                                            <h4>Imagen Actual:</h4>
                                            <img class="img-fluid" id="img" src="<?php echo $rutaImagen; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="idEquipoCambiar" id="idEquipoCambiar" value="<?php echo $idEquipo ?>">
                                        <button type="submit" class="btn btn-primary">Cambiar Imagen</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>

                </div>
                <br>
                <h2 class="profile-username text-center text-bold">Equipo - <?php echo $nombreEquipo ?> </h2>
                <?php
                if ($buscarequipoPadre !== 'null') {
                    ?>
                    <h5 class="text-center text-bold text-muted">Equipo Padre - <?php echo $buscarequipoPadre ?></h5>
                    <?php
                }
                ?>
                <!--                                <canvas id="myChart" width="300" height="300"></canvas>-->

                <!-- AQUIIIIII -->

                <?php

                if ($contarambosinput > 0 && $contarambosoutput > 0) {
                    ?>
                    <br>
                    <h6 class="text-left text-bold">Entradas (digitales y analógicas) ocupadas: <?php echo $contarambosinput ?></h6>
                    <h6 class="text-left text-bold">Salidas (digitales y analógicas) ocupadas: <?php echo $contarambosoutput ?></h6>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-8 col-md-6 col-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <?php
                    if ($estadoEquipo == 'Operativo' || $estadoEquipo == 'operativo' || $estadoEquipo == 'OPERATIVO') {
                        $color = 'bg-gradient-success';
                    } else if ($estadoEquipo == 'Mantenimiento') {
                        $color = 'bg-gradient-warning';
                    } else if ($estadoEquipo == 'OperativoObs') {
                        $estadoEquipo = 'Operativo, Observacion';
                        $color = 'bg-gradient-orange';
                    } else {
                        $color = 'bg-gradient-danger';
                    }

                    ?>
                    <div class="col-xl-6 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block <?php echo $color ?> btn-lg" style="font-size: medium" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-edit"></i> <br> Estado Actual: <b><?php echo $estadoEquipo ?></b></i>
                        </button>
                    </div>
                    <div class="col-xl-6 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block btn-lg btn-primary" style="font-size: medium" data-toggle="modal" data-target="#exampleModal1">
                            <i class="fas fa-list"></i> <br>Observaciones del Equipo<br> </i>
                        </button>
                    </div>
                    <!--
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block btn-lg bg-purple" style="font-size: medium" data-toggle="modal" data-target="#modalTiempoFuncion">
                            <i class="fas fa-clock"></i> <br> Tiempo de Funcionamiento<br> </i>
                        </button>
                    </div>
                    -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header <?php echo $color ?>">
                                    <h5 class="modal-title" id="exampleModalLabel"><b>Cambiar el estado del equipo</b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formularioCambiaEstado" method="POST" action="../sdo-funciones/cambiaEstadoEquipo.php">
                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                        <input type="hidden" value="<?php echo $idCategoria ?>" name="idCategoria">
                                        <input type="hidden" value="<?php echo $nombreEquipo ?>" name="nombreEquipo">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="codigoBarra">Nuevo Estado:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-info"></i></span>
                                                    </div>
                                                    <select name="opcionEstado" id="opcionEstado" required class="form-control">
                                                        <?php
                                                        $fuera = 'Fuera de Servicio';
                                                        $ope = 'Operativo';
                                                        $man = 'Mantenimiento';
                                                        if ($estadoEquipo == 'Fuera de Servicio') {
                                                            ?>
                                                            <option value="<?php echo $fuera ?>" selected="selected">Fuera de Servicio</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                            <?php
                                                        } else if ($estadoEquipo == 'Mantenimiento') {
                                                            ?>
                                                            <option value="<?php echo $man ?>" selected="selected">Mantenimiento</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="Operativo">Operativo</option>
                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <option value="<?php echo $ope ?>" selected="selected">Operativo</option>
                                                            <option value="Mantenimiento">Mantenimiento</option>
                                                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                                                            <option value="OperativoObs">Operativo con Observacion</option>
                                                            <?php
                                                        }
                                                        ?>


                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="codigoBarra">Observaciones:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Estado</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando todas las observaciones de este equipo</b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                        <thead class="bg-info">
                                        <tr>
                                            <th scope="col" class="text-center">Usuario Observador</th>
                                            <th scope="col" class="text-center">Fecha</th>
                                            <th scope="col" class="text-center">Tipo</th>
                                            <th scope="col" class="text-center">Detalle</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos_respaldo WHERE idEquipo = $idEquipo";
                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                            $datos = [];
                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                            $idCambio = $datos['idCambio'];
                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                            $fecha = $datos['fecha'];
                                            $datos['estado'] = $rowObservaciones['estado'];
                                            $estado = $datos['estado'];
                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                            $observaciones = $datos['observaciones'];
                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                            $idEquipo = $datos['idEquipo'];
                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                            $idUsuarioCambia = $datos['idUsuarioCambia'];
                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                            $tipoUsuario = $datos['tipoUsuario'];
                                            $fecha = strtotime($fecha);
                                            $fechaFormateada = date('d/m/Y', $fecha);
                                            ?>
                                            <tr>
                                                <?php
                                                if ($tipoUsuario == 'administrativo') {
                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                    }

                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                        $nombre = $datos2['nomJefe'];
                                                    }

                                                } else if ($tipoUsuario == 'personal') {
                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                        $nombre = $datos2['nomPersonal'];
                                                    }
                                                } else if ($tipoUsuario == 'administrador') {
                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                        $nombre = $datos2['nomAdministrador'];

                                                    }

                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                        $nombre = $datos2['nomMesaAyuda'];

                                                    }
                                                } else if ($tipoUsuario == 'directiva') {
                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                    $res = mysqli_query($conexion, $sql);
                                                    while ($row2 = $res->fetch_assoc()) {
                                                        $datos2 = [];
                                                        $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                        $nombre = $datos2['nomDirectivo'];
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" style="font-size: 14px">
                                                    <?php
                                                    echo $nombre;
                                                    ?>

                                                </td>

                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <th scope="col" class="text-center">Usuario Observador</th>
                                        <th scope="col" class="text-center">Fecha</th>
                                        <th scope="col" class="text-center">Tipo</th>
                                        <th scope="col" class="text-center">Detalle</th>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalTiempoFuncion" tabindex="-1" role="dialog" aria-labelledby="modalTiempoFuncion" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-purple">
                                    <h5 class="modal-title" id="exampleModalLabel"><b>Tiempo de Funcionamiento</b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php

                                    $aniosFuera = 0;
                                    $mesesFuera = 0;
                                    $diasFuera = 0;
                                    $horasFuera = 0;
                                    $minutosFuera = 0;

                                    $aniosOperativo = 0;
                                    $mesesOperativo = 0;
                                    $diasOperativo = 0;
                                    $horasOperativo = 0;
                                    $minutosOperativo = 0;

                                    $aniosMantenimiento = 0;
                                    $mesesMantenimiento = 0;
                                    $diasMantenimiento = 0;
                                    $horasMantenimiento = 0;
                                    $minutosMantenimiento = 0;

                                    $aniosOperativoObs = 0;
                                    $mesesOperativoObs = 0;
                                    $diasOperativoObs = 0;
                                    $horasOperativoObs = 0;
                                    $minutosOperativoObs = 0;

                                    // Fecha actual
                                    $fechaActual = new DateTime();

                                    $sqlEquipamiento = "SELECT fechaRegistro FROM equipamiento_hbqp WHERE id = $idEquipo";
                                    $resEquipamiento = mysqli_query($conexion, $sqlEquipamiento);
                                    $rowEquipamiento = $resEquipamiento->fetch_assoc();
                                    $fechaRegistro = new DateTime($rowEquipamiento['fechaRegistro']);

                                    $diferenciaParaTotal = $fechaActual->diff($fechaRegistro);

                                    $aniosTotal = $diferenciaParaTotal->y;
                                    $mesesTotal = $diferenciaParaTotal->m;
                                    $diasTotal = $diferenciaParaTotal->d;
                                    $horasTotal = $diferenciaParaTotal->h;
                                    $minutosTotal = $diferenciaParaTotal->i;

                                    $cantidadHistorialEquipo = obtenerCantidadHistorialEquipo($idEquipo);

                                    $fraseTotal = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";

                                    if ($cantidadHistorialEquipo == 0) {

                                        if ($estado == 'Fuera de Servicio') {

                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseFuera = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                        } else if ($estado == 'Mantenimiento') {

                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseMantenimiento = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                        } else if ($estado == 'OperativoObs') {

                                            $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseObserva = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";

                                        } else {

                                            $fraseOperativo = "$aniosTotal Año(s), $mesesTotal Mes(es), $diasTotal Día(s), $horasTotal Hora(s), $minutosTotal Minuto(s)";
                                            $fraseFuera = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseMantenimiento = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                            $fraseObserva = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";

                                        }
                                    } else {

                                        $sqlBuscarEquipo = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = $idEquipo ORDER BY fecha ASC";

                                        $resBuscarEquipo = mysqli_query($conexion, $sqlBuscarEquipo);
                                        $fechaAnterior = clone $fechaRegistro; // Clonamos la fecha de registro

                                        $totalAnioFuera = 0;
                                        $totalMesFuera = 0;
                                        $totalDiaFuera = 0;
                                        $totalHoraFuera = 0;
                                        $totalMinutoFuera = 0;

                                        $totalAnioMantenimiento = 0;
                                        $totalMesMantenimiento = 0;
                                        $totalDiaMantenimiento = 0;
                                        $totalHoraMantenimiento = 0;
                                        $totalMinutoMantenimiento = 0;

                                        $totalAnioOperativoObs = 0;
                                        $totalMesOperativoObs = 0;
                                        $totalDiaOperativoObs = 0;
                                        $totalHoraOperativoObs = 0;
                                        $totalMinutoOperativoObs = 0;

                                        while ($rowBuscarEquipo = $resBuscarEquipo->fetch_assoc()) {
                                            $estadoBuscarEquipo = $rowBuscarEquipo['estado'];
                                            $fechaEstado = new DateTime($rowBuscarEquipo['fecha']);

                                            if ($estadoBuscarEquipo == 'Fuera de Servicio') {
                                                $diferenciaFuera = $fechaEstado->diff($fechaAnterior);

                                                $aniosFuera = $diferenciaFuera->format('%y');
                                                $mesesFuera = $diferenciaFuera->format('%m');
                                                $diasFuera = $diferenciaFuera->format('%d');
                                                $horasFuera = $diferenciaFuera->format('%h');
                                                $minutosFuera = $diferenciaFuera->format('%i');

                                                $totalAnioFuera += $aniosFuera;
                                                $totalMesFuera += $mesesFuera;
                                                $totalDiaFuera += $diasFuera;
                                                $totalHoraFuera += $horasFuera;
                                                $totalMinutoFuera += $minutosFuera;

                                            }

                                            if ($estadoBuscarEquipo == 'Mantenimiento') {
                                                $diferenciaMantenimiento = $fechaEstado->diff($fechaAnterior);

                                                $aniosMantenimiento = $diferenciaMantenimiento->format('%y');
                                                $mesesMantenimiento = $diferenciaMantenimiento->format('%m');
                                                $diasMantenimiento = $diferenciaMantenimiento->format('%d');
                                                $horasMantenimiento = $diferenciaMantenimiento->format('%h');
                                                $minutosMantenimiento = $diferenciaMantenimiento->format('%i');

                                                $totalAnioMantenimiento += $aniosMantenimiento;
                                                $totalMesMantenimiento += $mesesMantenimiento;
                                                $totalDiaMantenimiento += $diasMantenimiento;
                                                $totalHoraMantenimiento += $horasMantenimiento;
                                                $totalMinutoMantenimiento += $minutosMantenimiento;
                                            }

                                            if ($estadoBuscarEquipo == 'OperativoObs') {
                                                $diferenciaOperativoObs = $fechaEstado->diff($fechaAnterior);

                                                $aniosOperativoObs = $diferenciaOperativoObs->format('%y');
                                                $mesesOperativoObs = $diferenciaOperativoObs->format('%m');
                                                $diasOperativoObs = $diferenciaOperativoObs->format('%d');
                                                $horasOperativoObs = $diferenciaOperativoObs->format('%h');
                                                $minutosOperativoObs = $diferenciaOperativoObs->format('%i');

                                                $totalAnioOperativoObs += $aniosOperativoObs;
                                                $totalMesOperativoObs += $mesesOperativoObs;
                                                $totalDiaOperativoObs += $diasOperativoObs;
                                                $totalHoraOperativoObs += $horasOperativoObs;
                                                $totalMinutoOperativoObs += $minutosOperativoObs;
                                            }

                                            $fechaAnterior = clone $fechaEstado;

                                        }


                                        $fraseOperativo = "0 Año(s), 0 Mes(es), 0 Día(s), 0 Hora(s), 0 Minuto(s)";
                                        $fraseFuera = "$totalAnioFuera Año(s), $totalMesFuera Mes(es), $totalDiaFuera Día(s), $totalHoraFuera Hora(s), $totalMinutoFuera Minuto(s)";
                                        $fraseMantenimiento = "$totalAnioMantenimiento Año(s), $totalMesFuera Mes(es), $totalDiaMantenimiento Día(s), $totalHoraMantenimiento Hora(s), $totalMinutoMantenimiento Minuto(s)";
                                        $fraseObserva = "$totalAnioOperativoObs Año(s), $totalMesOperativoObs Mes(es), $totalDiaOperativoObs Día(s), $totalHoraOperativoObs Hora(s), $totalMinutoOperativoObs Minuto(s)";


                                    }

                                    ?>

                                    <table class="table-hover table-bordered" id="tabla-equipo-observacion" width="100%" style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th colspan="2"><h6>Historial de Cambio de Estado: <?php echo $idEquipo ?></h6></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Tiempo Total:</td>
                                            <td><?php echo $fraseTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo Operativo:</td>
                                            <td><?php echo $fraseOperativo; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo Operativo pero con Observaciones:</td>
                                            <td><?php echo $fraseObserva; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo En Mantenimiento:</td>
                                            <td><?php echo $fraseMantenimiento; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo Fuera de Servicio:</td>
                                            <td><?php echo $fraseFuera; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <?php


                                    ?>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <!--<form action="equipo-hojas-tecnicas-inge-clinica.php" method="post" style="display: inline">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?><br>Hojas Técnicas</button>
                        </form>-->
                        <form action="equipo-hojas-tecnicas.php" method="post" style="display: inline">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?><br>Hojas Técnicas</button>
                        </form>
                    </div>
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <form action="equipo-gastos-inge-clinica.php" method="post" style="display: inline">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-money-bill"></i><br>
                                <?php
                                if ($gastoTotal == 0) {
                                    echo '$ 0';
                                } else {
                                    echo moneda_chilena($gastoTotal);
                                }
                                ?>
                                <br>Gastos
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <!--<form action="equipo-solicitudes-compra-inge-clinica.php" method="post" style="display: inline">-->
                        <form action="equipo-solicitudes-compra.php" method="POST" style="display: inline">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-danger btn-lg"><i class="fas fa-cart-plus"></i><br>
                                <?php
                                $numero = 0;
                                if ($total == 0) {
                                    echo $numero;
                                } else {
                                    echo $total;
                                }
                                ?>
                                <br>Solicitudes de Compra
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <form action="equipo-archivos.php" method="post" style="display: inline">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-pink btn-lg"><i class="fas fa-archive"></i><br><?php echo $cantidadArchivos ?><br>Archivos Adjuntos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary d-flex p-0">
                <ul class="nav nav-pills mr-auto ml-auto p-2">
                    <!--                    <li class="nav-item"><a class="nav-link" href="#tabInfo" data-toggle="tab">Informaci&oacute;n Equipo</a></li>-->
                    <li class="nav-item"><a class="nav-link active text-white" href="#tabSubEquipo" data-toggle="tab">Info de todos los equipos &nbsp; <!--<span class="badge badge-danger"><?php echo $cantidadSubEquipos ?></span>--></a></li>
                    <li class="nav-item"><a class="nav-link  text-white" href="#tabMod" data-toggle="tab">Modificar Equipo</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabDocs" data-toggle="tab">Documentaci&oacute;n</a></li>
                    <?php

                    $modeloBuscar1 = 'ECB_300';
                    $modeloBuscar2 = 'ECB_400';
                    $modeloBuscar3 = 'ECB_600';

                    $pos1 = strpos($modeloCadena, $modeloBuscar1);
                    $pos2 = strpos($modeloCadena, $modeloBuscar2);
                    $pos3 = strpos($modeloCadena, $modeloBuscar3);
                    if ($pos1 === false) {
                        if ($pos2 === false) {
                            if ($pos3 === false) {
                            } else {
                                ?>
                                <li class="nav-item"><a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li class="nav-item"><a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a></li>
                            <?php
                        }
                    } else {
                        ?>

                        <li class="nav-item"><a class="nav-link text-white" href="#tabDetalle" data-toggle="tab">Detalle Controladores</a></li>
                        <?php
                    }

                    ?>
                    <!--<li class="nav-item"><a class="nav-link text-white" href="#tabPlan" data-toggle="tab">Planificaci&oacute;n</a></li>-->
                    <li class="nav-item"><a class="nav-link text-white" href="#tabHallazgos" data-toggle="tab">Hallazgos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabAccesorios" data-toggle="tab">Accesorios</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabInsumos" data-toggle="tab">Insumos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabRegistros" data-toggle="tab">Registro de Actividades</a></li>
                    <?php
                    if ($equipo_controla > 0) {
                        ?>
                        <li class="nav-item"><a class="nav-link text-white" href="#tabSistemas" data-toggle="tab">Observaciones Sistemas</a></li>
                        <?php
                    }

                    if (!empty($equipoSource)) {
                        ?>
                        <li class="nav-item"><a class="nav-link text-white" href="#tabAlarmasBMS" data-toggle="tab">Alarmas BMS</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane" id="tabInfo">
                        <div class="col-12">
                            <h5>Información</h5>
                        </div>
                    </div>
                    <div class="tab-pane " id="tabMod">
                        <div class="col-12">
                            <form action="../sdo-funciones/guardar-edicion-equipo-inge-clinica.php" method="post">
                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                <br>
                                <fieldset>
                                    <legend>Información Técnica</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-3 col-12">
                                            <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                            <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $identificadorUnico ?>" placeholder="Escriba el Identificador unico del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                            <input type="text" class="form-control form-control-sm" id="inputInventario" name="inputInventario" value="<?php echo $numeroInventario ?>" placeholder="Escriba el numero de inventario del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                            <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacion" value="<?php echo $idLicitacion ?>" placeholder="Escriba el id de litación del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                            <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra ?>" >
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                            <input type="text" class="form-control form-control-sm" name="inputMantenimiento" value="<?php echo $frecuenciaMantenimiento ?>"  id="inputMantenimiento">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                            <input type="date" class="form-control form-control-sm" name="fechaRecepcion" value="<?php echo $fechaRecepcion ?>" id="fechaRecepcion">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                            <input type="date" class="form-control form-control-sm" name="fechaCompra" value="<?php echo $fechaCompra ?>" id="fechaCompra" name="fechaCompra">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                            <input type="date" class="form-control form-control-sm" name="fechaMarcha" value="<?php echo $fechaMarcha ?>" id="fechaMarcha" name="fechaMarcha">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="clase" style="font-size: 12px;">Clase:</label>
                                            <input type="text" class="form-control form-control-sm" id="clase" value="<?php echo $clase ?>" name="clase">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                            <input type="text" class="form-control form-control-sm" id="subClase" value="<?php echo $subclase ?>" name="subClase">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                            <input type="text" class="form-control form-control-sm" id="complejidad" value="<?php echo $complejidadTec ?>" name="complejidad">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                            <input type="text" class="form-control form-control-sm" id="riesgo" value="<?php echo $nivelRiesgo ?>" name="riesgo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                            <input type="text" class="form-control form-control-sm" id="costoMantencion"  value="<?php echo $costoMantAnual ?>" name="costoMantencion">
                                        </div>
                                    </div>
                                </fieldset>
                                <hr>
                                <fieldset>
                                    <legend>Información Básica</legend>

                                    <div class="form-row">
                                        <div class="form-group col-md-3 col-12">
                                            <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                            <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipo" value="<?php echo $nombreEquipo ?>" placeholder="Escriba el nombre del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                            <input type="number" class="form-control form-control-sm" id="valorConIva" value="<?php echo $valorConIva ?>" name="valorConIva">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                            <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" value="<?php echo $marca ?>" placeholder="Escriba la marca del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                            <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" value="<?php echo $modelo ?>" placeholder="Escriba el modelo del equipo">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                            <input type="text" class="form-control form-control-sm" id="fechaFabricación" value="<?php echo $AhnoFrabricacion ?>" name="fechaFabricación">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                            <input type="text" class="form-control form-control-sm" id="vidaUtil" value="<?php echo $vidaUtil ?>" name="vidaUtil">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                            <input type="text" class="form-control form-control-sm" id="versionSoftware" value="<?php echo $versionSoftware ?>" name="versionSoftware">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                            <input type="text" class="form-control form-control-sm" id="paisOrigen" value="<?php echo $paisOrigen ?>" name="paisOrigen">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                            <input type="text" class="form-control form-control-sm" id="terminoGarantia" value="<?php echo $terminoGarantia ?>" name="terminoGarantia">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                            <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $numeroSerie  ?>" name="numSerie">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Año de adquisicion:</label>
                                            <input type="text" class="form-control form-control-sm" id="ahnoAdquisicion" value="<?php echo $ahnoAdquisicion  ?>" name="ahnoAdquisicion">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Propiedades:</label>
                                            <input type="text" class="form-control form-control-sm" id="propiedades" value="<?php echo $propiedades  ?>" name="propiedades">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Evaluaciones:</label>
                                            <input type="text" class="form-control form-control-sm" id="evaluaciones" value="<?php echo $evaluaciones  ?>" name="evaluaciones">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Nombre Del Proovedor:</label>
                                            <input type="text" class="form-control form-control-sm" id="nombreProovedor" value="<?php echo $nombreProovedor  ?>" name="nombreProovedor">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Mantenimientos:</label>
                                            <input type="text" class="form-control form-control-sm" id="mantenimientos" value="<?php echo $mantenimientos  ?>" name="nombreProovedor">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">En Garantía:</label>
                                            <input type="text" class="form-control form-control-sm" id="enGarantia" value="<?php echo $enGarantia  ?>" name="enGarantia">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Vida Util Residual:</label>
                                            <input type="text" class="form-control form-control-sm" id="vidaUtilResidual" value="<?php echo $vidaUtilResidual  ?>" name="vidaUtilResidual">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Bajo Plan de Mantenimiento:</label>
                                            <input type="text" class="form-control form-control-sm" id="bajoPlanMantenimiento" value="<?php echo $bajoPlanMantenimiento  ?>" name="bajoPlanMantenimiento">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">ID Convenio Mantenimiento:</label>
                                            <input type="text" class="form-control form-control-sm" id="idConvenioMantenimiento" value="<?php echo $idConvenioMantenimiento  ?>" name="idConvenioMantenimiento">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Costo Anual del Mantenimiento:</label>
                                            <input type="text" class="form-control form-control-sm" id="costoAnualMantenimiento" value="<?php echo $costoAnualMantenimiento  ?>" name="costoAnualMantenimiento">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Frecuencia Anual del Mantenimiento:</label>
                                            <input type="text" class="form-control form-control-sm" id="frecuenciaAnualMantenimiento" value="<?php echo $frecuenciaAnualMantenimiento  ?>" name="frecuenciaAnualMantenimiento">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Pagina Web:</label>
                                            <input type="text" class="form-control form-control-sm" id="paginaWeb" name="paginaWeb" value="<?php echo $paginaWeb?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Mail:</label>
                                            <input type="text" class="form-control form-control-sm" id="mail" name="mail" value="<?php echo $mail?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Telefono:</label>
                                            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" value="<?php echo $telefono?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Dirección:</label>
                                            <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" value="<?php echo $direccion?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Año puesta de funcionamiento:</label>
                                            <input type="text" class="form-control form-control-sm" id="ahnoFuncionamiento" name="ahnoFuncionamiento" value="<?php echo $ahnoFuncionamiento?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Factura:</label>
                                            <input type="text" class="form-control form-control-sm" id="factura" name="factura" value="<?php echo $factura?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="numSerie" style="font-size: 12px;">Año de Instalación:</label>
                                            <input type="text" class="form-control form-control-sm" id="ahnoInstalacion" name="ahnoInstalacion" value="<?php echo $ahnoInstalacion?>">
                                        </div>
                                        <?php
                                        if($tipoControlador == 'esclavo'){
                                            ?>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                <input type="text" class="form-control form-control-sm" id="device" value="<?php echo $device  ?>" name="device">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Network:</label>
                                                <input type="text" class="form-control form-control-sm" id="network" value="<?php echo $network ?>" name="network">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Mac Address:</label>
                                                <input type="text" class="form-control form-control-sm" id="mac" value="<?php echo $mac ?>" name="mac">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">TCC:</label>
                                                <input type="text" class="form-control form-control-sm" id="tcc" value="<?php echo $tcc ?>" name="tcc">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Ejes TCC:</label>
                                                <input type="text" class="form-control form-control-sm" id="ejestcc" value="<?php echo $ejesTCC ?>" name="ejestcc">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Alimentación TCC:</label>
                                                <input type="text" class="form-control form-control-sm" id="alimentaciontcc" value="<?php echo $alimentacionTCC ?>" name="alimentaciontcc">
                                            </div>

                                            <?php
                                        }
                                        if($tipoControlador == 'maestro'){
                                            ?>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                <input type="text" class="form-control form-control-sm" id="ejesECBOSS" value="<?php echo $ejesECBoss  ?>" name="ejesECBOSS">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Ejes Rack:</label>
                                                <input type="text" class="form-control form-control-sm" id="ejesRack" value="<?php echo $ejesRack  ?>" name="ejesRack">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Rack:</label>
                                                <input type="text" class="form-control form-control-sm" id="rack" value="<?php echo $rack  ?>" name="rack">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Puerto:</label>
                                                <input type="text" class="form-control form-control-sm" id="puerto" value="<?php echo $puerto ?>" name="puerto">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Direccion IP:</label>
                                                <input type="text" class="form-control form-control-sm" id="direccionIP" value="<?php echo $direccionIP  ?>" name="direccionIP">
                                            </div>
                                            <?php
                                        }

                                        ?>

                                    </div>
                                </fieldset>
                                <hr>
                                <fieldset>
                                    <legend>Ubicación</legend>
                                    <div class="form-row">
                                        <div class="form-group col-md-3 col-12">
                                            <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                            <select class="form-control form-control-sm" id="servicio" required name="servicio">
                                                <option value="" disabled selected>-- Seleccione --</option>

                                                <?php

                                                $sqlServicios = "SELECT * FROM servicios";
                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                    $datos = [];

                                                    $datos['idServicio'] = $rowServicios['idServicio'];
                                                    $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                    $idServicio  = $datos['idServicio'];
                                                    $nomServicio  = $datos['nomServicio'];

                                                    if($idServicio == $servicioDB){
                                                        ?>
                                                        <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                            <input type="text" class="form-control form-control-sm" id="recinto" name="recinto" value="<?php echo $recinto ?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="pma" style="font-size: 12px;">PMA:</label>
                                            <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $pma  ?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                            <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas" value="<?php echo $coordenadas ?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="coordenadas" style="font-size: 12px;">Piso:</label>
                                            <input type="text" class="form-control form-control-sm" id="piso" name="piso" value="<?php echo $piso ?>">
                                        </div>
                                    </div>
                                </fieldset>
                                <hr>
                                <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $tipoControlador?>">
                                <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane active" id="tabSubEquipo">
                        <div class="col-12">
                            <br>
                            <div id="tablaequiposInge" class="table-responsive">
                                <table id="tabla-equipos-inge" class="table table-hover  table-bordered table-sm">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <th scope="col" class="text-center">ID</th>
                                        <?php
                                        if ($idCategoria == 2) {
                                            ?>
                                            <th scope="col" class="text-center">Ver Puntos</th>
                                            <?php
                                        }
                                        ?>
                                        <th scope="col" class="text-center">Nombre Equipo</th>
                                        <th scope="col" class="text-center">Servicio</th>
                                        <th scope="col" class="text-center">Recinto</th>
                                        <th scope="col" class="text-center">N° Inventario</th>
                                        <th scope="col" class="text-center">N° Serie</th>
                                        <th scope="col" class="text-center">PMA</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE nombreEquipo = '$nombreEquipo' ORDER BY piso ASC, pma ASC";
                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);

                                    $numExpandir = 1;

                                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                        $datos = [];
                                        $datos['id'] = $rowEquipo['id'];
                                        $datos['identificadorUnico'] = $rowEquipo['identificadorUnico'];
                                        $datos['nombreEquipo'] = $rowEquipo['nombreEquipo'];
                                        $datos['idServicio'] = $rowEquipo['idServicio'];
                                        $datos['recinto'] = $rowEquipo['recinto'];
                                        $datos['pma'] = $rowEquipo['pma'];
                                        $datos['piso'] = $rowEquipo['piso'];
                                        $datos['estadoEquipo'] = $rowEquipo['estadoEquipo'];
                                        $datos['modelo'] = $rowEquipo['modelo'];

                                        $idEquipoGeneral = $datos['id'];
                                        $identificadorUnico = $datos['identificadorUnico'];
                                        $nombreEquipoEqui = $datos['nombreEquipo'];
                                        $idServicio = $datos['idServicio'];
                                        $recinto = $datos['recinto'];
                                        $pma = $datos['pma'];
                                        $piso = $datos['piso'];
                                        $estadoEquipo = $datos['estadoEquipo'];
                                        $modelo = $datos['modelo'];
                                        $numeroInventarioequipo = $rowEquipo['numeroInventario'];
                                        $numeroSerieequipo = $rowEquipo['numeroSerie'];

                                        if ($recinto == '') {
                                            $recinto = '-';
                                        }

                                        if ($pma == '') {
                                            $pma = '-';
                                        }

                                        if ($piso == '') {
                                            $piso = '-';
                                        }


                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $numExpandir; ?></td>
                                            <!-- <td class="text-center"><?php echo $identificadorUnico ?></td> -->
                                            <?php
                                            if ($idCategoria == 2) {

                                                ?>
                                                <td class="text-center">
                                                    <button class="bg-primary btn btn-block" data-toggle="modal" data-target="#mostrarPuntos<?php echo $idEquipoGeneral ?>">Ver Puntos</button>
                                                    <div class="modal fade" id="mostrarPuntos<?php echo $idEquipoGeneral ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">CONTROLADOR: <?php echo $nombreEquipoEqui ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php


                                                                    // Encontrar la posición del texto 'ECB600_'
                                                                    $posicion_inicio = strpos($nombreEquipoEqui, 'ECB');
                                                                    // Si se encuentra 'ECB600_', obtener la subcadena desde esa posición hasta el siguiente espacio
                                                                    if ($posicion_inicio !== false) {
                                                                        $posicion_inicio += strlen('ECB'); // Mover la posición de inicio después de 'ECB600_'
                                                                        $posicion_fin = strpos($nombreEquipoEqui, ' ', $posicion_inicio); // Encontrar la posición del siguiente espacio
                                                                        if ($posicion_fin !== false) {
                                                                            $texto_deseado = substr($nombreEquipoEqui, $posicion_inicio, $posicion_fin - $posicion_inicio);
                                                                            $nombreEquipoEqui = 'ECB' . $texto_deseado; // Esto mostrará '1042'
                                                                        }
                                                                    }


                                                                    if (strpos($nombreEquipoEqui, 'ECB') === 0) {
                                                                        // Reemplazar "_" con "-"
                                                                        $nombreEquipoEqui = str_replace('_', '-', $nombreEquipoEqui);

                                                                        // Agregar un espacio después de "ECB"
                                                                        $nombreEquipoEqui = str_replace('ECB', 'ECB ', $nombreEquipoEqui);

                                                                        $nombreEquipoEqui = trim($nombreEquipoEqui);
                                                                    }


                                                                    ?>
                                                                    <table id="tabla-puntos" class="table-hover table-bordered">
                                                                        <thead class="bg-secondary">
                                                                        <tr>
                                                                            <th>
                                                                                <center></center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Piso
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>TCC
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Controlador
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>N&uacute;mero de Entrada
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Digital Input (UI)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Analog Input (UI)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>N&uacute;mero de Salida
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Digital Output (UO)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Analog Output (UO)
                                                                                    <center>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $querySelectEquiposs = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "' . $nombreEquipoEqui . '" ORDER BY idControlBMS ASC';
                                                                        $resSelectEquipos = mysqli_query($conexion, $querySelectEquiposs);
                                                                        $numsad = 1;
                                                                        while ($rowSelectEquipos = $resSelectEquipos->fetch_assoc()) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $numsad ?></td>
                                                                                <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                                                    <?php
                                                                                    $piso_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['piso_ControlBMS']);
                                                                                    echo $piso_ControlBMSExpandir;
                                                                                    ?>
                                                                                </td>
                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $tcc_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['tcc_ControlBMS']);
                                                                                    echo $tcc_ControlBMSExpandir;
                                                                                    ?>
                                                                                </td>
                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $controlador_ControlBMSExpandir = mb_strtoupper($rowSelectEquipos['controlador_ControlBMS']);
                                                                                    echo $controlador_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $numEntrada_ControlBMSExpandir = $rowSelectEquipos['numEntrada_ControlBMS'];
                                                                                    echo $numEntrada_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $digitalInput_ControlBMSExpandir = $rowSelectEquipos['digitalInput_ControlBMS'];
                                                                                    echo $digitalInput_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $analogInput_ControlBMSExpandir = $rowSelectEquipos['analogInput_ControlBMS'];
                                                                                    echo $analogInput_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $numSalida_ControlBMSExpandir = $rowSelectEquipos['numSalida_ControlBMS'];
                                                                                    echo $numSalida_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $digitalOutput_ControlBMSExpandir = $rowSelectEquipos['digitalOutput_ControlBMS'];
                                                                                    echo $digitalOutput_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align:middle;" align="center">
                                                                                    <?php
                                                                                    $analogOutput_ControlBMSExpandir = $rowSelectEquipos['analogOutput_ControlBMS'];
                                                                                    echo $analogOutput_ControlBMSExpandir;

                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            $numsad++;
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                        <tfoot class="bg-secondary">
                                                                        <tr>
                                                                            <th>
                                                                                <center>N°</center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Piso
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>TCC
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Controlador
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>N&uacute;mero de Entrada
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Digital Input (UI)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Analog Input (UI)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>N&uacute;mero de Salida
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Digital Output (UO)
                                                                                    <center>
                                                                            </th>
                                                                            <th>
                                                                                <center>Analog Output (UO)
                                                                                    <center>
                                                                            </th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                    <?php
                                                                    ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php
                                            }

                                            $servicio = "SELECT * FROM servicios WHERE idServicio = $idServicio";
                                            $res = mysqli_query($conexion, $servicio);
                                            while ($rowServicio = $res->fetch_assoc()) {
                                                $nombreServicio = $rowServicio['nomServicio'];
                                            }
                                            ?>

                                            <td class="text-center"><?php echo $nombreEquipoEqui ?></td>
                                            <td class="text-center"><?php echo $nombreServicio ?></td>
                                            <td class="text-center"><?php echo $recinto ?></td>
                                            <td class="text-center">
                                                <?php
                                                echo $numeroInventarioequipo;
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    echo $numeroSerieequipo;
                                                ?>
                                            </td>
                                            <td class="text-center"><?php echo $pma ?></td>
                                            <td class="text-center">
                                                <?php
                                                    if($idEquipo == $idEquipoGeneral){
                                                    }else{
                                                        ?>
                                                        <form action="equipo.php" method="POST" style="display: inline">
                                                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipoGeneral ?>">
                                                            <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-door-open"></i></button>
                                                        </form>
                                                        <?php
                                                    }
                                                ?>
                                                <form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento-Inge-Clinica.php" target="_blank" method="POST" style="display: inline">
                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipoGeneral ?>">
                                                    <button type="submit" title="Hoja de vida del equipo por ingeniería clinica" class="btn btn-success btn-sm"><i class="fa-solid fa-file"></i></button>
                                                </form>
                                                <form action="../sdo-pdf/SDO-Registro-Actividad-Inge-Clinica.php" method="POST" target="_blank" style="display: inline">
                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipoGeneral ?>">
                                                    <button type="submit" title="Registros de actividades del equipo por ingeniería clinica" class="btn btn-primary btn-sm"><i class="fa-solid fa-file"></i></button>
                                                </form>
                                                <!--<form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php" target="_blank" method="POST" style="display: inline;">
                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipo ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="far fa-file"></i></button>
                                                </form>
                                                -->
                                            </td>
                                        </tr>
                                        <?php
                                        $numExpandir++;
                                    }


                                    ?>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                    <tr>
                                        <th scope="col" class="text-center">ID</th>
                                        <?php
                                        if ($idCategoria == 2) {
                                            ?>
                                            <th scope="col" class="text-center">Ver Puntos</th>
                                            <?php
                                        }
                                        ?>
                                        <th scope="col" class="text-center">Nombre Equipo</th>
                                        <th scope="col" class="text-center">Servicio</th>
                                        <th scope="col" class="text-center">Recinto</th>
                                        <th scope="col" class="text-center">N° Inventario</th>
                                        <th scope="col" class="text-center">N° Serie</th>
                                        <th scope="col" class="text-center">PMA</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                    </tfoot>

                                </table>


                                <?php


                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabDocs">
                        <div class="col-12">
                            <div class="row">
                                <fieldset class="border p-3 text-center" style="width: 20%">
                                    <p><b>Listado de Documentación:</b></p>
                                    <hr>
                                    <button type="button" title="Agregar un manual" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarmanual"><i class="fas fa-plus"></i></button>
                                    <hr>
                                    <?php
                                    $sqlArchivos = "SELECT * FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
                                    $resArchivos = mysqli_query($conexion, $sqlArchivos);
                                    $count = 1;
                                    while ($rowArchivos = $resArchivos->fetch_assoc()) {
                                        $nomArchivo = $rowArchivos['nomArchivo'];
                                        $rutaArchivo = $rowArchivos['rutaArchivo'];
                                        $idArchivo = $rowArchivos['id'];
                                        $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);

                                        ?>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <?php
                                                if ($extension == 'pdf') {
                                                    ?>
                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')"><i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivo ?></button>
                                                    <?php
                                                } else if ($extension == 'jpg' || $extension == 'png') {
                                                    ?>
                                                    <button class="btn bg-gradient-info" onclick="cambiarPDF('<?php echo $rutaArchivo; ?>')"><i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivo ?></button>
                                                    <?php
                                                }
                                                ?>
                                                <!--<button type="button" title="Modificar el manual" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modificarmanual<?php echo $idArchivo ?>"><i class="fas fa-pen"></i></button>-->
                                                <div class="modal fade" id="modificarmanual<?php echo $idArchivo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-warning">
                                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Nombre del Manual</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="../sdo-funciones/modificar-nomManueales.php" method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12 col-12">
                                                                            <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                                </div>
                                                                                <input type="text" class="form-control form-control-sm" required id="nomArchivoModi" name="nomArchivoModi" value="<?php echo $nomArchivo ?>">
                                                                                <input type="hidden" name="id" id="id" value="<?php echo $idArchivo ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    <button type="submit" class="btn btn-success">Modificar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-danger btn-sm eliminarManual" id="<?php echo $idArchivo ?>"><i class="fas fa-trash-alt"></i></button>
                                                <hr>
                                            </li>
                                        </ul>
                                        <br>
                                        <?php
                                    }
                                    ?>
                                </fieldset>
                                <fieldset style="width: 80%" class="border p-3 text-center">
                                    <?php
                                    if ($extension == 'pdf') {
                                        ?>
                                        <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="900px"/>
                                        <?php
                                    } else if ($extension == 'jpg' || $extension == 'png') {
                                        ?>
                                        <img id="pdfViewer" src=""/>
                                        <?php
                                    }
                                    ?>
                                </fieldset>
                            </div>
                            <script>
                                function cambiarPDF(ruta) {
                                    document.getElementById('pdfViewer').setAttribute('src', ruta);
                                }
                            </script>
                            <div class="modal fade" id="agregarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title" id="exampleModalLabel">Formulario - Información del archivo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomDetalleEquipo ?>">
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 col-12">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                            </div>
                                                            <select class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                <?php
                                                                $sql = "SELECT * FROM tiposarchivos";
                                                                $res = mysqli_query($conexion, $sql);
                                                                while ($row = $res->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['id '] = $row['id'];
                                                                    $datos['nomTipo'] = $row['nomTipo'];
                                                                    $id = $datos['id'];
                                                                    $nomTipo = $datos['nomTipo'];
                                                                    ?>
                                                                    <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case($nomTipo, MB_CASE_UPPER, 'UTF-8') ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <option value="Otros">Otro</option>
                                                                <?php
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                    </div>
                                                    <div class="form-group col-md-12 col-12">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <input type="file" class="form-control form-control-sm" required id="archivoAdjunto" name="archivoAdjunto">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-success">Añadir Archivo</button>
                                            </div>
                                        </form>
                                        <input type="hidden" id="idCategoriaBuscar" name="idCategoriaBuscar" value="<?php echo $idCategoria ?>">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabHallazgos">
                        <div class="col-12">
                            <div class="row">
                                <fieldset class="border p-3 text-center" style="width: 20%">
                                    <p><b>Listado de Hallazgo:</b></p>
                                    <hr>
                                    <button type="button" title="Agregar un hallazgo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#agregarhallazgo"><i class="fas fa-plus"></i></button>
                                    <hr>
                                    <?php
                                    $sqlHallazgos = "SELECT * FROM archivos_equipamiento_hbqp_hallazgos WHERE idEquipo = $idEquipo";
                                    $resHallazgos = mysqli_query($conexion, $sqlHallazgos);
                                    while ($rowHallazgos = $resHallazgos->fetch_assoc()) {
                                        $nomArchivoHallazgo = $rowHallazgos['nomArchivo'];
                                        $rutaArchivoHallazgo = $rowHallazgos['rutaArchivo'];
                                        $idArchivoHallazgo = $rowHallazgos['id'];
                                        $extensionHallazgo = pathinfo($rutaArchivoHallazgo, PATHINFO_EXTENSION);
                                        ?>
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <?php
                                                if ($extensionHallazgo == 'pdf') {
                                                    ?>
                                                    <button class="btn bg-gradient-info" onclick="cambiarPDFHallazgo('<?php echo $rutaArchivoHallazgo; ?>')"><i class="fas fa-file-pdf"></i>&nbsp;<?php echo $nomArchivoHallazgo ?></button>
                                                    <?php
                                                } else if ($extensionHallazgo == 'jpg' || $extensionHallazgo == 'png') {
                                                    ?>
                                                    <button class="btn bg-gradient-info" onclick="cambiarPDFHallazgo('<?php echo $rutaArchivoHallazgo; ?>')"><i class="fas fa-image"></i>&nbsp;<?php echo $nomArchivoHallazgo ?></button>
                                                    <?php
                                                }
                                                ?>
                                                <button class="btn btn-danger btn-sm eliminarhallazgo" id="<?php echo $idArchivoHallazgo ?>"><i class="fas fa-trash-alt"></i></button>
                                                <hr>
                                            </li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </fieldset>
                                <fieldset style="width: 80%" class="border p-3 text-center">
                                    <?php
                                    if ($extensionHallazgo == 'pdf') {
                                        ?>
                                        <embed id="pdfViewerHallazgo" src="" type="application/pdf" width="100%" height="900px"/>
                                        <?php
                                    } else if ($extensionHallazgo == 'jpg' || $extensionHallazgo == 'png') {
                                        ?>
                                        <img id="pdfViewerHallazgo" src=""/>
                                        <?php
                                    }
                                    ?>
                                </fieldset>
                            </div>
                            <script>
                                function cambiarPDFHallazgo(ruta) {
                                    document.getElementById('pdfViewerHallazgo').setAttribute('src', ruta);
                                }
                            </script>
                            <div class="modal fade" id="agregarhallazgo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title" id="exampleModalLabel">Formulario Ingreso Hallazgos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="../sdo-funciones/subir-hallazgos.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                                <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomDetalleEquipo ?>">
                                                <p><u><code>Solo se permiten fotos o archivos pdf</code></u></p>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:15px">Adjunto el archivo:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <input type="file" class="form-control form-control-sm" required id="archivoAdjuntoHallazgo" name="archivoAdjuntoHallazgo[]" multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="tabAccesorios">
                        <button id="agregarAccesorios1" class="btn btn-sm bg-success agregarAccesorios"><i class="fas fa-plus"></i> Agregar Accesorios</button>
                        <button id="listarAccesoriosbutton" style="display: none" class="btn btn-sm bg-success listadoAccesorios"><i class="fas fa-clipboard"></i> Listar Accesorios</button>
                        <form action="modificar_accesorios.php" method="POST">
                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                            <button style="float: right"  type="submit" class="btn btn-sm bg-success"><i class="fas fa-clipboard"></i> Modificar los accesorios</button>
                        </form>
                        <br>
                        <br>
                        <div id="tabladelosaccesorios">
                            <table class="table table-hover text-center table-bordered">
                                <thead>
                                <tr class="bg-secondary">
                                    <th colspan="6">Detalle de Accesorios</th>
                                </tr>
                                <tr>
                                    <th>Accesorios</th>
                                    <th>Cantidad</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>Observación</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sqlAccesorios = "SELECT * FROM `detalle_accesorios_inge_clinica` WHERE idEquipo = $idEquipo AND habilitado = 0";
                                $resAccesorios = mysqli_query($conexion,$sqlAccesorios);
                                while($rowAccesorios = $resAccesorios->fetch_assoc()){
                                    ?>
                                    <tr>
                                        <td style="width: 25%">
                                            <?php echo $rowAccesorios['accesorios']?>
                                        </td>
                                        <td>
                                            <?php echo $rowAccesorios['cantidad']?>
                                        </td>
                                        <td>
                                            <?php echo $rowAccesorios['marca']?>
                                        </td>
                                        <td>
                                            <?php echo $rowAccesorios['modelo']?>
                                        </td>
                                        <td>
                                            <?php echo $rowAccesorios['serie']?>
                                        </td>
                                        <td>
                                            <?php echo $rowAccesorios['obs']?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="ingresodeaccesorios" style="display: none">
                            <form action="../sdo-funciones/guardar-accesorios-inge-clinico.php" method="POST">
                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                <div class="table-responsive">
                                    <button type="button" style="float: right" id="btnAgregarFila" class="btn bg-primary"><i class="fas fa-plus"></i> Añadir otro accesorio</button>
                                    <br>
                                    <br>
                                    <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCESORIOS:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MARCA:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MODELO:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">SERIE:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                        </tr>
                                        </thead>
                                        <tbody id="miTbody">
                                        <tr>
                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <input type="text" class="form-control" name="articuloSC1"   placeholder="Articulo">
                                            </td>
                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <input type="number" min="1" class="form-control" id="cantidadSC1" name="cantidadSC1" placeholder="Cantidad">
                                            </td>
                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <input type="text" min="1" class="form-control" id="marcaSC1" name="marcaSC1" placeholder="Marca">
                                            </td>
                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <input type="text" min="1" class="form-control" id="modeloSC1" name="modeloSC1" placeholder="Modelo">
                                            </td>
                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <input type="text" min="1" class="form-control" id="serieSC1" name="serieSC1" placeholder="Serie">
                                            </td>
                                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion1" name="observacion1"></textarea>
                                            </td>
                                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                No se puede eliminar
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="cantidadFilasFuncionario" id="cantidadFilasFuncionario" value="1"/>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Guardar</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabInsumos">
                        <button id="agregarInsumos1" class="btn btn-sm bg-primary agregarInsumos"><i class="fas fa-plus"></i> Agregar Insumos</button>
                        <button id="listarInsumosbutton" style="display: none" class="btn btn-sm bg-primary listadoInsumos"><i class="fas fa-clipboard"></i> Listar Insumos</button>
                        <form action="modificar_insumos.php" method="POST">
                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                            <button style="float: right" type="submit" class="btn btn-sm bg-primary"><i class="fas fa-clipboard"></i> Modificar los insumos</button>
                        </form>
                        <br>
                        <br>
                        <div id="tabladelosinsumos">
                            <table class="table table-hover text-center table-bordered">
                                <thead>
                                    <tr class="bg-secondary">
                                       <th colspan="5">Detalle de los Insumos</th>
                                    </tr>
                                    <tr>
                                        <th>Insumos</th>
                                        <th>Marca</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sqlInsumos = "SELECT * FROM `detalle_insumos_inge_clinica` WHERE idEquipo = $idEquipo AND habilitado = 0";
                                $resInsumos = mysqli_query($conexion,$sqlInsumos);
                                while($rowInsumos = $resInsumos ->fetch_assoc()){
                                    ?>
                                    <tr>
                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                            <?php echo $rowInsumos['insumos']?>
                                        </td>
                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                            <?php echo $rowInsumos['marca']?>
                                        </td>
                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                            <?php echo $rowInsumos['tipo']?>
                                        </td>
                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                            <?php echo $rowInsumos['cantidad']?>
                                        </td>
                                        <td style="vertical-align:middle; width:20%; font-size:  15px; text-align: center;">
                                            <?php echo $rowInsumos['obs']?>
                                        </td>

                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="ingresodeinsumos" style="display: none">
                            <form action="../sdo-funciones/guardar-insumos-inge-clinico.php" method="POST">
                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                <button type="button" style="float: right" id="btnAgregarFilaInsumos" class="btn bg-danger"><i class="fas fa-plus"></i> Añadir otro insumo</button>
                                <br>
                                <br>
                                <table id="miTablaInsumos" class="table table-bordered" style="font-size: 12px;">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">INSUMOS:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">TIPO:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MARCA:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBSERVACIONES:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                    </tr>
                                    </thead>
                                    <tbody id="miTbodyInsumos">
                                    <tr>
                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" class="form-control" name="insumosSC1"   placeholder="Articulo">
                                        </td>
                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" class="form-control" name="tiposSC1"   placeholder="Tipo">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" min="1" class="form-control" id="marcainsumosSC1" name="marcainsumosSC1" placeholder="Marca">
                                        </td>
                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="number" min="1" class="form-control" id="cantidadinsumosSC1" name="cantidadinsumosSC1" placeholder="Cantidad">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion1" name="observacion1"></textarea>
                                        </td>
                                        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            No se puede eliminar
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="cantidadFilasInsumos" id="cantidadFilasInsumos" value="1"/>
                                <button class="btn btn-primary btn-block">Guardar</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabRegistros">
                        <button id="agregarRegistros1" class="btn btn-sm bg-gradient-indigo agregarRegistros"><i class="fas fa-plus"></i> Agregar Registro</button>
                        <button id="listarRegistrosbutton" style="display: none" class="btn btn-sm bg-gradient-indigo listadoRegistros"><i class="fas fa-clipboard"></i> Listar Registro</button>
                        <form action="modificar_registros.php" method="POST">
                            <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                            <button style="float: right" type="submit" class="btn btn-sm bg-indigo"><i class="fas fa-clipboard"></i> Modificar los registros</button>
                        </form>
                        <br>
                        <br>
                        <div id="tabladelosregistros">
                            <table class="table-bordered table table-hover">
                                <thead>
                                <tr class="text-center bg-secondary">
                                    <th colspan="7">Registros de Actividades</th>
                                </tr>
                                <tr class="text-center">
                                    <th>Fecha</th>
                                    <th>Tipo de Intervencion</th>
                                    <th>Empresa</th>
                                    <th>OC</th>
                                    <th>N° Hoja Servicio Técnico</th>
                                    <th>Realizado</th>
                                    <th>Observaciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sqlRegistros = "SELECT * FROM `registros_inge_clinica` WHERE idEquipo = $idEquipo AND habilitado = 0";
                                        $resRegistros = mysqli_query($conexion,$sqlRegistros);
                                        while($rowRegistros = $resRegistros->fetch_assoc()){
                                            ?>
                                            <tr>
                                                <td><?php echo $rowRegistros['fecha'];?></td>
                                                <td><?php echo $rowRegistros['tipoIntervencion'];?></td>
                                                <td><?php echo $rowRegistros['empresa']?></td>
                                                <td><?php echo $rowRegistros['oc']?></td>
                                                <td><?php echo $rowRegistros['numeroHojaServicio']?></td>
                                                <td><?php echo $rowRegistros['realizado']?></td>
                                                <td><?php echo $rowRegistros['obs']?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="ingresoderegistros" style="display: none">
                            <form action="../sdo-funciones/guardar-registros-inge-clinico.php" method="POST">
                                <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo?>">
                                <button type="button" style="float: right" id="btnAgregarFilaRegistros" class="btn bg-gradient-indigo"><i class="fas fa-plus"></i> Añadir otro registro</button>
                                <br>
                                <br>
                                <table id="miTablaRegistros" class="table table-bordered" style="font-size: 12px;">
                                    <thead class="bg-secondary">
                                    <tr>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">FECHA:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">TIPO DE INTERVENCION:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">EMPRESA:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OC:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">N° HOJA SERVICIO TÉCNICO:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">REALIZADO:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">OBS:</td>
                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                                    </tr>
                                    </thead>
                                    <tbody id="miTbodyRegistros">
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="datetime-local" class="form-control" id="fecha1" name="fecha1">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <select class="form-control" id="tipoIntervencion1" name="tipoIntervencion1">
                                                <option value="" selected disabled>Seleccion...</option>
                                                <option value="MPPC">MPPC</option>
                                                <option value="MPPA">MPPA</option>
                                                <option value="PREDEC">PREDEC.</option>
                                                <option value="REPARAT">REPARAT.</option>
                                            </select>
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" class="form-control" id="empresa1" name="empresa1">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" class="form-control" id="oc1" name="oc1">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <input type="text" class="form-control" id="numeroServicioTecnico1" name="numeroServicioTecnico1">
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <select class="form-control" id="realizado1" name="realizado1">
                                                <option value="" selected disabled>Seleccion...</option>
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                            <textarea class="form-control" rows="10" style="resize: none" name="obs1" id="obs1"></textarea>
                                        </td>
                                        <td style="font-size: 10px; white-space: nowrap; vertical-align: middle; text-align: center">
                                            No se puede eliminar
                                        </td>
                                    </tbody>
                                </table>
                                <input type="hidden" id="cantidadFilasRegistros" name="cantidadFilasRegistros" value="1">
                                <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const tablaRegistros = document.getElementById('miTablaRegistros');
    const btnAgregarFilaRegistros = document.getElementById('btnAgregarFilaRegistros');
    let correlativoRegistros = 2;
    const tbodyRegistros = document.getElementById('miTbodyRegistros');

    btnAgregarFilaRegistros.addEventListener('click', () => {
        // Crear una nueva fila
        const nuevaFilaRegistros = document.createElement('tr');

        // Contenido de la nueva fila
        let contenidoRegistros = `
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="datetime-local" class="form-control" id="fecha${correlativoRegistros}" name="fecha${correlativoRegistros}">
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <select class="form-control" id="tipoIntervencion${correlativoRegistros}" name="tipoIntervencion${correlativoRegistros}">
                    <option value="" selected disabled>Seleccion...</option>
                    <option value="MPPC">MPPC</option>
                    <option value="MPPA">MPPA</option>
                    <option value="PREDEC">PREDEC.</option>
                    <option value="REPARAT">REPARAT.</option>
                </select>
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" class="form-control" id="empresa${correlativoRegistros}" name="empresa${correlativoRegistros}">
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" class="form-control" id="oc${correlativoRegistros}" name="oc${correlativoRegistros}">
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" class="form-control" id="numeroServicioTecnico${correlativoRegistros}" name="numeroServicioTecnico${correlativoRegistros}">
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <select class="form-control" id="realizado${correlativoRegistros}" name="realizado${correlativoRegistros}">
                    <option value="" selected disabled>Seleccion...</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <textarea class="form-control" rows="10" style="resize: none" name="obs${correlativoRegistros}" id="obs${correlativoRegistros}"></textarea>
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <button type="button" onclick="eliminarFilaRegistros(this)" class="btn btn-block btn-danger">
                        <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
                </button>
            </td>
        `;

        // Asignar el contenido a la nueva fila
        nuevaFilaRegistros.innerHTML = contenidoRegistros;

        // Agregar la nueva fila al tbody
        tbodyRegistros.appendChild(nuevaFilaRegistros);

        // Incrementar el correlativo
        correlativoRegistros++;

        // Actualizar la cantidad de filas
        const cantidadDeFilas2Registros = tbodyRegistros.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasRegistros').value = cantidadDeFilas2Registros;
    });
    function eliminarFilaRegistros(boton) {
        const filaRegistros = boton.closest('tr');
        filaRegistros.remove();
        correlativoRegistros--;
        const cantidadDeFilas2Registros = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasRegistros').value = cantidadDeFilas2Registros;
    }
</script>
<script>
    const tablaInsumos = document.getElementById('miTablaInsumos');
    const btnAgregarFilaInsumos = document.getElementById('btnAgregarFilaInsumos');
    let correlativoInsumos = 2;
    var tbodyInsumos = document.getElementById('miTbodyInsumos');
    btnAgregarFilaInsumos.addEventListener('click', () => {
        const nuevaFilaInsumos = document.createElement('tr');
        let contenidoInsumos = `
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" class="form-control" name="insumosSC${correlativoInsumos}"   placeholder="Articulo">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" class="form-control" name="tiposSC${correlativoInsumos}"   placeholder="Tipo">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="marcainsumosSC${correlativoInsumos}" name="marcainsumosSC${correlativoInsumos}" placeholder="Marca">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="number" min="1" class="form-control" id="cantidadinsumosSC${correlativoInsumos}" name="cantidadinsumosSC${correlativoInsumos}" placeholder="Cantidad">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion${correlativoInsumos}" name="observacion${correlativoInsumos}"></textarea>
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
           <button type="button" onclick="eliminarFilaInsumos(this)" class="btn btn-block btn-danger">
                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
            </button>
        </td>
        `;
        nuevaFilaInsumos.innerHTML = contenidoInsumos;
        tablaInsumos.querySelector('tbody').appendChild(nuevaFilaInsumos);
        correlativoInsumos++;

        const cantidadDeFilas2Insumos = tbodyInsumos.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasInsumos').value = cantidadDeFilas2Insumos;
    });
    function eliminarFilaInsumos(boton) {
        const filaInsumos = boton.closest('tr');
        filaInsumos.remove();
        correlativoInsumos--;
        const cantidadDeFilas2Insumos = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasInsumos').value = cantidadDeFilas2Insumos;
    }
</script>
<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('btnAgregarFila');
    let correlativo = 2;
    var tbody = document.getElementById('miTbody');
    btnAgregarFila.addEventListener('click', () => {
        const nuevaFila = document.createElement('tr');
        let contenido = `
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
           <input type="text" class="form-control" name="articuloSC${correlativo}" placeholder="Articulo">
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="number" min="1" class="form-control" id="cantidadSC${correlativo}" name="cantidadSC${correlativo}" placeholder="Cantidad">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="marcaSC${correlativo}" name="marcaSC${correlativo}" placeholder="Marca">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="modeloSC${correlativo}" name="modeloSC${correlativo}" placeholder="Modelo">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <input type="text" min="1" class="form-control" id="serieSC${correlativo}" name="serieSC${correlativo}" placeholder="Serie">
        </td>
        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
            <textarea class="form-control" rows="10" style="width: 100%;resize: none" id="observacion${correlativo}" name="observacion${correlativo}"></textarea>
        </td>
        <td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;">
                <button type="button" onclick="eliminarFila(this)" class="btn btn-block btn-danger">
                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
                </button>
        </td>
        `;
        nuevaFila.innerHTML = contenido;
        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++;

        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    });
    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        correlativo--;
        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    }
</script>
