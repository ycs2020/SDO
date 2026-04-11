<?php
// vistas-tabs/tab-informacion.php
?>
<div class="tab-pane fade show active" id="tabInformacion">
    <div class="row mb-3 mt-2">
        <div class="col-12 text-center">
            <h4 class="text-secondary font-weight-bold mb-1">Gestión de Información General</h4>
            <br>
            <div class="mt-2 mb-3">
                <form action="../sdo-pdf/SDO-Hoja-Vida-Movil-Nuevo.php" target="_blank" method="POST" class="d-inline">
                    <input type="hidden" value="<?php echo $idMovil; ?>" name="idMovil">
                    <button type="submit" class="btn btn-success shadow-sm font-weight-bold">
                        <i class="fas fa-file-pdf mr-1"></i> Visualizar Hoja de Vida del Vehículo
                    </button>
                </form>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end mb-3">
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                <label class="custom-control-label text-muted font-weight-normal" style="cursor:pointer;" for="customSwitch1">
                    <i class="fas fa-edit mr-1"></i> Habilitar Edición de Datos
                </label>
            </div>
        </div>
    </div>

    <form id="formDatosVehiculo">
        <fieldset id="fieldset" class="border-0 p-0" disabled style="opacity: 0.7; transition: opacity 0.3s ease;">

            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-car mr-2 text-primary"></i> Datos del Vehículo
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>ID Interno:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="idVehiculo" name="idVehiculo" value="<?php echo $idVehiculo; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Nombre / Alias:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="nomVehiculo" name="nomVehiculo" value="<?php echo $nomVehiculo; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Patente:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="patenteVehiculo" name="patenteVehiculo" value="<?php echo $patenteVehiculo; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Marca:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="marcaVehiculo" name="marcaVehiculo" value="<?php echo $marcaVehiculo; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Modelo:</label>
                            <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" value="<?php echo $modeloVehiculo; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Servicio:<span class="text-danger">*</span></label>
                            <select name="servicioVehiculo" id="servicioVehiculo" class="form-control" required>
                                <?php
                                $sqlServicios = "SELECT nomServicio FROM servicios";
                                $resServicios = mysqli_query($conexion, $sqlServicios);
                                while ($rowServicios = $resServicios->fetch_assoc()) {
                                    $nomServicio = htmlspecialchars($rowServicios['nomServicio']);
                                    $selected = ($servicioVehiculo === $nomServicio) ? 'selected' : '';
                                    echo "<option value=\"$nomServicio\" $selected>$nomServicio</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-fingerprint mr-2 text-secondary"></i> Identificación Técnica
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>N° de Inventario:</label>
                            <input type="text" class="form-control" id="numInventario" name="numInventario" value="<?php echo $numInventario; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>N° de Chasis:</label>
                            <input type="text" class="form-control" id="numChasis" name="numChasis" value="<?php echo $numChasis; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>N° de Motor:</label>
                            <input type="text" class="form-control" id="numMotor" name="numMotor" value="<?php echo $numMotor; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Fabricante:</label>
                            <input type="text" class="form-control" id="frabricante" name="frabricante" value="<?php echo $fabricante; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Vida Útil (Años):</label>
                            <input type="text" class="form-control" id="vidaUtil" name="vidaUtil" value="<?php echo $vidaUtil; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-file-invoice-dollar mr-2 text-success"></i> Información de Compra y Valor
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Fecha de Compra:</label>
                            <input type="date" class="form-control" id="fechaCompra" name="fechaCompra" value="<?php echo $fechaCompra; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Orden de Compra:</label>
                            <input type="text" class="form-control" id="ordenCompra" name="ordenCompra" value="<?php echo $ordenCompra; ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="text-success font-weight-bold">Valor Total con IVA ($):</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-success text-white border-0"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input type="number" class="form-control font-weight-bold border-success text-success" id="valorConIva" name="valorConIva" value="<?php echo $valorConIva; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3 mb-5">
                <input type="hidden" name="idMovil" value="<?php echo $idMovil; ?>">
                <button type="submit" class="btn btn-success btn-lg px-5 shadow rounded-pill">
                    <i class="fas fa-save mr-2"></i> Guardar Todos los Cambios
                </button>
            </div>
        </fieldset>
    </form>
</div>