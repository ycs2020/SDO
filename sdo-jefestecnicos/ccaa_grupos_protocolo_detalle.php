<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rolUsuarioSwitch = trim($_SESSION['rolUsuario']);
$rolUsuarioSwitch = mb_convert_case($rolUsuarioSwitch, MB_CASE_LOWER, 'UTF-8');

$rutaDireccion = '';

switch ($rolUsuarioSwitch) {
    case 'personal':
        $rutaDireccion = 'sdo-personal';
        break;
    case 'administrativo':
        $rutaDireccion = 'sdo-admtec';
        break;
    case 'bodega pañol':
        $rutaDireccion = 'sdo-bodega-panol';
        break;
    case 'jefe tecnico':
        $rutaDireccion = 'sdo-jefestecnicos';
        break;
    case 'administrador':
        $rutaDireccion = 'sdo-administrador';
        break;
    case 'directiva':
        $rutaDireccion = 'sdo-directiva';
        break;
    case 'oficina tecnica':
        $rutaDireccion = 'sdo-oficinaTecnica';
        break;
    default:
        header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
        exit();
}


if (isset($_POST['idGrupoCargar'])) {
    $idGrupoCargar = trim($_POST['idGrupoCargar']);
} elseif (isset($_SESSION['idGrupoCargar'])) {
    $idGrupoCargar = trim($_SESSION['idGrupoCargar']);
}

if (!$idGrupoCargar || !is_numeric($idGrupoCargar) || $idGrupoCargar <= 0) {
    header("Location: ../$rutaDireccion/ccaa_grupos_protocolo.php?errorCargaGrupo=true");
    exit();
}

$_SESSION['idGrupoCargar'] = $idGrupoCargar;

$arrayGrupo = getDatosProtocoloCCAAGrupo($idGrupoCargar);

