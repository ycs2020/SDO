<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 1. Configuración de cabeceras (Opcional pero recomendado para AJAX)
header('Content-Type: text/html; charset=utf-8');

// 2. Simulación de conexión o inclusión de config (Ajusta a tu proyecto)
include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

function formatearVacioFilas($valor)
{
    $valorLimpio = trim($valor ?? '');
    return $valorLimpio !== '' ? $valorLimpio : '-';
}

// 3. Captura de parámetros si los enviaste por AJAX (ej. idCarpeta)
$idCarpeta = isset($_GET['idCarpeta']) ? (int)$_GET['idCarpeta'] : 0;

/* 4. Lógica de Negocio / Consulta SQL */
$getIDCategoriaSistemaCCTV = getIDCategoriaSistemaCCTV();
$getIDCategoriaSistemaCCAA = getIDCategoriaSistemaCCAA();
$getIDCategoriaSistemaBMS = getIDCategoriaSistemaBMS();

if ($idCarpeta == $getIDCategoriaSistemaCCTV) {
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta ORDER BY piso ASC, nombreEquipo ASC";
} else if ($idCarpeta == $getIDCategoriaSistemaCCAA) {
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta AND (modelo = 'ASC1208C-S' OR  modelo = 'ASC1204C-S') ORDER BY piso ASC, nombreEquipo ASC";
} else if ($idCarpeta == $getIDCategoriaSistemaBMS) {
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE (idCategoria = $idCarpeta) AND (modelo LIKE 'BMS') AND nombreEquipo NOT LIKE ('%UMA-%') AND nombreEquipo NOT LIKE ('%Equipos HBQP%') ORDER BY nombreEquipo ASC";
} else {
    $sqlEquipo = "SELECT * FROM equipamiento_hbqp WHERE idCategoria = $idCarpeta ORDER BY  ordenEquipo ASC, piso ASC, pma ASC, nombreEquipo ASC";
}

/* 5. EJECUCIÓN DE LA CONSULTA Y LLENADO DEL ARREGLO */
$equipos = []; // Inicializamos el arreglo vacío

// IMPORTANTE: Cambia '$conexion' por el nombre real de tu variable de conexión
$resultado = mysqli_query($conexion, $sqlEquipo);

