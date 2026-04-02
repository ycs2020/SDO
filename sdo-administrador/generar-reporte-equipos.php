<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
if (isset($_POST['idHojaDesc'])) {
    $idHojaDesc = $_POST['idHojaDesc'];
    $folioDesc = trim($_POST['folioDesc']);
    $idUnico = $_POST['idUnico'];


    if ($idHojaDesc > 0) {

        if (empty($folioDesc) || $folioDesc == '') {
        } else {

            $folioSoliCompra2 = "EQUIPO-" . $idUnico;

            echo $folioSoliCompra2;
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = '../sdo-archivosHojasVida/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Archivos-" . $folioSoliCompra2 . ".zip";

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                if (file_exists($rutaFinal . "/" . $archivoZip)) {
                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} {
}

if (isset($_POST['idHojaTec'])) {
    $idHojaTec = $_POST['idHojaTec'];
    $folioHojaTec = trim($_POST['folioHojaTec']);


    if ($idHojaTec > 0) {

        if (empty($folioHojaTec) || $folioHojaTec == '') {
        } else {

            $folioSoliCompra2 = "HOJA-" . $idHojaTec;

            echo $folioSoliCompra2;
            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = '../sdo-archivosHojasTecnicas/' . $folioSoliCompra2;
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-administrador/";
            $archivoZip = "Archivos " . $folioHojaTec . ".zip";

            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                if (file_exists($rutaFinal . "/" . $archivoZip)) {
                    $absoluteFilePath = $rutaFinal . "/" . $archivoZip;
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . basename($archivoZip));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($archivoZip));
                    ob_clean();
                    flush();
                    readfile($archivoZip);
                    unlink($archivoZip); //Esta línea destruye el elemento zip que se genera
                    exit;
                } else {
                    $descargaConfirma .= "N";
                }
            }
        }
    }
} {
}

if (isset($_SESSION['idHoja'])) {
    $idHoja = $_SESSION['idHoja'];
}

if (isset($_SESSION['idUnico'])) {
    $idEquipo = $_SESSION['idUnico'];
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar Equipo</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            // Show div by removing inline display none style rule
            $(".show-visualizar").click(function() {
                $("#rowMenu1").show();
                $("#rowMenu2").hide();
                $("#VisualizarHV").hide();
                $("#ListarHT").show();
            });
            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu1").hide();
                $("#rowMenu2").show();
                $("#VisualizarHV").show();
                $("#ListarHT").hide();
            });


        });
    </script>

    <script>
        function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
            if (d.checked == true) {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
            } else {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
            }
        }
    </script>
    <style>
        .ocultar-manual {
            display: none;
        }

        .ocultar-accesorio {
            display: none;
        }

        .ocultar-pauta {
            display: none;
        }

        .ocultar-certificados {
            display: none;
        }

        .ocultar-otros {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini dark-mode" id="body">
    <!-- <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> -->

    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><b>Generar Reportes Hoja de vida equipos</b></h1>
                        </div>
                        <div class="col-sm-6">

                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3"><b>Modo Claro</b></label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")
                                    document.body.classList.toggle("dark-mode");
                                }
                            </script>
                            <br>
                            <br>
                        </div>


                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fuild">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <b>Filtros para generar reporte</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <a href="index.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b></a>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </section>
            <!-- /.content -->
        </div>


        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>

    <!-- Page specific script -->

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>


</body>

</html>