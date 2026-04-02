<?php
include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

if(isset($_POST['solicitanteFormRegAcceso'],$_POST['unidadSolicitaFormRegAcceso'],$_POST['emailSolicitaFormRegAcceso'])){
    $solicitanteFormRegAcceso = $_POST['solicitanteFormRegAcceso'];
    $unidadSolicitaFormRegAcceso = $_POST['unidadSolicitaFormRegAcceso'];
    $emailSolicitaFormRegAcceso = $_POST['emailSolicitaFormRegAcceso'];
}else{
    header('Location: accesos_seleccion.php?errorCargaDatos=true');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de Formato de Acceso</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>
<body style="background-image:url(../dist/img/Hospital.png); background-size: cover;" class="hold-transition login-page ">
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>
    <div class="login-box w-100">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a class="h2"><b>Solicitudes de Accesos - Listado de Funcionarios</b></a>
                <br>
                <br>
                <input style="align-content: center" type="image" src="../dist/img/controlCent-HBQP-80.png">
                <br>
                <div style="float: right">
                    <b class="h5">Unidades Seleccionadas:</b>
                    <br>
                    <?php
                    if(isset($_POST['contarunidades'])){
                        $seleccionAccesoArray = array();
                        $cantidadFilas = $_POST['contarunidades'];
                        if($cantidadFilas > 0){
                            for ($i = 1; $i <= $cantidadFilas; $i++) {
                                $seleccionAcceso = $_POST['seleccionAcceso' . $i];
                                $seleccionAccesoArray[] = $seleccionAcceso;
                                $sql = "SELECT * FROM formularios_acceso WHERE  idFormCCAA = $seleccionAcceso";
                                $res = mysqli_query($conexion,$sql);
                                while($row = $res->fetch_assoc()){
                                    $unidadFormCCAA = $row['unidadFormCCAA'];
                                    echo $unidadFormCCAA.'<br>';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="card-body flex-column align-items-center" style="max-height: 70vh; overflow-y: auto;">
                <a href="accesos_seleccion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                <br>
                <br>
                <hr>
                <button class="float-right btn bg-primary" type="button" id="btnAgregarFila"><i class="fas fa-plus"></i> Agregar otro Funcionario a la Tabla</button>
                <br>
                <br>
                <h6>
                    Nota: Si la calidad juridica del funcionario es Contrata o Titular, el correlativo debe ser 0.<br>
                    Nota 2: En el caso que la credencial posea un número, por ejemplo: (Reemplazo 001), debera solamente agregar en el correlativo el 1
                </h6>
                <form action="accesos_solicitud_permisos.php" method="POST">
                    <table id="miTabla" class="table table-bordered" style="font-size: 12px;">
                        <thead class="bg-secondary">
                        <tr>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CORRELATIVO TARJETA</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">FECHA DE INICIO</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">FECHA DE TERMINO</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES:</td>
                        </tr>
                        </thead>
                        <tbody id="miTbody">
                        <tr>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="text" id="nomFuncionario1" required name="nomFuncionario1" class="form-control" placeholder="Nombre">
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="text" id="rut1" oninput="formatRUT(this)" required name="rutFuncionario1" class="form-control rut" placeholder="Formato: 01.234.567-8">
                            </td>
                            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <select onchange="toggleFields()" class="form-control" id="calidadJuridica1" name="calidadJuridica1">
                                    <option value="Seleccione..." disabled selected>Seleccione</option>
                                    <option value="Alumno en Practica">Alumno en Practica</option>
                                    <option value="Becado">Becado</option>
                                    <option value="Contrata">Contrata</option>
                                    <option value="Docente Externo">Docente Externo</option>
                                    <option value="Empresa Externa">Empresa Externa</option>
                                    <option value="Honorarios">Honorarios</option>
                                    <option value="Medico PAO">Medico PAO</option>
                                    <option value="Reemplazo">Reemplazo</option>
                                    <option value="Titular">Titular</option>
                                </select>
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="text" id="unidadFuncionario1" required name="unidadFuncionario1" class="form-control" placeholder="Unidad">
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                <input type="number" id="correlativotarjeta1" required name="correlativotarjeta1" class="form-control" placeholder="Correlativo">
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;" id="inicio1">
                                <input type="date" required name="inicio1" min="1951-01-01" id="inputInicio1" onchange="verificarFechas()"  class="form-control" style="display: none;">
                                <span class="mensaje" id="mensajeInicio1" style="color: red;">No aplica</span>
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;" id="termino1">
                                <input type="date" required  name="termino1" min="1951-01-01" id="inputTermino1" onchange="verificarFechas()" class="form-control" style="display: none;">
                                <span class="mensaje" id="mensajeTermino1" style="color: red;">No aplica</span>
                            </td>
                            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                        </tr>
                        </tbody>
                        <tfoot class="bg-secondary">
                        <tr>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">NOMBRE DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">RUT DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CALIDAD JURIDICA DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">UNIDAD DEL FUNCIONARIO:</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">CORRELATIVO TARJETA</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">FECHA DE INICIO</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">FECHA DE TERMINO</td>
                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">ACCIONES</td>
                        </tr>
                        </tfoot>
                    </table>
                    <br>
                    <input type="hidden" name="cantidadFilasFuncionario" id="cantidadFilasFuncionario" value="1"/>
                    <input type="hidden" name="seleccionAccesoArray" id="seleccionAccesoArray" value="<?php echo base64_encode(serialize($seleccionAccesoArray));?>">
                    <input type="hidden" name="contarunidades" id="contarunidades" value="<?php echo $cantidadFilas ?>">
                    <input type="hidden" name="solicitanteFormRegAcceso" value="<?php echo $solicitanteFormRegAcceso?>">
                    <input type="hidden" name="unidadSolicitaFormRegAcceso" value="<?php echo $unidadSolicitaFormRegAcceso?>">
                    <input type="hidden" name="emailSolicitaFormRegAcceso" value="<?php echo $emailSolicitaFormRegAcceso?>">
                    <button type="submit" id="btnGuardar" class="btn btn-block bg-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
    function formatRUT(input) {
        // Eliminar caracteres no numéricos y la 'K'
        let value = input.value.replace(/[^0-9kK]/g, '');

        // Validar que no exceda los 9 dígitos (8 del RUT + 1 dígito verificador)
        if (value.length > 9) {
            value = value.slice(0, 9);
        }

        // Agregar guion antes del dígito verificador
        if (value.length > 8) {
            const rutPart = value.slice(0, 8);
            const dvPart = value.slice(8, 9).toUpperCase(); // Convertir a mayúscula
            value = rutPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + dvPart;
        } else {
            // Formatear solo la parte del RUT si tiene menos de 8 dígitos
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        input.value = value;
    }
</script>
<!--
<script>
    function verificarFechas() {
        const fechaInicioInput = document.querySelector("#inicio1 input");
        const fechaTerminoInput = document.querySelector("#termino1 input");
        const btnGuardar = document.getElementById('btnGuardar');

        // Obtener los valores de las fechas en formato YYYY-MM-DD
        const fechaInicio = new Date(fechaInicioInput.value);
        const fechaTermino = new Date(fechaTerminoInput.value);

        // Verificar si las fechas son válidas
        if (!fechaInicio || !fechaTermino) {
            btnGuardar.disabled = true; // Deshabilitar el botón si las fechas son inválidas
            return;
        }

        // Verificar si la fecha de inicio es mayor que la fecha de término
        if (fechaInicio > fechaTermino) {
            btnGuardar.disabled = true; // Deshabilitar el botón
            alert("La fecha de inicio no puede ser mayor que la fecha de término.");
        } else {
            btnGuardar.disabled = false; // Habilitar el botón si la fecha de inicio es menor o igual a la de término
        }
    }
</script>
-->
<script>
    function toggleFields() {
        const select = document.getElementById("calidadJuridica1");
        const selectedValue = select.value;

        const fechaInicioInput = document.querySelector("#inicio1 input");
        const fechaTerminoInput = document.querySelector("#termino1 input");
        const mensajeInicio = document.getElementById("mensajeInicio1");
        const mensajeTermino = document.getElementById("mensajeTermino1");

        console.log(fechaInicioInput);

        // Mostrar u ocultar los elementos según la opción seleccionada
        if (selectedValue === "Seleccione..." || selectedValue === "Titular") {
            fechaInicioInput.style.display = "none"; // Oculta el input
            fechaTerminoInput.style.display = "none"; // Oculta el input
            mensajeInicio.style.display = "inline"; // Muestra el mensaje
            mensajeTermino.style.display = "inline"; // Muestra el mensaje

            // Quitar los atributos required
            fechaInicioInput.required = false;
            fechaTerminoInput.required = false;

        } else {
            fechaInicioInput.style.display = "inline"; // Muestra el input
            fechaTerminoInput.style.display = "inline"; // Muestra el input
            mensajeInicio.style.display = "none"; // Oculta el mensaje
            mensajeTermino.style.display = "none"; // Oculta el mensaje

            // Agregar los atributos required
            fechaInicioInput.required = true;
            fechaTerminoInput.required = true;
        }
    }
</script>

<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('btnAgregarFila');
    let correlativo = 2;
    var tbody = document.getElementById('miTbody');

    btnAgregarFila.addEventListener('click', () => {
        const nuevaFila = document.createElement('tr');
        let contenido = `
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" id="nomFuncionario${correlativo}" required name="nomFuncionario${correlativo}" class="form-control" placeholder="Nombre">
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" id="rut" required name="rutFuncionario${correlativo}" oninput="formatMuchosRUT(this)" class="form-control rut" placeholder="Formato: 01.234.567-8">
            </td>
            <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <select onchange="cambiarEstado(${correlativo})" class="form-control" id="calidadJuridica${correlativo}" name="calidadJuridica${correlativo}">
                    <option value="Seleccione..." disabled selected>Seleccione</option>
                    <option value="Alumno en Practica">Alumno en Practica</option>
                    <option value="Becado">Becado</option>
                    <option value="Contrata">Contrata</option>
                    <option value="Docente Externo">Docente Externo</option>
                    <option value="Empresa Externa">Empresa Externa</option>
                    <option value="Honorarios">Honorarios</option>
                    <option value="Medico PAO">Medico PAO</option>
                    <option value="Reemplazo">Reemplazo</option>
                    <option value="Titular">Titular</option>
                </select>
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="text" id="unidadFuncionario${correlativo}" required name="unidadFuncionario${correlativo}" class="form-control" placeholder="Unidad">
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">
                <input type="number" id="correlativotarjeta${correlativo}" required name="correlativotarjeta${correlativo}" class="form-control" placeholder="Correlativo">
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;" id="inicio${correlativo}">
                <input type="date" name="inicio${correlativo}" min="1951-01-01" onchange="verificarMuchasFechas(${correlativo})" id="inputInicio${correlativo}"  class="form-control" style="display: none;">
                <span class="mensaje" id="mensajeInicio${correlativo}" style="color: red;">No aplica</span>
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;" id="termino${correlativo}">
                <input type="date" name="termino${correlativo}"  min="1951-01-01"  onchange="verificarMuchasFechas(${correlativo})" id="inputTermino${correlativo}" class="form-control" style="display: none;">
                <span class="mensaje" id="mensajeTermino${correlativo}" style="color: red;">No aplica</span>
            </td>
            <td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;">
                <button type="button" onclick="eliminarFila(this)" class="btn btn-block btn-danger">
                    <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar
                </button>
            </td>

        `;

        nuevaFila.innerHTML = contenido;
        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++;

        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    });

    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        correlativo--;
        const cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;
        document.getElementById('cantidadFilasFuncionario').value = cantidadDeFilas2;
    }

    function cambiarEstado(correlativo) {
        const select = document.getElementById(`calidadJuridica${correlativo}`);
        const selectedValue = select.value;

        const fechaInicioInput = document.querySelector(`#inicio${correlativo} input`);
        const fechaTerminoInput = document.querySelector(`#termino${correlativo} input`);
        const mensajeInicio = document.getElementById(`mensajeInicio${correlativo}`);
        const mensajeTermino = document.getElementById(`mensajeTermino${correlativo}`);

        console.log(fechaInicioInput);

        // Mostrar u ocultar los elementos según la opción seleccionada
        if (selectedValue === "Seleccione..." || selectedValue === "Titular") {
            fechaInicioInput.style.display = "none"; // Oculta el input de fecha de inicio
            fechaTerminoInput.style.display = "none"; // Oculta el input de fecha de término
            mensajeInicio.style.display = "inline"; // Muestra el mensaje de "No aplica"
            mensajeTermino.style.display = "inline"; // Muestra el mensaje de "No aplica"

            // Quitar los atributos required
            fechaInicioInput.required = false;
            fechaTerminoInput.required = false;

        } else {
            fechaInicioInput.style.display = "inline"; // Muestra el input de fecha de inicio
            fechaTerminoInput.style.display = "inline"; // Muestra el input de fecha de término
            mensajeInicio.style.display = "none"; // Oculta el mensaje de "No aplica"
            mensajeTermino.style.display = "none"; // Oculta el mensaje de "No aplica"

            // Agregar los atributos required
            fechaInicioInput.required = true;
            fechaTerminoInput.required = true;
        }
    }
/*
    function verificarMuchasFechas(correlativo) {
        const btnGuardar = document.getElementById('btnGuardar');
        let todasFechasValidas = true; // Bandera para verificar la validez de todas las fechas

        // Suponiendo que tienes múltiples pares de fecha con IDs como "inicio1", "termino1", "inicio2", "termino2", etc.
        const fechaInicioInput = document.querySelector(`#inicio${correlativo} input`);
        const fechaTerminoInput = document.querySelector(`#termino${correlativo} input`);

        // Obtener los valores de las fechas en formato YYYY-MM-DD
        const fechaInicio = new Date(fechaInicioInput.value);
        const fechaTermino = new Date(fechaTerminoInput.value);

        // Verificar si las fechas son válidas
        if (!fechaInicio || !fechaTermino || isNaN(fechaInicio.getTime()) || isNaN(fechaTermino.getTime())) {
            todasFechasValidas = false; // Marca que hay fechas inválidas
        }

        // Verificar si la fecha de inicio es mayor que la fecha de término
        if (fechaInicio > fechaTermino) {
            todasFechasValidas = false; // Marca que la fecha de inicio es mayor que la de término
            alert(`La fecha de inicio de la fila ${correlativo} no puede ser mayor que la fecha de término.`);
        }

        // Habilitar o deshabilitar el botón basado en la validez de las fechas
        btnGuardar.disabled = !todasFechasValidas;
    }


    document.addEventListener("DOMContentLoaded", function() {
        const fechaMinima = new Date('1951-01-01');

        // Seleccionar todos los inputs de tipo date que coincidan con el patrón "inputInicio" o "inputTermino"
        const inputsInicio = document.querySelectorAll("input[id^='inputInicio']");
        const inputsTermino = document.querySelectorAll("input[id^='inputTermino']");

        // Función para validar la fecha
        function validarFecha(input, mensaje) {
            const fechaIngresada = new Date(input.value);
            if (input.value === "" || fechaIngresada < fechaMinima) {
                mensaje.style.display = "inline";
            } else {
                mensaje.style.display = "none";
            }
        }

        // Añadir validación a todos los inputs de "inicio"
        inputsInicio.forEach(input => {
            const numero = input.id.replace("inputInicio", "");
            const mensaje = document.getElementById(`mensajeInicio${numero}`);
            input.addEventListener("change", function() {
                validarFecha(input, mensaje);
            });
        });

        // Añadir validación a todos los inputs de "termino"
        inputsTermino.forEach(input => {
            const numero = input.id.replace("inputTermino", "");
            const mensaje = document.getElementById(`mensajeTermino${numero}`);
            input.addEventListener("change", function() {
                validarFecha(input, mensaje);
            });
        });

    });
*/
    function formatMuchosRUT(input) {
        // Eliminar caracteres no numéricos y la 'K'
        let value = input.value.replace(/[^0-9kK]/g, '');

        // Validar que no exceda los 9 dígitos (8 del RUT + 1 dígito verificador)
        if (value.length > 9) {
            value = value.slice(0, 9);
        }

        // Agregar guion antes del dígito verificador
        if (value.length > 8) {
            const rutPart = value.slice(0, 8);
            const dvPart = value.slice(8, 9).toUpperCase(); // Convertir a mayúscula
            value = rutPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + dvPart;
        } else {
            // Formatear solo la parte del RUT si tiene menos de 8 dígitos
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        input.value = value;
    }
</script>

</html>


