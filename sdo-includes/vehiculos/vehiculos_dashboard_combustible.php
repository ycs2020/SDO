<div class="p-3">
    <h4 class="text-danger"><i class="fas fa-gas-pump mr-2"></i> Análisis de Combustible</h4>

    <div class="card card-outline card-danger shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">Año:</label>
                    <select id="filtroAnioComb" class="form-control form-control-sm select2">
                        <?php
                        $resAnios = mysqli_query($conexion, "SELECT DISTINCT YEAR(fechaRegistro) as anio FROM historial_petroleo_moviles ORDER BY anio DESC");
                        while ($row = $resAnios->fetch_assoc()) echo "<option value='{$row['anio']}'>{$row['anio']}</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">Mes:</label>
                    <select id="filtroMesComb" class="form-control form-control-sm select2"></select>
                </div>
                <div class="col-md-6 text-right">
                    <div class="p-2 bg-light border rounded d-inline-block">
                        <span class="small text-muted">INVERSIÓN TOTAL FLOTA:</span>
                        <h5 class="mb-0 text-danger" id="txtGastoTotalFlota">$0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger shadow-sm">
        <div class="card-body">
            <div style="height:250px;"><canvas id="graficoCombustible"></canvas></div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table id="tablaPetroleoResumen" class="table table-bordered table-striped table-sm text-center shadow-sm table-valign-middle" style="width:100%">
            <thead class="bg-danger">
            <tr>
                <th>VEHÍCULO / PATENTE</th>
                <th>N° CARGAS</th>
                <th>LITROS CARGADOS</th>
                <th>TOTAL INVERSIÓN</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>