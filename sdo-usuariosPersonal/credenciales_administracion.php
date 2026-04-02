<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';
include '../vendor/quickchart/QuickChart.php';

date_default_timezone_set('America/Santiago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$anio = date('Y');


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }
    </style>
    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-generar").click(function () {
                $("#rowGenerar").show();
                $("#rowListar").hide();
                $("#listarCredencial").show();
                $("#listarCredencial2").show();
                $("#generarCredencial").hide();
                $("#generarCredencial2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
                $("#rowGenerar").hide();
                $("#rowListar").show();
                $("#listarCredencial").hide();
                $("#listarCredencial2").hide();
                $("#generarCredencial").show();
                $("#generarCredencial2").show();
            });
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/unidadPersonal-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
            if(isset($_GET['exito'])){
                ?>
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "Todos los datos ingresados correctamente!",
                        icon: "success"
                    });
                </script>
                <?php
            }

            if(isset($_GET['error']) || isset($_GET['error1']) || isset($_GET['error2'])){
                ?>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Ha ocurrido un error. Por favor reintente",
                    });
                </script>
                <?php
            }
        ?>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="botonesMenu">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">

                                    <div id="generarCredencial2" name="generarCredencial2" class="col-lg-3 col-md-6 col-12 text-center">
                                        <!-- small box -->
                                        <button id="generarCredencial" style="color: white" name="generarCredencial" class="btn btn-block bg-gradient-primary btn-md show-generar text-black text-center"><i class="nav-icon fas fa-id-card"></i><br>Agregar Credencial</button>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarCredencial2" style="display:none;" name="listarCredencial2">
                                        <!-- small box -->
                                        <button id="listarCredencial" style="display:none; color: white" name="listarCredencial" class="btn btn-block bg-gradient-success btn-md text-black text-center show-listar"><i class="nav-icon fas fa-book"></i><br>Listar Credenciales</button>

                                    </div>


                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowListar">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-gradient-success">
                                <h5 style="font-weight: bold;">1.- Listado de Funcionarios</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table id="tablaListadoCredencial" class="table table-bordered table-hover">

                                        <thead class="bg-gradient-success">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center;">
                                                N°
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Nombre
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                RUT
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Tipo
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Unidad
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Codigo
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Fecha Creaci&oacute;n
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Detalle
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Modificar
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Dar de Baja
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot class="bg-gradient-success">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center;">
                                                N°
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Nombre
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                RUT
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Tipo
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Unidad
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Codigo
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Fecha Creaci&oacute;n
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Detalle
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Modificar
                                            </th>
                                            <th style="vertical-align: middle; text-align: center;">
                                                Dar de Baja
                                            </th>
                                        </tr>
                                        </tfoot>

                                    </table>

                                    <!-- Modal Mostrar -->
                                    <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-success">
                                                    <h5 class="modal-title" id="detalleModalLabel">Detalles de Credencial</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>RUT:</strong> <span id="rutCredencial"></span></p>
                                                    <p><strong>Nombre:</strong> <span id="nomCredencial"></span></p>
                                                    <p><strong>Cargo:</strong> <span id="cargoCredencial"></span></p>
                                                    <p><strong>Tipo:</strong> <span id="tipoCredencial"></span></p>
                                                    <p><strong>Unidad:</strong> <span id="unidadCredencial"></span></p>
                                                    <p><strong>Código:</strong> <span id="codigoCredencial"></span></p>
                                                    <p><strong>Fecha Creación:</strong> <span id="fechaCreacionCredencial"></span></p>
                                                    <p><strong>Fecha Entrega:</strong> <span id="fechaEntregaCredencial"></span></p>
                                                    <p><strong>Fecha Baja:</strong> <span id="fechaBajaCredencial"></span></p>
                                                    <p><strong>Creador:</strong> <span id="nomCreadorCredencial"></span></p>
                                                    <p><strong>Persona a la que se entrega:</strong> <span id="nomEntregaCredencial"></span></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin Modal Mostrar -->

                                    <!-- MODIFICAR DATOS -->

                                    <!-- Modal -->
                                    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-warning">
                                                    <h5 class="modal-title" id="editarModalLabel">Detalles de Credencial</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editarCredencialForm">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="rutModCredencial">RUT</label>
                                                                <input type="text" class="form-control" id="rutModCredencial" name="rutModCredencial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="nomModCredencial">Nombre</label>
                                                                <input type="text" class="form-control" id="nomModCredencial" name="nomModCredencial">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="cargoModCredencial">Cargo</label>
                                                                <input type="text" class="form-control" id="cargoModCredencial" name="cargoModCredencial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="tipoModCredencial">Tipo</label>
                                                                <select class="form-control" id="tipoModCredencial" name="tipoModCredencial">
                                                                    <option value="Personalizada">Personalizada</option>
                                                                    <option value="Generica">Generica</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="unidadModCredencial">Unidad</label>
                                                                <input type="text" class="form-control" id="unidadModCredencial" name="unidadModCredencial">

                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="codigoModCredencial">Código</label>
                                                                <input type="text" class="form-control" id="codigoModCredencial" name="codigoModCredencial">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="fechaCreacionModCredencial">Fecha Creación</label>
                                                                <input type="datetime-local" class="form-control" id="fechaCreacionModCredencial" name="fechaCreacionModCredencial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="fechaEntregaModCredencial">Fecha Entrega</label>
                                                                <input type="datetime-local" class="form-control" id="fechaEntregaModCredencial" name="fechaEntregaModCredencial">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="fechaBajaModCredencial">Fecha Baja</label>
                                                                <input type="datetime-local" class="form-control" id="fechaBajaModCredencial" name="fechaBajaModCredencial">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="nomCreadorModCredencial">Creador</label>
                                                                <input type="text" class="form-control" id="nomCreadorModCredencial" name="nomCreadorModCredencial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="nomEntregaModCredencial">Persona a la que se Entrego</label>
                                                                <input type="text" class="form-control" id="nomEntregaModCredencial" name="nomEntregaModCredencial">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success" id="modificarData"><i class="fas fa-check"></i>&nbsp;&nbsp;Guardar Cambios</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FIN MODIFICAR DATOS -->


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowGenerar" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <form action="../sdo-funciones/guardar_credenciales.php" method="post">
                                <div class="card-body">
                                    <h5 style="font-weight: bold;">2.- Agregar Credenciales</h5>
                                    <button style="float: right" id="agregarFila" type="button" class="btn btn-info"><i class="fas fa-plus"></i> Ingresar Otro Funcionario</button>
                                    <br>
                                    <br>
                                    <table id="miTabla" style="font-size: 15px;" class="table table-bordered">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th class="text-center">Nombre del Funcionario</th>
                                                <th class="text-center">Rut del Funcionario</th>
                                                <th class="text-center">Cargo del Funcionario</th>
                                                <th class="text-center">Tipo de Tarjeta</th>
                                                <th class="text-center">Unidad del Funcionario</th>
                                                <th class="text-center">Codigo de la Tarjeta</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="miTbody">
                                            <tr>
                                                <td><input type="text" required style="text-transform:uppercase" class="form-control" id="nomFuncionario1" name="nomFuncionario1" placeholder="Nombre y Apellido de Usuario de Tarjeta"></td>
                                                <td><input type="text" required style="text-transform:uppercase" class="form-control" id="rutFuncionario1" name="rutFuncionario1" placeholder="Rut del Usuario de Tarjeta"></td>
                                                <td><input type="text" class="form-control" required name="cargoFuncionario1" id="cargoFuncionario1" placeholder="CARGO DEL FUNCIONARIO"></td>
                                                <td>
                                                    <select class="form-control" required name="tipoCredencial1" id="tipoCredencial1">
                                                        <option value="Seleccione">Seleccione...</option>
                                                        <option value="Generica">Generica</option>
                                                        <option value="Personalizada">Personalizada</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" required name="unidadFuncionario1" id="unidadFuncionario1" placeholder="UNIDAD DEL FUNCIONARIO"></td>
                                                <td><input type="text" class="form-control" required name="codigoTarjeta1" id="codigoTarjeta1" placeholder="XXXXXXXX"></td>
                                                <td style="font-size: 12px; white-space: nowrap; vertical-align:middle; text-align:center;">No se puede eliminar</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                            <tr>
                                                <th class="text-center">Nombre del Funcionario</th>
                                                <th class="text-center">Rut del Funcionario</th>
                                                <th class="text-center">Cargo del Funcionario</th>
                                                <th class="text-center">Tipo de Tarjeta</th>
                                                <th class="text-center">Unidad del Funcionario</th>
                                                <th class="text-center">Codigo de la Tarjeta</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <br>
                                    <input type="hidden" name="cantidadFilas" id="cantidadFilas" value="1"/>
                                    <button type="submit" style="width: 100%" class="btn btn-success">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
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

