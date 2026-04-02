<div style="font-size: 85%;" class="card-body">
    <h5><b>Asistencia</b></h5>
    <br>
    <div class="row">
        <div class="col-lg-2 col-12">
            <button class="btn btn-block bg-gradient-primary btn-lg" data-toggle="modal" data-target="#modalAsistencia">
                <i class="fas fa-calendar-check"></i>
                <br>
                Asistencia
            </button>
        </div>

        <div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar Asistencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../sdo-funciones/asistencia_registrar.php" method="POST">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombreFuncionario" style="font-size:15px">Nombre Funcionario:<code>*</code></label>
                                <input type="text" class="form-control" required id="nombreFuncionario" name="nombreFuncionario" placeholder="Nombre de Funcionario" value="<?php echo $nomUsuario; ?>" readonly="readonly">
                            </div>
                            <?php
                            $now = date("Y-m-d");
                            ?>
                            <div class="form-group">
                                <label for="fechaAsistencia" style="font-size:15px">Fecha Asistencia:<code>*</code></label>
                                <input type="date" class="form-control" required id="fechaAsistencia" name="fechaAsistencia" value="<?php echo $now; ?>" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label for="turnoFuncionario" style="font-size:15px">Turno Funcionario:<code>*</code></label>
                                <select id="turnoFuncionario" name="turnoFuncionario" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Turno Dia">Turno Día</option>
                                    <option value="Turno Noche">Turno Noche</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="departamentoFuncionario" style="font-size:15px">Departamento de Turno:<code>*</code></label>
                                <select id="departamentoFuncionario" name="departamentoFuncionario" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <?php
                                    $departamentos = [];

                                    $queryDepartamentos1 = "SELECT * FROM departamentos WHERE nomDepartamento NOT IN('Informática', 'Ingeniería Operacional', 'Control Centralizado')";
                                    $resDepartamentos1 = mysqli_query($conexion, $queryDepartamentos1);

                                    // Guardar en array con nombre modificado si aplica
                                    while ($row = $resDepartamentos1->fetch_assoc()) {
                                        $idDepa = $row['idDepartamento'];
                                        $nomDepa1 = $row['nomDepartamento'];

                                        if ($nomDepa1 == 'Instrumentación') {
                                            $nomDepa1 = 'Control Centralizado';
                                        }

                                        $departamentos[] = ['id' => $idDepa, 'nombre' => $nomDepa1];
                                    }

                                    // Ordenar alfabéticamente por el nombre mostrado
                                    usort($departamentos, function ($a, $b) {
                                        return strcmp($a['nombre'], $b['nombre']);
                                    });


                                    foreach ($departamentos as $dep) {
                                        if($departamentoPersonal == 'Instrumentación'){
                                            $departamentoPersonal = 'Control Centralizado';
                                        }


                                        $selectedDepartamento = "";
                                        if($dep['nombre'] == $departamentoPersonal){

                                            $selectedDepartamento = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo htmlspecialchars($dep['nombre']) ?>" <?php echo $selectedDepartamento; ?>><?php echo htmlspecialchars($dep['nombre']) ?></option>

                                        <?php
                                    } ?>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" id="btnGuardarAsistencia" class="btn btn-success" disabled><i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const turnoSelect = document.getElementById('turnoFuncionario');
                            const departamentoSelect = document.getElementById('departamentoFuncionario');
                            const submitBtn = document.getElementById('btnGuardarAsistencia');

                            function validarFormulario() {
                                const turnoVal = turnoSelect.value.trim();
                                const departamentoVal = departamentoSelect.value.trim();
                                submitBtn.disabled = (turnoVal === '' || departamentoVal === '');
                            }

                            turnoSelect.addEventListener('change', validarFormulario);
                            departamentoSelect.addEventListener('change', validarFormulario);

                            // Ejecutar al cargar
                            validarFormulario();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <?php


        if($nomUsuario == 'Pablo Sanchez Molina'){
            ?>
            <!--
            <h5><b>Memos y Formatos de Acceso</b></h5>
            <br>
            <div class="row">
                <div class="col-lg-2 col-12">

                    <a href="formatosymemos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Memos y Formatos de Acceso</a>
                </div>
            </div>
            <hr>
            -->
            <h5><b>Reportes Electricos</b></h5>
            <br>
            <div class="row">
                <div class="col-lg-2 col-12">
                    <!-- small box -->
                    <a href="reportes_electricos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Reportes Electricos</a>
                </div>
            </div>
            <hr>
            <!--
            <h5><b>Listado de Equipos Equipo Clinico</b></h5>
            <br>
            <div class="row">
                <div class="col-lg-2 col-12">

                    <a href="../sdo-pdf/SDO_listado_equipos_clinicos_excel.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Listado de Equipos Equipo Clinico</a>
                </div>
            </div>
            <hr>
             -->
            <h5><b>Registros de Pabellones</b></h5>
            <br>
            <div class="row">
                <div class="col-lg-2 col-12">
                    <!-- small box -->
                    <a href="registros_pabellones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-thermometer-half"></i><br>Registros</a>
                </div>
            </div>
            <hr>
            <?php
        }
    ?>
    <h5><b>Memorandum Control de Acceso</b></h5>
    <br>
    <div class="row">
        <div class="col-lg-2 col-12">
            <!-- small box -->
            <a href="memo_control_acceso.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Memorandum</a>
        </div>

    </div>
    <hr>
    <h5><b>Organigrama</b></h5>
    <br>
    <div class="row">
        <div class="col-lg-2 col-12">
            <!-- small box -->
            <a href="hbqp_organigrama.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-project-diagram"></i><br>Organigrama</a>
        </div>

    </div>
    <hr>
    <h5>
        <b>
            Informe EDAN
        </b>
    </h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="listar_informe_edan.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>Listar Informe EDAN</a>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="#" class="btn btn-block bg-gradient-secondary btn-md" data-toggle="modal" data-target="#exampleModal"><i class="nav-icon fas fa-list"></i><br>Habilitar Informe EDAN</a>
        </div>
    </div>
    <hr>
    <h5>
        <b>
            Listado de Equipamiento
        </b>
    </h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-desktop"></i><br>Listado Equipos</a>

        </div>


        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="protocolo_refrigeradores.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-snowflake"></i><br>Protocolo Refrigeradores</a>

        </div>
    </div>
    <hr>
    <h5><b>Recepcion de Documentos</b></h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <?php
            $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
            if ($obtenerHistorialDocu > 0) {
                ?>
                <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="fas fa-clipboard-list"></i><br>Historial Documentos<span class="cantidad-badge cantidad1"><i class="fas fa-flag"><span id="cantidad1">0</span></i></span></a>
                <script>
                    document.getElementById("cantidad1").innerHTML = '&nbsp;<?php echo $obtenerHistorialDocu ?>';
                </script>
            <?php
            }else{
            ?>
                <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br>Historial Documentos</a>
                <?php
            }
            ?>
        </div>
    </div>
    <hr>
    <h5><b>Revisi&oacute;n Formatos de Acceso</b></h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="formulariosCCAA_menuSeleccion.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-clipboard-list"></i><br>Selecci&oacute;n Formularios</a>

        </div>


        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <?php
            $cantidadRecibidos = contarFormulariosRecibidos();

            if ($cantidadRecibidos > 0) {
                ?>

                <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-md" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formatos CA - Recibidos<span class="cantidad-badge"><i class="fas fa-flag"><span id="cantidad">0</span></i></span></a>

                <script>
                    document.getElementById("cantidad").innerHTML = '&nbsp;<?php echo $cantidadRecibidos ?>';
                </script>
            <?php
            }else{
            ?>

                <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-md" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formatos CA - Recibidos</a>

                <?php
            }
            ?>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="formulariosCCAA_listadoFinalizado.php" class="btn btn-block bg-gradient-info btn-md"><i class="fas fa-clipboard-list"></i><br>Formatos CA - Finalizados</a>

        </div>
    </div>
    <hr>
    <!-- RUTINA DIARIA -->
    <h5><b>Rutina Diaria</b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">

            <a href="bitacora-listado.php" class="btn btn-block bg-gradient-primary btn-md">
                <i class="nav-icon fas fa-clock"></i><br>Bitacora
            </a>

        </div>
        <?php
        $contarOT = contarOTTecnico($nomUsuario);
        if ($contarOT > 0) {
            ?>
            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-list"></i><br>OT Activas<span class="cantidad-badge cantidad3"><i class="fas fa-flag"><span id="cantidad3">0</span></i></span></a>
            </div>
            <script>
                document.getElementById("cantidad3").innerHTML = '&nbsp;<?php echo $contarOT ?>';
            </script>
        <?php
        }else{
        ?>
            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-list"></i><br>OT Activas</a>
            </div>
            <?php
        }
        ?>

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <a href="trabajos_general_listado_finalizados.php" class="btn btn-block bg-gradient-info btn-md"><i class="nav-icon fas fa-list"></i><br>OT Terminadas</a>
        </div>

    </div>
    <hr>
    <!-- RUTINA DIARIA -->

    <!-- REPORTES GENERALES -->
    <h5><b>Reportes Generales de Eventos Relevantes</b></h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="reportes-formulario.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Generar Reporte</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="reportes-listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-book"></i><br>Historial Reportes</a>

        </div>
    </div>

    <hr>

    <!-- RECEPCIONES DE TRABAJO -->
    <?php

    $mostrarOcultar = deshabilitarOpciones();

    if ($mostrarOcultar == 'Mostrar') {
        ?>
        <!-- HOJAS TECNICAS -->
        <h5><b>Hojas Técnicas</b></h5>
        <br>
        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <a href="ordenes-formulario-general2.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Crear HT</a>
                <!--<a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Crear HT</a>-->
                <!-- small box -->
            </div>
            &nbsp;
            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <!-- small box -->
                <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-book"></i><br>Historial HT</a>

            </div>
        </div>
        <hr>
        <?php
    }
    ?>


    <!-- HOJAS TECNICAS -->
    <!-- SISTEMA BMS -->
    <h5><b>Observaciones de Sistema BMS</b></h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="sistemas-listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-bars"></i><br>Sistema BMS</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="forzados-listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-cogs"></i><br>Equipos Forzados</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="sistemas-observaciones.php" class="btn btn-block bg-gradient-info btn-md"><i class="nav-icon fas fa-pen"></i><br>Observaciones</a>

        </div>
    </div>
    <hr>

    <!-- SISTEMA BMS -->

    <!-- LEVANTAMIENTO SISTEMA BMS -->
    <h5><b>Levantamiento de Sistema BMS</b></h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="controladores-maestros-listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>Controladores Maestros</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="controladores-esclavos-listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-list"></i><br>Controladores Esclavos</a>

        </div>
        &nbsp;
        <!--                                            <div class="col-xl-2 col-lg-3 col-md-3 col-12">-->
        <!--                                                <a href="detalle_controladores_bms.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Detalle Controladores</a>-->
        <!---->
        <!--                                            </div>-->
    </div>
    <hr>

    <!-- LEVANTAMIENTO SISTEMA BMS -->

    <!-- CONTROL DE ACCESO -->
    <h5><b>Control de Acceso</b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="control-acceso.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-users"></i><br>Listado CCAA</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="parking-acceso.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-parking"></i><br>Parking</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="actas-listado.php" class="btn btn-block bg-gradient-info btn-md"><i class="nav-icon fas fa-book"></i><br>Actas</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="eventos_registro.php" class="btn btn-block bg-gradient-purple btn-md"><i class="nav-icon fas fa-clock"></i><br>Eventos</a>
        </div>
    </div>

    <!-- CONTROL DE ACCESO -->

    <hr>


    <!-- SISTEMA SMARTPSS -->
    <h5><b>Informaci&oacute;n de Puertas</b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="puertas_ccaa_listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-door-closed"></i><br>Puertas</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="ucas_listado.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-door-closed"></i><br>UCAS</a>

        </div>
    </div>

    <hr>

    <!-- SISTEMA SMARTPSS -->
    <h5><b>Combustible </b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump    "></i><br>Medici&oacute;n Combustible</a>

        </div>

    </div>

    <hr>
    <h5><b>Solicitudes de Soporte</b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>

        </div>
        &nbsp;
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-md"><i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>

        </div>
    </div>

    <hr>
    <h5><b>Consumos Basicos</b></h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

        </div>
    </div>


    <!-- /.card-body -->
</div>