<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];

$idCarpeta2 = 0;

$idCarpetaPadre2 = 0;

$idCarpetaAnterior = 0;

if($_SESSION['idDepartamento'] == getIDInstrumentacion()){
    echo '';
}else{

    header('Location: ../sdo-jefestecnicos/index.php?SinAutorizacion=true');
}

if(isset($_POST['idCarpeta'])){
    $idCarpeta3 = trim($_POST['idCarpeta']);
    
    $_SESSION['idCarpeta'] = $idCarpeta3;

    if($idCarpeta3 == "" || empty($idCarpeta3)){
        $idCarpeta2 = 0;
        $idCarpetaPadre2 = 0;
    }else{
        $idCarpeta2 = $idCarpeta3;
        $idCarpetaPadre2 = $idCarpeta3;

        $idCarpetaAnterior = getIDCarpetaPadre($idCarpeta3);
    }


}else if(isset($_SESSION['idCarpeta'])){
    $idCarpeta3 = trim($_SESSION['idCarpeta']);

    $_SESSION['idCarpeta'] = $idCarpeta3;

    if($idCarpeta3 == "" || empty($idCarpeta3)){
        $idCarpeta2 = 0;
        $idCarpetaPadre2 = 0;
    }else{
        $idCarpeta2 = $idCarpeta3;
        $idCarpetaPadre2 = $idCarpeta3;

        $idCarpetaAnterior = getIDCarpetaPadre($idCarpeta3);
    }


}


