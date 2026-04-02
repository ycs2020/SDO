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


if (isset($_POST['idPerfilCargar'])) {
    $idPerfilCargar = trim($_POST['idPerfilCargar']);
} elseif (isset($_SESSION['idPerfilCargar'])) {
    $idPerfilCargar = trim($_SESSION['idPerfilCargar']);
}

if (!$idPerfilCargar || !is_numeric($idPerfilCargar) || $idPerfilCargar <= 0) {
    header("Location: ../$rutaDireccion/ccaa_grupos_protocolo.php?errorCargaGrupo=true");
    exit();
}

$_SESSION['idPerfilCargar'] = $idPerfilCargar;

$arrayPerfil = getDatosProtocoloCCAAPerfil($idPerfilCargar);

if ($arrayPerfil !== 'vacio') {
    $nomPerfil = $arrayPerfil['nomPerfil'];
    $fechaPerfil = $arrayPerfil['fechaPerfil'];
    $descripPerfil = $arrayPerfil['descripPerfil'];

    $fechaFormateada = "Sin Información";

    if (!empty($fechaGrupo)) {
        $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', $fechaPerfil);
        if ($fechaObj && $fechaObj->format('Y-m-d H:i:s') === $fechaPerfil) {
            $fechaFormateada = fechaEspañol2(strtotime($fechaPerfil));
        }
    }
} else {
    header("Location: ../$rutaDireccion/ccaa_perfiles_protocolo.php?errorCargaPerfil=true");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Perfiles Protocolo CCAA</title>

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
                                <a style="pointer-events: none;" class="btn font-weight-bold">Perfiles - Grupos Protocolo CCAA</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="ccaa_perfiles_protocolo.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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
                                                <b>Nombre Perfil:</b>
                                            </td>
                                            <td colspan="2" style="width: 80%; vertical-align: middle;">
                                                <?php echo htmlspecialchars($nomPerfil); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%; vertical-align: middle;">
                                                <b>Descripción Perfil:</b>
                                            </td>
                                            <td colspan="2" style="width: 80%; vertical-align: middle;">
                                                <?php echo nl2br(htmlspecialchars($descripPerfil)); ?>
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
                                        <button type="button" id="btnModificarPerfil"
                                                class="btn btn-warning btn-block"
                                                data-toggle="modal"
                                                data-target="#modalEditarPerfil"
                                                data-id="<?= $idPerfilCargar ?>"
                                                data-nombre="<?= htmlspecialchars($nomPerfil, ENT_QUOTES, 'UTF-8') ?>"
                                                data-descripcion="<?= htmlspecialchars($descripPerfil, ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fa fa-pen"></i>&nbsp;&nbsp;Modificar Perfil
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
                                        <button type="button" id="btnAgregarGrupo" class="btn btn-primary btn-block">
                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Grupo
                                        </button>
                                    </div>
                                </div>


                                <!-- Modal Agregar Grupo -->
                                <div class="modal fade" id="modalAgregarGrupo" tabindex="-1" role="dialog" aria-labelledby="modalAgregarGrupoLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <form id="formAgregarGrupo">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="modalAgregarGrupoLabel">Agregar Grupos</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="selectCriterio">Grupos</label>
                                                        <select class="form-control selectGrupo"
                                                                id="selectCriterio"
                                                                name="selectCriterio[]"
                                                                multiple="multiple">
                                                            <option value="Seleccione...">Seleccione...</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idPerfilParaGrupo" id="idPerfilParaGrupo" value="<?php echo $idPerfilCargar; ?>">
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

                                            <table id="tablaGrupoPerfilesProtoc" class="table table-bordered text-center table-sm">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        N°
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        Grupo
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        Descripcion
                                                    </th>
                                                    <th>
                                                        Visualizar
                                                    </th>
                                                    <th>
                                                        Eliminar
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
                                                        Grupo
                                                    </th>
                                                    <th style="vertical-align:middle; text-align:center;">
                                                        Descripcion
                                                    </th>
                                                    <th>
                                                        Visualizar
                                                    </th>
                                                    <th>
                                                        Eliminar
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <script>
                                                $(document).ready(function () {
                                                    let idPerfilProtocolo = <?php echo $idPerfilCargar; ?>;

                                                    var tablaGrupoPerfilesProtoc = $('#tablaGrupoPerfilesProtoc').DataTable({
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
                                                            "url": "/sdo-includes/ccaa_perfiles_protocolo_obtener_grupos.php", // Archivo PHP que contiene la consulta SQL
                                                            "type": "POST",
                                                            "data": function (d) {
                                                                d.idPerfilProtocolo = idPerfilProtocolo;
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
                                                            {"data": "grupo"},
                                                            {"data": "descripcion"},
                                                            {"data": "botonVisualizar"},
                                                            {"data": "botonEliminar"},

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

<!-- MODAL EDITAR PERFIL DE PROTOCOLO -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" role="dialog" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <form id="formEditarPerfil" method="POST" action="../sdo-includes/ccaa_perfiles_protocolo_modificar.php">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="fa fa-pen"></i> Editar Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="idPerfil" id="idPerfilEditar">

                    <div class="form-group">
                        <label>Nombre del Grupo</label>
                        <input type="text" class="form-control" name="nomPerfil" id="nomPerfilEditar" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripPerfil" id="descripPerfilEditar" rows="3"></textarea>
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

<div class="modal fade" id="modalVerPuertasGrupo" tabindex="-1" role="dialog" aria-labelledby="modalVerPuertasGrupoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAutorizacionesLabel">Puertas en Grupo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoPuertas">
                <p class="text-muted">Cargando información...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cargar datos actuales al presionar el botón
    $('#btnModificarPerfil').on('click', function() {
        // Ejemplo: podrías pasar los valores con data-* del botón
        const idPerfil = $(this).data('id'); // asegúrate que el botón tenga data-id
        const nomPerfil = $(this).data('nombre');
        const descrip = $(this).data('descripcion');

        $('#idPerfilEditar').val(idPerfil);
        $('#nomPerfilEditar').val(nomPerfil);
        $('#descripPerfilEditar').val(descrip);
    });
</script>

<script>

    $(document).ready(function () {
        $('.selectGrupo').select2({
            placeholder: 'Seleccione grupos...',
            theme: 'bootstrap4',
            allowClear: true,
            closeOnSelect: false, // ← esto evita que se cierre tras seleccionar
            width: 'resolve'
        });
    });
</script>

<script>
    $('#btnAgregarGrupo').on('click', function () {
        $('#formAgregarGrupo')[0].reset();
        $('#selectCriterio').val(null).trigger('change'); // limpia select2

        let idPerfil = $('#idPerfilParaGrupo').val();
        recargarSelectPerfiles(idPerfil);

        $('#modalAgregarGrupo').modal('show');
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


    $('#formAgregarGrupo').on('submit', function (e) {
        e.preventDefault();

        let idPerfil = $('#idPerfilParaGrupo').val();
        let opcionesSeleccionadas = $('#selectCriterio option:selected');

        if (!opcionesSeleccionadas.length) {
            toastr.warning('Debes seleccionar al menos un grupo.');
            return;
        }

        let gruposSeleccionados = [];

        opcionesSeleccionadas.each(function () {
            if ($(this).is(':disabled')) return;

            gruposSeleccionados.push({
                grupo: $(this).data('grupo'),
            });
        });

        $.ajax({
            url: '/sdo-includes/ccaa_perfiles_protocolo_agregar_grupo.php',
            type: 'POST',
            data: {
                idPerfil: idPerfil,
                grupo: JSON.stringify(gruposSeleccionados)
            },
            success: function (response) {

                if (!response.exito) {
                    toastr.error(response.mensaje || 'Error al agregar grupos');
                    return;
                }

                let insertados = parseInt(response.insertados);
                let duplicados = parseInt(response.duplicados);
                let erroresFormato = parseInt(response.erroresFormato);
                let erroresInsert = parseInt(response.erroresInsert);

                // --- LÓGICA DE CLASIFICACIÓN ---
                if (insertados > 0 && duplicados === 0 && erroresFormato === 0 && erroresInsert === 0) {
                    // TODOS EXITOSOS
                    toastr.success(response.mensaje);

                } else if (insertados > 0 && (duplicados > 0 || erroresFormato > 0 || erroresInsert > 0)) {
                    // PARCIALMENTE EXITOSOS
                    toastr.warning(response.mensaje);

                } else if (insertados === 0 && (duplicados > 0 || erroresFormato > 0 || erroresInsert > 0)) {
                    // NINGUNO SE INSERTÓ → ERROR
                    toastr.error(response.mensaje);

                } else {
                    // Caso extremo inesperado
                    toastr.info("Operación finalizada, revisar detalles.");
                }

                $('#modalAgregarGrupo').modal('hide');
                $('#tablaGrupoPerfilesProtoc').DataTable().ajax.reload(null, false);
                recargarSelectPerfiles(idPerfil);
            },
            error: function () {
                toastr.error('Error al comunicar con el servidor.');
            }
        });
    });




    function eliminarGrupo(idPerfilGrupo) {
        Swal.fire({
            title: '¿Eliminar Grupo del Perfil?',
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
                    url: '/sdo-includes/ccaa_perfiles_protocolo_eliminar_grupo.php',
                    type: 'POST',
                    data: {idPerfilGrupo: idPerfilGrupo},
                    success: function (response) {
                        toastr.success(response.mensaje, 'Grupo Eliminado');
                        $('#tablaGrupoPerfilesProtoc').DataTable().ajax.reload(null, false);

                        // recargar select
                        let idPerfil = $('#idPerfilParaGrupo').val();
                        recargarSelectPerfiles(idPerfil);
                    },
                    error: function (xhr) {
                        toastr.error('No se pudo eliminar la puerta.', 'Error');
                    }
                });
            }
        });
    }

    function recargarSelectPerfiles(idPerfil) {
        $.ajax({
            url: '/sdo-includes/ccaa_perfiles_protocolo_obtener_grupos_disponibles.php',
            type: 'GET',
            dataType: 'json',
            data: { idPerfil: idPerfil },
            success: function (grupos) {
                let select = $('#selectCriterio');
                select.empty();

                select.append('<option value="">Seleccione...</option>');

                grupos.forEach(g => {
                    select.append(`
                    <option value="${g.idGrupo}" data-grupo="${g.idGrupo}">
                        ${g.nomGrupo}
                    </option>
                `);
                });

                select.trigger('change'); // para Select2 si lo usas
            }
        });
    }

    // Botón para ver autorizaciones
    $(document).on('click', '#btnVerPuertas', function () {
        let idGrupoVer = $(this).data('idgrupo');

        $('#contenidoPuertas').html('<p class="text-info">Cargando puertas...</p>');
        $('#modalVerPuertasGrupo').modal('show');

        $.ajax({
            url: '/sdo-includes/ccaa_puertas_protocolo_obtener.php',
            method: 'POST',
            data: {
                idGrupoVer: idGrupoVer
            },
            success: function (data) {
                $('#contenidoPuertas').html(data);

                // Verificar y destruir si ya existe
                if ($.fn.DataTable.isDataTable('#tablaPuertasGrupo')) {
                    $('#tablaPuertasGrupo').DataTable().clear().destroy();
                }

                // Inicializar nuevamente
                $('#tablaPuertasGrupo').DataTable({
                    "paging": true,
                    "iDisplayLength": 10,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "lengthMenu": "Visualizando _MENU_ Registros por Pág.",
                        "zeroRecords": "No se han encontrado Registros.",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No existen Registros.",
                        "search": "Buscar:",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    }
                });
            },
            error: function () {
                $('#contenidoPuertas').html('<p class="text-danger">Error al cargar la información.</p>');
            }
        });
    });

</script>


</body>

</html>