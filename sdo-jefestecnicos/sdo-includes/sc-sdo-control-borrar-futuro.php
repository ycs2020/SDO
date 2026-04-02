<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5>2.- Solicitar Compra de Materiales</h5>
            <hr>
            <form id="formGuardarSC" action="../sdo-funciones/soliCompra_generarSCSDO.php" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12">
                        <label for="exampleInputArgumentos">Descripci&oacute;n de Solicitud (Ejemplo: Solicitud de Herramientas):<code>*</code></label>
                        <input type="text" class="form-control" maxlength="60" id="descripcionSoliCompra" name="descripcionSoliCompra" required placeholder="Descripción Solicitud">
                    </div>
                    <div class="form-group col-xl-4 col-lg-4 col-md-6 col-12" style="display: none">
                        <label for="exampleInputArgumentos">Ingrese N&uacute;mero de Ticket SIGH:</label>
                        <input type="text" class="form-control" maxlength="20" id="ticketSIGH" name="ticketSIGH" placeholder="Ticket SIGH">
                    </div>
                </div>
                <br>
                <?php
                if ($idDepartamento == 1 || $idDepartamento == 2 || $idDepartamento == 3 || $idDepartamento == 4 || $idDepartamento == 10 || $idDepartamento == 12 || $idDepartamento == 5 || $idDepartamento == 6 || $idDepartamento == 8) {

                    ?>
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">
                            <label for="selectSubAsignacion">Sub-Asignaci&oacute;n:</label>
                            <select class="form-control" name="selectSubAsignacion" id="selectSubAsignacion">
                                <option value="Seleccione" selected disabled>Seleccione...</option>
                                <?php
                                //CONTROL CENTRALIZADO Y ESPECIALIDADES
                                if ($idDepartamento == 1 || $idDepartamento == 2 || $idDepartamento == 3 || $idDepartamento == 4 || $idDepartamento == 10 || $idDepartamento == 12) {
                                    // FIN CONTROL CENTRALIZADO Y ESPECIALIDADES

                                    $sqlSubAsignacion = "SELECT * FROM subasignaciones_presupuesto WHERE idDepartamento = 9 ORDER BY nomSubAsigna ASC";

                                    //INFRAESTRUCTURA
                                } else if ($idDepartamento == 5) {
                                    //INFRAESTRUCTURA

                                    $sqlSubAsignacion = "SELECT * FROM subasignaciones_presupuesto WHERE idDepartamento = 5 ORDER BY nomSubAsigna ASC";

                                    //EQUIPOS MEDICOS
                                } else if ($idDepartamento == 6) {
                                    //EQUIPOS MEDICOS

                                    $sqlSubAsignacion = "SELECT * FROM subasignaciones_presupuesto WHERE idDepartamento = 6 ORDER BY nomSubAsigna ASC";

                                    //MOVILIZACIÓN
                                } else if ($idDepartamento == 8) {
                                    //MOVILIZACIÓN

                                    $sqlSubAsignacion = "SELECT * FROM subasignaciones_presupuesto WHERE idDepartamento = 8 ORDER BY nomSubAsigna ASC";



                                }

                                $resSubAsignacion = mysqli_query($conexion, $sqlSubAsignacion);


                                if(mysqli_num_rows($resSubAsignacion) > 0) {
                                    while ($rowSubAsignacion = $resSubAsignacion->fetch_assoc()) {
                                        $nomSubAsigna = $rowSubAsignacion['nomSubAsigna'];

                                        if(!empty($nomSubAsigna)){
                                            $nomSubAsigna2 = mb_convert_case($nomSubAsigna,MB_CASE_UPPER,'UTF-8');
                                            echo "<option value='".$rowSubAsignacion['idSubAsigna']."'>".$nomSubAsigna2."</option>";
                                        }

                                    }
                                }

                                ?>

                            </select>
                        </div>
                    </div>
                    <br>
                    <?php
                }
                ?>

                <!-- AQUI PABLO CARACTERES -->
                <div class="form-group">
                    <label for="exampleInputArgumentos">Argumente los motivos y necesidad de la compra:<code>*</code> (Maximo 1271 Caracteres)</label>
                    <textarea id="argumentosNecesidad" style="resize:none;" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="1271" required name="argumentosNecesidad" class="form-control" rows="10" placeholder="Argumentos y Motivos de la Solicitud (Maximo 1271 Caracteres)"></textarea>

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


                <br><br>

                <div class="form-group text-right">

                    <button type="button" class="bg-primary btn" id="agregarFila"><i class="fas fa-plus"></i>&nbsp;&nbsp;Agregar Material</button>
                </div>

                <br><br>


                <div class="table-responsive">
                    <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                        <thead class="bg-secondary">
                        <tr>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE MATERIAL:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CANTIDAD:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">MEDIDA:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">VALOR TOTAL REFERENCIAL:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                        </tr>
                        </thead>
                        <tbody id="miTbody">
                        <tr>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="text" class="form-control" name="articuloSC1" list="opcionesArticulo1" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" placeholder="Articulo">
                                <datalist id="opcionesArticulo1">
                                    <?php echo listadoInventarioDataList(); ?>
                                </datalist>
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="number" min="1" class="form-control" id="cantidadSC1" onchange="calcularSuma(this)" oninput="calcularSuma(this)" name="cantidadSC1" placeholder="Cantidad">
                                <input type="hidden" class="form-control" name="valorUnit1" id="valorUnit1" value="0">
                            </td>

                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="text" min="1" class="form-control" id="medidaSC1" name="medidaSC1" placeholder="Medida">
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="number" min="0" class="form-control" id="valorSC1" name="valorSC1" oninput="actualizarValorTotal()" onchange="actualizarValorTotal()" placeholder="Valor Total Referencial">
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                No se puede eliminar
                            </td>
                        </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="font-weight: bold; font-size: 16px; text-align: right;" colspan="3">TOTAL : (Valor no se incluye en Solicitud de Compra)</th>
                            <th style="font-weight: bold; font-size: 16px;" id="thValorTotal"></th>
                            <th>&nbsp;</th>
                        </tr>
                        </tfoot>
                    </table>

                    <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                </div>


                <br>


                <div class="form-group">
                    <!-- <label for="customFile">Custom File</label> -->
                    <label for="exampleInputTareasControlCent">Subir Archivos Adjuntos: </label><br>
                    <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml</label>
                    <div class="custom-file">
                        <input type="file" max-uploads="40" class="custom-file-input" onchange="mostrarRadio(this)" multiple="" id="adjuntosSC[]" name="adjuntosSC[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml">
                        <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                    </div>
                </div>

                <fieldset class="border p-3" id="radios" style="display: none;">
                    <h6>¿Es una cotización el archivo adjunto?</h6>
                    <div class="form-group">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion1" value="1">
                            <label class="form-check-label" for="inlineRadio1">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input " type="radio" name="isCotizacion" id="isCotizacion2" value="0">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>

                    </div>
                </fieldset>
                <script type="text/javascript">
                    $('.custom-file input').change(function (e) {
                        var files = [];
                        for (var i = 0; i < $(this)[0].files.length; i++) {
                            files.push($(this)[0].files[i].name);
                        }
                        $(this).next('.custom-file-label').html(files.join(', '));
                    });
                </script>

                <br>
                <hr>
                <br>

                <div class="form-group">
                    <input type="hidden" name="personalSC" id="personalSC" value="<?php echo $nomUsuario; ?>">
                    <input type="hidden" name="cargoSC" id="cargoSC" value="<?php echo $cargoJefe; ?>">
                    <input type="hidden" name="deptoSC" id="deptoSC" value="<?php echo $nomDepartamento; ?>">
                    <input type="hidden" name="emailSC" id="emailSC" value="<?php echo $emailJefe; ?>">
                    <input type="hidden" name="idSolicitudSoporte" id="idSolicitudSoporte" value="<?php echo $idSolicitud ?>">
                    <input type="hidden" name="cargaSolicitante" id="cargaSolicitante" value="PerfilEncargado">
                    <input type="submit" id="btn-guardarSC" name="btn-guardarSC" class="btn btn-block btn-success btn-guardarSC" value="Guardar"/>

                </div>
                <script>
                    // Selecciona el botón Guardar
                    const btnGuardar = document.getElementById('btn-guardarSC');

                    // Agrega un event listener para el clic en el botón Guardar
                    btnGuardar.addEventListener('click', function (event) {
                        // Previene el comportamiento por defecto del botón
                        event.preventDefault();

                        // Muestra el mensaje de confirmación
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: '¿Deseas generar la Solicitud de Compra?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            // Si el usuario confirma, envía el formulario
                            if (result.isConfirmed) {
                                btnGuardar.disabled = true;

                                document.getElementById('formGuardarSC').submit(); // Reemplaza 'formGuardarSC' con el ID de tu formulario
                            }
                        });
                    });
                </script>
            </form>

        </div>

    </div>

