$(document).ready(function () {
    // 1. Inicialización de la Tabla
    // Guardamos la instancia en una variable para poder usar .ajax.reload()
    const tableKilometro = $('#table_kilometro').DataTable(dataTableConfig);

    // 2. Manejo del Formulario de Guardado (Nuevo Registro)
    $('#formKilometraje').on('submit', function(e) {
        e.preventDefault();

        // Referencia al botón para feedback visual
        const btn = $('#btnGuardarKms');
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        // Serializar datos del formulario
        const formData = $(this).serialize();

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_kilometraje_agregar.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta servidor:", response); // DEPUREMOS AQUÍ
                if (response.status === 'success') {
                    $('#kilometrajeModal').modal('hide');
                    $('#formKilometraje')[0].reset();
                    // Refrescar usando la instancia guardada
                    tableKilometro.ajax.reload(null, false);
                    alert('¡Registro exitoso!');
                } else {
                    alert('Error del sistema: ' + response.message);
                }
            },
            error: function(xhr) {
                // Esto te dirá exactamente qué error de PHP está saltando
                console.error("Error crítico:", xhr.responseText);
                alert('Error técnico. Revisa la consola (F12).');
            }
        });
    });

    // 3. Manejo del Formulario de Edición
    $('#formEditarKilometraje').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '../sdo-funciones/editar-kilometraje-moviles.php', // Ajusta la ruta
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editarRegistroKm').modal('hide');
                    tableKilometro.ajax.reload(null, false);
                    alert('Registro actualizado.');
                } else {
                    alert('Error al actualizar: ' + response.message);
                }
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

// Función para eliminar (si la necesitas)
window.eliminarKilometraje = function(idKm) {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        $.post('../sdo-funciones/eliminar-kilometraje.php', { id: idKm }, function(resp) {
            $('#table_kilometro').DataTable().ajax.reload(null, false);
        }, 'json');
    }
};