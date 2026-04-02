
<input type="hidden" id="idDeptoHT" name="idDeptoHT" value="<?php echo $idDepartamento; ?>">
<table id="miTabla" class="table table-bordered text-center">
    <thead class="bg-secondary">
    <tr>
        <th>N°</th>
        <th>FOLIO</th>
        <th>PERSONAL QUE REALIZA</th>
        <th>SOLICITANTE</th>
        <th>UNIDAD SOLICITANTE</th>
        <th>N° SERIE EQUIPO ASOCIADO</th>
        <th>NOMBRE DEL EQUIPO ASOCIADO</th>
        <th>N° INVENTARIO EQUIPO ASOCIADO</th>
        <th>TICKET ASOCIADO</th>
        <th>FECHA</th>
        <th>DESCARGAR ARCHIVO</th>
        <th>VISUALIZAR/MODIFICAR HT</th>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot class="bg-secondary">
    <tr>
        <th>N°</th>
        <th>FOLIO</th>
        <th>PERSONAL QUE REALIZA</th>
        <th>SOLICITANTE</th>
        <th>UNIDAD SOLICITANTE</th>
        <th>N° SERIE EQUIPO ASOCIADO</th>
        <th>NOMBRE DEL EQUIPO ASOCIADO</th>
        <th>N° INVENTARIO EQUIPO ASOCIADO</th>
        <th>TICKET ASOCIADO</th>
        <th>FECHA</th>
        <th>DESCARGAR ARCHIVO</th>
        <th>VISUALIZAR/MODIFICAR HT</th>
    </tr>
    </tfoot>
</table>
<script>
    $(document).ready(function () {
        var miTabla = $('#miTabla').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "ajax-ht-listado.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.idDepartamento = $('#idDeptoHT').val(); // Pasa el valor seleccionado del select como parámetro
                },
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Devuelve el número de fila (empezando desde 1)
                    }
                },
                {"data": "folioOrden"},
                {"data": "nomTecnicoRecepcionador"},
                {"data": "nomSolicitante"},
                {"data": "recintoOrden"},
                {"data": "numeroSerie"},
                {"data": "nombreEquipo"},
                {"data": "numeroInventario"},
                {"data": "ticketAsociado"},
                {"data": "fechaOrden"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.cantidad == 0) {
                            return 'Sin Archivos Adjuntos';
                        } else {
                            return '' +
                                '<form style="margin: 0; text-align: center;" class="" action="ordenes-listado-general.php" method="post">' +
                                '<input type="hidden" name="idOrden" value="'+row.idOrden+'">' +
                                '<input type="hidden" name="folio" value="'+row.folioOrden+'">' +
                                '<input type="submit" id="inputMozi" class="btn btn-block btn-warning" value="Descargar Archivos Adjuntos">' +
                                '</form>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '' +
                            '<div style="text-align: center;">' +
                            // Botón para ver
                            '<form style="display: inline-block; margin: 0;" target="_blank" action="../../sdo-pdf/SDO-OrdenTrabajoGeneral-Inge-Clinica.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>' +
                            '</form>' +
                            // Espaciado entre los botones
                            '&nbsp;&nbsp;&nbsp;' +
                            // Botón para modificar
                            '<form style="display: inline-block; margin: 0;" action="ordenes-modificar-general-inge-clinica.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></button>' +
                            '</form>' +
                            '</div>';
                    }
                }

                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            },

            // Filtro por numeroSerie (en initComplete)
            "initComplete": function () {
                var table = this; // Se hace referencia a la instancia de DataTable

                // Aplicar filtro de la columna "numeroSerie"
                table.api().columns([5]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Numero de Serie</option></select>')
                        .appendTo($(column.header()).empty()) // Limpiar y agregar el select al encabezado
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    // Agregar las opciones únicas para el filtro de numeroSerie
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>

