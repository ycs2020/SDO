$(document).ready(function () {
    // Lógica EXCLUSIVA para el envío del formulario (POST AJAX)
    $(document).on('submit', '#formularioCambiaEstado', function(e) {
        e.preventDefault();
        const btn = $('#btnGuardarEstado');
        const datos = $(this).serialize();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
            url: '/sdo-includes/equipamiento/equipamiento_modificar_estado.php', // Tu archivo de procesamiento
            type: 'POST',
            data: datos,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalSdo').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => { location.reload(); });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    btn.prop('disabled', false).text('Guardar Estado');
                }
            },
            error: function() {
                Swal.fire('Error Crítico', 'Error de comunicación con el servidor', 'error');
                btn.prop('disabled', false).text('Guardar Estado');
            }
        });
    });
});