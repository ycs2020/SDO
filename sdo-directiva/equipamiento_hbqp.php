<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomUsuario = trim($_SESSION['nomUsuario'] ?? '');
$idDepartamento = $_SESSION['idDepartamento'] ?? 0;
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'] ?? 0;

date_default_timezone_set('America/Santiago');

/**
 * Ejecuta una consulta SQL y devuelve el valor del campo 'totalEquipos'
 */
function obtenerTotalEquipos($sql)
{
    include '../sdo-funciones/conexion.php';

    $res = mysqli_query($conexion, $sql);
    if ($row = mysqli_fetch_assoc($res)) {
        return (int) $row['totalEquipos'];
    }
    return 0;
}

/**
 * Total de equipos por categoría
 */
function totalEquipos($idCategoria)
{
    $sql = "SELECT COUNT(*) AS totalEquipos FROM equipamiento_hbqp WHERE idCategoria = $idCategoria";
    return obtenerTotalEquipos($sql);
}

/**
 * Total de equipos por categoría (función duplicada que puede omitirse)
 */
function totalSumaEquipos($idCategoria)
{
    // Esta función es redundante con totalEquipos
    return totalEquipos($idCategoria);
}

/**
 * Equipos operativos por categoría
 */
function equiposOperativos($idCategoria)
{
    $sql = "SELECT COUNT(*) AS totalEquipos FROM equipamiento_hbqp 
            WHERE idCategoria = $idCategoria AND estadoEquipo = 'Operativo'";
    return obtenerTotalEquipos($sql);
}

/**
 * Equipos con observación (se cuentan desde historial)
 */
function equiposConObservacion($idCategoria)
{
    $sql = "SELECT COUNT(*) AS totalEquipos FROM historial_cambios_equipos 
            WHERE idCategoria = $idCategoria";
    return obtenerTotalEquipos($sql);
}

/**
 * Equipos fuera de servicio por categoría
 */
function equiposFueraDeServicio($idCategoria)
{
    $sql = "SELECT COUNT(*) AS totalEquipos FROM equipamiento_hbqp 
            WHERE idCategoria = $idCategoria AND estadoEquipo = 'Fuera de Servicio'";
    return obtenerTotalEquipos($sql);
}

