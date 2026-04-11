<div class="p-3">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="text-danger"><i class="fas fa-gas-pump mr-2"></i> Gestión de Consumo de Combustible</h4>
            <p class="text-muted">Análisis de gasto y litros cargados por unidad.</p>
        </div>
    </div>

    <div class="card card-outline card-danger shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">Año:</label>
                    <select id="filtroAnioComb" class="form-control form-control-sm select2">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">Mes:</label>
                    <select id="filtroMesComb" class="form-control form-control-sm select2">
                        <option value="0">AÑO COMPLETO</option>
                    </select>
                </div>
                <div class="col-md-6 text-right">
                    <div class="d-inline-block p-2 bg-light border rounded">
                        <span class="small text-muted text-uppercase">Gasto Total Flota:</span>
                        <h5 class="mb-0 text-danger" id="txtGastoTotalFlota">$0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger shadow-sm">
        <div class="card-body">
            <div style="position: relative; height:300px;">
                <canvas id="graficoCombustible"></canvas>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped table-sm text-center shadow-sm" id="tablaCombustible" style="width:100%">
            <thead class="bg-danger">
            <tr>
                <th>VEHÍCULO / PATENTE</th>
                <th>CARGAS</th>
                <th>LITROS TOTALES</th>
                <th>INVERSIÓN TOTAL (C/IVA)</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>