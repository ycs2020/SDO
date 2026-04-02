<div class="col-12 col-md-12">
    <div class="card">
        <div class="card-body">

            <form id="formGuardarReporte" action="../sdo-funciones/reportes_electricidad_generar.php" method="post" enctype="multipart/form-data">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="exampleInputFecha">Fecha Actual:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <?php
                            date_default_timezone_set('America/Santiago');
                            ?>
                            <input type="datetime" readonly="readonly" id="fechaActualReporte" name="fechaActualReporte" class="form-control" value="<?php echo date('d-m-Y'); ?>"/>

                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="exampleInputNomEncargadoTurno">T&eacute;cnico Electrico en Turno:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" required style="text-transform:capitalize" class="form-control" id="nomEncargadoTurno" name="nomEncargadoTurno" value="<?php echo $nomUsuario; ?>" placeholder="T&eacute;cnico Electrico en Turno">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="exampleInputNomEncargadoTurnoAnterior">T&eacute;cnico Electrico de Turno Anterior:<code>*</code></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" required style="text-transform:capitalize" class="form-control" id="nomEncargadoTurnoAnterior" name="nomEncargadoTurnoAnterior" placeholder="T&eacute;cnico Electrico de Turno Anterior">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputTurno">Turno realizado:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <select id="listaTurno" class="form-control" required name="listaTurno">
                                <option value="">Seleccione...</option>
                                <option value="Dia">Día (08:00 - 20:00)</option>
                                <option value="Noche">Noche (20:00 - 08:00)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="exampleInputTareasControlCent">Eventos en Instalaciones El&eacute;ctricas:</label>
                        <textarea id="tareasDeteccionElectrica" style="resize:none;" name="tareasDeteccionElectrica" class="form-control" rows="8" placeholder="Eventos Relevantes en Detecci&oacute;n de Instalaci&oacute;n El&eacute;ctrica"></textarea>
                    </div>
                </div>


                <hr>

                <div class="form-group">
                    <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario; ?>">
                    <input type="submit" id="guardarReporte" class="btn btn-block btn-primary" value="Guardar"/>

                </div>
                <div class="form-group">
                    <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
                </div>

            </form>
        </div>
    </div>
</div>