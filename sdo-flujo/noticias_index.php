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

        <div class="row d-flex align-items-stretch">
            <!-- First Card -->
            <div class="col-lg-6 col-md-6 col-12  mb-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-center">
                        <h3 class="card-title">Pron&oacute;stico Meteorol&oacute;gico</h3>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 210px;">
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
                                style="padding-bottom:22px; position:relative; width: 100%; max-width: none;">
                            <a href="https://www.tomorrow.io/weather-api/"
                                    rel="nofollow noopener noreferrer"
                                    target="_blank"
                                    style="position: absolute; bottom: 0; transform: translateX(-50%); left: 50%;">
                            </a>
                        </div>
                        <style>
                            .tomorrow {
                                width: 100% !important;
                                max-width: 100% !important;
                            }
                        </style>
                    </div>
                </div>
            </div>

            <!-- Second Card -->
            <div class="col-lg-6 col-md-6 col-12 mb-3">
                <div class="card card-widget widget-user-2 h-100">
                    <div class="card-header bg-primary text-center">
                        <h3 class="card-title">Sismología Chile</h3>
                    </div>
                    <div class="card-body p-0">
                        <h5 id="contadorSismos" class="m-2 text-center" style="font-size: 11pt;">Actualización en: 180 segundos</h5>
                        <div id="scrollableTable" style="height: 210px; overflow-y: scroll;">
                            <table class="table table-striped" id="sismos" border="1" cellpadding="3" style="width: 100%; font-size: 11pt; background-color: #e8effd">
                                <!-- Contenido dinámico -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row d-flex align-items-stretch">
            <!-- First Card -->
            <div class="col-12  mb-3">

                <div class="card h-100">
                    <div class="card-header bg-primary text-center">
                        <h3 class="card-title">Noticias</h3>
                    </div>
                    <div style="height: 75vh;" class="card-body justify-content-center align-items-center">
                        <div id="contenedor-noticias" class="row justify-content-center">
                            <?php
                            // 1. Sistemas específicos con nombres visibles
                            $sistemas = [
                                "Electricidad" => "Electricidad",
                                "Equipos de Apoyo Clinico" => "Equipos de Apoyo Clínico",
                                "Equipos Industriales" => "Equipos Industriales",
                                "Infraestructura" => "Infraestructura",
                                "Inst. Térmicas, Climatización y Refrigeración" => "Climatización"
                            ];
                            // Ordenar alfabéticamente por el nombre visible
                            asort($sistemas);

                            // Mostrar tarjetas por sistema específico
                            foreach ($sistemas as $valorSQL => $nombreMostrar) {
                                ?>
                                <div class="col-lg-2 col-md-4 col-12 d-flex">
                                    <div class="card h-100 w-100">
                                        <div class="card-header text-center bg-blue">
                                            <h5 class="text-center w-100 text-bold"><?php echo htmlspecialchars($nombreMostrar); ?></h5>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-start align-items-start">
                                            <?php
                                            $sqlSistemas = "SELECT tituloAviso, fechaAviso FROM avisos_relevantes WHERE sistemaAviso LIKE '$valorSQL' AND estadoAviso = 'Activo' ORDER BY fechaAviso DESC";
                                            $resSistemas = mysqli_query($conexion, $sqlSistemas);
                                            $cantidadSistemas = mysqli_num_rows($resSistemas);

                                            if ($cantidadSistemas > 0) {
                                                while ($rowSistemas = mysqli_fetch_assoc($resSistemas)) {
                                                    $fechaTime = strtotime($rowSistemas["fechaAviso"]);
                                                    $fechaHora = fechaEspañol2($fechaTime);
                                                    $horaAviso = date('H:i', $fechaTime);
                                                    ?>
                                                    <div class="alert alert-warning p-2 mb-2 text-center w-100">
                                                        <strong><?php echo $fechaHora . '<br>' . $horaAviso . '<br><br>' . htmlspecialchars($rowSistemas['tituloAviso']); ?></strong>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                echo "<div class='text-muted w-100 text-center'>Sin avisos</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }

                            // 2. Obtener lista de sistemas a excluir
                            $sistemasExcluidos = array_keys($sistemas); // esto da ["Electricidad", "Equipos de Apoyo Clinico", ...]
                            $sistemasExcluidos[] = "Control Centralizado"; // agregar uno más

                            // Escapar valores para evitar inyecciones
                            $sistemasExcluidosSQL = "'" . implode("','", array_map(function($s) use ($conexion) {
                                    return mysqli_real_escape_string($conexion, $s);
                                }, $sistemasExcluidos)) . "'";

                            // 3. Consulta para "Ingeniería Operacional" (los demás sistemas)
                            $sqlOtros = "
                                        SELECT sistemaAviso, tituloAviso, fechaAviso 
                                        FROM avisos_relevantes 
                                        WHERE sistemaAviso NOT IN ($sistemasExcluidosSQL)
                                        AND estadoAviso = 'Activo' 
                                        ORDER BY fechaAviso DESC
                                    ";
                            $resOtros = mysqli_query($conexion, $sqlOtros);
                            ?>
                            <div class="col-lg-2 col-md-4 col-12 d-flex">
                                <div class="card h-100 w-100">
                                    <div class="card-header text-center bg-blue">
                                        <h5 class="text-center w-100 text-bold">Ingeniería Operacional</h5>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-start align-items-start">
                                        <?php
                                        if (mysqli_num_rows($resOtros) > 0) {
                                            while ($rowOtros = mysqli_fetch_assoc($resOtros)) {
                                                $fechaTime = strtotime($rowOtros["fechaAviso"]);
                                                $fechaHora = fechaEspañol2($fechaTime);
                                                $horaAviso = date('H:i', $fechaTime);
                                                ?>
                                                <div class="alert alert-warning p-2 mb-2 text-center w-100">
                                                    <strong><?php echo $fechaHora . '<br>' . $horaAviso . '<br><br>' . htmlspecialchars($rowOtros['tituloAviso']); ?></strong>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "<div class='text-muted w-100 text-center'>Sin avisos</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
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


    });


</script>
<script>
    function cargarNoticias() {
        const contenedor = document.getElementById('contenedor-noticias');
        fetch('noticias_sistemas.php')
            .then(response => response.text())
            .then(data => {
                contenedor.innerHTML = data;
            })
            .catch(error => {
                console.error('Error al cargar noticias:', error);
            });
    }

    // Carga inicial
    cargarNoticias();

    // Actualizar cada 180 segundos (180000 ms)
    setInterval(cargarNoticias, 60000);
</script>


</body>

</html>