<script src="js/dark-mode.js"></script>

<script>
    const tabla = document.getElementById('miTabla');
    const btnAgregarFila = document.getElementById('agregarFila');
    let correlativo = 2;
    var tbody = document.getElementById('miTbody');
    btnAgregarFila.addEventListener('click', () => {
        contenido = '';
        const nuevaFila = document.createElement('tr');
        contenido += '<td><input type="text" required style="text-transform:uppercase" class="form-control" id="nomFuncionario' + correlativo + '" name="nomFuncionario' + correlativo + '" placeholder="Nombre y Apellido de Usuario de Tarjeta"></td>';
        contenido += '<td><input type="text" required style="text-transform:uppercase" class="form-control" id="rutFuncionario' + correlativo + '" name="rutFuncionario' + correlativo + '" placeholder="Rut del Usuario de Tarjeta"></td>';
        contenido += '<td><input type="text" class="form-control" required name="cargoFuncionario' + correlativo + '" id="cargoFuncionario' + correlativo + '" placeholder="CARGO DEL FUNCIONARIO"></td>'
        contenido += '<td><select class="form-control" required name="tipoCredencial' + correlativo + '" id="tipoCredencial' + correlativo + '"> <option value="Seleccione">Seleccione...</option><option value="Generica">Generica</option><option value="Personalizada">Personalizada</option></select></td>';
        contenido += '<td><input type="text" class="form-control" required name="unidadFuncionario' + correlativo + '" id="unidadFuncionario' + correlativo + '" placeholder="UNIDAD DEL FUNCIONARIO"></td>';
        contenido += '<td><input type="text" class="form-control" required name="codigoTarjeta' + correlativo + '" id="codigoTarjeta' + correlativo + '" placeholder="XXXXXXXX"></td>';
        contenido += '<td style="font-size: 12px; white-space: nowrap; vertical-align: middle; text-align: center;"><button onclick="eliminarFila(this)" class="btn btn-block btn-danger"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span>Eliminar</button></td>';
        nuevaFila.innerHTML = contenido;

        tabla.querySelector('tbody').appendChild(nuevaFila);
        correlativo++;

        var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

        document.getElementById('cantidadFilas').value = cantidadDeFilas2;
    });
    function eliminarFila(boton) {
        const fila = boton.closest('tr');
        fila.remove();
        // Actualiza los correlativos después de eliminar una fila
        correlativo--;
        // Obtén una referencia al elemento <tbody> por su id

        // Cuenta la cantidad de elementos <tr> dentro del <tbody>
        var cantidadDeFilas = tbody.getElementsByTagName('tr').length;

        var cantidadDeFilas2 = tbody.getElementsByTagName('tr').length;

        document.getElementById('cantidadFilas').value = cantidadDeFilas2;
    }
