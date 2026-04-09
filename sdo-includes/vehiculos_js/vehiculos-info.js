/**
 * Inicializa la lógica de la pestaña Información
 */
window.initInformacion = function() {
    // Inicializar inputs de archivos (solo si no se ha hecho)
    bsCustomFileInput.init();

    // Switch de habilitar edición
    // Usamos off().on() para prevenir la duplicidad de listeners
    $('#customSwitch1').off('change').on('change', function () {
        $('#fielset').prop('disabled', !this.checked);
    });

    // AJAX: Guardar Información General
    $('#formDatosVehiculo').off('submit').on('submit', function (e) {
        e.preventDefault();

        // Feedback visual en el botón (Opcional pero recomendado)
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        let formData = new FormData(this);

        fetch('../sdo-includes/vehiculos/vehiculos_detalle_modificar.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Desactivar switch y bloquear campos tras éxito
                    $('#customSwitch1').prop('checked', false).trigger('change');

                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: 'Atención',
                        text: data.message,
                        icon: 'warning'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            })
            .finally(() => {
                btn.prop('disabled', false).html(originalText);
            });
    });
};