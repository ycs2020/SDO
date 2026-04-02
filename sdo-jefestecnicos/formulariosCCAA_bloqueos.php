<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

date_default_timezone_set('America/Santiago');

if (isset($_POST['seleccionBloqueos'])) {

    $seleccionBloqueos = trim($_POST['seleccionBloqueos']);

    if (!empty($seleccionBloqueos)) {

        if ($seleccionBloqueos == 'Bloqueo') {

            $nomSelecBloqueo = 'Bloqueo';

        } else if ($seleccionBloqueos == 'Desvinculacion') {

            $nomSelecBloqueo = 'Desvinculacion';

        } else if ($seleccionBloqueos == 'Perdida') {

            $nomSelecBloqueo = 'Perdida';

        } else {

            $nomSelecBloqueo = $seleccionBloqueos;
        }

    } else {
        header('Location: formulariosCCAA_menuSeleccion.php?errorCarga=true');
    }

} else {
    header('Location: formulariosCCAA_menuSeleccion.php?errorCarga=true');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de <?php echo $nomSelecBloqueo; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Formulario de <?php echo $nomSelecBloqueo; ?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="formulariosCCAA_menuSeleccion.php" id="hrefInicio" class="text-dark"></a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="#" class="h4"><b>Formulario de <?php echo $nomSelecBloqueo; ?></b></a>
                                <br>
                                <br>
                                <a href="formulariosCCAA_menuSeleccion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">




                                <form action="../sdo-funciones/formularios_realizarBloqueo.php" method="post">


                                    <div class="row">

                                        <div class="form-group col-lg-3 col-md-3">
                                            <label for="solicitanteFormRegAcceso">Nombre del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="Nombre del Solicitante" id="solicitanteFormRegAcceso" name="solicitanteFormRegAcceso"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3">

                                            <label for="unidadSolicitaFormRegAcceso">Unidad del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-building"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="Unidad del Solicitante" id="unidadSolicitaFormRegAcceso" name="unidadSolicitaFormRegAcceso"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3">
                                            <label for="emailSolicitaFormRegAcceso">E-Mail del Solicitante:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-mail-bulk"></span>
                                                    </div>
                                                </div>
                                                <input type="email" class="form-control" required placeholder="Email del Solicitante" id="emailSolicitaFormRegAcceso" name="emailSolicitaFormRegAcceso"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3">
                                            <label for="creacionSolicitaFormRegAcceso">Fecha de Documento:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-calendar"></span>
                                                    </div>
                                                </div>
                                                <input type="date" class="form-control" required placeholder="Fecha de Documento" id="fechaDocumentoBloqueo" name="fechaDocumentoBloqueo"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3">
                                            <label for="emailSolicitaFormRegAcceso">Tipo del Documento:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-list"></span>
                                                    </div>
                                                </div>
                                                <select id="tipoDocumentoSolicitaFormRegAcceso"name="tipoDocumentoSolicitaFormRegAcceso" class="form-control">
                                                    <option value="Seleccione..." selected disabled>Seleccione</option>
                                                    <option value="E-Mail" >E-Mail</option>
                                                    <option value="Memorandum" >Memorandum</option>
                                                    <option value="Otro" >Otro</option>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3">
                                            <label for="emailSolicitaFormRegAcceso">ID del Documento:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-mail-bulk"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="ID de Documento" id="numDocumentoSolicitaFormRegAcceso" name="numDocumentoSolicitaFormRegAcceso"/>
                                            </div>
                                        </div>

                                    </div>

                                    <br><br>

                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <label for="dato">Ingrese RUT de Funcionario:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user"></span>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" required placeholder="Ingrese RUT (XXXXXXXXXXX)" id="rut" name="rut"/>

                                                <script>
                                                    function formatearRut(rut) {
                                                        rut = rut.replace(/[^\dkK]/g, ''); // Permitir solo dígitos y la letra 'K' (mayúscula o minúscula)

                                                        if (rut.length > 1) {
                                                            let formateado = rut.slice(-4, -1) + '-' + rut.substr(rut.length - 1);
                                                            for (let i = 4; i < rut.length; i += 3) {
                                                                formateado = rut.slice(-i - 3, -i) + '.' + formateado;
                                                            }
                                                            return formateado;
                                                        } else {
                                                            return rut; // Si el RUT no tiene al menos 2 caracteres, se muestra tal cual
                                                        }
                                                    }

                                                    document.getElementById('rut').addEventListener('input', function (event) {
                                                        event.target.value = formatearRut(event.target.value).toUpperCase();
                                                    });

                                                    document.getElementById('rut').addEventListener('paste', function (event) {
                                                        setTimeout(function () {
                                                            event.target.value = formatearRut(event.target.value).toUpperCase();
                                                        }, 0);
                                                    });
                                                </script>
                                                <button type="button" class="btn btn-primary" onclick="buscarPorRut()"><i class="fas fa-search"></i>&nbsp;&nbsp;Buscar Funcionario</button>
                                            </div>
                                        </div>
                                    </div>


                                    <p id="cantidadFilas">Cantidad de Funcionarios Ingresados: 0</p>

                                    <div class="table-responsive">
                                        <table class="table table-hover" id="miTabla" border="1">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>RUT</th>
                                                <th>Calidad Juridica</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tablaBody">

                                            <!-- Filas de la tabla se agregarán aquí -->
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>RUT</th>
                                                <th>Calidad Juridica</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>


                                    </div>

                                    <div id="inputHidden" name="inputHidden">

                                    </div>
                                    <input type="hidden" name="categoriaFormulario" id="categoriaFormulario" value="<?php echo $seleccionBloqueos; ?>">
                                    <button type="submit" class="btn btn-block btn-primary" disabled="disabled" id="generarBloqueosFunc"><i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>

                                </form>

                                <script>
                                    function buscarPorRut() {
                                        var rut = document.getElementById("rut").value;

                                        var rut2 = '<?php echo quitarCaracteresRUT('++') ?>';


                                        // Verificar si el RUT ya está en la tabla
                                        var tabla = document.getElementById("miTabla").getElementsByTagName('tbody')[0];
                                        for (var i = 0; i < tabla.rows.length; i++) {
                                            var celdaRut = tabla.rows[i].cells[1];
                                            if (celdaRut.innerHTML === rut) {
                                                Swal.fire(
                                                    'RUT ya se encuentra ingresado.',
                                                    '',
                                                    'error'
                                                )
                                                return;
                                            }
                                        }
                                        // Enviar datos a PHP por AJAX
                                        var xhr = new XMLHttpRequest();
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState == 4 && xhr.status == 200) {
                                                // Manejar la respuesta del servidor
                                                var respuesta = JSON.parse(xhr.responseText);
                                                if (respuesta.success) {
                                                    // Agregar fila a la tabla dinámicamente
                                                    var tabla = document.getElementById("miTabla").getElementsByTagName('tbody')[0];
                                                    var nuevaFila = tabla.insertRow(tabla.rows.length);

                                                    // Insertar celdas con los datos y el botón eliminar
                                                    var celdaNombre = nuevaFila.insertCell(0);
                                                    celdaNombre.innerHTML = respuesta.nombre;

                                                    var rutSinCarac = nuevaFila.insertCell(1);
                                                    rutSinCarac.innerHTML = respuesta.rut;


                                                    var celdaDepto = nuevaFila.insertCell(2);
                                                    celdaDepto.innerHTML = respuesta.calidad.trim() + '<input type="hidden" name="idFuncionarios[]" value="'+respuesta.id+'"><input type="hidden" name="rutListFuncionarios[]" value="'+respuesta.rut+'">';

                                                    var celdaEliminar = nuevaFila.insertCell(3);
                                                    celdaEliminar.innerHTML = '<button type="button" class="btn btn-block btn-danger" onclick="eliminarFila(this)">Eliminar de Listado</button>';


                                                    // Actualizar la cantidad de filas
                                                    actualizarCantidadFilas();
                                                } else {
                                                    Swal.fire(
                                                        'No se encontraron resultados para el RUT proporcionado.',
                                                        '',
                                                        'error'
                                                    )
                                                }
                                            }
                                        };

                                        actualizarCantidadFilas();
                                        // Configurar y enviar la solicitud
                                        xhr.open("POST", "formularios_buscarFuncionario.php", true);
                                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        xhr.send("rut=" + encodeURIComponent(rut));
                                    }

                                    function eliminarFila(botonEliminar) {
                                        var fila = botonEliminar.parentNode.parentNode;
                                        fila.parentNode.removeChild(fila);

                                        // Actualizar la cantidad de filas después de eliminar
                                        actualizarCantidadFilas();
                                    }

                                    function actualizarCantidadFilas() {
                                        var cantidadFilas = document.getElementById("miTabla").getElementsByTagName('tbody')[0].rows.length;
                                        document.getElementById("cantidadFilas").innerHTML = "Cantidad de Funcionarios Ingresados: " + cantidadFilas;
                                        document.getElementById("inputHidden").innerHTML = "<input type='hidden' id='cantidadFilas' name='cantidadFilas' value='"+cantidadFilas+"'/>";

                                        var botonGenerar = document.getElementById("generarBloqueosFunc");
                                        botonGenerar.disabled = cantidadFilas === 0;
                                    }

                                </script>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="js/dark-mode.js"></script>
