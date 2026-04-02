<?php

include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} else {
    session_destroy();
    session_start();
}

if(isset($_GET['enMantenimiento'])){
    echo '<script language="javascript">alert("Acceso denegado. Tu IP no está autorizada.");</script>';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; de Inicio - SDO</title>

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

    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <script>
        function iniciarContador() {
            // Fecha y hora objetivo
            const fechaObjetivo = new Date('2024-09-23T16:20:00').getTime();

            // Actualizar el contador cada segundo
            const x = setInterval(function() {
                // Obtener la fecha y hora actual
                const ahora = new Date().getTime();

                // Calcular la distancia entre la fecha objetivo y ahora
                const distancia = fechaObjetivo - ahora;

                // Cálculos de tiempo
                const dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
                const horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

                // Mostrar el resultado en un elemento con id "contador"
                document.getElementById("contador").innerHTML =
                    "<br><h5 style='text-align: center'><b>Tiempo para Finalizar Mantenimiento: </b><br>"+ horas + " hrs, " + minutos + " minutos, " + segundos + " segundos </h5>";

                // Si el contador ha terminado, mostrar el mensaje y redirigir
                if (distancia < 0) {
                    clearInterval(x);
                    document.getElementById("contador").innerHTML = "Reingreso será a las 16:20 del día de hoy.";
                    setTimeout(function() {
                        window.location.href = 'login_index.php';
                    }, 2000); // Redirige después de 2 segundos
                }
            }, 1000);
        }
    </script>
</head>

<body style="background-image:url(../dist/img/Hospital.png); background-position: center; background-size: cover;" class="hold-transition login-page "  onload="iniciarContador()">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/controlCent-HBQP-120.png" alt="AdminLTELogo">
    </div>

    <div class="container">
        <div class="row align-items-center vh-100">
            <div class="col-md-6 mx-auto">
                <div class="card shadow border">
                    <div class="card-header text-center">
                        <a href="#" class="h3"><b>Ingreso a Plataforma SDO</b></a>
                        <br>
                        <br>
                        <input type="image" src="../dist/img/controlCent-HBQP-80.png">
                    </div>

                    <div class="card-body d-flex flex-column align-items-center">
                        <h4>Sitio en Mantenimiento.</h4>
                        <br>
                        <div id="contador"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>