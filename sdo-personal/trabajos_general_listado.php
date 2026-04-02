<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);

date_default_timezone_set('America/Santiago');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Trabajos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        .reset {
            all: revert;
        }

        #cargaCombustible,
        #medicionNormal {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#listarAC2").hide();
                $("#registrarAC").show();
                $("#registrarAC2").show();
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

    <script>
        function selectNit(e) {
            var nit =  e.target.selectedOptions[0].getAttribute("data-nit")
            document.getElementById("nit").value = nit;
        }
    </script>


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Ordenes de Trabajo</h4>
                    </div>
                    <?php


                    if(isset($_GET['exitoSinArchivo'])){
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente sin archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if (isset($_GET['exitoConArchivo'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente junto con archivo adjunto!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorEnvioArchivo'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'El Archivo Adjunto No Se Subio De Manera Correcta!',
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorArchivo'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Algo Salio Mal',
                                text: 'Algo fallo con la subida del Archivo Adjunto.Reintente!',
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['archivoNoHabilitado'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El archivo no se encuentra permitido.!'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['error'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'A ocurrido un error inesperado.Porfavor reintente'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['errorTecnicoTG'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '¡No se puede colocar el técnico dos veces.!'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['error4tg'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos datos no se han enviado correctamente.Reintente'
                            })
                        </script>
                        <?php

                    }

                    if(isset($_GET['errorEnlosDatos'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Algunos Datos No Se Encuentran Llenados.Reintente'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['exito'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'sucess',
                                title: 'Exito',
                                text: 'Se ha ingresado la respuesta con exito'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['exitoGenerar'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha ingresado el avance con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de BMS con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha la HT de Rondas con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT del bms'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarBMS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de BMS'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorOrdenRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al generar la HT de las rondas.'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorFolioRONDAS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en el folio de HT de las rondas'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarCA'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarCA'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de Control de Acceso'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha creado la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioCCTV'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de CCTV'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se ha generado la HT de LLE con exito'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }

                    if(isset($_GET['errorGenerarLLE'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de LLE'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['exitoGenerarOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Exito al crear la HT de Otros'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorFolioOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    if(isset($_GET['errorGenerarOTROS'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al crear la HT de otros'
                            })
                        </script>
                        <?php
                    }
                    ?>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="background-color: white">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


                <?php

                if (isset($_GET['exitoAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Medici&oacute;n de Combustible Agregada con &Eacute;xito.',
                            confirmButtonText: 'Cerrar',
                            icon: 'success',
                        });
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarAC'])) {
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Error al Ingresar Medici&oacute;n de Combustible.',
                            confirmButtonText: 'Cerrar',
                            icon: 'error',
                        });
                    </script>
                    <?php
                }


                ?>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de la Orden de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Informacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descripcion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE tecnico1 LIKE '%$nomUsuario%' AND estadoTG NOT IN ('Trabajo Terminado') ORDER BY fechaProgramacion DESC";
                                        $resTG = mysqli_query($conexion, $queryTG);

                                        $numTG = 1;

                                        $colorTG = "";

                                        while ($rowTG = $resTG->fetch_assoc()) {

                                            $fechaTG = $rowTG['fechaTG'];
                                            $fechaProgramacion = $rowTG['fechaProgramacion'];

                                            $turnoTG = $rowTG['turnoTG'];
                                            $turnoTG = trim($turnoTG);

                                            $solicitanteTG = $rowTG['solicitanteTG'];
                                            $solicitanteTG = trim($solicitanteTG);

                                            $idTG = $rowTG['idTG'];
                                            $idTG = trim($idTG);

                                            $estadoTG = trim($rowTG['estadoTG']);
                                            $folioTG = $rowTG['folioTG'];

                                            $contarObs = contarObs($idTG);
                                            $contarPanol = contarPanol($idTG);
                                            $contarRechazadoPanol = contarRechazadoPanol($idTG);
                                            $contarAceptadoPanol = contarAceptadoPanol($idTG);
                                            $descripcion = $rowTG['descripcion'];
                                            //$contarNoPanol = contarNoPanol($idTG);
                                            //$contarMaterial = contarMaterial($idTG);


                                            if($tecnico1 == $nomUsuario){
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            }else if($contarPanol > 0){
                                                $colorTR = "background-color: blue; color:white";
                                            }else if($contarAceptadoPanol > 0){
                                                $colorTR = "background-color: green; color:white";
                                            }else if($contarRechazadoPanol > 0) {
                                                $colorTR = "background-color: red; color:white";
                                            }else{
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php

                                                    if (DateTime::createFromFormat('Y-m-d', $fechaProgramacion) === false) {

                                                        echo "No registra fecha";
                                                    } else {
                                                        $fechaProgramacion = date('d/m/Y', strtotime(str_replace('/', '-', $fechaProgramacion)));
//
//                                                        $registrofechaTG = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTG)));
//                                                        $registroHoraTG = date('H:i', strtotime($fechaProgramacion));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

//                                                        echo $registrofechaTG . ' a las ' . $registroHoraTG . ' hrs';
                                                        echo $fechaProgramacion;
                                                    } ?>

                                                </td>
                                                <td nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                    <?php
                                                        echo $folioTG;
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($turnoTG)) {
                                                        echo "No registra Turno";
                                                    } else {

                                                        echo $turnoTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if (empty($solicitanteTG)) {
                                                        echo "No registra Solicitante";
                                                    } else {

                                                        echo $solicitanteTG;
                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php if (empty($estadoTG)) {
                                                        echo "No registra Estado";
                                                    } else {
                                                        if($estadoTG == 'trabajoTerminado'){
                                                            $estado = "Trabajo Terminado";
                                                            echo $estado;
                                                        }else{
                                                            echo $estadoTG;
                                                        }

                                                    } ?>

                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                    <?php
                                                    if($contarPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'Funcionario asignado ha solicitado materiales y/o herramientas'?>
                                                        </center>
                                                        <?php
                                                    }else if($contarAceptadoPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud hacia pañol se ha entregado con exito'?>
                                                        </center>
                                                        <?php
                                                    }else if($contarRechazadoPanol > 0){
                                                        ?>
                                                        <center>
                                                            <?php echo 'La solicitud se ha rechazado por personal de pañol'?>
                                                        </center>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <center>
                                                            <?php echo 'Sin Informacion';?>
                                                        </center>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="center">
                                                     <?php echo $descripcion?>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        if ($idTG == 0 || $idTG == "" || $idTG == " " || $idTG == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" action="visualizardetalles.php" method="post">

                                                                <input type="hidden" name="idTG" value="<?php echo $idTG; ?>">

                                                                <button type="submit" id="inputMozi" class="btn btn-block btn-success"><i class="fa fa-eye"></i></button>


                                                            </form>

                                                            <?php
                                                        }
                                                        ?>
                                                    </center>
                                                </td>

                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Folio de la Orden de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Turno de Trabajo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Solicitante</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Informacion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Descripcion</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Detalles</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>


            </div>
            <!-- /.timeline -->

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
<script src="js/dark-mode.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }

        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>


<script type="text/javascript">
    $("input[name='opciones']").change(function() {
        if ($(this).val() == 'medicionN') {
            $('#medicionNormal').show();
            $('#cargaCombustible').hide();
        } else if ($(this).val() == 'cargaC') {
            $('#medicionNormal').hide();
            $('#cargaCombustible').show();
        } else {
            $('#medicionNormal').hide();
            $('#cargaCombustible').hide();
        }
    })
</script>
</body>
</html>
