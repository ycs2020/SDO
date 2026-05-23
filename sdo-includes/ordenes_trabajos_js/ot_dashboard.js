$(function() {
    var tablaDashboardOT;
    var miGraficoDepartamentosOT;

    function initTablaResumenOT() {
        if ($.fn.DataTable.isDataTable('#tablaResumenOT')) {
            $('#tablaResumenOT').DataTable().destroy();
        }

        tablaDashboardOT = $('#tablaResumenOT').DataTable({
            "paging": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "autoWidth": false,
            "responsive": true,
            "data": [],
            "language": {
                "sSearch": "Buscar área:",
                "sLengthMenu": "Mostrar _MENU_ áreas",
                "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ áreas operativas",
                "sZeroRecords": "No hay órdenes de trabajo para el periodo seleccionado"
            },
            "columnDefs": [
                { "targets": "_all", "defaultContent": "-" }
            ]
        });
    }

    function cargarMesesDisponiblesOT() {
        const anio = $('#filtroAnioOT').val();
        if (!anio) return;

        $.ajax({
            url: '../sdo-includes/ordenes_trabajos/ot_dashboard_datos.php',
            type: 'POST',
            data: { accion: 'cargarMeses', anio: anio },
            dataType: 'json',
            success: function(response) {
                $('#filtroMesOT').html(response.html);
                cargarDatosDashboardOT();
            }
        });
    }

    function cargarDatosDashboardOT() {
        const anio = $('#filtroAnioOT').val();
        const mes = $('#filtroMesOT').val();

        if (mes === null) return;

        $.ajax({
            url: '../sdo-includes/ordenes_trabajos/ot_dashboard_datos.php',
            type: 'POST',
            data: { accion: 'cargarDatos', anio: anio, mes: mes },
            dataType: 'json',
            beforeSend: function() {
                $('#txtTotalOTs').html('<i class="fas fa-sync fa-spin text-muted"></i> Cargando...');
            },
            success: function(response) {
                if (!tablaDashboardOT) initTablaResumenOT();

                tablaDashboardOT.clear();

                let etiquetasGrafico = [];
                let datosGrafico = [];
                let sumaTotal = response.total_general || 0;

                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        etiquetasGrafico.push(item.departamento);
                        datosGrafico.push(item.total);

                        let porcentaje = sumaTotal > 0 ? ((item.total / sumaTotal) * 100).toFixed(1) : 0;

                        tablaDashboardOT.row.add([
                            `<b class="text-secondary">${item.departamento}</b>`,
                            `<span class="badge badge-primary" style="font-size:14px;">${item.total}</span>`,
                            `<b>${porcentaje}%</b>`
                        ]);
                    });
                }

                tablaDashboardOT.draw();
                $('#txtTotalOTs').html(new Intl.NumberFormat('es-CL').format(sumaTotal) + ' OTs');
                renderizarGraficoDepartamentosOT(etiquetasGrafico, datosGrafico);
            },
            error: function() {
                $('#txtTotalOTs').html('<span class="text-danger">Error de Conexión</span>');
            }
        });
    }

    function renderizarGraficoDepartamentosOT(labels, datos) {
        const canvas = document.getElementById('graficoDepartamentosOT');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        if (miGraficoDepartamentosOT) miGraficoDepartamentosOT.destroy();

        // CONFIGURACIÓN CORREGIDA: Texto negro flotando ARRIBA de la barra
        const dLConfig = {
            display: true,
            color: '#343a40', // Color gris muy oscuro (Casi negro) para máximo contraste
            font: { weight: 'bold', size: 11 },
            anchor: 'end',    // Anclado al final de la barra
            align: 'end',     // IMPORTANTE: Alineado hacia AFUERA (arriba) de la barra
            offset: 4,        // Separación de 4px para que no toque la barra
            formatter: (val) => val > 0 ? val : ''
        };

        miGraficoDepartamentosOT = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Órdenes Emitidas',
                    data: datos,
                    backgroundColor: 'rgba(0, 123, 255, 0.85)',
                    borderColor: '#007bff',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grace: '15%' // IMPORTANTE: Agrega 15% de altura extra al gráfico para que quepan los números arriba
                    },
                    x: { ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 45 } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true },
                    datalabels: dLConfig
                }
            },
            plugins: [ChartDataLabels]
        });
    }


    // --- DISPARADORES Y EVENTOS AUTOMÁTICOS ---
    initTablaResumenOT();

    $('#filtroAnioOT').on('change', function() {
        cargarMesesDisponiblesOT();
    });

    $('#filtroMesOT').on('change', function() {
        cargarDatosDashboardOT();
    });

    $('a[data-toggle="tab"], button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        let target = $(e.target).attr("href") || $(e.target).data('bs-target');

        if (target === '#content-dashboard-ot' || $(e.target).text().trim().toLowerCase() === 'dashboard') {
            if (tablaDashboardOT) {
                tablaDashboardOT.columns.adjust().responsive.recalc();
            }
            cargarMesesDisponiblesOT();
        }
    });
});