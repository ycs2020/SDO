<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = $_SESSION['nomUsuario'];

date_default_timezone_set('America/Santiago');

if (isset($_POST['idSoliCompra1'])){
    $idSoliCompra1 = $_POST['idSoliCompra1'];
    if($idSoliCompra1 > 0){
        ini_set("memory_limit", "5256M");
        $zip = new ZipArchive();
        $dir = "../sdo-archivo-orden-de-compra-panol-cc/id-$idSoliCompra1".'/';
        $archivoZip = "Orden de Compra-" . $idSoliCompra1 . ".zip";
        if ($zip->open($archivoZip, ZipArchive::CREATE) === true) {
            agregar_zipSC($dir, $zip);
            $zip->close();

            if (file_exists($archivoZip)) {
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

                $descargaConfirma .= "Y";
            } else {

                $descargaConfirma .= "N";
            }
        }
    }
}

if (isset($_POST['rutaArchivoRE'])) {
    $file = $_POST['rutaArchivoRE'];
    $nomArchivo = $_POST['nomArchivoRE'];
    if (is_file($file)) {
        $filename = $nomArchivo; // el nombre con el que se descargará, puede ser diferente al original
        /*header("Content-Type: application/octet-stream");*/
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($file);
    } else {
        die("Error: no se encontró el archivo '$file'");
    }
}

