<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];
$nowAnio1 = date('Y');

date_default_timezone_set('America/Santiago');

$fechaSistema = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Protocolo de Refrigeradores</title>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <style>
        /* El botón activo tendrá fondo coloreado */
        .btn-group-toggle .btn.active {
            background-color: var(--bs-success);
            color: white;
            border-color: var(--bs-success);
        }

        /* El otro botón será transparente */
        .btn-group-toggle .btn {
            background-color: transparent;
            color: inherit;
            border-color: transparent;
        }

        /* Ajuste del botón no activo con hover */
        .btn-group-toggle .btn:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

    </style>

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
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>



        <?php


        if (isset($_GET['exitoGenerarProtocolo'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Operación Realizada !',
                    'Protocolo Creado con Exito.',
                    'success'
                )
            </script>
            <?php
        }


        if (isset($_GET['errorGenerarProtocolo'])) {
            ?>
            <script>
                Swal.fire(
                    '¡ Error !',
                    'Error al Generar Protocolo.',
                    'error'
                )
            </script>
            <?php
        }

        ?>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>PROTOCOLO DE REVISIÓN DE REFRIGERADORES</h5>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div style="font-size: 13px;" class="card-header bg-secondary d-flex p-0">
                            <ul class="nav nav-pills mr-auto ml-auto p-2">
                                <li class="nav-item"><a class="nav-link text-white active" href="#tabListado" data-toggle="tab">LISTADO DE PROTOCOLOS</a></li>
                                <?php
                                if ($idDepartamento == 10) {
                                    ?>
                                    <li class="nav-item"><a class="nav-link text-white" href="#tabApoyoClinico" data-toggle="tab">GENERAR PROTOCOLO</a></li>
                                    <?php
                                }
                                F
                                ?>
                            </ul>
                        </div>


                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabListado">
                                    <div class="col-12">
                                        <input type="hidden" name="idDepartamentoProtocolo" id="idDepartamentoProtocolo" value="<?php echo $idDepartamento; ?>">
                                        <table class="table table-bordered" id="tableListProtocolos">
                                            <thead class="bg-secondary text-center align-content-center">
                                            <tr>
                                                <th>FOLIO</th>
                                                <th>FECHA</th>
                                                <th>EJECUTANTE</th>
                                                <th>EQUIPO</th>
                                                <th>VISUALIZAR</th>
                                                <?php
                                                if ($idDepartamento == 1) {
                                                    ?>
                                                    <th>OBSERVACION</th>
                                                    <?php
                                                }

                                                if ($idDepartamento == 10) {
                                                    ?>
                                                    <th>ANULACIÓN</th>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot class="bg-secondary text-center align-content-center">
                                            <tr>
                                                <th>FOLIO</th>
                                                <th>FECHA</th>
                                                <th>EJECUTANTE</th>
                                                <th>EQUIPO</th>
                                                <th>VISUALIZAR</th>
                                                <?php
                                                if ($idDepartamento == 1) {
                                                    ?>
                                                    <th>OBSERVACION</th>
                                                    <?php
                                                }

                                                if ($idDepartamento == 10) {
                                                    ?>
                                                    <th>ANULACIÓN</th>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalObservaciones" tabindex="-1" role="dialog" aria-labelledby="modalObservacionesLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalObservacionesLabel">Agregar Observaciones</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="formObservaciones">
                                                            <div class="form-group">
                                                                <label for="observaciones">FUNCIONARIO:</label>
                                                                <input class="form-control" type="text" name="nomFuncionarioControl" readonly="readonly" id="nomFuncionarioControl" value="<?php echo $nomUsuario ?>">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="exampleInputNomTarjeta">VISUALIZACIÓN EN CONTROL CENTRALIZADO:<code>*</code></label>
                                                                <div class="input-group mb-3">
                                                                    <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                        <label class="btn btn-outline-success active">
                                                                            <input type="radio" name="controlAlarma" id="activado" value="Activado"> Activada
                                                                        </label>
                                                                        <label class="btn btn-outline-danger">
                                                                            <input type="radio" name="controlAlarma" id="noActivado" value="No Activado"> No Activada
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputNomTarjeta" id="charCountObsControl">OBSERVACIONES (MAX. 1200 CARACTERES):<code>*</code></label>
                                                                <textarea id="observaciones" name="observaciones" required class="form-control" rows="7" maxlength="1200" placeholder="Observaciones..." oninput="updateCharCountObsControl()"></textarea>

                                                                <script>
                                                                    function updateCharCountObsControl() {
                                                                        const textarea = document.getElementById('observaciones');
                                                                        const charCount = document.getElementById('charCountObsControl');
                                                                        charCount.textContent = `OBSERVACIONES ${textarea.value.length}/1200 caracteres usados *`;
                                                                    }
                                                                </script>
                                                            </div>

                                                            <input type="hidden" id="idProtocoloObservacion" name="idProtocolo">
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" id="guardarObservaciones">Guardar</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <script>
                                            $(document).ready(function () {
                                                const idDeptoProtocolo = $('#idDepartamentoProtocolo').val();

                                                // Configuración de columnas
                                                let columnsConfig = [
                                                    {"data": "folioProtocolo"}, // Datos de la columna FOLIO
                                                    {"data": "fechaProtocolo2"}, // Datos de la columna FECHA
                                                    {"data": "ejecutanteProtocolo"}, // Datos de la columna EJECUTANTE
                                                    {"data": "nombreEquipo"}, // Datos de la columna EQUIPO
                                                    {
                                                        "data": "idProtocolo",  // Generar contenido para VISUALIZAR
                                                        "render": function (data, type, row) {
                                                            if (row.idProtocolo > 0) {
                                                                return '<form style="margin: 0; text-align: center;" target="_blank" class="" action="../sdo-pdf/SDO-Protocolo-Refrigeradores.php" method="post">' +
                                                                    '<input type="hidden" name="idProtocolo" id="idProtocolo" value="' + row.idProtocolo + '">' +
                                                                    '<button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-eye"></i></button>' +
                                                                    '</form>';
                                                            } else {
                                                                return '<center>Error al Cargar Datos.</center>';
                                                            }
                                                        }
                                                    }
                                                ];

                                                // Si idDeptoProtocolo es igual a 1, agrega otra columna
                                                if (idDeptoProtocolo == 1) {
                                                    columnsConfig.push({
                                                        "data": "idProtocolo", // Botón para abrir modal
                                                        "render": function (data, type, row) {
                                                            if (row.tieneObsControl === 0 && row.isAnulado === 0) {
                                                                return '<button class="btn btn-primary btn-block btn-observaciones" data-id="' + row.idProtocolo + '" data-toggle="modal" data-target="#modalObservaciones">' +
                                                                    '<i class="fa fa-pencil-alt"></i></button>';
                                                            } else if (row.isAnulado === 1) {
                                                                return '<button class="btn btn-secondary btn-block" disabled>Protocolo Anulado</button>';
                                                            } else {
                                                                return '<button class="btn btn-secondary btn-block" disabled>Deshabilitado</button>';
                                                            }
                                                        }
                                                    });
                                                }

                                                // Si idDeptoProtocolo es igual a 1, agrega otra columna
                                                if (idDeptoProtocolo == 10) {
                                                    columnsConfig.push({
                                                        "data": "idProtocolo", // Botón para abrir modal
                                                        "render": function (data, type, row) {
                                                            if (row.isAnulado === 0) {
                                                                return '<button class="btn btn-danger btn-block btn-anulacion" data-id="' + row.idProtocolo + '" data-funcionario="<?php echo $nomUsuario ?>">' +
                                                                    '<i class="fa fa-lock"></i></button>';
                                                            } else {
                                                                return '<button class="btn btn-secondary btn-block" disabled>Protocolo Anulado</button>';
                                                            }
                                                        }
                                                    });
                                                }

                                                $('#tableListProtocolos').DataTable({
                                                    "paging": true,
                                                    "iDisplayLength": 10,
                                                    "lengthChange": false,
                                                    "searching": true,
                                                    "ordering": false,
                                                    "info": true,
                                                    "autoWidth": false,
                                                    "responsive": true,
                                                    "language": {
                                                        "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                        "zeroRecords": "No se han encontrado Registros.",
                                                        "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                        "infoEmpty": "No existen Registros.",
                                                        "search": "Buscar:",
                                                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                        "paginate": {
                                                            "previous": "Anterior",
                                                            "next": "Siguiente"
                                                        }
                                                    },
                                                    "ajax": {
                                                        "url": "/sdo-funciones/protocolo_refrigeradores_datos.php", // Archivo PHP con los datos
                                                        "type": "POST",
                                                        "dataSrc": "" // Si el JSON devuelto contiene un array en la raíz
                                                    },
                                                    "columns": columnsConfig,
                                                    "createdRow": function (row, data, dataIndex) {
                                                        $('td', row).css('vertical-align', 'middle'); // Centrado vertical para las celdas
                                                        $('td', row).css('text-align', 'center'); // Centrado vertical para las celdas
                                                    },
                                                });
                                            });

                                            // Captura el evento de clic en el botón de observaciones
                                            $('#tableListProtocolos').on('click', '.btn-observaciones', function () {
                                                const idProtocolo = $(this).data('id');
                                                $('#idProtocoloObservacion').val(idProtocolo); // Establece el id del protocolo en el formulario

                                                $('#observaciones').val(''); // Limpia el campo de observaciones
                                            });

                                            // Manejo del botón de guardar observaciones
                                            $('#guardarObservaciones').on('click', function () {

                                                console.log('Observacion');
                                                const idProtocolo = $('#idProtocoloObservacion').val();
                                                const observaciones = $('#observaciones').val();
                                                const nomFuncionarioControl = $('#nomFuncionarioControl').val();
                                                const controlAlarma = $('input[name="controlAlarma"]:checked').val(); // Obtener la opción seleccionada

                                                // Validar datos
                                                if (observaciones.trim() === '') {
                                                    alert('Por favor, ingresa una observación.');
                                                    return;
                                                }

                                                // Realizar la solicitud AJAX para guardar las observaciones
                                                $.ajax({
                                                    url: '/sdo-funciones/protocolo_guardarObservaControl.php',
                                                    method: 'POST',
                                                    data: {
                                                        idProtocolo: idProtocolo,
                                                        observaciones: observaciones,
                                                        nomFuncionarioControl: nomFuncionarioControl,
                                                        controlAlarma: controlAlarma
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
                                                                $('#modalObservaciones').modal('hide');
                                                                $('#tableListProtocolos').DataTable().ajax.reload();
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
                                            });


                                            $(document).on('click', '.btn-anulacion', function () {
                                                const idProtocolo = $(this).data('id'); // Obtén el idProtocolo del atributo data-id
                                                const nomFuncionarioAnula = $(this).data('funcionario'); // Obtén el idProtocolo del atributo data-id

                                                console.log(idProtocolo);

                                                // Enviar solicitud AJAX
                                                $.ajax({
                                                    url: '/sdo-funciones/protocolo_anulacion.php', // Cambiar por la URL de tu script PHP
                                                    type: 'POST',
                                                    data: {
                                                        idProtocolo: idProtocolo,
                                                        nomFuncionarioAnula: nomFuncionarioAnula
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        if (response.status === 'success') {
                                                            // Mostrar mensaje de éxito (puedes usar SweetAlert si lo tienes configurado)
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Éxito',
                                                                text: response.message,
                                                                timer: 2000,
                                                                showConfirmButton: false
                                                            });
                                                            // Opcional: Recargar la tabla si es necesario
                                                            $('#tableListProtocolos').DataTable().ajax.reload();
                                                        } else {
                                                            // Mostrar mensaje de error
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Error',
                                                                text: response.message
                                                            });
                                                        }
                                                    },
                                                    error: function (xhr, status, error) {
                                                        // Manejar errores de AJAX
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'Ocurrió un problema al procesar la solicitud.'
                                                        });
                                                        console.error(xhr.responseText);
                                                    }
                                                });
                                            });

                                        </script>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tabApoyoClinico">
                                    <h6 class="text-center">CREACIÓN DE PROTOCOLO DE REVISIÓN</h6>
                                    <br>

                                    <div class="col-12">
                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">

                                                <form action="../sdo-funciones/protocolo_refrigeradores_generar.php" method="post" enctype="multipart/form-data">


                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">NOMBRE FUNCIONARIO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <input type="text" required style="text-transform:uppercase" readonly="readonly" class="form-control" id="nomFuncionario" name="nomFuncionario" placeholder="Nombre de Usuario" value="<?php echo $nomUsuario; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                </div>
                                                                <select class="form-control  select2equipo" name="select2equipo">
                                                                    <option value="Seleccione...">Seleccione...</option>
                                                                    <?php
                                                                    $sqlEquipos = "SELECT * FROM equipamiento_hbqp WHERE idDepaPertenece = 10 ORDER BY nombreEquipo ASC";
                                                                    $resEquipoSistema = mysqli_query($conexion, $sqlEquipos);
                                                                    if ($resEquipoSistema->num_rows > 0) {
                                                                        while ($rowEquipoSistema = $resEquipoSistema->fetch_assoc()) {

                                                                            $nombreEquipo = $rowEquipoSistema['nombreEquipo'];
                                                                            $numeroInventario = $rowEquipoSistema['numeroInventario'];

                                                                            if (!empty($numeroInventario)) {
                                                                                $nombreConInventario = $nombreEquipo . ' ( N° Inventario: ' . $numeroInventario . ' )';
                                                                            } else {
                                                                                $nombreConInventario = $nombreEquipo;
                                                                            }

                                                                            $nombreConInventario = mb_convert_case($nombreConInventario, MB_CASE_UPPER, 'UTF-8');


                                                                            echo '<option value="' . $rowEquipoSistema['id'] . '">' . $nombreConInventario . '</option>';

                                                                        }
                                                                    }

                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">ESTADO GENERAL DE EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                    <label class="btn btn-outline-success active">
                                                                        <input type="radio" name="estadoEquipo" id="operativo" value="Operativo" checked> Operativo
                                                                    </label>
                                                                    <label class="btn btn-outline-danger">
                                                                        <input type="radio" name="estadoEquipo" id="noOperativo" value="No Operativo"> No Operativo
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">ENTORNO DE EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                    <label class="btn btn-outline-success active">
                                                                        <input type="radio" name="entornoEquipo" id="apto" value="Apto" checked> Apto
                                                                    </label>
                                                                    <label class="btn btn-outline-danger">
                                                                        <input type="radio" name="entornoEquipo" id="noApto" value="No Apto"> No Apto
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">T° DISPLAY EQUIPO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                                </div>
                                                                <input type="number" required class="form-control" id="tempEquipo" name="tempEquipo" placeholder="Temperatura del Equipo" step="0.1">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">SONIDO DE ALARMA EN TERRENO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                    <label class="btn btn-outline-success active">
                                                                        <input type="radio" name="sonidoAlarma" id="activado" value="Activado" checked> Activado
                                                                    </label>
                                                                    <label class="btn btn-outline-danger">
                                                                        <input type="radio" name="sonidoAlarma" id="noActivado" value="No Activado"> No Activado
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputNomTarjeta">LUZ DE ALARMA EN TERRENO:<code>*</code></label>
                                                            <div class="input-group mb-3">
                                                                <div class="btn-group btn-group-toggle form-control p-0" data-toggle="buttons">
                                                                    <label class="btn btn-outline-success active">
                                                                        <input type="radio" name="luzAlarma" id="activado" value="Activado" checked> Activado
                                                                    </label>
                                                                    <label class="btn btn-outline-danger">
                                                                        <input type="radio" name="luzAlarma" id="noActivado" value="No Activado"> No Activado
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <label for="exampleInputNomTarjeta" id="charCount">OBSERVACIONES (MAX. 1200 CARACTERES):<code>*</code></label>
                                                            <div class="input-group mb-3">

                                                                <textarea id="observacionesApoyo" style="resize:none;" name="observacionesApoyo" class="form-control" rows="7" maxlength="1200" placeholder="Observaciones..." oninput="updateCharCount()"></textarea>
                                                            </div>

                                                            <script>
                                                                function updateCharCount() {
                                                                    const textarea = document.getElementById('observacionesApoyo');
                                                                    const charCount = document.getElementById('charCount');
                                                                    charCount.textContent = `OBSERVACIONES ${textarea.value.length}/1200 caracteres usados *`;
                                                                }
                                                            </script>
                                                        </div>


                                                    </div>

                                                    <div class="col-12">
                                                        <!-- Profile Image -->
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body box-profile">
                                                                <div class="form-group">
                                                                    <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>
                                                                </div>


                                                                <div class="form-group">
                                                                    <a href="index.php" class="btn btn-block btn-warning"> Volver </a>
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
                    </div>


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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script>

    $(document).ready(function () {
        //Initialize Select2 Elements
        $('.select2equipo').select2({
            theme: 'bootstrap4'
        })


    });
</script>

</body>

</html>