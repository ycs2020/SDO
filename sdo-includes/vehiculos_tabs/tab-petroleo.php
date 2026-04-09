<?php
// vistas-tabs/tab-petroleo.php
?>

<div class="tab-pane fade" id="tabPetroleo">
    <div class="text-center mb-4">
        <h4 class="text-secondary font-weight-bold">Registro de Combustible</h4>
        <p class="text-muted">Control de cargas de petróleo, valores netos e impuestos (IVA 19%)</p>
        <button class="btn btn-warning mt-2 shadow-sm font-weight-bold text-dark" data-toggle="modal" data-target="#gasolinaModal">
            <i class="fas fa-gas-pump mr-1"></i> Registrar Carga de Petróleo
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 table-valign-middle table-sm" id="table_gasolina">
            <thead class="bg-warning text-dark text-center">
            <tr>
                <th>Fecha</th>
                <th>N° Guía</th>
                <th>Litros</th>
                <th>V. Unitario</th>
                <th>Total (Sin IVA)</th>
                <th>IVA (19%)</th>
                <th>Total (Con IVA)</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody class="text-center align-middle">
            </tbody>
            <tfoot class="bg-warning text-dark text-center">
            <tr>
                <th>Fecha</th>
                <th>N° Guía</th>
                <th>Litros</th>
                <th>V. Unitario</th>
                <th>Total (Sin IVA)</th>
                <th>IVA</th>
                <th>Total</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="gasolinaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-gas-pump mr-2"></i> Nuevo Registro de Petróleo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCargaGasolina">
                <div class="modal-body bg-light">
                    <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">

                    <div class="form-group">
                        <label>Fecha de Carga:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" required value="<?php echo date('Y-m-d'); ?>" name="fechaReporte">
                    </div>

                    <div class="form-group">
                        <label>Número de Guía:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="numGuia" placeholder="Ej: 12345">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Cantidad Litros:<span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" required name="litros" placeholder="0.00">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Valor Unitario (Neto):<span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" required name="valorUnitario" placeholder="Sin IVA">
                        </div>
                    </div>
                    <p class="text-muted small"><i class="fas fa-info-circle"></i> El sistema calculará automáticamente el IVA (19%) y los totales al guardar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Carga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarPetroleo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-pen mr-2"></i> Editar Registro de Petróleo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="formEditarPetroleo">
                    <div class="form-group">
                        <label>Fecha de Carga</label>
                        <input type="date" class="form-control" id="fechaReporteMod" name="fechaReporteMod">
                    </div>

                    <div class="form-group">
                        <label>Número de Guía</label>
                        <input type="text" class="form-control" id="numGuiaMod" name="numGuiaMod">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Cantidad de Litros</label>
                            <input type="number" step="any" class="form-control" id="cantidadLitrosMod" name="cantidadLitrosMod">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Valor Unitario (Neto)</label>
                            <input type="number" step="any" class="form-control" id="valorUnitMod" name="valorUnitMod">
                        </div>
                    </div>
                </form>
                <div class="alert alert-info py-2 mb-0">
                    <small><i class="fas fa-calculator mr-1"></i> Al modificar litros o valor, se recalcularán automáticamente los totales e IVA.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarEdicionPetroleo">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>