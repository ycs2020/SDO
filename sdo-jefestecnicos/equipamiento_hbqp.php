
<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];
$nomDepartamento = getNomDepartamento($idDepartamento);
$idSubDepartamento = $_SESSION['idSubDepartamento'];

date_default_timezone_set('America/Santiago');

if ($idDepartamento == 1) {
    $showInstrumentacion = 'show active';
} else {
    $showInstrumentacion = '';
}
if ($idDepartamento == 2) {
    $showIndustrial = 'show active';
} else {
    $showIndustrial = '';
}
if ($idDepartamento == 3) {
    $showClima = 'show active';
} else {
    $showClima = '';
}
if ($idDepartamento == 4) {
    $showElectricidad = 'show active';
} else {
    $showElectricidad = '';
}
if ($idDepartamento == 5) {
    $showInfra = 'show active';
} else {
    $showInfra = '';
}
if ($idDepartamento == 6) {
    $showMedico = 'show active';
} else {
    $showMedico = '';
}
if ($idDepartamento == 7) {
    $showLogistica = 'show active';
} else {
    $showLogistica = '';
}
if ($idDepartamento == 8) {

    $showMovilizacion = 'show active';
} else {
    $showMovilizacion = '';
}
if ($idDepartamento == 10) {

    $showClinico = 'show active';
} else {
    $showClinico = '';
}

if ($idDepartamento == 12) {

    $showIncendio = 'show active';
    // $showIndustrial = 'show active';
} else {
    $showIncendio = '';
    // $showIndustrial = '';
}

