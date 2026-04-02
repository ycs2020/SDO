<div style="font-size: 85%;" class="card-body">

    <h5>
        <b>Organigrama</b>
    </h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="hbqp_organigrama.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-project-diagram"></i><br>Organigrama</a>
        </div>

    </div>
    <hr>

    <h5>
        <b>Listado de Informes EDAN</b>
    </h5>
    <br>
    <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3 col-12">

            <a href="listar_informe_edan_especialidades.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>Informes EDAN</a>
        </div>
    </div>
    <hr>

    <?php

    if ($idDepartamento == 8 ) {
        ?>


        <h5><b>Solicitudes de Compra</b></h5>
        <br>
        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-3 col-12">

                <a href="soliCompra_sdo_administracion.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>SC - SDO</a>
            </div>
        </div>
        <hr>
        <?php
    }

    if ($idDepartamento == 4) {
        ?>
        <h5>
            <b>Reportes Electricos</b>
        </h5>
        <br>
        <div class="row">
            <div class="col-lg-2 col-12">
                <!-- small box -->
                <a href="reportes_electricos.php" class="btn btn-block bg-gradient-primary btn-lg"><i class="fas fa-file"></i><br>Reportes Electricos</a>
            </div>
        </div>
        <hr>
        <?php
    }
    ?>


    <h5>
        <b>Listado de Equipamiento</b>
    </h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="equipamiento_hbqp.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-desktop"></i><br>Listado Equipos</a>

        </div>

        <?php

        if ($idDepartamento == 10) {
            ?>

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <!-- small box -->
                <a href="protocolo_refrigeradores.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-snowflake"></i><br>Protocolo Refrigeradores</a>

            </div>
            <?php
        }

        ?>
    </div>
    <hr>

    <?php
    if ($idDepartamento == 8) {
        ?>
        <h5>
            <b>Equipamiento - Moviles </b>
        </h5>
        <br>
        <div class="row">

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <!-- small box -->
                <a href="administrar-moviles.php" class="btn btn-block bg-gradient-primary btn-md"><i class="fas fa-ambulance"></i><br>Administrar</a>

            </div>
        </div>
        <hr>


        <!-- VIAJES MOVILIZACION -->
        <h5>
            <b>Viajes - Transporte </b>
        </h5>
        <br>
        <div class="row">

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <!-- small box -->
                <a href="viajes-1.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-car"></i><br>Viajes</a>

            </div>
        </div>
        <hr>


        <!-- VIAJES MOVILIZACION -->

        <?php
    }

    ?>

    <?php
    $lopuedever = 'NO';
    if ($lopuedever == 'NO') {
        ?>
        <h5>
            <b>Orden de Trabajo</b>
        </h5>
        <br>
        <div class="row">
            <?php
            $contarOT = contarOTTecnico($nomUsuario);
            if ($contarOT > 0) {
                ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>OT Activas<span class="cantidad-badge cantidad4"><i class="fas fa-flag"><span id="cantidad4">0</span></i></span></a>
                </div>
                <script>
                    document.getElementById("cantidad4").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                </script>
            <?php
            }else{
            ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">

                    <a href="trabajos_general_listado.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-list"></i><br>OT Activas</a>
                </div>
                <?php
            }
            ?>

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <a href="trabajos_general_listado_finalizados.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-list"></i><br>OT Terminadas</a>
            </div>
        </div>

        <hr>
        <?php
    }
    ?>
    <?php

    $mostrarOcultar = deshabilitarOpciones();

    if ($mostrarOcultar == 'Mostrar') {
        ?>
        <!-- HOJAS TECNICAS -->
        <h5>
            <b>Hojas Técnicas</b>
        </h5>
        <br>
        <div class="row">
            <?php
            if ($idDepartamento == '6') {
                ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <!-- small box -->
                    <a href="ordenes-formulario-general-inge-clinica.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Crear HT</a>

                </div>
                <?php
            } else {
                ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <!-- small box -->
                    <a href="ordenes-formulario-general.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Crear HT</a>

                </div>
                <?php
            }
            ?>

            &nbsp; <?php
            if ($idDepartamento == '10') {
                ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <!-- small box -->
                    <a href="ordenes-listado-general1.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-book"></i><br>Historial HT</a>

                </div>
                <?php
            } else {
                ?>
                <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                    <!-- small box -->
                    <a href="ordenes-listado-general.php" class="btn btn-block bg-gradient-secondary btn-md"><i class="nav-icon fas fa-book"></i><br>Historial HT</a>

                </div>
                <?php
            }
            ?>

        </div>

        <?php
    }
    ?>


    <!-- HOJAS TECNICAS -->

    <?php


    if ($nomUsuario == 'Técnico Pruebas') {
        ?>
        <hr>

        <h5>
            <b>Reportes - Turno Electricidad</b>
        </h5>
        <br>
        <div class="row">

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <a href="electricidad_reportes_turno.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-file"></i><br>Reporte de Turno</a>

            </div>
        </div>


        <?php
    }

    ?>
    <hr>
    <h5>
        <b>Solicitudes de Soporte</b>
    </h5>
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

    <?php
    if ($idDepartamento == 2 || $departamentoPersonal == 'Incendio y Seguridad') {
        ?>
        <hr>
        <h5>
            <b>Combustible </b>
        </h5>
        <br>
        <div class="row">

            <div class="col-xl-2 col-lg-3 col-md-3 col-12">
                <!-- small box -->
                <a href="combustible_mediciones.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump"></i><br>Medici&oacute;n Combustible</a>

            </div>

        </div>


        <?php
    }
    ?>
    <hr>
    <h5>
        <b>Consumos Basicos</b>
    </h5>
    <br>
    <div class="row">

        <div class="col-xl-2 col-lg-3 col-md-3 col-12">
            <!-- small box -->
            <a href="consumos_basicos.php" class="btn btn-block bg-gradient-primary btn-md"><i class="nav-icon fas fa-gas-pump"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-tint"></i>&nbsp;&nbsp;<i class="nav-icon fas fa-bolt"></i><br>Consumos Basicos</a>

        </div>
    </div>


    <!-- /.card-body -->
</div>