if ($arrayGrupo !== 'vacio') {
    $nomGrupo = $arrayGrupo['nomGrupo'];
    $fechaGrupo = $arrayGrupo['fechaGrupo'];
    $descripGrupo = $arrayGrupo['descripGrupo'];

    $fechaFormateada = "Sin Información";

    if (!empty($fechaGrupo)) {
        $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', $fechaGrupo);
        if ($fechaObj && $fechaObj->format('Y-m-d H:i:s') === $fechaGrupo) {
            $fechaFormateada = fechaEspañol2(strtotime($fechaGrupo));
        }
    }
} else {
    header("Location: ../$rutaDireccion/ccaa_grupos_protocolo.php?errorCargaGrupo=true");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grupos Protocolo CCAA</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Puertas - Grupos Protocolo CCAA</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="ccaa_grupos_protocolo.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row d-flex flex-wrap align-items-stretch">
                    <!-- Tarjeta Datos Grupo -->
                    <div class="col-md-8 col-12" id="cardDatos">
                        <div class="card card-primary card-outline h-100">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" style="font-size: 15px; vertical-align: middle;">
                                        <tbody>
                                        <tr>
                                            <td style="width: 20%; vertical-align: middle;">
                                                <b>Nombre Grupo:</b>
                                            </td>
                                            <td colspan="2" style="width: 80%; vertical-align: middle;">
                                                <?php echo htmlspecialchars($nomGrupo); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%; vertical-align: middle;">
                                                <b>Descripción Grupo:</b>
                                            </td>
                                            <td colspan="2" style="width: 80%; vertical-align: middle;">
                                                <?php echo nl2br(htmlspecialchars($descripGrupo)); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%; vertical-align: middle;">
                                                <b>Fecha Creación Grupo:</b>
                                            </td>
                                            <td colspan="2" style="width: 80%; vertical-align: middle;">
                                                <?php echo htmlspecialchars($fechaFormateada); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Botones con centrado vertical y horizontal de botones -->
                    <div class="col-md-4 col-12" id="cardButtons">
                        <div class="card card-primary card-outline h-100">
                            <div class="card-body h-100 d-flex justify-content-center align-items-center">
                                <div class="w-100 px-3">
                                    <div class="mb-3">
                                        <button type="button" id="btnModificarGrupo"
                                                class="btn btn-warning btn-block"
                                                data-toggle="modal"
                                                data-target="#modalEditarGrupo"
                                                data-id="<?= $idGrupoCargar ?>"
                                                data-nombre="<?= htmlspecialchars($nomGrupo, ENT_QUOTES, 'UTF-8') ?>"
                                                data-descripcion="<?= htmlspecialchars($descripGrupo, ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fa fa-pen"></i>&nbsp;&nbsp;Modificar Grupo
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" id="btnVerPerfiles" class="btn btn-success btn-block">
                                            <i class="fa fa-user"></i>&nbsp;&nbsp;Visualizar Perfiles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="row d-flex flex-wrap align-items-stretch">
                    <!-- Tarjeta Datos Grupo -->
                    <div class="col-12" id="cardPuertas">
                        <div class="card card-primary card-outline h-100">
                            <div class="card-body">

                                <h5 class="text-center font-weight-bold">Listado de Puertas</h5>

                                <br>

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    <div class="col-lg-2 col-12">
                                        <button type="button" id="btnAgregarPuerta" class="btn btn-primary btn-block">
                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Puerta
                                        </button>
                                    </div>
                                </div>


                                <!-- Modal Agregar Puerta -->
                                <div class="modal fade" id="modalAgregarPuerta" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPuertaLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <form id="formAgregarPuerta">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="modalAgregarGrupoLabel">Agregar Puertas</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="selectCriterio">Puertas</label>
                                                        <select class="form-control selectDoor"
                                                                id="selectCriterio"
                                                                name="selectCriterio[]"
                                                                multiple="multiple">
                                                            <option value="Seleccione...">Seleccione...</option>

                                                            <?php renderOpcionesPuertasOrdenadas($conexion, $idGrupoCargar); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idGrupoParaPuerta" id="idGrupoParaPuerta" value="<?php echo $idGrupoCargar; ?>">
                                                    <button type="submit" class="btn btn-success">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-12">

                                        <div class="table-responsive">

                                            <table id="tablaPuertasGruposProtoc" class="table table-bordered text-center table-sm">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        Puerta
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        UCA
                                                    </th>
                                                    <th>
                                                        Acciones
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        Puerta
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        UCA
                                                    </th>
                                                    <th>
                                                        Acciones
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <script>
                                                $(document).ready(function () {
                                                    let idGrupoProtocolo = <?php echo $idGrupoCargar; ?>;

                                                    var tablaPuertasGruposProtoc = $('#tablaPuertasGruposProtoc').DataTable({
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
                                                            "url": "/sdo-includes/ccaa_grupos_protocolo_obtener_puertas.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                                d.idGrupoProtocolo = idGrupoProtocolo;
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
                                                            {"data": "puerta"},
                                                            {"data": "uca"},
                                                            {"data": "botonGrupo"},

                                                            // Agrega más columnas según sea necesario
                                                        ],
                                                        "columnDefs": [
                                                            {
                                                                "width": "30%",
                                                                "targets": 1
                                                            }, // Columna "Nombre" (índice 1)
                                                            {
                                                                "width": "40%",
                                                                "targets": 2
                                                            }  // Columna "Descripción" (índice 2) si también deseas ajustarla
                                                        ],
                                                        "createdRow": function (row, data, dataIndex) {
                                                            $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                                                            $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")

                                                        }
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>

                            </div>
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
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- MODAL EDITAR GRUPO DE PROTOCOLO -->
<div class="modal fade" id="modalEditarGrupo" tabindex="-1" role="dialog" aria-labelledby="modalEditarGrupoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <form id="formEditarGrupo" method="POST" action="../sdo-includes/ccaa_grupos_protocolo_modificar.php">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="fa fa-pen"></i> Editar Grupo</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="idGrupo" id="idGrupoEditar">

                    <div class="form-group">
                        <label>Nombre del Grupo</label>
                        <input type="text" class="form-control" name="nomGrupo" id="nomGrupoEditar" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripGrupo" id="descripGrupoEditar" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Observación del cambio</label>
                        <textarea class="form-control" name="observacion" id="observacionEditar" rows="2" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Guardar Cambios</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    // Cargar datos actuales al presionar el botón
    $('#btnModificarGrupo').on('click', function() {
        // Ejemplo: podrías pasar los valores con data-* del botón
        const idGrupo = $(this).data('id'); // asegúrate que el botón tenga data-id
        const nomGrupo = $(this).data('nombre');
        const descrip = $(this).data('descripcion');

        $('#idGrupoEditar').val(idGrupo);
        $('#nomGrupoEditar').val(nomGrupo);
        $('#descripGrupoEditar').val(descrip);
    });
</script>

<script>

    $(document).ready(function () {
        $('.selectDoor').select2({
            placeholder: 'Seleccione puertas...',
            theme: 'bootstrap4',
            allowClear: true,
            closeOnSelect: false, // ← esto evita que se cierre tras seleccionar
            width: 'resolve'
        });
    });
</script>

<script>
    $('#btnAgregarPuerta').on('click', function () {
        $('#formAgregarPuerta')[0].reset();
        $('#selectCriterio').val(null).trigger('change'); // limpia select2
        $('#modalAgregarPuerta').modal('show');
    });

</script>

<?php
if (isset($_SESSION['iconoMod'], $_SESSION['mensajeMod'])) {
    $icono = $_SESSION['iconoMod'];        // 'success', 'error', 'info', 'warning'
    $mensaje = $_SESSION['mensajeMod'];
// Limpiar después de mostrar
    unset($_SESSION['iconoMod'], $_SESSION['mensajeMod']);

    ?>
    <script>
        <?php
            echo "toastr.$icono('$mensaje');";
        ?>
    </script>
<?php
}
?>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };

</script>
<script>

    $('#formAgregarPuerta').on('submit', function (e) {
        e.preventDefault();

        let idGrupo = $('#idGrupoParaPuerta').val();
        let opcionesSeleccionadas = $('#selectCriterio option:selected');

        if (!opcionesSeleccionadas.length) {
            toastr.warning('Debes seleccionar al menos una puerta.');
            return;
        }

        let puertasSeleccionadas = [];

        opcionesSeleccionadas.each(function () {
            if ($(this).is(':disabled')) return;

            puertasSeleccionadas.push({
                uca: $(this).data('uca'),
                puerta: $(this).data('puerta')
            });
        });

        $.ajax({
            url: '/sdo-includes/ccaa_grupos_protocolo_agregar_puertas.php',
            type: 'POST',
            data: {
                idGrupo: idGrupo,
                puertas: JSON.stringify(puertasSeleccionadas)
            },
            success: function (response) {
                if (response.exito) {
                    toastr.success(response.mensaje);
                    $('#modalAgregarPuerta').modal('hide');
                    $('#tablaPuertasGruposProtoc').DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.mensaje || 'Error al agregar puertas');
                }
            },
            error: function () {
                toastr.error('Error al comunicar con el servidor.');
            }
        });
    });




    function eliminarPuerta(idGrupoPuerta) {
        Swal.fire({
            title: '¿Eliminar Puerta del Grupo?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/sdo-includes/ccaa_grupos_protocolo_eliminar_puerta.php',
                    type: 'POST',
                    data: {idGrupoPuerta: idGrupoPuerta},
                    success: function (response) {
                        toastr.success(response.mensaje, 'Puerta eliminada');
                        $('#tablaPuertasGruposProtoc').DataTable().ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        toastr.error('No se pudo eliminar la puerta.', 'Error');
                    }
                });
            }
        });
    }
</script>
</body>

</html>