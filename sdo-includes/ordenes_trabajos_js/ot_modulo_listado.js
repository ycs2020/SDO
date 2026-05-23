$(document).ready(function () {
    // Duplicar header para montar selectores de filtrado
    $('#tableListOT thead tr').clone(true).addClass('fila-filtros').appendTo('#tableListOT thead');

    var table = $('#tableListOT').DataTable({
        ...dataTableConfig,
        "ordering": false,
        "order": [[0, "desc"]],
        "ajax": {
            "url": "../sdo-includes/ordenes_trabajos/ot_ejecucion_listado.php",
            "type": "POST",
            "dataSrc": "data",
            "data": function (d) {
                // 'd' contiene los parámetros por defecto de DataTables (paginación, búsquedas, etc.)
                // Aquí añadimos tu variable personalizada:
                d.rolBtn = "detalle";

                // Si el valor viene de una variable de JS o PHP, puedes pasarlo directamente:
                // d.rolBtn = rolUsuario;
            }
        },
        "columns": [
            { "data": "fecha_programacion" },
            { "data": "folio_trabajo", "className": "text-center" },
            { "data": "turno_trabajo" },
            { "data": "departamento" },
            { "data": "solicitante" },
            { "data": "tecnico_responsable" },
            { "data": "estado", "className": "text-center" },
            { "data": "descripcion" },
            { "data": "detalles", "className": "text-center" }
        ],
        initComplete: function () {
            var api = this.api();
            var columnasConFiltro = {
                2: "🔍 TURNO",
                3: "🔍 DEPARTAMENTO",
                4: "🔍 SOLICITANTE",
                5: "🔍 TÉCNICO",
                6: "🔍 ESTADO"
            };

            api.columns().eq(0).each(function (colIdx) {
                var cell = $('.fila-filtros th').eq(colIdx);

                if (columnasConFiltro.hasOwnProperty(colIdx)) {
                    var placeholder = columnasConFiltro[colIdx];
                    var select = $('<select class="form-control text-center font-weight-bold text-uppercase"><option value="">' + placeholder + '</option></select>')
                        .appendTo(cell.empty())
                        .on('change', function () {
                            var val = $(this).val();
                            api.column(colIdx).search(val ? val : '', false, false).draw();
                        });

                    var elementosAgregados = [];
                    api.column(colIdx).data().unique().sort().each(function (d) {
                        if (d) {
                            var tmp = document.createElement("DIV");
                            tmp.innerHTML = d;
                            var textClean = (tmp.textContent || tmp.innerText || "").trim();

                            if (textClean && elementosAgregados.indexOf(textClean) === -1) {
                                elementosAgregados.push(textClean);
                                select.append('<option value="' + textClean + '">' + textClean + '</option>');
                            }
                        }
                    });
                } else {
                    cell.empty();
                }
            });
        }
    });

    // Ajuste proporcional al alternar entre pestañas
    $('a[data-toggle="pill"]').on('shown.bs.tab', function () {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});