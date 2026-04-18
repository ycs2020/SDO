$('#formNuevoRequerimiento').on('submit', function(e) {
    e.preventDefault();

    // Guardamos el selector del formulario para usarlo después
    var formulario = $(this);

    $.ajax({
        type: 'POST',
        url: '../sdo-includes/plataforma_requerimientos_funciones/requerimientos_crear_solicitud.php', // <--- Aquí defines la ruta exacta
        data: formulario.serialize(),
        dataType: 'json',
        beforeSend: function() {
            // Opcional: Deshabilitar el botón para evitar múltiples clics
            formulario.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');

        },
        success: function(data) {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.text,
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.text, 'error');
                formulario.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Enviar');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); // Esto te dirá el error real en la consola de F12
            Swal.fire('Error', 'Error técnico: ' + error, 'error');
        }
    });
});