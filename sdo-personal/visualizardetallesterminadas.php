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
    while($row = $res->fetch_assoc()) {
        $ticket = $row['ticketAsociado'];
        $folioTG = $row['folioTG'];
        $estadoTG = $row['estadoTG'];
        $solicitanteTG = $row['solicitanteTG'];
        $detalleTG = $row['detalleTG'];
        $fechaTG = $row['fechaTG'];
        $turnoTG = $row['turnoTG'];
        $tecnico1 = $row['tecnico1'];
        $fechaTG2 = strtotime($fechaTG);
        $fechaTG3 = fechaEspañol2($fechaTG2);
        $fechaProgramacion = $row['fechaProgramacion'];
        $fechaProgram1 = strtotime($fechaProgramacion);
        $fechaProgram2 = fechaEspañol2($fechaProgram1);
        $detalleTurnoCamara = $row['detalleTurnoCamara'];
        $detalleTurnoLLE = $row['detalleTurnoLLE'];
        $detalleTurnoCA = $row['detalleTurnoCA'];
        $detalleTurnoRonda = $row['detalleTurnoRondas'];
        $detalleOtros = $row['detalleOtros'];
        $departamento = $row['departamento'];
        $formularioRondas = $row['formularioRondas'];
        $servicio = $row['servicio'];

    }
}

