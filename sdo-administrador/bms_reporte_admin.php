<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$fechaTime = strtotime($fecha);

$fechaPalabra = fechaEspañol2($fechaTime);

$rutaDireccion = "";

$rolesMap = [
    "personal"         => "sdo-personal",
    "administrativo"   => "sdo-admtec",
    "bodega pañol"     => "sdo-bodega-panol",
    "jefe tecnico"     => "sdo-jefestecnicos",
    "administrador"    => "sdo-administrador",
    "directiva"        => "sdo-directiva",
    "oficina tecnica"  => "sdo-oficinaTecnica"
];

if (isset($rolesMap[$rolUsuario2])) {
    $tipoUsuario   = $rolUsuario2;
    $rutaDireccion = $rolesMap[$rolUsuario2];
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Reportes de Alarma</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <style>
        .input-output-heading {
            font-size: 18px;
            color: #333; /* Cambiar color según tu preferencia */
            margin-bottom: 10px;
        }
    </style>
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
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="index.php" class="text-right">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">


                            <div class="card-body" style="text-align:center;">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-xl-3 col-lg-4 col-12">

                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/bms_alarmas_subir_reporte.php" method="post" enctype="multipart/form-data">

                                            <button type="button" data-toggle="modal" data-target="#modalSubirExcel" id="subirExcel" class="btn bg-gradient-success"><i class="fa fa-upload"></i>&nbsp;&nbsp;Agregar Reporte de Alarmas</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSubirExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title" id="exampleModalLabel">Subir Reporte de Alarmas</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12 ml-auto">
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="nombreReporte" class="w-auto">Nombre de Reporte de Alarmas: <code>*</code></label>
                                                                        <input type="text" class="form-control" required name="nombreReporte" id="nombreReporte" value="Reporte de Alarmas BMS <?php echo $fechaPalabra; ?>">
                                                                    </div>
                                                                    <br>
                                                                    <div style="text-align:left;" class="form-group">
                                                                        <label for="archivo_csv2" class="w-auto">Seleccione Archivo: <code>*.csv</code></label>

                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="archivo_csv" name="archivo_csv" accept=".csv">
                                                                            <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                                        </div>
                                                                        <script>
                                                                            document.getElementById('archivo_csv').addEventListener('change', function () {
                                                                                var fileName = this.value.split('\\').pop(); // Obtener solo el nombre del archivo
                                                                                var label = document.querySelector('.custom-file-label');
                                                                                label.textContent = fileName;
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="controlRegistro" id="controlRegistro" value="<?php echo $nomUsuario; ?>">
                                                            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp;Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                            <ul class="nav nav-pills mr-auto ml-auto p-2">
                                <li class="nav-item"><a class="nav-link text-white active" href="#tabRegistros" data-toggle="tab">Registros de Alarmas</a></li>
                                <li class="nav-item"><a class="nav-link text-white" href="#tabCategorias" data-toggle="tab">Categorias</a></li>
                            </ul>
                        </div>

                        <div class="card">

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabRegistros">
                                        <div class="table-responsive">

                                            <table id="tablaRegistroAlarmas" style="font-size: 11pt;" class="table table-bordered text-center table-sm">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Creador Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Cantidad Alarmas</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Visualizar</center>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle;">
                                                        <center>N°</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Fecha Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Nombre Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Creador Registro</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Cantidad Alarmas</center>
                                                    </th>
                                                    <th style="vertical-align:middle;">
                                                        <center>Visualizar</center>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <script>
                                                $(document).ready(function () {
                                                    var tablaRegistroAlarmas = $('#tablaRegistroAlarmas').DataTable({
                                                        "lengthChange": false,
                                                        "searching": true,
                                                        "autoWidth": false,
                                                        "responsive": true,
                                                        "processing":true,
                                                        "ordering": false,
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
                                                            "url": "/sdo-includes/bms_alarmas_cargar_registros.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {

                                                            },
                                                            "dataSrc": ""
                                                        },
                                                        "columns": [
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row, meta) {
                                                                    return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                                }
                                                            },
                                                            {"data": "fechaRegistro2"},
                                                            {"data": "nomRegistro"},
                                                            {"data": "controlRegistro"},
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    if (row.idRegistro < 1) {
                                                                        return '<center><span style="color: gray; font-weight: bold;">Deshabilitado</span></center>';
                                                                    } else {
                                                                        return '' +
                                                                            '<center>' +
                                                                            '<button type="button" ' +
                                                                            'class="btn btn-info btn-cant-alarmas" ' +
                                                                            'data-idreg="' + row.idRegistro + '" ' +
                                                                            'data-toggle="modal" data-target="#modalCantAlarmas">' +
                                                                            '<i class="fas fa-eye"></i>&nbsp;&nbsp;Cantidad' +
                                                                            '</button>' +
                                                                            '</center>';
                                                                    }
                                                                }
                                                            },
                                                            {
                                                                "data": null,
                                                                "render": function (data, type, row) {
                                                                    if (row.idRegistro < 1) {
                                                                        return '<center><span style="color: gray; font-weight: bold;">Deshabilitado</span></center>';
                                                                    }

                                                                    return '' +
                                                                        '<form style="margin: 0; text-align: center;" action="bms_reporte_detalle.php" method="post">' +
                                                                        '<input type="hidden" name="idRegistro" value="' + row.idRegistro + '">' +
                                                                        '<center>' +
                                                                        '<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar</button>' +
                                                                        '</center>' +
                                                                        '</form>';
                                                                }
                                                            }



                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td', row).css('text-transform', 'uppercase'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                                        },
                                                    });

                                                    // Delegado por si los botones se renderizan dinámicamente (DataTables)
                                                    $(document).on('click', '.btn-cant-alarmas', function () {
                                                        var idReg = $(this).data('idreg');

                                                        // loader básico mientras llega el HTML
                                                        var loader = '' +
                                                            '<div class="text-center py-4">' +
                                                            '<div class="spinner-border" role="status" aria-hidden="true"></div>' +
                                                            '<div class="mt-2">Cargando...</div>' +
                                                            '</div>';

                                                        $('#modalCantAlarmas .modal-body').html(loader);

                                                        // Llamada AJAX para traer HTML del servidor
                                                        $.ajax({
                                                            url: '/sdo-includes/bms_modal_cant_alarmas.php', // cambia por tu endpoint
                                                            type: 'POST',
                                                            dataType: 'html',
                                                            data: { idRegistro: idReg },
                                                            success: function (html) {
                                                                $('#modalCantAlarmas .modal-body').html(html);
                                                            },
                                                            error: function (xhr, status, err) {
                                                                var msg = '<div class="alert alert-danger mb-0">No se pudo cargar la información. Intente nuevamente.</div>';
                                                                $('#modalCantAlarmas .modal-body').html(msg);
                                                                console.error('AJAX error:', status, err, xhr.responseText);
                                                            }
                                                        });
                                                    });