if ($resultado) {
    // Recorremos los resultados y los guardamos en el arreglo $equipos
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $equipos[] = $fila;
    }
} else {
    // Opcional: Manejo de errores de SQL para depuración
    // echo "Error en la consulta: " . mysqli_error($conexion);
}
?>
    <h4 class="text-center">
        Listado de Equipos
    </h4>
    <hr>
    <div class="row justify-content-center">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <button type="button"
                    class="btn btn-secondary btn-block btn-buscar-general shadow-sm"
                    data-idcarpeta="<?= $idCarpeta ?>">
                <i class="fas fa-search"></i>
                Buscar equipo en esta categoría
            </button>
        </div>
    </div>

    <div class="modal fade" id="buscadorDinamico" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-search"></i>
                        Buscador de Equipos
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label><b>Buscar equipo</b></label>
                            <input type="text"
                                    class="form-control"
                                    id="variableBuscar"
                                    placeholder="Escriba nombre, marca o modelo...">
                        </div>
                    </div>
                    <input type="hidden" id="idCarpetaBuscar">
                    <div class="table-responsive">
                        <table id="tablaBuscadorDinamico"
                                class="table table-sm table-bordered table-hover text-center"
                                style="width:100%">
                            <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Ruta</th>
                                <th width="100">Ver</th>
                            </tr>
                            </thead>
                            <tbody id="resultadoBusqueda"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        <i class="fas fa-check"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="table-responsive">
        <table id="tablaEquiposListado" class="table table-hover table-striped table-sm mb-0">
            <thead class="bg-secondary text-center">
            <tr>
                <th>N°</th>
                <th>Equipo</th>
                <th>Recinto</th>
                <th>PMA</th>
                <th>Piso</th>
                <th>Estado</th>
                <th>Hoja de Vida</th>
                <th>Información</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $numList = 1;
            $spanEstado = '';
            $btnHojaVida = 'Error al Cargar Información';
            $btnInfo = 'Error al Cargar Información';
            $btnEliminar = 'Error al Cargar Información';
            if (count($equipos) > 0): ?>
                <?php
                foreach ($equipos as $equipo) {

                    $idEquipo = formatearVacioFilas($equipo['id']);
                    $nombreEquipo = formatearVacioFilas($equipo['nombreEquipo']);
                    $recinto = formatearVacioFilas($equipo['recinto']);
                    $pma = formatearVacioFilas($equipo['pma']);
                    $piso = formatearVacioFilas($equipo['piso']);
                    $estadoEquipo = formatearVacioFilas($equipo['estadoEquipo']);
                    $idCategoria = formatearVacioFilas($equipo['idCategoria']);
                    $tipoMenu = formatearVacioFilas($equipo['tipoMenu']);

                    if ($idEquipo > 0) {
                        $btnHojaVida = "<button type='button' class='btn btn-block btn-primary btn-hojaVida' data-idequipo='{$idEquipo}'><i class='fas fa-file'></i></button>";

                        $btnEliminar = "<button type='button' class='btn btn-block btn-danger btn-eliminarEquipo' data-idequipo='{$idEquipo}'><i class='fas fa-trash'></i></button>";

                        if ($idCategoria > 0) {
                            $btnInfo = "<button type='button' class='btn btn-block btn-warning btn-infoEquipo' data-idequipo='{$idEquipo}' data-idcategoria='{$idCategoria}' data-tipomenu='{$tipoMenu}'><i class='fas fa-search'></i></button>";                        }
                    }

                    switch ($estadoEquipo) {
                        case 'Operativo':
                            $spanEstado = "<span class='text-success font-weight-bold'><i class='fas fa-check'></i>&nbsp;&nbsp;{$estadoEquipo}</span>";
                            break;

                        case 'En Mantenimiento':
                            $spanEstado = "<span class='text-warning font-weight-bold'><i class='fas fa-cog'></i>&nbsp;&nbsp;{$estadoEquipo}</span>";
                            break;

                        case 'Fuera de Servicio':
                            $spanEstado = "<span class='text-danger font-weight-bold'><i class='fas fa-skull-crossbones'></i>&nbsp;&nbsp;{$estadoEquipo}</span>";
                            break;

                        case 'Extraviado':
                            $spanEstado = "<span class='text-info font-weight-bold'><i class='fas fa-search'></i>&nbsp;&nbsp;{$estadoEquipo}</span>";
                            break;

                        default:
                            $spanEstado = "<span class='text-secondary'><i class='fas fa-info-circle'></i>&nbsp;&nbsp;{$estadoEquipo}</span>";
                            break;
                    }


                    $spanEstado .= "<br><br>
                                    <span class='d-inline-block px-2 py-1 text-white border bg-secondary rounded btn-cambiaEstado'
                                          role='button'
                                          style='cursor:pointer; font-size: 12px;'
                                          data-idequipo='{$idEquipo}'
                                          data-idcategoria='{$idCategoria}'
                                          data-nombreequipo=\"" . htmlspecialchars($nombreEquipo, ENT_QUOTES, 'UTF-8') . "\"
                                          data-estado='{$estadoEquipo}'>
                                        <i class='fas fa-pen'></i> Cambiar
                                    </span>";

                    ?>
                    <tr>
                        <td><?= h($numList ?? '') ?></td>
                        <td>
                            <strong><?= h($nombreEquipo) ?></strong>
                        </td>
                        <td>
                            <?= h($recinto) ?>
                        </td>
                        <td>
                            <?= h($pma) ?>
                        </td>
                        <td>
                            <?= h($piso) ?>
                        </td>
                        <td>
                            <?= $spanEstado ?>
                        </td>
                        <td>
                            <?= $btnHojaVida ?>
                        </td>
                        <td>
                            <?= $btnInfo ?>
                        </td>
                        <td>
                            <?= $btnEliminar ?>
                        </td>

                    </tr>
                    <?php
                    $numList++;
                }
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted p-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        No se encontraron equipos registrados en esta categoría.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot class="bg-secondary text-center">
            <tr>
                <th>N°</th>
                <th>Equipo</th>
                <th>Recinto</th>
                <th>PMA</th>
                <th>Piso</th>
                <th>Estado</th>
                <th>Hoja de Vida</th>
                <th>Información</th>
                <th>Eliminar</th>
            </tr>
            </tfoot>
        </table>


    </div>

    <div class="modal fade" id="modalCambiarEstado" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit"></i> Cambiar Estado del Equipo
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="idEquipoEstado">
                    <input type="hidden" id="idCategoriaEstado">
                    <input type="hidden" id="nombreEquipoEstado">

                    <div class="form-group">
                        <label for="nuevoEstadoEquipo">Estado del equipo</label>
                        <select id="nuevoEstadoEquipo" class="form-control" required>
                            <option value="Operativo">Operativo</option>
                            <option value="En Mantenimiento">En Mantenimiento</option>
                            <option value="Fuera de Servicio">Fuera de Servicio</option>
                            <option value="OperativoObs">Operativo con Observaciones</option>
                            <option value="Extraviado">Extraviado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observacionesEstado">Observaciones:
                            <code>*</code></label>
                        <textarea id="observacionesEstado" name="observaciones" rows="3" class="form-control" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="btnGuardarEstado">
                        <i class="fas fa-save"></i> Guardar
                    </button>

                    <button class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // 1. Validamos que el DOM haya procesado este nuevo HTML
        $(document).ready(function () {

            // 2. IMPORTANTE: Destruir la tabla si ya existía para evitar errores de recarga
            if ($.fn.DataTable.isDataTable('#tablaEquiposListado')) {
                $('#tablaEquiposListado').DataTable().destroy();
            }

            // 3. Inicializamos CON SU CONFIGURACIÓN ÚNICA
            $('#tablaEquiposListado').DataTable({
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
                "createdRow": function (row, data, dataIndex) {
                    $('td', row).css('vertical-align', 'middle');
                    $('td', row).css('text-align', 'center');
                    $('td:eq(0)', row).html(dataIndex + 1);
                }
            });

            // Función reutilizable para enviar formularios POST
            function enviarFormulario(url, datos = {}, target = '_self') {

                const form = $('<form>', {
                    action: url,
                    method: 'POST',
                    target: target
                });

                $.each(datos, function (nombre, valor) {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: nombre,
                        value: valor
                    }));
                });

                form.appendTo('body').submit();
            }

            // HOJA DE VIDA
            $('#tablaEquiposListado')
                .off('click.hojaVida')
                .on('click.hojaVida', '.btn-hojaVida', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = $(this).data('idequipo');

                    enviarFormulario(
                        '../sdo-pdf/SDO-Hoja-Vida-Equipamiento.php',
                        { idCarpeta: id },
                        '_blank'
                    );
                });


            // INFO EQUIPO
            $('#tablaEquiposListado')
                .off('click.infoEquipo')
                .on('click.infoEquipo', '.btn-infoEquipo', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const idEquipo = $(this).data('idequipo');
                    const idCategoria = $(this).data('idcategoria');
                    const tipomenu = $(this).data('tipomenu');

                    const url = (tipomenu === 'nuevo')
                        ? 'equipo_menuNuevo.php'
                        : 'equipo_menuAntiguo.php';

                    const target = (tipomenu === 'nuevo')
                        ? '_self'
                        : '_blank';

                    enviarFormulario(url, {
                        idEquipo: idEquipo,
                        idCategoria: idCategoria
                    }, target);
                });

            //ELIMINAR EQUIPO
            $('#tablaEquiposListado').on('click', '.btn-eliminarEquipo', function (e) {
                e.preventDefault();

                const id = $(this).data('idequipo');

                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar este equipo?',
                    text: 'Esta acción no se puede revertir',
                    showConfirmButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, lo pensaré',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/eliminar-equipo-carpeta.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {id: id},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registro del equipo eliminado',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });

                                    setTimeout(function () {
                                        $('#tablaEquiposListado').DataTable().ajax.reload(null, false);
                                    }, 500);
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'No se puede eliminar',
                                        text: response.message,
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error al eliminar el equipo:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo procesar la solicitud.',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        });
                    }
                });
            });

            //ABRIR MODAL CAMBIAR ESTADO
            $('#tablaEquiposListado').on('click', '.btn-cambiaEstado', function () {
                const idEquipo = $(this).data('idequipo');
                const idCategoria = $(this).data('idcategoria');
                const nombreEquipo = $(this).data('nombreequipo');
                const estadoActual = $(this).data('estado');

                $('#idEquipoEstado').val(idEquipo);
                $('#idCategoriaEstado').val(idCategoria);
                $('#nombreEquipoEstado').val(nombreEquipo);
                $('#nuevoEstadoEquipo').val(estadoActual);
                $('#observacionesEstado').val('');

                $('#modalCambiarEstado').modal('show');
            });

            //GUARDAR NUEVO ESTADO
            $(document)
                .off('click.guardarEstado', '#btnGuardarEstado')
                .on('click.guardarEstado', '#btnGuardarEstado', function () {

                const boton = $(this);

                const idEquipo = $('#idEquipoEstado').val();
                const idCategoria = $('#idCategoriaEstado').val();
                const nombreEquipo = $('#nombreEquipoEstado').val();
                const estado = $('#nuevoEstadoEquipo').val();
                const observaciones = $('#observacionesEstado').val().trim();

                if (observaciones === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo requerido',
                        text: 'Debe ingresar una observación.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // 🔒 DESACTIVAR BOTÓN
                boton.prop('disabled', true);
                boton.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

                $.ajax({
                    url: '../sdo-funciones/actualizar-estado-equipo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idEquipo: idEquipo,
                        idCategoria: idCategoria,
                        nombreEquipo: nombreEquipo,
                        opcionEstado: estado,
                        observaciones: observaciones
                    },

                    success: function (response) {

                        if (response.status) {

                            $('#modalCambiarEstado').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Estado actualizado',
                                text: response.message || 'El estado fue actualizado correctamente.',
                                showConfirmButton: false,
                                timer: 1800
                            });

                            setTimeout(function () {
                                $('#tablaEquiposListado').DataTable().ajax.reload(null, false);
                            }, 500);

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'No se pudo actualizar el estado.',
                                confirmButtonColor: '#3085d6'
                            });

                        }

                    },

                    error: function () {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un problema al procesar la solicitud.',
                            confirmButtonColor: '#3085d6'
                        });

                    },

                    complete: function () {

                        // 🔓 REACTIVAR BOTÓN
                        boton.prop('disabled', false);
                        boton.html('<i class="fas fa-save"></i> Guardar');

                    }

                });

            });

            $(document)
                .off('click.buscarGeneral', '.btn-buscar-general')
                .on('click.buscarGeneral', '.btn-buscar-general', function () {
                    let idCarpeta = $(this).data('idcarpeta');

                    $('#idCarpetaBuscar').val(idCarpeta);
                    $('#variableBuscar').val('');
                    $('#resultadoBusqueda').html('');
                    $('#buscadorDinamico').modal('show');
                });

            $(document)
                .off('keyup.variableBuscar', '#variableBuscar')
                .on('keyup.variableBuscar', '#variableBuscar', function () {
                    let texto = $(this).val();
                    let idCarpeta = $('#idCarpetaBuscar').val();

                    if (texto.length < 2) {
                        $('#resultadoBusqueda').html('');
                        return;
                    }

                    $.ajax({
                        url: '../sdo-funciones/equipamiento_buscar_equipo_categoria.php',
                        type: 'POST',
                        data: {
                            texto: texto,
                            idCarpeta: idCarpeta
                        },
                        success: function (respuesta) {
                            $('#resultadoBusqueda').html(respuesta);
                        }
                    });
                });

            $('#tablaBuscadorDinamico')
                .off('click.infoEquipoBuscador')
                .on('click.infoEquipoBuscador', '.btn-infoEquipo', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const idEquipo = $(this).data('idequipo');
                    const idCategoria = $(this).data('idcategoria');
                    const tipomenu = $(this).data('tipomenu');

                    const url = (tipomenu === 'nuevo')
                        ? 'equipo_menuNuevo.php'
                        : 'equipo_menuAntiguo.php';

                    const target = (tipomenu === 'nuevo')
                        ? '_self'
                        : '_blank';

                    enviarFormulario(url, {
                        idEquipo: idEquipo,
                        idCategoria: idCategoria
                    }, target);
                });
        });
    </script>


<?php
// Función auxiliar para seguridad (XSS)
function h($string)
{
    // Verifica si la variable existe para evitar warnings de null
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

?>