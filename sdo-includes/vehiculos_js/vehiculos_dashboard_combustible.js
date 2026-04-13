$(function() {
    var tablaComb;
    var miGraficoComb;

    // 1. Definimos la función de inicialización para poder reutilizarla
    function initTablaCombustible() {
        if ($.fn.DataTable.isDataTable('#tablaPetroleoResumen')) {
            $('#tablaPetroleoResumen').DataTable().destroy();
        }

        tablaComb = $('#tablaPetroleoResumen').DataTable({
            "paging": true,
            "ordering": false,
            "autoWidth": false,
            "responsive": true,
            "data": [], // Empezamos vacío para evitar el error de parámetros
            "language": {
                "sSearch": "Buscar:",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No hay datos para el periodo seleccionado"
            },
            "columnDefs": [
                { "targets": "_all", "defaultContent": "-" }
            ]
        });
    }

    // 2. Ejecutar inicialización inmediata
    initTablaCombustible();

    function inicializarDashboardComb() {
        const anio = $('#filtroAnioComb').val();
        if (!anio) return;

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_combustible_obtener_meses.php',
            type: 'POST',
            data: { anio: anio },
            success: function(html) {
                // Actualizamos el HTML de los meses
                $('#filtroMesComb').html(html);
                // Llamamos a cargar los datos una sola vez
                cargarCombustible();
            }
        });
    }

    function cargarCombustible() {
        const mes = $('#filtroMesComb').val();
        const anio = $('#filtroAnioComb').val();

        // Si el select de meses está vacío (mientras carga el AJAX anterior), salimos
        if (mes === null) return;

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_dashboard_combustible_datos.php',
            type: 'POST',
            data: { mes: mes, anio: anio },
            dataType: 'json',
            beforeSend: function() {
                $('#txtGastoTotalFlota').html('<i class="fas fa-sync fa-spin"></i>');
            },
            success: function(data) {
                // Si la tabla se perdió por alguna razón, la recreamos
                if (!tablaComb) initTablaCombustible();

                tablaComb.clear();

                let totalGasto = 0;
                let etiquetas = [], valores = [];

                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        totalGasto += parseFloat(item.gasto_total) || 0;
                        etiquetas.push(item.patenteVehiculo);
                        valores.push(item.gasto_total);

                        // Agregamos las 4 columnas definidas en el HTML
                        tablaComb.row.add([
                            `<b>${item.nomVehiculo}</b><br><small>${item.patenteVehiculo}</small>`,
                            item.cargas || 0,
                            new Intl.NumberFormat('es-CL').format(item.total_litros || 0) + ' Lts',
                            `<b class="text-danger">$${new Intl.NumberFormat('es-CL').format(item.gasto_total || 0)}</b>`
                        ]);
                    });
                }

                tablaComb.draw();
                $('#txtGastoTotalFlota').text('$' + new Intl.NumberFormat('es-CL').format(totalGasto));
                renderizarGraficoComb(etiquetas, valores);
            }
        });
    }

    function renderizarGraficoComb(labels, datos) {
        const canvas = document.getElementById('graficoCombustible');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        if (miGraficoComb) miGraficoComb.destroy();

        miGraficoComb = new Chart(ctx, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: labels,
                datasets: [{
                    label: 'Gasto Total',
                    data: datos,
                    fill: true,
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderColor: '#dc3545',
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        backgroundColor: '#343a40',
                        color: 'white',
                        borderRadius: 4,
                        font: { weight: 'bold' },
                        formatter: (val) => '$' + new Intl.NumberFormat('es-CL').format(val)
                    }
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { top: 30 } },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: (v) => '$' + v } }
                }
            }
        });
    }

    // --- DISPARADORES ---

    $('#filtroAnioComb').on('change', function() {
        inicializarDashboardComb();
    });

    $('#filtroMesComb').on('change', function() {
        cargarCombustible();
    });

    // Manejo de Tabs: Detectar cuando se muestra el tab de combustible
    $('a[data-toggle="tab"], a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        let target = $(e.target).attr("href") || $(e.target).attr("id");
        if (target.includes('comb')) {
            // Ajustar visualmente la tabla (importante en tabs)
            if (tablaComb) {
                tablaComb.columns.adjust().responsive.recalc();
            }
            inicializarDashboardComb();
        }
    });

    // Carga inicial por si es el tab activo por defecto
    inicializarDashboardComb();
});