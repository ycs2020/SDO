<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}
date_default_timezone_set('America/Santiago');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; de Noticias - SDO</title>

    <link rel="icon" href="../dist/img/controlCent-HBQP-120.png" type="image/icon type">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <style>
        .widget-user-2 {
            margin-bottom: 20px;
        }

        ,
        .table-custom img {
            max-width: 50px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
    ;

    <style>
        .container {
            width: 100%;
            margin: 0 auto;
        }
    </style>

</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; width: 100%; height: 100%; background-size: cover;">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
</div>

<section class="content">
    <div class="container-fluid mt-2">

        <div class="row" id="">
            <div class="col-md-6 col-12">
                <div class="row">
                    <!-- First Card -->
                    <div style="" class=" col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title">Pron&oacute;stico Meteorol&oacute;gico</h3>
                            </div>
                            <div class="card-body">
                                <script>
                                    (function (d, s, id) {
                                        if (d.getElementById(id)) {
                                            if (window.__TOMORROW__) {
                                                window.__TOMORROW__.renderWidget();
                                            }
                                            return;
                                        }
                                        const fjs = d.getElementsByTagName(s)[0];
                                        const js = d.createElement(s);
                                        js.id = id;
                                        js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";

                                        fjs.parentNode.insertBefore(js, fjs);
                                    })(document, 'script', 'tomorrow-sdk');
                                </script>

                                <div class="tomorrow"
                                        data-location-id="019092"
                                        data-language="ES"
                                        data-unit-system="METRIC"
                                        data-skin="dark"
                                        data-widget-type="upcoming"
                                        style="padding-bottom:22px;position:relative;"
                                >
                                    <a
                                            href="https://www.tomorrow.io/weather-api/"
                                            rel="nofollow noopener noreferrer"
                                            target="_blank"
                                            style="position: absolute; bottom: 0; transform: translateX(-50%); left: 50%;"
                                    >
                                        <img
                                                alt="Powered by the Tomorrow.io Weather API"
                                                src="https://weather-website-client.tomorrow.io/img/powered-by.svg"
                                                width="250"
                                                height="18"
                                        />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="card card-widget widget-user-2">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title">Sismología Chile</h3>
                            </div>
                            <div class="card-footer p-0">
                                <h5 id="contadorSismos" class="m-2 text-center" style="font-size: 11pt;">Actualización en: 180 segundos</h5>
                                <div id="scrollableTable" style="height: 680px; overflow-y: scroll;">
                                    <table class="table table-striped" id="sismos" border="1" cellpadding="3" style="width: 100%; font-size: 11pt; background-color: #e8effd">

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="card card-widget widget-user-2">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title">Noticias Chile</h3>
                            </div>
                            <div class="card-footer p-0">
                                <!--                                <h5 id="contadorSismos" class="m-2 text-center" style="font-size: 11pt;">Actualización en: 180 segundos</h5>-->
                                <div id="noticiasChileContainer">
                                    <div id="scrollableTable" style="height: 700px; overflow-y: scroll;">
                                        <table class="table table-bordered" id="noticiasChile" border="1" cellpadding="3" style="width: 100%; font-size: 11pt; background-color: #e8effd">

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CUMPLE -->

                </div>
            </div>


            <div class="col-md-6 col-12">
                <div class="row">
                    <div style="" class=" col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-center">
                                <h3 class="card-title">Noticias Internas</h3>
                                <h5 id="contador" class="mt-2">Próximo cambio en: <span id="tiempoRestante">10</span> segundos</h5>

                            </div>
                            <div class="card-body d-flex justify-content-center" id="images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>


    document.addEventListener('DOMContentLoaded', function () {
        let interval = 180; // Intervalo de actualización en segundos
        let contadorSismos = interval;

        var offset = 0; // Controla la cantidad de sismos ya cargados
        var limit = 8;  // La cantidad de sismos a cargar por cada solicitud
        var loading = false; // Control para evitar solicitudes duplicadas

        function fetchSismos(loadMore = false) {
            if (loading) return; // Evitar múltiples solicitudes si ya se está cargando
            loading = true;

            $.ajax({
                url: 'sdo-includes/sismos_obtener.php',
                method: 'GET',
                dataType: 'json',
                data: {
                    offset: offset,
                    limit: limit
                },
                success: function (data) {
                    console.log('Datos recibidos:', data); // Añade esto para depurar
                    var html = '';

                    if (data.length > 0) {
                        data.forEach(function (sismo) {
                            var colorTD = sismo.Magnitud >= 5 ? 'red' : '';
                            var bold = sismo.Magnitud >= 5 ? 'bold' : 'normal';

                            html += '<tr style="color: ' + colorTD + '"><td style=" vertical-align: middle;font-weight: ' + bold + '">' + sismo.Fecha + '<br>' + sismo.RefGeografica + '</td><td style="text-align: center; vertical-align: middle; font-weight: bold;">Magnitud: ' + sismo.Magnitud + '</td></tr>';
                        });

                        if (loadMore) {
                            $('#sismos').append(html);
                        } else {
                            $('#sismos').html(html);
                        }

                        offset += limit;
                    } else if (!loadMore) {
                        $('#sismos').html('<p>No hay sismos recientes.</p>');
                    }
                    loading = false;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud AJAX:', textStatus, errorThrown); // Añade esto para depurar
                    $('#sismos').html('<p>Error al obtener los datos.</p>');
                    loading = false;
                }
            });
        }


        function setupInfiniteScroll() {
            $('#scrollableTable').on('scroll', function () {
                var element = this;
                if (element.scrollHeight - element.scrollTop === element.clientHeight) {
                    fetchSismos(true); // Cargar más datos cuando se alcanza el final
                }
            });
        }

        $(document).ready(function () {
            fetchSismos(); // Carga inicial
            setupInfiniteScroll(); // Configurar desplazamiento infinito
        });

        function updateCounters() {
            $('#contadorSismos').text('Actualización en: ' + contadorSismos + ' segundos');
            contadorSismos--;
            if (contadorSismos < 0) {
                fetchSismos();
                contadorSismos = interval; // Reiniciar el contador
            }
        }

        setInterval(updateCounters, 1000); // Actualizar cada segundo

        var images = [
            'sdo-imagenes/Foto1.jpeg',
            'sdo-imagenes/Foto2.jpeg',
            'sdo-imagenes/Foto3.jpeg',
            'sdo-imagenes/Foto4.jpeg',
            'sdo-imagenes/Foto5.jpeg',
            'sdo-imagenes/Foto6.jpeg',
            'sdo-imagenes/Foto7.jpeg',
            'sdo-imagenes/Foto8.jpeg',
            'sdo-imagenes/Foto9.jpeg'
        ];

// Función para verificar la fecha actual
        function verificarFecha() {
            var today = new Date();
            var currentDay = today.getDate();
            var currentMonth = today.getMonth() + 1; // Los meses son base 0, por lo que sumamos 1

            console.log('Fecha actual: ' + currentDay + '/' + currentMonth);

            if (currentDay === 30 && currentMonth === 8) {
                if (!images.includes('sdo-imagenes/Francisco_Yanez.jpeg')) {
                    images.unshift('sdo-imagenes/Francisco_Yanez.jpeg');
                    console.log('Imagen Francisco_Yanez.jpeg agregada.');
                }
            } else {
                var index = images.indexOf('sdo-imagenes/Francisco_Yanez.jpeg');
                if (index !== -1) {
                    images.splice(index, 1);
                    console.log('Imagen Francisco_Yanez.jpeg eliminada.');
                }
            }
        }

// Llamar a la función al cargar la página
        verificarFecha();

// Crear el contenedor de las imágenes dentro del div #images
        var imageContainer = document.getElementById('images');
        imageContainer.innerHTML = '<img id="sliderImage" src="' + images[0] + '" class="img-fluid" alt="Acreditacion" />';

        var currentIndex = 0;
        var totalImages = images.length;

        var tiempoTotal = 10; // Tiempo en segundos
        var tiempoRestante = tiempoTotal;

        function changeImage() {
            currentIndex = (currentIndex + 1) % images.length;
            document.getElementById('sliderImage').src = images[currentIndex];
        }

        function updateTimer() {
            tiempoRestante--;
            if (tiempoRestante < 1) {
                tiempoRestante = tiempoTotal; // Reiniciar el contador a 10 segundos
                changeImage(); // Cambiar la imagen cuando el tiempo se agote
            }
            document.getElementById('tiempoRestante').innerText = tiempoRestante;
        }

// Verificar la fecha cada minuto para ver si se debe eliminar la imagen
        setInterval(verificarFecha, 60000); // Cada 60 segundos

        setInterval(updateTimer, 1000); // Actualiza el contador cada segundo

        function fetchNoticias() {
            $.ajax({
                url: 'sdo-includes/noticiasChile_obtener.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos recibidos:', data); // Depuración
                    var html = '';

                    if (data.length > 0) {
                        data.forEach(function(noticia) {
                            html += '<tr>';
                            html += '<td><img style="width: 50px; height: 50px;" src="' + noticia.imagen + '" alt="' + noticia.titulo + '"></td>';
                            html += '<td><a style="color: black" href="' + noticia.enlace + '">' + noticia.fecha + '<br><br>' + noticia.titulo + '</a></td>';
                            html += '</tr>';
                        });


                        $('#noticiasChile').html(html);

                        offset += limit;
                    } else {
                        $('#noticiasChile').html('<p>No hay noticias recientes.</p>');
                    }

                    loading = false;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud AJAX:', textStatus, errorThrown); // Depuración
                    $('#noticiasChile').html('<p>Error al obtener las noticias.</p>');
                    loading = false;
                }
            });
        }

        // Actualizar cada 180 segundos
        setInterval(fetchNoticias, 180000);

        // Llamada inicial para cargar las noticias inmediatamente al cargar la página
        fetchNoticias();

    });


</script>

</body>

</html>

