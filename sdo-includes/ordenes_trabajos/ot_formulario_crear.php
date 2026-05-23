<form id="formRegistrarTG" method="POST" enctype="multipart/form-data">

    <div class="card card-light border shadow-sm mb-4">
        <div class="card-header bg-light py-2"><h6 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-info-circle mr-1"></i> Información General del Trabajo</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="font-weight-bold">Descripción: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-pencil-alt"></i></span></div>
                        <input type="text" class="form-control" name="descripcion" placeholder="Descripción del Trabajo" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Fecha de Programación del Trabajo: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="fechaProgramado" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Ticket Asociado: <small class="text-danger">Para Tickets SIGH, ingresar solo números</small></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-ticket-alt"></i></span></div>
                        <input type="text" class="form-control" list="ticketsSigh" name="ticketAsociado" placeholder="Ingrese número del ticket">
                        <datalist id="ticketsSigh">
                            <?php
                            $sqlSolic = "SELECT folioSolicitud, nomSolicitante FROM solicitudes_soportes ORDER BY inicioSolicitud DESC";
                            if ($resSolic = mysqli_query($conexion, $sqlSolic)) {
                                while ($rowSolic = $resSolic->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($rowSolic['folioSolicitud'], ENT_QUOTES, 'UTF-8')."'>Creador: ".htmlspecialchars($rowSolic['nomSolicitante'], ENT_QUOTES, 'UTF-8')."</option>";
                                }
                                mysqli_free_result($resSolic);
                            }
                            ?>
                        </datalist>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Tipo de Trabajo: </label>
                    <select class="form-control select2" name="tipoTrabajo">
                        <option value="" disabled selected>-- Seleccione --</option>
                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                        <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                        <option value="OM">OM - Obras Menores</option>
                        <option value="IS">IS - Inspección/Supervisión</option>
                        <option value="RD">RD - Recepcion de Documentos</option>
                        <option value="MS">MS - Mejoras del Sistema</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Turno: <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="turno" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <option value="Personal Diurno">Personal Diurno</option>
                        <option value="Personal Nocturno">Personal Nocturno</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-light border shadow-sm mb-4">
        <div class="card-header bg-light py-2"><h6 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-users mr-1"></i> Personal y Ubicación</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Persona Solicitante: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                        <input type="text" class="form-control" name="personaSolicitante" value="<?= $nomUsuario ?>" required>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Departamentos: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="departamentoSolicitante" id="departamentoSolicitante" class="form-control select2" required>
                            <option value="" selected disabled>Seleccione</option>
                            <?php
                            $sqlDepa = "SELECT idDepartamento, nomDepartamento FROM departamentos WHERE idDepartamento NOT IN (9, 11, 13, 14, 15) ORDER BY nomDepartamento ASC";
                            if ($resDepa = mysqli_query($conexion, $sqlDepa)) {
                                while ($rowDepa = $resDepa->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($rowDepa['idDepartamento'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($rowDepa['nomDepartamento'], ENT_QUOTES, 'UTF-8')."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Personal Responsable: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="tecnico1" id="tecnico1" class="form-control select2" required>
                            <option value="">Seleccione departamento</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="font-weight-bold">Unidad o Servicio: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" list="listServicios" name="servicios" id="servicios" placeholder="ESCRIBA EL SERVICIO" required>
                    <datalist id="listServicios">
                        <?php
                        $sqlSolic = "SELECT idServicio, nomServicio FROM servicios ORDER BY nomServicio ASC";
                        if ($resSolic = mysqli_query($conexion, $sqlSolic)) {
                            while ($rowSolic = $resSolic->fetch_assoc()) {
                                $nomServicio = mb_strtoupper($rowSolic['nomServicio'], 'UTF-8');
                                echo "<option value='".htmlspecialchars($nomServicio, ENT_QUOTES, 'UTF-8')."'>";
                            }
                            mysqli_free_result($resSolic);
                        }
                        ?>
                    </datalist>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-light border shadow-sm mb-4" id="seccion-checklist-adjuntos" style="display: none;">
        <div class="card-header bg-light py-2"><h6 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-tasks mr-1"></i> Checklists y Adjuntos</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Check List:</label>
                    <select class="form-control select2" name="checklist" id="checklist">
                        <option value="" selected>Seleccione...</option>
                        <option value="Pantallas Graficas">Pantallas Gráficas</option>
                        <option value="Logica de Programacion">Lógica de Programación</option>
                        <option value="Unidades Control de Accesos">Unidades Control de Accesos (UCAS)</option>
                        <option value="Controlador Modelo ECB">Controlador Modelo ECB</option>
                        <option value="TCC de UMA">TCC de UMA</option>
                        <option value="VAV">VAV</option>
                        <option value="VF">Vigas Frías</option>
                    </select>
                </div>
                <div class="col-md-4 form-group"><label class="font-weight-bold">¿Cuántas veces se hará?:</label><input type="number" class="form-control" name="cantidad" id="cantidad" min="1"></div>
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Formulario de Rondas: </label>
                    <select class="form-control select2" name="formularioRondas" id="formularioRondas">
                        <option value="" selected disabled>Seleccione..</option>
                        <option value="1">Formato Rondas con Consola de Alarmas</option>
                        <option value="2">Formato Rondas con Unidades manejadoras de aire BMS</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 form-group">
                    <label class="font-weight-bold">Adjuntar Trabajo General:</label>
                    <div class="custom-file"><input type="file" class="custom-file-input" id="archivoAdjunto" name="archivoAdjunto"><label class="custom-file-label" for="archivoAdjunto">Elegir archivo...</label></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-light border shadow-sm mb-4">
        <div class="card-header bg-light py-2"><h6 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-file-alt mr-1"></i> Detalle de los Trabajos a Realizar</h6></div>
        <div class="card-body">
            <div id="bloque-detalle-especifico" style="display: none;">
                <div class="row">
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-primary">Detalle en BMS: </label><textarea class="form-control" name="detalle_bms" id="detalle_bms" rows="3"></textarea></div>
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-primary">Detalle en CCTV: </label><textarea class="form-control" name="detalle_cctv" id="detalle_cctv" rows="3"></textarea></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-primary">Detalle en LLE: </label><textarea class="form-control" name="detalle_lle" id="detalle_lle" rows="3"></textarea></div>
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-primary">Detalle en Control de Acceso: </label><textarea class="form-control" name="detalle_acceso" id="detalle_acceso" rows="3"></textarea></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-primary">Detalle en Rondas: </label><textarea class="form-control" name="detalle_rondas" id="detalle_rondas" rows="3"></textarea></div>
                    <div class="col-md-6 form-group"><label class="font-weight-bold text-muted">Otros tipos de trabajos:</label><textarea class="form-control" name="detalle_otros" id="detalle_otros" rows="3"></textarea></div>
                </div>
            </div>
            <div id="bloque-detalle-general" style="display: none;">
                <div class="row"><div class="col-md-12 form-group"><label class="font-weight-bold text-success">Descripción Detallada del Trabajo: </label><textarea class="form-control" name="detalle_general" id="detalle_general" rows="6" placeholder="Escriba aquí las actividades..."></textarea></div></div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-2"><button type="submit" class="btn btn-success btn-block font-weight-bold py-2"><i class="fas fa-save mr-1"></i> Guardar Registro</button></div>
        <div class="col-md-6"><a href="index.php" class="btn btn-warning btn-block font-weight-bold py-2 text-white"><i class="fas fa-undo mr-1"></i> Volver Atrás</a></div>
    </div>
</form>