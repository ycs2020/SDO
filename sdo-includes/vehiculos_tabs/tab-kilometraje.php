<?php
// vistas-tabs/tab-kilometraje.php
?>

<div class="tab-pane fade" id="tabKilometraje">
    <div class="text-center mb-4">
        <h4 class="text-secondary font-weight-bold">Registro de Kilometraje</h4>
        <p class="text-muted">Seguimiento de recorrido y alertas de mantenimiento</p>
        <button class="btn btn-success mt-2 shadow-sm" data-toggle="modal" data-target="#kilometrajeModal">
            <i class="fas fa-plus mr-1"></i> Añadir Nuevo Kilometraje
        </button>
    </div>

    <div class="alert alert-info mb-4 shadow-sm border-0">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x mr-3"></i>
            <div>
                <strong>Referencia de Colores:</strong><br>
                <span class="badge text-dark p-2 mt-1" style="background-color: #ffff00; border: 1px solid #d4d400;">
                    Fila Amarilla
                </span> = Este registro marca el inicio de un **Próximo Mantenimiento**.
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 shadow-sm" id="table_kilometro">
            <thead class="bg-secondary text-white text-center">
            <tr>
                <th>Fecha Registro</th>
                <th>Kilometraje Actual</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody class="text-center align-middle">
            </tbody>
            <tfoot class="bg-secondary text-white text-center">
            <tr>
                <th>Fecha Registro</th>
                <th>Kilometraje Actual</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="kilometrajeModal" tabindex="-1" aria-labelledby="kilometrajeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="kilometrajeModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i><b>Nuevo Registro - Kilometraje</b>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formKilometraje">
                <div class="modal-body">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                    <input type="hidden" name="estadoVehiculo" value="<?php echo $estadoVehiculo; ?>">

                    <div class="row">
                        <div class="form-group col-12">
                            <label class="font-weight-bold small">Fecha Reporte: <code class="text-danger">*</code></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-secondary"></i></span>
                                </div>
                                <input type="date" class="form-control" required id="fechaReporte" name="fechaReporte" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label class="font-weight-bold small">Kilometraje Actual: <code class="text-danger">*</code></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-tachometer-alt text-secondary"></i></span>
                                </div>
                                <input type="number" step="1" min="0" class="form-control" required id="numKilometraje" name="numKilometraje" placeholder="Ej: 150500">
                            </div>
                            <small class="form-text text-muted">Ingrese el valor total del odómetro.</small>
                        </div>

                        <div class="form-group col-12">
                            <div class="custom-control custom-switch py-2">
                                <input type="checkbox" class="custom-control-input" id="registroMantenimiento" name="registroMantenimiento" value="1">
                                <label class="custom-control-label font-weight-bold text-primary" for="registroMantenimiento" style="cursor:pointer;">
                                    ¿Es inicio de ciclo de mantenimiento?
                                </label>
                            </div>
                            <div class="alert alert-warning py-1 px-2 mt-2 small border-0" style="display:none;" id="infoMantenimiento">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Se marcará como base para el cálculo de alertas.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold shadow-sm" id="btnGuardarKms">
                        <i class="fas fa-save mr-1"></i> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>