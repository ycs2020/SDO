<?php
// vistas-tabs/tab-estados.php
?>

<div class="tab-pane fade" id="tabEstados">
    <div class="text-center mb-4">
        <h4 class="text-secondary font-weight-bold">Registro de Estados</h4>
        <p class="text-muted">Historial de operatividad y mantenimientos del vehículo</p>
        <button class="btn btn-success mt-2 shadow-sm" data-toggle="modal" data-target="#estadoModal">
            <i class="fas fa-plus mr-1"></i> Añadir Nuevo Estado
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 table-valign-middle table-sm" id="table_estado">
            <thead class="bg-dark text-white text-center">
            <tr>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Motivo</th>
            </tr>
            </thead>
            <tbody class="text-center align-middle">
            </tbody>
            <tfoot class="bg-dark text-white text-center">
            <tr>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Motivo</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="estadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-clipboard-check mr-2"></i> Nuevo Estado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNuevoEstado">
                <div class="modal-body bg-light">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">

                    <div class="form-group">
                        <label>Fecha Reporte:</label>
                        <input type="date" class="form-control" readonly id="fechaReporteEstado" value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                        <small class="form-text text-muted">La fecha se asigna automáticamente al día de hoy.</small>
                    </div>

                    <div class="form-group">
                        <label>Estado:<span class="text-danger">*</span></label>
                        <select class="form-control" required name="estadoMovil">
                            <option value="" disabled selected>-- Seleccione el estado --</option>
                            <option value="Operativa">Operativa</option>
                            <option value="Mantencion Preventiva">Mantención Preventiva</option>
                            <option value="Mantencion Correctiva">Mantención Correctiva</option>
                            <option value="Debaja">Debaja</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Motivo / Observaciones:</label>
                        <textarea rows="4" class="form-control" name="motivo" placeholder="Explique brevemente el motivo del cambio de estado o detalles del mantenimiento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save mr-1"></i> Guardar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>