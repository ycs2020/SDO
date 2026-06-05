<div class="modal fade" id="mostrarDescargar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header py-2" style="background-color: deepskyblue">
                <h6 class="modal-title font-weight-bold text-white"><i class="fas fa-file-download mr-1"></i> Descargar Documentos Adjuntos</h6>
                <button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-2">
                <table class="table table-sm table-hover table-bordered mb-0" style="font-size:13.5px;">
                    <thead class="bg-light text-center"><tr><th>Unidad Emisor</th><th>Nombre Emisor</th><th>Asignado</th><th>Descarga</th></tr></thead>
                    <tbody>
                    <?php
                    $sqlDocu = "SELECT * FROM archivos_recepcion_documento WHERE idTG = $idTG";
                    $resDocu = mysqli_query($conexion,$sqlDocu);
                    while($rowDocu = $resDocu->fetch_assoc()){
                        $sql1 = "SELECT * FROM recepcion_documento WHERE idRecepcion = " . $rowDocu['idRecepcion'];
                        $res1 = mysqli_query($conexion,$sql1);
                        while($row1 = $res1->fetch_assoc()){
                            echo '<tr>
                                    <td>'.$row1['unidadEmisor'].'</td>
                                    <td>'.$row1['nomEmisor'].'</td>
                                    <td>'.$row1['asignadoRecepcion'].'</td>
                                    <td class="text-center">';
                            if(!empty($rowDocu['folioDocumento'])){
                                echo '<form action="detalle_ordenes_trabajo.php" method="POST" style="margin:0;">
                                        <input type="hidden" name="folioDocumento" value="'.$rowDocu['folioDocumento'].'">
                                        <input type="hidden" name="idArchivoDocu" value="'.$rowDocu['idArchivosDocu'].'">
                                        <button type="submit" class="btn btn-xs font-weight-bold text-white px-3" style="background-color: deepskyblue;"><i class="fas fa-download mr-1"></i> Descargar</button>
                                      </form>';
                            }
                            echo '</td></tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="derivar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning py-2"><h6 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-1"></i> Cambiar Persona Asignada</h6><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
            <form action="../sdo-funciones/cambioasigando-ot.php" method="POST">
                <div class="modal-body py-2" style="font-size:14px;">
                    <div class="form-group mb-2"><label class="mb-1">Usuario Creador: <code>*</code></label><input type="text" class="form-control form-control-sm" readonly name="nomCreador" value="<?php echo $nomUsuario ?>" required></div>
                    <div class="form-group mb-2"><label class="mb-1">Nueva Persona a Asignar: <code>*</code></label><select required name="selectPersona" class="form-control form-control-sm"><option value="" selected disabled>Seleccione...</option><?php $sqlPerso = "SELECT * FROM personal WHERE departamentoPersonal = $departamentos"; $resPerso = mysqli_query($conexion,$sqlPerso); while($rowPerso = $resPerso->fetch_assoc()){ echo '<option value="'.$rowPerso['nomPersonal'].'">'.$rowPerso['nomPersonal'].'</option>'; } ?></select></div>
                    <div class="form-group mb-0"><label class="mb-1">Justifique el cambio: <code>*</code></label><textarea name="detalleObs" style="resize:none;" class="form-control form-control-sm" rows="6" required></textarea></div>
                </div>
                <div class="modal-footer py-1"><input type="hidden" name="idTG" value="<?php echo $idTG?>"><button type="submit" class="btn btn-sm btn-primary">Guardar</button><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalSoliCompra" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info py-2"><h6 class="modal-title font-weight-bold text-white"><i class="fas fa-shopping-cart mr-1"></i> Solicitud de Compra</h6><button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
            <form action="../sdo-funciones/ingresar-soli-compra.php" method="POST">
                <div class="modal-body py-2"><input type="hidden" name="idTG" value="<?php echo $idTG?>"><label class="form-label mb-1" style="font-size:14px;">Ingrese el Folio de la Solicitud de Compra:</label><input type="text" id="numSoliCompra" name="numSoliCompra" class="form-control form-control-sm" required></div>
                <div class="modal-footer py-1"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-sm btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLineaVidaOT" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info py-2"><h6 class="modal-title font-weight-bold text-white"><i class="fas fa-history mr-1"></i> Agregar Obs a la Línea de Vida</h6><button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
            <form action="../sdo-funciones/ingresar-linea-vida.php" method="POST">
                <div class="modal-body py-2" style="font-size:14px;">
                    <input type="hidden" name="idTG" value="<?php echo $idTG?>">
                    <div class="form-group mb-2"><label class="mb-1">Nombre Creador:</label><input type="text" readonly class="form-control form-control-sm" name="nomCreador" value="<?php echo $nomUsuario?>"></div>
                    <?php if($tecnico1 == $nomUsuario){ ?>
                        <div class="form-group mb-2"><label class="mb-1">¿Se necesitan materiales por Solicitud de Compra?</label><select id="material" name="material" class="form-control form-control-sm" onchange="visible(this.value)"><option value="" selected disabled>Seleccione...</option><option value="1">Si</option><option value="2">No</option></select></div>
                        <div id="obs" style="display: none" class="form-group mb-0"><label class="mb-1">Ingresar Observación:</label><textarea class="form-control form-control-sm" name="detalleObs" rows="6"></textarea></div>
                        <div id="materiales" style="display: none" class="form-group mb-0"><label class="mb-1">Ingrese los materiales:</label><textarea class="form-control form-control-sm" name="detalleObs" rows="6"></textarea></div>
                    <?php } else { ?>
                        <div id="obs1" class="form-group mb-0"><label class="mb-1">Ingresar Observación:</label><textarea class="form-control form-control-sm" name="detalleObs" rows="6" required></textarea></div>
                    <?php } ?>
                </div>
                <div class="modal-footer py-1"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-sm btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger py-2"><h6 class="modal-title font-weight-bold text-white"><i class="fas fa-comment-alt mr-1"></i> Ingresar Observación</h6><button type="button" class="close text-white" data-dismiss="modal"><span aria-hidden="true">&times;</span></button></div>
            <form action="../sdo-funciones/ingresar-observacion-ot.php" method="POST">
                <input type="hidden" name="idTG" value="<?php echo $idTG?>"><input type="hidden" name="rolUsuario" value="<?php echo $rolUsuario?>">
                <div class="modal-body py-2" style="font-size:14px;">
                    <div class="form-group mb-2"><label class="mb-1">Nombre de Usuario: <code>*</code></label><input type="text" readonly class="form-control form-control-sm" name="nomCreador" value="<?php echo $nomUsuario?>" required></div>
                    <div class="form-group mb-0"><label class="mb-1">Describa la observación: <code>*</code></label><textarea class="form-control form-control-sm" id="detalleObs" rows="6" name="detalleObs" required></textarea></div>
                </div>
                <div class="modal-footer py-1"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>
</div>