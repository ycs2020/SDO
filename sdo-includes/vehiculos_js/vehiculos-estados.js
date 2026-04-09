$(document).ready(function () {
    const idMovil = $('#idMovilAjax').val();

    const tableEstado = $('#table_estado').DataTable($.extend({}, dataTableConfig, {
        ajax: {
            url: "../sdo-includes/vehiculos/vehiculos_estado_listado.php",
            type: "POST",
            data: { idMovilAjax: idMovil },
            dataSrc: ""
        },
        columns: [
            { "data": "fechaFormateada" },
            { "data": "estadoVehiculoBadge" },
            {
                "data": "motivo",
                "render": data => data ? data.toUpperCase() : 'SIN MOTIVO'
            }
        ]
    }));

    $('#formNuevoEstado').submit(function (e) {
        e.preventDefault();
        fetch('../sdo-includes/vehiculos/vehiculos_estado_modificar.php', {
            method: 'POST',
            body: new FormData(this)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    $('#estadoModal').modal('hide');
                    this.reset();
                    Swal.fire({ title: '¡Éxito!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
                    tableEstado.ajax.reload(null, false);
                }
            });
    });
});