$(function() {
    var tablaComb;
    var miGraficoComb;

    // Inicializar Tabla
    tablaComb = $('#tablaCombustible').DataTable({
        "ordering": false,
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.2/i18n/es-ES.json" }
    });

    function cargarCombustible() {
        const mes = $('#filtroMesComb').val();
        const anio = $('#filtroAnioComb').val();

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_dashboard_combustible_datos.php',
            type: 'POST',
            data: { mes: mes, anio: anio },
            dataType: 'json',
            success: function(data) {
                tablaComb.clear();
                let totalGasto = 0;
                let etiquetas = [];
                let valoresGasto = [];

                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        totalGasto += item.gasto_total;
                        etiquetas.push(item.patente);
                        valoresGasto.push(item.gasto_total);

                        tablaComb.row.add([
                            `<b>${item.nomVehiculo}</b><br><small>${item.patente}</small>`,
                            item.cargas,
                            item.txt_litros,
                            `<b class="text-danger">${item.txt_gasto}</b>`
                        ]);
                    });
                }
                tablaComb.draw();
                $('#txtGastoTotalFlota').text('$' + new Intl.NumberFormat('es-CL').format(totalGasto));
                renderizarGraficoComb(etiquetas, valoresGasto);
            }
        });
    }

    function renderizarGraficoComb(labels, datos) {
        const ctx = document.getElementById('graficoCombustible').getContext('2d');
        if (miGraficoComb) miGraficoComb.destroy();

        miGraficoComb = new Chart(ctx, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: labels,
                datasets: [{
                    label: 'Gasto en Pesos ($)',
                    data: datos,
                    fill: true,
                    backgroundColor: 'rgba(220, 53, 69, 0.1)', // Rojo suave
                    borderColor: '#dc3545',
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#dc3545',
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        backgroundColor: '#343a40',
                        color: 'white',
                        borderRadius: 4,
                        formatter: (val) => '$' + new Intl.NumberFormat('es-CL').format(val)
                    }
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { top: 30 } },
                plugins: { legend: { display: false } }
            }
        });
    }

    // Disparadores
    $('#filtroAnioComb, #filtroMesComb').on('change', cargarCombustible);

    // Carga inicial al mostrar el tab
    $('a[href="#tab-dashboard-comb"]').on('shown.bs.tab', function () {
        cargarCombustible();
    });
});