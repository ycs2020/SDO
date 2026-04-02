<div class="modal fade" id="exampleModal<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title" id="exampleModalLabel">Informe EDAN: <?php echo $id?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../sdo-funciones/ingresar-obs-edan-infra.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                <input type="hidden" id="idDepa" name="idDepa" value="<?php echo $idDepartamento?>">
                <div class="modal-body">
                    <label>Nombre del Usuario</label>
                    <input type="text" name="nomUsuario" readonly class="form-control-border form-control" value="<?php echo $nomUsuario?>">
                    <br>
                    <fieldset>
                        <h6><b>¿Hubo daños estructurales y/o no estructurales?</b></h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>6.3 Estructural</label>
                                <br>
                                <?php
                                $estruc = "Estado: ";
                                ?>
                                <textarea name="obs_6-3"  class="form-control" style="resize: none" rows="10"><?php echo $estruc?></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label>6.4 No Estructural</label>
                                <br>
                                <?php
                                $noestruc = "Estado: ";
                                ?>
                                <textarea name="obs_6-4"  class="form-control" style="resize: none" rows="10"><?php echo $noestruc?></textarea>
                            </div>
                        </div>
                        <h6><b>¿Se produjo afectación en las lineas vitales?</b></h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>6.6 Vias de Acceso</label>
                                <br>
                                <textarea name="obs_6-6"  class="form-control" style="resize: none" rows="10">Estado: </textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label>6.9 Agua Potable de la red de distribucion</label>
                                <br>
                                <textarea name="obs_6-9"  maxlength="515" class="form-control" style="resize: none" rows="10">Estado del Servicio:</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>6.10 Alcantarillado</label>
                                <br>
                                <textarea name="obs_6-10"  class="form-control" style="resize: none" rows="10">Estado: </textarea>
                            </div>
                        </div>

                    </fieldset>
                    <script>
                        function limitarCaracteres(element) {
                            var maxLength = parseInt(element.getAttribute("maxlength"));
                            if (element.value.length > maxLength) {
                                element.value = element.value.substring(0, maxLength);
                            }

                        }
                        function limitarCaracteres2(element) {
                            var maxLength = parseInt(element.getAttribute("maxlength"));
                            if (element.value.length > maxLength) {
                                element.value = element.value.substring(0, maxLength);
                            }

                        }
                        function limitarCaracteres3(element) {
                            var maxLength = parseInt(element.getAttribute("maxlength"));
                            if (element.value.length > maxLength) {
                                element.value = element.value.substring(0, maxLength);
                            }

                        }
                        function limitarCaracteres4(element) {
                            var maxLength = parseInt(element.getAttribute("maxlength"));
                            if (element.value.length > maxLength) {
                                element.value = element.value.substring(0, maxLength);
                            }

                        }
                        function limitarCaracteres5(element) {
                            var maxLength = parseInt(element.getAttribute("maxlength"));
                            if (element.value.length > maxLength) {
                                element.value = element.value.substring(0, maxLength);
                            }

                        }
                        function bloquearSaltoDeLinea(event) {
                            if (event.keyCode === 13) {
                                event.preventDefault();
                                // También puedes mostrar un mensaje de advertencia al usuario si lo deseas
                                console.log("No se permite el salto de línea.");
                            }
                        }
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>