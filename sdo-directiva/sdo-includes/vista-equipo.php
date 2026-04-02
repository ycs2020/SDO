<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?></h1>
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
                    <form method="post" action="carpeta-equipo.php" class="text-right">
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
                    } else{
                        ?>
                        <img class=" img-fluid" src="../sdo-administrador/img/ssvq1.png" width="100px" alt="Servicio de Salud">
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
            <!-- /.card-body -->
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

                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block <?php echo $color ?> btn-lg" style="font-size: medium" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-edit"></i> <br> Estado Actual: <b><?php echo $estadoEquipo ?></b></i>
                        </button>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block btn-lg btn-primary" style="font-size: medium" data-toggle="modal" data-target="#exampleModal1">
                            <i class="fas fa-list"></i> <br>Observaciones del Equipo<br> </i>
                        </button>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <button class="btn btn-block btn-lg bg-purple" style="font-size: medium" data-toggle="modal" data-target="#modalTiempoFuncion">
                            <i class="fas fa-clock"></i> <br> Tiempo de Funcionamiento<br> </i>
                        </button>
                    </div>
                    <!-- Modal -->
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
                                    $horasFueraDeServicio = 0;
                                    $horasOperativas = 0;
                                    // Fecha actual
                                    $fechaActual = new DateTime();
                                    $fechaActual->format('Y-m-d H:i:s');

                                    $sqlEquipamiento = "SELECT fechaRegistro FROM equipamiento_hbqp WHERE id = $idEquipo";
                                    $resEquipamiento = mysqli_query($conexion,$sqlEquipamiento);
                                    while($rowEquipamiento = $resEquipamiento->fetch_assoc()){
                                        $fechaRegistro = $rowEquipamiento['fechaRegistro'];
                                        $sqlBuscarEquipo = "SELECT * FROM `historial_cambios_equipos_respaldo` WHERE `idEquipo` = $idEquipo ORDER BY fecha ASC";
                                        $resBuscarEquipo = mysqli_query($conexion,$sqlBuscarEquipo);
                                        while($rowBuscarEquipo = $resBuscarEquipo->fetch_assoc()){
                                            $estadoBuscarEquipo = $rowBuscarEquipo['estado'];
                                            $fechaEstado = $rowBuscarEquipo['fecha'];
                                        }
                                    }
                                    ?>
                                    <table class="table-hover table-bordered" id="tabla-equipo-observacion">
                                        <h6>Historial de Cambio de Estado</h6>
                                        <tbody>
                                        <tr>
                                            <td>Tiempo Operativo:</td>
                                            <td><?php echo 'hola'?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo Fuera de Mantenimiento:</td>
                                            <td><?php echo 'hola'?></td>
                                        </tr>
                                        <tr>
                                            <td>Tiempo Fuera de Servicio:</td>
                                            <td><?php echo 'hola'?></td>
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
                        <form action="equipo-hojas-tecnicas.php" method="post" style="display: inline">
                            <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                            <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                            <button type="submit" style="font-size: medium" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br><?php echo $cantidadHojasTec ?><br>Hojas Técnicas</button>
                        </form>
                    </div>
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <form action="equipo-gastos.php" method="post" style="display: inline">
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
                        <form action="equipo-solicitudes-compra.php" method="post" style="display: inline">
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
                    <li class="nav-item"><a class="nav-link active text-white" href="#tabMod" data-toggle="tab">Modificar Equipo</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabSubEquipo" data-toggle="tab">Sub-Equipos &nbsp; <span class="badge badge-danger"><?php echo $cantidadSubEquipos ?></span></a></li>
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
                    <li class="nav-item"><a class="nav-link text-white" href="#tabPlan" data-toggle="tab">Planificaci&oacute;n</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#tabHallazgos" data-toggle="tab">Hallazgos</a></li>

                    <?php
                    if ($equipo_controla > 0) {
                        ?>
                        <li class="nav-item"><a class="nav-link text-white" href="#tabSistemas" data-toggle="tab">Observaciones Sistemas</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    <div class="tab-pane" id="tabInfo">
                        <div class="col-12">
                            <h5>Informacion</h5>
                        </div>
                    </div>

                    <div class="tab-pane active" id="tabMod">
                        <div class="col-12">
                            <form action="../sdo-funciones/guardar-edicion-equipo.php" method="post">
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
                                            <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra ?>">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                            <input type="text" class="form-control form-control-sm" name="inputMantenimiento" value="<?php echo $frecuenciaMantenimiento ?>" id="inputMantenimiento">
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
                                            <input type="text" class="form-control form-control-sm" id="costoMantencion" value="<?php echo $costoMantAnual ?>" name="costoMantencion">
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                            <select class="form-control form-control-sm equipoQueControlaMod" id="equipoQueControlaMod" name="equipoQueControlaMod" style="width: 100%">
                                                <option value="Seleccione...">Seleccione...</option>
                                                <?php
                                                $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                if ($resCategoriasEquipos->num_rows > 0) {
                                                    while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                        $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                        $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                        $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                        $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                        // Agregar el ID y el nombre de la subcategoría al array
                                                        $categoriasEquipos[] = array(
                                                            'id' => $idSubCatEquipo,
                                                            'nombre' => $nomSubCatEquipo,
                                                            'idPadre' => $getCategoriaSegunSubcategoria,
                                                            'nomPadre' => $nomCatEquipo
                                                        );
                                                    }
                                                }

                                                usort($categoriasEquipos, function ($a, $b) {
                                                    // Primero ordenar por nomPadre y luego por nombre
                                                    $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                    if ($cmp == 0) {
                                                        return strcmp($a['nombre'], $b['nombre']);
                                                    }
                                                    return $cmp;
                                                });


                                                // Recorrer $categoriasEquipos y agregar las opciones al select
                                                foreach ($categoriasEquipos as $categoria) {
                                                    if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                        echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                        $idCategoriaEquipo = $categoria['id'];
                                                        $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                        $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                        $idSubCategoria = 0;
                                                        if ($resEquipoSistema->num_rows > 0) {
                                                            while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                if ($rowEquipoSistema['idEquipo'] == $equipo_controla) {
                                                                    echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                }
                                                            }
                                                        }
                                                        echo '</optgroup>';
                                                    }
                                                }

                                                ?>
                                            </select>
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
                                            <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $numeroSerie ?>" name="numSerie">
                                        </div>
                                        <?php
                                        if ($tipoControlador == 'esclavo') {
                                            ?>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                <input type="text" class="form-control form-control-sm" id="device" value="<?php echo $device ?>" name="device">
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
                                                <label for="numSerie" style="font-size: 12px;">Alimentacion TCC:</label>
                                                <input type="text" class="form-control form-control-sm" id="alimentaciontcc" value="<?php echo $alimentacionTCC ?>" name="alimentaciontcc">
                                            </div>

                                            <?php
                                        }
                                        if ($tipoControlador == 'maestro') {
                                            ?>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                <input type="text" class="form-control form-control-sm" id="ejesECBOSS" value="<?php echo $ejesECBoss ?>" name="ejesECBOSS">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Ejes Rack:</label>
                                                <input type="text" class="form-control form-control-sm" id="ejesRack" value="<?php echo $ejesRack ?>" name="ejesRack">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Rack:</label>
                                                <input type="text" class="form-control form-control-sm" id="rack" value="<?php echo $rack ?>" name="rack">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Puerto:</label>
                                                <input type="text" class="form-control form-control-sm" id="puerto" value="<?php echo $puerto ?>" name="puerto">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Direccion IP:</label>
                                                <input type="text" class="form-control form-control-sm" id="direccionIP" value="<?php echo $direccionIP ?>" name="direccionIP">
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

                                                $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                $resServicios = mysqli_query($conexion, $sqlServicios);

                                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                                    $datos = [];

                                                    $datos['idServicio'] = $rowServicios['idServicio'];
                                                    $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                    $idServicio = $datos['idServicio'];
                                                    $nomServicio = $datos['nomServicio'];

                                                    if ($idServicio == $servicioDB) {
                                                        ?>
                                                        <option value="<?php echo $idServicio ?>" selected><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>
                                                        <?php
                                                    } else {
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
                                            <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $pma ?>">
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
                                <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $tipoControlador ?>">
                                <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabSubEquipo">
                        <div class="col-12">
                            <br>
                            <div class="row">
                                <button class="btn bg-info text-center" data-toggle="modal" data-target="#staticBackdrop" aria-describedby="tooltip" id="agregar"><i class="fas fa-plus"></i><b> Asociar un nuevo sub-equipo</b></button>&nbsp;
                                <div id="divshowagregar">
                                    <button class="btn bg-gradient-success text-center show-agregar" id="agregarnuevo"><i class="fas fa-plus"></i><b> Añadir un nuevo equipo y asociarlo como sub-equipo</b></button>
                                </div>
                                <div id="divshowlistar" style="display: none">
                                    <button class="btn bg-gradient-success text-center show-listar" id="listarsub-equipos"><i class="fas fa-clipboard-list"></i><b> Listar sub-equipos</b></button>
                                </div>
                            </div>
                            <hr>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title" id="staticBackdropLabel">Seleccione el equipo a añadir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-sm table-bordered table-hover" id="tablaSubequipos">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">N°</th>
                                                    <th scope="col" class="text-center">ID Unico</th>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <th scope="col" class="text-center">Estado</th>
                                                    <th scope="col" class="text-center">Seleccionar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlhijo = "SELECT * FROM equipamiento_hbqp WHERE (idEquipoPadre is null OR idEquipoPadre = 0) AND idCategoria = $idCategoria";
                                                $resHijo = mysqli_query($conexion, $sqlhijo);
                                                $num = 1;
                                                while ($rowHijo = $resHijo->fetch_assoc()) {
                                                    $datos = [];
                                                    $datos['id'] = $rowHijo['id'];
                                                    $datos['identificadorUnico'] = $rowHijo['identificadorUnico'];
                                                    $datos['nombreEquipo'] = $rowHijo['nombreEquipo'];
                                                    $datos['estadoEquipo'] = $rowHijo['estadoEquipo'];
                                                    $id = $datos['id'];
                                                    $identificadorUnico = $datos['identificadorUnico'];
                                                    $nombreEquipo = $datos['nombreEquipo'];
                                                    $estadoEquipo = $datos['estadoEquipo'];
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $num ?></td>
                                                        <td class="text-center"><?php echo $identificadorUnico ?></td>
                                                        <td class="text-center"><?php echo $nombreEquipo ?></td>
                                                        <td class="text-center"><?php echo $estadoEquipo ?></td>
                                                        <td>
                                                            <form action="../sdo-funciones/agregarEquipoHijo.php" method="POST">
                                                                <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="idEquipoHijo" value="<?php echo $id ?>">
                                                                <input type="submit" value="Añadir Equipo" class="btn bg-danger">
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php

                                                    $num++;
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th scope="col" class="text-center">N°</th>
                                                    <th scope="col" class="text-center">ID Unico</th>
                                                    <th scope="col" class="text-center">Nombre Equipo</th>
                                                    <th scope="col" class="text-center">Estado</th>
                                                    <th scope="col" class="text-center">Seleccionar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary">Guardar Equipo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="agregarnuevodiv" style="display:none;">
                                <p class="text-bold text-danger">(*) Campos Obligatorios</p>
                                <form action="../sdo-funciones/guardar-equipo.php" method="post">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">
                                    <input type="hidden" name="idEquipoPadre" value="<?php echo $idEquipo ?>">
                                    <fieldset>
                                        <legend>Información Técnica</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-2 col-12">
                                                <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $idGenerado ?>" placeholder="Escriba el Identificador unico del equipo">
                                            </div>
                                            <div class="col-md-1">
                                                <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                    <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID Único es generado aleatoriamente y es la identificación del equipo dentro del sistema, guarde este código." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                <input type="text" class="form-control form-control-sm" id="inputInventario" name="inputInventario" placeholder="Escriba el numero de inventario del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacion" placeholder="Escriba el id de litación del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                <input type="text" class="form-control form-control-sm" name="inputMantenimiento" id="inputMantenimiento">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                <input type="date" class="form-control form-control-sm" name="fechaRecepcion" id="fechaRecepcion">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                <input type="date" class="form-control form-control-sm" id="fechaCompra" name="fechaCompra">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                <input type="date" class="form-control form-control-sm" id="fechaMarcha" name="fechaMarcha">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="clase" style="font-size: 12px;">Clase:</label>
                                                <input type="text" class="form-control form-control-sm" id="clase" name="clase">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                <input type="text" class="form-control form-control-sm" id="subClase" name="subClase">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                <input type="text" class="form-control form-control-sm" id="complejidad" name="complejidad">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                <input type="text" class="form-control form-control-sm" id="riesgo" name="riesgo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                <input type="text" class="form-control form-control-sm" id="costoMantencion" name="costoMantencion">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                <select class="form-control form-control-sm equipoQueControlaAgregar" id="equipoQueControlaAgregar" name="equipoQueControlaAgregar" style="width: 100%">
                                                    <option value="Seleccione...">Seleccione...</option>
                                                    <?php
                                                    $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                    $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                    if ($resCategoriasEquipos->num_rows > 0) {
                                                        while ($rowCategoriasEquipos = $resCategoriasEquipos->fetch_assoc()) {
                                                            $idSubCatEquipo = $rowCategoriasEquipos['idSubCategoria'];
                                                            $nomSubCatEquipo = getNomSubCategoria($idSubCatEquipo);
                                                            $getCategoriaSegunSubcategoria = getCategoriaSegunSubcategoria($idSubCatEquipo);
                                                            $nomCatEquipo = getNomCategoria($getCategoriaSegunSubcategoria);

                                                            // Agregar el ID y el nombre de la subcategoría al array
                                                            $categoriasEquipos[] = array(
                                                                'id' => $idSubCatEquipo,
                                                                'nombre' => $nomSubCatEquipo,
                                                                'idPadre' => $getCategoriaSegunSubcategoria,
                                                                'nomPadre' => $nomCatEquipo
                                                            );
                                                        }
                                                    }

                                                    usort($categoriasEquipos, function ($a, $b) {
                                                        // Primero ordenar por nomPadre y luego por nombre
                                                        $cmp = strcmp($a['nomPadre'], $b['nomPadre']);
                                                        if ($cmp == 0) {
                                                            return strcmp($a['nombre'], $b['nombre']);
                                                        }
                                                        return $cmp;
                                                    });


                                                    // Recorrer $categoriasEquipos y agregar las opciones al select
                                                    foreach ($categoriasEquipos as $categoria) {
                                                        if (!empty($categoria['nombre']) && !empty($categoria['nomPadre'])) {
                                                            echo '<optgroup label="' . $categoria['nomPadre'] . ' / ' . $categoria['nombre'] . '">';

                                                            $idCategoriaEquipo = $categoria['id'];
                                                            $sqlEquipos = "SELECT * FROM equipos WHERE idSubCategoria = " . $idCategoriaEquipo;
                                                            $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                            $idSubCategoria = 0;
                                                            if ($resEquipoSistema->num_rows > 0) {
                                                                while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {
                                                                    if ($rowEquipoSistema['idEquipo'] == $equipo_controla) {
                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '" selected>' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $rowEquipoSistema['idEquipo'] . '">' . $rowEquipoSistema['nomEquipo'] . '</option>';
                                                                    }
                                                                }
                                                            }
                                                            echo '</optgroup>';
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <fieldset>
                                        <legend>Información Básica</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-12">
                                                <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipo" placeholder="Escriba el nombre del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                <input type="number" class="form-control form-control-sm" id="valorConIva" name="valorConIva">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" placeholder="Escriba el modelo del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                <input type="text" class="form-control form-control-sm" id="fechaFabricación" name="fechaFabricación">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                <input type="text" class="form-control form-control-sm" id="vidaUtil" name="vidaUtil">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                <input type="text" class="form-control form-control-sm" id="versionSoftware" name="versionSoftware">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                <input type="text" class="form-control form-control-sm" id="paisOrigen" name="paisOrigen">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                <input type="text" class="form-control form-control-sm" id="terminoGarantia" name="terminoGarantia">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                <input type="text" class="form-control form-control-sm" id="numSerie" name="numSerie">
                                            </div>
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
                                                    $sqlServicios = "SELECT * FROM servicios ORDER BY nomServicio ASC";
                                                    $resServicios = mysqli_query($conexion, $sqlServicios);

                                                    while ($rowServicios = $resServicios->fetch_assoc()) {
                                                        $datos = [];

                                                        $datos['idServicio'] = $rowServicios['idServicio'];
                                                        $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                        $idServicio = $datos['idServicio'];
                                                        $nomServicio = $datos['nomServicio'];

                                                        ?>

                                                        <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>

                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                <input type="text" class="form-control form-control-sm" id="recinto" name="recinto">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="pma" style="font-size: 12px;">PMA:</label>
                                                <input type="text" class="form-control form-control-sm" id="pma" name="pma">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Piso: <code>*</code> <code>Coloque un 0 si no conoce el piso</code></label>
                                                <input type="text" required class="form-control form-control-sm" id="piso" name="piso">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Departamento al que pertenece: <code>*</code></label>
                                                <select name="idDepaCategoria" id="idDepaCategoria" class="form-control form-control-sm" required>
                                                    <option value="selected">
                                                        Seleccione un departamento
                                                    </option>
                                                    <?php
                                                    $sqlDepa = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%') and nomDepartamento NOT LIKE ('%Informatica%') AND nomDepartamento NOT LIKE ('%Operacional%')";
                                                    $resDepa = mysqli_query($conexion, $sqlDepa);
                                                    while ($rowDepa = $resDepa->fetch_assoc()) {
                                                        $datos1 = [];
                                                        $datos1['nomDepartamento'] = $rowDepa['nomDepartamento'];
                                                        $datos1['idDepartamento'] = $rowDepa['idDepartamento'];
                                                        $idDepartamento1 = $datos1['idDepartamento'];
                                                        $nomDepartamento = $datos1['nomDepartamento'];

                                                        ?>
                                                        <option value="<?php echo $idDepartamento1 ?>">
                                                            <?php echo $nomDepartamento ?>
                                                        </option>
                                                        <?php
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <button type="submit" class="btn btn-success btn-block text-center"><b> Guardar Equipo</b></button>
                                </form>

                                <br>
                                <br>
                            </div>

                            <div id="tablaequipos" class="table-responsive">
                                <table id="tabla-equipos" class="table table-hover  table-bordered table-sm">
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
                                        <!-- <th scope="col" class="text-center">Servicio</th> -->
                                        <th scope="col" class="text-center">Recinto</th>
                                        <th scope="col" class="text-center">PMA</th>
                                        <th scope="col" class="text-center">Piso</th>
                                        <th scope="col" class="text-center">Estado</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo ORDER BY piso ASC, pma ASC";
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

                                        $idEquipo = $datos['id'];
                                        $identificadorUnico = $datos['identificadorUnico'];
                                        $nombreEquipoEqui = $datos['nombreEquipo'];
                                        $idServicio = $datos['idServicio'];
                                        $recinto = $datos['recinto'];
                                        $pma = $datos['pma'];
                                        $piso = $datos['piso'];
                                        $estadoEquipo = $datos['estadoEquipo'];
                                        $modelo = $datos['modelo'];

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
                                                    <button class="bg-primary btn btn-block" data-toggle="modal" data-target="#mostrarPuntos<?php echo $idEquipo ?>">Ver Puntos</button>
                                                    <div class="modal fade" id="mostrarPuntos<?php echo $idEquipo ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            ?>

                                            <td class="text-center"><?php echo $nombreEquipoEqui ?></td>
                                            <!-- <td class="text-center"><?php echo $nombreServicio ?></td> -->
                                            <td class="text-center"><?php echo $recinto ?></td>
                                            <td class="text-center"><?php echo $pma ?></td>
                                            <td class="text-center">
                                                <?php

                                                if ($piso == 0) {
                                                    $piso = -1;
                                                }

                                                echo $piso;
                                                ?>
                                            </td>

                                            <?php

                                            if ($estadoEquipo == 'Operativo') {
                                                ?>
                                                <td class="font-weight-bold text-success  text-center " style="display: inline-block"><?php echo '<i class="fas fa-check"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                <?php
                                            } else if ($estadoEquipo == 'Mantenimiento') {
                                                ?>
                                                <td class="font-weight-bold   text-warning text-center " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-triangle"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                <?php
                                            } else if($estadoEquipo == 'OperativoObs'){
                                                $estadoEquipo = 'Operativo con Observaciones'
                                                ?>
                                                <td class="font-weight-bold   text-secondary text-center " style="display: inline-block"><?php echo '<i class="fas fa-exclamation-circle"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                <?php
                                            }else{
                                                ?>
                                                <td class="font-weight-bold   text-danger text-center " style="display: inline-block"><?php echo '<i class="fas fa-skull-crossbones"></i> ' . strtoupper($estadoEquipo) ?></td>
                                                <?php
                                            }

                                            ?>
                                            <td class="text-center">
                                                <form action="../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php" target="_blank" method="POST" style="display: inline;">
                                                    <input type="hidden" id="idCarpeta" name="idCarpeta" value="<?php echo $idEquipo ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="far fa-file"></i></button>
                                                </form>
                                                <form action="equipo.php" method="POST" style="display: inline">
                                                    <input type="hidden" id="idEquipo" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                    <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-door-open"></i></button>
                                                </form>

                                                <button class="btn btn-danger btn-sm eliminar" id="<?php echo $idEquipo ?>"><i class="fas fa-trash-alt"></i></button>
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
                                        <!-- <th scope="col" class="text-center">Servicio</th> -->
                                        <th scope="col" class="text-center">Recinto</th>
                                        <th scope="col" class="text-center">PMA</th>
                                        <th scope="col" class="text-center">Piso</th>
                                        <th scope="col" class="text-center">Estado</th>
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
                                    <p><b>Listado de Documentacion:</b></p>
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

                    <div class="tab-pane" id="tabDetalle">

                        <?php

                        // Encontrar la posición del texto 'ECB600_'
                        $posicion_inicio = strpos($nomDetalleEquipo, 'ECB');
                        // Si se encuentra 'ECB600_', obtener la subcadena desde esa posición hasta el siguiente espacio
                        if ($posicion_inicio !== false) {
                            $posicion_inicio += strlen('ECB'); // Mover la posición de inicio después de 'ECB600_'
                            $posicion_fin = strpos($nomDetalleEquipo, ' ', $posicion_inicio); // Encontrar la posición del siguiente espacio
                            if ($posicion_fin !== false) {
                                $texto_deseado = substr($nomDetalleEquipo, $posicion_inicio, $posicion_fin - $posicion_inicio);
                                $nomDetalleEquipo = 'ECB' . $texto_deseado; // Esto mostrará '1042'
                            }
                        }

                        $porciones1 = explode("ECB", $nomDetalleEquipo);

                        $variableParaTabla = 'ECB ' . $porciones1[1];

                        $porciones2 = explode("_", $variableParaTabla);

                        $variableParaTablaFinal = $porciones2[0] . '-' . $porciones2[1];

                        $variableParaTablaFinal = trim($variableParaTablaFinal);
                        ?>

                        <table id="tableDetalleControl" class="table table-bordered table-hover">
                            <thead class="bg-secondary">
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
                            </thead>
                            <tbody>

                            <?php
                            $query = 'SELECT * FROM detalle_control_bms WHERE controlador_ControlBMS LIKE "' . $variableParaTablaFinal . '" ORDER BY idControlBMS ASC';

                            $res = mysqli_query($conexion, $query);

                            $numContBMS = 1;

                            while ($row = $res->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td nowrap="nowrap" style="vertical-align:middle;">
                                        <center><?php echo $numContBMS; ?></center>
                                    </td>
                                    <td nowrap="nowrap" style="vertical-align:middle;" nowrap="nowrap" align="center">
                                        <?php
                                        $piso_ControlBMS = mb_strtoupper($row['piso_ControlBMS']);
                                        echo $piso_ControlBMS;
                                        ?>
                                    </td>
                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                        <?php
                                        $tcc_ControlBMS = mb_strtoupper($row['tcc_ControlBMS']);
                                        echo $tcc_ControlBMS;
                                        ?>
                                    </td>
                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                        <?php
                                        $controlador_ControlBMS = mb_strtoupper($row['controlador_ControlBMS']);
                                        echo $controlador_ControlBMS;

                                        ?>
                                    </td>
                                    <td nowrap="nowrap" style="vertical-align:middle;" align="center">
                                        <?php
                                        $numEntrada_ControlBMS = $row['numEntrada_ControlBMS'];
                                        echo $numEntrada_ControlBMS;

                                        ?>
                                    </td>
                                    <td style="vertical-align:middle;" align="center">
                                        <?php
                                        $digitalInput_ControlBMS = $row['digitalInput_ControlBMS'];
                                        echo $digitalInput_ControlBMS;

                                        ?>
                                    </td>
                                    <td style="vertical-align:middle;" align="center">
                                        <?php
                                        $analogInput_ControlBMS = $row['analogInput_ControlBMS'];
                                        echo $analogInput_ControlBMS;

                                        ?>
                                    </td>
                                    <td style="vertical-align:middle;" align="center">
                                        <?php
                                        $numSalida_ControlBMS = $row['numSalida_ControlBMS'];
                                        echo $numSalida_ControlBMS;

                                        ?>
                                    </td>
                                    <td style="vertical-align:middle;" align="center">
                                        <?php
                                        $digitalOutput_ControlBMS = $row['digitalOutput_ControlBMS'];
                                        echo $digitalOutput_ControlBMS;

                                        ?>
                                    </td>
                                    <td style="vertical-align:middle;" align="center">
                                        <?php
                                        $analogOutput_ControlBMS = $row['analogOutput_ControlBMS'];
                                        echo $analogOutput_ControlBMS;

                                        ?>
                                    </td>

                                </tr>
                                <?php

                                $numContBMS = $numContBMS + 1;
                            }
                            ?>


                            </tbody>
                            <tfoot class="bg-secondary">
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
                            </tfoot>
                        </table>

                    </div>

                    <div class="tab-pane" id="tabPlan">

                        <button style="float: right" class="btn bg-primary" type="button" data-toggle="modal" data-target="#modalAddSubTarea"><i class="fas fa-plus"></i> Agregar Tarea</button>
                        <form method="post" style="vertical-align: middle" action="../sdo-funciones/planificacion_agregarTarea.php">
                            <div class="modal fade" id="modalAddSubTarea" name="modalAddSubTarea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Tarea</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 ml-auto">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Actividad: <code>*</code></label>
                                                        <textarea id="actividadTareaP" style="resize:none;" name="actividadTareaP" required class="form-control" rows="3" placeholder="Actividad..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 ml-auto">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="inicioPlanTareaP">Fecha Inicio - Planificaci&oacute;n: <code>*</code></label>
                                                        <input type="date" name="inicioPlanTareaP" id="inicioPlanTareaP" placeholder="Fecha Inicio - Planificaci&oacute;n" class="form-control" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ml-auto">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="finPlanTareaP">Fecha Termino - Planificaci&oacute;n: <code>*</code></label>
                                                        <input type="date" name="finPlanTareaP" id="finPlanTareaP" placeholder="Fecha Termino - Planificaci&oacute;n" class="form-control" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 ml-auto">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="finPlanTareaP">Tipo de Días a Trabajar: <code>*</code></label>
                                                        <select name="tipoDiasHabil" id="tipoDiasHabil" class="form-control">
                                                            <option value="Seleccione...">Seleccione...</option>
                                                            <option value="Habiles">Habiles</option>
                                                            <option value="Inhabiles">Inhabiles</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo ?>">
                                            <input type="hidden" name="esEquipamiento" id="esEquipamiento" value="1">
                                            <input type="hidden" name="idTareaP" id="idTareaP" value="0">
                                            <input type="hidden" name="nomCreadorTareaP" id="nomCreadorTareaP" value="<?php echo $nomUsuario; ?>">
                                            <input type="hidden" name="deptoCreadorTareaP" id="deptoCreadorTareaP" value="<?php echo getIDControlCentra(); ?>">
                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <br>

                        <div class="table-responsive">

                            <table id="tableCartaPlanificacion" class="table table-bordered table-hover">
                                <thead>
                                <tr class="bg-gradient-dark">
                                    <th>N°</th>
                                    <th>Detalle</th>
                                    <th>Actividad</th>
                                    <th>Inicio Planificado</th>
                                    <th>Termino Planificado</th>
                                    <th>Inicio Real</th>
                                    <th>Termino Real</th>
                                    <th>Porcentaje</th>
                                    <th>Detalle</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $query = 'SELECT * FROM tareas_carta_gantt WHERE (deptoCreadorTareaP = ' . getIDControlCentra() . ' OR deptoCreadorTareaP = ' . getIDInstrumentacion() . ') AND (actividadTareaP LIKE "%' . $nombreEquipo . '%" OR idEquipamiento = ' . $idEquipo . ') ORDER BY inicioPlanTareaP ASC';

                                $res = mysqli_query($conexion, $query);

                                $numTareaP = 1;
                                $numFila = 0;

                                while ($row = $res->fetch_assoc()) {

                                    $idTareaP = $row['idTareaP'];

                                    $contarSubTareaP = contarSubTareaP($idTareaP);

                                    $contarTotalPorcentaje = contarTotalPorcentaje($idTareaP);

                                    $contarCantidadPadre = contarCantidadPadre($idTareaP);

                                    if ($contarTotalPorcentaje > 0) {
                                        $porcentajePadre = $contarTotalPorcentaje / $contarCantidadPadre;
                                    } else {
                                        $porcentajePadre = 0;
                                    }

                                    if ($contarSubTareaP > 0) {
                                        $argumentoTR = 'data-widget="expandable-table" aria-expanded="false"';
                                    } else {
                                        $argumentoTR = '';
                                    }
                                    ?>
                                    <tr class="fila-clickeable">
                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                            <?php
                                            $numFila++;
                                            echo $numFila;
                                            ?>
                                        </td>
                                        <td style="width: 5%; vertical-align: middle; text-align: center;">
                                            <?php
                                            if ($contarSubTareaP > 0) {
                                                ?>
                                                <button type="submit" style="text-wrap: nowrap;" id="button<?php echo $numTareaP; ?>" class="btn btn-primary" onclick="mostrarOcultarFilas('info<?php echo $numTareaP; ?>')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Expandir</button>
                                                <?php
                                            } else {
                                                ?>
                                                <button type="submit" class="btn btn-primary" disabled="disabled">Deshabilitado</button>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td style="width: 25%; vertical-align: middle;">
                                            <?php
                                            echo mb_convert_case($row['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                            ?>
                                        </td>
                                        <td style="width: 10%; vertical-align: middle;">
                                            <?php
                                            $inicioPlanTareaP = $row['inicioPlanTareaP'];
                                            $inicioPlanTareaP2 = date("d-m-Y", strtotime($inicioPlanTareaP));
                                            echo $inicioPlanTareaP2;
                                            ?>
                                        </td>
                                        <td style="width: 10%; vertical-align: middle;">
                                            <?php
                                            $finPlanTareaP = $row['finPlanTareaP'];
                                            $finPlanTareaP2 = date("d-m-Y", strtotime($finPlanTareaP));
                                            echo $finPlanTareaP2;
                                            ?>
                                        </td>
                                        <td style="width: 10%; vertical-align: middle;">
                                            <?php
                                            $inicioRealTareaP = $row['inicioRealTareaP'];

                                            if (empty($inicioRealTareaP)) {
                                                echo 'No Iniciado';
                                            } else {
                                                $inicioRealTareaP2 = date("d-m-Y", strtotime($inicioRealTareaP));
                                                echo $inicioRealTareaP2;
                                            }

                                            ?>
                                        </td>
                                        <td style="width: 10%; vertical-align: middle;">
                                            <?php
                                            $finRealTareaP = $row['finRealTareaP'];

                                            if (empty($inicioRealTareaP)) {
                                                echo 'No Iniciado';
                                            } else {
                                                if (empty($finRealTareaP)) {
                                                    echo 'No Finalizado';
                                                } else {
                                                    $finRealTareaP2 = date("d-m-Y", strtotime($finRealTareaP));
                                                    echo $finRealTareaP2;
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td style="width: 20%; vertical-align: middle; text-align: center;">
                                            <?php
                                            if ($contarTotalPorcentaje == 0) {
                                                echo "No Iniciado";
                                            } else {
                                                ?>
                                                <div class="form-group">

                                                    <?php
                                                    $porcentajeTareaP2 = round($porcentajePadre, 1);

                                                    ?>
                                                    <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajePadre; ?>%" aria-valuenow="<?php echo $porcentajePadre; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td style="width: 10%; vertical-align: middle; text-align: center;">
                                            <?php
                                            if ($row['idTareaP'] > 0) {
                                                ?>

                                                <form style="margin: 0; text-align: center;" class="" action="planificador_detalle.php" method="post">

                                                    <input type="hidden" name="idTareaP" value="<?php echo $row['idTareaP']; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fas fa-eye"></i></button>

                                                </form>
                                                <?php
                                            } else {
                                                echo 'Deshabilitado';
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <?php

                                    if ($contarSubTareaP > 0) {
                                        $queryPadre = 'SELECT * FROM tareas_carta_gantt WHERE idPadreTareaP = ' . $row['idTareaP'] . ' ORDER BY inicioPlanTareaP ASC';

                                        $resPadre = mysqli_query($conexion, $queryPadre);

                                        $numTareaPadre = 1;

                                        while ($rowPadre = $resPadre->fetch_assoc()) {
                                            ?>
                                            <tr class="info<?php echo $numTareaP; ?> fila-oculta" style="display: none; background-color: lightgrey;">
                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                    <?php
                                                    echo '';
                                                    ?>
                                                </td>
                                                <td style="width: 5%; vertical-align: middle; text-align: center;">
                                                    <?php echo $numFila . '.' . $numTareaPadre; ?>
                                                </td>
                                                <td style="width: 25%; vertical-align: middle;">
                                                    <?php
                                                    echo mb_convert_case($rowPadre['actividadTareaP'], MB_CASE_UPPER, 'UTF-8');
                                                    ?>
                                                </td>
                                                <td style="width: 10%; vertical-align: middle;">
                                                    <?php
                                                    $inicioPlanTareaPadre = $rowPadre['inicioPlanTareaP'];
                                                    $inicioPlanTareaPadre2 = date("d-m-Y", strtotime($inicioPlanTareaPadre));
                                                    echo $inicioPlanTareaPadre2;
                                                    ?>
                                                </td>
                                                <td style="width: 10%; vertical-align: middle;">
                                                    <?php
                                                    $finPlanTareaPadre = $rowPadre['finPlanTareaP'];
                                                    $finPlanTareaPadre2 = date("d-m-Y", strtotime($finPlanTareaPadre));
                                                    echo $finPlanTareaPadre2;
                                                    ?>
                                                </td>
                                                <td style="width: 10%; vertical-align: middle;">
                                                    <?php
                                                    $inicioRealTareaPadre = $row['inicioRealTareaP'];

                                                    if (empty($inicioRealTareaPadre)) {
                                                        echo 'No Iniciado';
                                                    } else {
                                                        $inicioRealTareaPadre2 = date("d-m-Y", strtotime($inicioRealTareaPadre));
                                                        echo $inicioRealTareaPadre2;
                                                    }

                                                    ?>
                                                </td>
                                                <td style="width: 10%; vertical-align: middle;">
                                                    <?php
                                                    $finRealTareaPadre = $row['finRealTareaP'];

                                                    if (empty($inicioRealTareaPadre)) {
                                                        echo 'No Iniciado';
                                                    } else {
                                                        if (empty($finRealTareaPadre)) {
                                                            echo 'No Finalizado';
                                                        } else {
                                                            $finRealTareaPadre2 = date("d-m-Y", strtotime($finRealTareaPadre));
                                                            echo $finRealTareaPadre2;
                                                        }
                                                    }

                                                    ?>
                                                </td>
                                                <td style="width: 20%; vertical-align: middle; text-align: center;">
                                                    <div class="form-group">
                                                        <?php
                                                        $porcentajeTareaP2 = round($rowPadre['porcentajeTareaP'], 1);

                                                        ?>
                                                        <label><?php echo $porcentajeTareaP2 . '%'; ?></label>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?php echo $porcentajeTareaP2; ?>%" aria-valuenow="<?php echo $porcentajeTareaP2; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 10%; vertical-align: middle; text-align: center;">
                                                    <?php
                                                    echo '';
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $numTareaPadre++;
                                        }
                                    }
                                    $numTareaP++;
                                }


                                ?>


                                </tbody>
                                <tfoot>

                                <tr class="bg-gradient-dark">
                                    <th>N°</th>
                                    <th>Detalle</th>
                                    <th>Actividad</th>
                                    <th>Inicio Planificado</th>
                                    <th>Termino Planificado</th>
                                    <th>Inicio Real</th>
                                    <th>Termino Real</th>
                                    <th>Porcentaje</th>
                                    <th>Detalle</th>
                                </tr>
                                </tfoot>
                            </table>

                            <!--                            <h5>--><?php //echo $query; ?><!--</h5>-->
                        </div>
                    </div>

                    <div class="tab-pane" id="tabHallazgos">
                        <div class="col-12">
                            <div class="row">
                                <fieldset class="border p-3 text-center" style="width: 20%">
                                    <p><b>Listado de Hallazgo:</b></p>
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

                    <div class="tab-pane" id="tabSistemas">

                        <h4 class="text-center font-weight-bold">Observaciones de Equipos en Sistema</h4>

                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="text-center bg-primary">
                                <tr>
                                    <th style="width: 50%;">EQUIPO</th>
                                    <th style="width: 50%;">ESTADO ACTUAL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($equipo_controla) && $equipo_controla > 0) {
                                    $nomEquipoControla = getNomEquipo($equipo_controla);
                                    $forzadoEquipo = getStatusForzado($equipo_controla);
                                    $forzadoEncendido = getStatusForzadoEncendido($equipo_controla);
                                    $forzadoApagado = getStatusForzadoApagado($equipo_controla);
                                    $forzadoVariable = getStatusForzadoVariables($equipo_controla);
                                    $modoAutomatico = getStatusAutomatico($equipo_controla);

                                    $getSubCategoriaEquipo = getSubCategoriaEquipo($equipo_controla);

                                    $_SESSION['idSubCategoria'] = $getSubCategoriaEquipo;
                                } else {
                                    $nomEquipoControla = 'NO APLICA';
                                    $forzadoEquipo = 0;
                                    $forzadoEncendido = 0;
                                    $forzadoApagado = 0;
                                    $forzadoVariable = 0;
                                    $modoAutomatico = 0;
                                }


                                if ($forzadoEncendido == 1) {
                                    $estadoEquipoControla = 'Forzado Encendido';
                                } else if ($forzadoApagado == 1) {
                                    $estadoEquipoControla = 'Forzado Apagado';
                                } else if ($modoAutomatico == 1) {
                                    $estadoEquipoControla = 'Modo Automatico';
                                } else if ($forzadoVariable == 1) {
                                    $estadoEquipoControla = 'Variables Forzadas';
                                } else {
                                    $estadoEquipoControla = 'Modo Automatico';
                                }

                                $nomEquipoControla2 = mb_convert_case($nomEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                $estadoEquipoControla2 = mb_convert_case($estadoEquipoControla, MB_CASE_UPPER, 'UTF-8');
                                ?>

                                <tr>
                                    <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                        <?php echo $nomEquipoControla; ?>
                                    </td>
                                    <td style="width: 50%; font-size: 15pt;" class="text-center font-weight-bold">
                                        <?php echo $estadoEquipoControla2; ?>
                                    </td>
                                </tr>

                                </tbody>

                            </table>
                        </div>

                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-center" style="display: flex; justify-content:center;">


                                        <!-- FORZADO ENCENDIDO -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                            <form method="post" action="../sdo-funciones/equipos-forzadoEncendido.php">
                                                <button type="button" data-toggle="modal" data-target="#modalForzarEncendido" id="inputMozi" class="btn btn-success btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Encendido</button>

                                                <div class="modal fade" id="modalForzarEncendido" name="modalForzarEncendido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Encendido de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Forzar Encendido: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Forzar Encendido de Equipo: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Encendido de Equipo..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="encendidoEquipo" id="encendidoEquipo" value="Encender">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                <button type="submit" class="btn btn-primary">Forzar Encendido</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                        <!-- FIN FORZADO ENCENDIDO -->

                                        <!-- FORZADO APAGADO -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                            <form method="post" action="../sdo-funciones/equipos-forzadoApagado.php">
                                                <button type="button" data-toggle="modal" data-target="#modalForzarApagado" id="inputMozi" class="btn bg-pink btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Apagado</button>

                                                <div class="modal fade" id="modalForzarApagado" name="modalForzarApagado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-pink">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Apagado de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Forzar Apagado: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Forzar Apagado de Equipo: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Apagado de Equipo..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">

                                                                <input type="hidden" name="apagadoEquipo" id="apagadoEquipo" value="Apagar">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                <button type="submit" class="btn btn-primary">Forzar Apagado Equipo</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                        <!-- FIN FORZADO APAGADO -->

                                        <!-- MODO AUTOMATICO -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                            <form method="post" action="../sdo-funciones/equipos-forzarAutomatico.php">
                                                <button type="button" data-toggle="modal" data-target="#modalAutomatico" id="inputMozi" class="btn btn-info btn-block" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Modo Automatico</button>

                                                <div class="modal fade" id="modalAutomatico" name="modalAutomatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-info">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Modo Automatico de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Modo Automatico: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Modo Automatico: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Modo Automatico..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">

                                                                <input type="hidden" name="modoAutomatico" id="modoAutomatico" value="Automatico">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                        <!-- FIN MODO AUTOMATICO -->

                                        <!-- FORZAR VARIABLES -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">

                                            <form method="post" action="../sdo-funciones/equipos-forzadoVariable.php">
                                                <button type="button" data-toggle="modal" data-target="#modalVariables" id="inputMozi" class="btn bg-yellow btn-block"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Variables</button>

                                                <div class="modal fade" id="modalVariables" name="modalVariables" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-yellow">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Variables de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Forzado de Variables: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Forzado de Variables: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzado de Variables..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="forzadoVariable" id="forzadoVariable" value="Variables">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- FIN FORZAR VARIABLES -->

                                        <!-- AGREGAR OBSERVACION -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                                            <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                                <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn bg-navy btn-block"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-navy">
                                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Observacion: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Observacion: <code>*</code></label>
                                                                    <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="12" placeholder="Observacion..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- FIN AGREGAR OBSERVACION -->
                                        <?php

                                        $verificarEsPabellon = verificarEsPabellon($equipo_controla);
                                        if ($verificarEsPabellon == 1) {
                                            ?>
                                            <!-- CAMBIO SET POINT -->
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-12">
                                                <form method="post" action="../sdo-funciones/pabellones_cambiarSetPoint.php">
                                                    <button type="button" data-toggle="modal" data-target="#modalCambiarSP" id="inputMozi" class="btn btn-block bg-indigo"><i class="fa fa-pen"></i>&nbsp;&nbsp; Set-Point T°</button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalCambiarSP" name="modalCambiarSP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Cambiar Set-Point de Temperatura</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                        <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Fecha de Observacion: <code>*</code></label>
                                                                        <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Nuevo Set-Point de Temperatura: <code>*</code></label>
                                                                        <label for="exampleInputBorder" style="color: red">Para Decimales debe agregar una coma ' , '</label>
                                                                        <input style="text-align:left;" type="number" style="text-align:center;" class="form-control" min="0" step=".1" id="nuevoValorSP" name="nuevoValorSP" required>
                                                                    </div>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="exampleInputBorder">Observacion: <code>*</code></label>
                                                                        <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="8" placeholder="Observacion..."></textarea>

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controla; ?>">
                                                                    <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (' . $cargoUsuario . ')'; ?>">
                                                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idEquipo; ?>">
                                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- FIN CAMBIO SET POINT -->
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">

                            <table id="tableAnotacionesSistema" class="table table-bordered table-hover">
                                <thead class="bg-info">
                                <tr>
                                    <th style="vertical-align:middle;">
                                        <center>N°</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Fecha Observaciones:</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Nombre de Quien Registra:</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Observaciones:</center>
                                    </th>
                                    <?php
                                    $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                    if ($verificarEsPabellon == 1) {
                                        ?>
                                        <th nowrap="nowrap" style="vertical-align:middle;">
                                            <center>Set-Point de T°:</center>
                                        </th>
                                        <?php
                                    }
                                    ?>
                                    <th style="vertical-align:middle;">
                                        <center>Tipo de Observaci&oacute;n:</center>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $query = "SELECT * FROM equipos_observaciones WHERE idEquipo = '" . $equipo_controla . "' ORDER BY fechaObservaciones DESC";

                                $res = mysqli_query($conexion, $query);

                                $numObservacion = 1;

                                while ($row = $res->fetch_assoc()) {
                                    ?>

                                    <?php

                                    $classObservacion = "";

                                    if ($row['esForzado'] == "Forzado - Encendido") {

                                        $classObservacion = "bg-success";
                                    } else if ($row['esForzado'] == "Forzado - Apagado") {

                                        $classObservacion = "bg-pink ";
                                    } else if ($row['esForzado'] == "Forzado - Variables") {

                                        $classObservacion = "bg-yellow";
                                    } else if ($row['esForzado'] == "Modo Automatico") {

                                        $classObservacion = "bg-info";
                                    } else if ($row['esForzado'] == "Observación Normal") {

                                        $classObservacion = "bg-navy";
                                    } else if ($row['esForzado'] == "Forzado") {

                                        $classObservacion = "bg-gray";
                                    } else if ($row['esForzado'] == "Cambio de Set Point") {

                                        $classObservacion = "bg-indigo";
                                    } else {

                                        $classObservacion = "bg-light";
                                    }
                                    ?>

                                    <tr class="<?php echo $classObservacion; ?>">
                                        <td style="vertical-align:middle;">
                                            <center>
                                                <?php echo $numObservacion; ?>
                                            </center>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <center>
                                                <?php if ($row['fechaObservaciones'] == 0) {
                                                    echo "No registra fecha";
                                                } else {
                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                    $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                    echo $inicioFecha . '&nbsp;&nbsp;' . $inicioHora;
                                                } ?>
                                            </center>
                                        </td>
                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                            <center>
                                                <?php
                                                $personalObservacion = $row['personalObservacion'];

                                                $partes = explode(' (', $personalObservacion, 2);
                                                $nombre = $partes[0];
                                                $profesion = rtrim($partes[1], ')');

                                                echo $nombre . '<br>(' . $profesion . ')';
                                                ?>
                                            </center>
                                        </td>
                                        <td style="vertical-align:middle; text-align:justify;">
                                            <?php echo nl2br($row['detalleObservaciones']); ?>
                                        </td>
                                        <?php
                                        $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                        if ($verificarEsPabellon == 1) {
                                            ?>
                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                <center>
                                                    <?php
                                                    $porcentajeObservacion = $row['porcentajeObservacion'];

                                                    if ($porcentajeObservacion > 0) {
                                                        echo $porcentajeObservacion;
                                                    } else {
                                                        echo 'No Aplica';
                                                    }

                                                    ?>
                                                </center>
                                            </td>
                                            <?php
                                        }
                                        ?>
                                        <td style="vertical-align:middle; text-align:center;">
                                            <?php echo $row['esForzado']; ?>
                                        </td>
                                    </tr>

                                    <?php

                                    $numObservacion++;
                                }
                                ?>
                                </tbody>
                                <tfoot class="bg-info">
                                <tr>
                                    <th style="vertical-align:middle;">
                                        <center>N°</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Fecha Observaciones:</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Nombre de Quien Registra:</center>
                                    </th>
                                    <th style="vertical-align:middle;">
                                        <center>Observaciones:</center>
                                    </th>
                                    <?php
                                    $verificarEsPabellon = verificarEsPabellon($equipo_controla);

                                    if ($verificarEsPabellon == 1) {
                                        ?>
                                        <th nowrap="nowrap" style="vertical-align:middle;">
                                            <center>Set-Point de T°:</center>
                                        </th>
                                        <?php
                                    }
                                    ?>
                                    <th style="vertical-align:middle;">
                                        <center>Tipo de Observaci&oacute;n:</center>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>

                            <!--                            <h5>--><?php //echo $query; ?><!--</h5>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /.container-fluid -->