<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h1 class="card-title">Visualizando Equipo - <?php echo $nombreEquipo ?> - <?php echo $nomCategoria ?></h1>
                <?php
                if ($idEquipoPadre > 0) {


                $tipoMenuPadre = obtenerTipoMenu($idEquipoPadre);

                if ($tipoMenuPadre == 'nuevo') {
                    $rutaMenuPadre = 'equipo_menuNuevo.php';
                } else {
                    $rutaMenuPadre = 'equipo_menuAntiguo.php';

                }
                ?>
                    <form method="post" action="<?php echo $rutaMenuPadre ?>" class="text-right">
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
    <!--    <div class="col-xl-3 col-lg-4 col-md-6 col-12">-->
    <div class="col-12">

        <div class="card card-primary card-outline">
            <div class="card-body box-profile w">

                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td class="align-middle" style="width: 25%">
                                <?php

                                if (!empty($rutaImagen)) {
                                    ?>
                                    <img class=" img-fluid" src="<?php echo $rutaImagen; ?>" width="200px" alt="ECBOS">
                                    <?php
                                } else {
                                    ?>
                                    <img class=" img-fluid" src="../sdo-administrador/img/controlCent-HBQP.png" width="200px" alt="Control Centralizado">
                                    <?php
                                }

                                ?>

                                <br>
                                <br>


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
                            </td>
                            <td class="align-middle" style="width: 75%">


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
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <!--    <div class="col-xl-3 col-lg-4 col-md-6 col-12">-->
    <div class="col-12">

        <div class="card">
            <div class="card-header bg-secondary d-flex p-0">
                <?php
                $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo ORDER BY piso ASC, pma ASC, nombreEquipo ASC";
                $resEquipo = mysqli_query($conexion, $sqlEquipo);

                if ($resEquipo && mysqli_affected_rows($conexion) > 0) {
                    ?>
                    <ul class="nav nav-pills mr-auto ml-auto p-2">
                        <?php
                        while ($rowEquipo = $resEquipo->fetch_assoc()) {
                            $idEquipoSE = $rowEquipo['id'];
                            $nombreEquipoEquiSE = $rowEquipo['nombreEquipo'];
                            $nombreEquipoEquiSEEspacios = preg_replace('/\s+/', '', $nombreEquipoEquiSE);
                            ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#<?php echo $nombreEquipoEquiSEEspacios ?>" data-toggle="tab" onclick="loadEquipoData('<?php echo $idEquipoSE; ?>')">
                                    <?php echo $nombreEquipoEquiSE; ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php
                    mysqli_data_seek($resEquipo, 0); // Reset the result set to loop again
                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                        $nombreEquipoEquiSE = $rowEquipo['nombreEquipo'];
                        $nombreEquipoEquiSEEspacios = preg_replace('/\s+/', '', $nombreEquipoEquiSE);
                        ?>
                        <div class="tab-pane" id="<?php echo $nombreEquipoEquiSEEspacios ?>">
                            <div id="equipoData<?php echo $rowEquipo['id']; ?>">
                                <?php
                                $idEquipoCarga = $rowEquipo['id'];

                                if ($idEquipoCarga > 0) {
                                    $sqlSubEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipoCarga";
                                    $resultSubEquipo = mysqli_query($conexion, $sqlSubEquipo);

                                    if ($resultSubEquipo && mysqli_num_rows($resultSubEquipo) > 0) {
                                        $rowSubEquipo = mysqli_fetch_assoc($resultSubEquipo);

                                        $idSubEquipo = $rowSubEquipo['id'];
                                        $nombreSubEquipo = $rowSubEquipo['nombreEquipo'];
                                        $identificadorSubEquipo = $rowSubEquipo['identificadorUnico'];
                                        $equipo_controlaSubEquipo = $rowSubEquipo['equipo_controla'];
                                        $sourceAlarmaSubEquipo = $rowSubEquipo['sourceAlarma'];
                                        $numVista = 'Eq' . $idSubEquipo;
                                        $estadoSubEquipo = $rowSubEquipo['estadoEquipo'];
                                        $idEquipoPadreSubEquipo = $rowSubEquipo['idEquipoPadre'];
                                        $colorSubEquipo = '';

                                        ?>


                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header bg-green d-flex p-0">
                                                        <ul class="nav nav-pills mr-auto ml-auto p-2">
                                                            <li class="nav-item">
                                                                <a class="nav-link active text-white" href="#infoGeneral<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Informaci&oacute;n
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#modEquipos<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Modificar Equipo
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#subEquipos<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Sub-Equipos
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#documentacion<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Documentaci&oacute;n
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#planificacion<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Planificaci&oacute;n
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#hallazgos<?php echo $numVista; ?>" data-toggle="tab">
                                                                    Hallazgos
                                                                </a>
                                                            </li>

                                                            <?php
                                                            if ($equipo_controlaSubEquipo > 0) {
                                                                ?>
                                                                <li class="nav-item"><a class="nav-link text-white" href="#sistemas<?php echo $numVista; ?>" data-toggle="tab">Observaciones Sistemas</a></li>
                                                                <?php
                                                            }


                                                            if (!empty($sourceAlarmaSubEquipo)) {
                                                                ?>
                                                                <li class="nav-item"><a class="nav-link text-white" href="#alarmasBMS<?php echo $numVista; ?>" data-toggle="tab">Alarmas BMS<?php echo ' -- ' . $sourceAlarmaSubEquipo ?></a></li>
                                                                <?php
                                                            }
                                                            ?>

                                                            <li class="nav-item">
                                                                <a class="nav-link text-white" href="#bms<?php echo $numVista; ?>" data-toggle="tab">BMS</a>
                                                            </li>

                                                        </ul>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="tab-content">
                                                            <!-- INFORMACIÓN GENERAL -->
                                                            <div class="tab-pane active" id="infoGeneral<?php echo $numVista; ?>">
                                                                <div class="row">
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                        <div class="card card-primary card-outline">
                                                                            <div class="card-body box-profile">
                                                                                <h3 class="profile-username text-center"><?php echo $nombreSubEquipo; ?></h3>
                                                                                <br>

                                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                                    <li class="list-group-item">
                                                                                        <b>Identificador: </b><?php echo $identificadorSubEquipo; ?>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                                                                        <div class="row">

                                                                            <?php
                                                                            if ($estadoSubEquipo == 'Operativo' || $estadoSubEquipo == 'operativo' || $estadoSubEquipo == 'OPERATIVO') {
                                                                                $colorSubEquipo = 'bg-gradient-success';
                                                                            } else if ($estadoSubEquipo == 'Mantenimiento') {
                                                                                $colorSubEquipo = 'bg-gradient-warning';
                                                                            } else if ($estadoSubEquipo == 'OperativoObs') {
                                                                                $estadoSubEquipo = 'Operativo, Observacion';
                                                                                $colorSubEquipo = 'bg-gradient-orange';
                                                                            } else {
                                                                                $colorSubEquipo = 'bg-gradient-danger';
                                                                            }

                                                                            ?>

                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                <button class="btn btn-block <?php echo $colorSubEquipo ?> btn-lg" style="font-size: medium" data-toggle="modal" data-target="#cambiarOperatividad<?php echo $idSubEquipo; ?>">
                                                                                    <i class="fas fa-edit"></i> <br> Estado Actual: <b><?php echo $estadoSubEquipo ?></b>
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                <button class="btn btn-block btn-lg btn-primary" style="font-size: medium" data-toggle="modal" data-target="#observaciones<?php echo $idSubEquipo; ?>">
                                                                                    <i class="fas fa-list"></i> <br>Observaciones del Equipo<br> </i>
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                <button class="btn btn-block btn-lg bg-purple" style="font-size: medium" data-toggle="modal" data-target="#tiempoFuncionamiento<?php echo $idSubEquipo; ?>">
                                                                                    <i class="fas fa-clock"></i> <br> Tiempo de Funcionamiento<br> </i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- FIN INFORMACIÓN GENERAL -->

                                                            <!-- MODIFICAR EQUIPO -->
                                                            <div class="tab-pane " id="modEquipos<?php echo $numVista; ?>">
                                                                <div class="col-12">
                                                                    <form action="../sdo-funciones/guardar-edicion-equipo.php" method="post">
                                                                        <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                        <br>
                                                                        <fieldset>
                                                                            <?php

                                                                            $sqlModSubEquipo = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
                                                                            $resultModSubEquipo = mysqli_query($conexion, $sqlModSubEquipo);

                                                                            if ($resultModSubEquipo && mysqli_num_rows($resultModSubEquipo) > 0) {
                                                                                $rowModSubEquipo = mysqli_fetch_assoc($resultModSubEquipo);

                                                                                // Asignar a las variables
                                                                                $subEquipoId = $rowModSubEquipo['id'];
                                                                                $subEquipoFechaRegistro = $rowModSubEquipo['fechaRegistro'];
                                                                                $subEquipoIdentificadorUnico = $rowModSubEquipo['identificadorUnico'];
                                                                                $subEquipoNumeroInventario = $rowModSubEquipo['numeroInventario'];
                                                                                $subEquipoFechaRecepcion = $rowModSubEquipo['fechaRecepcion'];
                                                                                $subEquipoNombreEquipo = $rowModSubEquipo['nombreEquipo'];
                                                                                $subEquipoMarca = $rowModSubEquipo['marca'];
                                                                                $subEquipoModelo = $rowModSubEquipo['modelo'];
                                                                                $subEquipoIdServicio = $rowModSubEquipo['idServicio'];
                                                                                $subEquipoRecinto = $rowModSubEquipo['recinto'];
                                                                                $subEquipoPma = $rowModSubEquipo['pma'];
                                                                                $subEquipoCoordenadas = $rowModSubEquipo['coordenadas'];
                                                                                $subEquipoFechaCompra = $rowModSubEquipo['fechaCompra'];
                                                                                $subEquipoValorConIva = $rowModSubEquipo['valorConIva'];
                                                                                $subEquipoIdLicitacion = $rowModSubEquipo['idLicitacion'];
                                                                                $subEquipoOrdenCompra = $rowModSubEquipo['ordenCompra'];
                                                                                $subEquipoFechaMarcha = $rowModSubEquipo['fechaMarcha'];
                                                                                $subEquipoClase = $rowModSubEquipo['clase'];
                                                                                $subEquipoSubclase = $rowModSubEquipo['subclase'];
                                                                                $subEquipoComplejidadTec = $rowModSubEquipo['complejidadTec'];
                                                                                $subEquipoNivelRiesgo = $rowModSubEquipo['nivelRiesgo'];
                                                                                $subEquipoAhnoFrabricacion = $rowModSubEquipo['AhnoFrabricacion'];
                                                                                $subEquipoVidaUtil = $rowModSubEquipo['vidaUtil'];
                                                                                $subEquipoVersionSoftware = $rowModSubEquipo['versionSoftware'];
                                                                                $subEquipoPaisOrigen = $rowModSubEquipo['paisOrigen'];
                                                                                $subEquipoTerminoGarantia = $rowModSubEquipo['terminoGarantia'];
                                                                                $subEquipoCostoMantAnual = $rowModSubEquipo['costoMantAnual'];
                                                                                $subEquipoFrecuenciaMantenimiento = $rowModSubEquipo['frecuenciaMantenimiento'];
                                                                                $subEquipoNumeroSerie = $rowModSubEquipo['numeroSerie'];
                                                                                $subEquipoPiso = $rowModSubEquipo['piso'];
                                                                                $subEquipoIdCategoria = $rowModSubEquipo['idCategoria'];
                                                                                $subEquipoIdEquipoPadre = $rowModSubEquipo['idEquipoPadre'];
                                                                                $subEquipoEstadoEquipo = $rowModSubEquipo['estadoEquipo'];
                                                                                $subEquipoOrdenEquipo = $rowModSubEquipo['ordenEquipo'];
                                                                                $subEquipoIdDepaPertenece = $rowModSubEquipo['idDepaPertenece'];
                                                                                $subEquipoEjesECBoss = $rowModSubEquipo['ejesECBoss'];
                                                                                $subEquipoEjesRack = $rowModSubEquipo['ejesRack'];
                                                                                $subEquipoRack = $rowModSubEquipo['rack'];
                                                                                $subEquipoPuerto = $rowModSubEquipo['puerto'];
                                                                                $subEquipoDireccionIP = $rowModSubEquipo['direccionIP'];
                                                                                $subEquipoDeviceId = $rowModSubEquipo['device_id'];
                                                                                $subEquipoNetwork = $rowModSubEquipo['network'];
                                                                                $subEquipoMacAddress = $rowModSubEquipo['mac_address'];
                                                                                $subEquipoTcc = $rowModSubEquipo['tcc'];
                                                                                $subEquipoEjesTCC = $rowModSubEquipo['ejesTCC'];
                                                                                $subEquipoAlimentacionTCC = $rowModSubEquipo['alimentacionTCC'];
                                                                                $subEquipoTipoControlador = $rowModSubEquipo['tipoControlador'];
                                                                                $subEquipoRutaImagen = $rowModSubEquipo['rutaImagen'];
                                                                                $subEquipoEquipoControla = $rowModSubEquipo['equipo_controla'];
                                                                                $subEquipoSourceAlarma = $rowModSubEquipo['sourceAlarma'];
                                                                                $subEquipoTipoMenu = $rowModSubEquipo['tipoMenu'];


                                                                            }

                                                                            ?>
                                                                            <legend>Información Técnica</legend>


                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                                                    <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $subEquipoIdentificadorUnico ?>" placeholder="Escriba el Identificador unico del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="inputInventario" name="inputInventario" value="<?php echo $subEquipoNumeroInventario ?>" placeholder="Escriba el numero de inventario del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                                                    <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacion" value="<?php echo $subEquipoIdLicitacion ?>" placeholder="Escriba el id de litación del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra" value="<?php echo $subEquipoOrdenCompra ?>">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                                                    <input type="text" class="form-control form-control-sm" name="inputMantenimiento" value="<?php echo $subEquipoFrecuenciaMantenimiento ?>" id="inputMantenimiento">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                                                    <input type="date" class="form-control form-control-sm" name="fechaRecepcion" value="<?php echo $subEquipoFechaRecepcion ?>" id="fechaRecepcion">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                                                    <input type="date" class="form-control form-control-sm" name="fechaCompra" value="<?php echo $subEquipoFechaCompra ?>" id="fechaCompra" name="fechaCompra">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                                                    <input type="date" class="form-control form-control-sm" name="fechaMarcha" value="<?php echo $subEquipoFechaMarcha ?>" id="fechaMarcha" name="fechaMarcha">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="clase" style="font-size: 12px;">Clase:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="clase" value="<?php echo $subEquipoClase ?>" name="clase">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="subClase" value="<?php echo $subEquipoSubclase ?>" name="subClase">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="complejidad" value="<?php echo $subEquipoComplejidadTec ?>" name="complejidad">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="riesgo" value="<?php echo $subEquipoNivelRiesgo ?>" name="riesgo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="costoMantencion" value="<?php echo $subEquipoCostoMantAnual ?>" name="costoMantencion">
                                                                                </div>
                                                                                <?php
                                                                                if ($subEquipoIdCategoria == 2) {
                                                                                    ?>
                                                                                    <div class="form-group col-md-3 col-12">
                                                                                        <label for="costoMantencion" style="font-size: 12px;">Equipo que Controla en Sistema:</label>
                                                                                        <select class="form-control form-control-sm equipoQueControlaMod" id="equipoQueControlaMod" name="equipoQueControlaMod" style="width: 100%">
                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                            <?php
                                                                                            $sqlCategoriasEquipos = "SELECT DISTINCT idSubCategoria FROM equipos";
                                                                                            $resCategoriasEquipos = mysqli_query($conexion, $sqlCategoriasEquipos);
                                                                                            $categoriasEquipos = array();
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
                                                                                                            if ($rowEquipoSistema['idEquipo'] == $subEquipoEquipoControla) {
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
                                                                                    <div class="form-group col-md-3 col-12">
                                                                                        <label for="sourceAlarma" style="font-size: 12px;">Source de Alarmas:</label>
                                                                                        <select class="form-control form-control-sm sourceAlarmaMod" id="sourceAlarmaMod" name="sourceAlarmaMod" style="width: 100%">
                                                                                            <option value="Seleccione...">Seleccione...</option>
                                                                                            <?php
                                                                                            $sqlSourceAlarmas = "SELECT DISTINCT nameSource FROM bms_sourceAlarmas";
                                                                                            $resSourceAlarmas = mysqli_query($conexion, $sqlSourceAlarmas);
                                                                                            $dataSource = array();
                                                                                            if ($resSourceAlarmas->num_rows > 0) {
                                                                                                while ($rowSourceAlarmas = $resSourceAlarmas->fetch_assoc()) {
                                                                                                    $nomSourceAlarmas = $rowSourceAlarmas['nameSource'];

                                                                                                    // Agregar el ID y el nombre de la subcategoría al array
                                                                                                    $dataSource[] = array(
                                                                                                        'nombre' => $nomSourceAlarmas
                                                                                                    );
                                                                                                }
                                                                                            }

                                                                                            usort($dataSource, function ($a, $b) {
                                                                                                // Primero ordenar por nomPadre y luego por nombre
                                                                                                $cmp = strcmp($a['nombre'], $b['nombre']);
                                                                                                return $cmp;
                                                                                            });


                                                                                            // Recorrer $categoriasEquipos y agregar las opciones al select
                                                                                            foreach ($dataSource as $data) {
                                                                                                if (!empty($data['nombre'])) {
                                                                                                    $nombre = $data['nombre'];
                                                                                                    if ($nombre == $subEquipoSourceAlarma) {
                                                                                                        echo '<option value="' . $nombre . '" selected>' . $nombre . '</option>';
                                                                                                    } else {
                                                                                                        echo '<option value="' . $nombre . '">' . $nombre . '</option>';
                                                                                                    }
                                                                                                }
                                                                                            }

                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </fieldset>
                                                                        <hr>
                                                                        <fieldset>
                                                                            <legend>Información Básica</legend>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                                                    <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipo" value="<?php echo $subEquipoNombreEquipo ?>" placeholder="Escriba el nombre del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                                                    <input type="number" class="form-control form-control-sm" id="valorConIva" value="<?php echo $subEquipoValorConIva ?>" name="valorConIva">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" value="<?php echo $subEquipoMarca ?>" placeholder="Escriba la marca del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" value="<?php echo $subEquipoModelo ?>" placeholder="Escriba el modelo del equipo">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="fechaFabricación" value="<?php echo $subEquipoAhnoFrabricacion ?>" name="fechaFabricación">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="vidaUtil" value="<?php echo $subEquipoVidaUtil ?>" name="vidaUtil">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="versionSoftware" value="<?php echo $subEquipoVersionSoftware ?>" name="versionSoftware">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="paisOrigen" value="<?php echo $subEquipoPaisOrigen ?>" name="paisOrigen">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="terminoGarantia" value="<?php echo $subEquipoTerminoGarantia ?>" name="terminoGarantia">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="numSerie" value="<?php echo $subEquipoNumeroSerie ?>" name="numSerie">
                                                                                </div>
                                                                                <!--                                        --><?php
                                                                                //                                        if ($tipoControlador == 'esclavo') {
                                                                                //                                            ?>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Device_id:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="device" value="<?php echo $subEquipoDeviceId ?>" name="device">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Network:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="network" value="<?php echo $subEquipoNetwork ?>" name="network">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Mac Address:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="mac" value="<?php echo $subEquipoMacAddress ?>" name="mac">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">TCC:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="tcc" value="<?php echo $subEquipoTcc ?>" name="tcc">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Ejes TCC:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="ejestcc" value="<?php echo $subEquipoEjesTCC ?>" name="ejestcc">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="numSerie" style="font-size: 12px;">Alimentacion TCC:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="alimentaciontcc" value="<?php echo $subEquipoAlimentacionTCC ?>" name="alimentaciontcc">
                                                                                </div>
                                                                                <!---->
                                                                                <!--                                            --><?php
                                                                                //                                        }
                                                                                //                                        if ($tipoControlador == 'maestro') {
                                                                                //                                            ?>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="ejesECBOSS" style="font-size: 12px;">Ejes ECBOSS:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="ejesECBOSS" value="<?php echo $subEquipoEjesECBoss ?>" name="ejesECBOSS">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="ejesRack" style="font-size: 12px;">Ejes Rack:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="ejesRack" value="<?php echo $subEquipoEjesRack ?>" name="ejesRack">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="rack" style="font-size: 12px;">Rack:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="rack" value="<?php echo $subEquipoRack ?>" name="rack">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="puerto" style="font-size: 12px;">Puerto:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="puerto" value="<?php echo $subEquipoPuerto ?>" name="puerto">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="direccionIP" style="font-size: 12px;">Direccion IP:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="direccionIP" value="<?php echo $subEquipoDireccionIP ?>" name="direccionIP">
                                                                                </div>
                                                                                <!--                                            --><?php
                                                                                //                                        }
                                                                                //
                                                                                //                                        ?>

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

                                                                                            if ($idServicio == $subEquipoIdServicio) {
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
                                                                                    <input type="text" class="form-control form-control-sm" id="recinto" name="recinto" value="<?php echo $subEquipoRecinto ?>">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="pma" style="font-size: 12px;">PMA:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="pma" name="pma" value="<?php echo $subEquipoPma ?>">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas" value="<?php echo $subEquipoCoordenadas ?>">
                                                                                </div>
                                                                                <div class="form-group col-md-3 col-12">
                                                                                    <label for="coordenadas" style="font-size: 12px;">Piso:</label>
                                                                                    <input type="text" class="form-control form-control-sm" id="piso" name="piso" value="<?php echo $subEquipoPiso ?>">
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                        <hr>
                                                                        <input type="hidden" name="tipoControlador" id="tipoControlador" value="<?php echo $subEquipoTipoControlador ?>">
                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre ?>">
                                                                        <button type="submit" class="btn btn-warning btn-block text-center"><b> Guardar Cambios</b></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <!-- FIN MODIFICAR EQUIPO -->

                                                            <!-- SUB EQUIPOS EQUIPO -->
                                                            <div class="tab-pane " id="subEquipos<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold"><?php echo 'Sub-Equipos Asociados a : ' . $nombreSubEquipo; ?></h4>
                                                                <br><br>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <input type="hidden" name="nomTableSubEquipos" id="nomTableSubEquipos" value="<?php echo 'tableSE' . $idSubEquipo ?>">
                                                                            <table id="<?php echo 'tableSE' . $idSubEquipo ?>" class="table table-bordered text-center">
                                                                                <thead class="bg-secondary">
                                                                                <tr>
                                                                                    <th>ID</th>
                                                                                    <th>Nombre Equipo</th>
                                                                                    <th>Recinto</th>
                                                                                    <th>PMA</th>
                                                                                    <th>Piso</th>
                                                                                    <th>Estado</th>
                                                                                    <th>Acciones</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tfoot class="bg-secondary">
                                                                                <tr>
                                                                                    <th>ID</th>
                                                                                    <th>Nombre Equipo</th>
                                                                                    <th>Recinto</th>
                                                                                    <th>PMA</th>
                                                                                    <th>Piso</th>
                                                                                    <th>Estado</th>
                                                                                    <th>Acciones</th>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FIN SUB EQUIPO EQUIPO -->


                                                            <!-- DOCUMENTACION EQUIPO -->
                                                            <div class="tab-pane " id="documentacion<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold"><?php echo 'Documentacion: ' . $nombreSubEquipo; ?></h4>
                                                                <br><br>
                                                            </div>
                                                            <!-- FIN DOCUMENTACION EQUIPO -->


                                                            <!-- PLANIFICACION EQUIPO -->
                                                            <div class="tab-pane " id="planificacion<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold"><?php echo 'PLANIFICACION: ' . $nombreSubEquipo; ?></h4>
                                                                <br><br>
                                                            </div>
                                                            <!-- FIN PLANIFICACION EQUIPO -->


                                                            <!-- HALLAZGOS EQUIPO -->
                                                            <div class="tab-pane " id="hallazgos<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold"><?php echo 'HALLAZGOS: ' . $nombreSubEquipo; ?></h4>
                                                                <br><br>
                                                            </div>
                                                            <!-- FIN HALLAZGOS EQUIPO -->


                                                            <!-- SISTEMA EQUIPO -->
                                                            <div class="tab-pane " id="sistemas<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold">Observaciones de <?php echo $nombreSubEquipo; ?> en Sistema</h4>

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
                                                                        if (!empty($equipo_controlaSubEquipo) && $equipo_controlaSubEquipo > 0) {
                                                                            $nomEquipoControla = getNomEquipo($equipo_controlaSubEquipo);
                                                                            $forzadoEquipo = getStatusForzado($equipo_controlaSubEquipo);
                                                                            $forzadoEncendido = getStatusForzadoEncendido($equipo_controlaSubEquipo);
                                                                            $forzadoApagado = getStatusForzadoApagado($equipo_controlaSubEquipo);
                                                                            $forzadoVariable = getStatusForzadoVariables($equipo_controlaSubEquipo);
                                                                            $modoAutomatico = getStatusAutomatico($equipo_controlaSubEquipo);

                                                                            $getSubCategoriaEquipo = getSubCategoriaEquipo($equipo_controlaSubEquipo);

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

                                                                <div class="row justify-content-center text-center">

                                                                    <?php
                                                                    $acciones = [
                                                                        ['estado' => $forzadoEncendido, 'accion' => 'encendido', 'btnClass' => 'success', 'titulo' => 'Forzar Encendido', 'archivo' => 'equipos-forzadoEncendido.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzado - Encendido'],
                                                                        ['estado' => $forzadoApagado, 'accion' => 'apagado', 'btnClass' => 'pink', 'titulo' => 'Forzar Apagado', 'archivo' => 'equipos-forzadoApagado.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzado - Apagado'],
                                                                        ['estado' => $modoAutomatico, 'accion' => 'automatico', 'btnClass' => 'info', 'titulo' => 'Modo Automatico', 'archivo' => 'equipos-forzarAutomatico.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Modo Automatico Activado'],
                                                                        ['estado' => $forzadoVariable, 'accion' => 'variables', 'btnClass' => 'yellow', 'titulo' => 'Forzar Variables', 'archivo' => 'equipos-forzadoVariable.php', 'iconOn' => 'toggle-on', 'iconOff' => 'toggle-off', 'msg' => 'Forzar Variables'],
                                                                    ];

                                                                    foreach ($acciones as $accion) {
                                                                        $estado = $accion['estado'];
                                                                        $btnClass = $accion['btnClass'];
                                                                        $titulo = $accion['titulo'];
                                                                        $archivo = $accion['archivo'];
                                                                        $iconOn = $accion['iconOn'];
                                                                        $iconOff = $accion['iconOff'];
                                                                        $msg = $accion['msg'];
                                                                        $pasa = 'no';

                                                                        if ($titulo == 'Forzar Variables') {

                                                                            if (strpos($nombreSubEquipo, "UMA") === false) {
                                                                                $pasa = 'si';
                                                                            }
                                                                        }

                                                                        if ($pasa == 'no') {
                                                                            ?>
                                                                            <?php
                                                                            if ($estado == 0) {
                                                                                ?>
                                                                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                                    <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block">
                                                                                        <i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?>
                                                                                    </button>
                                                                                </div>

                                                                                <?php
                                                                            } else {

                                                                                if ($titulo == 'Forzar Variables') {
                                                                                    ?>

                                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                                        <button type="button" data-toggle="modal" data-target="#modal<?php echo ucfirst($accion['accion']); ?>" id="inputMozi" class="btn bg-<?php echo $btnClass; ?> btn-block">
                                                                                            <i class="fa fa-<?php echo $iconOff; ?>"></i>&nbsp;&nbsp;<?php echo $titulo; ?>
                                                                                        </button>
                                                                                    </div>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">
                                                                                        <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple btn-block"><i class="fa fa-<?php echo $iconOn; ?>"></i>&nbsp;&nbsp;<?php echo $msg; ?></button>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <div class="modal fade" id="modal<?php echo ucfirst($accion['accion']) ?>" name="modal<?php echo ucfirst($accion['accion']) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <form method="post" action="../sdo-funciones/<?php echo $archivo; ?>">
                                                                                        <div class="modal-header bg-<?php echo $btnClass; ?>">
                                                                                            <h5 class="modal-title" id="exampleModalLabel"><b><?php echo $titulo ?> de Equipo: </b><?php echo $nombreSubEquipo ?></h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                                                <input type="text" readonly="readonly" class="form-control" id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Fecha de <?php echo $titulo ?>: <code>*</code></label>
                                                                                                <input type="text" readonly="readonly" class="form-control" id="addFecha" name="addFecha" value="<?php echo $fecha ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Razones de <?php echo $titulo ?>: <code>*</code></label>
                                                                                                <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de <?php echo $titulo ?>..."></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">

                                                                                            <input type="hidden" name="<?php echo $accion['accion'] ?>Equipo" id="<?php echo $accion['accion'] ?>Equipo" value="<?php echo ucfirst($accion['accion']) ?>">

                                                                                            <?php

                                                                                            $nomUsuarioMayus = mb_convert_case($nomUsuario, MB_CASE_UPPER, 'UTF-8');

                                                                                            if ($nomUsuarioMayus == 'JORGE CANTILLANO') {
                                                                                                $nombreConCargo = $nomUsuario . " ( Jefe de Control Centralizado )";
                                                                                            } else {
                                                                                                $nombreConCargo = $nomUsuario . " ( Administrador )";

                                                                                            }

                                                                                            ?>
                                                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controlaSubEquipo; ?>">
                                                                                            <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                            <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre; ?>">
                                                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nombreConCargo; ?>">
                                                                                            <button type="submit" class="btn btn-primary"><?php echo $titulo ?> Equipo</button>
                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 p-2">

                                                                        <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn btn-block bg-navy" style="float:right;">
                                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion
                                                                        </button>
                                                                    </div>

                                                                    <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                                                        <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header bg-primary">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="modal-body">
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder">Nombre: <code>*</code></label>
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
                                                                                        <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $equipo_controlaSubEquipo; ?>">
                                                                                        <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $idSubEquipo; ?>">
                                                                                        <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $idEquipoPadre; ?>">
                                                                                        <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nombreConCargo; ?>">
                                                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>


                                                                </div>

                                                                <br>

                                                                <div class="row justify-content-center text-center">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <table id="<?php echo 'tableAnotacionesSistema' . $idSubEquipo ?>" class="table table-bordered table-hover">
                                                                                <thead class="bg-info">
                                                                                <tr>
                                                                                    <th style="vertical-align:middle;">N°</th>
                                                                                    <th style="vertical-align:middle;">Fecha Observaciones:</th>
                                                                                    <th style="vertical-align:middle;">Nombre de Quien Registra:</th>
                                                                                    <th style="vertical-align:middle;">Observaciones:</th>
                                                                                    <?php
                                                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controlaSubEquipo);
                                                                                    if ($verificarEsPabellon == 1) {
                                                                                        ?>
                                                                                        <th nowrap="nowrap" style="vertical-align:middle;">Set-Point de T°:</th>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                    <th style="vertical-align:middle;">Tipo de Observación:</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                $query = "SELECT * FROM equipos_observaciones WHERE idEquipo = '" . $equipo_controlaSubEquipo . "' ORDER BY fechaObservaciones DESC";
                                                                                $res = mysqli_query($conexion, $query);
                                                                                $numObservacion = 1;

                                                                                while ($row = $res->fetch_assoc()) {
                                                                                    $classObservacion = "";
                                                                                    switch ($row['esForzado']) {
                                                                                        case "Forzado - Encendido":
                                                                                            $classObservacion = "bg-success";
                                                                                            break;
                                                                                        case "Forzado - Apagado":
                                                                                            $classObservacion = "bg-pink";
                                                                                            break;
                                                                                        case "Forzado - Variables":
                                                                                            $classObservacion = "bg-yellow";
                                                                                            break;
                                                                                        case "Modo Automatico":
                                                                                            $classObservacion = "bg-info";
                                                                                            break;
                                                                                        case "Observación Normal":
                                                                                            $classObservacion = "bg-navy";
                                                                                            break;
                                                                                        case "Forzado":
                                                                                            $classObservacion = "bg-gray";
                                                                                            break;
                                                                                        case "Cambio de Set Point":
                                                                                            $classObservacion = "bg-indigo";
                                                                                            break;
                                                                                        default:
                                                                                            $classObservacion = "bg-light";
                                                                                    }
                                                                                    ?>
                                                                                    <tr class="<?php echo $classObservacion; ?>">
                                                                                        <td style="vertical-align:middle;"><?php echo $numObservacion; ?></td>
                                                                                        <td style="vertical-align:middle;">
                                                                                            <?php
                                                                                            if ($row['fechaObservaciones'] == 0) {
                                                                                                echo "No registra fecha";
                                                                                            } else {
                                                                                                $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaObservaciones'])));
                                                                                                $inicioHora = date('H:i', strtotime($row['fechaObservaciones']));
                                                                                                echo $inicioFecha . '&nbsp;&nbsp;' . $inicioHora;
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td nowrap="nowrap" style="vertical-align:middle;">
                                                                                            <?php
                                                                                            $personalObservacion = $row['personalObservacion'];
                                                                                            $partes = explode(' (', $personalObservacion, 2);
                                                                                            $nombre = $partes[0];
                                                                                            $profesion = rtrim($partes[1], ')');
                                                                                            echo $nombre . '<br>(' . $profesion . ')';
                                                                                            ?>
                                                                                        </td>
                                                                                        <td style="vertical-align:middle; text-align:justify;"><?php echo nl2br($row['detalleObservaciones']); ?></td>
                                                                                        <?php
                                                                                        if ($verificarEsPabellon == 1) {
                                                                                            ?>
                                                                                            <td nowrap="nowrap" style="vertical-align:middle;">
                                                                                                <?php
                                                                                                $porcentajeObservacion = $row['porcentajeObservacion'];
                                                                                                echo $porcentajeObservacion > 0 ? $porcentajeObservacion : 'No Aplica';
                                                                                                ?>
                                                                                            </td>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        <td style="vertical-align:middle; text-align:center;"><?php echo $row['esForzado']; ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $numObservacion++;
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot class="bg-info">
                                                                                <tr>
                                                                                    <th style="vertical-align:middle;">N°</th>
                                                                                    <th style="vertical-align:middle;">Fecha Observaciones:</th>
                                                                                    <th style="vertical-align:middle;">Nombre de Quien Registra:</th>
                                                                                    <th style="vertical-align:middle;">Observaciones:</th>
                                                                                    <?php
                                                                                    $verificarEsPabellon = verificarEsPabellon($equipo_controlaSubEquipo);
                                                                                    if ($verificarEsPabellon == 1) {
                                                                                        ?>
                                                                                        <th nowrap="nowrap" style="vertical-align:middle;">Set-Point de T°:</th>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                    <th style="vertical-align:middle;">Tipo de Observación:</th>
                                                                                </tr>
                                                                                </tfoot>
                                                                            </table>

                                                                            <script>
                                                                                $(document).ready(function () {
                                                                                    $('#<?php echo 'tableAnotacionesSistema' . $idSubEquipo ?>').DataTable({
                                                                                        "paging": true,
                                                                                        "iDisplayLength": 10,
                                                                                        "searching": true,
                                                                                        "ordering": false,
                                                                                        "info": true,
                                                                                        "processing": true,
                                                                                        "autoWidth": false,
                                                                                        "responsive": false,
                                                                                        "language": {
                                                                                            "lengthMenu": "Visualizando _MENU_ Registros por Página",
                                                                                            "zeroRecords": "No se han encontrado Registros.",
                                                                                            "info": "Mostrando página _PAGE_ de _PAGES_",
                                                                                            "infoEmpty": "No existen Registros.",
                                                                                            "search": "Buscar:",
                                                                                            "processing": "Procesando...",
                                                                                            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                                                            "paginate": {
                                                                                                "previous": "Anterior",
                                                                                                "next": "Siguiente"
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FIN SISTEMA EQUIPO -->


                                                            <!-- BMS EQUIPO -->
                                                            <div class="tab-pane " id="bms<?php echo $numVista; ?>">
                                                                <h4 class="text-center font-weight-bold"><?php echo 'BMS: ' . $nombreSubEquipo; ?></h4>
                                                                <br><br>

                                                                <div class="row">
                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                        <div class="card card-primary card-outline">
                                                                            <div class="card-body">
                                                                                <h3 class="profile-username text-center">Sources Asociados</h3>
                                                                                <br>


                                                                                <ul class="list-group list-group-unbordered mb-3">
                                                                                    <li class="list-group-item">
                                                                                        <button type="button" title="Asociar Sources" class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#asociarSource<?php echo htmlspecialchars($idSubEquipo); ?>"><i class="fas fa-plus"></i>&nbsp;&nbsp;Asociar Sources</button>
                                                                                    </li>

                                                                                    <?php
                                                                                    $sqlObtenerSource = "SELECT sourceAsocia FROM bms_asocia_source WHERE idEquipoAsocia = $idSubEquipo ORDER BY sourceAsocia ASC";

                                                                                    //                                                    echo $sqlObtenerSource;
                                                                                    $resObtenerSource = mysqli_query($conexion, $sqlObtenerSource);

                                                                                    $sourcesArray = array();

                                                                                    if (mysqli_num_rows($resObtenerSource) > 0) {
                                                                                        while ($rowObtenerSource = $resObtenerSource->fetch_assoc()) {
                                                                                            $sourceAsocia = $rowObtenerSource['sourceAsocia'];
                                                                                            $sourcesArray[] = $rowObtenerSource['sourceAsocia'];
                                                                                            ?>

                                                                                            <li style="vertical-align: middle" class="list-group-item">
                                                                                                <?php echo $sourceAsocia; ?>
                                                                                                <a href="#" onclick="confirmDelete('<?php echo $sourceAsocia; ?>'); return false;" class="btn btn-danger btn-sm float-right"><i class="fas fa-times"></i></a>
                                                                                            </li>
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </ul>

                                                                                <div class="modal fade" id="asociarSource<?php echo htmlspecialchars($idSubEquipo); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-info">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">Asociar Sources</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <form action="../sdo-funciones/bms_asociarSourcesEquipos.php" method="POST" enctype="multipart/form-data">
                                                                                                <div class="modal-body">
                                                                                                    <input type="hidden" name="idEquipamiento" id="idEquipamiento" value="<?php echo $subEquipoId; ?>">
                                                                                                    <input type="hidden" name="idEquipamientoPadre" id="idEquipamientoPadre" value="<?php echo $subEquipoIdEquipoPadre ?>">
                                                                                                    <div class="form-row">

                                                                                                        <div class="form-group col-12">
                                                                                                            <label for="sourcesParaAsociar">Listado de Sources<code>*</code></label>
                                                                                                            <div class="input-group mb-3">
                                                                                                                <select class="form-control listadoDataSource" required id="sourcesParaAsociar" name="sourcesParaAsociar[]" multiple="multiple">
                                                                                                                    <option value="Seleccione..." style="font-weight: bold" disabled>Seleccione...</option>
                                                                                                                    <?php
                                                                                                                    // Conectar a la base de datos y consultar las fuentes
                                                                                                                    $sqlSource = "SELECT sourceAlarma FROM bms_alarmas_equipamiento GROUP BY sourceAlarma ORDER BY sourceAlarma";
                                                                                                                    $resSource = mysqli_query($conexion, $sqlSource);

                                                                                                                    if ($resSource->num_rows > 0) {
                                                                                                                        while ($rowSource = $resSource->fetch_assoc()) {
                                                                                                                            $sourceAlarma = trim($rowSource['sourceAlarma']);
                                                                                                                            echo "<option value=\"" . htmlspecialchars($sourceAlarma) . "\">" . htmlspecialchars($sourceAlarma) . "</option>";
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                </select>


                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="submit" class="btn btn-success"><i class="fas fa-arrow-circle-up"></i>&nbsp;&nbsp;Asociar</button>
                                                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                                                                </div>
                                                                                            </form>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-9 col-lg-8 col-md-6 col-12">

                                                                        <div class="card card-primary card-outline">
                                                                            <div class="card-body">
                                                                                <h3 class="profile-username text-center">Tabla de Alarmas</h3>
                                                                                <br>
                                                                                <br>
                                                                                <div class="table-responsive">
                                                                                    <input type="hidden" name="idSubEquipoCargaAlarmas" id="idSubEquipoCargaAlarmas" value="<?php echo $idSubEquipo; ?>">
                                                                                    <table id="bms_alarmas_equipos" class="table table-bordered">
                                                                                        <thead class="bg-secondary text-center">
                                                                                        <tr>
                                                                                            <th>Fecha</th>
                                                                                            <th>Source</th>
                                                                                            <th>Estado</th>
                                                                                            <th>Clase</th>
                                                                                            <th>Mensaje</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        // Escapa y formatea los valores del array para la consulta SQL
                                                                                        $escapedSources = array_map(function ($value) use ($conexion) {
                                                                                            return "'" . mysqli_real_escape_string($conexion, $value) . "'";
                                                                                        }, $sourcesArray);

                                                                                        // Convierte el array de valores en una cadena separada por comas
                                                                                        $sourceList = implode(', ', $escapedSources);

                                                                                        // Construye la consulta SQL
                                                                                        $sqlTableAlarmasEquipos = "SELECT * FROM bms_alarmas_equipamiento WHERE sourceAlarma IN ($sourceList)";

                                                                                        // Ejecuta la consulta
                                                                                        $resTableAlarmas = mysqli_query($conexion, $sqlTableAlarmasEquipos);

                                                                                        // Verifica si la consulta se ejecutó correctamente
                                                                                        if ($resTableAlarmas && mysqli_num_rows($resTableAlarmas) > 0) {
                                                                                            while ($rowTableAlarmas = $resTableAlarmas->fetch_assoc()) {
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td><?php echo $rowTableAlarmas['fechaAlarma'] ?></td>
                                                                                                    <td><?php echo $rowTableAlarmas['sourceAlarma'] ?></td>
                                                                                                    <td><?php echo $rowTableAlarmas['stateAlarma'] ?></td>
                                                                                                    <td><?php echo $rowTableAlarmas['classAlarma'] ?></td>
                                                                                                    <td><?php echo $rowTableAlarmas['mensajeAlarma'] ?></td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                        }

                                                                                        ?>
                                                                                        </tbody>
                                                                                        <tfoot class="bg-secondary text-center">
                                                                                        <tr>
                                                                                            <th>Fecha</th>
                                                                                            <th>Source</th>
                                                                                            <th>Estado</th>
                                                                                            <th>Clase</th>
                                                                                            <th>Mensaje</th>
                                                                                        </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FIN BMS EQUIPO -->


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <?php

                                    } else {

                                        echo '';
                                    }
                                }

                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
