<span>


                                        <form method="post" action="../sdo-funciones/observaciones-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn bg-navy" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observacion</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Observacion</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha de Observacion: <code>*</code></label>
                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Observacion: <code>*</code></label>
                                                                <textarea id="observacionEquipo" style="resize:none;" name="observacionEquipo" class="form-control" rows="12" placeholder="Observacion..."></textarea>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <p style="float:right;">&nbsp;</p>

                                        <form method="post" action="../sdo-funciones/equipos-forzadoVariable.php">
                                            <button type="button" data-toggle="modal" data-target="#modalVariables" id="inputMozi" class="btn bg-yellow" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Variables</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalVariables" name="modalVariables" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Variables de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha de Forzado de Variables: <code>*</code></label>
                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Razones de Forzado de Variables: <code>*</code></label>
                                                                <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzado de Variables..."></textarea>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="forzadoVariable" id="forzadoVariable" value="Variables">
                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                            <button type="submit" class="btn btn-primary">Forzar Variables</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



                                        <p style="float:right;">&nbsp;</p>
                                        <form method="post" action="../sdo-funciones/equipos-forzarAutomatico.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAutomatico" id="inputMozi" class="btn btn-info" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Modo Automatico</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAutomatico" name="modalAutomatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel"><b>Modo Automatico de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha de Modo Automatico: <code>*</code></label>
                                                                <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Razones de Modo Automatico: <code>*</code></label>
                                                                <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Modo Automatico..."></textarea>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="modoAutomatico" id="modoAutomatico" value="Automatico">
                                                            <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                            <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                            <button type="submit" class="btn btn-primary">Activar Modo Automatico</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



                                        <p style="float:right;">&nbsp;</p>
                                        <?php

                                        if ($forzadoApagado == 0) {
                                            ?>
                                            <form method="post" action="../sdo-funciones/equipos-forzadoApagado.php">
                                                <button type="button" data-toggle="modal" data-target="#modalForzarApagado" id="inputMozi" class="btn bg-pink" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Apagado</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modalForzarApagado" name="modalForzarApagado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Apagado de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Forzar Apagado: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Forzar Apagado de Equipo: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Apagado de Equipo..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="apagadoEquipo" id="apagadoEquipo" value="Apagar">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                <button type="submit" class="btn btn-primary">Forzar Apagado Equipo</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                        } else if ($forzadoApagado == 1) {
                                            ?>
                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple" style="float:right;"><i class="fa fa-toggle-on"></i>&nbsp;&nbsp;Equipo Forzado - Apagado</button>

                                            <?php
                                        }

                                        ?>

                                        <p style="float:right;">&nbsp;</p>
                                        <?php

                                        if ($forzadoEncendido == 0) {
                                            ?>
                                            <form method="post" action="../sdo-funciones/equipos-forzadoEncendido.php">
                                                <button type="button" data-toggle="modal" data-target="#modalForzarEncendido" id="inputMozi" class="btn btn-success" style="float:right;"><i class="fa fa-toggle-off"></i>&nbsp;&nbsp;Forzar Encendido</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modalForzarEncendido" name="modalForzarEncendido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h5 class="modal-title" id="exampleModalLabel"><b>Forzar Encendido de Equipo: </b><?php echo $nomEquipo; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                                    <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Fecha de Forzar Encendido: <code>*</code></label>
                                                                    <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                                </div>
                                                                <div style="text-align:left;" class="form-group">
                                                                    <label for="exampleInputBorder">Razones de Forzar Encendido de Equipo: <code>*</code></label>
                                                                    <textarea id="razonesForzado" style="resize:none; word-break: break-all;" name="razonesForzado" class="form-control" rows="12" placeholder="Razones de Forzar Encendido de Equipo..."></textarea>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="encendidoEquipo" id="encendidoEquipo" value="Encender">
                                                                <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                <input type="hidden" name="personalObservacion" id="personalObservacion" value="<?php echo $nomUsuario . ' (Técnico de ' . getNomDepartamento($idDepartamento) . ')'; ?>">
                                                                <button type="submit" class="btn btn-primary">Forzar Encendido Equipo</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                        } else if ($forzadoEncendido == 1) {
                                            ?>
                                            <button type="button" disabled="disabled" id="inputMozi" class="btn bg-purple" style="float:right;"><i class="fa fa-toggle-on"></i>&nbsp;&nbsp;Equipo Forzado - Encendido</button>

                                            <?php
                                        }

                                        ?>
                                    </span>