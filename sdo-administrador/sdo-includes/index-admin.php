<div class="row mb-2">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-12">
                <!-- Custom Tabs -->
                <div class="card">
                    <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                        <h3 class="card-title p-3"><b>Accesos Rápidos</b></h3>
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_4" data-toggle="tab"> <b>Inicio</b> </a></li>
                        </ul>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_4">

                                <h5><b>Memorandum</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <a href="memorandum_crear.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Crear Memorandum</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <a href="memorandum_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Memorandum</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Recepcion de Documentos</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="recepcion_documento.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Agregar Documento</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <?php
                                        $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
                                        if ($obtenerHistorialDocu > 0) {
                                            ?>
                                            <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Documentos<span class="cantidad-badge cantidad1"><i class="fas fa-flag"><span id="cantidad1">0</span></i></span></a>

                                            <script>
                                                document.getElementById("cantidad1").innerHTML = '&nbsp;<?php echo $obtenerHistorialDocu ?>';
                                            </script>
                                        <?php
                                        }else{
                                        ?>
                                            <a href="listar_recepcion_documento.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="fas fa-clipboard-list"></i><br>Historial Documentos</a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr>

                                <h5><b>Revisi&oacute;n Formatos de Acceso</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="formulariosCCAA_menuSeleccion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-clipboard-list"></i><br>Selecci&oacute;n Formularios</a>

                                    </div>
                                    &nbsp;

                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <?php
                                        $cantidadRecibidos = contarFormulariosRecibidos();

                                        if ($cantidadRecibidos > 0) {
                                            ?>

                                            <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-lg" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formatos - Recibidos<span class="cantidad-badge"><i class="fas fa-flag"><span id="cantidad">0</span></i></span></a>


                                            <script>
                                                document.getElementById("cantidad").innerHTML = '&nbsp;<?php echo $cantidadRecibidos ?>';
                                            </script>
                                        <?php
                                        }else{
                                        ?>

                                            <a href="formulariosCCAA_listado.php" id="formCCAARecibidos" name="formCCAARecibidos" class="btn btn-block bg-gradient-secondary btn-lg" style="position: relative;"><i class="fas fa-clipboard-list"></i><br>Formularios CCAA - Recibidos</a>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="formulariosCCAA_listadoFinalizado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="fas fa-clipboard-list"></i><br>Formatos - Finalizados</a>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <a href="formulariosCCAA_listadoUsuarios.php" class="btn btn-block bg-gradient-yellow btn-lg"><i class="fas fa-clipboard-list"></i><br>Formatos - Usuarios</a>

                                    </div>
                                </div>
                                <hr>
                                <h5><b>Planificador </b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="planificador_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-briefcase"></i><br>Planificador</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <a href="presupuesto-equipo.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-briefcase"></i><br>Presupuesto Equipo</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <a href="inventario-hbqp.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-briefcase"></i><br>Inventario</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Equipamiento HBQP </b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">

                                        <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="fas fa-clipboard-list"></i><br>Listado de sistemas</a>
                                    </div>
                                </div>

                                <?php

                                $userUsuarioMin = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');
                                if ($userUsuarioMin == 'adminsdo') {
                                    ?>
                                    <hr>
                                    <h5><b>Planificador </b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-2 col-12">
                                            <!-- small box -->
                                            <a href="planificador_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-briefcase"></i><br>Planificador</a>
                                        </div>
                                    </div>
                                    <?php
                                }

                                ?>

                                <hr>
                                <?php

                                $mostrarOcultar = deshabilitarOpciones();

                                if ($mostrarOcultar == 'Mostrar') {
                                    ?>
                                    <h5><b>Hojas Técnicas </b></h5>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-2 col-12">

                                            <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Crear</a>
                                        </div>
                                        &nbsp;
                                        <div class="col-lg-2 col-12">

                                            <a href="ordenes-subir-firmas.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Listar</a>
                                        </div>

                                    </div>
                                    <hr>
                                    <?php
                                }
                                ?>


                                <h5><b>Rutina Diaria </b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="bitacora-listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-file"></i><br>Visualizar Bitacora</a>
                                    </div>

                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="trabajos_general_listado_terminado.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-list"></i><br>Trabajos Terminados</a>
                                    </div>


                                    <div class="col-lg-2 col-12">
                                        <a href="ucas_levantamiento_incendio.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-list"></i><br>Levant. UCAS Incendio</a>
                                    </div>

                                </div>
                                <hr>
                                <h5><b>Reportes Generales de Eventos Relevantes </b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="reportes-formulario.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Crear</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="reportes-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Historial</a>
                                    </div>
                                </div>
                                <hr>

                                <h5><b>Observaciones de Sistema BMS</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="sistemas-listado.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Sistemas</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="forzados-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-cogs"></i><br>Equipos Forzados</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="sistemas-observaciones.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-book"></i><br>Observaciones</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Levantamiento de Sistema BMS</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="controladores-maestros-listado.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-list"></i><br>Sistemas Maestros</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="controladores-esclavos-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Controladores</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Administraci&oacute;n de Jefes y Tecnicos</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="jefes-administrar.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-users"></i><br>Encargados</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="personal-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-user-tag"></i><br>Técnicos</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="profesionales-administrar.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-user-tie"></i><br>Profesionales</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Solicitudes de Soporte</b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_recibidas.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-ticket-alt"></i><br>Tickets Recibidos</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_finalizadas.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-check"></i><br>Tickets Finalizados</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="solicitudes_canceladas.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-times"></i><br>Tickets Cancelados</a>
                                    </div>
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="usuarios_tickets.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-list"></i><br>Listado de Usuarios</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Control de Acceso</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="control-acceso.php" class="btn btn-block bg-gradient-primary btn-lg"> <i class="nav-icon fas fa-list-ol"></i><br>Listado</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="actas-listado.php" class="btn btn-block bg-gradient-secondary btn-lg"> <i class="nav-icon fas fa-book"></i><br>Administrar</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="parking-acceso.php" class="btn btn-block bg-gradient-info btn-lg"><i class="nav-icon fas fa-parking"></i><br>Parking</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="llaves_entregadas.php" class="btn btn-block bg-gradient-warning btn-lg"><i class="nav-icon fas fa-key"></i><br>Llaves Entregadas</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="eventos_registro.php" class="btn btn-block bg-gradient-purple btn-lg"><i class="nav-icon fas fa-clock"></i><br>Eventos</a>
                                    </div>
                                </div>
                                <hr>


                                <!-- SISTEMA SMARTPSS -->
                                <h5><b>Informaci&oacute;n de Puertas</b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-md-2 col-12">
                                        <!-- small box -->
                                        <a href="puertas_ccaa_listado.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado Puertas</a>

                                    </div>
                                    &nbsp;
                                    <div class="col-md-2 col-12">
                                        <!-- small box -->
                                        <a href="ucas_listado.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-door-closed"></i><br>Listado de UCAS</a>

                                    </div>
                                </div>

                                <hr>

                                <!-- SISTEMA SMARTPSS -->
                                <h5><b>Solicitudes de Compra</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="soliCompra_pm_administracion.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-list"></i><br>SC - Puesta en marcha</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-list"></i><br>SC - SDO</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="soliCompra_sdo_resumen.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-list"></i><br>SC - Resumen</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Bodega Pañol</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="panel-arriendo-retiro.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-store"></i><br>Administrar Productos</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="bodega-administrar.php" class="btn btn-block bg-gradient-secondary btn-lg"><i class="nav-icon fas fa-tools"></i><br>Ingresar Herramienta</a>
                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="materiales-administrar.php" class="btn btn-block bg-gradient-info btn-lg"> <i class="nav-icon fas fa-box"></i><br>Ingresar Materiales</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Viajes Transporte - Movilización</b></h5>

                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-car"></i><br>Ingresar Viaje</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Combustible</b></h5>
                                <br>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-gas-pump"></i><br>Registrar Medición</a>
                                    </div>
                                </div>
                                <hr>
                                <h5><b>Consumos Basicos</b></h5>
                                <br>
                                <div class="row">

                                    <div class="col-lg-2 col-12">
                                        <!-- small box -->
                                        <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- ./card -->
            </div>
            <!-- /.col -->
        </div>
    </div>

</div>