if (isset($_POST['idTG1'])) {
    $idTG1 = $_POST['idTG1'];
    $folioTG = $_POST['folio'];

    if ($idTG1 > 0) {
        if (empty($idTG1) || $idTG1 == '') {
        } else {
            if ($idTG1 > 116) {

                $dir = "../sdo-archivosOT/" . $folioTG . "/";
            } else {

                $dir = "../sdo-administrador/archivos-trabajo-general/folio-" . $folioTG . "/carpeta-" . $idTG1 . "/";
            }

            ini_set("memory_limit", "5256M");
            $zip = new ZipArchive();
            $dir2 = $dir . "/";
            $rutaFinal = "../sdo-personal/";
            $archivoZip = "Documentos-" . $folioTG . ".zip";
            if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
                agregar_zipSC($dir2, $zip);
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
} else {
}
if(isset($_POST['folioTGDescargar'])){
    $folioDescargar = $_POST['folioTGDescargar'];
    $dir = "../sdo-archivo-ot-asignado/$folioDescargar".'/';
    ini_set("memory_limit", "5256M");
    $zip = new ZipArchive();
    $dir2 = $dir . "/";
    $rutaFinal = "../sdo-jefestecnicos/";
    $archivoZip = "Archivos-Funcionario-Asignado-" . $folioDescargar . ".zip";
    if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
        agregar_zipSC($dir2, $zip);
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

$sqlTrabajo = "SELECT * FROM ordenes_trabajos_general WHERE noOrdenTrabajo = '$folioTG'";
$resTrabajo = mysqli_query($conexion,$sqlTrabajo);
while($rowTrabajo = $resTrabajo->fetch_assoc()){
    $idOrden = $rowTrabajo['idOrden'];
    $folio = $rowTrabajo['folioOrden'];

}
/*
$contarHT = contarHT1($folioTG);
$contarBMS = contarBMS1($folioTG);
$contarCA = contarCA1($folioTG);
$contarCCTV = contarCCTV1($folioTG);
$contarRondas = contarRondas1($folioTG);
$contarLLE = contarLLE1($folioTG);
$contarOtros = contarOtros1($folioTG);
*/
$contarRondas = contarRonda($idTG);
$buscarNombreServicio = buscarNombreServicio($servicio);
$contarchecklistvav = contarchecklistvav($idTG);
$contarchecklistucas = contarchecklistucas($idTG);
$contarchecklistvf = contarchecklistvf($idTG);
$contarASTUCA = contarASTUCA($idTG);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detalle Orden de Trabajo</title>

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

    <script>
        $(document).ready(function() {
            $(".show-parametros").click(function () {
                $("#rowParametrosPab").show();
                $("#row1").hide();
                $("#row6").hide();
                $("#row8").hide();
                $("#row5").hide();
                $("#row4").hide();
                $("#row2").hide();
                $("#row3").hide();

            });
            $(".show-listar").click(function() {
                $("#row6").hide();
                $("#row8").show();
                $("#row5").show();
                $("#row4").show();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").hide();
                $("#listarAC").show();
                $("#row9").hide();
                $("#rowParametrosPab").hide();
                $("#row10").hide();
            });
            $(".show-listar-detalle").click(function() {
                $("#row5").hide();
                $("#row6").show();
                $("#row4").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#row8").hide();
                $("#row9").hide();
                $("#row10").hide();
                $("#rowParametrosPab").hide();
            });
            $(".show-visar").click(function(){
                $("#row6").hide();
                $("#row8").hide();
                $("#row5").hide();
                $("#row4").hide();
                $("#row1").hide();
                $("#row2").hide();
                $("#row3").hide();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#row9").show();
                $("#row10").show();
                $("#visar").hide();
                $("#desvisar").show();
                $("#rowParametrosPab").hide();
                $("#checklistvav").show();
                $("#checklistvf").show();
                $("#checklistucas").show();
            });
            $(".show-detalles").click(function(){
                $("#row5").hide();
                $("#row6").show();
                $("#row4").hide();
                $("#row1").show();
                $("#row2").show();
                $("#row3").show();
                $("#registrarAC").show();
                $("#listarAC").hide();
                $("#row8").hide();
                $("#row9").hide();
                $("#row10").hide();
                $("#visar").show();
                $("#desvisar").hide();
                $("#rowParametrosPab").hide();
                $("#checklistvav").hide();
                $("#checklistvf").hide();
                $("#checklistucas").hide();
            });
        });
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
                <div class="row mb-2">
                    <div class="col-sm-6">



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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Orden de Trabajo N° <?php echo $folioTG?></h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="trabajos_general_listado_finalizados.php" style="float: right;"
                                       class="btn bg-gradient-red float-right btn-md text-white"><i
                                                class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>

                                <br>
                                <br>
                                <hr>
                                <div style="text-align:center;">
                                    <button class="btn bg-yellow show-listar" id="registrarAC" name="registrarAC"><i class="fas fa-eye"></i>&nbsp;Ver Linea de Vida de la OT </button>
                                    <button class="btn bg-yellow show-listar-detalle" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Detalle de la OT</button>
                                    <?php
                                        if($idDepartamento == 1){
                                            ?>
                                            <button class="btn bg-gradient-success btn-md show-parametros text-white" id="listarParametros" name="listarParametros">
                                                <i class="fas fa-pen"></i>&nbsp;Listado de Parametros de Pabellon
                                            </button>
                                            <?php
                                        }
                                    ?>

                                    <button class="btn btn-success show-visar" style="float: right;" id="visar"><i class="fas fa-check"></i>&nbsp;Ver HT y OT</button>
                                    <button class="btn btn-success show-detalles" style="float: right;display:none;" id="desvisar"><i class="fas fa-check"></i>&nbsp;Ver detalles</button>
                                    <button type="button" class="btn bg-info" data-toggle="modal" data-target="#exampleModallinadevida"><i class="fas fa-eye"></i> Agregar una Observacion a la linea de vida</button>
                                    <?php
                                        if($contarASTUCA > 0){
                                            ?>
                                            <button type="button" class="btn bg-primary" id="btnVistaAST"><i class="fas fa-eye"></i> Revisar AST</button>
                                            <script>
                                                document.getElementById('btnVistaAST').addEventListener('click', function() {
                                                    const idTG = "<?php echo $idTG; ?>";

                                                    // Crear un formulario temporal
                                                    const form = document.createElement('form');
                                                    form.method = 'POST';
                                                    form.action = '../sdo-pdf/SDO-AST-UCAS.php';
                                                    form.target = '_blank'; // Abre en nueva pestaña

                                                    // Crear campo oculto con el valor de idTG
                                                    const input = document.createElement('input');
                                                    input.type = 'hidden';
                                                    input.name = 'idTG';
                                                    input.value = idTG;
                                                    form.appendChild(input);

                                                    // Agregar al documento, enviar y luego eliminar
                                                    document.body.appendChild(form);
                                                    form.submit();
                                                    document.body.removeChild(form);
                                                });
                                            </script>
                                            <?php
                                        }
                                    ?>


                                    <div class="modal fade" id="exampleModallinadevida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-info">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Obs a la Linea de Vida</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="../sdo-funciones/ingresar-linea-vida.php" method="POST">
                                                    <input type="hidden" name="terminada" id="terminada" value="1">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="idTG" id="idTG" value="<?php echo $idTG ?>">
                                                        <label class="w-auto">Nombre Creador:</label>
                                                        <input type="text" readonly class="form-control" id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>">
                                                        <br>
                                                        <label class="w-auto">Ingresar Observacion:</label>
                                                        <textarea class="form-control" name="detalleObs" rows="12" id="detalleObs"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <fieldset class="border p-3" id="row4" name="row4" style="display: none">
                                    <legend class="w-auto">Avance de la OT</legend>
                                    <table id="observacionTotal" class="table table-bordered table-sm">
                                        <thead style="background-color: indianred; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Estado</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Obs</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT * FROM avances_trabajo_general WHERE idTG = $idTG AND detalleObs NOT LIKE ('%Se ha ingresado la orden de trabajo %')";
                                        $res = mysqli_query($conexion,$sql);
                                        while($row = $res->fetch_assoc()){
                                            $nomCreador = $row['nomCreador'];
                                            $detalleObs = $row['detalleObs'];
                                            $estadoAvance = $row['estadoAvance'];
                                            $fechaSubida = $row['fechaSubida'];
                                            $observacion = $row['observacion'];
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php
                                                    echo $fechaSubida;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    echo $nomCreador;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    echo $estadoAvance;
                                                    ?>
                                                </td>
                                                <td class="text-center" >
                                                    <?php
                                                    echo $detalleObs;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if($observacion == '' || empty($observacion)){
                                                        echo 'NO APLICA';
                                                    }else{
                                                        echo  $observacion;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: indianred; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha de Ingreso del Avance</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Estado</th>
                                            <th style="font-size: 15px">Detalle</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row5" name="row5" style="display: none">
                                    <legend class="w-auto">Observaciones Generales</legend>
                                    <table id="obsercacion2" class="table table-bordered table-sm">
                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlObs = "SELECT * FROM observacion_ot WHERE idTG = $idTG";
                                        $resObs = mysqli_query($conexion,$sqlObs);
                                        while($rowObs = $resObs->fetch_assoc()){
                                            $detalle = $rowObs['detalleObs'];
                                            $nomCreador1 = $rowObs['nomCreador'];
                                            $fechaObs = $rowObs['fecha'];
                                            ?>
                                            <tr class="text-center">
                                                <td><?php echo $fechaObs?></td>
                                                <td><?php echo $nomCreador1?></td>
                                                <td><?php echo $detalle?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <div class="row" id="rowParametrosPab" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body" style="text-align:center;">


                                                <fieldset>
                                                    <legend>Registros de Parametros de Pabellones</legend>


                                                    <div class="row text-center" style="display: flex; justify-content:center;">


                                                        <div class="col-lg-3 col-12" id="divAgregarParametros" name="divAgregarParametros">
                                                            <!-- small box -->
                                                            <button class="btn btn-block bg-gradient-success btn-md text-white" id="agregarParametros" name="agregarParametros" data-toggle="modal" data-target="#modalAgregar">
                                                                <i class="fas fa-plus"></i>&nbsp;Agregar Nueva Medición
                                                            </button>

                                                            <!-- Modal -->
                                                        </div>

                                                    </div>
                                                    <hr>

                                                    <div class="table-responsive">
                                                        <table style="font-size:80%" id="example2" class="table table-bordered table-hover">
                                                            <thead class="bg-secondary">
                                                            <tr>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Fecha</center>
                                                                </th>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Funcionario</center>
                                                                </th>
                                                                <?php

                                                                for ($i = 2; $i <= 10; $i++) {
                                                                    ?>
                                                                    <th style="vertical-align:middle;">
                                                                        <center>Pabellon <?php echo $i; ?></center>
                                                                    </th>
                                                                    <?php
                                                                }

                                                                ?>
                                                                <th>
                                                                    Enviar
                                                                </th>
                                                                <th>
                                                                    Modificar
                                                                </th>
                                                                <th>
                                                                    Eliminar
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                            <tfoot class="bg-secondary">
                                                            <tr>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Fecha</center>
                                                                </th>
                                                                <th style="vertical-align:middle;">
                                                                    <center>Funcionario</center>
                                                                </th>
                                                                <?php

                                                                for ($i = 2; $i <= 10; $i++) {
                                                                    ?>
                                                                    <th style="vertical-align:middle;">
                                                                        <center>Pabellon <?php echo $i; ?></center>
                                                                    </th>
                                                                    <?php
                                                                }

                                                                ?>

                                                                <th>
                                                                    Enviar
                                                                </th>
                                                                <th>
                                                                    Modificar
                                                                </th>
                                                                <th>
                                                                    Eliminar
                                                                </th>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>


                                                    <script>
                                                        $(document).ready(function () {
                                                            var columns = [
                                                                {"data": "fechaRegistroPab"},
                                                                {"data": "nomRegistroPab"},

                                                                // Otras columnas existentes

                                                                // Agregar columnas dinámicamente
                                                            ];

                                                            for (var i = 2; i <= 10; i++) {
                                                                columns.push({"data": "valorTotal" + i});
                                                                // Agrega más columnas según sea necesario
                                                            }
                                                            columns.push(
                                                                {
                                                                    "data": null,
                                                                    "render": function (data, type, row) {
                                                                        var buttonHTML;

                                                                        if (row.correoEnviado === 0) {
                                                                            buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '"><i class="fas fa-envelope"></i></button>';
                                                                        } else {
                                                                            buttonHTML = '<button class="btn btn-primary btn-confirm" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-envelope"></i></button>';
                                                                        }

                                                                        return buttonHTML;
                                                                    },
                                                                    "createdRow": function (row, data, dataIndex) {
                                                                        $(row).on('click', '.btn-confirm', function () {
                                                                            var button = $(this);
                                                                            var icon = button.find('i');

                                                                            // Cambiar el texto del botón a "Cargando"
                                                                            button.text('Cargando...');

                                                                            // Aquí puedes realizar cualquier acción adicional, como enviar una solicitud AJAX

                                                                            // Simular una operación asíncrona (puedes reemplazar esto con tu lógica real)
                                                                            setTimeout(function () {
                                                                                // Restaurar el botón a su estado original (habilitado y con el texto original)
                                                                                button.prop('disabled', false);
                                                                                icon.removeClass('fa-spinner fa-spin').addClass('fa-envelope');
                                                                                button.text('Enviar Correo');
                                                                            }, 2000); // Cambia 2000 por la cantidad de tiempo que tomará tu operación real
                                                                        });
                                                                    }
                                                                },

                                                                {
                                                                    "data": null,
                                                                    "render": function (data, type, row) {
                                                                        var buttonHTML;

                                                                        if (row.correoEnviado === 0) {
                                                                            buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '"><i class="fas fa-pen"></i></button>';
                                                                        } else {
                                                                            buttonHTML = '<button class="btn btn-warning btn-editar" data-id="' + row.idRegistroPab + '" disabled><i class="fas fa-pen"></i></button>';
                                                                        }

                                                                        return buttonHTML;
                                                                    }
                                                                },
                                                                {
                                                                    "data": null,
                                                                    "render": function (data, type, row) {
                                                                        return '<button class="btn btn-danger btn-eliminar" data-id="' + row.idRegistroPab + '"><i class="fas fa-trash"></i></button>';
                                                                    }
                                                                }
                                                            )

                                                            var example2 = $('#example2').DataTable({
                                                                "lengthChange": false,
                                                                "searching": true,
                                                                "autoWidth": false,
                                                                "responsive": true,
                                                                "ordering": false,
                                                                "language": {
                                                                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                                                                    "zeroRecords": "No se han encontrado Registros.",
                                                                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                                                                    "infoEmpty": "No existen Registros.",
                                                                    "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                                                                    "search": "Buscar:",
                                                                    "processing": "Procesando...",
                                                                    "paginate": {
                                                                        "previous": "Anterior",
                                                                        "next": "Siguiente"
                                                                    }
                                                                },
                                                                "ajax": {
                                                                    "url": "ajax-pabCorreo-listado.php", // Archivo PHP que contiene la consulta SQL
                                                                    "type": "POST",
                                                                    "data": function (d) {

                                                                    },
                                                                    "dataSrc": ""
                                                                },
                                                                "columns": columns,
                                                                "createdRow": function (row, data, dataIndex) {
                                                                    $('td', row).css({
                                                                        'text-align': 'center', // Centrar texto horizontalmente
                                                                        'vertical-align': 'middle', // Centrar texto verticalmente
                                                                        'border': '1px solid #dddddd' // Añadir borde
                                                                        // Añade más estilos según tus necesidades
                                                                    });
                                                                }
                                                            });
                                                            // Manejar clic en el botón de confirmación
                                                            $('#example2').on('click', '.btn-confirm', function () {
                                                                var id = $(this).data('id');

                                                                // Mostrar swal.alert de confirmación
                                                                Swal.fire({
                                                                    title: '¿Estás seguro de enviar Parametros a Pabellon?',
                                                                    text: 'Esta acción no se puede revertir.',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Sí, confirmar'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Crear un formulario dinámico
                                                                        var form = $('<form>', {
                                                                            'action': '../sdo-funciones/pabellones_enviarCorreo.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                            'method': 'POST'
                                                                        });

                                                                        // Agregar un campo oculto para el ID
                                                                        form.append($('<input>', {
                                                                            'type': 'hidden',
                                                                            'name': 'idRegPab',
                                                                            'value': id
                                                                        }));

                                                                        // Agregar el formulario al documento y enviarlo
                                                                        form.appendTo('body').submit();


                                                                        // Realizar acciones adicionales si el usuario confirma
                                                                        // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
                                                                        // confirmarAccion(id);
                                                                    }
                                                                });
                                                            });

                                                            $('#example2').on('click', '.btn-editar', function () {
                                                                var idRegistro = $(this).data('id');

                                                                // Hacer una petición al servidor para obtener los datos del registro usando AJAX
                                                                $.ajax({
                                                                    url: 'sdo-includes/parametros_pabellon_modificar.php',
                                                                    type: 'POST',
                                                                    data: {id: idRegistro},
                                                                    dataType: 'json',
                                                                    success: function (data) {
                                                                        console.log('Respuesta del servidor:', data);

                                                                        // Llenar el modal con los datos obtenidos
                                                                        for (var key in data) {
                                                                            var value = data[key];
                                                                            $('#' + key).val(value);
                                                                            console.log('Asignando valor a ' + key + ': ' + value);
                                                                        }

                                                                        $('#modalEditar').modal('show'); // Abre el modal
                                                                    },
                                                                    error: function (error) {
                                                                        console.log(error);
                                                                        // Manejar errores si es necesario
                                                                    }
                                                                });

                                                            });

                                                            $('#example2').on('click', '.btn-eliminar', function () {
                                                                var id = $(this).data('id');

                                                                // Mostrar swal.alert de confirmación
                                                                Swal.fire({
                                                                    title: '¿Estás seguro de eliminar estos Parametros?',
                                                                    text: 'Esta acción no se puede revertir.',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Sí, confirmar'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Crear un formulario dinámico
                                                                        var form = $('<form>', {
                                                                            'action': '../sdo-funciones/pabellones_eliminarParametros.php', // Reemplaza 'tu_url_destino' con la URL de destino
                                                                            'method': 'POST'
                                                                        });

                                                                        // Agregar un campo oculto para el ID
                                                                        form.append($('<input>', {
                                                                            'type': 'hidden',
                                                                            'name': 'idRegPab',
                                                                            'value': id
                                                                        }));

                                                                        // Agregar el formulario al documento y enviarlo
                                                                        form.appendTo('body').submit();


                                                                        // Realizar acciones adicionales si el usuario confirma
                                                                        // Puedes agregar lógica aquí o llamar a una función, por ejemplo:
                                                                        // confirmarAccion(id);
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>

                                                </fieldset>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <form class="" method="post" action="../sdo-funciones/pabellones_agregarUltMedicion.php">
                                                <input type="hidden" id="terminadas" name="terminadas" value="1">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="modalAgregarLabel">Agregar Nueva Medición</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre del Tecnico: <code>*</code></label>
                                                        <input type="text" readonly="readonly" class="form-control " id="addNombre" name="addNombre" placeholder="Nombre de Personal" value="<?php echo $nomUsuario; ?>" required>
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Fecha de Registro: <code>*</code></label>
                                                        <input style="text-align:left;" readonly="readonly" type="text" style="text-align:center;" class="form-control " id="addFecha" name="addFecha" value="<?php echo $fecha; ?>" required>
                                                    </div>
                                                    <?php
                                                    for ($k = 2; $k < 11; $k++) {
                                                        $valorTemp = 'addTempPabellon' . $k;
                                                        $valorHumedad = 'addHumedadPabellon' . $k;

                                                        $nomValorTemp = 'Temperatura Pabellon ' . $k;
                                                        $nomValorHumedad = 'Humedad Pabellon ' . $k;
                                                        ?>
                                                        <div class="form-row">
                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                <label for="<?php echo $valorTemp; ?>"><?php echo $nomValorTemp; ?>:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-temperature-high"></i></span>
                                                                    </div>
                                                                    <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorTemp; ?>" name="<?php echo $valorTemp; ?>" placeholder="<?php echo $nomValorTemp; ?>">
                                                                </div>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                <label for="<?php echo $valorHumedad; ?>"><?php echo $nomValorHumedad; ?>:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                                                                    </div>
                                                                    <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorHumedad; ?>" name="<?php echo $valorHumedad; ?>" placeholder="<?php echo $nomValorHumedad; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="nomRegistroPab" id="nomRegistroPab" value="<?php echo mb_convert_case($nomUsuario, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                    <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo mb_convert_case($folioTG, MB_CASE_UPPER, 'UTF-8'); ?>">
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;Registrar Medicion</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <fieldset id="row1" name="row1" class="border p-3">
                                    <legend class="w-auto">&nbsp;Informaci&oacute;n de la Orden de Trabajo</legend>
                                    <h5>
                                        <b>Folio Trabajo: </b>
                                        <?php
                                        echo $folioTG;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha Trabajo:</b>
                                        <?php
                                        echo $fechaTG3
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Fecha de programacion del trabajo:</b>
                                        <?php
                                        echo $fechaProgram2;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Turno Trabajo:</b>
                                        <?php
                                        echo $turnoTG;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Personal Solicitante:</b>
                                        <?php
                                        echo $solicitanteTG;
                                        ?>
                                    </h5>
                                    <h5>
                                        <b>Estado :</b>
                                        <?php echo $estadoTG; ?>
                                    </h5>
                                    <h5>
                                        <b>Persona Asignada:</b>
                                        <?php echo $tecnico1?>
                                    </h5>
                                    <?php
                                    if($ticket == 0 || empty($ticket)){
                                    }else{
                                        ?>
                                        <h5>
                                            <b>Ticket Asociado:</b>
                                            <?php echo $ticket?>
                                        </h5>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                        if($idDepartamento == 1){
                                            if($detalleTurnoRonda == '' || empty($detalleTurnoRonda)){
                                            }else{
                                                ?>
                                                <h5>
                                                    <b>Cantidad de Rondas hechas en esta Orden de Trabajo:
                                                        <?php echo $contarRondas?></b>
                                                </h5>
                                                <?php
                                            }
                                        }
                                    ?>

                                    <?php
                                    if($departamento == 1){
                                        ?>
                                        <?php
                                    }else{
                                        ?>
                                        <!--<h5><b>Cantidad de Hojas Técnicas que lleva esta Orden de Trabajo es: <?php echo $contarHT?></b></h5>-->
                                        <?php
                                    }
                                    ?>
                                    <?php
                                        if($idDepartamento == 1){
                                            if($formularioRondas > 0){
                                                ?>
                                                <h5>
                                                    <b>Formulario de rondas
                                                        <?php
                                                        if($formularioRondas == 1){
                                                            echo 'con Consola de Alarmas';
                                                        }else if($formularioRondas == 2){
                                                            echo 'con parametros de unidades manejadoras de aire BMS';
                                                        }

                                                        ?></b>
                                                </h5>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                                if($servicio > 0){
                                                    ?>
                                                    <h5>
                                                        <b>Unidad o Servicio: <?php echo $buscarNombreServicio?></b>
                                                    </h5>
                                                    <?php
                                                }
                                        }



                                    ?>





                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row8" name="row8" style="display: none">
                                    <legend class="w-auto">Linea de Vida de la OT</legend>
                                    <table class="table table-bordered table-sm">
                                        <thead style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Detalle</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlLinea = "SELECT * FROM linea_vida_ot WHERE idTG = $idTG";
                                        $resLinea = mysqli_query($conexion,$sqlLinea);
                                        while($rowLinea = $resLinea->fetch_assoc()){
                                            $detalleLinea = $rowLinea['detalle'];
                                            $nomCreador1Linea = $rowLinea['nomCreador'];
                                            $fechaObsLinea = $rowLinea['fechaSubida'];
                                            ?>
                                            <tr class="text-center">
                                                <td><?php echo $fechaObsLinea?></td>
                                                <td><?php echo $nomCreador1Linea?></td>
                                                <td><?php echo $detalleLinea?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot style="background-color: lightslategray; color: white" class="text-center">
                                        <tr>
                                            <th style="font-size: 15px">Fecha</th>
                                            <th style="font-size: 15px">Creador</th>
                                            <th style="font-size: 15px">Obs</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row10" name="row10" style="display: none">
                                    <legend class="w-auto">Tabla de HT</legend>
                                    <table  class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Ticket Asociado</center>
                                            </th>
                                            <th>
                                                <center>Folio<center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador<center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante<center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>
                                            <th>
                                                <center>Ver HT<center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $query = "SELECT * FROM ordenes_trabajos_general WHERE idDepartamento = '$idDepartamento' AND noOrdenTrabajo = '$folioTG' ORDER BY fechaOrden DESC";

                                        $res = mysqli_query($conexion, $query);

                                        $numOrden = 1;

                                        while ($row = $res->fetch_assoc()) {

                                            $nombreOrden = $row['nomOrden'];
                                            ?>

                                            <tr>
                                                <td style="vertical-align:middle;">
                                                    <center><?php echo $numOrden; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center><?php

                                                        $ticketAsociado = mb_strtoupper($row['ticketAsociado']);

                                                        if (!empty($ticketAsociado)) {
                                                            echo $ticketAsociado;
                                                        } else {
                                                            echo "SIN INFORMACIÓN";
                                                        }

                                                        ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <?php
                                                    $folioOrden = mb_strtoupper($row['folioOrden']);
                                                    echo $folioOrden;

                                                    ?>
                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <center>
                                                        <?php

                                                        $nomTecnicoRecepcionador = mb_strtoupper($row['nomTecnicoRecepcionador']);
                                                        echo $nomTecnicoRecepcionador;

                                                        ?>
                                                    </center>

                                                </td>
                                                <td style="vertical-align:middle;" align="justify">
                                                    <center>
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['nomSolicitante']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center><?php if ($row['fechaOrden'] == 0) {
                                                            echo "No registra fecha";
                                                        } else {
                                                            $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                            $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                        } ?></center>
                                                </td>
                                                <td style="vertical-align:middle;">
                                                    <center>
                                                        <?php
                                                        $idOrden = $row['idOrden'];
                                                        $idOrden = trim($idOrden);
                                                        if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">

                                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Hoja Técnica">

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>
                                                    </center>
                                                </td>


                                            </tr>
                                            <?php

                                            $numOrden = $numOrden + 1;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>N°</center>
                                            </th>
                                            <th>
                                                <center>Ticket Asociado</center>
                                            </th>
                                            <th>
                                                <center>Folio<center>
                                            </th>
                                            <th>
                                                <center>Tec. Recepcionador<center>
                                            </th>
                                            <th>
                                                <center>Nombre del Solicitante<center>
                                            </th>
                                            <th>
                                                <center>Fecha<center>
                                            </th>
                                            <th>
                                                <center>Ver HT<center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <fieldset class="border p-3" id="row9" name="row9" style="display: none">
                                    <legend class="w-auto">Tabla de OT</legend>
                                    <table class="table table-bordered table-hover">
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
                                                <center>Funcionario Asig.</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver OT</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryTG = "SELECT * FROM trabajos_general WHERE estadoTG = 'Trabajo Terminado' AND departamento = $idDepartamento AND folioTG = '$folioTG' ORDER BY fechaTG DESC";

                                        $resTG = mysqli_query($conexion, $queryTG);

                                        $numTG = 1;

                                        $colorTG = "";

                                        while ($rowTG = $resTG->fetch_assoc()) {
                                            $tecnico = $rowTG['tecnico1'];
                                            $fechaTG = $rowTG['fechaTG'];

                                            $turnoTG = $rowTG['turnoTG'];
                                            $turnoTG = trim($turnoTG);

                                            $solicitanteTG = $rowTG['solicitanteTG'];
                                            $solicitanteTG = trim($solicitanteTG);

                                            $idTG = $rowTG['idTG'];
                                            $idTG = trim($idTG);

                                            $estadoTG = trim($rowTG['estadoTG']);
                                            $folioTG = $rowTG['folioTG'];

                                            if($estadoTG == 'Trabajo terminado con exito'){
                                                $colorTR = "background-color: #FFC107; color:black;";
                                            }else{
                                                $colorTR = "";
                                            }

                                            ?>

                                            <tr style="<?php echo $colorTR?>">
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php if ($fechaTG == 0) {
                                                        echo "No registra fecha";
                                                    } else {

                                                        $registrofechaTG = date('d/m/Y', strtotime(str_replace('/', '-', $fechaTG)));
                                                        $registroHoraTG = date('H:i', strtotime($fechaTG));

                                                        // echo $registrofechaAC . ' a las ' . $registroHoraMC.' hrs.';

                                                        echo $registrofechaTG . ' a las ' . $registroHoraTG . ' hrs';
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
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <?php
                                                    if(empty($tecnico)){
                                                        echo "No registra funconario";
                                                    }else{
                                                        echo $tecnico;
                                                    }
                                                    ?>
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
                                                <td>
                                                    <form action="../sdo-pdf/orden-trabajo.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                        <input type="submit" id="inputMozi" class="btn btn-block btn-info" value="Ver Orden de Trabajo">
                                                    </form>
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
                                                <center>Funcionario Asig.</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Estado</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Ver OT</center>
                                            </th>

                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <?php
                                    if($contarchecklistvf > 0){
                                        ?>
                                        <br>
                                        <fieldset id="checklistvf" name="checklistvf" class="border p-3" style="display: none">
                                            <legend class="w-auto">Tabla de Check List - VF</legend>
                                            <table class="table table-bordered table-sm" style="text-align: center">
                                                <thead class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sqlCheckListVF = "SELECT * FROM datos_vf WHERE idTG = $idTG";
                                                $resCheckListVF = mysqli_query($conexion,$sqlCheckListVF);
                                                $contarVF = 1;
                                                while($rowCheckListVF = $resCheckListVF->fetch_assoc()){
                                                    $fechaVF = $rowCheckListVF['fecha'];
                                                    $folioVF = $rowCheckListVF['folio'];
                                                    $idVF = $rowCheckListVF['id'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $contarVF?></td>
                                                        <td><?php echo $fechaVF?></td>
                                                        <td><?php echo $folioVF?></td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-VF.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                <input type="hidden" id="idVF" name="idVF" value="<?php echo $idVF?>">
                                                                <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $contarVF++;
                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="text-center bg-gradient-secondary">
                                                <tr>
                                                    <th style="font-size: 15px">N°</th>
                                                    <th style="font-size: 15px">Fecha de Ingreso</th>
                                                    <th style="font-size: 15px">Folio</th>
                                                    <th style="font-size: 15px">Visualizar</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </fieldset>
                                        <?php
                                    }
                                ?>
                                <?php
                                if($contarchecklistvav > 0){
                                    ?>
                                    <br>
                                    <fieldset id="checklistvav" name="checklistvav" class="border p-3" style="display: none">
                                        <legend class="w-auto">Tabla de Check List - VAV</legend>
                                        <table class="table table-bordered table-sm" style="text-align: center">
                                            <thead class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">Folio</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlCheckListVav = "SELECT * FROM datos_vav WHERE idTG = $idTG";
                                            $resCheckListVav = mysqli_query($conexion,$sqlCheckListVav);
                                            $contarVav = 1;
                                            while($rowCheckListVav = $resCheckListVav->fetch_assoc()){
                                                $fechaVav = $rowCheckListVav['fecha'];
                                                $folioVav = $rowCheckListVav['folio'];
                                                $idVav = $rowCheckListVav['idVav'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $contarVav?></td>
                                                    <td><?php echo $fechaVav?></td>
                                                    <td><?php echo $folioVav?></td>
                                                    <td>
                                                        <form action="../sdo-pdf/SDO-CheckList-VAV.php" target="_blank" method="POST">
                                                            <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                            <input type="hidden" id="idVAV" name="idVAV" value="<?php echo $idVav?>">
                                                            <button type="submit" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                                $contarVav++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">Folio</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>
                                    <?php
                                }
                                ?>
                                <?php
                                if($contarchecklistucas > 0){
                                    ?>
                                    <br>
                                    <fieldset class="border p-3" id="checklistucas" style="display: none">
                                        <legend class="w-auto">Tabla de Check List - Ucas</legend>
                                        <table id="checklistucas" class="table table-bordered table-sm" style="text-align: center">
                                            <thead class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">UCA</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $countUCAS = 1;
                                            $sqlUcas = "SELECT * FROM datos_checklist_ucas WHERE idTG = $idTG ORDER BY uca ASC";
                                            $resUcas = mysqli_query($conexion,$sqlUcas);
                                            while($rowUcas = $resUcas->fetch_assoc()){
                                                $fechaUca = $rowUcas['fecha'];
                                                $uca = $rowUcas['uca'];
                                                $idCheckUCA = $rowUcas['id'];
                                                $isVisadoUCA = $rowUcas['isVisado'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $countUCAS?></td>
                                                    <td><?php echo $fechaUca?></td>
                                                    <td><?php echo $uca?></td>
                                                    <td>
                                                        <form target="_blank" action="../sdo-pdf/SDO-CheckList-Ucas.php" method="POST">
                                                            <input type="hidden" name="idCheck" value="<?php echo $idCheckUCA?>">
                                                            <input type="hidden" name="idTG" value="<?php echo $idTG?>">
                                                            <button type="submit" class="btn btn-sm bg-success"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                                $countUCAS++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="text-center bg-gradient-secondary">
                                            <tr>
                                                <th style="font-size: 15px">N°</th>
                                                <th style="font-size: 15px">Fecha de Ingreso</th>
                                                <th style="font-size: 15px">UCA</th>
                                                <th style="font-size: 15px">Visualizar</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>
                                    <?php
                                }
                                ?>
                                <?php
                                if($departamento == 1){
                                    ?>
                                    <div  name="row2" id="row2" class="row">
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en BMS</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en CCTV</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCamara; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en LLE</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoLLE; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Acceso</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoCA; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar en Control de Rondas</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTurnoRonda; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle de Otros Trabajos</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleOtros" class="form-control" rows="15"><?php echo $detalleOtros; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                }else{
                                    ?>
                                    <div id="row6">
                                        <div class="col-md-12">
                                            <fieldset class="border p-3">
                                                <legend class="w-auto">&nbsp;Detalle del Trabajo a Realizar</legend>
                                                <textarea id="detalleTG" style="resize:none; font-size:85%" readonly="readonly" name="detalleTG" class="form-control" rows="15"><?php echo $detalleTG; ?></textarea>
                                                <br>
                                            </fieldset>
                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>

                                <fieldset name="row3" id="row3"  class="border p-3">
                                    <legend class="w-auto">&nbsp;Acciones a Realizar</legend>
                                    <div class="row justify-content-md-center">
                                        <?php
                                        if($estadoTG == 'Trabajo Terminado' || $tecnico1 != $nomUsuario){
                                        }else{
                                            ?>
                                            <div class="col-md-2">
                                                <form method="POST" action="ingresar_avance_orden.php">
                                                    <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                    <button type="submit" id="inputMozi"  class="btn btn-block bg-primary"><i class="fas fa-pen"></i>&nbsp;&nbsp;Agregar Avance del Trabajo</button>
                                                </form>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-2">
                                            <form action="visualizardetallesterminadas.php" method="POST" style="display: inline">
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
                                                    <b>No hay Archivo Adjunto Subido por el Profesional</b>
                                                    <!--<button type="submit" class="btn btn-lg" disabled style="background-color: indigo; color: white"><i class="fas fa-download"></i></button> -->
                                                    <?php
                                                }else{
                                                    ?>
                                                    <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                    <input type="hidden" name="idTG1" id="idTG1" value="<?php echo $idTG?>">
                                                    <input type="hidden" name="folio" id="folio" value="<?php echo $folioTG?>">
                                                    <button type="submit" class="btn  btn-block" title="Descargar el Archivo Adjunto " style="background-color: indigo; color: white"><i class="fas fa-download"></i></button>

                                                    <?php
                                                }
                                                ?>

                                            </form>
                                        </div>
                                        <?php
                                        $buscarArchivostecnico = buscararchivostecnicos($idTG);
                                        if($buscarArchivostecnico > 0){
                                            ?>
                                            <br>
                                            <div class="col-md-4">
                                                <form action="visualizardetallesterminadas.php" method="POST">
                                                    <input type="hidden" id="folioTGDescargar" name="folioTGDescargar" value="<?php echo $folioTG?>">
                                                    <button type="submit" class="btn btn-block bg-gradient-success" style="color: white"><i class="fas fa-download"></i> Descargar Archivos Subidos por el Funcionario Asignado</button>
                                                </form>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                                <div class="modal fade" id="derivar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title" id="exampleModalLabel">Cambiar Persona Asignada</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/cambioasigando-ot.php" method="POST">
                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder" style="font-size: 15px">Usuario Creador: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-border" readonly id="nomCreador" name="nomCreador" value="<?php echo $nomUsuario ?>" required>
                                                        <br>
                                                        <label for="exampleInputBorder" style="font-size: 15px">Nueva Persona a Asignar: <code>*</code></label>
                                                        <select id="selectPersona" required name="selectPersona" class="form-control form-control-border">
                                                            <?php
                                                            $sqlPerso = "SELECT * FROM personal WHERE departamentoPersonal = $idDepartamento";
                                                            $resPerso = mysqli_query($conexion,$sqlPerso);
                                                            ?>
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            <?php
                                                            while($rowPerso = $resPerso->fetch_assoc()){
                                                                $nomPersonal = $rowPerso['nomPersonal'];
                                                                ?>
                                                                <option value="<?php echo $nomPersonal?>"><?php echo $nomPersonal?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <br>
                                                        <label for="exampleInputBorder" style="font-size: 15px">Justifique el cambio. <code>*</code></label>
                                                        <textarea id="detalleObs" style="resize:none;" name="detalleObs" class="form-control" rows="15" required></textarea>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                </div>
                                                <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                            <form class="" method="post" action="../sdo-funciones/pabellones_actualizarMedicion.php">
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title" id="modalEditarLabel">Editar Medición</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php
                                                                    for ($k = 2; $k < 11; $k++) {
                                                                        $valorTemp = 'tempPabellon' . $k;
                                                                        $valorHumedad = 'humedadPabellon' . $k;

                                                                        $nomValorTemp = 'Temperatura Pabellon ' . $k;
                                                                        $nomValorHumedad = 'Humedad Pabellon ' . $k;
                                                                        ?>
                                                                        <div class="form-row">
                                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                                <label for="<?php echo $valorTemp; ?>"><?php echo $nomValorTemp; ?>:</label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-temperature-high"></i></span>
                                                                                    </div>
                                                                                    <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorTemp; ?>" name="<?php echo $valorTemp; ?>" placeholder="<?php echo $nomValorTemp; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div style="text-align:left;" class="form-group col-md-6 col-12">
                                                                                <label for="<?php echo $valorHumedad; ?>"><?php echo $nomValorHumedad; ?>:</label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                                                                                    </div>
                                                                                    <input type="number" min="0" step=".1" class="form-control" required id="<?php echo $valorHumedad; ?>" name="<?php echo $valorHumedad; ?>" placeholder="<?php echo $nomValorHumedad; ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <!-- Agrega los campos ocultos para enviar información necesaria al servidor -->
                                                                    <input type="hidden" name="idRegistroPab" id="idRegistroPab">
                                                                    <input type="hidden" name="nomModificaPab" id="nomModificaPab" value="<?php echo $nomUsuario; ?>">
                                                                    <input type="hidden" name="ordenRegistroPab" id="ordenRegistroPab" value="<?php echo $folioTG; ?>">
                                                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>&nbsp;&nbsp;Actualizar Medición</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp;Cerrar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
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
    $(function () {
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
</body>
</html>

