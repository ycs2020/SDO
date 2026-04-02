<div class="modal fade" id="exampleModal<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title" id="exampleModalLabel">Informe EDAN: <?php echo $id?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="myForm" action="../sdo-funciones/ingresar-obs-edan-movi.php" method="POST">
                <input type="hidden" id="id" name="id" value="<?php echo $id?>">
                <input type="hidden" id="idDepa" name="idDepa" value="<?php echo $idDepartamento?>">
                <div class="modal-body">
                    <label>Nombre del Usuario</label>
                    <input type="text" name="nomUsuario" readonly class="form-control-border form-control" value="<?php echo $nomUsuario?>">
                    <br>
                    <fieldset>
                        <h6><b>¿Se interrumpio el funcionamiento de servicios clinicos y/o programas?</b></h6>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>6.15 Vehiculos (Camionetas o ambulancias)</label>
                                <br>
                                <textarea name="obs_6-15" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
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