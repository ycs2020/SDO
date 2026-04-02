<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);


$sqlUsuario = "SELECT * FROM personal WHERE idPersonal = $idUsuario";
$resUsuario = mysqli_query($conexion,$sqlUsuario);
while($rowUsuario = $resUsuario->fetch_assoc()){
    $nomPersonal = $rowUsuario['nomPersonal'];

}



if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
    }

}
date_default_timezone_set('America/Santiago');


if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {

            ini_set("memory_limit", "1256M");
            $zip = new ZipArchive();
            $dir = "../sdo-administrador/archivos-trabajo-general/folio-". $folioTG ."/carpeta-" . $idTG1 . "/";
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-personal/";
            $archivoZip = "Trabajo-General-" . $folioTG . "-Carpeta-" . $idTG1 . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zip($dir2, $zip);
                $zip->close();
                // echo '<script type="text/javascript">alert("'.$dir.'");</script>';
                if (file_exists($rutaFinal . $archivoZip)) {
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
}else {
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizaci&oacute;n de Trabajos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"/>
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
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
            $('textarea').on('keyup keypress', function() {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>

    <script>
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#listarAC2").show();
                $("#registrarAC").hide();
                $("#registrarAC2").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function () {
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


    <script type="text/javascript">
        function visible(valor)
        {
            if(valor == '')
            {
                console.log('NADA');
            }
            else if (valor == "Trabajo Terminado")
            {
                console.log('NADAD 2');
                //document.getElementById("observacionesTG").style.visibility = "visible"
                //document.getElementById("observaciones").style.visibility = "visible"
                //document.getElementById("avanceTG").style.visibility = "hidden"
                //document.getElementById("avanceT").style.visibility = "hidden"
                //document.getElementById("observacionesTG").value = valor ;
            }
            else
            {
                //document.getElementById("observacionesTG").style.visibility = "hidden"
                //document.getElementById("observaciones").style.visibility = "hidden"
                //document.getElementById("avanceTG").style.visibility = "visible"
                //document.getElementById("avanceT").style.visibility = "visible"
            }
        }
    </script>
</head>


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
                        <h4>Orden de Trabajo N°<?php echo $idTG?></h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
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
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">


                                <div class="row text-center" style="display: flex; justify-content:center;">

                                    <div id="registrarAC2" name="registrarAC2" class="col-lg-3 col-md-6 col-12 text-center">
                                        <?php
                                            if($estadoTG == 'Trabajo Terminado' || $estadoTG == 'Trabajo No Terminado'){
                                                ?>
                                                <button id="registrarAC" disabled style="color: white; background-color: #e25e3e" name="registrarAC"  class="btn btn-block btn-md show-realizar text-black text-center"><i class="nav-icon fas fa-book"></i><br>Registrar Avance de la Orden de Trabajo</button>
                                                <?php
                                            }else{
                                                ?>
                                                <button id="registrarAC" style="color: white; background-color: #e25e3e" name="registrarAC"  class="btn btn-block btn-md show-realizar text-black text-center"><i class="nav-icon fas fa-book"></i><br>Registrar Avance de la Orden de Trabajo</button>
                                                <?php
                                            }
                                        ?>

                                    </div>

                                    &nbsp;
                                    <div class="col-lg-3 col-md-6 col-12 text-center" id="listarAC2" style="display:none;" name="listarAC2">

                                        <button id="listarAC" style="display:none; color: white; background-color: #4477CE" name="listarAC" class="btn btn-block
                                         btn-md text-black text-center show-listar"><i class="nav-icon fas fa-book"></i><br>Ver Detalle de Orden de Trabajo</button>

                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                        <button type="button" style="color: white; background-color: indianred"  class="btn btn-md text-black text-center" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i><br>Visualizar Linea de Vida del Trabajo</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>&nbspOrden de Trabajo N° <?php echo $idTG?></h4>
                                <hr>
                                <div class="row">
                                    <div class="card-body">
                                        <fieldset>
                                            <div class="col-12 col-lg-3 col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless text-justify">

                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Folio Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $folioTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Fecha Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $fechaTG3  ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Turno Trabajo:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $turnoTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Personal Solicitante:</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $solicitanteTG; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-auto" style="vertical-align:middle;">
                                                                <b>Estado :</b>
                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <?php echo $estadoTG; ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </div>
                                            <h6 style="vertical-align: middle;">
                                                <form action="visualizarTrabajoOrden.php" method="POST" style="display: inline">
                                                    <?php
                                                    $sql = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                    $res = mysqli_query($conexion,$sql);
                                                    while($row = $res->fetch_assoc()){
                                                        $datos = [''];
                                                        $datos['rutaArchivo'] = $row['rutaArchivo'];
                                                        $rutaArchivo = $datos['rutaArchivo'];
                                                        $idArchivo = $row['idArchivo'];

                                                    }
                                                    if (empty($idArchivo) || $idArchivo == 0) {
                                                        ?>
                                                        <b>&nbsp&nbspNo hay Archivo Adjunto Subido por el Profesional</b>
                                                        <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                        <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                        <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG?>">
                                                        <b>&nbsp&nbspDescargar Archivo Adjunto Subido por el Profesional:</b>

                                                        &nbsp;<button type="submit" class="btn btn-lg"  style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>

                                                        <?php
                                                    }
                                                    ?>

                                                </form>
                                            </h6>
                                        </fieldset>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-12">

                                        <h6 style="text-align:justify; vertical-align: middle">
                                            <b>&nbsp;&nbsp;Detalle Solicitud: </b>
                                            <br><br>
                                            <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>

                                        </h6>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div style="font-size:85%" class="col-md-12">
                                        <h6 style="text-align: justify; vertical-align: middle">

                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>&nbsp;Registrar Avance del Trabajo General</h5>
                                <br>
                                <div class="row">
                                    <form method="POST" action="../sdo-funciones/ingresar-orden-trabajo-tecnico.php" enctype="multipart/form-data">
                                        <fieldset class="border p-3">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Fecha del Trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <?php
                                                        date_default_timezone_set('America/Santiago');
                                                        ?>
                                                        <input type="datetime" class="form-control" readonly="readonly" name="fechaSubidaTecnico" id="fechaSubidaTecnico" value="<?php echo date('Y-m-d H:i:s'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Programacion del Trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <?php
                                                        date_default_timezone_set('America/Santiago');
                                                        ?>
                                                        <input type="date" class="form-control" readonly="readonly" name="fechaProgramacion" id="fechaProgramacion" value="<?php echo $fechaProgramacion ?>" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Turno de Trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" readonly="readonly" name="turnoPersonal" id="turnoPersonal" value="<?php echo $turnoTG?>" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Técnico Responsable: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" readonly  name="tecnicoResponsable" id="tecnicoResponsable" value="<?php echo $nomUsuario?>" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputTurno">Estado del Avance: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-stream"></i></span>
                                                        </div>
                                                        <select id="estadoAvance" name="estadoAvance" class="form-control" onchange="visible(this.value)">
                                                                <option value="" selected disabled>Seleccione....</option>
                                                                <option value="Trabajo Terminado">Trabajo Terminado</option>
                                                                <option value="Observacion">Solicitar Materiales</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                                   <!--
                                                    <label for="exampleInputTurno">Subir Orden de Trabajo: <code>*</code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                                        </div>
                                                        <input type="file" class="form-control" name="ordenArchivoPersonal" id="ordenArchivoPersonal">
                                                    </div>
                                                    -->
                                            </fieldset>
                                            <br>
                                            </div>
                                            <div class="row">
                                                <div style="font-size:85%" class="col-md-12">
                                                    <h6 style="text-align:justify; vertical-align: middle">
                                                        <b id="avanceT">&nbsp;Avance del Trabajo: <code>*</code></b>
                                                        <br><br>
                                                        <textarea id="avanceTG" required style="resize:none; font-size:85%"  name="avanceTG" class="form-control"></textarea>
                                                    </h6>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <button type="submit" name="Guardar" id="Guardar" class="btn btn-block btn-success">Guardar</button>
                                                <input type="hidden" class="form-control" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                                <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario?>">
                                            </div>
                                </form>
                            </div>

                        </div>
                    </div>

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
    $(function () {
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
    $("input[name='opciones']").change(function () {
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