date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Carpetas de Control</title>

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
        $(document).ready(function () {


            // Show div by removing inline display none style rule
            $(".show-crearCarpeta").click(function () {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listarCarpeta").click(function () {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
            });

            // Show div by removing inline display none style rule
            $(".show-listarArchivo").click(function () {
                $("#rowMenu4").hide();
                $("#rowMenu3").show();
            });

            // Show div by removing inline display none style rule
            $(".show-crearArchivo").click(function () {
                $("#rowMenu4").show();
                $("#rowMenu3").hide();
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


</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<!-- Site wrapper -->
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
                        <h4>Listado de Carpetas</h4>

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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i
                                            class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>

                            <div class="card-body">

                                <div class="row text-center" style="display: flex; justify-content:center;">
                                    &nbsp;
                                    <div class="col-lg-2 col-md-6 col-12 text-center" id="listarCarpeta2"
                                         name="listarCarpeta2">
                                        <!-- small box -->
                                        <button id="listarCarpeta" name="listarCarpeta"
                                                class="btn btn-block bg-gradient-purple btn-md text-white text-center show-listarCarpeta">
                                            <i class="nav-icon fas fa-folder"></i><br>Listado de Carpetas
                                        </button>

                                    </div>

                                    <div id="agregarCarpeta2" name="agregarCarpeta2"
                                         class="col-lg-2 col-md-6 col-12 text-center">
                                        <!-- small box -->
                                        <button id="agregarCarpeta" name="agregarCarpeta"
                                                class="btn btn-block bg-gradient-primary btn-md show-crearCarpeta text-white text-center">
                                            <i class="nav-icon fas fa-folder"></i><br>Crear Carpeta
                                        </button>

                                    </div>
                                    &nbsp;
                                    <div class="col-lg-2 col-md-6 col-12 text-center" id="listarArchivo2"
                                         name="listarArchivo2">
                                        <!-- small box -->
                                        <button id="listarArchivo" name="listarArchivo"
                                                class="btn btn-block bg-gradient-green btn-md text-white text-center show-listarArchivo">
                                            <i class="nav-icon fas fa-folder"></i><br>Listado de Archivos
                                        </button>

                                    </div>

                                    <div id="agregarArchivo2" name="agregarArchivo2"
                                         class="col-lg-2 col-md-6 col-12 text-center">
                                        <!-- small box -->
                                        <button id="agregarArchivo" name="agregarArchivo"
                                                class="btn btn-block bg-gradient-warning btn-md show-crearArchivo text-black text-center">
                                            <i class="nav-icon fas fa-folder"></i><br>Subir Archivos
                                        </button>

                                    </div>
                                </div>


                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <?php

                if (isset($_GET['exitoBorrarCarpeta'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Carpeta Eliminada con &Eacute;xito.',
                            '',
                            'success'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorBorrarCarpeta'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Eliminar Carpeta. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorCarpetaOcupada'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Eliminar Carpeta.<br>Existen Archivos en Carpeta.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['exitoAgregarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Archivo(s) Agregado(s) con &Eacute;xito.',
                            '',
                            'success'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorAgregarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Agregar Archivo(s). Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['vacioAgregarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'No existe(n) Archivo(s). Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['formatoAgregarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Formato de Archivo Incorrecto. Reintente.',
                            'Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml, .ppt, .pptx, .png, .jpg, .jpeg',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['exitoBorrarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Archivo Eliminado con &Eacute;xito.',
                            '',
                            'success'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorBorrarArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Eliminar Archivo. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorDescargaArchivo'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Descargar Archivo. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['exitoCrearCarpeta'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Carpeta Creada con &Eacute;xito.',
                            '',
                            'success'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorCarpetaSQL'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Agregar Carpeta a Base de Datos. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorCarpetaExiste'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Existe Carpeta. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }

                if (isset($_GET['errorCrearCarpeta'])) {
                    ?>
                    <script>
                        Swal.fire(
                            'Error al Crear Carpeta. Reintente.',
                            '',
                            'error'
                        )
                    </script>
                    <?php
                }
                ?>

                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <?php

                                    if($idCarpeta3 == 0){
                                        ?>

                                        <div class="col-md-6 col-12">
                                            <h5>1.- Visualizando Carpetas en Raiz</h5>
                                        </div>
                                        <?php
                                    }else{
                                        ?>

                                        <div class="col-md-6 col-12">
                                            <h5>1.- Visualizando Carpeta "<?php echo getNomCarpetaPadre($idCarpeta3); ?>"</h5>
                                        </div>
                                        <?php
                                    }


                                    if($idCarpeta3 > 0){
                                        ?>

                                        <div class="col-md-6 col-12" align="right">
                                            <form style="margin: 0; text-align: right;" class="" action="carpetas_listado.php" method="post">

                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCarpetaAnterior; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-warning"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Volver a Carpeta Anterior</button>

                                            </form>
                                        </div>
                                    <?php
                                    }

                                    ?>

                                    <br><br>


                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="example2" class="table cell-border row-border" style="width:100%">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre de Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Borrar Carpeta</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryCarpeta = "SELECT * FROM carpeta_control WHERE idCarpetaPadre = ".$idCarpetaPadre2." AND idDepartamento ";

                                        $resCarpeta = mysqli_query($conexion, $queryCarpeta);

                                        $numCarpeta = 1;

                                        while ($rowCarpeta = $resCarpeta->fetch_assoc()) {

                                            $idCarpeta = $rowCarpeta['idCarpeta'];
                                            $idCarpeta = trim($idCarpeta);

                                            $nomCarpeta = $rowCarpeta['nomCarpeta'];
                                            $nomCarpeta = trim($nomCarpeta);


                                            ?>

                                            <tr>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center><?php echo $numCarpeta; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center><?php echo $nomCarpeta; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center>
                                                        <?php
                                                        if ($idCarpeta == 0 || $idCarpeta == "" || $idCarpeta == " " || $idCarpeta == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;" class="" action="carpetas_listado.php" method="post">

                                                                <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-eye"></i></button>

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center>
                                                        <?php
                                                        if ($idCarpeta == 0 || $idCarpeta == "" || $idCarpeta == " " || $idCarpeta == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            $rutaArchivosCarpetas = getRutaArchivosCarpetas($idCarpeta);

                                                            $rutaArchivosCarpetas2 = $rutaArchivosCarpetas.'/*';

                                                            $cantidadArchivosCarpetas = getCantidadArchivosCarpetas($rutaArchivosCarpetas2);

                                                            if($cantidadArchivosCarpetas == 0){

                                                                ?>
                                                                <form style="margin: 0; text-align: center;" class="" action="../sdo-carpetas/carpetas_borrarDirectorio.php" method="post">

                                                                    <input type="hidden" name="idCarpeta" value="<?php echo $idCarpeta; ?>">
                                                                    <input type="hidden" name="idCarpetaAnterior" value="<?php echo $idCarpetaAnterior; ?>">
                                                                    <button type="submit" id="inputMozi" class="btn btn-danger"><i class="fa fa-trash"></i></button>

                                                                </form>

                                                                <?php
                                                            }else{
                                                                echo 'Deshabilitado.';
                                                            }
                                                        }
                                                        ?>
                                                    </center>
                                                </td>
                                            </tr>

                                            <?php

                                            $numCarpeta++;
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre de Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Carpeta</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Borrar Carpeta</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
                                <h5>2.- Agregar Carpeta</h5>
                                <br>

                                <fieldset class="border p-3 reset" id="agregarCarpetaNueva" name="agregarCarpetaNueva">
                                    <legend class="w-auto reset"><b>&nbsp;Agregar Carpeta&nbsp;&nbsp;</b>
                                    </legend>

                                    <form action="../sdo-carpetas/carpetas_agregarNueva.php" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputTerreno1">Ingrese Nombre de Carpeta:
                                                    <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-file"></i></span>
                                                    </div>
                                                    <input class="form-control" required id="nomCarpeta"
                                                           placeholder="Nombre de Carpeta" type="text"
                                                           name="nomCarpeta"/>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <input type="hidden" name="idDepartamentoCarpeta" id="idDepartamentoCarpeta" value="<?php echo $idDepartamento; ?>"/>
                                            <input type="hidden" name="idCarpetaPadre" id="idCarpetaPadre" value="<?php echo $idCarpetaPadre2; ?>"/>
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                        </div>
                                    </form>
                                </fieldset>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row" id="rowMenu3" name="rowMenu3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <?php

                                    if($idCarpeta3 == 0){
                                        ?>

                                        <div class="col-md-6 col-12">
                                            <h5>3.- Visualizando Archivos en Carpeta Raiz</h5>
                                        </div>
                                        <?php
                                    }else{
                                        ?>

                                        <div class="col-md-6 col-12">
                                            <h5>3.- Visualizando Archivos en Carpeta "<?php echo getNomCarpetaPadre($idCarpeta3); ?>"</h5>
                                        </div>
                                        <?php
                                    }


                                    if($idCarpeta3 > 0){
                                        ?>

                                        <div class="col-md-6 col-12" align="right">
                                            <form style="margin: 0; text-align: right;" class="" action="carpetas_listado.php" method="post">

                                                <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCarpetaAnterior; ?>">
                                                <button type="submit" id="inputMozi" class="btn btn-warning"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Volver a Carpeta Anterior</button>

                                            </form>
                                        </div>
                                        <?php
                                    }


                                    ?>

                                    <br><br>


                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="example4" class="table cell-border row-border" style="width:100%">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre de Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Borrar Archivo</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $queryArchivoC = "SELECT * FROM carpeta_archivos WHERE idCarpeta = ".$idCarpetaPadre2;

                                        $resArchivoC = mysqli_query($conexion, $queryArchivoC);

                                        $numArchivoC = 1;

                                        while ($rowArchivoC = $resArchivoC->fetch_assoc()) {

                                            $idArchivoC = $rowArchivoC['idArchivoC'];

                                            $nomArchivoC = trim($rowArchivoC['nomArchivoC']);


                                            ?>

                                            <tr>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center><?php echo $numArchivoC; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center><?php echo $nomArchivoC; ?></center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center>
                                                        <?php
                                                        if ($idArchivoC == 0 || $idArchivoC == "" || $idArchivoC == " " || $idArchivoC == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {

                                                            ?>
                                                            <form style="margin: 0; text-align: center;" class="" target="_blank" action="../sdo-carpetas/carpetas_visualizarArchivo.php" method="post">

                                                                <input type="hidden" name="idArchivoC" value="<?php echo $idArchivoC; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-primary"><i class="fa fa-eye"></i></button>

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>


                                                    </center>
                                                </td>
                                                <td style="vertical-align:middle;" nowrap="nowrap" align="center">
                                                    <center>
                                                        <?php
                                                        if ($idArchivoC == 0 || $idArchivoC == "" || $idArchivoC == " " || $idArchivoC == "  ") {
                                                            ?>
                                                            <center><?php echo "Error al Cargar Datos."; ?></center>
                                                            <?php
                                                        } else {
                                                            ?>
                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-carpetas/carpetas_borrarArchivo.php" method="post">

                                                            <input type="hidden" name="idArchivoC" value="<?php echo $idArchivoC; ?>">
                                                            <button type="submit" id="inputMozi" class="btn btn-danger"><i class="fa fa-trash"></i></button>

                                                        </form>

                                                        <?php

                                                        }
                                                        ?>
                                                    </center>
                                                </td>
                                            </tr>

                                            <?php

                                            $numArchivoC++;
                                        }
                                        ?>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Nombre de Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar Archivo</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Borrar Archivo</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row" id="rowMenu4" name="rowMenu4" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>4.- Agregar Archivos</h5>
                                <br>

                                <fieldset class="border p-3 reset" id="agregarCarpetaNueva" name="agregarCarpetaNueva">
                                    <legend class="w-auto reset"><b>&nbsp;Agregar Archivos&nbsp;&nbsp;</b>
                                    </legend>

                                    <form action="../sdo-carpetas/carpetas_agregarArchivos.php" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <!-- <label for="customFile">Custom File</label> -->
                                                <label for="exampleInputTareasControlCent">Subir Archivos: </label><br>
                                                <label for="exampleInputTareasControlCent">Formatos Permitidos: .pdf , .doc , .docx , .xls , .xlsx, .eml, .ppt, .pptx, .png, .jpg, .jpeg</label>
                                                <div class="custom-file">
                                                    <input type="file" max-uploads="40" class="custom-file-input" onchange="mostrarRadio(this)" multiple="" id="adjuntosArchivosC[]" name="adjuntosArchivosC[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .eml, .ppt, .pptx, .png, .jpg, .jpeg">
                                                    <label class="custom-file-label" for="customFile">Seleccionar Archivo...</label>
                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            $('.custom-file input').change(function(e) {
                                                var files = [];
                                                for (var i = 0; i < $(this)[0].files.length; i++) {
                                                    files.push($(this)[0].files[i].name);
                                                }
                                                $(this).next('.custom-file-label').html(files.join(', '));
                                            });
                                        </script>
                                        <br>
                                        <div class="form-group">
                                            <input type="hidden" name="idDepartamentoCarpeta" id="idDepartamentoCarpeta" value="<?php echo $idDepartamento; ?>"/>
                                            <input type="hidden" name="idCarpetaPadre" id="idCarpetaPadre" value="<?php echo $idCarpetaPadre2; ?>"/>
                                            <input type="submit" class="btn btn-block btn-primary" value="Guardar"/>

                                        </div>
                                    </form>
                                </fieldset>

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
            "pageLength": 5,
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
        $('#example4').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            "pageLength": 5,
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

        }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
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