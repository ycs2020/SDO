<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i');


if(isset($_POST['id']) || isset($_SESSION['id'])){

    if (isset($_POST['id'])) {
        $idEdan = $_POST['id'];
        if($idEdan > 0){
            $_SESSION['id'] = $idEdan;
        }else{
            header('Location: listar_informe_edan.php?errorCargaEdan=true');
        }
    }else if(isset($_SESSION['id'])){
        $idEdan = $_SESSION['id'];
        if($idEdan > 0){
            $_SESSION['id'] = $idEdan;
        }else{
            header('Location: listar_informe_edan.php?errorCargaEdan=true');
        }
    }

    $sql = "SELECT * FROM informes_edan WHERE id = $idEdan";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $folio = $row['folio'];
    }

}else{
    header('Location: listar_informe_edan.php?errorCargaEdan=true');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Informe EDAN</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">

<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Detalle Informe: <?php echo $folio?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
        if(isset($_GET['exito'])){
            ?>
            <script>
                Swal.fire({
                    title: "Exito",
                    text: "Se ha modificado con exito!",
                    icon: "success"
                });
            </script>
            <?php
        }

        if(isset($_GET['error1'])){
            ?>
            <script>
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error. Por favor reintente!",
                    icon: "error"
                });
            </script>
            <?php
        }
        ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="listar_informe_edan.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-6_3" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">6.3 Estructural</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-6_4" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">6.4 No Estructural</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-6_6" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">6.6 Vias de Acceso</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_7-tab" data-toggle="pill" href="#custom-tabs-one-6_7" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.7 Gases Clinicos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_8-tab" data-toggle="pill" href="#custom-tabs-one-6_8" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.8 Energía Eléctrica de la red de distribución</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_9-tab" data-toggle="pill" href="#custom-tabs-one-6_9" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.9 Agua Potable de la red de distribución</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_10-tab" data-toggle="pill" href="#custom-tabs-one-6_10" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.10 Alcantarillado</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_15-tab" data-toggle="pill" href="#custom-tabs-one-6_15" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.15 Vehículos (Camionetas o Ambulancias)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_24-tab" data-toggle="pill" href="#custom-tabs-one-6_24" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.24 Otros</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-6_3" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlInfra1 = "SELECT * FROM observacion_edan_infra WHERE idEdan = $idEdan";
                                            $resInfra1 = mysqli_query($conexion, $sqlInfra1);
                                            while($rowInfra1 = $resInfra1->fetch_assoc()){
                                                $idInfra1 = $rowInfra1['id'];
                                                $detalleObs6_3 = $rowInfra1['detalleObservacion6_3'];
                                                $nomCreadorInfra1 = $rowInfra1['nomCreador'];
                                                $fechaInfra1 = $rowInfra1['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorInfra1?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaInfra1?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalInfra1<?php echo $idInfra1?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalInfra1<?php echo $idInfra1?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_3?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiInfra1<?php echo $idInfra1?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiInfra1<?php echo $idInfra1?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idInfra1?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_3" rows="15" style="resize: none" ><?php echo $detalleObs6_3?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_4" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlInfra2 = "SELECT * FROM observacion_edan_infra WHERE idEdan = $idEdan";
                                            $resInfra2 = mysqli_query($conexion, $sqlInfra2);
                                            while($rowInfra2 = $resInfra2->fetch_assoc()){
                                                $idInfra2 = $rowInfra2['id'];
                                                $detalleObs6_4 = $rowInfra2['detalleObservacion6_4'];
                                                $nomCreadorInfra2 = $rowInfra2['nomCreador'];
                                                $fechaInfra2 = $rowInfra2['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorInfra2?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaInfra2?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalInfra2<?php echo $idInfra2?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalInfra2<?php echo $idInfra2?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_4?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiInfra2<?php echo $idInfra2?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiInfra2<?php echo $idInfra2?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_4.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idInfra2?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_4" rows="15" style="resize: none" ><?php echo $detalleObs6_4?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_6" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlInfra3 = "SELECT * FROM observacion_edan_infra WHERE idEdan = $idEdan";
                                            $resInfra3 = mysqli_query($conexion, $sqlInfra3);
                                            while($rowInfra3 = $resInfra3->fetch_assoc()){
                                                $idInfra3 = $rowInfra3['id'];
                                                $detalleObs6_6 = $rowInfra3['detalleObservacion6_6'];
                                                $nomCreadorInfra3 = $rowInfra3['nomCreador'];
                                                $fechaInfra3 = $rowInfra3['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorInfra3?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaInfra3?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalInfra3<?php echo $idInfra3?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalInfra3<?php echo $idInfra3?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_6?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiInfra3<?php echo $idInfra3?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiInfra3<?php echo $idInfra3?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_6.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idInfra3?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_6" rows="15" style="resize: none" ><?php echo $detalleObs6_6?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_7" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlIndustriales1 = "SELECT * FROM observacion_edan_industriales WHERE idEdan = $idEdan";
                                            $resIndustriales1 = mysqli_query($conexion, $sqlIndustriales1);
                                            while($rowIndustriales1 = $resIndustriales1->fetch_assoc()){
                                                $idIndustriales1 = $rowIndustriales1['id'];
                                                $detalleObs6_7 = $rowIndustriales1['detalleObservacion6_7'];
                                                $nomCreadorIndustriales1 = $rowIndustriales1['nomCreador'];
                                                $fechaIndustriales1 = $rowIndustriales1['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorIndustriales1?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaIndustriales1?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalIndustriales1<?php echo $idIndustriales1?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalIndustriales1<?php echo $idIndustriales1?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_7?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiIndustriales1<?php echo $idIndustriales1?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiIndustriales1<?php echo $idIndustriales1?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_7.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idIndustriales1?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_7" rows="15" style="resize: none" ><?php echo $detalleObs6_7?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_8" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlElectricidad = "SELECT * FROM observacion_edan WHERE idEdan = $idEdan";
                                            $resElectricidad = mysqli_query($conexion, $sqlElectricidad);
                                            while($rowElectricidad = $resElectricidad->fetch_assoc()){
                                                $idElectricidad = $rowElectricidad['id'];
                                                $detalleObs6_8 = $rowElectricidad['detalleObservacion6_8'];
                                                $nomCreadorElectricidad = $rowElectricidad['nomCreador'];
                                                $fechaElectricidad = $rowElectricidad['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorElectricidad?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaElectricidad?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalElectricidad<?php echo $idElectricidad?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalElectricidad<?php echo $idElectricidad?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_8?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiElectricidad<?php echo $idElectricidad?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiElectricidad<?php echo $idElectricidad?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_8.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idElectricidad?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_8" rows="15" style="resize: none" ><?php echo $detalleObs6_8?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_9" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <label>Infraestructura</label>
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlInfra4 = "SELECT * FROM observacion_edan_infra WHERE idEdan = $idEdan";
                                            $resInfra4 = mysqli_query($conexion, $sqlInfra4);
                                            while($rowInfra4 = $resInfra4->fetch_assoc()){
                                                $idInfra4 = $rowInfra4['id'];
                                                $detalleObs6_9_Infra = $rowInfra4['detalleObservacion6_9'];
                                                $nomCreadorInfra4 = $rowInfra4['nomCreador'];
                                                $fechaInfra4 = $rowInfra4['fecha'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $nomCreadorInfra4?></td>
                                                    <td>
                                                        <?php echo $fechaInfra4?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalInfra4<?php echo $idInfra4?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalInfra4<?php echo $idInfra4?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_9_Infra?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiInfra4<?php echo $idInfra4?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiInfra4<?php echo $idInfra4?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_9_infra.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idInfra4?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_9_infra" rows="15" style="resize: none" ><?php echo $detalleObs6_9_Infra?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <label>Industriales</label>
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlIndustriales2 = "SELECT * FROM observacion_edan_industriales WHERE idEdan = $idEdan";
                                            $resIndustriales2 = mysqli_query($conexion, $sqlIndustriales2);
                                            while($rowIndustriales2 = $resIndustriales2->fetch_assoc()){
                                                $idIndustriales2 = $rowIndustriales2['id'];
                                                $detalleObs6_9_industriales = $rowIndustriales2['detalleObservacion6_9'];
                                                $nomCreadorIndustriales2 = $rowIndustriales2['nomCreador'];
                                                $fechaIndustriales2 = $rowIndustriales2['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorIndustriales2?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaIndustriales2?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalIndustriales2<?php echo $idIndustriales2?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalIndustriales2<?php echo $idIndustriales2?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_7?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiIndustriales2<?php echo $idIndustriales2?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiIndustriales2<?php echo $idIndustriales2?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_9_industriales.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idIndustriales2?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_9_industriales" rows="15" style="resize: none" ><?php echo $detalleObs6_9_industriales?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_10" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlInfra5 = "SELECT * FROM observacion_edan_infra WHERE idEdan = $idEdan";
                                            $resInfra5 = mysqli_query($conexion, $sqlInfra5);
                                            while($rowInfra5 = $resInfra5->fetch_assoc()){
                                                $idInfra5 = $rowInfra5['id'];
                                                $detalleObs6_10 = $rowInfra5['detalleObservacion6_10'];
                                                $nomCreadorInfra5 = $rowInfra5['nomCreador'];
                                                $fechaInfra5 = $rowInfra5['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorInfra5?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaInfra5?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalInfra5<?php echo $idInfra5?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModalInfra5<?php echo $idInfra5?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_10?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiInfra5<?php echo $idInfra5?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiInfra5<?php echo $idInfra5?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_10.php" method="POST">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idInfra5?>">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_10" rows="15" style="resize: none" ><?php echo $detalleObs6_10?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_15" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlMovi = "SELECT * FROM observacion_edan_movi WHERE idEdan = $idEdan";
                                            $resMovi = mysqli_query($conexion, $sqlMovi);
                                            while($rowMovi = $resMovi->fetch_assoc()){
                                                $idMovi = $rowMovi['id'];
                                                $detalleObs6_15 = $rowMovi['detalleObservacion6_15'];
                                                $nomCreadorMovi = $rowMovi['nomCreador'];
                                                $fechaMovi = $rowMovi['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorMovi?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaMovi?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModaMovi<?php echo $idMovi?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModaMovi<?php echo $idMovi?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_15?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiMovi<?php echo $idMovi?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiMovi<?php echo $idMovi?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_15.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idMovi?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_15" rows="15" style="resize: none" ><?php echo $detalleObs6_15?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_24" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <table class="table text-center">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlOtros = "SELECT * FROM observacion_edan_otros WHERE idEdan = $idEdan";
                                            $resOtros = mysqli_query($conexion,$sqlOtros);
                                            while($rowOtros = $resOtros->fetch_assoc()){
                                                $idOtros = $rowOtros['id'];
                                                $detalleObs6_24 = $rowOtros['detalleObservacion6_24'];
                                                $nomCreadorOtros = $rowOtros['nomCreador'];
                                                $fechaOtros = $rowOtros['fecha'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $nomCreadorOtros?>
                                                    </td>
                                                    <td>
                                                        <?php echo $fechaOtros?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModaOtros<?php echo $idOtros?>" class="btn btn-sm bg-primary"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="exampleModaOtros<?php echo $idOtros?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de la Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea class="form-control form-control-border" rows="15" style="resize: none" readonly><?php echo $detalleObs6_24?></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#exampleModalModiOtros<?php echo $idOtros?>" class="btn btn-sm bg-warning"><i class="fas fa-pen"></i></button>
                                                        <div class="modal fade" id="exampleModalModiOtros<?php echo $idOtros?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Modificar Obs</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="../sdo-funciones/modificar_detalle_obs_6_24.php" method="POST">
                                                                        <input type="hidden" name="dato" value="admin">
                                                                        <input type="hidden" id="id" name="id" value="<?php echo $idOtros?>">
                                                                        <div class="modal-body">
                                                                            <textarea class="form-control " name="observacion6_24" rows="15" style="resize: none" ><?php echo $detalleObs6_24?></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                            <button type="submit" class="btn btn-success">Modificar</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th>Funcionario Creador</th>
                                                <th>Fecha</th>
                                                <th>Visualizar Detalles</th>
                                                <th>Modificar Detalle</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
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
<script src="js/dark-mode.js"></script>
</body>
</html>