</div>


<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('agregarFila');
    let correlativo = 2;
    var tbody = document.getElementById('miTbody');

    let valorTotalAcumulado = 0;


    btnAgregarFila.addEventListener('click', () => {
        contenido = '';
        const nuevaFila = document.createElement('tr');
        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">' +
            '<input type="text" class="form-control" oninput="limpiarMedida(this)" onchange="cambiarUnidad(this)" name="articuloSC' + correlativo + '" list="opcionesArticulo' + correlativo + '" placeholder="Articulo">' +
            '<datalist id="opcionesArticulo' + correlativo + '"><?php echo listadoInventarioDataList(); ?></datalist>' +
            '</td>';

        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="1" onchange="calcularSuma(this)" oninput="calcularSuma(this)"  class="form-control" id="cantidadSC' + correlativo + '" name="cantidadSC' + correlativo + '" placeholder="Cantidad"><input type="hidden" class="form-control" name="valorUnit' + correlativo + '" id="valorUnit' + correlativo + '" value="0"></td>';

        contenido += '<td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="text" min="1" class="form-control" id="medidaSC' + correlativo + '" name="medidaSC' + correlativo + '" placeholder="Medida"></td>';

        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;"><input type="number" min="0" class="form-control" id="valorSC' + correlativo + '" name="valorSC' + correlativo + '" oninput="actualizarValorTotal()" onchange="actualizarValorTotal()" placeholder="Valor Total Referencial"></td>';

        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';

        nuevaFila.innerHTML = contenido;

        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++; // Incrementar el contador

        var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilas').value = cantidadDeFilas2; // Actualizar la cantidad de filas en un campo oculto
    });

    function limpiarMedida(input) {
        const selectedOption = input.value;
        const dataList = input.list;
        const options = dataList.options;

        // Obtener el número de correlativo del id del input
        const correlativo = input.name.replace('articuloSC', '');

        const medidaInput = document.getElementById('medidaSC' + correlativo);
        const valorUnitInput = document.getElementById('valorUnit' + correlativo);

        // Verificar si el valor ingresado coincide con algún valor del datalist
        let coincide = false;
        for (let i = 0; i < options.length; i++) {
            if (options[i].text === selectedOption) {
                coincide = true;
                break;
            }
        }

        // Si no coincide, limpiar el valor del input medidaSC
        if (!coincide) {
            medidaInput.value = '';
            valorUnitInput.value = '';
        }
    }

    function cambiarUnidad(input) {
        const selectedOption = input.value;
        const dataList = input.list;
        const options = dataList.options;

        // Obtener el número de correlativo del id del input
        const correlativo = input.name.replace('articuloSC', '');

        const medidaInput = document.getElementById('medidaSC' + correlativo);

        const valorUnitInput = document.getElementById('valorUnit' + correlativo);

        // Buscar la opción seleccionada y obtener su unidad
        for (let i = 0; i < options.length; i++) {
            if (options[i].text === selectedOption) {
                const unidad = options[i].getAttribute('data-unidad');
                const valorSin = options[i].getAttribute('data-valorSin');
                medidaInput.value = unidad; // Asignar la unidad al input de medida correspondiente
                valorUnitInput.value = valorSin;
                break;
            }
        }


        // Si no coincide, limpiar el valor del input medidaSC
        if (!coincide) {
            medidaInput.value = '';
            valorUnitInput.value = '';
        }
    }

    function calcularSuma(input) {
        const selectedOption = input.value;

        // Obtener el número de correlativo del id del input
        const correlativo = input.name.replace('cantidadSC', '');

        const valorSC = document.getElementById('valorSC' + correlativo);
        const valorUnitInput = document.getElementById('valorUnit' + correlativo).value;

        // Calcular el nuevo valor total
        const valorFinal = Math.floor(selectedOption * valorUnitInput * 1.19);

        // Actualizar el valor total en el input correspondiente
        valorSC.value = valorFinal;

        // Actualizar el valor total en el th
        actualizarValorTotal();
    }

    function actualizarValorTotal() {
        let valorTotal = 0;

        // Obtener todas las filas de la tabla
        const filas = document.querySelectorAll('#miTabla tbody tr');

        // Iterar sobre cada fila para calcular el valor total
        filas.forEach((fila, index) => {
            const cantidadInput = fila.querySelector('input[name^="cantidadSC"]');
            const valorUnitarioInput = fila.querySelector('input[name^="valorUnit"]');
            const cantidad = parseInt(cantidadInput.value);
            const valorUnitario = parseFloat(valorUnitarioInput.value);

            if (cantidad > 0) {

                valorTotal += cantidad * valorUnitario;
            } else {

                valorTotal += 0;
            }

        });

        // Calcular el valor total final con impuestos
        const valorTotalConImpuestos = Math.floor(valorTotal * 1.19);

        const valorTotalConImpuestos2 = valorTotalConImpuestos.toLocaleString('es-CL', {
            style: 'currency',
            currency: 'CLP'
        });

        // Actualizar el contenido del th con el valor total
        document.getElementById('thValorTotal').textContent = valorTotalConImpuestos2;
    }


    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        // Actualiza los correlativos después de eliminar una fila
        correlativo--;
        // Obtén una referencia al elemento <tbody> por su id

        // Cuenta la cantidad de elementos <tr> dentro del <tbody>
        var cantidadDeFilas = tbody.getElementsByTagName('tr').length;

        if (cantidadDeFilas < 1) {
            document.getElementById("enviarFormulario").disabled = true;
        }
        var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

        document.getElementById('cantidadFilas').value = cantidadDeFilas2;
    }

    function actualizarCorrelativos() {
        const filas = tabla.querySelectorAll('tbody tr');
        filas.forEach((fila, index) => {
            const correlativoCell = fila.querySelector('td:first-child');
            correlativoCell.textContent = index + 1;
        });
    }


</script>
