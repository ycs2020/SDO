/**
 * Inicializa la lógica de la pestaña Información
 */
window.initInformacion = function() {
    console.log("Iniciando componentes de Información..."); // Depuración

    // Inicializar inputs de archivos de AdminLTE
    if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
    }

    // Switch de habilitar edición
    // IMPORTANTE: Asegúrate que el ID en el HTML sea 'fieldset' (con 'd')
    $('#customSwitch1').off('change').on('change', function () {
        const isChecked = $(this).is(':checked');
        // Seleccionamos ambos posibles IDs por si acaso: el correcto y el del error de tipeo
        $('#fieldset, #fielset').prop('disabled', !isChecked);

        // Estética: opacidad suave cuando está deshabilitado
        if(!isChecked) {
            $('#fieldset, #fielset').css('opacity', '0.7');
        } else {
            $('#fieldset, #fielset').css('opacity', '1');
        }
    });

    // AJAX: Guardar Información General
    $('#formDatosVehiculo').off('submit').on('submit', function (e) {
        e.preventDefault();

        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.html();

        // Bloqueo de botón y spinner
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        let formData = new FormData(this);

        fetch('../sdo-includes/vehiculos/vehiculos_detalle_modificar.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                return response.json();
            })
            .then(data => {
                if (data.success || data.status === 'success') {

                    // 1. Obtener los nuevos valores de los inputs
                    const nuevoNombre = $('#nomVehiculo').val();
                    const nuevoIdInterno = $('#idVehiculo').val();
                    const nuevoTitulo = `${nuevoNombre} - ${nuevoIdInterno}`;

                    // 2. Actualizar el texto en la cabecera inmediatamente
                    $('#nombreVehiculoCabecera').html(`<i class="fas fa-car mr-2"></i> Vehículo: ${nuevoTitulo}`);

                    // 3. (Opcional) Actualizar el título de la pestaña del navegador
                    document.title = `${nuevoTitulo} | SDO`;

                    // Desactivar switch y bloquear campos tras éxito
                    $('#customSwitch1').prop('checked', false).trigger('change');

                    Swal.fire({
                        title: '¡Actualizado!',
                        text: 'La información del vehículo se ha actualizado correctamente.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: 'Atención',
                        text: data.message || 'No se pudieron guardar los cambios.',
                        icon: 'warning'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            })
            .finally(() => {
                btn.prop('disabled', false).html(originalText);
            });
    });
};

// Ejecución inmediata si el tab ya está activo al cargar
$(document).ready(function() {
    if ($('#tabInformacion').hasClass('active')) {
        initInformacion();
    }

    // Si usas tabs dinámicos de AdminLTE/Bootstrap
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).attr("href") === "#tabInformacion") {
            initInformacion();
        }
    });
});