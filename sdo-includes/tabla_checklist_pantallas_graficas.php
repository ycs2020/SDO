    <?php date_default_timezone_set('America/Santiago');?>
    <?php $now = date('H:i:s')?>
    <fieldset class="border p-3">
        <input type="hidden" name="tipoCheckList" id="tipoCheckList" value="Pantallas Graficas">
        <div class="row">
            <div class="form-row">
                <div class="form-group col-mb-2">
                    <label style="float: left;">Pantalla:</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                        </div>
                        <input type="text" class="form-control" id="pantalla" name="pantalla" oninput="this.value = this.value.toUpperCase()" required>

                    </div>
                </div>
                <div class="form-group col-mb-2">
                    <label style="float: left;">Especialidad:</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                        </div>
                        <input type="text" style="text-transform:capitalize" class="form-control" id="especialidad"
                               oninput="this.value = this.value.toUpperCase()" name="especialidad" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-mb-2">
            <label class="form-check-label" style="float: right;"> Marcar Todos</label>
            <br>
            <input type="checkbox" style="float: right;" class="show-mostrar" onchange="marcarOtroInput()">
        </div>
        <br>
        <div class="col-sm">
            <div class="form-check">
                <table class="table table-hover table-bordered">
                    <thead class="text-center bg-gradient-secondary">
                        <th>Item</th>
                        <th>Revisión en pantalla gráfica</th>
                        <th>Estado</th>
                    </thead>
                    <tbody class="text-center">
                    <tr>
                        <td>1</td>
                        <td>Enlaces de botones</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlaceBotones" id="enlaceBotones">
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Enlaces de imágenes</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlaceImagenes" id="enlaceImagenes">
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Enlaces de parámetros (temperatura, presión, etc.)</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlaceparametros" id="enlaceparametros">
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Enlaces de gráficas</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlacegraficas" id="enlacegraficas">
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Enlaces de tendencias</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlacetendencias" id="enlacetendencias">
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Revisión de textos</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlacetextos" id="enlacetextos">
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Revisión de Status effect</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlacestatus" id="enlacestatus">
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Temperatura y humedad exterior</td>
                        <td>
                            <input class="form-check-input" type="checkbox"  value="1" name="enlacehumedad" id="enlacehumedad">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <legend>Observaciones:</legend>
        <textarea class="form-control" id="obs" name="obs" rows="5" style="resize: none"></textarea>
    </fieldset>
