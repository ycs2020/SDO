
<input type="hidden" id="idDeptoHT" name="idDeptoHT" value="<?php echo $idDepartamento; ?>">
<table id="miTabla" class="table table-bordered text-center">
    <thead class="bg-secondary">
    <tr>
        <th>N°</th>
        <th>FOLIO</th>
        <th>PERSONAL QUE REALIZA</th>
        <th>SOLICITANTE</th>
        <th>TICKET ASOCIADO</th>
        <th>FECHA</th>
        <th>TIEMPO</th>
        <th>DESCARGAR ARCHIVO</th>
        <th>VISUALIZAR HT</th>
        <th>MODIFICAR HT</th>
        <th>VISADO HT</th>
    </tr>
    </thead>
    <tbody></tbody>
    <tfoot class="bg-secondary">
    <tr>
        <th>N°</th>
        <th>FOLIO</th>
        <th>PERSONAL QUE REALIZA</th>
        <th>SOLICITANTE</th>
        <th>TICKET ASOCIADO</th>
        <th>FECHA</th>
        <th>TIEMPO</th>
        <th>DESCARGAR ARCHIVO</th>
        <th>VISUALIZAR HT</th>
        <th>MODIFICAR HT</th>
        <th>VISADO HT</th>
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
                {"data": "ticketAsociado"},
                {"data": "fechaOrden"},
                {"data": "diferencia"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.cantidad == 0) {
                            return 'Sin Archivos Adjuntos';
                        }else{
                            return '' +
                                '<form style="margin: 0; text-align: center;" class="" action="ordenes-listado-general.php" method="post">' +
                                '<input type="hidden" name="idOrden" value="'+row.idOrden+'">' +
                                '<input type="hidden" name="folio" value="'+row.folioOrden+'">' +
                                '<input type="submit" id="inputMozi" class="btn btn-block btn-warning" value="Descargar Archivos Adjuntos">' +
                                '</form>'
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form  style="margin: 0; text-align: center;" class="" target="_blank" action="../../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<center>' +
                            '<button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>' +
                            '</center>' +
                            '</form>';
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        // Agrega el formulario y el botón de visualización para cada fila
                        return '' +
                            '<form  style="margin: 0; text-align: center;" class="" action="ordenes-modificar-general.php" method="post">' +
                            '<input type="hidden" name="idOrden" value="' + row.idOrden + '">' +
                            '<center>' +
                            '<button type="submit" class="btn btn-warning"><i class="fas fa-pen"></i></button>' +
                            '</center>' +
                            '</form>';
                    }

                },
                {
                    "data": null,
                    "render": function (data, type, row) {

                        if (row.isVisado == 1) {
                            return 'HT se encuentra Visada.';
                        } else {
                            return '<button id="buttonModalVisar" type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalVisar' + row.idOrden + '">' +
                                '<i class="fas fa-check"></i>' +
                                '</button>' +
                                '<div class="modal fade" id="exampleModalVisar' + row.idOrden + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
                                '<div class="modal-dialog d-flex align-items-center justify-content-center" role="document">' +
                                '<div class="modal-content">' +
                                '<div class="modal-header bg-gradient-primary">' +
                                '<h5 class="modal-title" id="exampleModalLabel">Visar la HT</h5>' +
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>' +
                                '<form action="../../sdo-funciones/visar-ht-original.php" method="POST">' +
                                '<div class="modal-body">' +
                                '<input type="hidden" name="idOrden" id="idOrden" value="' + row.idOrden + '">' +
                                '<label class="w-auto">Ingrese la contraseña para ingresar a la plataforma</label>' +
                                '<input type="password" class="form-control" id="contra" name="contra">' +
                                '</div>' +
                                '<div class="modal-footer">' +
                                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                                '<button type="submit" class="btn btn-primary">Guardar</button>' +
                                '</div>' +
                                '</form>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    }
                }



                // Agrega más columnas según sea necesario
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css('vertical-align', 'middle'); // Aplica el estilo de centrado vertical a todas las celdas
                $('td:eq(0)', row).html(dataIndex + 1); // Actualiza el contenido de la primera celda (columna "N°")
            }
        });
    });
</script>