<div class="modal fade" id="exampleModal<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title" id="exampleModalLabel">Informe EDAN: <?php echo $id?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../sdo-funciones/ingresar-obs-edan-industriales.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                <input type="hidden" id="idDepa" name="idDepa" value="<?php echo $idDepartamento?>">
                <div class="modal-body">
                    <label>Nombre del Usuario</label>
                    <br>
                    <input type="text" name="nomUsuario" readonly class="form-control-border form-control" value="<?php echo $nomUsuario?>">
                    <br>
                    <fieldset>
                        <h6><b>¿Se produjo afectación en las lineas vitales?</b></h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>6.7 Gases Clínicos</label>
                                <br>
                                <?php
                                    $gases = "Cantidad de gases clínicos en m3: ";
                                ?>
                                <textarea name="obs_6-7"  oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"><?php echo $gases?></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label>6.9 Agua potable de la red de distribución</label>
                                <br>
                                <?php
                                    $agua = "Cantidad de almacenamiento de agua en m3 : ";
                                ?>
                                <textarea name="obs_6-9"  oninput="limitarCaracteres2(this)" maxlength="515" class="form-control" style="resize: none" rows="10"><?php echo $agua?></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label>6.24 Otros (Combustible)</label>
                                <br>
                                <?php
                                    $tanques1 = "Cantidad de Combustible disponible: ";
                                ?>
                                <textarea name="obs_6-24"  oninput="limitarCaracteres3(this)" maxlength="515" class="form-control" style="resize: none" rows="10"><?php echo $tanques1?></textarea>
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