// $fecha = date('d-m-Y h:i a');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema BMS - Control Centralizado</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <style>
        .zoom-card {
            transition: transform 0.3s;
        }

        .zoom-card:hover {
            transform: scale(1.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .contador {
            display: inline-block;
            background-color: rgb(40, 82, 167);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <!-- /.navbar -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-flex p-0 text-center bg-success text-white">
                                                <ul style="font-size: 10pt;" class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                                    <?php
                                                    $sqlSelect = "SELECT * FROM departamentos WHERE (nomDepartamento NOT LIKE ('%Control Centralizado%')) AND (nomDepartamento NOT LIKE ('%Informática%')) AND (nomDepartamento NOT LIKE ('%Ingeniería Operacional%')) AND (nomDepartamento NOT LIKE ('%Subdirección de Operaciones%')) ORDER BY idDepartamento ASC";

                                                    $resSelect = mysqli_query($conexion, $sqlSelect);

                                                    if ($resSelect && mysqli_num_rows($resSelect) > 0) {

                                                        while ($rowSelect = $resSelect->fetch_assoc()) {

                                                            $idDepartamento = $rowSelect['idDepartamento'];
                                                            $nomDepartamento = $rowSelect['nomDepartamento'];

                                                            if ($nomDepartamento == 'Instrumentación') {
                                                                $nomDepartamento = 'Control Centralizado';
                                                                $active = 'active';
                                                                $selected = 'true';
                                                            } else {
                                                                $selected = 'false';
                                                                $active = '';
                                                            }


                                                            ?>
                                                            <li class="nav-item text-white"><a class="nav-link <?php echo $active ?> text-white" href="#vistaDepto<?php echo $idDepartamento ?>" data-toggle="tab"><?php echo $nomDepartamento ?></a></li>

                                                            <?php
                                                        }
                                                    }

                                                    ?>
                                                </ul>
                                            </div>

                                            <div class="card-body text-center">


                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCategoria"><b> <i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir una nueva categoría </b></button>

                                                <!-- Aqui va codigo de un modal para agregar nuevas categorias -->
                                                <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success">
                                                                <h5 class="modal-title" id="modalCategoriaLabel">Nueva Categoría</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="../sdo-funciones/guarda-categoria-equipamiento.php" method="POST" enctype="multipart/form-data">
                                                                    <div class="form-group">
                                                                        <label for="nombreCategoria" style="font-size:15px">Nombre Categoría:</label>
                                                                        <input type="text" class="form-control" required id="nombreCategoria" name="nombreCategoria" placeholder="Escriba el nombre de la categoría">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="departamentoCategoria" style="font-size:15px">Departamento:</label>
                                                                        <select class="form-control " required id="departamentoCategoria" name="departamentoCategoria">
                                                                            <option value="" selected disabled>-- Seleccione Departamento --</option>
                                                                            <?php

                                                                            $seleccionaDepartamentos = "SELECT * FROM departamentos WHERE nomDepartamento NOT LIKE ('%Control Centralizado%')";
                                                                            $respuesta = mysqli_query($conexion, $seleccionaDepartamentos);

                                                                            while ($fila = $respuesta->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['idDepartamento'] = $fila['idDepartamento'];
                                                                                $datos['nomDepartamento'] = $fila['nomDepartamento'];
                                                                                $idDepartamento = $datos['idDepartamento'];
                                                                                $nomDepartamento = $datos['nomDepartamento'];
                                                                                ?>
                                                                                <option value="<?php echo $idDepartamento ?>"><?php echo $nomDepartamento ?></option>
                                                                                <?php
                                                                            }

                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="input-group mb-3">
                                                                        <div class="custom-file">
                                                                            <input type="file" required class="custom-file-input" id="imagen" name="imagenCategoria" aria-describedby="inputGroupFileAddon01" accept="image/*">
                                                                            <label class="custom-file-label" for="imagen">Subir Imagen</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-4">
                                                                        <h4>Vista previa de la imagen:</h4>
                                                                        <div id="vista-previa"></div>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Modal</button>
                                                                <button type="submit" class="btn btn-info">Guardar Categoría</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Aqui va codigo de un modal para editar la informacion de las categorias ya existentes -->
                                                <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title" id="modalEditarCategoriaLabel">Editar Categoria</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="../sdo-funciones/guarda-edicion-categoria-equipamiento.php" method="POST" enctype="multipart/form-data">
                                                                    <input type="hidden" name="idCategoria" id="idCategoria">
                                                                    <div class="form-group">
                                                                        <label for="nombreCategoria" style="font-size:15px">Nombre Categoría:</label>
                                                                        <input type="text" class="form-control" required id="nombreCategoriaEdita" name="nombreCategoriaEdita" placeholder="Escriba el nombre de la categoría">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="departamentoCategoria" style="font-size:15px">Departamento:</label>
                                                                        <select class="form-control " required id="departamentoCategoriaEdita" name="departamentoCategoriaEdita">
                                                                            <option value="" selected disabled>-- Seleccione Departamento --</option>
                                                                            <?php

                                                                            $seleccionaDepartamentos = "SELECT * FROM departamentos";
                                                                            $respuesta = mysqli_query($conexion, $seleccionaDepartamentos);

                                                                            while ($fila = $respuesta->fetch_assoc()) {
                                                                                $datos = [];
                                                                                $datos['idDepartamento'] = $fila['idDepartamento'];
                                                                                $datos['nomDepartamento'] = $fila['nomDepartamento'];
                                                                                $idDepartamento = $datos['idDepartamento'];
                                                                                $nomDepartamento = $datos['nomDepartamento'];
                                                                                ?>
                                                                                <option value="<?php echo $idDepartamento ?>"><?php echo $nomDepartamento ?></option>
                                                                                <?php
                                                                            }

                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="input-group mb-3">
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="imagen" name="imagenCategoriaEdita" aria-describedby="imagen" accept="image/*">
                                                                            <label class="custom-file-label" for="imagen">Subir Imagen</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-4">
                                                                        <hr>
                                                                        <h4>Imagen Actual:</h4>
                                                                        <img class="img-fluid" id="img" src=""></img>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Modal</button>
                                                                <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="tab-content">

                                                    <?php
                                                    $sqlSelect1 = "SELECT * FROM departamentos WHERE (nomDepartamento NOT LIKE ('%Control Centralizado%')) AND (nomDepartamento NOT LIKE ('%Informática%')) AND (nomDepartamento NOT LIKE ('%Ingeniería Operacional%')) ORDER BY idDepartamento ASC";

                                                    $resSelect1 = mysqli_query($conexion, $sqlSelect1);

                                                    if ($resSelect1 && mysqli_num_rows($resSelect1) > 0) {

                                                        while ($rowSelect1 = $resSelect1->fetch_assoc()) {

                                                            $idDepartamento1 = $rowSelect1['idDepartamento'];
                                                            $nomDepartamento1 = $rowSelect1['nomDepartamento'];

                                                            if ($nomDepartamento1 == 'Instrumentación') {
                                                                $nomDepartamento1 = 'Control Centralizado';
                                                                $active1 = 'show active';
                                                                $selected1 = 'true';
                                                            } else {
                                                                $selected1 = 'false';
                                                                $active1 = '';
                                                            }


                                                            ?>
                                                            <div class="tab-pane <?php echo $active1 ?>" id="vistaDepto<?php echo $idDepartamento1 ?>">
                                                                <div class="col-12">
                                                                    <?php
                                                                    $sqlCategorias = "SELECT COUNT(*) AS TOTAL FROM equipamiento_hbqp WHERE idDepaPertenece = " . $idDepartamento1;
                                                                    $resCategorias = mysqli_query($conexion, $sqlCategorias);
                                                                    $cantidadEquipos = 0;
                                                                    while ($row = $resCategorias->fetch_assoc()) {
                                                                        $totalEquipos = $row['TOTAL'];
                                                                        ?>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <div class="contador text-center">
                                                                        <span class="numero">
                                                                            <h5><b>Total de equipos en este departamento son: <?php echo $totalEquipos ?></b></h5>
                                                                        </span>
                                                                    </div>
                                                                    <br>
                                                                    <br>

                                                                    <!--
                                                                    <div class="contadors text-center">
                                                                         <span class="numero">
                                                                            <h5><b>Valor Total Equipamiento y Sistemas Control Centralizado: <?php echo $totalTodosCa?></b></h5>
                                                                        </span>
                                                                    </div>
                                                                    -->

                                                                    <br>

                                                                    <div class="row">

                                                                        <?php

                                                                        $sqlCategorias = "SELECT * FROM categorias_equipos WHERE idDepartamento = " . $idDepartamento1;

                                                                        $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                                                        $numCategorias = 1;

                                                                        if ($resCategorias && mysqli_affected_rows($conexion) > 0) {
                                                                            while ($rowCategorias = $resCategorias->fetch_assoc()) {

                                                                                $idCategoria = trim($rowCategorias['idCategoria']);
                                                                                $nomCategoria = trim($rowCategorias['nomCategoria']);
                                                                                $rutaImagen = $rowCategorias['imagenCategoria'];
                                                                                $nomCategoria = mb_strtolower($nomCategoria);

                                                                                $nomCategoria = mb_convert_case($nomCategoria,MB_CASE_UPPER,'UTF-8');

                                                                                if ($nomCategoria == 'CONTROL CENTRALIZADO' || $nomCategoria == 'SISTEMA INCENDIO' || $nomCategoria == 'bitacora' || $nomCategoria == 'bitácora' || $nomCategoria == 'Bitácora' || $nomCategoria == 'BITACORA' || $nomCategoria == 'BITÁCORA') {
                                                                                } else {
                                                                                    ?>
                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body box-profile w">
                                                                                                <div class="text-center">
                                                                                                    <?php

                                                                                                    if (!empty($rutaImagen)) {
                                                                                                        ?>
                                                                                                        <img src="<?php echo $rutaImagen; ?>"style="height: 150px; width: 200px" alt="<?php echo $nomCategoria; ?>">
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        <img class=" img-fluid" src="img/controlCent-HBQP.png" width="200px" alt="Control Centralizado">
                                                                                                        <?php
                                                                                                    }

                                                                                                    ?>


                                                                                                </div>
                                                                                                <br>

                                                                                                <h6 class=" text-center text-bold"><?php echo $nomCategoria; ?> </h6>

                                                                                                <hr>
                                                                                                <div class=" text-center">
                                                                                                    <button class="btn bg-blue  btn-sm editar" data-id="<?php echo $idCategoria ?>" title="Editar la categoria"><i class="fas fa-edit"></i></button>
                                                                                                </div>
                                                                                                <br>
                                                                                                <form action="carpeta-equipo.php" method="POST">
                                                                                                    <input type="hidden" name="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                    <button type="submit" class="btn btn-block bg-info "><b>Administrar</b></button>
                                                                                                </form>
                                                                                                <hr>
                                                                                                <div class=" text-center">
                                                                                                    <div class="modal fade" id="modalInfoEquipos<?php echo $idCategoria?>" tabindex="-1" aria-labelledby="labelInfoEquipos" aria-hidden="true">
                                                                                                        <div class="modal-dialog modal-xl">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header bg-primary">
                                                                                                                    <h5 class="modal-title" id="labelInfoEquipos"><b> Informaci&oacute;n de Equipos </b></h5>
                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">

                                                                                                                    <table class="table table-bordered table-sm" id="tablaInfoEquipos">

                                                                                                                        <thead class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Modelo Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Marca Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Cantidad</th>
                                                                                                                            <th scope="col" class="text-center">Precio Unitario (Con IVA)</th>
                                                                                                                            <th scope="col" class="text-center">Precio Total (Con IVA)</th>

                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <?php
                                                                                                                        $totalTodos = 0;
                                                                                                                        $sqlInfoEquipo = "SELECT DISTINCT modelo, marca FROM equipamiento_hbqp WHERE idCategoria = " . $idCategoria . " AND (modelo IS NOT NULL AND modelo NOT LIKE '')";
                                                                                                                        $resInfoEquipo = mysqli_query($conexion, $sqlInfoEquipo);
                                                                                                                        $sumaTotalSistema = 0;
                                                                                                                        while ($rowInfoEquipo = $resInfoEquipo->fetch_assoc()) {

                                                                                                                            $marca = $rowInfoEquipo['marca'];

                                                                                                                            if (empty($marca)) {
                                                                                                                                $marca = 'No Aplica';
                                                                                                                            }

                                                                                                                            $modelo = $rowInfoEquipo['modelo'];

                                                                                                                            if (empty($modelo)) {
                                                                                                                                $modelo = 'No Aplica';
                                                                                                                            }

                                                                                                                            $valorConIva = obtenerMayorValorEquipamiento($modelo, $idCategoria);

                                                                                                                            if ($valorConIva > 0) {
                                                                                                                                $valorConIva2 = moneda_chilena($valorConIva);
                                                                                                                                $valorMultiplicar = $valorConIva;
                                                                                                                            } else {
                                                                                                                                $valorConIva2 = '$ 0';
                                                                                                                                $valorMultiplicar = 0;
                                                                                                                            }

                                                                                                                            $contarModelosDistintos = contarModelosDistintos($modelo, $idCategoria);

                                                                                                                            if ($contarModelosDistintos > 0) {
                                                                                                                                $contarModelosDistintos2 = $contarModelosDistintos;
                                                                                                                                $valorMultiplicar2 = $contarModelosDistintos;
                                                                                                                            } else {
                                                                                                                                $contarModelosDistintos2 = 0;
                                                                                                                                $valorMultiplicar2 = 0;
                                                                                                                            }

                                                                                                                            $total = $valorMultiplicar * $valorMultiplicar2;
                                                                                                                            $totalTodos = $totalTodos + $total;

                                                                                                                            if ($total > 0) {
                                                                                                                                $sumaTotalSistema = $sumaTotalSistema + $total;
                                                                                                                            } else {

                                                                                                                                $sumaTotalSistema = $sumaTotalSistema + 0;
                                                                                                                            }

                                                                                                                            if ($total > 0) {
                                                                                                                                $totalFormat = moneda_chilena($total);
                                                                                                                            } else {
                                                                                                                                $totalFormat = '$ 0';
                                                                                                                            }

                                                                                                                            $marca2 = mb_convert_case($marca, MB_CASE_UPPER, 'UTF-8');
                                                                                                                            $modelo2 = mb_convert_case($modelo, MB_CASE_UPPER, 'UTF-8');
                                                                                                                            $valorConIva2 = mb_convert_case($valorConIva2, MB_CASE_UPPER, 'UTF-8');
                                                                                                                            $contarModelosDistintos2 = mb_convert_case($contarModelosDistintos2, MB_CASE_UPPER, 'UTF-8');
                                                                                                                            $totalFormat2 = mb_convert_case($totalFormat, MB_CASE_UPPER, 'UTF-8');

                                                                                                                            ?>
                                                                                                                            <tr>
                                                                                                                                <td class="text-center"><?php echo $modelo2 ?></td>
                                                                                                                                <td class="text-center"><?php echo $marca2 ?></td>
                                                                                                                                <td class="text-center"><?php echo $contarModelosDistintos2 ?></td>
                                                                                                                                <td class="text-center"><?php echo $valorConIva2 ?></td>
                                                                                                                                <td class="text-center"><?php echo $totalFormat2 ?></td>
                                                                                                                            </tr>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        </tbody>
                                                                                                                        <tfoot class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Modelo Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Marca Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Cantidad</th>
                                                                                                                            <th scope="col" class="text-center">Precio Unitario (Con IVA)</th>
                                                                                                                            <th scope="col" class="text-center">Precio Total (Con IVA)</th>

                                                                                                                        </tr>
                                                                                                                        </tfoot>
                                                                                                                    </table>

                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <button class="btn bg-purple btn-block btn-md " data-toggle="modal" data-target="#modalInfoEquipos<?php echo $idCategoria?>">
                                                                                                        <i class="fas fa-money-bill-wave"></i>&nbsp;&nbsp;Valor Sistema:
                                                                                                        <?php

                                                                                                        if ($sumaTotalSistema > 0) {
                                                                                                            $sumaTotalSistema2 = moneda_chilena($sumaTotalSistema);
                                                                                                        } else {
                                                                                                            $sumaTotalSistema2 = '$ 0';
                                                                                                        }

                                                                                                        echo '<b>' . $sumaTotalSistema2 . '</b>';

                                                                                                        ?>
                                                                                                    </button>
                                                                                                    <button class="btn btn-primary btn-block btn-md ">
                                                                                                        <i class="fas fa-desktop"></i>&nbsp;&nbsp;Equipos Registrados:
                                                                                                        <?php

                                                                                                        echo '<b>' .contarequiposcategoria($idCategoria). '</b>';

                                                                                                        ?>
                                                                                                    </button>
                                                                                                    <button class="btn btn-success btn-block btn-md ">
                                                                                                        <i class="fas fa-check"></i>&nbsp;&nbsp;Equipos Operativos:
                                                                                                        <?php

                                                                                                        echo '<b>' . contareuiposoperativos($idCategoria) . '</b>';

                                                                                                        ?>
                                                                                                    </button>
                                                                                                    <button class="btn btn-secondary btn-block btn-md " data-toggle="modal" data-target="#exampleModal<?php echo $idCategoria?>">
                                                                                                        <i class="fas fa-cog"></i>&nbsp;&nbsp;Equipos Con Observaciones:
                                                                                                        <?php

                                                                                                        echo '<b>' . contarequiposobs($idCategoria) . '</b>';

                                                                                                        ?>
                                                                                                    </button>

                                                                                                    <button class="btn btn-warning btn-block btn-md " data-toggle="modal" data-target="#equiposMantenimiento<?php echo $idCategoria?>">
                                                                                                        <i class="fas fa-check"></i>&nbsp;&nbsp;Equipos en Mantenimiento:
                                                                                                        <?php

                                                                                                        echo '<b>' . contarequiposmanteni($idCategoria). '</b>';

                                                                                                        ?>
                                                                                                    </button>
                                                                                                    <button class="btn btn-danger btn-block btn-md " data-toggle="modal" data-target="#modalFalla<?php echo $idCategoria;?>">
                                                                                                        <i class="fas fa-skull-crossbones"></i>&nbsp;&nbsp;Equipos Fuera de Servicio:
                                                                                                        <?php

                                                                                                        echo '<b>' . contarequiposfallas($idCategoria). '</b>';

                                                                                                        ?>
                                                                                                    </button>
                                                                                                    <div class="modal fade" id="exampleModal<?php echo $idCategoria?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                                        <div class="modal-dialog modal-xl">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header bg-secondary">
                                                                                                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando los equipos con observaciones </b></h5>
                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">

                                                                                                                    <table class="table table-bordered table-sm" id="tabla-equipo-falla">

                                                                                                                        <thead class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                                                                            <th scope="col" class="text-center">Detalle</th>
                                                                                                                            <th scope="col" class="text-center">Marca</th>
                                                                                                                            <th scope="col" class="text-center">Editar</th>

                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <?php

                                                                                                                        $rows = array();

                                                                                                                        $sqlBuscaEquipoOBS = "SELECT id FROM equipamiento_hbqp WHERE estadoEquipo LIKE 'OperativoOBS' AND idCategoria = " . $idCategoria . " ORDER BY nombreEquipo ASC";
                                                                                                                        $resBuscaEquipoOBS = mysqli_query($conexion, $sqlBuscaEquipoOBS);

                                                                                                                        if ($resBuscaEquipoOBS && mysqli_num_rows($resBuscaEquipoOBS) > 0) {

                                                                                                                            while ($rowBuscaEquipoOBS = $resBuscaEquipoOBS->fetch_assoc()) {

                                                                                                                                $idBuscaEquipoOBS = $rowBuscaEquipoOBS['id'];

                                                                                                                                if($idBuscaEquipoOBS > 0){
                                                                                                                                    $sqlConObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = ".$idBuscaEquipoOBS." AND estado LIKE '%OperativoOBS%' ORDER BY fecha DESC LIMIT 1";
                                                                                                                                    $resConObservaciones = mysqli_query($conexion, $sqlConObservaciones);
                                                                                                                                    while ($rowConObservaciones = $resConObservaciones->fetch_assoc()) {

                                                                                                                                        $rows[] = $rowConObservaciones;

                                                                                                                                    }
                                                                                                                                }

                                                                                                                            }

                                                                                                                            usort($rows, function ($a, $b) {
                                                                                                                                return strtotime($b['fecha']) - strtotime($a['fecha']);
                                                                                                                            });

                                                                                                                            foreach ($rows as $rowConObservaciones){
                                                                                                                                $idCambioOBS = $rowConObservaciones['idCambio'];
                                                                                                                                $fechaOBS = $rowConObservaciones['fecha'];
                                                                                                                                $estadoOBS = $rowConObservaciones['estado'];
                                                                                                                                $observacionesOBS = $rowConObservaciones['observaciones'];
                                                                                                                                $idEquipoOBS = $rowConObservaciones['idEquipo'];
                                                                                                                                $nombreEquipoOBS = $rowConObservaciones['nombreEquipo'];
                                                                                                                                $tipoUsuarioOBS = $rowConObservaciones['tipoUsuario'];
                                                                                                                                $idUsuarioCambiaOBS = $rowConObservaciones['idUsuarioCambia'];

                                                                                                                                $fechaOBS = strtotime($fechaOBS);
                                                                                                                                $fechaFormateadaOBS = date('d/m/Y', $fechaOBS);

                                                                                                                                if ($estadoOBS == 'OperativoObs') {
                                                                                                                                    $estadoOBS = 'Operativo,Observaciones';
                                                                                                                                }
                                                                                                                                ?>
                                                                                                                                <tr>
                                                                                                                                    <?php
                                                                                                                                    if ($tipoUsuarioOBS == 'administrativo') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambiaOBS";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomAdmTecnicos'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }
                                                                                                                                        }

                                                                                                                                    } else if ($tipoUsuarioOBS == 'jefe tecnico') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambiaOBS";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomJefe'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }
                                                                                                                                        }

                                                                                                                                    } else if ($tipoUsuarioOBS == 'personal') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambiaOBS";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomPersonal'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    } else if ($tipoUsuarioOBS == 'administrador') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambiaOBS";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomAdministrador'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }

                                                                                                                                        }

                                                                                                                                    } else if ($tipoUsuarioOBS == 'mesa ayuda') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambiaOBS";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomMesaAyuda'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }

                                                                                                                                        }
                                                                                                                                    } else if ($tipoUsuarioOBS == 'directiva') {
                                                                                                                                        $sqlTipoUsuarioOBS = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambiaOBS ";
                                                                                                                                        $resTipoUsuarioOBS = mysqli_query($conexion, $sqlTipoUsuarioOBS);
                                                                                                                                        while ($rowTipoUsuarioOBS = $resTipoUsuarioOBS->fetch_assoc()) {
                                                                                                                                            $nomUsuarioOBS = $rowTipoUsuarioOBS['nomDirectivo'];
                                                                                                                                            if(empty($nomUsuarioOBS)){
                                                                                                                                                $nomUsuarioOBS = 'NO APLICA.';
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }

                                                                                                                                    $nomUsuarioOBS2 = mb_convert_case($nomUsuarioOBS,MB_CASE_UPPER,'UTF-8');
                                                                                                                                    ?>
                                                                                                                                    <td class="text-center" style="font-size: 14px">
                                                                                                                                        <?php
                                                                                                                                        echo $nomUsuarioOBS;
                                                                                                                                        ?>

                                                                                                                                    </td>

                                                                                                                                    <td class="text-center" style="font-size: 14px">
                                                                                                                                        <?php echo $nombreEquipoOBS ?>
                                                                                                                                    </td>
                                                                                                                                    <td class="text-center">
                                                                                                                                        <form method="POST" action="equipo.php">
                                                                                                                                            <input type="hidden" value="<?php echo $idEquipoOBS ?>" name="idEquipo">
                                                                                                                                            <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                                                                                                        </form>
                                                                                                                                    </td>
                                                                                                                                    <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateadaOBS ?></td>
                                                                                                                                    <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estadoOBS, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                                                                                    <td class="text-center" style="font-size: 14px"><?php echo $observacionesOBS ?></td>
                                                                                                                                    <td class="text-center" style="font-size: 14px">
                                                                                                                                        <form method="POST" action="quitar_observacion_de_lista.php">
                                                                                                                                            <input type="hidden" value="<?php echo $idCambioOBS ?>" name="idCambio" id="idCambio">
                                                                                                                                            <button type="submit" class="btn btn-info btn-xs">
                                                                                                                                                <i class="fas fa-check"></i><b></b></i>
                                                                                                                                            </button>
                                                                                                                                        </form>

                                                                                                                                    </td>
                                                                                                                                    <td class="text-center" style="font-size: 14px">
                                                                                                                                        <form method="POST" action="editar_observacion.php">
                                                                                                                                            <input type="hidden" value="<?php echo $idCambioOBS ?>" name="idCambio" id="idCambio">
                                                                                                                                            <button type="submit" class="btn btn-warning btn-xs">
                                                                                                                                                <i class="fas fa-pen"></i>
                                                                                                                                            </button>
                                                                                                                                        </form>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                                <?php
                                                                                                                            }

                                                                                                                        }

                                                                                                                        ?>
                                                                                                                        </tbody>
                                                                                                                        <tfoot class="bg-info">
                                                                                                                        <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                                                                        <th scope="col" class="text-center">Detalle</th>
                                                                                                                        <th scope="col" class="text-center">Marca</th>
                                                                                                                        <th scope="col" class="text-center">Editar</th>
                                                                                                                        </tfoot>
                                                                                                                    </table>

                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal fade" id="equiposMantenimiento<?php echo $idCategoria?>" tabindex="-1" aria-labelledby="equiposMantenimientoLabel" aria-hidden="true">
                                                                                                        <div class="modal-dialog modal-xl">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header bg-warning">
                                                                                                                    <h5 class="modal-title" id="equiposMantenimientoLabel"><b> Estas visualizando los equipos en Mantenimiento </b></h5>
                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">

                                                                                                                    <table class="table table-bordered table-sm" id="tabla-equipo-falla">

                                                                                                                        <thead class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                                                                            <th scope="col" class="text-center">Detalle</th>
                                                                                                                            <th scope="col" class="text-center">Marca</th>
                                                                                                                            <th scope="col" class="text-center">Editar</th>

                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <?php
                                                                                                                        $sqlObservaciones = "SELECT * FROM historial_cambios_equipos WHERE idCategoria = " . $idCategoria . " AND estado LIKE '%En Mantenimiento%' ORDER BY fecha DESC";
                                                                                                                        $resObservaciones = mysqli_query($conexion, $sqlObservaciones);
                                                                                                                        while ($rowObservaciones = $resObservaciones->fetch_assoc()) {
                                                                                                                            $datos = [];
                                                                                                                            $datos['idCambio'] = $rowObservaciones['idCambio'];
                                                                                                                            $idCambio = $datos['idCambio'];
                                                                                                                            $datos['fecha'] = $rowObservaciones['fecha'];
                                                                                                                            $fecha = $datos['fecha'];
                                                                                                                            $datos['estado'] = $rowObservaciones['estado'];
                                                                                                                            $estado = $datos['estado'];
                                                                                                                            $datos['observaciones'] = $rowObservaciones['observaciones'];
                                                                                                                            $observaciones = $datos['observaciones'];
                                                                                                                            $datos['idEquipo'] = $rowObservaciones['idEquipo'];
                                                                                                                            $idEquipo = $datos['idEquipo'];
                                                                                                                            $datos['nombreEquipo'] = $rowObservaciones['nombreEquipo'];
                                                                                                                            $nomEquipo = $datos['nombreEquipo'];
                                                                                                                            $datos['tipoUsuario'] = $rowObservaciones['tipoUsuario'];
                                                                                                                            $tipoUsuario = $datos['tipoUsuario'];
                                                                                                                            $datos['idUsuarioCambia'] = $rowObservaciones['idUsuarioCambia'];
                                                                                                                            $idUsuarioCambia = $datos['idUsuarioCambia'];
                                                                                                                            $fecha = strtotime($fecha);
                                                                                                                            $fechaFormateada = date('d/m/Y', $fecha);
                                                                                                                            ?>
                                                                                                                            <tr>
                                                                                                                                <?php
                                                                                                                                if ($tipoUsuario == 'administrativo') {
                                                                                                                                    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambia";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomAdmTecnicos'] = $row2['nomAdmTecnicos'];
                                                                                                                                        $nombre = $datos2['nomAdmTecnicos'];
                                                                                                                                    }

                                                                                                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                                                                                                    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambia";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomJefe'] = $row2['nomJefe'];
                                                                                                                                        $nombre = $datos2['nomJefe'];
                                                                                                                                    }

                                                                                                                                } else if ($tipoUsuario == 'personal') {
                                                                                                                                    $sql = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambia";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomPersonal'] = $row2['nomPersonal'];
                                                                                                                                        $nombre = $datos2['nomPersonal'];
                                                                                                                                    }
                                                                                                                                } else if ($tipoUsuario == 'administrador') {
                                                                                                                                    $sql = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambia";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomAdministrador'] = $row2['nomAdministrador'];
                                                                                                                                        $nombre = $datos2['nomAdministrador'];

                                                                                                                                    }

                                                                                                                                } else if ($tipoUsuario == 'mesa ayuda') {
                                                                                                                                    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambia";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomMesaAyuda'] = $row2['nomMesaAyuda'];
                                                                                                                                        $nombre = $datos2['nomMesaAyuda'];

                                                                                                                                    }
                                                                                                                                } else if ($tipoUsuario == 'directiva') {
                                                                                                                                    $sql = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambia ";
                                                                                                                                    $res = mysqli_query($conexion, $sql);
                                                                                                                                    while ($row2 = $res->fetch_assoc()) {
                                                                                                                                        $datos2 = [];
                                                                                                                                        $datos2['nomDirectivo'] = $row2['nomDirectivo'];
                                                                                                                                        $nombre = $datos2['nomDirectivo'];
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                ?>
                                                                                                                                <td class="text-center" style="font-size: 14px">
                                                                                                                                    <?php
                                                                                                                                    echo $nombre;
                                                                                                                                    ?>

                                                                                                                                </td>

                                                                                                                                <td class="text-center" style="font-size: 14px">
                                                                                                                                    <?php echo $nomEquipo ?>
                                                                                                                                </td>
                                                                                                                                <td class="text-center">
                                                                                                                                    <form method="POST" action="equipo.php">
                                                                                                                                        <input type="hidden" value="<?php echo $idEquipo ?>" name="idEquipo">
                                                                                                                                        <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                                                                                                    </form>
                                                                                                                                </td>
                                                                                                                                <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateada ?></td>
                                                                                                                                <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                                                                                <td class="text-center" style="font-size: 14px"><?php echo $observaciones ?></td>
                                                                                                                                <td class="text-center" style="font-size: 14px">
                                                                                                                                    <form method="POST" action="quitar_observacion_de_lista.php">
                                                                                                                                        <input type="hidden" value="<?php echo $idCambio ?>" name="idCambio" id="idCambio">
                                                                                                                                        <button type="submit" class="btn btn-info btn-xs">
                                                                                                                                            <i class="fas fa-check"></i><b></b></i>
                                                                                                                                        </button>
                                                                                                                                    </form>

                                                                                                                                </td>
                                                                                                                                <td class="text-center" style="font-size: 14px">
                                                                                                                                    <form method="POST" action="editar_observacion.php">
                                                                                                                                        <input type="hidden" value="<?php echo $idCambio ?>" name="idCambio" id="idCambio">
                                                                                                                                        <button type="submit" class="btn btn-warning btn-xs">
                                                                                                                                            <i class="fas fa-pen"></i>
                                                                                                                                        </button>
                                                                                                                                    </form>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        </tbody>
                                                                                                                        <tfoot class="bg-info">
                                                                                                                        <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                        <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                        <th scope="col" class="text-center">Equipo</th>
                                                                                                                        <th scope="col" class="text-center">Fecha</th>
                                                                                                                        <th scope="col" class="text-center">Tipo</th>
                                                                                                                        <th scope="col" class="text-center">Detalle</th>
                                                                                                                        <th scope="col" class="text-center">Marca</th>
                                                                                                                        <th scope="col" class="text-center">Editar</th>
                                                                                                                        </tfoot>
                                                                                                                    </table>

                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal fade" id="modalFalla<?php echo $idCategoria?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                                        <div class="modal-dialog modal-xl">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header bg-danger">
                                                                                                                    <h5 class="modal-title" id="exampleModalLabel"><b> Estas visualizando los equipos fuera de servicio </b></h5>
                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">

                                                                                                                    <table class="table table-bordered table-sm" id="tabla-equipo-observacion">
                                                                                                                        <thead class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                                                                            <th scope="col" class="text-center">Detalle</th>
                                                                                                                        </tr>
                                                                                                                        </thead>
                                                                                                                        <tbody>
                                                                                                                        <?php
                                                                                                                        $sqlBuscaEquipoFS = "SELECT id FROM equipamiento_hbqp WHERE estadoEquipo LIKE 'Fuera de Servicio' AND idCategoria = " . $idCategoria . " ORDER BY nombreEquipo ASC";

                                                                                                                        $resBuscaEquipoFS = mysqli_query($conexion, $sqlBuscaEquipoFS);

                                                                                                                        if ($resBuscaEquipoFS && mysqli_num_rows($resBuscaEquipoFS) > 0) {

                                                                                                                            while ($rowBuscaEquipoFS = $resBuscaEquipoFS->fetch_assoc()) {

                                                                                                                                $idBuscaEquipoFS = $rowBuscaEquipoFS['id'];

                                                                                                                                if($idBuscaEquipoFS > 0){
                                                                                                                                    $sqlFueraServicio = "SELECT * FROM historial_cambios_equipos WHERE idEquipo = ".$idBuscaEquipoFS." AND estado LIKE '%Fuera de Servicio%' ORDER BY fecha DESC LIMIT 1";
                                                                                                                                    $resFueraServicio = mysqli_query($conexion, $sqlFueraServicio);
                                                                                                                                    while ($rowFueraServicio = $resFueraServicio->fetch_assoc()) {

                                                                                                                                        $idCambioFS = $rowFueraServicio['idCambio'];
                                                                                                                                        $fechaFS = $rowFueraServicio['fecha'];
                                                                                                                                        $estadoFS = $rowFueraServicio['estado'];
                                                                                                                                        $observacionesFS = $rowFueraServicio['observaciones'];
                                                                                                                                        $idEquipoFS = $rowFueraServicio['idEquipo'];
                                                                                                                                        $nombreEquipoFS = $rowFueraServicio['nombreEquipo'];
                                                                                                                                        $tipoUsuarioFS = $rowFueraServicio['tipoUsuario'];
                                                                                                                                        $idUsuarioCambiaFS = $rowFueraServicio['idUsuarioCambia'];

                                                                                                                                        $fechaFS = strtotime($fechaFS);
                                                                                                                                        $fechaFormateadaFS = date('d/m/Y', $fechaFS);
                                                                                                                                        ?>
                                                                                                                                        <tr>
                                                                                                                                            <?php
                                                                                                                                            if ($tipoUsuarioFS == 'administrativo') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos = $idUsuarioCambiaFS";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomAdmTecnicos'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }
                                                                                                                                                }

                                                                                                                                            } else if ($tipoUsuarioFS == 'jefe tecnico') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM jefes_tecnicos WHERE idJefe = $idUsuarioCambiaFS";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomJefe'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }
                                                                                                                                                }

                                                                                                                                            } else if ($tipoUsuarioFS == 'personal') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM personal WHERE idPersonal = $idUsuarioCambiaFS";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomPersonal'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            } else if ($tipoUsuarioFS == 'administrador') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM administracion WHERE idAdministrador = $idUsuarioCambiaFS";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomAdministrador'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }

                                                                                                                                                }

                                                                                                                                            } else if ($tipoUsuarioFS == 'mesa ayuda') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda = $idUsuarioCambiaFS";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomMesaAyuda'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }

                                                                                                                                                }
                                                                                                                                            } else if ($tipoUsuarioFS == 'directiva') {
                                                                                                                                                $sqlTipoUsuarioFS = "SELECT * FROM directivos WHERE idDirectivo = $idUsuarioCambiaFS ";
                                                                                                                                                $resTipoUsuarioFS = mysqli_query($conexion, $sqlTipoUsuarioFS);
                                                                                                                                                while ($rowTipoUsuarioFS = $resTipoUsuarioFS->fetch_assoc()) {
                                                                                                                                                    $nomUsuarioFS = $rowTipoUsuarioFS['nomDirectivo'];
                                                                                                                                                    if(empty($nomUsuarioFS)){
                                                                                                                                                        $nomUsuarioFS = 'NO APLICA.';
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }

                                                                                                                                            $nomUsuarioFS2 = mb_convert_case($nomUsuarioFS,MB_CASE_UPPER,'UTF-8');
                                                                                                                                            ?>
                                                                                                                                            <td class="text-center" style="font-size: 14px">
                                                                                                                                                <?php
                                                                                                                                                echo $nomUsuarioFS2;
                                                                                                                                                ?>

                                                                                                                                            </td>

                                                                                                                                            <td class="text-center">
                                                                                                                                                <?php echo $nombreEquipoFS ?>
                                                                                                                                            </td>
                                                                                                                                            <td class="text-center">
                                                                                                                                                <form method="POST" action="equipo.php">
                                                                                                                                                    <input type="hidden" value="<?php echo $idEquipoFS ?>" name="idEquipo">
                                                                                                                                                    <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i></button>
                                                                                                                                                </form>
                                                                                                                                            </td>
                                                                                                                                            <td class="text-center" style="font-size: 14px"><?php echo $fechaFormateadaFS ?></td>
                                                                                                                                            <td class="text-center" style="font-size: 14px"><?php echo mb_convert_case($estadoFS, MB_CASE_UPPER, 'UTF-8') ?></td>
                                                                                                                                            <td class="text-center" style="font-size: 14px"><?php echo $observacionesFS ?></td>
                                                                                                                                        </tr>
                                                                                                                                        <?php
                                                                                                                                    }

                                                                                                                                }

                                                                                                                            }

                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        </tbody>
                                                                                                                        <tfoot class="bg-info">
                                                                                                                        <tr>
                                                                                                                            <th scope="col" class="text-center">Usuario Observador</th>
                                                                                                                            <th scope="col" class="text-center">Nombre del Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Equipo</th>
                                                                                                                            <th scope="col" class="text-center">Fecha</th>
                                                                                                                            <th scope="col" class="text-center">Tipo</th>
                                                                                                                            <th scope="col" class="text-center">Detalle</th>
                                                                                                                        </tr>
                                                                                                                        </tfoot>
                                                                                                                    </table>

                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Modal</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <br>
                                                                                                    <!--<div>
                                                                                                        <form action="ver-observaciones.php" method="post">
                                                                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button class="btn btn-warning  btn-block btn-sm">
                                                                                                                <i class="fas fa-exclamation-triangle"></i> Observaciones:
                                                                                                                <?php echo equiposConObservacion($idCategoria); ?>
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                    <br>
                                                                                                    <div>
                                                                                                        <form action="ver-fallas.php" method="post">
                                                                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button class="btn btn-danger  btn-block btn-sm ">
                                                                                                                <i class="fas fa-skull-crossbones"></i> Fallas:
                                                                                                                <?php echo EquiposFueraDeServicio($idCategoria); ?>
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                    -->
                                                                                                    <!--
                                                                                                    <div>
                                                                                                        <form action="ver-listado-inventario.php" method="POST">
                                                                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button class="btn btn-outline-primary  btn-block btn-sm ">
                                                                                                                <i class="fas fa-boxes"></i> Inventario del Equipo
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                    -->

                                                                                                </div>
                                                                                                <br>
                                                                                            </div>


                                                                                            <!-- /.card-body -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }

                                                                            }

                                                                        }

                                                                        ?>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };
