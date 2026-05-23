<div class="p-3">

    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-uppercase">Seleccionar Año:</label>
                    <select id="filtroAnioOT" class="form-control form-control-sm select2">
                        <?php
                        // Obtenemos dinámicamente SOLO los años que tienen OTs registradas
                        $queryAnios = "SELECT DISTINCT YEAR(fechaProgramacion) as anio FROM trabajos_general WHERE fechaProgramacion IS NOT NULL ORDER BY anio DESC";
                        $resAnios = mysqli_query($conexion, $queryAnios);

                        if (mysqli_num_rows($resAnios) > 0) {
                            while ($row = mysqli_fetch_assoc($resAnios)) {
                                $anioBd = $row['anio'];
                                $selected = ($anioBd == date('Y')) ? 'selected' : '';
                                echo "<option value='{$anioBd}' {$selected}>{$anioBd}</option>";
                            }
                        } else {
                            // Por si la tabla está completamente vacía, mostramos el año actual
                            echo "<option value='" . date('Y') . "'>" . date('Y') . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-uppercase">Seleccionar Mes:</label>
                    <select id="filtroMesOT" class="form-control form-control-sm select2">
                        <option value="TODOS">-- AÑO COMPLETO --</option>
                    </select>
                </div>
                <div class="col-md-6 text-right mt-3 mt-md-0">
                    <div class="p-2 bg-light border rounded-3 d-inline-block shadow-sm">
                        <span class="small text-muted text-uppercase font-weight-bold">Total OT (Período Seleccionado):</span>
                        <h4 class="mb-0 text-primary font-weight-bold" id="txtTotalOTs"><i class="fas fa-spinner fa-spin"></i></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-light">
            <h3 class="card-title font-weight-bold text-uppercase mb-0" style="font-size: 85%;">
                <i class="fas fa-chart-bar text-primary mr-2"></i> Distribución por Departamentos
            </h3>
        </div>
        <div class="card-body">
            <div style="height: 320px;">
                <canvas id="graficoDepartamentosOT"></canvas>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaResumenOT" class="table table-bordered table-striped table-sm text-center shadow-sm table-valign-middle" style="width:100%; font-size: 85%;">
            <thead class="bg-primary text-white text-uppercase">
            <tr>
                <th>Departamento / Área</th>
                <th>Cantidad de OT</th>
                <th>Porcentaje del Total</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="../sdo-includes/ordenes_trabajos_js/ot_dashboard.js"></script>