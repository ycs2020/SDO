<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$bitacora = obtenerIDBitacora();
$idCategoria = null;
$nomCategoria = '';

// Función auxiliar DRY
function obtenerCategoriaValida($idCategoria) {
    if (!empty($idCategoria) && $idCategoria > 0) {
        $nombre = getNomCategoria($idCategoria);
        return mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
    }
    return '';
}

// 1. Si hay bitácora activa
if (!empty($bitacora) && $bitacora > 0) {
    $idCategoria = $bitacora;
} else {
    // 2. POST > GET > SESSION
    if (isset($_POST['idCategoria'])) {
        $idCategoria = trim($_POST['idCategoria']);
    } else if (isset($_GET['idCategoria'])) {
        $idCategoria = trim($_GET['idCategoria']);
    } else if (isset($_SESSION['idCategoria'])) {
        $idCategoria = trim($_SESSION['idCategoria']);
    } else {
        header('Location: index.php?errorCategoria=true');
        exit;
    }
}

// Validar y obtener nombre
$nomCategoria = obtenerCategoriaValida($idCategoria);

// Si no es válido, reiniciar sesión o redirigir
if (empty($nomCategoria)) {
    header('Location: index.php?errorCategoria=true');
    exit;
}

$_SESSION['idCategoria'] = $idCategoria;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $nomCategoria; ?></title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            recargarDataEquipos();

            $('#selectSubCategoria').change(function () {
                recargarDataEquipos();
            });

            $('#customRadio1').change(function () {
                enabledOptionFechas();
            });

            $('#customRadio2').change(function () {
                disableOptionFechas();
            });

            function enabledOptionFechas() {
                var fechaInicio = document.getElementById('fechaInicio');
                var fechaTermino = document.getElementById('fechaTermino');

                fechaInicio.disabled = false;
                fechaTermino.disabled = false;
            };

            function disableOptionFechas() {
                var fechaInicio = document.getElementById('fechaInicio');
                var fechaTermino = document.getElementById('fechaTermino');

                fechaInicio.disabled = true;
                fechaTermino.disabled = true;
            };
        });
    </script>
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4><?php echo $nomCategoria; ?></h4>
                    </div>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                                <?php

                                //AVISOS AGREGAR CATEGORIA
                                if (isset($_GET['exitoAgregarCategoria'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Categoria Creada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarCategoria'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Categoria. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeCategoria'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;La Categoria ya se encuentra registrada. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR CATEGORIA

                                //AVISOS AGREGAR EQUIPO
                                if (isset($_GET['exitoAgregarEquipo'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Equipo Creado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarEquipo'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar Equipo. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeEquipo'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;El Equipo ya se encuentra registrado. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR EQUIPO

                                //AVISOS MODIFICAR OBSERVACIÓN
                                if (isset($_GET['exitoModificarObservacion'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observacion Modificada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorModificarObservacion'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar Observacion. Reintente.</h5></div>';
                                }
                                //AVISOS MODIFICAR OBSERVACIÓN

                                //AVISOS ELIMINAR OBSERVACIÓN
                                if (isset($_GET['exitoEliminarObservacion'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Observacion Eliminada con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorEliminarObservacion'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Eliminar Observacion. Reintente.</h5></div>';
                                }
                                //AVISOS ELIMINAR OBSERVACIÓN

                                //AVISOS DESCARGA BITACORA

                                if (isset($_GET['errorDescargarBitacora'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Descargar Bitacora. Reintente.</h5></div>';
                                }

                                if (isset($_GET['bitacoraSinEventos'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error: No Existen Eventos en el Periodo Consultado.</h5></div>';
                                }

                                if (isset($_GET['errorFechaTermino'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;La Fecha de Termino no debe ser antes que la Fecha de Inicio. Reintente.</h5></div>';
                                }
                                //AVISOS DESCARGA BITACORA
                                ?>
                                <br>

                                <?php
                                $contarCategoria = contarSubCategoria($idCategoria);

                                if ($contarCategoria > 0) {
                                    ?>
                                    <form method="post" action="../sdo-funciones/jefe-bitacora-agregar.php">
                                        <button type="button" style="float: right;" data-toggle="modal" data-target="#modalAddObservacion" id="inputMozi" class="btn btn-secondary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Observaci&oacute;n</button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalAddObservacion" name="modalAddObservacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Observaci&oacute;n</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Categoria: <code>*</code></label>
                                                            <select class="custom-select" name="categoriaBitacora" id="categoriaBitacora">
                                                                <?php

                                                                $querySubCategoria = "SELECT * FROM subcategorias WHERE idCategoria = '" . $idCategoria . "' ORDER BY nomSubcategoria ASC";

                                                                $resSubCategoria = mysqli_query($conexion, $querySubCategoria);

                                                                while ($rowSubCategoria = $resSubCategoria->fetch_assoc()) {

                                                                    ?>
                                                                    <option value="<?php echo $rowSubCategoria['idSubcategoria']; ?>">
                                                                        <?php echo $rowSubCategoria['nomSubcategoria']; ?>
                                                                    </option>
                                                                    <?php

                                                                }


                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div style="text-align:left;" class="form-group">
                                                            <label for="exampleInputBorder">Observaci&oacute;n: <code>*</code></label>
                                                            <textarea id="observacionBitacora" required style="resize:none;" name="observacionBitacora" class="form-control" rows="15" placeholder="Observaciones..."></textarea>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="tecnicoBitacora" id="tecnicoBitacora" value="<?php echo $idUsuario; ?>">
                                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>


                                <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <form method="post" action="../sdo-funciones/jefe-bitacora-categorias-agregar.php">
                                    <button type="button" style="float: right;" data-toggle="modal" data-target="#modalAddCategoria" id="inputMozi" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Categoria</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalAddCategoria" name="modalAddCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Categoria</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <label for="exampleInputBorder">Nombre Categoria: <code>*</code></label>
                                                        <input type="text" class="form-control form-control-border" id="addNomCategoria" name="addNomCategoria" placeholder="Nombre Categoria:" value="" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria; ?>">
                                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                <p style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                <form method="post" action="../sdo-funciones/bitacora-descargar.php">
                                    <button type="button" style="float: right;" data-toggle="modal" data-target="#modalDownloadBitacora" id="inputMozi" class="btn btn-info"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Bitacora</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalDownloadBitacora" name="modalDownloadBitacora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Descargar Bitacora</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div style="text-align:left;" class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="customRadio1" value="false" name="customRadio" checked="checked">
                                                            <label for="customRadio1" class="custom-control-label">Descargar Bitacora Seg&uacute;n Fechas</label>
                                                        </div>
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label>Fecha de Inicio:</label>
                                                        <input type="datetime-local" class="form-control" name="fechaInicio" id="fechaInicio">
                                                    </div>
                                                    <div style="text-align:left;" class="form-group">
                                                        <label>Fecha de Termino:</label>
                                                        <input type="datetime-local" class="form-control" name="fechaTermino" id="fechaTermino">

                                                    </div>
                                                    <br>
                                                    <div style="text-align:left;" class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="customRadio2" value="true" name="customRadio">
                                                            <label for="customRadio2" class="custom-control-label">Descargar Bitacora Completa</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="nomUsuario" id="nomUsuario" value="<?php echo $nomUsuario; ?>">
                                                    <input type="hidden" name="tipoUsuario" id="tipoUsuario" value="Jefe de Departamento: <?php echo $nomDeptoBitacora; ?>">
                                                    <button type="submit" class="btn btn-primary">Descargar</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th>
                                                <center>Fecha</center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Visualizar</center>
                                            </th>
                                            <th>
                                                <center>Modificar</center>
                                            </th>
                                            <th>
                                                <center>Eliminar</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="displayTableExample" name="displayTableExample">
                                        <?php

                                        $query = "SELECT * FROM bitacora ORDER BY fechaBitacora DESC";

                                        $res = mysqli_query($conexion, $query);


                                        $numReporte = 1;

                                        while ($row = $res->fetch_assoc()) {
                                            ?>

                                            <tr>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if ($row['fechaBitacora'] == 0) {

                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaBitacora'])));
                                                        $inicioHora = date('H:i', strtotime(str_replace('/', '-', $row['fechaBitacora'])));

                                                        $fechaBitacora2 = strtotime($row['fechaBitacora']);
                                                        echo $inicioFecha . ' a las ' . $inicioHora . ' hrs.';
                                                    }
                                                    ?>
                                                </td>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if (empty(getNomSubCategoria($row["idSubcategoria"]))) {

                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        echo getNomSubCategoria($row["idSubcategoria"]);
                                                    }
                                                    ?>
                                                </td>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if (empty($row["tecnicoBitacora"])) {

                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        $nomTecnico = mb_convert_case($row["tecnicoBitacora"], MB_CASE_TITLE, 'UTF-8');
                                                        echo trim($nomTecnico);
                                                    }
                                                    ?>
                                                </td>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if (empty($row['idBitacora'])) {

                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-primary" onclick="mostrarModal(<?php echo $row['idBitacora']; ?>)"><i class="fas fa-eye"></i>&nbsp;&nbsp;Visualizar</button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if (empty($row['idBitacora'])) {

                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-warning" onclick="modificarModal(<?php echo $row['idBitacora']; ?>)"><i class="fas fa-pen"></i>&nbsp;&nbsp;Modificar</button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td nowrap="nowrap" style="vertical-align:middle; width:10%; text-align: center;">
                                                    <?php
                                                    if (empty($row['idBitacora'])) {
                                                        echo 'Error al Obtener Datos.';
                                                    } else {
                                                        ?>
                                                        <button class="btn btn-danger" onclick="eliminarBitacora(<?php echo $row['idBitacora']; ?>)"><i class="fas fa-trash"></i>&nbsp;&nbsp;Eliminar</button>
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
                                            <th>
                                                <center>Fecha
                                                    <center>
                                            </th>
                                            <th>
                                                <center>Categoria</center>
                                            </th>
                                            <th>
                                                <center>Tecnico</center>
                                            </th>
                                            <th>
                                                <center>Visualizar</center>
                                            </th>
                                            <th>
                                                <center>Modificar</center>
                                            </th>
                                            <th>
                                                <center>Eliminar</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="modal" id="observacionModal">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Observación</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="modal-body" style=" white-space: pre-wrap; text-align: justify;" id="observacionContenido">
                                                    <!-- Aquí se mostrará la observación -->
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal" id="modModal">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <form method="post" action="../sdo-funciones/jefe-modificarBitacora.php" name="formModificar">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="modal-title">Modificar Observación</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body" style="" id="observacionMod">
                                                        <div class="form-row">

                                                            <div class="form-group col-12">
                                                                <label for="nomTecnico" style="font-size: 12px;">Nombre de Tecnico: <code>*</code></label>
                                                                <input type="text" class="form-control"  readonly="readonly" name="nomTecnico" id="nomTecnico" value="" placeholder="Nombre de Tecnico">

                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label for="idSubCateg" style="font-size: 12px;">Categoria: <code>*</code></label>
                                                                <select class="form-control" required name="idSubCateg" id="idSubCateg">
                                                                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label for="exampleInputDetalleTrabajo" style="font-size: 12px;">Trabajo Realizado:<code>*</code></label>
                                                                <textarea id="obserMod" style="resize:none; text-align: justify;" required name="obserMod" class="form-control" rows="4" placeholder="Observacion"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idBitacoraMod" id="idBitacoraMod">
                                                        <button type="submit" class="btn btn-primary">Modificar</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>


                                    <script>
                                        $(document).ready(function () {
                                            // Inicializar DataTable
                                            $('#example').DataTable();
                                        });

                                        function mostrarModal(id) {
                                            // Realizar una solicitud AJAX al script PHP para obtener la observación
                                            $.ajax({
                                                type: 'POST',
                                                url: 'sdo-datos-bitacora/obtener_observacion.php',  // Ajusta la ruta al script PHP
                                                data: {id: id},
                                                success: function (data) {
                                                    if (data === null || data === 'null') {
                                                        // Mostrar una alerta si la observación es null
                                                        alert('No se encontró observación para el ID: ' + id);
                                                    } else {
                                                        // Mostrar la observación en el modal
                                                        $('#observacionContenido').html(data);

                                                        // Abrir el modal
                                                        $('#observacionModal').modal('show');
                                                    }
                                                },
                                                error: function () {
                                                    alert('Error al obtener la observación.');
                                                }
                                            });
                                        }

                                        function modificarModal(id){
                                            // Realizar una solicitud AJAX al script PHP para obtener la observación
                                            $.ajax({
                                                type: 'POST',
                                                url: 'sdo-datos-bitacora/modificar_observacion.php',  // Ajusta la ruta al script PHP
                                                data: {id: id},
                                                success: function (data) {
                                                    if (data === null || data === 'null') {
                                                        // Mostrar una alerta si la observación es null
                                                        alert('No se encontró observación para el ID: ' + id);
                                                    } else {
                                                        // Parsea los datos JSON
                                                        var jsonData = JSON.parse(data);
                                                        $('#nomTecnico').val(jsonData.nomTecnico);
                                                        $('#obserMod').val(jsonData.obserMod);
                                                        $('#idBitacoraMod').val(jsonData.idBitacoraMod);

                                                        $('#idSubCateg').empty();
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'sdo-datos-bitacora/opciones_bitacora.php',  // Ajusta la ruta al script PHP que obtiene las opciones
                                                            success: function (optionsData) {
                                                                // Parsea los datos JSON de las opciones
                                                                var options = JSON.parse(optionsData);

                                                                // Rellena el select con las opciones y selecciona la opción correspondiente
                                                                for (var i = 0; i < options.length; i++) {
                                                                    var option = $('<option>');
                                                                    option.val(options[i].value);
                                                                    option.text(options[i].text);

                                                                    // Selecciona la opción correspondiente
                                                                    if (options[i].value === jsonData.idSubCateg) {
                                                                        option.prop('selected', true);
                                                                    }

                                                                    $('#idSubCateg').append(option);
                                                                }

                                                                // Abre el modal
                                                                $('#modModal').modal('show');
                                                            },
                                                            error: function () {
                                                                alert('Error al obtener las opciones.');
                                                            }
                                                        });

                                                    }
                                                },
                                                error: function () {
                                                    alert('Error al obtener la observación.');
                                                }
                                            });
                                        }

                                        function eliminarBitacora(id) {
                                            // Muestra un cuadro de confirmación con SweetAlert
                                            Swal.fire({
                                                title: "¿Estás seguro?",
                                                showDenyButton: true,
                                                confirmButtonText: "Eliminar",
                                                icon: "question",
                                                denyButtonText: `Cancelar`
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result.isConfirmed) {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: '../sdo-funciones/jefe-eliminarBitacora.php',  // Ajusta la ruta al script PHP que elimina la bitácora
                                                        data: {idBitacora: id},
                                                        success: function (response) {
                                                            // Muestra una alerta con SweetAlert indicando el resultado de la eliminación
                                                            Swal.fire("Observación Eliminada", "", "success")
                                                                .then(() => {
                                                                    // Recarga la página para reflejar los cambios
                                                                    location.reload();
                                                                });
                                                        },
                                                        error: function () {
                                                            // Muestra una alerta con SweetAlert indicando un error
                                                            Swal.fire("Error al eliminar el registro. Por favor, inténtalo de nuevo.", "", "error");
                                                        }
                                                    });
                                                } else if (result.isDenied) {
                                                    Swal.fire("Acción Cancelada", "", "info");
                                                }
                                            });
                                        }
                                    </script>
                                </div>
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

        <script>
            $(document).ready(function () {
                $("#myInput").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#displayTableExample tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
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
<script src="js/dark-mode.js"></script>
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
    $(function () {
        $('#example2').DataTable({
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
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            initComplete: function () {
                this.api().columns([1]).every(function () {
                    var column = this;
                    var select = $('<select style="width: 100%;"><option value="">Categoria...</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
</body>

</html>