// Opcional: limpiar al cerrar
                                                    $('#modalCantAlarmas').on('hidden.bs.modal', function () {
                                                        $(this).find('.modal-body').empty();
                                                    });
                                                });
                                            </script>
                                            <div class="modal fade" id="modalCantAlarmas" tabindex="-1" role="dialog" aria-labelledby="modalCantAlarmasLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document"><!-- cambia tamaño si quieres -->
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title mb-0" id="modalCantAlarmasLabel">Cantidad de alarmas</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body"><!-- se llena por AJAX --></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tabCategorias">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h5>Categorias de Alarmas según Departamento</h5>
                                                <br>
                                                <div class="table-responsive">

                                                    <table id="tablaCategoriasAlarma" class="table table-bordered text-center table-sm">
                                                        <thead class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>N°</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Clase</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Departamento</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Alarmas<br>Asociadas</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Modificar<br>Departamento</center>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot class="bg-secondary">
                                                        <tr>
                                                            <th style="vertical-align:middle;">
                                                                <center>N°</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Clase</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Departamento</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Alarmas<br>Asociadas</center>
                                                            </th>
                                                            <th style="vertical-align:middle;">
                                                                <center>Modificar<br>Departamento</center>
                                                            </th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>

                                                    <div class="modal fade" id="modalCambiarDepto" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-md text-justify" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title" id="modalEditarLabel">Modificar Departamento</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Formulario de Edición -->
                                                                    <input type="hidden" id="idCategoriaMod" name="idCategoriaMod" value="">

                                                                    <div class="form-group col-12">
                                                                        <label for="deptoSeleccionado" style="font-size: 12px;">Categoria: <code>*</code></label>
                                                                        <select class="form-control" required name="deptoSeleccion" id="deptoSeleccionado">
                                                                            <option value="Seleccione">Seleccione...</option>
                                                                            <?php

                                                                            $query = "SELECT * FROM departamentos WHERE idDepartamento != 9 AND idDepartamento != 11 AND idDepartamento != 1 ORDER BY nomDepartamento ASC";

                                                                            $result = mysqli_query($conexion, $query);

                                                                            // Verifica si hay resultados
                                                                            if ($result->num_rows > 0) {

                                                                                while ($row = $result->fetch_assoc()) {

                                                                                    ?>
                                                                                    <option value="<?php echo $row['idDepartamento'] ?>"><?php echo $row['nomDepartamento'] ?></option>
                                                                                    <?php

                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success" id="guardarCambiarDepto">Guardar</button>

                                                                    <!-- Cancelar Button -->
                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>

                                                                    <!-- Save or Submit Button -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#tablaCategoriasAlarma').DataTable({
                                                                "lengthChange": false,
                                                                "searching": true,
                                                                "autoWidth": false,
                                                                "processing": true,
                                                                "responsive": true,
                                                                "ordering": false,
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
                                                                    "url": "/sdo-includes/bms_alarmas_cargar_categorias.php", // Archivo PHP que contiene la consulta SQL
                                                                    "type": "POST",
                                                                    "data": function (d) {

                                                                    },
                                                                    "dataSrc": ""
                                                                },
                                                                "columns": [
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row, meta) {
                                                                            return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                                                                        }
                                                                    },
                                                                    {"data": "nomCategoria"},
                                                                    {"data": "nomDepartamento"},
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            var buttonHTML;

                                                                            if (row.cantidadCategoria > 0) {
                                                                                if (row.cantidadCategoria = 1) {
                                                                                    buttonHTML = row.cantidadCategoria + ' Alarma Asociada';
                                                                                } else {
                                                                                    buttonHTML = row.cantidadCategoria + ' Alarmas Asociadas';
                                                                                }
                                                                            } else {
                                                                                buttonHTML = '0 Alarmas Asociadas';
                                                                            }

                                                                            return buttonHTML;
                                                                        }
                                                                    },
                                                                    {
                                                                        "data": null,
                                                                        "render": function (data, type, row) {
                                                                            var buttonHTML;

                                                                            if (row.idCategoria > 0) {
                                                                                buttonHTML = '<button class="btn btn-warning btn-block btn-cambiardepto" data-id="' + row.idCategoria + '" data-depto="' + row.idDepartamento + '" ><i class="fas fa-pen"></i></button>';
                                                                            } else {
                                                                                buttonHTML = '<button class="btn btn-secondary btn-block" disabled>Deshabilitado</button>';
                                                                            }

                                                                            return buttonHTML;
                                                                        }
                                                                    },


                                                                    // Agrega más columnas según sea necesario
                                                                ],
                                                                "createdRow": function (row, data, dataIndex) {
                                                                    $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                                    $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
                                                                },
                                                            });
                                                        });


                                                        $(document).on('click', '.btn-cambiardepto', function () {
                                                            // Obtén los datos de los atributos data-
                                                            var idCategoria = $(this).data('id');
                                                            var idDepartamento = $(this).data('depto');

                                                            $('#idCategoriaMod').val(idCategoria); // Establece el id del protocolo en el formulario

                                                            // Itera sobre las opciones del <select>
                                                            $('#deptoSeleccionado option').each(function () {
                                                                var optionValue = $(this).val();

                                                                // Activa la opción correspondiente y desactiva las demás
                                                                if (optionValue == idDepartamento) {
                                                                    $(this).prop('selected', true);
                                                                } else {
                                                                    $(this).prop('selected', false);
                                                                }
                                                            });

                                                            // Abre el modal de edición
                                                            $('#modalCambiarDepto').modal('show');
                                                        });

                                                        $('#guardarCambiarDepto').on('click', function () {

                                                            const deptoSeleccionadoVal = $('#deptoSeleccionado').val();
                                                            const idCategoriaModVal = $('#idCategoriaMod').val();

                                                            if (!deptoSeleccionadoVal || deptoSeleccionadoVal == 0) {

                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: 'Error',
                                                                    text: 'Seleccione una opcion',
                                                                    confirmButtonText: 'Aceptar'
                                                                });
                                                            } else {
                                                                // Aquí puedes enviar el valor seleccionado mediante AJAX o realizar cualquier otra acción
                                                                $.ajax({
                                                                    url: '/sdo-funciones/bms_alarmas_cambiar_depto.php',
                                                                    method: 'POST',
                                                                    data: {
                                                                        deptoSeleccion: deptoSeleccionadoVal,
                                                                        idCategoria: idCategoriaModVal
                                                                    },
                                                                    success: function (response) {
                                                                        console.log(response.status);
                                                                        if (response.status === 'success') {
                                                                            Swal.fire({
                                                                                icon: 'success',
                                                                                title: 'Éxito',
                                                                                text: response.message,
                                                                                confirmButtonText: 'Aceptar'
                                                                            }).then(() => {
                                                                                $('#modalCambiarDepto').modal('hide');
                                                                                $('#tablaCategoriasAlarma').DataTable().ajax.reload();
                                                                            });
                                                                        } else {
                                                                            Swal.fire({
                                                                                icon: 'error',
                                                                                title: 'Error2',
                                                                                text: response.message,
                                                                                confirmButtonText: 'Aceptar'
                                                                            });
                                                                        }
                                                                    },
                                                                    error: function (xhr, status, error) {
                                                                        Swal.fire({
                                                                            icon: 'error',
                                                                            title: 'Error',
                                                                            text: 'Error al procesar la solicitud. Intente nuevamente.',
                                                                            confirmButtonText: 'Aceptar'
                                                                        });
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    </script>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
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

</body>

</html>