</script>

<?php
// Aquí tu PHP con los toastr según corresponda

if (isset($_GET['exitoCrearCategoria'])) {
    echo "<script>toastr.success('Categoria Creada con Exito.');</script>";
}
if (isset($_GET['errorCrearCategoria'])) {
    echo "<script>toastr.error('Error al Crear la Categoria.');</script>";
}

if (isset($_GET['exitoModificarInventario'])) {
    echo "<script>toastr.success('Cantidad de Equipo Modificada con Exito.');</script>";
}
if (isset($_GET['errorModificarInventario'])) {
    echo "<script>toastr.error('Error al Modificar Cantidad de Equipo.');</script>";
}
?>

<!-- Page specific script -->

<script>
    // Obtener referencia al campo de entrada de archivo
    const inputImagen = document.getElementById('imagen');
    // Obtener referencia al div de vista previa
    const vistaPreviaDiv = document.getElementById('vista-previa');

    // Escuchar el evento change del campo de entrada de archivo
    inputImagen.addEventListener('change', function (e) {
        // Obtener la imagen seleccionada
        const imagenSeleccionada = e.target.files[0];

        // Crear un objeto URL para la imagen seleccionada
        const imagenURL = URL.createObjectURL(imagenSeleccionada);

        // Crear un elemento de imagen para mostrar la vista previa
        const imagenPrevia = document.createElement('img');
        imagenPrevia.src = imagenURL;
        imagenPrevia.classList.add('img-fluid');

        // Limpiar el div de vista previa antes de añadir la nueva imagen
        vistaPreviaDiv.innerHTML = '';

        // Agregar la imagen de vista previa al div
        vistaPreviaDiv.appendChild(imagenPrevia);
    });
</script>
<script>
    $('[data-id]').click(function () {
        var dataId = $(this).attr('data-id');
        $('#modalEditarCategoria').data('id', dataId).modal('show');
        var elementos = {};
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtiene_datos_categoria.php',
            data: {
                'id': dataId
            },
            success: function (response) {
                // console.log(response);
                var datos = JSON.parse(response);

                elementos.nomCategoria = datos.nomCategoria;
                elementos.idDepartamento = datos.idDepartamento;
                elementos.imagenCategoria = datos.imagenCategoria;

                $('#idCategoria').val(dataId);
                $('#nombreCategoriaEdita').val(elementos.nomCategoria);
                $('#departamentoCategoriaEdita').val(elementos.idDepartamento);
                $('#img').attr('src', elementos.imagenCategoria);

            }
        })
    })
</script>

<script>
    $(document).ready(function () {
        $('#tabla-equipo-observacion').DataTable({
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
    function obtenerid(idCategoria) {
        console.log(idCategoria);

</script>

<script src="js/fullScreen.js"></script>
</body>

</html>