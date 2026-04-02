<?php

?>
<div class="row d-flex align-items-stretch">
    <div class="col-lg-12 col-md-12 col-12 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-center">
                <h3 class="card-title">Pronóstico Meteorológico</h3>
            </div>

            <div class="card-body" style="min-height: 100px;">

                <?php
                // API Key
                $apikey = 'Q4KG2kqXK8pK2rCEisVz4gbPIE7l30Wg';

                // Ubicación
                $location = '-32.8801,-71.2472';

                $url = "https://api.tomorrow.io/v4/weather/realtime?location=" . urlencode($location) . "&apikey={$apikey}&units=metric";

                echo "<script>console.log('$url');</script>";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
                $response = curl_exec($ch);

                if (!curl_errno($ch)) {
                    $data = json_decode($response, true);
                }

                curl_close($ch);

                // Mostrar UV si existe
                if (isset($data['data']['values']['uvIndex'], $data['data']['values']['humidity'])) {

                    $uvIndex = $data['data']['values']['uvIndex'];
                    $humidity = $data['data']['values']['humidity'];

                    // RANGOS UV COMO INDICASTE
                    if ($uvIndex >= 0 && $uvIndex <= 2) {
                        $uvColor = "#2ecc71";  // Verde
                        $uvText = "Bajo";
                    } elseif ($uvIndex >= 3 && $uvIndex <= 5) {
                        $uvColor = "#f1c40f";  // Amarillo
                        $uvText = "Moderado";
                    } elseif ($uvIndex >= 6 && $uvIndex <= 7) {
                        $uvColor = "#e67e22";  // Naranjo
                        $uvText = "Alto";
                    } elseif ($uvIndex >= 8 && $uvIndex <= 10) {
                        $uvColor = "#e74c3c";  // Rojo
                        $uvText = "Muy Alto";
                    } else { // 11+
                        $uvColor = "#8e44ad";  // Morado
                        $uvText = "Extremo";
                    }




                    echo "
                    <div class='text-center p-2 mb-2'
                        style='background:$uvColor; color:white; border-radius:6px;'>
                        <strong>Índice UV Actual:</strong> $uvIndex<br>
                        <small>Nivel: $uvText</small>
                    </div>";

                    echo "
                    <div class='text-center p-2 mb-2'
                        style='background:#b7b723; color:black; border-radius:6px;'>
                        <strong>Humedad:<br>$humidity%</strong>
                    </div>";
                }
                ?>

                <!-- WIDGET DE TOMORROW.IO -->
                <script>
                    (function (d, s, id) {
                        if (d.getElementById(id)) {
                            if (window.__TOMORROW__) {
                                window.__TOMORROW__.renderWidget();
                            }
                            return;
                        }
                        const fjs = d.getElementsByTagName(s)[0];
                        const js = d.createElement(s);
                        js.id = id;
                        js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    })(document, 'script', 'tomorrow-sdk');
                </script>

                <div class="tomorrow"
                        data-location-id="019092"
                        data-language="ES"
                        data-unit-system="METRIC"
                        data-skin="dark"
                        data-widget-type="upcoming"
                        style="padding-bottom: 10px; position:relative; width: 100%; max-width: none;">
                </div>

                <style>
                    .tomorrow {
                        width: 100% !important;
                        max-width: 100% !important;
                    }
                </style>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
            <div class="card-body">

                <div class="row">

                    <?php
                    $userMinusSecret = mb_convert_case($userUsuario, MB_CASE_LOWER, 'UTF-8');
                    $datos = [
                        ["id" => 1, "nombre" => "Informes Control Centralizado", "ruta" => "controlCent_informes.php", "icono" => '<i class="fas fa-file"></i>'],
                        ["id" => 2, "nombre" => "Organigrama HBQP", "ruta" => "hbqp_organigrama.php", "icono" => '<i class="fas fa-project-diagram"></i>'],
                        ["id" => 3, "nombre" => "Turnos Control Centralizado", "ruta" => "turnos_calendario.php", "icono" => '<i class="fas fa-users"></i>'],
                        ["id" => 4, "nombre" => "Asistencia", "ruta" => "asistencia_registro.php", "icono" => '<i class="fas fa-calendar-check"></i>'],
                        ["id" => 5, "nombre" => "Listado Funcionarios", "ruta" => "../sdo-pdf/SDO-Excel-Funcionarios.php", "icono" => '<i class="fas fa-users"></i>'],
                        ["id" => 6, "nombre" => "Listado Puertas - Solicitudes Accesos", "ruta" => "../sdo-pdf/SDO-Formularios-CCAA-Accesos.php", "icono" => '<i class="fas fa-list"></i>'],
                    ];
                    // Obtener una lista de nombres para ordenar
                    $nombres = array_column($datos, 'nombre');

                    // Ordenar el array basado en los nombres
                    array_multisort($nombres, SORT_ASC, $datos);

                    $contador = 1; // Inicializar contador

                    foreach ($datos as $fila) {
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                            <?php
                            $color = '';
                            switch ($contador % 4) {
                                case 1:
                                    $color = 'bg-gradient-primary';
                                    break;
                                case 2:
                                    $color = 'bg-gradient-secondary';
                                    break;
                                case 3:
                                    $color = 'bg-gradient-info';
                                    break;
                                case 0:
                                    $color = 'bg-gradient-warning';
                                    break;
                            }
                            ?>
                            <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md"><?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?></a>

                        </div>
                        <?php
                        $contador++;
                    }

                    $ipPermitida = '10.69.250.151';

                    $ipCliente = $_SERVER['REMOTE_ADDR'];

                    if ($ipCliente == $ipPermitida || $nomUsuario = 'Jorge Cantillano') {
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">

                            <a href="avisos_relevantes_administracion.php" style="font-size: 12pt;" class="btn btn-block bg-gradient-success btn-md">
                                <i class="fas fa-exclamation-triangle"></i>
                                <br>
                                Avisos Relevantes
                            </a>

                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">

                            <button class="btn btn-block bg-gradient-success btn-md" data-toggle="modal" data-target="#modalAviso">
                                <i class="fas fa-exclamation-triangle"></i>
                                <br>
                                Avisos Relevantes
                            </button>


                        </div>
                        <div class="modal fade" id="modalAviso" tabindex="-1" role="dialog" aria-labelledby="modalAvisoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h5 class="modal-title" id="exampleModalLabel">Avisos Relevantes</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h4 class="font-weight-bold">“Esta función está actualmente en fase de desarrollo.”</h4>
                                        <br>
                                        <img src="../dist/img/patan_risa.gif" alt="Aviso" class="img-fluid">
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php
                    }

                    ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex p-0" style="background-color: #03cea4;">
                <h3 class="card-title p-3">
                    <b>Accesos Rápidos</b>
                </h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab_4" data-toggle="tab">
                            <b>Inicio</b>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_4">

                        <div class="row">
                            <?php
                            $datos = [
                                ["id" => 1, "nombre" => "Documentos - Agregar", "ruta" => "recepcion_documento.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 2, "nombre" => "Documentos - Historial", "ruta" => "listar_recepcion_documento.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 3, "nombre" => "Formularios - Seleccion", "ruta" => "formulariosCCAA_menuSeleccion.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 4, "nombre" => "Formularios - Recibidos", "ruta" => "formulariosCCAA_listado.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 5, "nombre" => "Formularios - Finalizados", "ruta" => "formulariosCCAA_listadoFinalizado.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 6, "nombre" => "Formularios - Usuarios", "ruta" => "formulariosCCAA_listadoUsuarios.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 7, "nombre" => "Planificador", "ruta" => "planificador_administracion.php", "icono" => '<i class="nav-icon fas fa-briefcase"></i>'],
                                ["id" => 8, "nombre" => "Inventario - Presupuesto", "ruta" => "presupuesto-equipo.php", "icono" => '<i class="nav-icon fas fa-briefcase"></i>'],
                                ["id" => 9, "nombre" => "Inventario - Listado", "ruta" => "inventario-hbqp.php", "icono" => '<i class="nav-icon fas fa-briefcase"></i>'],
                                ["id" => 10, "nombre" => "Equipamiento - Listado", "ruta" => "equipamiento_hbqp.php", "icono" => '<i class="fas fa-clipboard-list"></i>'],
                                ["id" => 11, "nombre" => "Hojas Tecnicas - Agregar", "ruta" => "ordenes-formulario-general.php", "icono" => '<i class="nav-icon fas fa-file"></i>'],
                                ["id" => 12, "nombre" => "Hojas Tecnicas - Historial", "ruta" => "ordenes-subir-firmas.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 13, "nombre" => "Bitacora - Listado", "ruta" => "bitacora-listado.php", "icono" => '<i class="nav-icon fas fa-file"></i>'],
                                ["id" => 14, "nombre" => "Orden de Trabajo - En Proceso", "ruta" => "trabajos_general_listado.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 15, "nombre" => "Orden de Trabajo - Terminadas", "ruta" => "trabajos_general_listado_terminado.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 16, "nombre" => "UCAS - Modulos Incendio", "ruta" => "ucas_levantamiento_incendio.php", "icono" => '<i class="nav-icon fas fa-fire"></i>'],
                                ["id" => 17, "nombre" => "Reportes - Agregar", "ruta" => "reportes-formulario.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 18, "nombre" => "Reportes - Historial", "ruta" => "reportes-administrar.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 19, "nombre" => "Sistema BMS - Listado", "ruta" => "sistemas-listado.php", "icono" => '<i class="nav-icon fas fa-cogs"></i>'],
                                ["id" => 20, "nombre" => "Sistema BMS - Equipos Forzados", "ruta" => "forzados-listado.php", "icono" => '<i class="nav-icon fas fa-cogs"></i>'],
                                ["id" => 21, "nombre" => "Sistema BMS - Resumen", "ruta" => "sistemas-observaciones.php", "icono" => '<i class="nav-icon fas fa-cogs"></i>'],
                                ["id" => 22, "nombre" => "ECBOS - Controladores Maestros", "ruta" => "controladores-maestros-listado.php", "icono" => '<i class="nav-icon fas fa-cogs"></i>'],
                                ["id" => 23, "nombre" => "ECBOS - Controladores Esclavos", "ruta" => "controladores-esclavos-listado.php", "icono" => '<i class="nav-icon fas fa-cogs"></i>'],
                                ["id" => 24, "nombre" => "Funcionarios", "ruta" => "funcionarios-listado.php", "icono" => '<i class="nav-icon fas fa-users"></i>'],
                                ["id" => 25, "nombre" => "Tickets - Recibidos", "ruta" => "solicitudes_recibidas.php", "icono" => '<i class="nav-icon fas fa-ticket-alt"></i>'],
                                ["id" => 26, "nombre" => "Tickets - Finalizados", "ruta" => "solicitudes_finalizadas.php", "icono" => '<i class="nav-icon fas fa-check"></i>'],
                                ["id" => 27, "nombre" => "Tickets - Cancelados", "ruta" => "solicitudes_canceladas.php", "icono" => '<i class="nav-icon fas fa-times"></i>'],
                                ["id" => 28, "nombre" => "Tickets - Usuarios", "ruta" => "usuarios_tickets.php", "icono" => '<i class="nav-icon fas fa-users"></i>'],
                                ["id" => 29, "nombre" => "CCAA - Usuarios", "ruta" => "control-acceso.php", "icono" => '<i class="nav-icon fas fa-users"></i>'],
                                ["id" => 30, "nombre" => "CCAA - Actas", "ruta" => "actas-listado.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 31, "nombre" => "CCAA - Parking", "ruta" => "parking-acceso.php", "icono" => '<i class="nav-icon fas fa-car"></i>'],
                                ["id" => 32, "nombre" => "CCAA - Llaves", "ruta" => "llaves_entregadas.php", "icono" => '<i class="nav-icon fas fa-key"></i>'],
                                ["id" => 33, "nombre" => "CCAA - Eventos", "ruta" => "eventos_registro.php", "icono" => '<i class="nav-icon fas fa-clock"></i>'],
                                ["id" => 34, "nombre" => "CCAA - UCAS", "ruta" => "puertas_ccaa_listado.php", "icono" => '<i class="nav-icon fas fa-door-closed"></i>'],
                                ["id" => 35, "nombre" => "CCAA - Puertas", "ruta" => "ucas_listado.php", "icono" => '<i class="nav-icon fas fa-door-closed"></i>'],
                                ["id" => 36, "nombre" => "SC - Puesta en Marcha", "ruta" => "soliCompra_pm_administracion.php", "icono" => '<i class="nav-icon fas fa-shopping-cart"></i>'],
                                ["id" => 37, "nombre" => "SC - SDO", "ruta" => "soliCompra_sdo_administracion.php", "icono" => '<i class="nav-icon fas fa-shopping-cart"></i>'],
                                ["id" => 38, "nombre" => "SC - Resumen", "ruta" => "soliCompra_sdo_resumen.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 39, "nombre" => "Pañol - Productos", "ruta" => "panel-arriendo-retiro.php", "icono" => '<i class="fas fa-store"></i>'],
                                ["id" => 40, "nombre" => "Pañol - Herramientas", "ruta" => "bodega-administrar.php", "icono" => '<i class="nav-icon fas fa-tools"></i>'],
                                ["id" => 41, "nombre" => "Pañol - Materiales", "ruta" => "materiales-administrar.php", "icono" => '<i class="nav-icon fas fa-tools"></i>'],
                                ["id" => 42, "nombre" => "Movilización - Viaje", "ruta" => "viajes-1.php", "icono" => '<i class="fas fa-car"></i>'],
                                ["id" => 43, "nombre" => "Combustible - Medición", "ruta" => "combustible_mediciones.php", "icono" => '<i class="fas fa-gas-pump"></i>'],
                                ["id" => 44, "nombre" => "Consumos Básicos", "ruta" => "consumos_basicos.php", "icono" => '<i class="nav-icon fas fa-gas-pump"></i>'],
                                ["id" => 45, "nombre" => "Memorandum - Agregar", "ruta" => "memorandum_crear.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 46, "nombre" => "Memorandum - Historial", "ruta" => "memorandum_listado.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 47, "nombre" => "Ticket Sigh - Listado", "ruta" => "tickets_sigh.php", "icono" => '<i class="nav-icon fas fa-list"></i>'],
                                ["id" => 48, "nombre" => "Ticket Sigh - Dashboard", "ruta" => "dashboard-ticket-sigh.php", "icono" => '<i class="nav-icon fas fa-tachometer-alt"></i>'],
                                ["id" => 49, "nombre" => "LLE - Listado", "ruta" => "Listado_LLE.php", "icono" => '<i class="nav-icon fas fa-list-alt"></i>'],
                                ["id" => 50, "nombre" => "EDAN - Listado", "ruta" => "listar_informe_edan.php", "icono" => '<i class="nav-icon fas fa-list-alt"></i>'],
                                ["id" => 51, "nombre" => "Registro - Comunicacion", "ruta" => "registros_comunicacion.php", "icono" => '<i class="nav-icon fas fa-list-alt"></i>'],
                                ["id" => 52, "nombre" => "BMS - Reporte Alarmas", "ruta" => "bms_reporte_admin.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 53, "nombre" => "Licitaciones", "ruta" => "licitaciones_admin.php", "icono" => '<i class="nav-icon fas fa-book"></i>'],
                                ["id" => 54, "nombre" => "SC - Cotiza Productos", "ruta" => "soliCompra_sdo_cotiza_productos.php", "icono" => '<i class="nav-icon fas fa-shopping-cart"></i>'],
                                ["id" => 55, "nombre" => "Protocolo - Grupos CCAA", "ruta" => "ccaa_grupos_protocolo.php", "icono" => '<i class="nav-icon fas fa-users"></i>'],
                                ["id" => 56, "nombre" => "Protocolo - Perfiles CCAA", "ruta" => "ccaa_perfiles_protocolo.php", "icono" => '<i class="nav-icon fas fa-users"></i>'],

                            ];
                            // Obtener una lista de nombres para ordenar
                            $nombres = array_column($datos, 'nombre');

                            // Ordenar el array basado en los nombres
                            array_multisort($nombres, SORT_ASC, $datos);

                            $contador = 1; // Inicializar contador

                            foreach ($datos as $fila) {
                                ?>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                                    <?php
                                    $color = '';
                                    switch ($contador % 4) {
                                        case 1:
                                            $color = 'bg-gradient-primary';
                                            break;
                                        case 2:
                                            $color = 'bg-gradient-secondary';
                                            break;
                                        case 3:
                                            $color = 'bg-gradient-info';
                                            break;
                                        case 0:
                                            $color = 'bg-gradient-warning';
                                            break;
                                    }
                                    ?>
                                    <a href="<?php echo $fila['ruta']; ?>" style="font-size: 12pt;" class="btn btn-block <?php echo $color; ?> btn-md"><?php echo $fila['icono'] . '<br>' . $fila['nombre']; ?></a>

                                </div>
                                <?php
                                $contador++;
                            }
                            ?>
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