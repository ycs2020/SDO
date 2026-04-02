<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);



date_default_timezone_set('America/Santiago');

if(isset($_POST['idTG'])){
    $idTG = $_POST['idTG'];
    $sql = "SELECT * FROM trabajos_general WHERE idTG = $idTG";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $estadoTG = $row['estadoTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $tipoTecnico = $row['tipoTecnico'];
        $folioTG = $row['folioTG'];
        $fechaSubida = $row['fechaSubidaTecnico'];
        $avanceTGT = $row['avanceTGT'];
        $observacionTGT = $row['observacionTGT'];


    }


    $sqlTecnico = "SELECT * FROM personal WHERE idPersonal = $tecnico1";
    $resTecnico = mysqli_query($conexion,$sqlTecnico);
    while($rowTecnico = $resTecnico->fetch_assoc()){
        $nomPersonal = $rowTecnico['nomPersonal'];

    }


}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Trabajo General</title>

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

    <script>
        $(document).ready(function() {
            $('textarea').on('keyup keypress', function() {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>



<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
    $_SESSION['idTG'] = $idTG;
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Modificar Trabajo General</h4>
                    </div>
                    <?php


                    if (isset($_GET['exitoIngresoTG'])) {
                        ?>
                        <script>
                            Swal.fire(
                                'Exito!',
                                'Orden de Trabajo ingresada correctamente!',
                                'success'
                            )
                        </script>
                        <?php
                    }

                    if(isset($_GET['error1TG'])){
                        ?>
                        <script>
                            Swal.fire(
                                'Error!',
                                'La subida del Archivo fallo.Porfavor reintente!',
                                'danger'
                            )
                        </script>
                        <?php
                    }

                    if(isset($_GET['error2TG'])){
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

                    if(isset($_GET['error3TG'])){
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se a subido un archivo. Porfavor suba un archivo adjunto'
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
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
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

                            </div>
                            <!-- /.card-body -->
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

                <div class="row" id="rowMenu2" name="rowMenu2">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <br>
                                <form action="../sdo-personal/trabajorden-modificar.php" method="POST">
                                    <fieldset class="border p-3">
                                        <legend class="w-auto">&nbsp;Informaci&oacute;n del Trabajo General:&nbsp;&nbsp;</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha del Trabajo Subida Por El Profesional: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" class="form-control" readonly="readonly" name="fechaOrden" id="fechaOrden" value="<?php echo $fechaTG ?>" >

                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <br>
                                                <label for="exampleInputTurno">Fecha del Trabajo Subida Por El Técnico: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <?php
                                                    date_default_timezone_set('America/Santiago');
                                                    ?>
                                                    <input type="datetime" class="form-control" readonly="readonly" name="fechaSubida" id="fechaSubida" value="<?php echo $fechaSubida ?>" >

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Persona Solicitante: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" readonly required name="personaSolicitante" id="personaSolicitante" value="<?php echo $solicitanteTG?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTurno">Estado del Trabajo: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select id="estadoCambio" name="estadoCambio" class="form-control">
                                                        <?php
                                                            if($estadoTG == 'Trabajo Terminado'){
                                                                ?>
                                                                <option value="Trabajo Terminado" selected>Trabajo Terminado</option>
                                                                <option value="Trabajo No Terminado">Trabajo No Terminado</option>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <option value="Trabajo No Terminado"selected>Trabajo No Terminado</option>
                                                                <option value="Trabajo Terminado" >Trabajo Terminado</option>

                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <label for="exampleInputTurno">Avance del Trabajo: <code>*</code></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                            </div>
                                            <textarea required class="form-control" name="avanceTGT" id="avanceTGT" rows="10"><?php echo $avanceTGT?></textarea>
                                        </div>
                                        <hr>
                                        <label for="exampleInputTurno">Observacion del Trabajo: <code>*</code></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                            </div>
                                            <textarea required class="form-control" name="observacionTGT" id="observacionTGT" rows="10"><?php echo $observacionTGT?></textarea>
                                        </div>
                                    </fieldset>
                                    <br>
                                   <!-- <fieldset class="border p-3">
                                        <label for="exampleInputTurno">¿Desea eliminar un archivo adjunto?: </label>
                                        <div class="input-group mb-3">
                                            <button type="button" data-toggle="modal" data-target="#eliminar" class="btn btn-danger btn-lg"><i class="fas fa-trash"></i> Eliminar</button>
                                        </div>
                                    </fieldset> -->
                                    <br>
                                    <div class="form-group">
                                        <?php
                                        $now = date('Y-m-d H:i:s');

                                        $nomCarpeta = 'ORDEN DE TRABAJO - '.$now;

                                        if($userUsuario == 'ycasimis'){
                                            $letra = 'YC';
                                        }else if($userUsuario == 'iperez'){
                                            $letra = 'IP';
                                        }else if($userUsuario == 'acantillano'){
                                            $letra = 'AC';
                                        }

                                        $trabajoTerminado = 0;

                                        ?>
                                        <input type="hidden" name="fechaModi" id="fechaModi" value="<?php echo $now?>">
                                        <input type="hidden" name="letraSolicitante" id="letraSolicitante" value="<?php echo $letra?>">
                                        <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomCarpeta?>">
                                        <input type="hidden" name="trabajoTerminado" id="trabajoTerminado" value="<?php echo $trabajoTerminado?>">
                                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG?>">
                                        <input type="hidden" name="idTecnico1" id="idTecnico1" value="<?php echo $tecnico1?>">
                                        <input type="hidden" name="tipoTecnico1" id="tipoTecnico1" value="<?php echo $tipoTecnico?>">
                                        <input type="hidden" name="folioTG" id="folioTG" value="<?php echo $folioTG?>">
                                        <button type="submit" name="Guardar" id="Guardar" class="btn btn-block btn-success">Guardar</button>
                                        <a href="trabajos_general_listado.php" class="btn btn-block btn-warning">Volver Atras</a>
                                    </div>
                                </form>
                                <div class="modal fade" id="eliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title" id="exampleModalLabel"> <b> Escoja el archivo que quiere eliminar </b> </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <table class="table table-bordered table-sm" id="tabla-equipo-falla">
                                                    <thead  class="bg-info">
                                                    <tr>
                                                        <th scope="col" class="text-center">Nombre del Archivo</th>
                                                        <th scope="col" class="text-center">Tipo de Archivo</th>
                                                        <th scope="col" class="text-center">¿Es avance del técnico?</th>
                                                        <th scope="col" class="text-center">Fecha de Subida del Archivo</th>
                                                        <th scope="col" class="text-center">Eliminar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sqlArchivo = "SELECT * FROM Archivos_Trabajo_General WHERE idTG = $idTG";
                                                    $resArchivo = mysqli_query($conexion,$sqlArchivo);
                                                    while($rowArchivo = $resArchivo->fetch_assoc()){
                                                        $idArchivo = $rowArchivo['idArchivo'];
                                                        $nomArchivo = $rowArchivo['nomArchivo'];
                                                        $tipoArchivo = $rowArchivo['extArchivo'];
                                                        $trabajoTerminado1 = $rowArchivo['trabajoTerminado'];
                                                        $fechaSubida = $rowArchivo['fechaSubida'];
                                                        ?>
                                                        <tr>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $nomArchivo;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $tipoArchivo;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                if($trabajoTerminado1 == 0){
                                                                    echo 'NO';
                                                                }else{
                                                                    echo 'SI';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <?php
                                                                echo $fechaSubida;
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 14px">
                                                                <form action="../sdo-funciones/eliminar-archivo-trabajo-general.php" method="POST">
                                                                    <input type="hidden" name="idArchivo" id="idArchivo" value="<?php echo $idArchivo?>">
                                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>
                                                    <tfoot class="bg-info">
                                                    <th scope="col" class="text-center">Nombre del Archivo</th>
                                                    <th scope="col" class="text-center">Tipo de Archivo</th>
                                                    <th scope="col" class="text-center">¿Es avance del técnico?</th>
                                                    <th scope="col" class="text-center">Fecha de Subida del Archivo</th>
                                                    <th scope="col" class="text-center">Eliminar</th>
                                                    </tfoot>
                                                </table>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
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
    $(document).ready(function() {
        $('#tabla-equipo-falla').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>

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


</body>

</html>