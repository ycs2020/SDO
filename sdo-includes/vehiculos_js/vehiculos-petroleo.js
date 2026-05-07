$(document).ready(function () {
    const idMovil = $('#idMovilAjax').val();

    const tableGasolina = $('#table_gasolina').DataTable($.extend({}, dataTableConfig, {
        ajax: {
            url: "../sdo-includes/vehiculos/vehiculos_petroleo_listado.php",
            type: "POST",
            data: { idMovilAjax: idMovil },
            dataSrc: ""
        },
        columns: [
            { "data": "fechaFormateada" },
            { "data": "nomConductorFormateada" },
            { "data": "numGuiaFormateada" },
            { "data": "cantidadLitrosFormateada" },
            { "data": "valorUnitarioFormateada" },
            { "data": "totalSinIvaFormateada" },
            { "data": "IvaCalculadoFormateada" },
            { "data": "totalConIvaFormateada" },
            { "data": "btnModificar" },
            { "data": "btnEliminar" }
        ],
        "order": [[ 0, "desc" ]] // Ordenar por fecha y hora más reciente por defecto
    }));

    // Agregar carga
    $('#formCargaGasolina').on('submit', function (e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>...');

        fetch('../sdo-includes/vehiculos/vehiculos_petroleo_agregar.php', {
            method: 'POST',
            body: new FormData(this)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    $('#gasolinaModal').modal('hide');
                    this.reset();

                    // Si usas Flatpickr, resetear el valor a la fecha actual después de limpiar el form
                    if (this.querySelector('[name="fechaReporte"]')._flatpickr) {
                        this.querySelector('[name="fechaReporte"]')._flatpickr.setDate(new Date());
                    }

                    tableGasolina.ajax.reload(null, false);
                    Swal.fire('¡Registrado!', data.message, 'success');
                }
            })
            .finally(() => btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Carga'));
    });

    // Abrir Modal Editar
    $(document).on('click', '.btn-mod-petro', function() {
        const data = tableGasolina.row($(this).closest('tr')).data();

        // CRÍTICO: Formatear fecha para datetime-local (reemplazar espacio por T)
        // El input espera YYYY-MM-DDTHH:MM
        if (data.fechaRegistro) {
            let fechaISO = data.fechaRegistro.replace(" ", "T").substring(0, 16);
            const inputMod = document.querySelector('#fechaReporteMod');

            $(inputMod).val(fechaISO);

            // Si Flatpickr está activo, actualizar el calendario visual
            if (inputMod._flatpickr) {
                inputMod._flatpickr.setDate(fechaISO);
            }
        }

        $('#numGuiaMod').val(data.numGuia);
        $('#cantidadLitrosMod').val(data.cantidadLitros);
        $('#valorUnitMod').val(data.valorUnitario);
        $('#nomConductorMod').val(data.nomConductor);
        $('#btnGuardarEdicionPetroleo').data('id', $(this).data('id'));
        $('#modalEditarPetroleo').modal('show');
    });

    // Guardar Edición
    $('#btnGuardarEdicionPetroleo').on('click', function() {
        const idPetroleo = $(this).data('id');
        const btn = $(this);

        const datos = {
            idPetroleo: idPetroleo,
            fechaReporte: $('#fechaReporteMod').val(), // Enviará "YYYY-MM-DDTHH:MM"
            numGuia: $('#numGuiaMod').val(),
            cantidadLitros: $('#cantidadLitrosMod').val(),
            valorUnitario: $('#valorUnitMod').val(),
            nomConductor: $('#nomConductorMod').val()
        };

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $.post('../sdo-includes/vehiculos/vehiculos_petroleo_modificar.php', datos, function(res) {
            if (res.success) {
                $('#modalEditarPetroleo').modal('hide');
                tableGasolina.ajax.reload(null, false);
                Swal.fire('¡Actualizado!', 'El registro de petróleo ha sido modificado.', 'success');
            } else {
                Swal.fire('Error', res.message || 'Error al actualizar.', 'error');
            }
        }, 'json')
            .fail(() => Swal.fire('Error', 'Error de conexión con el servidor.', 'error'))
            .always(() => btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Cambios'));
    });

    // Eliminar (Sin cambios necesarios, pero optimizado)
    $(document).on('click', '.btn-delete-petro', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer y el registro de combustible será eliminado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../sdo-includes/vehiculos/vehiculos_petroleo_eliminar.php', { idPetroleo: id }, function(res) {
                    if (res.success) {
                        tableGasolina.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: res.message || 'El registro ha sido eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', res.message || 'No se pudo eliminar el registro.', 'error');
                    }
                }, 'json');
            }
        });
    });
});