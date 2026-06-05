<div class="row mb-4">
    <div class="col-12">
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalCargaCalderas">
            <i class="fas fa-file-upload"></i> Importar Nuevos Archivos CSV
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table"></i> Registros Sincronizados (Estado y Temperatura)</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="tablaCalderas" class="table table-bordered table-striped dataTable dtr-inline" style="width:100%">
                    <thead>
                    <tr>
                        <th class="text-center">Timestamp</th>
                        <th class="text-center">Caldera</th>
                        <th class="text-center">Estado de Operación</th>
                        <th class="text-center">Temperatura (°C)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Cargando registros históricos...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCargaCalderas" tabindex="-1" role="dialog" aria-labelledby="modalCargaLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modalCargaLabel">
                    <i class="fas fa-copy"></i> Cargar Reportes de Caldera
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formCargaPasos" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="callout callout-info py-2 mb-3">
                        <h5><i class="fas fa-info-circle text-info"></i> Sincronización Requerida</h5>
                        <p class="text-muted mb-0">Por favor, selecciona la caldera correspondiente y suba ambos archivos.</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="id_caldera"><strong>Seleccionar Caldera <span class="text-danger">*</span></strong></label>
                                <select class="form-control" name="id_caldera" id="id_caldera" required>
                                    <option value="" disabled selected>-- Seleccione una Caldera --</option>
                                    <option value="1">Caldera 1</option>
                                    <option value="2">Caldera 2</option>
                                    <option value="3">Caldera 3</option>
                                    <option value="4">Caldera 4</option>
                                    <option value="5">Caldera 5</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="csv_estado"><strong>1. Archivo de Estados (.csv) <span class="text-danger">*</span></strong></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="csv_estado" id="csv_estado" accept=".csv" required>
                                        <label class="custom-file-label" data-browse="Buscar" for="csv_estado">Elegir archivo...</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="csv_temperatura"><strong>2. Archivo de Temperaturas (.csv) <span class="text-danger">*</span></strong></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="csv_temperatura" id="csv_temperatura" accept=".csv" required>
                                        <label class="custom-file-label" data-browse="Buscar" for="csv_temperatura">Elegir archivo...</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="procesandoCarga" class="text-center my-3 d-none">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="mt-2 text-bold text-success mb-0">Procesando y guardando lecturas... Por favor espere.</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnEnviarCarga">
                        <i class="fas fa-cloud-upload-alt"></i> Procesar e Importar
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnCerrarModal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>