if (isset($_POST['idSoliCompra']) || isset($_SESSION['idSoliCompra'])) {
    $idSoliCompra = isset($_POST['idSoliCompra']) ? trim($_POST['idSoliCompra']) : trim($_SESSION['idSoliCompra']);
    if ($idSoliCompra == 0 || empty($idSoliCompra)) {
        header('Location: soliCompra_sdo_administracion2.php?errorCargarSolicitud=true');
        exit();
    } else {
        $datosSolicitud = getSoliCompra_sdo_cc($idSoliCompra);

        $idSoliCompra2 = $datosSolicitud['idSoliCompra'];
        $numSoliCompra = $datosSolicitud['numSoliCompra'];
        $folioSoliCompra = $datosSolicitud['folioSoliCompra'];
        $fechaSoliCompra = $datosSolicitud['fechaSoliCompra'];
        $personalSoliCompra = $datosSolicitud['personalSoliCompra'];
        $unidadSoliCompra = $datosSolicitud['unidadSoliCompra'];
        $emailSoliCompra = $datosSolicitud['emailSoliCompra'];
        $argumentosSoliCompra = $datosSolicitud['argumentosSoliCompra'];
        $adjuntaCotizacion = $datosSolicitud['adjuntaCotizacion'];
        $estadoSoliCompra = $datosSolicitud['estadoSoliCompra'];

        $fechaCreacion = fechaEspañol2(strtotime($fechaSoliCompra));
        $horaCreacion = date('H:i', strtotime($fechaSoliCompra));

        $_SESSION['idSoliCompra'] = $idSoliCompra;
    }
} else {
    header('Location: soliCompra_sdo_administracion.php?errorCargarSolicitud=true');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seguimiento de la solicitud - Control Centralizado</title>

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
    <script LANGUAGE="JavaScript">
        var cuenta = 0;

        function enviado() {
            if (cuenta == 0) {
                cuenta++;
                return true;
            } else {
                alert("El check-list está siendo enviado, espere porfavor! .");
                return false;
            }
        }
        //
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Seguimiento de la solicitud - Control Centralizado</h4>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- MENSAJES DE ACCIONES -->
        <?php
        $alerts = [
            'errorNumSDA' => ['error', 'Error', 'Error al Obtener Numero SDA'],
            'exitoNumInterno' => ['success', 'Exito', 'Número Interno Asignado Correctamente'],
            'errorNumInterno' => ['error', 'Error', 'Error Al Asignar Número Interno SDA'],
            'exitoEntrega' => ['success', 'Exito', 'Exito al cambiar estado de la solicitud "Entregado a la SDA"'],
            'errorEntrega' => ['error', 'Error', 'Error al cambiar estado de la solicitud "Entregado a la SDA'],
            'exitoEditEstado' => ['success', 'Exito', 'Exito cambio de estado de la SDA'],
            'errorEditEstado' => ['error', 'Error', 'Error en el cambio de estado de la SDA'],
            'exitoEntregaAbastecimiento' => ['success', 'Exito', 'Exito al cambiar el estado de solicitud a "Derivado a Abastecimiento"'],
            'errorEntregaAbastecimiento' => ['error', 'Error', 'Error al cambiar el estado de solicitud a "Derivado a Abastecimiento"'],
            'exitoOrdenCompra' => ['success', 'Exito', 'Exito al añadir ordenes de compra'],
            'errorOrdenCompra' => ['error', 'Error', 'Error al añadir ordenes de compra'],
            'ExitoEditOrdenCompra' => ['success', 'Exito', 'Exito al editar ordenes de compra'],
            'ErrorEditOrdenCompra' => ['error', 'Error', 'Error al editar ordenes de compra'],
            'exitoEstado' => ['success','Exito','Se ha ingresado correctamente'],
            'errorEstado' => ['error','Error','Error al ingresar. Reintente'],
            'noSubioCarpeta' => ['error','Error','No se ha logrado subir el archivo a la carpeta.'],
            'errorFormato' => ['error','Error','El formato seleccionado no se encuentra permitido'],
            'sinArchivo' => ['error','Error','No se ha subido ningun archivo. Reintente']
        ];

        foreach ($alerts as $key => $alert) {
            if (isset($_GET[$key])) {
                echo "<script>
                Swal.fire({
                    icon: '{$alert[0]}',
                    title: '{$alert[1]}',
                    text: '{$alert[2]}'
                });
              </script>";
                break; // Si solo debe mostrar una alerta a la vez, de lo contrario, elimina esto
            }
        }

        ?>

        <!-- Main content -->
        <section class="content" style="font-size: 10pt;">
            <div class="container-fluid">

                <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="soliCompra_sdo_administracion_nuevo.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>


                            </div>
                            <div style="font-size:85%" class="card-body">
                                <div class="row">
                                    <div style="font-size:100%" class="col-md-12">

                                        <div class="table-responsive">

                                            <table id="dataSCSDO" class="table table-bordered text-center" style="vertical-align: middle; justify-content: center;">

                                                <thead class="bg-gradient-info">
                                                <tr>
                                                    <th>FOLIO:</th>
                                                    <th>FECHA:</th>
                                                    <th>FUNCIONARIO:</th>
                                                    <th>UNIDAD:</th>
                                                    <th>CORREO:</th>
                                                    <th>ESTADO:</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold"><?php echo $folioSoliCompra; ?></td>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold">
                                                        <?php
                                                        $fechaSoliCompra2 = date("d-m-Y", strtotime($fechaSoliCompra));
                                                        $horaSoliCompra = date("H:i", strtotime($fechaSoliCompra));

                                                        $fechaCompleta = $fechaSoliCompra2 . ' a las ' . $horaSoliCompra . ' hrs.';
                                                        echo $fechaCompleta;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold">
                                                        <?php
                                                        $personalSoliCompra = explode(' (', $personalSoliCompra);

                                                        if (empty($personalSoliCompra[1])) {
                                                            $personalSoliCompra2 = $personalSoliCompra[0];
                                                        } else {
                                                            $personalSoliCompra2 = $personalSoliCompra[0] . '<br>(' . $personalSoliCompra[1];
                                                        }
                                                        echo $personalSoliCompra2;
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold"><?php echo $unidadSoliCompra; ?></td>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold"><?php echo $emailSoliCompra; ?></td>
                                                    <td style="vertical-align: middle; justify-content: center;" class="font-weight-bold"><?php echo $estadoSoliCompra; ?></td>
                                                </tr>
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                                <hr>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#tabControl" role="tab" aria-controls="tabControl" aria-selected="true">Men&uacute; de Control Centralizado</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#tabEspecialidades" role="tab" aria-controls="tabEspecialidades" aria-selected="false">Men&uacute; Generico</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="tabControl" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <label>Cambiar de Estado de la Solicitud</label>
                                        <div class="row">
                                            <div class="col-12 col-sm-4">
                                                <?php
                                                if ($estadoSoliCompra == 'Solicitada a Secretaria SDO' || $estadoSoliCompra == 'Revisada por Secretaría SDO') {
                                                    ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregaSDA" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn disabled bg-primary btn-block sda" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a la SDA</b></button>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <?php

                                                if ($estadoSoliCompra == 'Recibido por pañol' || $estadoSoliCompra == 'Solicitud Completada') {
                                                    ?>
                                                    <button class="btn bg-primary   btn-block sda" disabled style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Recibido por Pañol</b></button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn bg-primary btn-block sda" data-toggle="modal" data-target="#entregapanol" style="float:right;"><i class="fas fa-file-import"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Recibido por Pañol</b></button>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <!--<div class="col-12 col-sm-4">
                                                <button class="btn bg-info btn-block " data-toggle="modal" data-target="#valor" style="float:right; margin-top: 10px"><i class="far fa-money-bill-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Valor orden de compra</b></button>
                                            </div>-->
                                            <?php
                                            if ($estadoSoliCompra == 'Entregado a la S.D.A' || $estadoSoliCompra == 'Autorizado por la S.D.A' || $estadoSoliCompra == 'Derivado a Abastecimiento') {
                                                ?>
                                                <div class="col-12 col-sm-4">
                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {

                                                        if (!empty($numInternoSDA)) {
                                                            ?>
                                                            <button type="button" data-toggle="modal" data-target="#editarAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" data-toggle="modal" data-target="#modalAsignarNumero" id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                            <?php
                                                        }
                                                    } else {


                                                        if (!empty($numInternoSDA)) {
                                                            ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Editar Numero Interno SDA</b> </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" disabled id="inputMozi" class="btn bg-info btn-block" style="float:right;"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Numero Interno SDA</b> </button>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <?php

                                                    if ($estadoSoliCompra !== 'Derivado a Abastecimiento') {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalCambiaEstado" id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" disabled id="inputMozi" class="btn bg-warning btn-block" style="float:right;"><i class="fas fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Estado SDA</b> </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <?php
                                                    if ($estadoSoliCompra == 'Autorizado por la S.D.A') {
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#modalAbastecimiento" id="inputMozi" class="btn bg-danger btn-block" style="float:right;"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Entregado a Abastecimiento</b> </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            $buscararchivocoti = buscararchivocoticc($idSoliCompra);
                                            if($buscararchivocoti > 0){
                                                ?>

                                                <div class="col-12 col-sm-4">
                                                    <form action="soliCompra_sdo_seguimiento_nuevo.php" method="POST">
                                                        <input type="hidden" id="idSoliCompra1" name="idSoliCompra1" value="<?php echo $idSoliCompra?>">
                                                        <button type="submit" class="btn btn-block bg-success"><i class="fas fa-download"></i> Descargar Orden de Compra</button>
                                                    </form>
                                                </div>

                                                <?php
                                            }
                                            ?>
                                            <div class="col-12 col-sm-4">
                                                <form action="soliCompra_sdo_seguimiento_nuevo.php" method="POST">
                                                    <?php

                                                    $sqlSelect = "SELECT * FROM archivos_solicompra_material_resumen WHERE idSoliCompra = '$idSoliCompra'";
                                                    $resSelect = mysqli_query($conexion, $sqlSelect);

                                                    while ($rowSelect = $resSelect->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['nomArchivoRE'] = $rowSelect['nomArchivoRE'];
                                                        $datos['rutaArchivoRE'] = $rowSelect['rutaArchivoRE'];
                                                        $datos['extArchivo'] = $rowSelect['extArchivo'];

                                                        $nomArchivoRE = $datos['nomArchivoRE'];
                                                        $rutaArchivoRE = $datos['rutaArchivoRE'];
                                                        $extArchivo = $datos['extArchivo'];

                                                        ?>
                                                        <input type="hidden" name="rutaArchivoRE" value="<?php echo $rutaArchivoRE ?>">
                                                        <input type="hidden" name="nomArchivoRE" value="<?php echo $rutaArchivoRE ?>">
                                                        <button class="btn btn-warning btn-block" style="float:right; margin-top: 10px"><i class="fas fa-download"></i> <b><?php echo $nomArchivoRE ?></b> </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </form>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <?php
                                                if ($estadoSoliCompra == 'Solicitud Completada' || $estadoSoliCompra == 'Solicitud Anulada') {
                                                }else{
                                                    ?>
                                                    <!--<button class="btn bg-danger btn-block sda" data-toggle="modal" data-target="#ordenCompra" style="float:right;"><i class="fas fa-snowplow"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Materiales</b></button>-->
                                                    <br>
                                                    <button class="btn bg-success btn-block completada" id="<?php echo $idSoliCompra ?>" style="float:right;"><i class="fas fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp; <b>Completar Solicitud</b></button>
                                                    <br>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado_cc.php" method="POST">
                                            <div class="modal fade" id="modalCambiaEstado" name="modalCambiaEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                Cambiar Estado SDA</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>


                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Estado
                                                                    SDA: <code>*</code></label>
                                                                <select name="estadoSDA" id="estadoSDA" class="form-control" required>
                                                                    <option value="" selected disabled>-- Seleccione --</option>
                                                                    <option value="autorizada">Autorizada</option>
                                                                    <option value="denegada">Denegada</option>
                                                                </select>
                                                            </div>
                                                            <div style="text-align:left;display: none;" class="form-group" id="motivo">
                                                                <label for="exampleInputBorder">Motivo/
                                                                    Razón: <code>*</code></label>
                                                                <br>
                                                                <textarea name="motivo" id="motivoText" class="form-control" required></textarea>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                            <input type="hidden" value="<?php echo "cambiaEstadoSDA" ?>" name="opcion">

                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="modal fade" id="entregaSDA" name="entregaSDA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            Entregado a la SDA</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado_cc.php" method="POST">
                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha
                                                                    Entrega: <code>*</code></label>
                                                                <input type="datetime-local" class="form-control" name="fechaEntrega" required id="fechaEntrega">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <input type="hidden" value="<?php echo "entregadoSDA" ?>" name="opcion">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade " id="entregapanol" name="entregapanol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            Recibido por Bodega Pañol</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol_cc.php" method="POST">
                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha
                                                                    Recibido: <code>*</code></label>
                                                                <input type="datetime-local" class="form-control" name="fechaEntrega" required id="fechaEntrega">
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <input type="hidden" value="<?php echo "recibidoPanol" ?>" name="opcion">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>

                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalAsignarNumero" name="modalAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                Asignar Número Interno SDA</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado_cc.php" method="POST">

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">N°
                                                                    Interno: <code>*</code></label>
                                                                <input type="text" class="form-control " id="numInterno" name="numInterno" placeholder="Escriba Numero Interno" required>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                            <input type="hidden" value="<?php echo "asignarNumeroInternoSDA" ?>" name="opcion">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal fade" id="editarAsignarNumero" name="editarAsignarNumero" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                Editar Número Interno SDA</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado_cc.php" method="POST">
                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">N°
                                                                    Interno: <code>*</code></label>
                                                                <input type="text" class="form-control " id="numInterno" name="numInterno" value="<?php echo $numInternoSDA ?>" placeholder="Escriba Numero Interno" required>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" class="btn btn-primary">Guardar Modificación</button>
                                                            <input type="hidden" value="<?php echo "editarAsignarNumeroInternoSDA" ?>" name="opcion">

                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal fade" id="modalAbastecimiento" name="modalAbastecimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            <b>Entregado a Abastecimiento</b>
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstado_cc.php" method="POST">
                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Fecha recibido
                                                                    Abastecimiento: <code>*</code></label>
                                                                <input type="datetime-local" class="form-control" name="fechaEntrega" id="fechaEntrega">

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $idSoliCompra; ?>">
                                                            <button type="submit" class="btn btn-success">Guardar</button>
                                                            <input type="hidden" value="<?php echo "entregaAbastecimiento" ?>" name="opcion">

                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="modal fade" id="ordenCompra" name="ordenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="exampleModalLabel">Orden de compras</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol.php" onsubmit="return enviado()" id="form" method="POST" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <label for="ordenCompraInput">Orden de Compra: <code>*</code></label>
                                                                <input type="text" class="form-control" style="text-transform: uppercase;" name="ordenCompra" required id="ordenCompraInput">
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table id="tabla_subEquipo" class="table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">Articulo</th>
                                                                        <th scope="col">Cantidad Solicitada/Faltante</th>
                                                                        <th scope="col">Medida</th>
                                                                        <th scope="col">Seleccionar</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $sqlEquipo = "SELECT * FROM material_solicompra_sdo WHERE idSoliCompra = $idSoliCompra";
                                                                    $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                                                    while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                                        $datos = [
                                                                            'idArticuloSC' => $rowEquipo['idArticuloSC'],
                                                                            'nomArticuloSC' => $rowEquipo['nomArticuloSC'],
                                                                            'cantidadArticuloSC' => $rowEquipo['cantidadArticuloSC'],
                                                                            'medidaSC' => $rowEquipo['medidaSC'],
                                                                            'cantidadLlego' => $rowEquipo['cantidadLlego'],
                                                                            'cantidadFaltante' => $rowEquipo['cantidadFaltante'],
                                                                        ];

                                                                        $idArticuloSC = $datos['idArticuloSC'];
                                                                        $nomArticuloSC = $datos['nomArticuloSC'];
                                                                        $cantidadArticuloSC = $datos['cantidadArticuloSC'];
                                                                        $medidaSC = $datos['medidaSC'];
                                                                        $cantidadLlego = $datos['cantidadLlego'];
                                                                        $cantidadFaltante = $datos['cantidadFaltante'];
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo htmlspecialchars($nomArticuloSC); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if ($cantidadFaltante > 0) {
                                                                                    echo htmlspecialchars($cantidadArticuloSC . '/' . $cantidadFaltante);
                                                                                } else {
                                                                                    echo htmlspecialchars($cantidadArticuloSC . '/' . $cantidadFaltante);
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo mb_strtoupper(htmlspecialchars($medidaSC), 'UTF-8'); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if ($cantidadLlego == $cantidadArticuloSC) {
                                                                                    ?>
                                                                                    <span class="badge badge-success" style="font-size:15px">Todo Entregado <i class="fas fa-check"></i></span>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <div class="form-group">
                                                                                        <input type="hidden" name="opcion" value="ordenesCompra">
                                                                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($idSoliCompra); ?>">
                                                                                        <label for="cantidadLlego_<?php echo $idArticuloSC; ?>" class="label-form">Cantidad:</label>
                                                                                        <input type="checkbox" class="checkboxs" style="display: inline;" id="checks_<?php echo $idArticuloSC; ?>" name="checks[]" value="<?php echo htmlspecialchars($idArticuloSC); ?>">
                                                                                        <br>
                                                                                        <input type="hidden" class="cantidad" min="1" max="<?php echo htmlspecialchars($cantidadFaltante); ?>" id="cantidadLlego_<?php echo $idArticuloSC; ?>" style="display: inline; margin-right: 10px;" name="cantidadLlego[]">
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                        <div class="modal fade" id="ordenCompra" name="ordenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="exampleModalLabel">Materiales</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="ordenCompraInput">Orden de Compra: <code>*</code></label>
                                                            <input type="text" class="form-control" style="text-transform: uppercase;" name="ordenCompra" required id="ordenCompraInput">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tabEspecialidades" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <div class="row text-center" style="display: flex; justify-content:center;">
                                            <?php

                                            if ($idSoliCompra > 0) {

                                            ?>

                                            <div class="col-lg-3 col-12">
                                                <form target="_blank" style="margin: 0; text-align: center;" class="" action="../sdo-pdf/SDO-SoliCompra-SDO.php" method="post">

                                                    <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i>&nbsp;&nbsp;Visualizar Solicitud de Compra</button>

                                                </form>

                                                <br>
                                            </div>
                                            <?php
                                                }
                                            if ($idSoliCompra > 0) {
                                                ?>
                                                <div class="col-lg-3 col-12">
                                                    <?php
                                                    $contarCotizacionesSoliCompraSDO = contarCotizacionesSoliCompraSDO($idSoliCompra);

                                                    if ($contarCotizacionesSoliCompraSDO > 0) {
                                                        ?>

                                                        <form action="soliCompra_sdo_administracion.php" style="margin: 0; text-align: center;" class="" method="post">

                                                            <input type="hidden" name="descargaCotizacion" value="<?php echo $idSoliCompra; ?>">
                                                            <input type="hidden" name="folioSoliCompraDescarga" id="folioSoliCompraDescarga" value="<?php echo $folioSoliCompra; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos</button>

                                                        </form>

                                                        <br>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="submit" id="inputMozi" disabled class="btn btn-block btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Archivos Adjuntos</button>
                                                        <br>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }

                                            if ($idSoliCompra > 0) {
                                                ?>

                                                <div class="col-lg-3 col-12">
                                                    <button type="button" class="btn btn-block bg-orange" data-toggle="modal" data-target="#modal-xl" id="btnVerObservaciones">
                                                        <i class="fas fa-comments"></i>&nbsp;&nbsp;Ver Observaciones
                                                        <span class="badge bg-orange border" style="border: 1px; solid black;" id="numObservaciones">1</span>
                                                    </button>


                                                    <div class="modal fade" id="modal-xl">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h4 class="modal-title">
                                                                        Observaciones de la solicitud
                                                                    </h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="table table-responsive">
                                                                        <table class="table table-bordered text-center">

                                                                            <thead class="bg-secondary">
                                                                            <tr>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Fecha
                                                                                </th>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Creado Por
                                                                                </th>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Detalles
                                                                                </th>
                                                                            </tr>
                                                                            </thead>

                                                                            <tbody>

                                                                            <?php
                                                                            $queryObs = "SELECT * FROM solicitudes_compra_sdo_observaciones_cc WHERE idSoliCompra = " . $idSoliCompra;
                                                                            $resObs = mysqli_query($conexion, $queryObs);

                                                                            $numObs = mysqli_num_rows($resObs);
                                                                            while ($rowObs = $resObs->fetch_assoc()) {
                                                                                ?>

                                                                                <tr>

                                                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                        <?php


                                                                                        $inicioFecha = date('d/m/Y', (strtotime(str_replace('/', '-', $rowObs['fechaObservacion']))));
                                                                                        $inicioHora = date('H:i', strtotime($rowObs['fechaObservacion']));

                                                                                        echo $inicioFecha . '<br>' . $inicioHora;


                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="vertical-align:middle;" nowrap="nowrap">

                                                                                        <?php

                                                                                        echo $rowObs['creadorObservacion'];

                                                                                        ?>

                                                                                    </td>
                                                                                    <td style="vertical-align:middle;" nowrap="nowrap">
                                                                                        <?php

                                                                                        echo $rowObs['detalleObservacion'];


                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                            </tbody>

                                                                            <tfoot class="bg-secondary">
                                                                            <tr>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Fecha
                                                                                </th>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Creado Por
                                                                                </th>
                                                                                <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                                                    Detalles
                                                                                </th>
                                                                            </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                        <input type="hidden" id="numObsMostrar" name="numObsMostrar" value="<?php echo $numObs; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-right">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <!-- /.modal -->

                                                    <br>
                                                </div>
                                                <?php
                                            }


                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-tabs">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#tabEstado" role="tab" aria-controls="tabControl" aria-selected="true">Estado</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#tabMateriales" role="tab" aria-controls="tabMateriales" aria-selected="false">Materiales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#tabOC" role="tab" aria-controls="tabOC" aria-selected="false">Ordenes de Compra</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="custom-tabs-one-tabContent bg-primary">
                                <div class="tab-pane fade show active" id="tabEstado" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <button style="float: right" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#agregarNuevaObs"><i class="fas fa-plus"></i> Agregar Observación</button>
                                            <h5 class="font-weight-bold">¿Como va mi solicitud?</h5>

                                            <?php
                                            $estadoImagenes = [
                                                'Solicitada a Secretaria SDO' => 'img/linea-sdo.png',
                                                'Revisada por Secretaría SDO' => 'img/linea-sdo.png',
                                                'Entregado a la S.D.A' => 'img/linea-sda.png',
                                                'Autorizado por la S.D.A' => 'img/linea-sda.png',
                                                'Derivado a Abastecimiento' => 'img/linea-abastecimiento.png',
                                                'Recibido por pañol' => 'img/linea-panol.png',
                                                'Solicitud Completada' => 'img/linea-completada.png',
                                                'Solicitud Anulada' => 'img/linea-sdo-anulada.png',
                                                'Rechazado por la S.D.A' => 'img/linea-sda-rechazada.png',
                                            ];

                                            if (array_key_exists($estadoSoliCompra, $estadoImagenes)) {
                                                $imagen = $estadoImagenes[$estadoSoliCompra];
                                                ?>
                                                <div class="row justify-content-center">
                                                    <img src="<?php echo $imagen; ?>" width="700px" height="150px" alt="Estado de la Solicitud">
                                                </div>
                                                <?php
                                            }
                                            ?>


                                            <table style="font-size:90%; width:100%;" id="example2" class="table table-bordered table-hover text-center">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Fecha Actualización
                                                    </th>
                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Tipo de Actualización
                                                    </th>

                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Observación
                                                    </th>


                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php
                                                $query = "SELECT * FROM seguimiento_sc_sdo_cc WHERE idSoliCompra = '$idSoliCompra' ORDER BY fechaActualizacion DESC";
                                                $res = mysqli_query($conexion, $query);


                                                while ($row = $res->fetch_assoc()) {
                                                    ?>

                                                    <tr>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php


                                                            $inicioFecha = date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaActualizacion']))));
                                                            $inicioHora = date('H:i', strtotime($row['fechaActualizacion']));

                                                            echo $inicioFecha . '<br>' . $inicioHora;
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap">
                                                            <?php
                                                            $cantidadArticuloSC = mb_strtoupper($row['tipoActualizacion']);
                                                            echo $cantidadArticuloSC;

                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap">
                                                            <?php
                                                            $nomArticuloSC = mb_strtoupper($row['observacion']);
                                                            echo nl2br($nomArticuloSC);

                                                            ?>
                                                        </td>
                                                    </tr>

                                                    <?php

                                                }

                                                $sqlObs = "SELECT * FROM `observaciones_sdo_cc` WHERE idSoliCompra = $idSoliCompra";
                                                $resObs = mysqli_query($conexion,$sqlObs);
                                                while($rowObs = $resObs->fetch_assoc()){
                                                    ?>
                                                    <tr>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php


                                                            $inicioFechaObs = date('d/m/Y', (strtotime(str_replace('/', '-', $rowObs['fecha']))));
                                                            $inicioHoraObs = date('H:i', strtotime($rowObs['fecha']));

                                                            echo $inicioFechaObs . '<br>' . $inicioHoraObs;
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            Observación Normal
                                                        </td>
                                                        <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                            <?php
                                                                echo $rowObs['obs'];
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>

                                                <tfoot class="bg-secondary">
                                                <tr>
                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Fecha Actualización
                                                    </th>
                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Tipo de Actualización
                                                    </th>
                                                    <th nowrap="nowrap" align="center" style="vertical-align:middle;">
                                                        Observación
                                                    </th>


                                                </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabMateriales" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol_cc.php" method="POST">
                                                <table id="materiales" class="table table-bordered text-center" style="vertical-align: middle; justify-content: center;">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th scope="col">Articulo</th>
                                                        <th scope="col">Cantidad Solicitada/Faltante</th>
                                                        <th scope="col">Medida</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sqlEquipo = "SELECT * FROM material_solicompra_sdo_cc WHERE idSoliCompra = $idSoliCompra ";
                                                    $resEquipo = mysqli_query($conexion,$sqlEquipo);
                                                    while($rowEquipo = $resEquipo->fetch_assoc()){
                                                        $idArticuloSC  = $rowEquipo['idArticuloSC'];
                                                        $nomArticuloSC = $rowEquipo['nomArticuloSC'];
                                                        $cantidadArticuloSC = $rowEquipo['cantidadArticuloSC'];
                                                        $medidaSC = $rowEquipo['medidaSC'];
                                                        $cantidadLlego = $rowEquipo['cantidadLlego'];
                                                        $cantidadFaltante = $rowEquipo['cantidadFaltante'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $nomArticuloSC ?> </td>
                                                            <td>
                                                                <?php

                                                                if ($cantidadFaltante > 0) {
                                                                    $cantidadFalta = $cantidadFaltante;
                                                                    echo  $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                } else {
                                                                    $cantidadFalta = $cantidadArticuloSC;
                                                                    echo $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                }

                                                                ?>
                                                            </td>
                                                            <td><?php echo mb_convert_case($medidaSC, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabOC" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="../sdo-funciones/SoliCompra_sdo_cambiaEstadoPanol_cc.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" id="id" name="id" value="<?php echo $idSoliCompra?>">
                                                <h5>Orden compra, fecha y valor:</h5>
                                                <div class="row" style="float: right;">
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#subirOC"><i class="fas fa-upload"></i> Subir OC</button>&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#agregarObsOrdenesCompra" style="float: right"><i class="fas fa-plus"></i> Agregar/Visualizar Observación/es a la Orden de Compra</button>
                                                </div>
                                                <br>
                                                <br>
                                                <hr>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Orden de Compra:</label>
                                                    <input type="text" required class="form-control form-control-sm" id="valorIdOC" name="ordenCompra" aria-describedby="emailHelp">
                                                    <small id="emailHelp" class="form-text text-muted">Escriba la orden de compra respectiva.</small>
                                                </div>
                                                <br>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha Envio:</label>
                                                    <input type="date" required class="form-control form-control-sm" id="fechaEnvio" name="fechaEnvio" aria-describedby="emailHelp">
                                                    <small id="emailHelp" class="form-text text-muted">Fecha envio de la orden de compra al proveedor.</small>
                                                </div>
                                                <br>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Valor:</label>
                                                    <input type="number" required class="form-control form-control-sm" min="0" id="valorIdOC" name="valorOrdenCompra" aria-describedby="emailHelp">
                                                    <small id="emailHelp" class="form-text text-muted">Escriba el valor de la orden de compra respectiva.</small>
                                                </div>
                                                <br>
                                                <div class="form-group">
                                                    <label>Subir Orden de Compra</label>
                                                    <input type="file" class="form-control form-control-sm" id="archivoAdjunto" name="archivoAdjunto[]" multiple>
                                                    <small id="emailHelp" class="form-text text-muted">Suba el archivo de la orden de compra</small>
                                                </div>
                                                <br>
                                                <table id="materiales" class="table table-bordered text-center" style="vertical-align: middle; justify-content: center;">
                                                    <thead class="bg-secondary">
                                                    <tr>
                                                        <th scope="col">Articulo</th>
                                                        <th scope="col">Cantidad Solicitada/Faltante</th>
                                                        <th scope="col">Medida</th>
                                                        <th scope="col">Seleccionar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $sqlEquipo = "SELECT * FROM material_solicompra_sdo_cc WHERE idSoliCompra = $idSoliCompra ";
                                                    $resEquipo = mysqli_query($conexion,$sqlEquipo);
                                                    while($rowEquipo = $resEquipo->fetch_assoc()){
                                                        $idArticuloSC  = $rowEquipo['idArticuloSC'];
                                                        $nomArticuloSC = $rowEquipo['nomArticuloSC'];
                                                        $cantidadArticuloSC = $rowEquipo['cantidadArticuloSC'];
                                                        $medidaSC = $rowEquipo['medidaSC'];
                                                        $cantidadLlego = $rowEquipo['cantidadLlego'];
                                                        $cantidadFaltante = $rowEquipo['cantidadFaltante'];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $nomArticuloSC ?> </td>
                                                            <td>
                                                                <?php

                                                                if ($cantidadFaltante > 0) {
                                                                    $cantidadFalta = $cantidadFaltante;
                                                                    echo  $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                } else {
                                                                    $cantidadFalta = $cantidadArticuloSC;
                                                                    echo $cantidadArticuloSC . '/' . $cantidadFaltante;
                                                                }

                                                                ?>
                                                            </td>
                                                            <td><?php echo mb_convert_case($medidaSC, MB_CASE_UPPER, 'UTF-8'); ?></td>
                                                            <td>
                                                                <?php
                                                                if ($cantidadLlego == $cantidadArticuloSC) {
                                                                    ?>
                                                                    <span class="badge badge-success" style="font-size:15px">Todo Entregado <i class="fas fa-check"></i></span>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div class="form-group" id="padreForm">
                                                                        <input type="hidden" name="opcion" value="ordenesCompra">
                                                                        <input type="hidden" name="id" value="<?php echo $idSoliCompra ?>">
                                                                        <label for="cantidadLlego" style="display: block" class="label-form">Cantidad:</label>
                                                                        <input type="checkbox" class="checkboxs" style="display: inline;" id="checks_'. $idArticuloSC. '" name="checks[]" value="<?php echo $idArticuloSC ?>">
                                                                        <br>
                                                                        <input type="hidden" class="cantidad" min='1' max="<?php echo $cantidadFalta ?>" id="cantidadLlego_'.$idArticuloSC.'" style="display: inline; margin-right: 10px;" name="cantidadLlego[]">
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                    <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th scope="col">Articulo</th>
                                                        <th scope="col">Cantidad Solicitada/Faltante</th>
                                                        <th scope="col">Medida</th>
                                                        <th scope="col">Seleccionar</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                                <br>
                                                <br>
                                                <button type="submit" class="btn btn-block btn-success"> Guardar</button>
                                            </form>
                                            <br>
                                            <br>
                                            <hr>
                                            <table class="table table-bordered" style="text-align: center">
                                                <thead class="bg-secondary">
                                                <tr>
                                                    <th scope="col">Orden de Compra</th>
                                                    <th scope="col">Fecha Env. Proveedor</th>
                                                    <th scope="col">Costo Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php

                                                $sqlOrdenCompra = "SELECT * FROM detalle_ordencompra_cc WHERE idSoliCompra = $idSoliCompra";
                                                $resOrdenCompra = mysqli_query($conexion, $sqlOrdenCompra);
                                                $sumaTotal = 0;
                                                while ($rowOrdenCompra = $resOrdenCompra->fetch_assoc()) {
                                                    $datos = [];
                                                    $datos['codOrdenCompra'] = $rowOrdenCompra['codOrdenCompra'];
                                                    $datos['fechaEnvioProv'] = $rowOrdenCompra['fechaEnvioProv'];
                                                    $datos['valorTotal'] = $rowOrdenCompra['valorTotal'];

                                                    $codOrdenCompra = $datos['codOrdenCompra'];
                                                    $fechaEnvioProv = $datos['fechaEnvioProv'];
                                                    $valorTotal = $datos['valorTotal'];
                                                    $sumaTotal = $sumaTotal + $valorTotal;
                                                    $valorTotal = moneda_chilena($valorTotal);
                                                    $fechaEnvioProv = date('d-m-Y', strtotime($rowOrdenCompra['fechaEnvioProv']));

                                                    ?>

                                                    <tr>
                                                        <td style="font-weight:bold;color: green;font-size: 15px"><?php echo strtoupper($codOrdenCompra) ?></td>
                                                        <td style="font-weight:bold;color: green;font-size: 15px"><?php echo strtoupper($fechaEnvioProv) ?></td>
                                                        <td style="font-weight:bold;color: green;font-size: 15px"><?php echo $valorTotal ?></td>
                                                    </tr>

                                                    <?php
                                                }

                                                ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <td style="font-weight:bold;color: white;font-size: 15px">Total</td>
                                                        <td></td>
                                                        <td style="font-weight:bold;color: white;font-size: 15px"><?php echo moneda_chilena($sumaTotal)?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="subirOC" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="exampleModalLabel">Subir OC</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="../sdo-funciones/subir-archivo-adjunto.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="idSoliCompra" name="idSoliCompra" value="<?php echo $idSoliCompra?>">
                            <div class="modal-body">
                                <input type="file" class="form-control" id="archivoAdjunto" name="archivoAdjunto[]" multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Subir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="agregarNuevaObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Nueva Observación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="../sdo-funciones/guardar-obs-oc-cc.php" method="POST">
                            <input type="hidden" id="idSoliCompra" name="idSoliCompra" value="<?php echo $idSoliCompra?>">
                            <div class="modal-body">
                                <label>Nombre Creador:</label>
                                <input type="text" name="nomUsuario" id="nomUsuario" class="form-control" value="<?php echo $nomUsuario?>">
                                <br>
                                <label>Observación:</label>
                                <textarea style="resize: none" class="form-control" rows="15" id="obs" name="obs"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-warning">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="agregarObsOrdenesCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Observación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="../sdo-funciones/guardar-obs-oc-f-v-cc.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" id="idSoliCompra" name="idSoliCompra" value="<?php echo $idSoliCompra?>">
                                <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario?>">
                                <div class="modal-body">
                                    <label>Nombre Creador:</label>
                                    <input type="text" name="nomUsuarioObs" id="nomUsuarioObs" class="form-control" value="<?php echo $nomUsuario?>">
                                    <br>
                                    <label>Observación:</label>
                                    <textarea style="resize: none" class="form-control" rows="15" id="obsCompra" name="obsCompra"></textarea>
                                    <hr>
                                    <br>
                                    <table id="hola" class="table-bordered table" style="vertical-align: middle; justify-content: center;">
                                        <thead style="text-align: center" class="bg-secondary">
                                        <tr>
                                            <th>Creador</th>
                                            <th>Fecha</th>
                                            <th>Obs</th>
                                        </tr>
                                        </thead>
                                        <tbody style="text-align: center">
                                            <?php
                                                $sqlTabla = "SELECT * FROM `observaciones_obs_ca_cc` WHERE idSoliCompra = $idSoliCompra";
                                                $resTabla = mysqli_query($conexion,$sqlTabla);
                                                while($rowTabla = $resTabla->fetch_assoc()){
                                                    $nomCreador = $rowTabla['nomUsuario'];
                                                    $fechaTabla = $rowTabla['fecha'];
                                                    $obsTabla = $rowTabla['obs'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $nomCreador?></td>
                                                        <td>
                                                            <?php
                                                            $inicioFechaObsOC = date('d/m/Y', (strtotime(str_replace('/', '-', $rowTabla['fecha']))));
                                                            $inicioHoraObsOC = date('H:i', strtotime($rowTabla['fecha']));
                                                            echo $inicioFechaObsOC.' '.$inicioHoraObsOC;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $obsTabla?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot style="text-align: center" class="bg-secondary">
                                        <tr>
                                            <th>Creador</th>
                                            <th>Fecha</th>
                                            <th>Obs</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
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
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->

<script type="text/javascript">
    $(document).ready(function() {

        $('[name="checks[]"]').click(function() {

            var arr = $('[name="checks[]"]:checked').map(function() {
                return this.value;
            }).get();

            var str = arr.join(',');

            console.log(str);

            $('#arrayProductos').val(str);

        });

        $('[name="cantidadLlego[]"]').change(function() {

            var arr = $('[name="cantidadLlego[]"]').map(function() {
                return this.value;
            }).get();

            var str = arr.join(',');

            console.log(str);

            $('#arrayCantidad').val(str);

        });

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var checkboxs = document.querySelectorAll('.checkboxs');
        var guardar = document.getElementById('guardar');

        checkboxs.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                var input = this.parentNode.querySelector('.cantidad');

                if (this.checked) {
                    input.type = 'number';
                    input.required = true;
                    guardar.disabled = false;

                } else {
                    input.type = 'hidden';
                    input.required = false;
                }


            })
        })
    });
</script>
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
            "pageLength": 10,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({


                        header: function (row) {
                            var data = row.data();
                            return 'Informaci&oacute;n de Solicitud Telefonica: ';
                        },
                        autoWidth: true,
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table col-12 text-justify align-items-center'
                    })
                }
            },
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
        });
    });

    // Supongamos que tienes una variable 'numObservaciones' que contiene el número total de observaciones
    // Puedes cambiar este valor según lo que necesites en tu aplicación

    // Ejemplo de valor aleatorio entre 0 y 10 para simular el número de observaciones
    let numObservaciones = document.getElementById('numObsMostrar').value;

    // Actualiza el texto del span con el número de observaciones
    document.getElementById("numObservaciones").textContent = numObservaciones;

</script>
<script type="text/javascript">
    $(document).ready(function () {
        //group add limit
        var maxGroup = "10";

        //add more fields group
        $(".addMore").click(function () {
            if ($('body').find('.fieldGroup').length < maxGroup) {
                var fieldHTML = '<div class="form-row fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);

            } else {
                alert('Limite de Materiales Completados. Solo se permiten ' + maxGroup + ' Materiales.');
            }
        });

        //remove fields group
        $("body").on("click", ".remove", function () {
            $(this).parents(".fieldGroup").remove();
        });
    });
</script>
<script>
    estadoSDA = document.getElementById('estadoSDA');
    motivo = document.getElementById('motivo');
    motivoText = document.getElementById('motivoText');

    estadoSDA.addEventListener('change', function (event) {

        valorEstado = estadoSDA.value;

        if (valorEstado == 'denegada') {
            motivo.style.display = 'block';
        } else if (valorEstado == '' || valorEstado == 'autorizada') {
            motivo.style.display = 'none';
            motivoText.removeAttribute("required");
        }


    })
</script>
<script src="js/dark-mode.js"></script>
<script>
    $(document).ready(
        $('.completada').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'info',
                title: '¿Seguro que quieres marcar como completada esta solicitud?',
                showConfirmButton: true,
                confirmButtonText: 'SI',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/soliCompra_sdo_completarPanol_cc.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Solicitud completada con exito" ',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            // setTimeout(function() {
                            //     location.reload();
                            // }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<!-- <script>
    $(document).ready(

        $('.sda').click(function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'info',
                title: '¿Seguro que quieres cambiar el estado de la solicitud a "Entregado a la SDA"?',
                showConfirmButton: true,
                confirmButtonText: 'SI',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
                buttonsStyling: true
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        url: '../sdo-funciones/SoliCompra_sdo_cambiaEstado.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('cambiado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Estado de la solicitud modificado a "Entregado a la SDA" ',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })


    )
</script> -->
<script>
    estadoSDA = document.getElementById('estadoSDA');
    motivo = document.getElementById('motivo');
    motivoText = document.getElementById('motivoText');

    estadoSDA.addEventListener('change', function(event) {

        valorEstado = estadoSDA.value;

        if (valorEstado == 'denegada') {
            motivo.style.display = 'block';
        } else if (valorEstado == '' || valorEstado == 'autorizada') {
            motivo.style.display = 'none';
            motivoText.removeAttribute("required");
        }


    })
</script>
</body>
</html>