<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Solicitud de Acceso</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;background-attachment: fixed;" class="hold-transition login-page ">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>
<div class="container">
    <div class="row align-items-center vh-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow border">
                <div class="card-header text-center">
                    <h3 class="text-bold">Formulario de Solicitud de Acceso</h3>
                    <br>
                    <br>
                    <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                </div>

                <?php
                $ipPermitida = '10.69.250.151'; // Reemplaza con la IP permitida
                $ipPermitida2 = '10.69.250.19'; // Reemplaza con la IP permitida
                $ipPermitida3 = '10.69.250.205'; // Reemplaza con la IP permitida
                //
                // Obtén la IP del cliente
                $ipCliente = $_SERVER['REMOTE_ADDR'];
                //
                /// // Verifica si la IP del cliente es la permitida
//
//                if ($ipCliente == $ipPermitida || $ipCliente == $ipPermitida2) {
                   ?>

                    <div class="card-body  flex-column align-items-center">
                        <p>
                            Seleccione la Unidad a la cual desea ingresar.
                            <br>
                            <br>
                            <b>Importante: </b>Si la Unidad a la cual desea ingresar no aparece en el listado, quiere decir que dicha Unidad no cuenta con Sistema de Control de Acceso, por lo cual debe seleccionar Accesos Comunes.
                            <br>
                            <br>
                        </p>
                        <form action="accesos_solicitud_2_funcionarios.php" method="post">
                            <label for="solicitanteFormRegAcceso">Nombre del Solicitante:<code>*</code></label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                <input type="text" class="form-control" required placeholder="Nombre del Solicitante" id="solicitanteFormRegAcceso" name="solicitanteFormRegAcceso"/>
                            </div>
                            <br>
                            <label for="unidadSolicitaFormRegAcceso">Unidad del Solicitante:<code>*</code></label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-building"></span>
                                    </div>
                                </div>
                                <input type="text" class="form-control" required placeholder="Unidad del Solicitante" id="unidadSolicitaFormRegAcceso" name="unidadSolicitaFormRegAcceso"/>
                            </div>
                            <br>
                            <label for="emailSolicitaFormRegAcceso">E-Mail del Solicitante:<code>*</code></label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-mail-bulk"></span>
                                    </div>
                                </div>
                                <input type="email" class="form-control" required placeholder="Email del Solicitante" id="emailSolicitaFormRegAcceso" name="emailSolicitaFormRegAcceso"/>
                            </div>
                            <button type="button" style="float: right" id="agregarFila" class="btn bg-gradient-info"><i class="fas fa-plus"></i> Añadir Otra Unidad</button>
                            <br>
                            <label for="unidadSolicitaFormRegAcceso">Unidad donde desea ingresar:<code>*</code></label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-building"></span>
                                    </div>
                                </div>
                                <select class="form-control" id="seleccionAcceso1" name="seleccionAcceso1" required>
                                    <option value="Seleccione..." selected disabled="disabled">Seleccione...</option>
                                    <?php
                                    $querySelec = "SELECT * FROM formularios_acceso ORDER BY unidadFormCCAA ASC";
                                    $resSelec = mysqli_query($conexion, $querySelec);

                                    while ($rowSelec = $resSelec->fetch_assoc()) {

                                        $idFormCCAA = $rowSelec['idFormCCAA'];
                                        $unidadFormCCAA = $rowSelec['unidadFormCCAA'];
                                        $unidadFormCCAA = mb_convert_case($unidadFormCCAA, MB_CASE_UPPER, 'UTF-8');

                                        ?>
                                        <option value="<?php echo $idFormCCAA; ?>"><?php echo $unidadFormCCAA; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="contenedorTablas">

                            </div>
                            <br>
                            <div class="social-auth-links text-center mt-2 mb-3">
                                <input type="hidden" id="contarunidades" name="contarunidades" value="1">
                                <button type="submit" id="guardarBtn" class="btn btn-primary btn-block">Ingresar</button>
                            </div>
                        </form>
                    </div>
<!--                    --><?php
//                } else {
//                    ?>
<!--                    <div class="card-body  flex-column align-items-center text-center font-weight-bold">-->
<!--                        <p>-->
<!--                            Sitio en Mantenimiento.-->
<!--                        </p>-->
<!--                    </div>-->
<!--                    --><?php
//                }
//
//                ?>


            </div>
        </div>

    </div>
</div>
<!-- /.login-box -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('seleccionAcceso1');
        const saveButton = document.getElementById('guardarBtn');
        const botonAnadir = document.getElementById('agregarFila');

        selectElement.addEventListener('change', function() {
            // Habilitar el botón si se selecciona una opción diferente a "Seleccione..."
            if (selectElement.value !== 'Seleccione...') {
                saveButton.disabled = false;
                botonAnadir.disabled = false;
            } else {
                saveButton.disabled = true;
                botonAnadir.disabled = true;
            }
        });

        // Inicializar el estado del botón en función del valor actual del <select>
        if (selectElement.value === 'Seleccione...') {
            saveButton.disabled = true;
            botonAnadir.disabled = true;
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnAgregarFila = document.getElementById('agregarFila');
        const contenedorTablas = document.getElementById('contenedorTablas');
        const contarUnidadesInput = document.getElementById('contarunidades');

        let correlativo = 2;
        let contador = 1; // Inicializar el contador
        btnAgregarFila.addEventListener('click', () => {
            contador++;
            // Crear elemento div
            const div = document.createElement('div');
            div.className = 'input-group mb-3';

            // Crear elementos internos
            const divAppend = document.createElement('div');
            divAppend.className = 'input-group-append';
            const divIcon = document.createElement('div');
            divIcon.className = 'input-group-text';
            const spanIcon = document.createElement('span');
            spanIcon.className = 'fas fa-building';

            // Agregar spanIcon a divIcon
            divIcon.appendChild(spanIcon);
            // Agregar divIcon a divAppend
            divAppend.appendChild(divIcon);

            // Crear elemento select
            const select = document.createElement('select');
            select.className = 'form-control';
            select.id = `seleccionAcceso${correlativo}`; // Asignar un ID único
            select.name = `seleccionAcceso${correlativo}`; // Asignar un nombre único
            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Cargando...';
            optionDefault.disabled = true;
            optionDefault.selected = true;
            select.appendChild(optionDefault);

            // Realizar solicitud AJAX para obtener los datos del servidor
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'datos-unidades.php', true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    // Convertir la respuesta JSON en un objeto JavaScript
                    const datos = JSON.parse(xhr.responseText);

                    // Limpiar el select y agregar las opciones desde los datos obtenidos
                    select.innerHTML = '';
                    const optionDefault = document.createElement('option');
                    optionDefault.value = '';
                    optionDefault.textContent = 'Seleccione...';
                    optionDefault.disabled = true;
                    optionDefault.selected = true;
                    select.appendChild(optionDefault);
                    datos.forEach(function (dato) {
                        const option = document.createElement('option');
                        option.value = dato.idFormCCAA; // Ajustar el valor al idFormCCAA
                        option.textContent = dato.unidadFormCCAA; // Mostrar la unidadFormCCAA
                        select.appendChild(option);
                    });
                } else {
                    // Error al obtener los datos
                    select.textContent = 'Error al cargar datos';
                }
            };
            xhr.onerror = function () {
                // Error de conexión
                select.textContent = 'Error de conexión';
            };
            xhr.send();

            // Crear botón para eliminar el input
            const btnEliminar = document.createElement('button');
            btnEliminar.type = 'button';
            btnEliminar.className = 'btn btn-outline-danger  btnEliminar'; // Reducir tamaño del botón y agregar clase 'btnEliminar'
            btnEliminar.textContent = 'X'; // Cambiar texto por una X para hacerlo más compacto
            btnEliminar.addEventListener('click', () => {
                // Eliminar el div padre al botón
                div.remove();
            });

            // Agregar elementos al div contenedor
            divAppend.appendChild(divIcon);
            divAppend.appendChild(select);
            divAppend.appendChild(btnEliminar);
            div.appendChild(divAppend);

            // Agregar div al contenedor de tablas
            contenedorTablas.appendChild(div);

            // Incrementar el correlativo
            correlativo++;
            contarUnidadesInput.value = contador;
        });

        // Agregar evento delegado para los botones de eliminar
        contenedorTablas.addEventListener('click', (event) => {
            if (event.target && event.target.classList.contains('btnEliminar')) {
                contador--; // Decrementar el contador cuando se elimina un elemento
                contarUnidadesInput.value = contador; // Actualizar el valor del input

                // Eliminar el div padre del botón
                const div = event.target.closest('.input-group');
                if (div) {
                    div.remove();
                }
            }
        });
    });
</script>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>

</html>