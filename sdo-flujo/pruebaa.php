<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Sismos y Clima</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #contadorSismos, #contadorClima {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h1>Últimos Sismos</h1>
<div id="contadorSismos">Actualización en: 180 segundos</div>
<div id="sismos"></div>

<h1>Condiciones Climáticas</h1>
<div id="contadorClima">Actualización en: 180 segundos</div>
<div id="clima"></div>

<script>
    let interval = 180; // Intervalo de actualización en segundos (5 minutos)
    let contadorSismos = interval;
    let contadorClima = interval;

    function fetchSismos() {
        $.ajax({
            url: 'sdo-includes/sismos_obtener.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#sismos').html('');
                if (data.length > 0) {
                    var html = '<ul>';
                    data.forEach(function(sismo) {
                        html += '<li>' + sismo.Fecha + ' - Magnitud: ' + sismo.Magnitud + ' - Lugar: ' + sismo.RefGeografica + '</li>';
                    });
                    html += '</ul>';
                    $('#sismos').html(html);
                } else {
                    $('#sismos').html('<p>No hay sismos recientes.</p>');
                }
            },
            error: function() {
                $('#sismos').html('<p>Error al obtener los datos.</p>');
            }
        });

        // Reiniciar el contador después de la actualización
        contadorSismos = interval;
    }

    function fetchClima() {
        $.ajax({
            url: 'sdo-includes/clima_obtener.php', // URL del script PHP que hace la solicitud curl
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#clima').html('');
                if (data.Estacion) {
                    var html = '<ul>';
                    html += '<li>' + data.Estacion + ' - ' + data.Temp + '°C - ' + data.Humedad + '% Humedad</li>';
                    html += '</ul>';
                    $('#clima').html(html);
                } else {
                    $('#clima').html('<p>No hay datos climáticos recientes.</p>');
                }
            },
            error: function() {
                $('#clima').html('<p>Error al obtener los datos.</p>');
            }
        });

        // Reiniciar el contador después de la actualización
        contadorClima = interval;
    }

    function updateCounters() {
        $('#contadorSismos').text('Actualización en: ' + contadorSismos + ' segundos');
        $('#contadorClima').text('Actualización en: ' + contadorClima + ' segundos');
        contadorSismos--;
        contadorClima--;
        if (contadorSismos < 0) {
            fetchSismos();
        }
        if (contadorClima < 0) {
            fetchClima();
        }
    }

    // Llamar a las funciones al cargar la página
    fetchSismos();
    fetchClima();

    // Actualizar cada segundo
    setInterval(updateCounters, 1000);
</script>
</body>
</html>
