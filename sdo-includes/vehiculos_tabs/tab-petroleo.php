<?php
// vistas-tabs/tab-petroleo.php
?>

<div class="tab-pane fade" id="tabPetroleo">
    <div class="text-center mb-4">
        <h4 class="text-secondary font-weight-bold">Registro de Combustible</h4>
        <p class="text-muted">Control de cargas de petróleo, valores netos e impuestos (IVA 19%)</p>

        <div class="row justify-content-center">
            <!-- Columna para el botón de Registro -->
            <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-2">
                <button class="btn btn-warning btn-block shadow-sm font-weight-bold text-dark" data-toggle="modal" data-target="#gasolinaModal">
                    <i class="fas fa-gas-pump mr-1"></i> Registrar Carga
                </button>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-12 mb-2">
                <button class="btn btn-success btn-block shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalExcelPetroleo">
                    <i class="fas fa-file-excel mr-1"></i> Descargar Excel
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered w-100 table-valign-middle table-sm" id="table_gasolina">
            <thead class="bg-warning text-dark text-center">
            <tr>
                <th>Fecha y Hora</th>
                <th>Nombre Conductor</th>
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
                <th>Fecha y Hora</th>
                <th>Nombre Conductor</th>
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
                        <input type="datetime-local" class="form-control" required value="<?php echo date('Y-m-d\TH:i'); ?>" name="fechaReporte">
                    </div>

                    <!-- Busca el campo de Número de Guía y agrega arriba el de Conductor -->
                    <div class="form-group">
                        <label>Nombre Conductor:</label>
                        <input type="text" class="form-control" name="nomConductor" list="listadoConductores" placeholder="Escriba o seleccione un nombre...">
                        <datalist id="listadoConductores">
                            <?php
                            // Consulta rápida para obtener nombres únicos ya registrados
                            $queryNombres = $conexion->query("SELECT DISTINCT nomConductor FROM historial_petroleo_moviles WHERE nomConductor IS NOT NULL ORDER BY nomConductor ASC");
                            while ($nom = $queryNombres->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($nom['nomConductor']) . "'>";
                            }
                            ?>
                        </datalist>
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
                    <p class="text-muted small">
                        <i class="fas fa-info-circle"></i> El sistema calculará automáticamente el IVA (19%) y los totales al guardar.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Carga
                    </button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Cancelar
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
                        <input type="datetime-local" class="form-control" id="fechaReporteMod" name="fechaReporteMod">
                    </div>

                    <!-- Agregar dentro del formEditarPetroleo -->
                    <div class="form-group">
                        <label>Nombre Conductor</label>
                        <input type="text" class="form-control" id="nomConductorMod" name="nomConductorMod" list="listadoConductores">
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
                <button type="button" class="btn btn-success" id="btnGuardarEdicionPetroleo">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcelPetroleo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-alt mr-2"></i>Rango de Exportación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                // 1. Preparamos la sentencia
                $stmt = $conexion->prepare("SELECT MIN(DATE(fechaRegistro)) as f_min, MAX(DATE(fechaRegistro)) as f_max 
                            FROM historial_petroleo_moviles 
                            WHERE idMovil = ?");

                // 2. "Bindeamos" el parámetro (i = integer, s = string)
                $stmt->bind_param("i", $idMovil);

                // 3. Ejecutamos
                $stmt->execute();

                // 4. Obtenemos el resultado
                $resultado = $stmt->get_result();
                $dataLimites = $resultado->fetch_assoc();

                $minima = $dataLimites['f_min'] ?? date('Y-m-d');
                $maxima = $dataLimites['f_max'] ?? date('Y-m-d');

                $stmt->close();
                ?>

                <p class="text-muted small">Seleccione el rango de fechas para el informe de petróleo.</p>
                <div class="row">
                    <div class="col-6">
                        <label class="font-weight-bold">Fecha Inicio:</label>
                        <input type="date" id="fechaInicioExcel"
                                class="form-control"
                                min="<?php echo $minima; ?>"
                                max="<?php echo $maxima; ?>">
                    </div>
                    <div class="col-6">
                        <label class="font-weight-bold">Fecha Fin:</label>
                        <input type="date" id="fechaFinExcel"
                                class="form-control"
                                min="<?php echo $minima; ?>"
                                max="<?php echo $maxima; ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button id="btnDescargarTodo" class="btn btn-info shadow-sm font-weight-bold" data-idmovil="<?php echo $idMovil; ?>">
                    <i class="fas fa-list mr-1"></i> Descargar Todo
                </button>

                <button id="btnProcesarExcel" class="btn btn-success font-weight-bold" data-idmovil="<?php echo $idMovil; ?>">
                    <i class="fas fa-download mr-1"></i> Generar Reporte
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cancelar</button>
            </div>
        </div>
    </div>
</div>