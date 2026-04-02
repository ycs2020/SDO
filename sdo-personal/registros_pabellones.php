<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$fecha = date("d-m-Y H:i");
$folioTG = 'Enviado desde Otra parte';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Registros de T° de Pabellones</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        fieldset {
            border: 1px solid #ccc;
            padding: 10px;
        }

        legend {
            font-weight: bold;
        }

        /* Eliminar el espacio interno por defecto en los elementos li */

        /* Aplicar espacio interno a los elementos li según sea necesario */
        ul li, ol li {
            padding-bottom: 10px; /* Ajusta el valor según tu preferencia */
        }
    </style>
    <script>
        $(document).ready(function () {
            $(".show-listar").click(function () {
                $("#rowAvancesObs").show();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#divRegistrarAC").hide();
                $("#divListarAC").show();
                $("#rowParametrosPab").hide();
                $("#divParamPab").show();
                $("#listarParametros").show();
                $("#rowCheckList").hide();
            });
            $(".show-listar-detalle").click(function () {
                $("#rowAvancesObs").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#divRegistrarAC").show();
                $("#divListarAC").hide();
                $("#rowParametrosPab").hide();
                $("#divParamPab").show();
                $("#listarParametros").show();
                $("#rowCheckList").hide();
                $("#divchecklistucas").show();
                $("#listardetallesucas").hide();
            });
            $(".show-parametros").click(function () {
                $("#rowAvancesObs").hide();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#divRegistrarAC").hide();
                $("#divListarAC").show();
                $("#rowParametrosPab").show();
                $("#divParamPab").hide();
                $("#listarParametros").hide();
                $("#rowCheckList").hide();
                $("#rowCheckListUCAS").hide();
                $("#divchecklistucas").hide();
            });
            $(".mostrar-check-list").click(function () {
                $("#rowCheckList").show();
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklist").hide();
                $("#listardetalles").show();
            });
            $(".listar-check-list").click(function () {
                $("#rowCheckList").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklist").show();
                $("#listardetalles").hide();

            });
            $(".mostrar-check-list-logica").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistlogica").hide();
                $("#listardetalleslogica").show();
                $("#rowCheckListLogica").show();

            });
            $(".listar-check-list-logica").click(function () {
                $("#divchecklistlogica").show();
                $("#listardetalleslogica").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListLogica").hide();
            });
            $(".mostrar-check-list-ucas").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistucas").hide();
                $("#listardetallesucas").show();
                $("#rowCheckListUCAS").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-ucas").click(function () {
                $("#divchecklistucas").show();
                $("#listardetallesucas").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListUCAS").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-controladores").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistcontroladores").hide();
                $("#listardetallescontroladores").show();
                $("#rowCheckListControladores").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-controladores").click(function () {
                $("#divchecklistcontroladores").show();
                $("#listardetallescontroladores").hide();
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#rowCheckListControladores").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-tccuma").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklisttccuma").hide();
                $("#listardetallestccuma").show();
                $("#rowCheckListtccuma").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-tccuma").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklisttccuma").show();
                $("#listardetallestccuma").hide();
                $("#rowCheckListtccuma").hide();
                $("#rowParametrosPab").hide();
            });
            $(".mostrar-check-list-vav").click(function () {
                $("#rowInformacion").hide();
                $("#rowTareas").hide();
                $("#divchecklistvav").hide();
                $("#listardetallesvav").show();
                $("#rowCheckListvav").show();
                $("#rowParametrosPab").hide();
            });
            $(".listar-check-list-vav").click(function () {
                $("#rowInformacion").show();
                $("#rowTareas").show();
                $("#divchecklistvav").show();
                $("#listardetallesvav").hide();
                $("#rowCheckListvav").hide();
                $("#rowParametrosPab").hide();
            });

        });
    </script>
    <script>
        function handleRadioChange(radio) {
            var panolDiv = document.getElementById('panol');
            var solicompraDiv = document.getElementById('solicompra');

            if (radio.id === 'radioOpcion1') {
                panolDiv.style.display = 'block';
                solicompraDiv.style.display = 'none';
            } else if (radio.id === 'radioOpcion2') {
                panolDiv.style.display = 'none';
                solicompraDiv.style.display = 'block';
            }
        }
    </script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="index.php" id="hrefInicio" class="text-dark">
                                    <i class="fas fa-home"></i> Inicio
                                </a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><a href="index.php" style="float: right" class="btn btn-sm bg-danger">Volver Atras</a></h4>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <legend style="text-align: center">Registros de Parametros de Pabellones</legend>
                                <div class="row text-center" style="display: flex; justify-content:center;">


                                    <div class="col-lg-3 col-12" id="divAgregarParametros" name="divAgregarParametros">
                                        <!-- small box -->
                                        <button class="btn btn-block bg-gradient-success btn-md text-white" id="agregarParametros" name="agregarParametros" data-toggle="modal" data-target="#modalAgregar">
                                            <i class="fas fa-plus"></i>&nbsp;Agregar Nueva Medición
                                        </button>

                                        <!-- Modal -->
                                    </div>

                                </div>
                                <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <form class="" method="post" action="../sdo-funciones/pabellones_agregarUltMedicion_index.php">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalAgregarLabel">Agregar Nueva Medición</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                        <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Fecha de Registro: <code>*</code></label>
                                                        <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                    </div>
                                                    <?php
                                                    for ($k = 2; $k < 11; $k++) {
                                                        $valorTemp = 'addTempPabellon' . $k;
                                                        $valorHumedad = 'addHumedadPabellon' . $k;

                                                        $nomValorTemp = 'Temperatura Pabellon ' . $k;
                                                        $nomValorHumedad = 'Humedad Pabellon ' . $k;
                                                        ?>
                                                        <div class="form-row">
                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                <label for="<?php echo $valorTemp; ?>"><?php echo $nomValorTemp; ?>:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-temperature-high"></i></span>
                                                                    </div>
                                                                    <input type="number" min="0" step=".1" class="form-control" max="26" required id="<?php echo $valorTemp; ?>" name="<?php echo $valorTemp; ?>" placeholder="<?php echo $nomValorTemp; ?>">
                                                                </div>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                <label for="<?php echo $valorHumedad; ?>"><?php echo $nomValorHumedad; ?>:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                                                                    </div>
                                                                    <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorHumedad; ?>" name="<?php echo $valorHumedad; ?>" placeholder="<?php echo $nomValorHumedad; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="nomRegistroPab" id="nomRegistroPab" value="<?php echo mb_convert_case($nomUsuario, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                    <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo mb_convert_case($folioTG, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;Registrar Medicion</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario</center>
                                            </th>
                                            <?php

                                            for ($i = 2; $i <= 10; $i++) {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Pabellon <?php echo $i; ?></center>
                                                </th>
                                                <?php
                                            }

                                            ?>
                                            <th>
                                                Enviar
                                            </th>
                                            <th>
                                                Modificar
                                            </th>
                                            <th>
                                                Eliminar
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Funcionario</center>
                                            </th>
                                            <?php

                                            for ($i = 2; $i <= 10; $i++) {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Pabellon <?php echo $i; ?></center>
                                                </th>
                                                <?php
                                            }

                                            ?>

                                            <th>
                                                Enviar
                                            </th>
                                            <th>
                                                Modificar
                                            </th>
                                            <th>
                                                Eliminar
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                <script>
                                    $(document).ready(function () {
                                        var columns = [
                                            {"data": "fechaRegistroPab"},
                                            {"data": "nomRegistroPab"},

                                            // Otras columnas existentes

                                            // Agregar columnas dinámicamente
                                        ];

                                        for (var i = 2; i <= 10; i++) {
                                            columns.push({"data": "valorTotal" + i});
                                            // Agrega más columnas según sea necesario
                                        }
                                        columns.push(
                                            {
                                                "data": null,
                                                "render": function (data, type, row) {
                                                    var buttonHTML;

                                                    if (row.correoEnviado === 0) {
                                                        buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '"><i class="fas fa-envelope"></i></button>';
                                                    } else {
                                                        buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-envelope"></i></button>';
                                                    }

                                                    return buttonHTML;
                                                },
                                                "createdRow": function (row, data, dataIndex) {
                                                    $(row).on('click', '.btn-confirm', function () {
                                                        var button = $(this);
                                                        var icon = button.find('i');

                                                        // Cambiar el texto del botón a "Cargando"
                                                        button.text('Cargando...');

                                                        // Aquí puedes realizar cualquier acción adicional, como enviar una solicitud AJAX

                                                        // Simular una operación asíncrona (puedes reemplazar esto con tu lógica real)
                                                        setTimeout(function () {
                                                            // Restaurar el botón a su estado original (habilitado y con el texto original)
                                                            button.prop('disabled', false);
                                                            icon.removeClass('fa-spinner fa-spin').addClass('fa-envelope');
                                                            button.text('Enviar Correo');
                                                        }, 2000); // Cambia 2000 por la cantidad de tiempo que tomará tu operación real
                                                    });
                                                }
                                            },

                                            {
                                                "data": null,
                                                "render": function (data, type, row) {
                                                    var buttonHTML;

                                                    if (row.correoEnviado === 0) {
                                                        buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '"><i class="fas fa-pen"></i></button>';
                                                    } else {
                                                        buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-pen"></i></button>';
                                                    }

                                                    return buttonHTML;
                                                }
                                            },
                                            {
                                                "data": null,
                                                "render": function (data, type, row) {
                                                    return '<button class="btn btn-danger btn-eliminar" data-id="' + row.idRegistroPab + '"><i class="fas fa-trash"></i></button>';
                                                }
                                            }
                                        )

                                        var example2 = $('#example2').DataTable({
                                            "lengthChange": false,
                                            "searching": true,
                                            "autoWidth": false,
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
                                                "url": "ajax-pabCorreo-listado.php", // Archivo PHP que contiene la consulta SQL
                                                "type": "POST",
                                                "data": function (d) {

                                                },
                                                "dataSrc": ""
                                            },
                                            "columns": columns,
                                            "createdRow": function (row, data, dataIndex) {
                                                $('td', row).css({
                                                    'text-align': 'center', // Centrar texto horizontalmente
                                                    'vertical-align': 'middle', // Centrar texto verticalmente
                                                    'border': '1px solid #dddddd' // Añadir borde
                                                    // Añade más estilos según tus necesidades
                                                });
                                            }
                                        });
                                        // Manejar clic en el botón de confirmación
                                        $('#example2').on('click', '.btn-confirm', function () {
                                            var id = $(this).data('id');

                                            // Mostrar swal.alert de confirmación
                                            Swal.fire({
                                                title: '¿Estás seguro de enviar Parametros a Pabellon?',
                                                text: 'Esta acción no se puede revertir.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Sí, confirmar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Crear un formulario dinámico
                                                    // Mostrar el overlay de bloqueo de pantalla
                                                    $('.overlay').show();

                                                    var form = $('<form>', {
                                                        'action': '../sdo-funciones/pabellones_enviarCorreo_index.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                        'method': 'POST'
                                                    });

                                                    // Agregar un campo oculto para el ID
                                                    form.append($('<input>', {
                                                        'type': 'hidden',
                                                        'name': 'idRegPab',
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

                                        $('#example2').on('click', '.btn-editar', function () {
                                            var idRegistro = $(this).data('id');

                                            // Hacer una petición al servidor para obtener los datos del registro usando AJAX
                                            $.ajax({
                                                url: 'sdo-includes/parametros_pabellon_modificar_index.php',
                                                type: 'POST',
                                                data: {id: idRegistro},
                                                dataType: 'json',
                                                success: function (data) {
                                                    console.log('Respuesta del servidor:', data);

                                                    // Llenar el modal con los datos obtenidos
                                                    for (var key in data) {
                                                        var value = data[key];
                                                        $('#' + key).val(value);
                                                        console.log('Asignando valor a ' + key + ': ' + value);
                                                    }

                                                    $('#modalEditar').modal('show'); // Abre el modal
                                                },
                                                error: function (error) {
                                                    console.log(error);
                                                    // Manejar errores si es necesario
                                                }
                                            });

                                        });

                                        $('#example2').on('click', '.btn-eliminar', function () {
                                            var id = $(this).data('id');

                                            // Mostrar swal.alert de confirmación
                                            Swal.fire({
                                                title: '¿Estás seguro de eliminar estos Parametros?',
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
                                                        'action': '../sdo-funciones/pabellones_eliminarParametros_index.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                        'method': 'POST'
                                                    });

                                                    // Agregar un campo oculto para el ID
                                                    form.append($('<input>', {
                                                        'type': 'hidden',
                                                        'name': 'idRegPab',
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
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>s
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/dark-mode.js"></script>
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
</body>
</html>
