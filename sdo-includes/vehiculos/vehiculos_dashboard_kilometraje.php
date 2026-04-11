<div class="p-3">
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="text-purple"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard de Kilometraje</h4>
            <p class="text-muted">Análisis de recorrido mensual y estado operativo de la flota.</p>
        </div>
    </div>

    <?php
    // Obtener años con datos reales
    $resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaRegistro) as anio FROM historial_kilometraje_moviles ORDER BY anio DESC");
    $aniosDisponibles = [];
    while ($rowA = $resAnios->fetch_assoc()) { $aniosDisponibles[] = $rowA['anio']; }

    // Año inicial para la carga
    $anioInicial = !empty($aniosDisponibles) ? $aniosDisponibles[0] : date('Y');
    ?>

    <div class="card card-outline card-purple shadow-sm">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold"><i class="fas fa-calendar-alt mr-1"></i> Seleccionar Año:</label>
                    <select id="filtroAnio" class="form-control form-control-sm select2">
                        <?php foreach ($aniosDisponibles as $anio): ?>
                            <option value="<?= $anio ?>"><?= $anio ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="small font-weight-bold"><i class="fas fa-calendar-check mr-1"></i> Seleccionar Mes:</label>
                    <select id="filtroMes" class="form-control form-control-sm select2">
                    </select>
                </div>

                <div class="col-md-6 text-right">
                    <div id="resumenFlota" class="d-inline-block p-2 bg-light border rounded">
                        <span class="small text-muted text-uppercase">Total Recorrido Flota:</span>
                        <h5 class="mb-0 text-purple" id="txtTotalFlota">0 Km</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-purple shadow-sm mt-4">
        <div class="card-header">
            <h3 class="card-title small font-weight-bold">
                <i class="fas fa-chart-line mr-1"></i> Comparativa de Recorrido por Vehículo
            </h3>
        </div>
        <div class="card-body">
            <div style="position: relative; height:250px; width:100%;">
                <canvas id="graficoKms"></canvas>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped table-sm text-center table-valign-middle shadow-sm" id="tablaKilometraje" style="width:100%">
            <thead class="bg-navy">
            <tr>
                <th>VEHÍCULO / PATENTE</th>
                <th>KM INICIO MES</th>
                <th>KM FIN MES</th>
                <th class="bg-purple">RECORRIDO TOTAL</th>
                <th>ESTADO CIERRE MES/AÑO</th>
                <th>ESTADO ACTUAL</th>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="bg-light font-weight-bold">
            <tr>
                <td colspan="3" class="text-right">TOTAL MENSUAL:</td>
                <td id="totalFilaKms" class="text-purple">0 Km</td>
                <td colspan="2"></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>