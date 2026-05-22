$(document).ready(function () {

    // 1. Inicialización de la Tabla
    // Guardamos la instancia en una variable para poder usar .ajax.reload()
    // Asegúrate de que este ID exista en tu HTML o usa $('input[name="idMovil"]').val();
    const idMovil = $('#idMovilAjax').val() || $('input[name="idMovil"]').val();

    const tableKilometro = $('#table_kilometro').DataTable($.extend({}, dataTableConfig, {
        ajax: {
            url: "../sdo-includes/vehiculos/vehiculos_kilometraje_listado.php",
            type: "POST",
            data: { idMovil: idMovil }
            // dataSrc: ""  <-- ELIMINADO: Tu PHP ya devuelve el objeto con la llave "data"
        },
        columns: [
            { "data": "fechaSubidaFormateada" },
            { "data": "fechaRegistroFormateada" },
            { "data": "cantKilometraje" },
            { "data": "btnModificar" },
            { "data": "btnEliminar" },

        ],
        createdRow: function(row, data, dataIndex) {
            // Pintar de amarillo si es registro de mantenimiento
            if (data.registroMantenimiento == 1) {
                $(row).find('td').css('background-color', '#ffff00');
            }
        }
    }));

    // 2. Evento para MODIFICAR (Delegado)
    $('#table_kilometro').on('click', '.btn-mod-km', function() {
        // Obtenemos los datos de la fila actual a través de la API de DataTables
        const data = tableKilometro.row($(this).parents('tr')).data();

        // Cargamos el modal con los valores originales (sin formato)
        $('#idKilometrajeEditar').val(data.idKilometraje);
        $('#fechaReporteKmMod').val(data.fechaRegistro); // Valor original yyyy-mm-dd
        $('#numKilometrajeMod').val(data.valorNumerico);  // Valor limpio sin puntos

        $('#editarRegistroKm').modal('show');
    });

    // 2. Manejo del Formulario de Guardado (Nuevo Registro)
    $('#formKilometraje').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btnGuardarKms');
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        const formData = $(this).serialize();

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_kilometraje_agregar.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#kilometrajeModal').modal('hide');
                    $('#formKilometraje')[0].reset();
                    tableKilometro.ajax.reload(null, false);
                    refreshMantenimientoUI(); // <--- Aquí ocurre la magia
                    // Toast de éxito (menos invasivo)
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registro Exitoso!',
                        text: 'El kilometraje se ha guardado correctamente.',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#1e1e2d', // Color oscuro para tu dashboard
                        color: '#ffffff'
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr) {
                console.error("Error crítico:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Técnico',
                    text: 'No se pudo procesar la solicitud. Revisa la consola.',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

// 3. Manejo del Formulario de Edición
    $('#formEditarKilometraje').on('submit', function (e) {
        e.preventDefault();

        const btn = $(this).find('button[type="submit"]');
        const originalText = '<i class="fas fa-sync-alt mr-1"></i> Actualizar Cambios';

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_kilometraje_modificar.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editarRegistroKm').modal('hide');
                    tableKilometro.ajax.reload(null, false);
                    refreshMantenimientoUI(); // <--- Aquí ocurre la magia
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'El registro se modificó correctamente.',
                        timer: 1500,
                        showConfirmButton: false
                        // Quitamos el background para que no sea negro
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo actualizar',
                        text: response.message // Aquí verás el error que lance el PHP
                    });
                }
            },
            error: function(xhr) {
                console.error("Respuesta del servidor:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'Revisa la consola (F12) para ver el error técnico.'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // 3. Evento para ELIMINAR (Delegado con SweetAlert2)
    // Dentro del $(document).ready
    $('#table_kilometro').on('click', '.btn-delete-km', function() {
        const idKm = $(this).data('id'); // Captura el data-id del botón

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará el registro de kilometraje permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Rojo para eliminar
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar estado de carga en el SweetAlert
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: '../sdo-includes/vehiculos/vehiculos_kilometraje_eliminar.php',
                    type: 'POST',
                    data: { id: idKm },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Refrescar la tabla sin recargar página
                            tableKilometro.ajax.reload(null, false);
                            refreshMantenimientoUI(); // <--- Aquí ocurre la magia
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: 'El registro ha sido borrado correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error Técnico', 'No se pudo comunicar con el servidor.', 'error');
                    }
                });
            }
        });
    });
});

// --- Funciones Globales (Window) ---

// Función para cargar datos en el modal de edición
window.editarKilometraje = function(idKm, fecha, km) {
    $('#idKilometrajeEditar').val(idKm);
    $('#fechaReporteKmMod').val(fecha);
    $('#numKilometrajeMod').val(km);
    $('#editarRegistroKm').modal('show');
};

function refreshMantenimientoUI() {
    // Buscamos el ID del móvil de forma más robusta
    const idMovil = $('#idMovilAjax').val() || $('input[name="idMovil"]').val();

    if(!idMovil) {
        console.error("No se encontró el idMovil para actualizar la alerta.");
        return;
    }

    // Efecto visual de carga
    $('#contenedorAlertasMantenimiento').css('opacity', '0.6');

    $.ajax({
        url: '../sdo-includes/vehiculos/vehiculos_mantenimiento_info.php',
        type: 'POST',
        data: { idMovil: idMovil },
        // Forzamos que no guarde en caché para que el cálculo sea real
        cache: false,
        success: function(html) {
            if(html.trim() === "") {
                console.warn("El servidor devolvió una alerta vacía.");
            }
            $('#contenedorAlertasMantenimiento').html(html).css('opacity', '1');
        },
        error: function(xhr) {
            console.error("Error al refrescar alertas:", xhr.responseText);
            $('#contenedorAlertasMantenimiento').css('opacity', '1');
        }
    });
}

// Función para eliminar (si la necesitas)
window.eliminarKilometraje = function(idKm) {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        $.post('../sdo-funciones/eliminar-kilometraje.php', { id: idKm }, function(resp) {
            $('#table_kilometro').DataTable().ajax.reload(null, false);
        }, 'json');
    }
};