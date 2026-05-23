$(document).ready(function () {

    // Inicializar Select2 con el tema nativo de AdminLTE / Bootstrap 4
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione una opción',
        allowClear: false,
        width: '100%'
    });

    // Fix para recalcular tamaño de Select2 al cambiar de pestañas en AdminLTE
    $('a[data-toggle="pill"]').on('shown.bs.tab', function () {
        $('.select2').each(function() {
            $(this).select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    });

    // Forzar mayúsculas nativas en servicios
    $('#servicios').on('input', function () {
        this.value = this.value.toUpperCase();
    });

    // Escuchar cambios en la selección de departamentos
    $('#departamentoSolicitante').on('change', function () {
        var idDepartamento = $(this).val();
        var idDepartamentoInt = parseInt(idDepartamento);
        var $selectPersonal = $('#tecnico1');

        // Carga dinámica mediante AJAX de técnicos
        if (idDepartamento) {
            $selectPersonal.html('<option value="">Cargando personal...</option>').prop('disabled', true);

            $.ajax({
                url: '../sdo-includes/ordenes_trabajos/personal_buscar_nombres.php',
                type: 'POST',
                data: { idDepartamento: idDepartamento },
                dataType: 'json',
                success: function (response) {
                    $selectPersonal.html('<option value="" selected disabled>Seleccione Personal...</option>').prop('disabled', false);

                    if (response.status === 'success' && response.data.length > 0) {
                        $.each(response.data, function (index, usuario) {
                            // Aquí mandamos el NOMBRE como value a petición tuya
                            $selectPersonal.append('<option value="' + usuario.nombre + '">' + usuario.nombre + '</option>');
                        });
                    } else {
                        $selectPersonal.html('<option value="">No se encontró personal</option>');
                    }
                    // Refrescar Select2 de forma segura una sola vez al terminar
                    $selectPersonal.trigger('change.select2');
                },
                error: function (xhr) {
                    console.error("Error cargando técnicos:", xhr.responseText);
                    $selectPersonal.html('<option value="">Error al cargar datos</option>').prop('disabled', false).trigger('change.select2');
                }
            });
        } else {
            $selectPersonal.html('<option value="">Seleccione un departamento primero</option>').trigger('change.select2');
        }

        // Conmutación visual de contenedores (Ningún detalle de texto es requerido)
        if (idDepartamentoInt === 1) {
            $('#seccion-checklist-adjuntos').fadeIn('fast');
            $('#bloque-detalle-general').hide();
            $('#bloque-detalle-especifico').fadeIn('fast');
        } else if (idDepartamentoInt > 0) {
            $('#seccion-checklist-adjuntos').hide();
            $('#checklist').val('').trigger('change.select2');
            $('#cantidad').val('');
            $('#archivoAdjunto').val('');
            $('#seccion-checklist-adjuntos .custom-file-label').html('Elegir archivo...');

            $('#bloque-detalle-especifico').hide();
            $('#bloque-detalle-general').fadeIn('fast');
        } else {
            $('#seccion-checklist-adjuntos, #bloque-detalle-especifico, #bloque-detalle-general').hide();
        }
    });

    // Cambiar dinámicamente el texto del archivo cargado
    $(document).on('change', '.custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // CAPTURAR EL ENVÍO DEL FORMULARIO
    $('#formRegistrarTG').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        console.log("=== DEBUG: DATOS QUE ENVÍA EL FORMULARIO ===");
        for (var pair of formData.entries()) {
            if (pair[1] instanceof File) {
                console.log(pair[0] + ": [Archivo] -> Name: " + pair[1].name + " | Size: " + pair[1].size + " bytes");
            } else {
                console.log(pair[0] + ": " + pair[1]);
            }
        }
        console.log("=============================================");

        $.ajax({
            url: '../sdo-includes/ordenes_trabajos/ot_funcion_crear.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
            },
            success: function (response) {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Registro');

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Excelente!',
                        text: response.message || 'La Orden de Trabajo se registró correctamente.',
                        timer: 2500,
                        showConfirmButton: false
                    });

                    $('#formRegistrarTG')[0].reset();
                    $('.select2').val('').trigger('change.select2');

                    if ($.fn.DataTable.isDataTable('#tableListOT')) {
                        $('#tableListOT').DataTable().ajax.reload(null, false);
                    }

                    $('#tab-listado-ot').tab('show');

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al registrar',
                        text: response.message || 'Hubo un problema en el servidor.'
                    });
                }
            },
            error: function (xhr, status, error) {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Registro');

                console.error("--- ERROR EN EL SERVIDOR (XHR) ---");
                console.error("Status:", status);
                console.error("Error:", error);
                console.error("Respuesta cruda del PHP:", xhr.responseText);
                console.error("----------------------------------");

                Swal.fire({
                    icon: 'error',
                    title: 'Error Crítico',
                    text: 'No se pudo comunicar con el servidor. Revisa la consola (F12) para ver la respuesta real del PHP.'
                });
            }
        });
    });
});