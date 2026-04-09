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
            { "data": "numGuiaFormateada" },
            { "data": "cantidadLitrosFormateada" },
            { "data": "valorUnitarioFormateada" },
            { "data": "totalSinIvaFormateada" },
            { "data": "IvaCalculadoFormateada" },
            { "data": "totalConIvaFormateada" },
            { "data": "btnModificar" },
            { "data": "btnEliminar" }
        ]
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
                    tableGasolina.ajax.reload(null, false);
                    Swal.fire('¡Registrado!', data.message, 'success');
                }
            })
            .finally(() => btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Carga'));
    });

    // Abrir Modal Editar
    $(document).on('click', '.btn-mod-petro', function() {
        const data = tableGasolina.row($(this).closest('tr')).data();
        $('#fechaReporteMod').val(data.fechaRegistro);
        $('#numGuiaMod').val(data.numGuia);
        $('#cantidadLitrosMod').val(data.cantidadLitros);
        $('#valorUnitMod').val(data.valorUnitario);
        $('#btnGuardarEdicionPetroleo').data('id', $(this).data('id'));
        $('#modalEditarPetroleo').modal('show');
    });

    // Guardar Edición
    $('#btnGuardarEdicionPetroleo').on('click', function() {
        const idPetroleo = $(this).data('id');
        const datos = {
            idPetroleo: idPetroleo,
            fechaReporte: $('#fechaReporteMod').val(),
            numGuia: $('#numGuiaMod').val(),
            cantidadLitros: $('#cantidadLitrosMod').val(),
            valorUnitario: $('#valorUnitMod').val()
        };

        $.post('../sdo-includes/vehiculos/vehiculos_petroleo_modificar.php', datos, function(res) {
            if (res.success) {
                $('#modalEditarPetroleo').modal('hide');
                tableGasolina.ajax.reload(null, false);
                Swal.fire('¡Actualizado!', '', 'success');
            }
        }, 'json');
    });

    // Eliminar
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
                        // Recargar la tabla
                        tableGasolina.ajax.reload(null, false);

                        // Mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: res.message || 'El registro ha sido eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        // Mensaje de error si el servidor responde con success: false
                        Swal.fire('Error', res.message || 'No se pudo eliminar el registro.', 'error');
                    }
                }, 'json').fail(function() {
                    // Mensaje de error si hay un fallo de red o error 500
                    Swal.fire('Error', 'Ocurrió un error crítico en el servidor.', 'error');
                });
            }
        });
    });
});