function totalEquipos($idCategoria)
{
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function equiposOperativos($idCategoria)
{
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo = 'Operativo'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function equiposConObservacion($idCategoria)
{
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM historial_cambios_equipos WHERE idCategoria = $idCategoria ";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
}

function equiposFueraDeServicio($idCategoria)
{
    include '../sdo-funciones/conexion.php';

    $sql = "SELECT COUNT(*) AS totalEquipos FROM  equipamiento_hbqp WHERE idCategoria = $idCategoria AND estadoEquipo = 'Fuera de Servicio'";
    $res = mysqli_query($conexion, $sql);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['totalEquipos'] = $row['totalEquipos'];
        $totalEquipos = $datos['totalEquipos'];
    }
    return $totalEquipos;
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
    include '../sdo-templates/jefes-sidebar.php';
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
                                <div class="card-body" style="text-align:center;">
                                </div>
                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">

                                            <?php

                                            $nomDeptoSentencia = $nomDepartamento;

                                            ?>

                                            <div class="card-header d-flex p-0 text-center bg-success text-white">
                                                <ul class="nav nav-pills mr-auto ml-auto p-2 text-center text-white">
                                                    <?php

                                                    if($idDepartamento == 2 || $idDepartamento == 12){
                                                        $sqlSelect = "SELECT * FROM departamentos WHERE idDepartamento = 12 OR idDepartamento = 2 ORDER BY idDepartamento ASC";

                                                    }else{
                                                        $sqlSelect = "SELECT * FROM departamentos WHERE idDepartamento = ".$idDepartamento." ORDER BY idDepartamento ASC";

                                                    }

                                                    $resSelect = mysqli_query($conexion, $sqlSelect);

                                                    $numNav = 1;
                                                    if ($resSelect && mysqli_num_rows($resSelect) > 0) {

                                                        while ($rowSelect = $resSelect->fetch_assoc()) {

                                                            $idDepartamento = $rowSelect['idDepartamento'];
                                                            $nomDepartamento = $rowSelect['nomDepartamento'];

                                                            if ($nomDepartamento == $nomDeptoSentencia) {
                                                                $active = 'active';
                                                                $selected = 'true';
                                                            } else {
                                                                $selected = 'false';
                                                                $active = '';
                                                            }

                                                            ?>
                                                            <li class="nav-item text-white"><a class="nav-link <?php echo $active ?> text-white" href="#vistaDepto<?php echo $idDepartamento ?>" data-toggle="tab"><?php echo $nomDepartamento ?></a></li>

                                                            <?php

                                                            $numNav++;
                                                        }
                                                    }

                                                    ?>
                                                </ul>
                                            </div>

                                            <div class="card-body text-center">


                                                <?php
                                                if (isset($_GET['exitoCrearCategoria'])) {
                                                    ?>
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se creo la nueva categoria correctamente.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }
                                                if (isset($_GET['errorCrearCategoria'])) {
                                                    ?>
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al crear la nueva categoría.&nbsp;&nbsp;&nbsp;&nbsp;<a href="soporte-sdo.php" style="text-decoration: none;" class="btn btn-warning"> <b>Reportar el error</b> </a>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }

                                                if (isset($_GET['exitoActualizarCategoria'])) {
                                                    ?>
                                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Exito al modificar la categoria seleccionada.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }
                                                if (isset($_GET['errorActualizarCategoria'])) {
                                                    ?>
                                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                        <i class="fas fa-times"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al modificar la categoría seleccionada .&nbsp;&nbsp;&nbsp;&nbsp;<a href="soporte-sdo.php" style="text-decoration: none;" class="btn btn-danger"> <b>Reportar el error</b> </a>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }
                                                if (isset($_GET['exitoIngresoInventario'])) {
                                                    ?>
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se ingreso el equipo al inventario correctamente.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php

                                                }
                                                if (isset($_GET['errorIngresoInventario'])) {
                                                    ?>
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al ingresar un equipo a inventario.Reintente
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php

                                                }
                                                if (isset($_GET['exitoModificarInventario'])) {
                                                    ?>
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Se modifico la cantidad del equipo correctamente.
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php
                                                }

                                                if (isset($_GET['errorModificarInventario'])) {
                                                    ?>
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Error al modificar la cantidad del equipo.Reintente
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <?php

                                                }
                                                ?>

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

                                                                            if($idDepartamento == 2 || $idDepartamento == 12){
                                                                                $seleccionaDepartamentos = "SELECT * FROM departamentos WHERE idDepartamento = 12 OR idDepartamento = 2 ORDER BY idDepartamento ASC";

                                                                            }else{
                                                                                $seleccionaDepartamentos = "SELECT * FROM departamentos WHERE idDepartamento = ".$idDepartamento." ORDER BY idDepartamento ASC";

                                                                            }


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

                                                                            if($idDepartamento == 2 || $idDepartamento == 12){
                                                                                $seleccionaDepartamentos1 = "SELECT * FROM departamentos WHERE idDepartamento = 12 OR idDepartamento = 2 ORDER BY idDepartamento ASC";

                                                                            }else{
                                                                                $seleccionaDepartamentos1 = "SELECT * FROM departamentos WHERE idDepartamento = ".$idDepartamento." ORDER BY idDepartamento ASC";

                                                                            }
                                                                            $respuesta = mysqli_query($conexion, $seleccionaDepartamentos1);

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

                                                    if($idDepartamento == 2 || $idDepartamento == 12){
                                                        $sqlSelect1 = "SELECT * FROM departamentos WHERE idDepartamento = 12 OR idDepartamento = 2 ORDER BY idDepartamento ASC";

                                                    }else{
                                                        $sqlSelect1 = "SELECT * FROM departamentos WHERE idDepartamento = ".$idDepartamento." ORDER BY idDepartamento ASC";

                                                    }

                                                    $resSelect1 = mysqli_query($conexion, $sqlSelect1);

                                                    $numNav1 = 1;

                                                    if ($resSelect1 && mysqli_num_rows($resSelect1) > 0) {

                                                        while ($rowSelect1 = $resSelect1->fetch_assoc()) {

                                                            $idDepartamento1 = $rowSelect1['idDepartamento'];
                                                            $nomDepartamento1 = $rowSelect1['nomDepartamento'];

                                                            if ($nomDepartamento1 == $nomDeptoSentencia) {
                                                                $active1 = 'active';
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
                                                                    <br>

                                                                    <div class="row">

                                                                        <?php

                                                                        $sqlCategorias = "SELECT * FROM categorias_equipos WHERE idDepartamento = " . $idDepartamento1."  ORDER BY nomCategoria ASC";

                                                                        $resCategorias = mysqli_query($conexion, $sqlCategorias);

                                                                        $numCategorias = 1;

                                                                        if ($resCategorias && mysqli_affected_rows($conexion) > 0) {
                                                                            while ($rowCategorias = $resCategorias->fetch_assoc()) {

                                                                                $idCategoria = trim($rowCategorias['idCategoria']);
                                                                                $nomCategoria = trim($rowCategorias['nomCategoria']);
                                                                                $rutaImagen = $rowCategorias['imagenCategoria'];
                                                                                $nomCategoria = mb_strtolower($nomCategoria);

                                                                                $nomCategoria = mb_convert_case($nomCategoria,MB_CASE_UPPER,'UTF-8');

                                                                                if ($nomCategoria == 'CONTROL CENTRALIZADO' || $nomCategoria == 'bitacora' || $nomCategoria == 'bitácora' || $nomCategoria == 'Bitácora' || $nomCategoria == 'BITACORA' || $nomCategoria == 'BITÁCORA') {
                                                                                } else {
                                                                                    ?>

                                                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                                                                        <div class="card card-primary card-outline">
                                                                                            <div class="card-body box-profile w">
                                                                                                <div class="text-center">
                                                                                                    <?php

                                                                                                    if (!empty($rutaImagen)) {
                                                                                                        ?>
                                                                                                        <img src="<?php echo $rutaImagen; ?>" width="150px" height="150px" alt="<?php echo $nomCategoria; ?>">
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        <img class=" img-fluid" src="img/controlCent-HBQP.png" width="150px" height="150px" alt="Control Centralizado">
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
                                                                                                <?php
                                                                                                    if($idDepartamento == 6){
                                                                                                        ?>
                                                                                                        <form action="carpeta-equipo-inge-clinica.php" method="POST">
                                                                                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button type="submit" class="btn btn-block bg-info "><b>Administrar</b></button>
                                                                                                        </form>
                                                                                                        <?php
                                                                                                    }else{
                                                                                                        ?>
                                                                                                        <form action="carpeta-equipo.php" method="POST">
                                                                                                            <input type="hidden" name="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button type="submit" class="btn btn-block bg-info "><b>Administrar</b></button>
                                                                                                        </form>
                                                                                                        <?php
                                                                                                    }
                                                                                                ?>

                                                                                                <hr>
                                                                                                <div class=" text-center">
                                                                                                    <p class="text-bold">Total de Equipos: <?php echo totalEquipos($idCategoria); ?> </p>
                                                                                                    <div>
                                                                                                        <button class="btn btn-success  btn-block btn-sm ">
                                                                                                            <i class="fas fa-check"></i> Operativos:
                                                                                                            <?php echo equiposOperativos($idCategoria); ?>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <br>
                                                                                                    <div>
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
                                                                                                    <br>
                                                                                                    <div>
                                                                                                        <form action="ver-listado-inventario.php" method="POST">
                                                                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCategoria ?>">
                                                                                                            <button class="btn btn-outline-primary  btn-block btn-sm ">
                                                                                                                <i class="fas fa-boxes"></i> Inventario del Equipo
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
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

                                                            $numNav1++;
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
<script src="js/fullScreen.js"></script>
</body>

</html>