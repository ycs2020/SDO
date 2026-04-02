<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');


$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = trim($_SESSION['nomUsuario']);
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$idDepartamento = $_SESSION['idDepartamento'];


if(isset($_POST['idTG']) || isset($_SESSION['idTG'])){
    if(isset($_POST['idTG'])){

        $idTG = $_POST['idTG'];

        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }else if(isset($_SESSION['idTG'])){
        $idTG = $_SESSION['idTG'];
        if($idTG > 0){
            $_SESSION['idTG'] = $idTG;
        }else{
            header('Location: trabajos_general_listado.php?errorDetalle=true');
        }
    }
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
        // $tipoTecnico = $row['tipoTecnico'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $ticketAsociado = $row['ticketAsociado'];
        $tipoTrabajo = $row['tipoTrabajo'];
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamentos = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
    }

}
$contarRondas = contarRonda($idTG);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Formulario Rondas</title>

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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".show-listar").click(function() {
                $("#row4").show();
                $("#row5").show();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
            });
            $(".show-listar-detalle").click(function() {
                $("#row4").hide();
                $("#row5").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
            });
        });
    </script>
    <script>
        function marcarOtroInput() {
            var primerInput = document.querySelector('.show-mostrar');
            var otroInput = document.getElementById('torre1');
            var torre2 = document.getElementById('torre2');
            var torre3 = document.getElementById('torre3');
            var torre4 = document.getElementById('torre4');
            var chiller1 = document.getElementById('chiller1');
            var chiller2 = document.getElementById('chiller2');
            var chiller3 = document.getElementById('chiller3');
            var chiller4 = document.getElementById('chiller4');
            var bac1 = document.getElementById('bac1');
            var bac2 = document.getElementById('bac2');
            var bac3R = document.getElementById('bac3R');
            var bac4 = document.getElementById('bac4');
            var bac4R = document.getElementById('bac4R');
            var barvf2 = document.getElementById('barvf2');
            var baep1 = document.getElementById('baep1');
            var baep2 = document.getElementById('baep2');
            var baep3R = document.getElementById('baep3R');
            var baep4 = document.getElementById('baep4');
            var baep4R = document.getElementById('baep4R');
            var baes1 = document.getElementById('baes1');
            var baes1R = document.getElementById('baes1R');
            var baes2 = document.getElementById('baes2');
            var baes2R = document.getElementById('baes2R');
            var baes2_1 = document.getElementById('baes2_1');
            var baes3 = document.getElementById('baes3');
            var baes3R = document.getElementById('baes3R');
            var baes4 = document.getElementById('baes4');
            var baes4R = document.getElementById('baes4R');
            var caldera1 = document.getElementById('caldera1');
            var caldera2 = document.getElementById('caldera2');
            var caldera3 = document.getElementById('caldera3');
            var caldera4 = document.getElementById('caldera4');
            var caldera5 = document.getElementById('caldera5');
            var bcap5 = document.getElementById('bcap5');
            var bcap5R = document.getElementById('bcap5R');
            var bcap1 = document.getElementById('bcap1');
            var bcap2 = document.getElementById('bcap2');
            var bcap3 = document.getElementById('bcap3');
            var bcap4R = document.getElementById('bcap4R');
            var bcas1 = document.getElementById('bcas1');
            var bcas1R = document.getElementById('bcas1R');
            var bcas2 = document.getElementById('bcas2');
            var bcas2R = document.getElementById('bcas2R');
            var bcas2_1 = document.getElementById('bcas2_1');
            var bcas3 = document.getElementById('bcas3');
            var bcas3R = document.getElementById('bcas3R');
            var bcas4 = document.getElementById('bcas4');
            var bcas4R = document.getElementById('bcas4R');
            var bracs1 = document.getElementById('bracs1');
            var bracs1R = document.getElementById('bracs1R');
            var bracs2 = document.getElementById('bracs2');
            var bracs2R = document.getElementById('bracs2R');
            var bcc1 = document.getElementById('bcc1');
            var bcc1R = document.getElementById('bcc1R');
            var bcc2 = document.getElementById('bcc2');
            var bcc2R = document.getElementById('bcc2R');
            var bcpss1 = document.getElementById('bcpss1');
            var bcpss1R = document.getElementById('bcpss1R');
            var bcpss2 = document.getElementById('bcpss2');
            var bcpss2R = document.getElementById('bcpss2R');
            var bsap1 = document.getElementById('bsap1');
            var bsap1R = document.getElementById('bsap1R');
            var valv_264 = document.getElementById('valv_264');
            var valv_262 = document.getElementById('valv_262');
            var valv_260 = document.getElementById('valv_260');
            var valv_261 = document.getElementById('valv_261');
            var valv_208 = document.getElementById('valv_208');
            var valv_205 = document.getElementById('valv_205');
            var valv_263 = document.getElementById('valv_263');
            var valv_265 = document.getElementById('valv_265');
            var drycooler = document.getElementById('drycooler');

            if (primerInput.checked) {
                otroInput.checked = true;
                torre2.checked = true;
                torre3.checked = true;
                torre4.checked = true;
                chiller1.checked = true;
                chiller2.checked = true;
                chiller3.checked = true;
                chiller4.checked = true;
                bac1.checked = true;
                bac2.checked = true;
                bac3R.checked = true;
                baep3R.checked = true;
                bac4.checked = true;
                bac4R.checked = true;
                barvf2.checked = true;
                baep1.checked = true;
                baep2.checked = true;
                baep4.checked = true;
                baep4R.checked = true;
                baes1.checked = true;
                baes1R.checked = true;
                baes2.checked = true;
                drycooler.checked = true;
                baes2R.checked = true;
                baes2_1.checked = true;
                baes3.checked = true;
                baes3R.checked = true;
                baes4.checked = true;
                baes4R.checked = true;
                caldera1.checked = true;
                caldera2.checked = true;
                caldera3.checked = true;
                caldera4.checked = true;
                caldera5.checked = true;
                bcap5.checked = true;
                bcap5R.checked = true;
                bcap1.checked = true;
                bcap2.checked = true;
                bcap3.checked = true;
                bcap4R.checked = true;
                bcas1.checked = true;
                bcas1R.checked = true;
                bcas2.checked = true;
                bcas2R.checked = true;
                bcas2_1.checked = true;
                bcas3.checked = true;
                bcas3R.checked = true;
                bcas4.checked = true;
                bcas4R.checked = true;
                bracs1.checked = true;
                bracs1R.checked = true;
                bracs2.checked = true;
                bracs2R.checked = true;
                bcc1.checked = true;
                bcc1R.checked = true;
                bcc2.checked = true;
                bcc2R.checked = true;
                bcpss1.checked = true;
                bcpss1R.checked = true;
                bcpss2.checked = true;
                bcpss2R.checked = true;
                bsap1.checked = true;
                bsap1R.checked = true;
                valv_264.checked = true;
                valv_262.checked = true;
                valv_260.checked = true;
                valv_261.checked = true;
                valv_208.checked = true;
                valv_205.checked = true;
                valv_263.checked = true;
                valv_265.checked = true;
            } else {
                otroInput.checked = false;
                torre2.checked = false;
                torre2.checked = false;
                torre3.checked = false;
                torre4.checked = false;
                chiller1.checked = false;
                chiller2.checked = false;
                chiller3.checked = false;
                chiller4.checked = false;
                bac1.checked = false;
                bac2.checked = false;
                bac3R.checked = false;
                baep3R.checked = false;
                bac4.checked = false;
                bac4R.checked = false;
                barvf2.checked = false;
                baep1.checked = false;
                baep2.checked = false;
                baep4.checked = false;
                baep4R.checked = false;
                baes1.checked = false;
                baes1R.checked = false;
                baes2.checked = false;
                drycooler.checked = false;
                baes2R.checked = false;
                baes2_1.checked = false;
                baes3.checked = false;
                baes3R.checked = false;
                baes4.checked = false;
                baes4R.checked = false;
                caldera1.checked = false;
                caldera2.checked = false;
                caldera3.checked = false;
                caldera4.checked = false;
                caldera5.checked = false;
                bcap5.checked = false;
                bcap5R.checked = false;
                bcap1.checked = false;
                bcap2.checked = false;
                bcap3.checked = false;
                bcap4R.checked = false;
                bcas1.checked = false;
                bcas1R.checked = false;
                bcas2.checked = false;
                bcas2R.checked = false;
                bcas2_1.checked = false;
                bcas3.checked = false;
                bcas3R.checked = false;
                bcas4.checked = false;
                bcas4R.checked = false;
                bracs1.checked = false;
                bracs1R.checked = false;
                bracs2.checked = false;
                bracs2R.checked = false;
                bcc1.checked = false;
                bcc1R.checked = false;
                bcc2.checked = false;
                bcc2R.checked = false;
                bcpss1.checked = false;
                bcpss1R.checked = false;
                bcpss2.checked = false;
                bcpss2R.checked = false;
                bsap1.checked = false;
                bsap1R.checked = false;
                valv_264.checked = false;
                valv_262.checked = false;
                valv_260.checked = false;
                valv_261.checked = false;
                valv_208.checked = false;
                valv_205.checked = false;
                valv_263.checked = false;
                valv_265.checked = false;
            }
        }
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?> - Formulario Rondas con Consola de Alarmas</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-left" id="volverAtras2">
                                    <h5>
                                        <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                            <?php echo $contarRondas?></b>
                                    </h5>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2"
                                     name="volverAtras2">
                                    <!-- small box -->

                                    <a href="visualizardetalles.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>
                                <hr>
                                <form action="../sdo-funciones/ingresar-rondas-alarma.php" method="post">
                                    <?php include_once('../sdo-includes/tabla_de_rondas.php')?>
                                    <input type="hidden" id="contarRondas" name="contarRondas" value="<?php echo $contarRondas?>">
                                    <input type="hidden" id="folioTG" name="folioTG" value="<?php echo $folioTG?>">
                                    <br>
                                    <?php
                                    if($contarRondas >= 1){
                                        ?>
                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Consola de Alarmas:</legend>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Analizadores de Red:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control"
                                                                  name="analizadoresred" id="analizadoresred"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Fan-Coil:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="fancoil" id="fancoil"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Riego:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="riego"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Sentina:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="sentina"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">VAV:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="vav"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Controladores:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="controladores"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">Vigas Frias:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="vigas-frias"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">CW:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="cw"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">VIN y VEX:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="vinyvex"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputQuienSolicita">UMA:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-cloud"></i></span>
                                                        </div>
                                                        <textarea class="form-control" name="uma"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                    <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                    <button class="btn btn-block bg-primary" type="submit">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
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
<script>
    $(function() {
        $('#observacionTotal').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    $(function() {
        $('#obsercacion2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    $(document).ready(
        $('.terminartodo').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('name');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Desea finalizar esta Orden de Trabajo?',
                text: 'Esta acción no se puede revertir',
                showConfirmButton: true,
                confirmButtonText: 'Si , finalizar',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'No, lo pensare',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/finalizar_OT.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('finalizando.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'OT finalizada con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.href =
                                    "trabajos_general_listado_finalizados.php";
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
</body>

</html>