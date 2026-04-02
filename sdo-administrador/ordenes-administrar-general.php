<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


$descargaConfirma = "";
if (isset($_POST['idOrden'])) {

    $idOrden = $_POST['idOrden'];
    $folio = $_POST['folio'];

    if ($idOrden > 0) {

        ini_set("memory_limit", "1256M");
        $zip = new ZipArchive();
        $dir = '../sdo-archivos-orden/' . $folio;
        $dir2 = $dir . "/";
        $rutaFinal = "../sdo-administrador/";
        $archivoZip = "Archivos-" . $folio . ".zip";

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
} {
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Recepciones de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha {
            display: none;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
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
                            <h4> <b>Listado de Hojas Técnicas</b> </h4>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body" style="text-align:center;">

                                    <form method="post" action="ordenes-formulario-general.php" style="display: inline-block;">

                                        <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Agregar Hoja Técnica</b> </button>

                                    </form>

                                    <button class="btn bg-info show-verdash" id="verDashAC" name="verDashAC"><i class="fas fa-eye"></i>&nbsp;&nbsp;Ver Dashboard</button>
                                    <button class="btn bg-red show-ocultardash" id="ocultarDashAC" style="display:none;" name="ocultarDashAC"><i class="fa fa-eye-slash"></i>&nbsp;&nbsp;Ocultar Dashboard</button>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row" id="rowMenu3" name="rowMenu3" style="display: none;">

                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-center"> <b>Resumen Hojas Técnicas - 2023</b> </h5>
                                    <hr>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">

                                            <div class="row">
                                                <?php

                                                date_default_timezone_set('America/Santiago');
                                                $nowT = date('Y-m', time());
                                                $anioT = date('Y', time());

                                                $titulo1 = "Cantidad de Hojas Técnicas Realizadas en Año " . $anioT . " por Departamento";

                                                $titulo2 = mb_convert_case($titulo1, MB_CASE_UPPER, 'utf-8');
                                                ?>
                                                <p class="badge-info w-100 text-center" style="font-size: 12px;font-weight:bold; overflow-wrap: break-word;"><?php echo $titulo2; ?></p>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead class="bg-success">
                                                            <tr>
                                                                <th scope="col">Departamento</th>
                                                                <th scope="col" class="text-center">Enero</th>
                                                                <th scope="col" class="text-center">Febrero</th>
                                                                <th scope="col" class="text-center">Marzo</th>
                                                                <th scope="col" class="text-center">Abril</th>
                                                                <th scope="col" class="text-center">Mayo</th>
                                                                <th scope="col" class="text-center">Junio</th>
                                                                <th scope="col" class="text-center">Julio</th>
                                                                <th scope="col" class="text-center">Agosto</th>
                                                                <th scope="col" class="text-center">Septiembre</th>
                                                                <th scope="col" class="text-center">Octubre</th>
                                                                <th scope="col" class="text-center">Noviembre</th>
                                                                <th scope="col" class="text-center">Diciembre</th>
                                                                <th scope="col" class="text-center">Total A&ntilde;o <?php echo $anioT; ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            $sqlDeptoT = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                            $resDeptoT = mysqli_query($conexion, $sqlDeptoT);

                                                            $cantPorDepto = 0;

                                                            $cantPorMesTotal = 0;

                                                            $arrayCantTotal = array();

                                                            $totalMes1 = 0;
                                                            $totalMes2 = 0;
                                                            $totalMes3 = 0;
                                                            $totalMes4 = 0;
                                                            $totalMes5 = 0;
                                                            $totalMes6 = 0;
                                                            $totalMes7 = 0;
                                                            $totalMes8 = 0;
                                                            $totalMes9 = 0;
                                                            $totalMes10 = 0;
                                                            $totalMes11 = 0;
                                                            $totalMes12 = 0;


                                                            while ($rowDeptoT = $resDeptoT->fetch_assoc()) {

                                                                $cantTotalFinal = 0;

                                                                $idDepartamentoT = $rowDeptoT['idDepartamento'];
                                                                $nomDepartamentoT = $rowDeptoT['nomDepartamento'];

                                                                $contarHojasTecnicasAnio = contarHojasTecnicasAnio($idDepartamentoT, $anioT);

                                                                if ($nomDepartamentoT == 'Informática' || $nomDepartamentoT == 'Ingeniería Operacional') {
                                                                } else {
                                                                    $cantTotalDepto = 0;
                                                            ?>
                                                                    <tr>
                                                                        <th scope="row">
                                                                            <?php echo $nomDepartamentoT; ?>
                                                                        </th>
                                                                        <?php

                                                                        for ($i = 1; $i < 13; $i++) {
                                                                        ?>
                                                                            <td class="text-center">
                                                                                <?php
                                                                                $mesHT = '';
                                                                                if ($i < 10) {
                                                                                    $mesHT = '0' . $i;
                                                                                }

                                                                                $fechaT = $anioT . '-' . $mesHT;

                                                                                $contarHojasTecnicasMesAnio = contarHojasTecnicasMesAnio($idDepartamentoT, $fechaT);

                                                                                $cantTotalDepto = $cantTotalDepto + $contarHojasTecnicasMesAnio;

                                                                                if ($contarHojasTecnicasMesAnio < 1) {
                                                                                    echo '-';
                                                                                } else {
                                                                                    echo $contarHojasTecnicasMesAnio;
                                                                                }

                                                                                ?>
                                                                            </td>
                                                                        <?php

                                                                            if ($i == 1) {
                                                                                $totalMes1 = $totalMes1 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 2) {
                                                                                $totalMes2 = $totalMes2 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 3) {
                                                                                $totalMes3 = $totalMes3 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 4) {
                                                                                $totalMes4 = $totalMes4 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 5) {
                                                                                $totalMes5 = $totalMes5 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 6) {
                                                                                $totalMes6 = $totalMes6 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 7) {
                                                                                $totalMes7 = $totalMes7 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 8) {
                                                                                $totalMes8 = $totalMes8 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 9) {
                                                                                $totalMes9 = $totalMes9 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 10) {
                                                                                $totalMes10 = $totalMes10 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 11) {
                                                                                $totalMes11 = $totalMes11 + $contarHojasTecnicasMesAnio;
                                                                            } else if ($i == 12) {
                                                                                $totalMes12 = $totalMes12 + $contarHojasTecnicasMesAnio;
                                                                            }

                                                                            $cantTotalFinal = $cantTotalFinal + $cantTotalDepto;
                                                                        }
                                                                        ?>

                                                                        <td class="text-center text-bold">
                                                                            <?php echo $cantTotalDepto; ?>
                                                                        </td>
                                                                    </tr>

                                                            <?php
                                                                }
                                                            }

                                                            array_push($arrayCantTotal, $totalMes1);
                                                            array_push($arrayCantTotal, $totalMes2);
                                                            array_push($arrayCantTotal, $totalMes3);
                                                            array_push($arrayCantTotal, $totalMes4);
                                                            array_push($arrayCantTotal, $totalMes5);
                                                            array_push($arrayCantTotal, $totalMes6);
                                                            array_push($arrayCantTotal, $totalMes7);
                                                            array_push($arrayCantTotal, $totalMes8);
                                                            array_push($arrayCantTotal, $totalMes9);
                                                            array_push($arrayCantTotal, $totalMes10);
                                                            array_push($arrayCantTotal, $totalMes11);
                                                            array_push($arrayCantTotal, $totalMes12);
                                                            ?>

                                                            <tr class="bg-info">
                                                                <th scope="row">Totales</th>

                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                ?>
                                                                    <td class="text-center text-bold"><?php echo $arrayCantTotal[$i] ?></td>
                                                                <?php
                                                                    $cantPorMesTotal = $cantPorMesTotal + $arrayCantTotal[$i];
                                                                }
                                                                ?>

                                                                <td class="text-center text-bold"><?php echo $cantPorMesTotal ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr>

                                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                                        <?php
                                        $activeDash = '';
                                        for ($i = 1; $i < 13; $i++) {

                                            $mesNow = date('m', time());
                                            if ($i == $mesNow) {
                                                $activeDash = 'active';
                                            } else {
                                                $activeDash = '';
                                            }
                                        ?>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link <?php echo $activeDash; ?>" id="pills-home<?php echo $i; ?>" data-toggle="pill" href="#pills-homea<?php echo $i; ?>" role="tab" aria-controls="pills-home" aria-selected="true"> <b><?php echo mesEspañol2($i) ?></b> </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent">
                                        <?php
                                        $activeDash2 = '';
                                        for ($i = 1; $i < 13; $i++) {
                                            $mesAnioTab = date('Y', time());

                                            if ($i < 10) {
                                                $mesAnioTab = $mesAnioTab . '-0' . $i;
                                            } else {
                                                $mesAnioTab = $mesAnioTab . '-' . $i;
                                            }

                                            $mesNow = date('m', time());
                                            $mesEspañol2Dash = mesEspañol2($mesNow);
                                            if ($i == $mesNow) {
                                                $activeDash2 = 'show active';
                                            } else {
                                                $activeDash2 = '';
                                            }

                                        ?>

                                            <div class="tab-pane fade <?php echo $activeDash2; ?>" id="pills-homea<?php echo $i; ?>" role="tabpanel" aria-labelledby="pills-home<?php echo $i; ?>">

                                                <div class="row">

                                                    <div class="col-12 col-md-6 h-100">
                                                        <div class="card card-purple collapsed-card">
                                                            <div class="card-header">
                                                                <h3 class="card-title"><i class="fas fa-briefcase"></i>&nbsp;&nbsp;<b>Cantidad de Hojas Técnicas por Tipo de Mantenimiento</b></h3>

                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <!-- /.card-tools -->
                                                            </div>
                                                            <!-- /.card-header -->
                                                            <div class="card-body">
                                                                <table class="table table-hover">
                                                                    <thead style="font-size: 12px">
                                                                        <tr>
                                                                            <th scope="col" class="text-center ">ESPECIALIDAD</th>
                                                                            <th scope="col" class="text-center">MPI</th>
                                                                            <th scope="col" class="text-center">MRI</th>
                                                                            <th scope="col" class="text-center">MPE</th>
                                                                            <th scope="col" class="text-center">MRE</th>
                                                                            <th scope="col" class="text-center">OM</th>
                                                                            <th scope="col" class="text-center">IS</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody style="font-size: 12px">
                                                                        <?php
                                                                        $sqlDeptoT2 = "SELECT * FROM departamentos ORDER BY nomDepartamento ASC";

                                                                        $resDeptoT2 = mysqli_query($conexion, $sqlDeptoT2);

                                                                        $mesEspañol2Dash2 = mesEspañol2($i);


                                                                        while ($rowDeptoT2 = $resDeptoT2->fetch_assoc()) {

                                                                            $nomDeptoT2 = $rowDeptoT2['nomDepartamento'];

                                                                            $idDeptoT2 = $rowDeptoT2['idDepartamento'];

                                                                            if ($nomDeptoT2 == 'Informática' || $nomDeptoT2 == 'Ingeniería Operacional') {
                                                                            } else {
                                                                        ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo $nomDeptoT2; ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPI', $mesEspañol2Dash2); ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRI', $mesEspañol2Dash2); ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MPE', $mesEspañol2Dash2); ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'MRE', $mesEspañol2Dash2); ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'OM', $mesEspañol2Dash2); ?></td>
                                                                                    <td class="text-center"><?php echo obtieneHTTipo($idDeptoT2, 'IS', $mesEspañol2Dash2); ?></td>
                                                                                </tr>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>

                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td class="text-center">Totales</td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPI', $mesEspañol2Dash2); ?> </td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRI', $mesEspañol2Dash2); ?> </td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MPE', $mesEspañol2Dash2); ?> </td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('MRE', $mesEspañol2Dash2); ?> </td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('OM', $mesEspañol2Dash2); ?> </td>
                                                                            <td class="text-center"><?php echo obtieneTotalHTTipo('IS', $mesEspañol2Dash2); ?> </td>
                                                                        </tr>
                                                                    </tfoot>

                                                                </table>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </div>

                                                </div>

                                                <br>

                                                <br>

                                                <?php

                                                $numCardHT = 1;

                                                $obtenerCantidadDepartamentos = obtenerCantidadDepartamentos();

                                                $colorTipoDepartamento = '';

                                                ?>
                                                <div class="row">
                                                    <?php

                                                    $sqlDeptoT3 = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE 'Ingeniería Operacional'  AND nomDepartamento NOT LIKE 'Informática' ORDER BY nomDepartamento ASC";

                                                    $resDeptoT3 = mysqli_query($conexion, $sqlDeptoT3);

                                                    while ($rowDeptoT3 = $resDeptoT3->fetch_assoc()) {

                                                        $idDeptoT3 = $rowDeptoT3['idDepartamento'];
                                                        $nomDeptoT3 = $rowDeptoT3['nomDepartamento'];

                                                        switch ($numCardHT) {
                                                            case 1:
                                                                $colorTipoDepartamento = 'card-success';
                                                                break;
                                                            case 2:
                                                                $colorTipoDepartamento = 'card-purple';
                                                                break;
                                                            case 3:
                                                                $colorTipoDepartamento = 'card-info';
                                                                break;
                                                            case 4:
                                                                $colorTipoDepartamento = 'card-danger';
                                                                break;
                                                            case 5:
                                                                $colorTipoDepartamento = 'card-orange';
                                                                break;
                                                            case 6:
                                                                $colorTipoDepartamento = 'card-yellow';
                                                                break;
                                                            case 7:
                                                                $colorTipoDepartamento = 'card-success';
                                                                break;
                                                            case 8:
                                                                $colorTipoDepartamento = 'card-purple';
                                                                break;
                                                            case 9:
                                                                $colorTipoDepartamento = 'card-info';
                                                                break;
                                                            case 10:
                                                                $colorTipoDepartamento = 'card-danger';
                                                                break;
                                                            case 11:
                                                                $colorTipoDepartamento = 'card-orange';
                                                                break;
                                                            case 11:
                                                                $colorTipoDepartamento = 'card-yellow';
                                                                break;
                                                            default:
                                                                $colorTipoDepartamento = '';
                                                        }
                                                    ?>
                                                        <div class="col-12 col-md-6 h-100">
                                                            <div class="card <?php echo $colorTipoDepartamento; ?> collapsed-card">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">&nbsp;&nbsp;<b>
                                                                            <?php
                                                                            echo $nomDeptoT3;

                                                                            $numCardHT = $numCardHT + 1;
                                                                            ?>
                                                                        </b></h3>

                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /.card-tools -->
                                                                </div>
                                                                <!-- /.card-header -->
                                                                <div class="card-body">
                                                                    <table class="table table-hover">
                                                                        <thead style="font-size: 12px">
                                                                            <tr>
                                                                                <th scope="col" class="text-center">PERSONAL</th>
                                                                                <th scope="col" class="text-center">CANTIDAD DE DOCUMENTOS</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size: 12px">
                                                                            <?php


                                                                            $sql = "SELECT p.idPersonal, p.nomPersonal,p.cargoPersonal, COUNT(o.idOrden) as total   FROM personal as p INNER JOIN ordenes_trabajos_general as o ON p.idPersonal = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'personal' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idPersonal ORDER BY p.nomPersonal ASC;";
                                                                            $res = mysqli_query($conexion, $sql);
                                                                            $totalDeptoFor = 0;

                                                                            while ($row = $res->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['nomPersonal'] = $row['nomPersonal'];
                                                                                $datos['cargoPersonal'] = $row['cargoPersonal'];
                                                                                $datos['idPersonal'] = $row['idPersonal'];
                                                                                $datos['total'] = $row['total'];
                                                                                $nomPersonal = $datos['nomPersonal'];
                                                                                $cargoPersonal = $datos['cargoPersonal'];
                                                                                $idPersonal = $datos['idPersonal'];
                                                                                $total = $datos['total'];

                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo $nomPersonal . " (" . $cargoPersonal . ")" ?></td>
                                                                                    <td class="text-center"><?php echo $total ?></td>
                                                                                </tr>

                                                                            <?php

                                                                                $totalDeptoFor = $totalDeptoFor + $total;
                                                                            }

                                                                            $sqlJefeDeptoFor = "SELECT p.idJefe, p.nomJefe,p.cargoJefe, COUNT(o.idOrden) as total  FROM jefes_tecnicos as p INNER JOIN ordenes_trabajos_general as o ON p.idJefe = o.idCreador WHERE o.idDepartamento = " . $idDeptoT3 . " AND o.tipoUsuario = 'jefe tecnico' AND o.fechaOrden BETWEEN '" . $mesAnioTab . "-01 00:00:00' AND '" . $mesAnioTab . "-31 23:59:59' GROUP BY p.idJefe ORDER BY p.nomJefe ASC";
                                                                            $resJefeDeptoFor = mysqli_query($conexion, $sqlJefeDeptoFor);

                                                                            while ($rowJefeDeptoFor = $resJefeDeptoFor->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['nomJefe'] = $rowJefeDeptoFor['nomJefe'];
                                                                                $datos['cargoJefe'] = $rowJefeDeptoFor['cargoJefe'];
                                                                                $datos['idJefe'] = $rowJefeDeptoFor['idJefe'];
                                                                                $datos['total'] = $rowJefeDeptoFor['total'];
                                                                                $nomJefe = $datos['nomJefe'];
                                                                                $cargoJefe = $datos['cargoJefe'];
                                                                                $idJefe = $datos['idJefe'];
                                                                                $total = $datos['total'];
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo $nomJefe . " (" . $cargoJefe . ")" ?></td>
                                                                                    <td class="text-center"><?php echo $total ?></td>
                                                                                </tr>

                                                                            <?php

                                                                                $totalDeptoFor = $totalDeptoFor + $total;
                                                                            }

                                                                            ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td class="text-center">Total</td>
                                                                                <td class="text-center"><?php echo $totalDeptoFor ?> </td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                                <!-- /.card-body -->
                                                            </div>
                                                        </div>

                                                    <?php


                                                    }

                                                    ?>
                                                </div>

                                            </div><!-- fin pestaña -->
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                    <?php

                                    if (isset($_GET['noEditId'])) {
                                        echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;No fue posible recuperar el ID del registro para editar.</h5></div>';
                                    }

                                    if (isset($_GET['ExitoActualizar'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al actualizar la hoja tecnica</h5></div>';
                                    }

                                    if (isset($_GET['exitoEliminarHoja'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito al eliminar hoja tecnica</h5></div>';
                                    }

                                    if (isset($_GET['exitoGenerar'])) {
                                        echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Exito la Hoja Técnica</h5></div>';
                                    }

                                    ?>

                                    <!-- Button trigger modal -->
                                    <!-- <button type="button" class="btn btn-info" style="float: right;margin-right:10px" data-toggle="modal" data-target="#staticBackdrop">
                                        <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Generar Excel Dinamico
                                    </button> -->

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Generar Informe - Excel</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <form action="../sdo-pdf/SDO-Reporte-HT.php" method="POST">

                                                        <div class="row">
                                                            <div class="col-md-6 ml-auto">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title tex-center"> <b>Campos</b> </h5>
                                                                        <p class="card-text text-muted">Escoja los campos a mostrar en el excel</p>
                                                                        <hr>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="campos[]" value="folioOrden" id="folioCheck">
                                                                            <label class="form-check-label" for="folioCheck">
                                                                                Folio
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="campos[]" type="checkbox" value="nomTecnicoRecepcionador" id="tecCheck">
                                                                            <label class="form-check-label" for="tecCheck">
                                                                                Tec. Recepcionador
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="campos[]" type="checkbox" value="idDepartamento" id="tecDepaCheck">
                                                                            <label class="form-check-label" for="tecDepaCheck">
                                                                                Tec. Dapartamento
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="campos[]" type="checkbox" value="tipoMantencion" id="tipoMantCheck">
                                                                            <label class="form-check-label" for="tipoMantCheck">
                                                                                Tipo Mantencion
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="campos[]" type="checkbox" value="nomSolicitante" id="solicitanteCheck">
                                                                            <label class="form-check-label" for="solicitanteCheck">
                                                                                Solicitante
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" name="campos[]" type="checkbox" value="fechaOrden" id="fechaRecepCheck">
                                                                            <label class="form-check-label" for="fechaRecepCheck">
                                                                                Fecha Recepción
                                                                            </label>
                                                                        </div>
                                                                        <!-- <div class="form-check">
                                                                        <input class="form-check-input" name="campos" type="checkbox" value="" id="fechaRecepCheck">
                                                                        <label class="form-check-label" for="fechaRecepCheck">
                                                                            Fecha Recepción
                                                                        </label>
                                                                    </div> -->
                                                                        <hr>
                                                                        <p class="card-text text-muted">Escoja los campos del equipo a mostrar</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 ml-auto">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h5 class="card-title tex-center"> <b>Rango Tiempo</b> </h5>
                                                                        <p class="card-text text-muted">Seleccione el periodo para filtrar</p>
                                                                        <hr>
                                                                        <div class="form-group">
                                                                            <h6 class="tex-center">Escoja el periodo</h6>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios1" value="mensual">
                                                                                <label class="form-check-label" for="exampleRadios1">
                                                                                    Mensual
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios2" value="rangoFechas">
                                                                                <label class="form-check-label" for="exampleRadios2">
                                                                                    Rango de Fechas
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios3" value="todos">
                                                                                <label class="form-check-label" for="exampleRadios3">
                                                                                    Todos
                                                                                </label>
                                                                            </div>

                                                                        </div>

                                                                        <div class="row" id="mesInforme">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Escoja el mes:<code>*</code></label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    <select type="text" class="form-control form-control-sm" required id="mensual" name="mesInforme">
                                                                                        <option value="" disabled selected>-- Seleccione --</option>
                                                                                        <option value="Enero">Enero</option>
                                                                                        <option value="Febrero">Febrero</option>
                                                                                        <option value="Marzo">Marzo</option>
                                                                                        <option value="Abril">Abril</option>
                                                                                        <option value="Mayo">Mayo</option>
                                                                                        <option value="Junio">Junio</option>
                                                                                        <option value="Julio">Julio</option>
                                                                                        <option value="Agosto">Agosto</option>
                                                                                        <option value="Septiembre">Septiembre</option>
                                                                                        <option value="Octubre">Octubre</option>
                                                                                        <option value="Noviembre">Noviembre</option>
                                                                                        <option value="Diciembre">Diciembre</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" id="rangoFecha">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :<code>*</code></label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    <input type="date" class="form-control form-control-sm" required id="fechaInicio" name="fechaInicio">

                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :<code>*</code></label>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    <input type="date" class="form-control form-control-sm" onchange="ValidarFechas()" required id="fechaFin" name="fechaFin">

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" id="enviar" disabled class="btn btn-success">Generar Excel</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>
                                                    <center> N°</center>
                                                </th>
                                                <th>
                                                    <center>Folio<center>
                                                </th>
                                                <th>
                                                    <center>Tec. Recepcionador<center>
                                                </th>
                                                <th>
                                                    <center>Tec. Dapartamento<center>
                                                </th>
                                                <th>
                                                    <center>Tipo Mantencion<center>
                                                </th>
                                                <th>
                                                    <center>Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                                <th>
                                                    <center>Descargar<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = 'SELECT * FROM ordenes_trabajos_general ORDER BY fechaOrden DESC';

                                            $res = mysqli_query($conexion, $query);

                                            $numOrden = 1;

                                            while ($row = $res->fetch_assoc()) {
                                            ?>
                                                <tr>
                                                    <td style="vertical-align:middle;">
                                                        <center><?php echo $numOrden; ?></center>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $folioOrden = mb_strtoupper($row['folioOrden']);
                                                        echo $folioOrden;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $tecRecep = mb_strtoupper($row['nomTecnicoRecepcionador']);
                                                        echo $tecRecep;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $idDepartamento = mb_strtoupper($row['idDepartamento']);

                                                        $queryDepa = "SELECT * FROM departamentos WHERE idDepartamento  = $idDepartamento";
                                                        $resDepa = mysqli_query($conexion, $queryDepa);

                                                        while ($rowDepa = $resDepa->fetch_assoc()) {
                                                            $datos = [];
                                                            $datos['nomDepartamento'] = $rowDepa['nomDepartamento'];
                                                            $nomDepartamento = $datos['nomDepartamento'];
                                                        }
                                                        echo $nomDepartamento;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $tipoMantencion = mb_strtoupper($row['tipoMantencion']);
                                                        echo $tipoMantencion;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="justify">
                                                        <?php
                                                        $nuevoNom = mb_strtoupper($row['nomSolicitante']);
                                                        echo $nuevoNom;

                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <CENTER><?php if ($row['fechaOrden'] == 0) {
                                                                    echo "No registra fecha";
                                                                } else {
                                                                    $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaOrden'])));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaOrden']));
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                } ?></CENTER>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" target="_blank" class="" action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-primary"><i class="fa fa-eye"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);

                                                            $sqlArchivo = "SELECT count(*) as cantidad FROM archivos_orden_general WHERE idOrden = $idOrden ";
                                                            $resArchivo = mysqli_query($conexion, $sqlArchivo);

                                                            while ($rowArchivo = $resArchivo->fetch_assoc()) {
                                                                $datos = [];
                                                                $datos['cantidad'] = $rowArchivo['cantidad'];
                                                                $cantidad = $datos['cantidad'];
                                                            }

                                                            if ($cantidad > 0) {
                                                            ?>
                                                                <form style="margin: 0; text-align: center;" class="" action="ordenes-administrar-general.php" method="post">
                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <input type="hidden" name="folio" value="<?php echo $folioOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-info"> <i class="fas fa-download"></i> </button>
                                                                </form>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <p>Sin Archivos Adjuntos</p>
                                                            <?php
                                                            }

                                                            ?>
                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <form style="margin: 0; text-align: center;" class="" action="ordenes-modificar-general.php" method="post">

                                                                    <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                </form>

                                                            <?php

                                                            }
                                                            ?>


                                                        </center>
                                                    </td>
                                                    <td style="vertical-align:middle;">
                                                        <center>
                                                            <?php
                                                            $idOrden = $row['idOrden'];
                                                            $idOrden = trim($idOrden);
                                                            if ($idOrden == 0 || $idOrden == "" || $idOrden == " " || $idOrden == "  ") {
                                                            ?>
                                                                <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                            } else {

                                                            ?>

                                                                <a type="submit" id="<?php echo $idOrden ?>" class="btn btn-block btn-danger eliminar"><i class="fa fa-trash"></i></a>

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
                                                    <center>Folio<center>
                                                </th>
                                                <th>
                                                    <center>Tec. Recepcionador<center>
                                                </th>
                                                <th>
                                                    <center>Tec. Dapartamento<center>
                                                </th>
                                                <th>
                                                    <center>Tipo Mantencion<center>
                                                </th>
                                                <th>
                                                    <center>Solicitante<center>
                                                </th>
                                                <th>
                                                    <center>Fecha de Recepci&oacute;n<center>
                                                </th>
                                                <th>
                                                    <center>Visualizar<center>
                                                </th>
                                                <th>
                                                    <center>Descargar<center>
                                                </th>
                                                <th>
                                                    <center>Modificar<center>
                                                </th>
                                                <th>
                                                    <center>Eliminar<center>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
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
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'ADMINISTRACIÓN DE HOJAS TÉCNICAS - SDO',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function(data, row, column, node) {
                                data = $('<p>' + data + '</p>').text();
                                return $.isNumeric(data.replace(',', '.')) ? data.replace('.', ',') : data;
                            }
                        }
                    }
                }],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
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
                initComplete: function() {
                    this.api().columns([3]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Departamento</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    this.api().columns([4]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Tipo</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });


                }
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar la hoja técnica seleccionada?',
                    text: 'Esta acción no se puede revertir',
                    showConfirmButton: true,
                    confirmButtonText: 'Si , eliminar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No, lo pensare',
                    cancelButtonColor: '#d33',
                    buttonsStyling: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../sdo-funciones/ordenes-eliminar-general.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Hoja Técnica Eliminada Correctamente',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1400)
                            }
                        })
                    }
                })
            })
        )
    </script>
    <script>
        function mostrarInputs(valor) {

            var enviar = document.getElementById("enviar");


            //Mensual
            mesInforme = document.getElementById('mesInforme');
            mensual = document.getElementById('mensual');

            //Rango de fechas
            rangoFecha = document.getElementById('rangoFecha');
            fechaInicio = document.getElementById('fechaInicio');
            fechaFin = document.getElementById('fechaFin');

            if (valor == 'mensual') {
                enviar.disabled = false;


                mensual.required = true;
                mesInforme.style.display = 'block';

                //Configura rango fechas 
                rangoFecha.style.display = 'none';
                fechaInicio.required = false;
                fechaFin.required = false;


            } else if (valor == 'rangoFechas') {
                enviar.disabled = false;

                fechaInicio.required = true;
                fechaFin.required = true;
                rangoFecha.style.display = 'block';


                //Configura radio mensual
                mensual.required = false;
                mesInforme.style.display = 'none';
            } else if (valor == 'todos') {
                enviar.disabled = false;

                //Configura rango fechas 
                rangoFecha.style.display = 'none';
                fechaInicio.required = false;
                fechaFin.required = false;

                //Configura radio mensual
                mensual.required = false;
                mesInforme.style.display = 'none';
            }

            console.log(valor);

        }
    </script>
    <script>
        function ValidarFechas() {
            var fechainicial = document.getElementById("fechaInicio").value;
            var fechafinal = document.getElementById("fechaFin").value;
            var enviar = document.getElementById("enviar");



            console.log(fechainicial)
            console.log(fechafinal)

            fechainicial = Date.parse(fechainicial);
            fechafinal = Date.parse(fechafinal);

            console.log(fechainicial)
            console.log(fechafinal)

            if (fechainicial > fechafinal) {
                alert("La fecha final debe ser mayor a la fecha inicial");
                enviar.disabled = true;

            } else {
                enviar.disabled = false;
            }

        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            // Show div by removing inline display none style rule
            $(".show-verdash").click(function() {
                $("#rowMenu3").show();
                $("#verDashAC").hide();
                $("#ocultarDashAC").show();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu3").hide();
                $("#verDashAC").show();
                $("#ocultarDashAC").hide();
            });
        });
    </script>


</body>

</html>