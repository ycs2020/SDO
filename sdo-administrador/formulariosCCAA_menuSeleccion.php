<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$nomUsuario = $_SESSION['nomUsuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; - Selecci&oacute;n de Formularios</title>

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
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Men&uacute; - Ingreso de Formularios</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                        <br>
                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="200px">
                                        <div class="card-body text-center">
                                            <h6 style="font-weight: bold;">FORMULARIOS DE ACCESO - ANTIGUO</h6>

                                            <h6>Seleccione la Unidad donde se desea acceder.</h6>

                                            <hr>
                                            <form method="post" action="formulariosCCAA_ingresoAntiguo.php">

                                                <!--                                                <h4>-->
                                                <!--                                                    --><?php
                                                //                                                    // Ejemplo de uso
                                                //                                                    $rutConFormato = "19.047.643-K";
                                                //                                                    $rutSinFormato = "19047643k";
                                                //
                                                //                                                    $rutFormateado1 = verificarYFormatearRUT($rutConFormato);
                                                //                                                    $rutFormateado2 = verificarYFormatearRUT($rutSinFormato);
                                                //
                                                //                                                    if ($rutFormateado1) {
                                                //                                                        echo "RUT válido: $rutFormateado1";
                                                //                                                    } else {
                                                //                                                        echo "RUT no válido";
                                                //                                                    }
                                                //
                                                //                                                    echo '<br><br>';
                                                //
                                                //                                                    if ($rutFormateado2) {
                                                //                                                        echo "RUT válido: $rutFormateado2";
                                                //                                                    } else {
                                                //                                                        echo "RUT no válido";
                                                //                                                    }
                                                //                                                    ?>
                                                <!--                                                </h4>-->

                                                <div style="border: 1px solid black;">

                                                    <select class="form-control" id="seleccionAccesoAntiguo" name="seleccionAccesoAntiguo">
                                                        <option value="Seleccione" required>Seleccione...</option>
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
                                                <br><br>
                                                <button type="submit" id="generarFormAntiguo" class="btn btn-block btn-primary">Ingresar</button>
                                            </form>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        // Obtenemos el elemento select y el botón
                                        var select2 = document.getElementById("seleccionAccesoAntiguo");
                                        var boton2 = document.getElementById("generarFormAntiguo");

                                        // Agregamos un evento de cambio al select
                                        select2.addEventListener("change", function () {
                                            // Verificamos si la opción "Seleccione" está seleccionada
                                            if (select2.value === "Seleccione") {
                                                // Si está seleccionada, deshabilitamos el botón
                                                boton2.disabled = true;
                                            } else {
                                                // Si no está seleccionada, habilitamos el botón
                                                boton2.disabled = false;
                                            }
                                        });

                                        // Asegúrate de verificar el estado inicial cuando se carga la página
                                        if (select2.value === "Seleccione") {
                                            boton2.disabled = true;
                                        }
                                    </script>
                                    <div class="card col-12 col-xl-4 col-lg-6 col-md-6" style="width: 18rem; text-align: center">
                                        <br>
                                        <img src="../dist/img/HBQP.jpg" class="card-img-top img-fluid" alt="..." height="500px">
                                        <div class="card-body text-center">
                                            <h6 style="font-weight: bold;">FORMULARIOS DE ACCESO - NUEVO</h6>
                                            <h6>Seleccione la Unidad donde se desea acceder.</h6>
                                            <hr>
                                            <button class="button btn bg-info" id="agregarFila">Añadir Otra Unidad</button>
                                            <br>
                                            <br>
                                            <form method="post" action="formulariosCCAA_ingresoNuevo_2.php">
                                                <div style="border: 1px solid black;">
                                                    <select class="form-control" id="seleccionAccesoNuevo1" name="seleccionAccesoNuevo1">
                                                        <option value="Seleccione">Seleccione...</option>
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
                                                <br>
                                                <div id="contenedorTablas">

                                                </div>
                                                <br><br>
                                                <button type="submit" id="generarFormNuevo" class="btn btn-block btn-primary">Ingresar</button>
                                                <input type="hidden" id="contarunidades" name="contarunidades" value="1">
                                            </form>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        // Obtenemos el elemento select y el botón
                                        var select = document.getElementById("seleccionAccesoNuevo");
                                        var boton = document.getElementById("generarFormNuevo");

                                        // Agregamos un evento de cambio al select
                                        select.addEventListener("change", function () {
                                            // Verificamos si la opción "Seleccione" está seleccionada
                                            if (select.value === "Seleccione") {
                                                // Si está seleccionada, deshabilitamos el botón
                                                boton.disabled = true;
                                            } else {
                                                // Si no está seleccionada, habilitamos el botón
                                                boton.disabled = false;
                                            }
                                        });

                                        // Asegúrate de verificar el estado inicial cuando se carga la página
                                        if (select.value === "Seleccione") {
                                            boton.disabled = true;
                                        }
                                    </script>
                                </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAgregarFila = document.getElementById('agregarFila');
        const contarUnidadesInput = document.getElementById('contarunidades');
        const divToShow = document.getElementById('divMostrar');

        let correlativo = 2;
        let contador = 1; // Inicializar el contador
        btnAgregarFila.addEventListener('click', () => {
            contador++;
            const div = document.createElement('div');
            div.className = 'form-group'; // Cambiado de 'input-group mb-3' a 'form-group'

            // Crear elemento select
            const select = document.createElement('select');
            select.className = 'form-control'; // Cambiado de 'form-control'
            select.id = `seleccionAccesoNuevo${correlativo}`; // Asignar un ID único
            select.name = `seleccionAccesoNuevo${correlativo}`; // Asignar un nombre único
            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Cargando...';
            optionDefault.disabled = true;
            optionDefault.selected = true;
            select.appendChild(optionDefault);

            // Crear objeto XMLHttpRequest
            const xhr = new XMLHttpRequest();

            // Configurar la solicitud
            xhr.open('GET', '../sdo-flujo/datos-unidades.php', true);

            // Definir la función de retrollamada onload
            xhr.onload = function() {
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
                    datos.forEach(function(dato) {
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

            // Definir la función de retrollamada onerror
            xhr.onerror = function() {
                // Error de conexión
                select.textContent = 'Error de conexión';
            };

            // Enviar la solicitud
            xhr.send();

            // Agregar select al div contenedor
            div.appendChild(select);

            // Agregar div al contenedor de tablas
            contenedorTablas.appendChild(div);

            // Incrementar el correlativo
            correlativo++;
            contarUnidadesInput.value = contador;

            // Mostrar el div
            divToShow.style.display = 'block';
        });

    });
</script>
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
</body>

</html>