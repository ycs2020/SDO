<div class="row mb-3">
    <div class="col-md-6 col-12">
        <div class="card card-outline card-primary mb-0">
            <div class="card-body py-2 d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center mr-3 my-1" style="min-width: 220px; flex: 1;">
                    <label for="filtroCalderaDashboard" class="mb-0 mr-2 text-bold text-secondary text-nowrap">
                        <i class="fas fa-industry"></i> Ver Caldera:
                    </label>
                    <select id="filtroCalderaDashboard" class="form-control form-control-sm">
                        <option value="1" selected>Caldera 1</option>
                        <option value="2">Caldera 2</option>
                        <option value="3">Caldera 3</option>
                        <option value="4">Caldera 4</option>
                        <option value="5">Caldera 5</option>
                    </select>
                </div>

                <div class="custom-control custom-switch my-1">
                    <input type="checkbox" class="custom-control-input" id="chkMostrarNumeros" checked>
                    <label class="custom-control-label text-bold text-secondary" for="chkMostrarNumeros" style="cursor: pointer;">
                        <i class="fas fa-tag"></i> Mostrar valores
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card card-primary card-outline">
            <div class="card-header border-0">
                <h3 class="card-title text-bold">
                    <i class="fas fa-thermometer-half text-danger"></i> Monitoreo de Temperatura (°C) - Últimos 24 Registros
                </h3>
            </div>
            <div class="card-body">
                <div class="position-relative" style="height: 380px; width: 100%;">
                    <canvas id="graficoTemperatura"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>