</script>

<script>
    $(document).ready(function () {
        var tablaListadoCredencial = $('#tablaListadoCredencial').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "credenciales_cargarDatos.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    // d.idDepartamento = $('#idDeptoHT').val(); // Pasa el valor seleccionado del select como parámetro

                },
                "dataSrc": function (json) {
                    console.log("Response from server:", json);
                    return json;
                },
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "nomCredencial"},
                {"data": "rutCredencial"},
                {"data": "tipoCredencial"},
                {"data": "unidadCredencial"},
                {"data": "codigoCredencial"},
                {"data": "fechaCreacionCredencial"},
                {
                    "data": null,
                    "defaultContent": '<button class="btn bg-gradient-primary btn-detalle"><i class="fas fa-eye"></i></button>'
                },
                {
                    "data": null,
                    "defaultContent": '<button class="btn bg-gradient-warning btn-editar"><i class="fas fa-pen"></i></button>'
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.fechaBajaCredencial !== 'No Aplica') {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-danger btn-darBaja" data-id="' + row.idCredencial + '"><i class="fas fa-arrow-down"></i></button>';
                        }
                    }
                }



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td', row).css('text-align', 'center'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });

        // Evento para manejar la apertura del modal con los detalles de la credencial
        $('#tablaListadoCredencial tbody').on('click', 'button.btn-detalle', function () {
            var data = tablaListadoCredencial.row($(this).parents('tr')).data();

            // Llenar los datos del modal con los valores de la fila seleccionada
            $('#rutCredencial').text(data.rutCredencial);
            $('#nomCredencial').text(data.nomCredencial);
            $('#cargoCredencial').text(data.cargoCredencial);
            $('#tipoCredencial').text(data.tipoCredencial);
            $('#unidadCredencial').text(data.unidadCredencial);
            $('#codigoCredencial').text(data.codigoCredencial);
            $('#fechaCreacionCredencial').text(data.fechaCreacionCredencial);
            $('#fechaEntregaCredencial').text(data.fechaEntregaCredencial);
            $('#fechaBajaCredencial').text(data.fechaBajaCredencial);
            $('#nomCreadorCredencial').text(data.nomCreadorCredencial);
            $('#nomEntregaCredencial').text(data.nomEntregaCredencial);

            // Mostrar el modal
            $('#detalleModal').modal('show');
        });


        // Evento para manejar la apertura del modal con los detalles de la credencial
        $('#tablaListadoCredencial tbody').on('click', 'button.btn-editar', function () {
            var data = tablaListadoCredencial.row($(this).parents('tr')).data();

            // Llenar los datos del formulario con los valores de la fila seleccionada
            $('#editarCredencialForm #rutModCredencial').val(formatRut(data.rutCredencial));
            $('#editarCredencialForm #nomModCredencial').val(data.nomCredencial);
            $('#editarCredencialForm #cargoModCredencial').val(data.cargoCredencial);
            $('#editarCredencialForm #tipoModCredencial').val(data.tipoCredencial);
            $('#editarCredencialForm #unidadModCredencial').val(data.unidadCredencial);
            $('#editarCredencialForm #codigoModCredencial').val(data.codigoCredencial);
            $('#editarCredencialForm #fechaCreacionModCredencial').val(data.fechaCreacionEditar);
            $('#editarCredencialForm #fechaEntregaModCredencial').val(data.fechaEntregaEditar);
            $('#editarCredencialForm #fechaBajaModCredencial').val(data.fechaBajaEditar);
            $('#editarCredencialForm #nomCreadorModCredencial').val(data.nomCreadorCredencial);
            $('#editarCredencialForm #nomEntregaModCredencial').val(data.nomEntregaCredencial);

            // Mostrar el modal
            $('#editarModal').modal('show');
        });

        // Evento para manejar el envío del formulario de edición
        $('#modificarData').on('click', function () {
            var formData = $('#editarCredencialForm').serialize();

            $.ajax({
                url: 'credenciales_actualizar.php', // Archivo PHP que procesa la actualización
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Manejar la respuesta del servidor
                    if (response.success) {
                        // Cerrar el modal y recargar la tabla
                        $('#detalleModal').modal('hide');
                        tablaListadoCredencial.ajax.reload();
                    } else {
                        // Mostrar mensaje de error
                        alert('Error al actualizar la credencial.');
                    }
                },
                error: function (xhr, status, error) {
                    // Manejar errores de la solicitud
                    alert('Error en la solicitud: ' + error);
                }
            });
        });

        $('#tablaListadoCredencial').on('click', '.btn-darBaja', function () {
            var id = $(this).data('id');

            // Mostrar swal.alert de confirmación
            Swal.fire({
                title: '¿Estás seguro de dar de baja esta Credencial? ',
                text: 'Esta acción no se puede revertir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear un formulario dinámico
                    var form = $('<form>', {
                        'action': '../sdo-funciones/credenciales_darBaja.php', // Reemplaza 'tu_url_destino' con la URL de destino
                        'method': 'POST'
                    });

                    // Agregar un campo oculto para el ID
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'idCredencial',
                        'value': id
                    }));

                    // Agregar el formulario al documento y enviarlo
                    form.appendTo('body').submit();


                    // Realizar acciones adicionales si el usuario confirma
                    // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
                    // confirmarAccion(id);
                }
            });
        });


        function formatRut(value) {
            // Eliminar cualquier cosa que no sea un dígito o una 'K' (si hay una 'k' minúscula, se convierte en mayúscula)
            value = value.replace(/[^0-9kK]/g, '').toUpperCase();

            // Insertar puntos y guion
            if (value.length > 1) {
                const body = value.slice(0, -1);
                const dv = value.slice(-1);

                let formattedRut = '';
                for (let i = body.length; i > 0; i -= 3) {
                    formattedRut = body.substring(Math.max(0, i - 3), i) + (formattedRut ? '.' + formattedRut : '');
                }
                return formattedRut + '-' + dv;
            }

            return value;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rutInput = document.getElementById('rutModCredencial');

        rutInput.addEventListener('input', function (event) {
            this.value = formatRut(this.value);
        });
    });


    function formatRut(value) {
        // Eliminar cualquier cosa que no sea un dígito o una 'K' (si hay una 'k' minúscula, se convierte en mayúscula)
        value = value.replace(/[^0-9kK]/g, '').toUpperCase();

        // Insertar puntos y guion
        if (value.length > 1) {
            const body = value.slice(0, -1);
            const dv = value.slice(-1);

            let formattedRut = '';
            for (let i = body.length; i > 0; i -= 3) {
                formattedRut = body.substring(Math.max(0, i - 3), i) + (formattedRut ? '.' + formattedRut : '');
            }
            return formattedRut + '-' + dv;
        }

        return value;
    }
</script>

</body>

</html>