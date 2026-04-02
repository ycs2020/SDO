<div style="font-size: 85%;" class="card-body">
    <?php
    $obtenerHistorialDocu = obtenerHistorialDocu($nomUsuario);
    $cantidadRecibidos = contarFormulariosRecibidos();
    $contarOT = contarOTTecnico($nomUsuario);


    $datosRedireccion = [
        "Organigrama" => [
            ["id" => 1, "nombre" => "Organigrama", "ruta" => "hbqp_organigrama.php", "icono" => '<i class="nav-icon fas fa-project-diagram"></i>', "modal" => ""],
        ],
        "Informe EDAN" => [
            ["id" => 1, "nombre" => "Listar Informe EDAN", "ruta" => "listar_informe_edan.php", "icono" => '<i class="nav-icon fas fa-list"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Habilitar Informe EDAN", "ruta" => "#", "icono" => '<i class="nav-icon fas fa-list"></i>', "modal" => "exampleModal"],
        ],
        "Equipamiento" => [
            ["id" => 1, "nombre" => "Equipamiento HBQP", "ruta" => "equipamiento_hbqp.php", "icono" => '<i class="nav-icon fas fa-desktop"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Protocolo Refrigeradores", "ruta" => "protocolo_refrigeradores.php", "icono" => '<i class="nav-icon fas fa-snowflake"></i>', "modal" => ""],
        ],
        "Documentos" => [
            ["id" => 1, "nombre" => "Historial Documentos", "ruta" => "listar_recepcion_documento.php", "icono" => '<i class="nav-icon fas fa-clipboard-list"></i>', "modal" => "", "cantidadDoc" => "$obtenerHistorialDocu"],
        ],
        "Formatos de Solicitud de Accesos" => [
            ["id" => 1, "nombre" => "Selecci&oacute;n Formularios", "ruta" => "formulariosCCAA_menuSeleccion.php", "icono" => '<i class="nav-icon fas fa-clipboard-list"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Formatos CA - Recibidos", "ruta" => "formulariosCCAA_listado.php", "icono" => '<i class="nav-icon fas fa-clipboard-list"></i>', "modal" => "", "cantidadRecibidos" => "$cantidadRecibidos"],
            ["id" => 3, "nombre" => "Formatos CA - Finalizados", "ruta" => "formulariosCCAA_listadoFinalizado.php", "icono" => '<i class="nav-icon fas fa-clipboard-list"></i>', "modal" => ""],
        ],
        "Rutina Diaria" => [
            ["id" => 1, "nombre" => "Bitacora", "ruta" => "bitacora-listado.php", "icono" => '<i class="nav-icon fas fa-clock"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "OT Activas", "ruta" => "trabajos_general_listado.php", "icono" => '<i class="nav-icon fas fa-list"></i>', "modal" => "", "contarOT" => "$contarOT"],
            ["id" => 3, "nombre" => "OT Terminadas", "ruta" => "trabajos_general_listado_finalizados.php", "icono" => '<i class="nav-icon fas fa-clipboard-list"></i>', "modal" => ""],
        ],
        "Reportes de Turno" => [
            ["id" => 1, "nombre" => "Reportes - Formulario", "ruta" => "reportes-formulario.php", "icono" => '<i class="nav-icon fas fa-pen"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Reportes - Listado", "ruta" => "reportes-listado.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
        ],
        "Hojas Técnicas" => [
            ["id" => 1, "nombre" => "HT - Formulario", "ruta" => "ordenes-formulario-general2.php", "icono" => '<i class="nav-icon fas fa-pen"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "HT - Listado", "ruta" => "ordenes-listado-general.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
        ],
        "Sistema BMS" => [
            ["id" => 1, "nombre" => "Sistema BMS", "ruta" => "sistemas-listado.php", "icono" => '<i class="nav-icon fas fa-cog"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Equipos Forzados", "ruta" => "forzados-listado.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
            ["id" => 3, "nombre" => "Observaciones", "ruta" => "sistemas-observaciones.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
            ["id" => 4, "nombre" => "Controladores Maestros", "ruta" => "controladores-maestros-listado.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
            ["id" => 5, "nombre" => "Controladores Esclavos", "ruta" => "controladores-esclavos-listado.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
//            ["id" => 6, "nombre" => "Detalle Controladores", "ruta" => "detalle_controladores_bms.php", "icono" => '<i class="nav-icon fas fa-file"></i>', "modal" => ""],
        ],
        "Control de Acceso" => [
            ["id" => 1, "nombre" => "Listado CCAA", "ruta" => "control-acceso.php", "icono" => '<i class="nav-icon fas fa-door-closed"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Parking", "ruta" => "parking-acceso.php", "icono" => '<i class="nav-icon fas fa-parking"></i>', "modal" => ""],
            ["id" => 3, "nombre" => "Actas", "ruta" => "actas-listado.php", "icono" => '<i class="nav-icon fas fa-book"></i>', "modal" => ""],
            ["id" => 4, "nombre" => "Llaves Entregadas", "ruta" => "llaves_entregadas.php", "icono" => '<i class="nav-icon fas fa-key"></i>', "modal" => ""],
            ["id" => 5, "nombre" => "Eventos", "ruta" => "eventos_registro.php", "icono" => '<i class="nav-icon fas fa-clock"></i>', "modal" => ""],
        ],
        "Informaci&oacute;n de Puertas" => [
            ["id" => 1, "nombre" => "Puertas", "ruta" => "puertas_ccaa_listado.php", "icono" => '<i class="nav-icon fas fa-door-closed"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "UCAS", "ruta" => "ucas_listado.php", "icono" => '<i class="nav-icon fas fa-door-closed"></i>', "modal" => ""],
        ],
        "Combustible" => [
            ["id" => 1, "nombre" => "Medici&oacute;n Combustible", "ruta" => "combustible_mediciones.php", "icono" => '<i class="nav-icon fas fa-gas-pump"></i>', "modal" => ""],
        ],
        "Solicitudes de Soporte" => [
            ["id" => 1, "nombre" => "Tickets Recibidos", "ruta" => "solicitudes_recibidas.php", "icono" => '<i class="nav-icon fas fa-ticket-alt"></i>', "modal" => ""],
            ["id" => 2, "nombre" => "Tickets Finalizados", "ruta" => "solicitudes_finalizadas.php", "icono" => '<i class="nav-icon fas fa-check"></i>', "modal" => ""],
            ["id" => 3, "nombre" => "Tickets Cancelados", "ruta" => "solicitudes_canceladas.php", "icono" => '<i class="nav-icon fas fa-times"></i>', "modal" => ""],
        ],
        "Consumos Basicos" => [
            ["id" => 1, "nombre" => "Consumos Basicos", "ruta" => "consumos_basicos.php", "icono" => '<i class="nav-icon fas fa-gas-pump"></i>', "modal" => ""],
        ]




    ];

//    $contador = 1; // Inicializar contador

    foreach ($datosRedireccion as $categoria => $filas) {
        // Mostrar el nombre de la categoría
        $contador = $filas['id'];
        ?>
        <div class="row">
            <div class="col-12">
                <h5><b><?php echo $categoria; ?></b></h5>
            </div>
            <br>
            <?php

            foreach ($filas as $fila) {
                ?>
                <?php
                $color = '';
                switch ($fila['id']) {
                    case 1:
                        $color = 'bg-gradient-primary';
                        break;
                    case 2:
                        $color = 'bg-gradient-secondary';
                        break;
                    case 3:
                        $color = 'bg-gradient-info';
                        break;
                    case 4:
                        $color = 'bg-gradient-yellow';
                        break;
                    case 5:
                        $color = 'bg-gradient-purple';
                        break;
                    case 6:
                        $color = 'bg-gradient-danger';
                        break;
                    case 0:
                        $color = 'bg-gradient-warning';
                        break;
                }

                if (!empty($fila['modal'])) {
                    ?>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md" data-toggle="modal" data-target="#<?php echo $fila['modal']; ?>">
                            <?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?>
                        </a>
                    </div>
                    <?php
                } else if ($fila['cantidadDoc'] > 0) {
                    ?>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md">
                            <?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?>

                            <span class="cantidad-badge cantidad1">
                                <i class="fas fa-flag">
                                    <span id="cantidad1">0</span>
                                </i>
                            </span>
                        </a>
                        <script>
                            document.getElementById("cantidad1").innerHTML = '&nbsp;<?php echo $obtenerHistorialDocu ?>';
                        </script>
                    </div>
                    <?php
                } else if ($fila['cantidadRecibidos'] > 0) {
                    ?>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md">
                            <?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?>

                            <span class="cantidad-badge indicaRecibidos">
                                <i class="fas fa-flag">
                                    <span id="indicaRecibidos">0</span>
                                </i>
                            </span>
                        </a>
                        <script>
                            document.getElementById("indicaRecibidos").innerHTML = '&nbsp;<?php echo $cantidadRecibidos ?>';
                        </script>
                    </div>
                    <?php
                } else if($fila['contarOT'] > 0) {
                    ?>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-3">
                        <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md">
                            <?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?>

                            <span class="cantidad-badge indicaOTActiva">
                                <i class="fas fa-flag">
                                    <span id="indicaOTActiva">0</span>
                                </i>
                            </span>
                        </a>
                        <script>
                            document.getElementById("indicaOTActiva").innerHTML = '&nbsp;<?php echo $contarOT ?>';
                        </script>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-3">



                        <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md">
                            <?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?>
                        </a>
                    </div>
                    <?php
                }

                ?>
                <?php
//                $contador++;
            }
            ?>

        </div>

        <hr>
        <?php
    }
    ?>


    <!-- /.card-body -->
</div>