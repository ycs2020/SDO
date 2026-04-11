$(function() {
    // Variable global para el gráfico
    var miGraficoKms;

    // 1. Inicializar DataTable
    var tablaKms = $('#tablaKilometraje').DataTable({
        "paging": true,
        "iDisplayLength": 10,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sSearch":         "Buscar:",
            "oPaginate": {
                "sFirst": "Primero", "sLast": "Último", "sNext": "Siguiente", "sPrevious": "Anterior"
            }
        }
    });

    // 2. Función para inicializar/actualizar el Gráfico (Estilo Smooth Area)
    function actualizarGrafico(labels, valores) {
        if (typeof Chart === 'undefined') return;

        const ctx = document.getElementById('graficoKms').getContext('2d');
        if (miGraficoKms) { miGraficoKms.destroy(); }

        miGraficoKms = new Chart(ctx, {
            type: 'line',
            // IMPORTANTE: Registrar el plugin aquí
            plugins: [ChartDataLabels],
            data: {
                labels: labels,
                datasets: [{
                    label: 'Recorrido (Km)',
                    data: valores,
                    fill: true,
                    backgroundColor: 'rgba(102, 16, 242, 0.1)',
                    borderColor: '#6610f2',
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#6610f2',
                    pointBorderColor: '#fff',
                    borderWidth: 3,
                    // Configuración específica de las etiquetas (globos)
                    // ... dentro de la configuración del dataset en miGraficoKms ...
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        backgroundColor: '#343a40',
                        borderRadius: 4,
                        color: 'white',
                        font: { weight: 'bold', size: 11 },
                        padding: 4,
                        formatter: function(value) {
                            // Formatear con separador de miles puntos (.) para Chile
                            return new Intl.NumberFormat('es-CL').format(Math.round(value));
                        }
                    }
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { top: 30 } // Espacio extra arriba para que no se corten los globos
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true } // Mantenemos el tooltip por si acaso
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('es-CL').format(value) + ' Km';
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Función para cargar meses
    function inicializarDashboard() {
        const anio = $('#filtroAnio').val();
        if(!anio) return;

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_obtener_meses_disponibles.php',
            type: 'POST',
            data: { anio: anio },
            success: function(html) {
                $('#filtroMes').html(html);
                $('#filtroMes').trigger('change.select2');
                cargarKilometraje();
            }
        });
    }

    // 4. Función para traer los datos y alimentar Tabla + Gráfico
    function cargarKilometraje() {
        const mes = $('#filtroMes').val();
        const anio = $('#filtroAnio').val();
        if (mes === null || !anio) return;

        const esAnual = (mes == "0");
        const labelPeriodo = esAnual ? "AÑO" : "MES";

        $(tablaKms.column(1).header()).text('KM INICIO ' + labelPeriodo);
        $(tablaKms.column(2).header()).text('KM FIN ' + labelPeriodo);
        $(tablaKms.column(4).header()).text('ESTADO CIERRE ' + labelPeriodo);
        $('#tablaKilometraje tfoot td.text-right').text('TOTAL ' + (esAnual ? 'ANUAL:' : 'MENSUAL:'));

        $.ajax({
            url: '../sdo-includes/vehiculos/vehiculos_dashboard_kilometraje_datos.php',
            type: 'POST',
            data: { mes: mes, anio: anio },
            dataType: 'json',
            beforeSend: function() {
                $('#txtTotalFlota').html('<i class="fas fa-sync fa-spin"></i>');
            },
            success: function(data) {
                tablaKms.clear();
                let totalFlota = 0;

                // Arrays para el gráfico
                let etiquetas = [];
                let valores = [];

                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        let totalKms = parseFloat(item.total_periodo || item.total_mes) || 0;
                        totalFlota += totalKms;

                        // Datos para gráfico (Patente y Kilómetros)
                        etiquetas.push(item.patenteVehiculo);
                        valores.push(totalKms);

                        tablaKms.row.add([
                            `<b>${item.nomVehiculo}</b><br><small class="text-black">${item.patenteVehiculo}</small>`,
                            new Intl.NumberFormat('es-CL').format(item.km_inicial) + ' Km',
                            new Intl.NumberFormat('es-CL').format(item.km_final) + ' Km',
                            `<b class="text-purple" style="font-size:1.1rem">${new Intl.NumberFormat('es-CL').format(totalKms)} Km</b>`,
                            item.badge_periodo || item.badge_mes,
                            item.badge_actual
                        ]);
                    });
                }
                tablaKms.draw();
                $('#txtTotalFlota, #totalFilaKms').text(new Intl.NumberFormat('es-CL').format(totalFlota) + ' Km');

                // Chequeo de seguridad para el gráfico
                if ($('#graficoKms').length > 0 && data.length > 0) {
                    actualizarGrafico(etiquetas, valores);
                } else {
                    console.warn("No se encontró el elemento canvas o no hay datos para el gráfico.");
                }
            }
        });
    }

    // --- DISPARADORES ---
    $('#filtroAnio').on('change', inicializarDashboard);
    $('#filtroMes').on('change', cargarKilometraje);

    $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ( $.fn.DataTable.isDataTable( '#tablaKilometraje' ) ) {
            tablaKms.columns.adjust();
            if (tablaKms.responsive) { tablaKms.responsive.recalc(); }
        }
        inicializarDashboard();
    });

    inicializarDashboard();
});