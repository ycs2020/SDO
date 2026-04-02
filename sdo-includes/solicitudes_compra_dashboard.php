<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Dashboard de Informaci&oacute;n</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioSC = date('Y', time());
                $sqlAniosSC = "SELECT DISTINCT YEAR(fechaSoliCompra) AS anio FROM solicitudes_compra_sdo ORDER BY fechaSoliCompra ASC;";
                $resAniosSC = mysqli_query($conexion, $sqlAniosSC);
                while ($rowSC = $resAniosSC->fetch_assoc()) {
                    if ($rowSC['anio'] == $nowAnioSC) {
                        ?>
                        <li class="nav-item"><a class="nav-link active" href="#tabSC<?php echo $rowSC['anio']; ?>" data-toggle="tab"><?php echo $rowSC['anio']; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item"><a class="nav-link" href="#tabSC<?php echo $rowSC['anio']; ?>" data-toggle="tab"><?php echo $rowSC['anio']; ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php
                date_default_timezone_set('America/Santiago');
                $nowAnioSC = date('Y', time());
                $sqlAniosSC = "SELECT DISTINCT YEAR(fechaSoliCompra) AS anio FROM solicitudes_compra_sdo ORDER BY fechaSoliCompra ASC;";
                $resAniosSC = mysqli_query($conexion, $sqlAniosSC);
                while ($rowSC = $resAniosSC->fetch_assoc()) {
                    $anioSC = $rowSC['anio'];

                    $anioAnteriorSC = $anioSC - 1;
                    $nowMesSC = date('m', time());
                    if ($anioSC == $nowAnioSC) {
                        $tabPane = 'active';
                    } else {
                        $tabPane = '';
                    }

                    $sqlContador = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalSolicitudes = obtenerDashboardSC($sqlContador);

                    $sqlContador1 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Autorizado por la S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalAutoSDA = obtenerDashboardSC($sqlContador1);

                    $sqlContador2 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Rechazado por la S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalRechazoSDA = obtenerDashboardSC($sqlContador2);

                    $sqlContador3 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Anulada' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalAnula = obtenerDashboardSC($sqlContador3);

                    $sqlContador4 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'SOLICITADA A SECRETARIA SDO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalSolicitaSDO = obtenerDashboardSC($sqlContador4);

                    $sqlContador5 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'ENTREGADO A LA S.D.A' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalEntregadoSDA = obtenerDashboardSC($sqlContador5);

                    $sqlContador6 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'DERIVADO A ABASTECIMIENTO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalAbastecimiento = obtenerDashboardSC($sqlContador6);

                    $sqlContador7 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Recibido por pañol' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalPanol = obtenerDashboardSC($sqlContador7);

                    $sqlContador8 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Solicitud Completada' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalCompletado = obtenerDashboardSC($sqlContador8);

                    $sqlContador9 = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE estadoSoliCompra LIKE 'Revisada por Secretaría SDO' AND YEAR(fechaSoliCompra) = " . $anioSC;
                    $totalRevisadaSDO = obtenerDashboardSC($sqlContador9);

                    $totalGeneradas = $totalSolicitudes - $totalAnula;
                    if ($totalGeneradas > -1) {
                        $totalGeneradas2 = $totalGeneradas;
                    } else {
                        $totalGeneradas2 = 'Sin Información';
                    }

                    $recibidasSDA = $totalGeneradas2 - $totalSolicitaSDO - $totalRevisadaSDO;

                    ?>

                    <div class="tab-pane <?php echo $tabPane ?>" id="tabSC<?php echo $rowSC['anio']; ?>">
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-gradient-primary">
                                        <h5 class="card-title">Total de Solicitudes de Compra SDO Año <?php echo $anioSC . ': <b>' . $totalSolicitudes . '</b>'; ?></h5>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <p class="font-weight-bold">SC = Solicitud de Compra</p>
                                        <div class="row">

                                            <br>

                                            <!-- /.col -->
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                <p class="text-center">
                                                    <strong>Proceso SDO</strong>
                                                </p>

                                                <div class="progress-group">
                                                    <span class="progress-text">
                                                        SC Generadas</span>
                                                    <span class="float-right"><b><?php echo $totalSolicitudes; ?></b><br></span>

                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-primary" style="width: <?php echo calcularPorcentajesSC($totalSolicitudes, 100, $totalSolicitudes) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-danger" onclick="cargarDatos('anuladas','<?php echo $anioSC; ?>','Solicitudes Anuladas')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Anuladas
                                                    <span class="float-right"><b><?php echo $totalAnula; ?></b>/<?php echo $totalSolicitudes; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-danger" style="width: <?php echo calcularPorcentajesSC($totalAnula, 100, $totalSolicitudes) ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="progress-group">
                                                    <span class="progress-text">
                                                        <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('enCurso','<?php echo $anioSC; ?>','Solicitudes en Curso')">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        SC En Curso
                                                    </span>
                                                    <span class="float-right"><b><?php echo $totalGeneradas2; ?></b>/<?php echo $totalSolicitudes; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalGeneradas2, 100, $totalSolicitudes) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->
                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('porRevisarSDO','<?php echo $anioSC; ?>','Solicitudes Faltantes por Revisar')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Faltantes por Revisar
                                                    <span class="float-right"><b><?php echo $totalSolicitaSDO; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalSolicitaSDO, 100, $totalGeneradas2) ?>%"></div>
                                                    </div>
                                                </div>

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-purple" onclick="cargarDatos('porTramitarSDO','<?php echo $anioSC; ?>','Solicitudes Faltantes por Tramitar')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Faltantes por Tramitar
                                                    <span class="float-right"><b><?php echo $totalRevisadaSDO; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-purple" style="width: <?php echo calcularPorcentajesSC($totalRevisadaSDO, 100, $totalGeneradas2) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-primary" onclick="cargarDatos('enviadasSDA','<?php echo $anioSC; ?>','Solicitudes Enviadas a SDA')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Enviadas a SDA
                                                    <span class="float-right"><b><?php echo $recibidasSDA; ?></b>/<?php echo $totalGeneradas2; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-info" style="width: <?php echo calcularPorcentajesSC($recibidasSDA, 100, $totalGeneradas2) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->


                                                <!-- /.progress-group -->
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                <p class="text-center">
                                                    <strong>Proceso SDA</strong>
                                                </p>



                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-primary" onclick="cargarDatos('recibidaSDA','<?php echo $anioSC; ?>','Solicitudes Pendientes por SDA')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Pendientes por SDA
                                                    <span class="float-right"><b><?php echo $totalEntregadoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-primary" style="width: <?php echo calcularPorcentajesSC($totalEntregadoSDA, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('aprobadaSDA','<?php echo $anioSC; ?>','Solicitudes Por Derivar desde SDA')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Por Derivar desde SDA
                                                    <span class="float-right"><b><?php echo $totalAutoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalAutoSDA, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-danger" onclick="cargarDatos('rechazadaSDA','<?php echo $anioSC; ?>','Solicitudes Rechazadas por SDA')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Rechazadas por SDA
                                                    <span class="float-right"><b><?php echo $totalRechazoSDA; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-danger" style="width: <?php echo calcularPorcentajesSC($totalRechazoSDA, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                <p class="text-center">
                                                    <strong>Proceso Abastecimiento</strong>
                                                </p>

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('abastecimiento','<?php echo $anioSC; ?>','Solicitudes Pendientes por Abastecimiento')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Pendientes por Abastecimiento
                                                    <span class="float-right"><b><?php echo $totalAbastecimiento; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalAbastecimiento, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                                                <p class="text-center">
                                                    <strong>Proceso Pa&ntilde;ol</strong>
                                                </p>

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-warning" onclick="cargarDatos('panol','<?php echo $anioSC; ?>','SC Pendientes por Entregar desde Pañol')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Pendientes por Entregar desde Pa&ntilde;ol
                                                    <span class="float-right"><b><?php echo $totalPanol; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-warning" style="width: <?php echo calcularPorcentajesSC($totalPanol, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->

                                                <div class="progress-group">
                                                    <button class="btn btn-white btn-sm btn-no-background text-success" onclick="cargarDatos('completada','<?php echo $anioSC; ?>','Solicitudes Completadas')">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    SC Completadas
                                                    <span class="float-right"><b><?php echo $totalCompletado; ?></b>/<?php echo $recibidasSDA; ?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?php echo calcularPorcentajesSC($totalCompletado, 100, $recibidasSDA) ?>%"></div>
                                                    </div>
                                                </div>
                                                <!-- /.progress-group -->
                                            </div>
                                            <!-- /.col -->

                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- ./card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h5 class="card-title">Registro de Informaci&oacute;n</h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <h5 class="text-center font-weight-bold" id="tituloSCMostrar"></h5>
                <div class="table-responsive">
                    <table id="tablaDBSC" class="table table-bordered">
                        <thead class="bg-secondary">
                        <tr>
                            <th>
                                N°
                            </th>
                            <th>
                                Folio
                            </th>
                            <th>
                                Solicitante
                            </th>
                            <th>
                                Fecha
                            </th>
                        </tr>
                        </thead>
                        <tfoot class="bg-secondary">
                        <tr>
                            <th>
                                N°
                            </th>
                            <th>
                                Folio
                            </th>
                            <th>
                                Solicitante
                            </th>
                            <th>
                                Fecha
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function cargarDatos(argumento,anio,titulo) {
        console.log(argumento+' -- '+anio)
        var tablaDBSC = $('#tablaDBSC').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "sc_cargardatos_dashboard.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.argumento = argumento; // Pasa el valor seleccionado del select como parámetro
                    d.anio = anio; // Pasa el valor seleccionado del select como parámetro
                },
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form  style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-SoliCompra-SDO.php" method="post">' +
                            '<input type="hidden" name="idSoliCompra" value="' + row.idSoliCompra + '">' +
                            '<center>' +
                            '<button type="submit" class="btn btn-white btn-no-background text-primary">'+row.folioSoliCompra+'</button>' +
                            '</center>' +
                            '</form>';
                    }

                },
                { "data": "personalSoliCompra" },
                { "data": "fechaSoliCompra"}
                // Agrega más columnas según sea necesario
            ],
            "success": function(response) {
                // Manejar la respuesta del servidor aquí
                console.log('Respuesta del servidor:', response);
            },
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

            },
            "destroy": true
        });

        document.getElementById('tituloSCMostrar').innerHTML = titulo;
    }
</script>