<!-- jQuery -->
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
<!-- Page specific script -->
<script>
    $(function () {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const datosOriginales = [];
        // Carga los datos originales en la variable datosOriginales desde el servidor o donde sea que los obtengas.

        const buscador = document.getElementById("buscador");
        const tablaResultados = document.getElementById("tabla_resultados");
        const buscarButton = document.getElementById("buscar");
        const tbody = tablaResultados.querySelector("tbody");

        buscarButton.addEventListener("click", function () {
            const busqueda = buscador.value;
            const resultadosFiltrados = filtrarResultados(datosOriginales, busqueda);
            actualizarTabla(resultadosFiltrados);
        });

        function filtrarResultados(datos, busqueda) {
            // Implementa la lógica para filtrar los datos según la búsqueda
            // y devuelve un array con los resultados filtrados.
        }

        function actualizarTabla(resultados) {
            // Limpia el contenido actual de la tabla
            tbody.innerHTML = "";

            // Agrega los nuevos resultados
            resultados.forEach(function (fila) {
                const tr = document.createElement("tr");
                tr.innerHTML = `<td>${fila.CARD_NUMBER}</td><td>${fila.OWNER_Name}</td>`;
                tbody.appendChild(tr);
            });
        }

        // Inicialmente, muestra los datos originales
        actualizarTabla(datosOriginales);
    });
</script>
</body>

</html>