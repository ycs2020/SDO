<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);

$idDepartamento = $_SESSION['idDepartamento'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Herramientas</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="hold-transition sidebar-mini dark-mode" id="body">
    <div class="wrapper">

        <!-- <div class="preloader  flex-column justify-content-center align-items-center">

            <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
            <br>
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div> -->
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
                            <h4>Mis solicitudes arriendo herramientas y/o retiro materiales </h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            </ol>
                            <br>
                            <br>
                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")

                                    document.body.classList.toggle("dark-mode");

                                }
                            </script>
                            <br>
                            <br>
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
                                <!-- /.card-header -->
                                <div class="card-header">
                                    <a href="arriendo-retiro-administrar.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <a href="soli-arriendo-retiro.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Generar Nueva Solicitud</a>


                                    <h5>Colores para Identificar:</h5>
                                    <ul>
                                       
                                        <li>
                                            <i style="color : #eb2d0c ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Pendiente - Solicitud aún no procesado por pañolero.
                                        </li>
                                        <li>
                                            <i style="color : #0a30f2 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Preparado - Herramientas y/o materiales preparadas para su retiro en pañol.
                                        </li>
                                        <li>
                                            <i style="color : #DCE20E ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; En Ejecución - Ya recibiste las herramientas y/o materiales solicitados.
                                        </li>
                                        <li>
                                            <i style="color : #fc03ca ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Pendiente devolución - Devolviste parcialmente las herramientas arrendadas.
                                        </li>
                                        <li>
                                            <i style="color : #0cc5eb ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Entrega Definitiva - Se te entrego permanentemente la herramienta para su uso institucional.
                                        </li>
                                        <li>
                                            <i style="color : #4BD30B ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Completada - Ya devolviste todas las herramienta, se cierra la solicitud.
                                        </li>


                                    </ul>
                                    <br>
                                    <br>
                                    <?php


                                    if (isset($_GET['guardado'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Solicitud Generada Correctamente',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['mateEliminado'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Materiales de la solicitud eliminadas',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['herraEliminado'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Herramientas de la solicitud eliminadas',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['existeHerra'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Ya Añadida.',
                                                text: 'El producto ya se encuentra seleccionado, si desea cambiar la cantidad borrelo de su lista y vuelva a añadirlo',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['nuevo'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Nuevas Herramientas Añadidas A La Solicitud',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['nuevoMate'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Nuevos Materiales Añadidos A La Solicitud',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['exito'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Estado solicitud modificado como completado',

                                            })
                                        </script>
                                    <?php

                                    }



                                    if (isset($_GET['inconclusa'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Estado solicitud modificado como pendiente',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['eliminado'])) {

                                        echo '<div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Solicitud Eliminada Correctamente.</h5></div>';
                                    }

                                    if (isset($_GET['modificacion'])) {

                                        echo '<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Registro de la herramienta Modificada.</h5></div>';
                                    }

                                    if (isset($_GET['errorEliminar'])) {

                                        echo '<div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="fa fa-check"></i>&nbsp;Fallo la eliminación de la solicitud.</h5></div>';
                                    }
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>

                                                        <th style="vertical-align:middle;">
                                                            <center>Estado<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Observaciones<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Herramientas<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Materiales<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>N° Solicitud<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Termino<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Categoria Herramienta<center>
                                                        </th> -->
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    $query = "SELECT * FROM solicitudes_arriendo_retiro WHERE idSolicitante = '${idUsuario}' ORDER BY fechaSolicitud DESC;";
                                                    $res = mysqli_query($conexion, $query);

                                                    $numHerramienta = 1;

                                                    while ($row = $res->fetch_assoc()) {

                                                        $tipoUsuario = $row['tipoUsuario'];
                                                        $color = "";
                                                        $row['estadoSolicitud'];
                                                        $idSoli = $row['idSoli'];
                                                        if ($row['estadoSolicitud'] == "Pendiente") {
                                                            $color = "style=' background-color: #eb2d0c; font-weight:bold;vertical-align:middle;color:white;'";
                                                        } else if ($row['estadoSolicitud'] == "Preparado") {
                                                            $color = "style=' background-color: #0a30f2;font-weight:bold;vertical-align:middle;color:white;'";
                                                        } else if ($row['estadoSolicitud'] == "En Ejecucion") {
                                                            $color = "style=' background-color: #DCE20E;font-weight:bold;vertical-align:middle;color:black;'";
                                                        } else if ($row['estadoSolicitud'] == "Devolucion Pendiente") {
                                                            $color = "style=' background-color: #fc03ca;font-weight:bold;vertical-align:middle;color:white;'";
                                                        }else if ($row['estadoSolicitud'] == "Entrega Definitiva") {
                                                            $color = "style=' background-color: #0cc5eb;font-weight:bold;vertical-align:middle;color:black;'";
                                                        }else if ($row['estadoSolicitud'] == "Completado") {
                                                            $color = "style=' background-color: #4BD30B;font-weight:bold;vertical-align:middle;color:black;'";
                                                        }

                                                        
                                                    ?>
                                                        <tr>



                                                            <td nowrap="nowrap" <?php echo $color ?>>
                                                                <center>

                                                                    <?php

                                                                    $estado = mb_strtoupper($row['estadoSolicitud']);
                                                                    echo $estado;

                                                                    ?>

                                                                 
                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle; text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php


                                                                    $idSoli = $row['idSoli'];
                                                                    $observaciones = $row['observaciones'];

                                                                    if ($observaciones == '') {

                                                                    ?>
                                                                        <button type="button" title="Sin Observaciones" disabled class="btn btn-warning" id="#idHerramienta<?php echo $idSoli ?>" data-toggle="modal" data-target="#idHerramienta<?php echo $idSoli ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>

                                                                    <?php


                                                                    } else {

                                                                    ?>

                                                                        <button type="button" class="btn  btn-warning" id="#idHerramienta<?php echo $idSoli ?>" data-toggle="modal" data-target="#idHerramienta<?php echo $idSoli ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>


                                                                    <?php

                                                                    }



                                                                    ?>

                                                                    <!-- Button trigger modal -->


                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="idHerramienta<?php echo $idSoli ?>" name="idHerramienta<?php echo $idSoli ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel">Observaciones de la solicitud</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <?php

                                                                                    $sqlDescript = "SELECT observaciones FROM solicitudes_arriendo_retiro WHERE idSoli = ${idSoli}";
                                                                                    $resDescript = mysqli_query($conexion, $sqlDescript);

                                                                                    while ($rowDescript = $resDescript->fetch_assoc()) {

                                                                                        $descript = $rowDescript['observaciones'];
                                                                                    }


                                                                                    ?>

                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <textarea type="text" readonly class="form-control" rows="10"><?php echo $descript ?></textarea>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Ya he leído esto!</button>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align: middle;font-weight:bold">
                                                                <center>

                                                                    <!-- <button title="Añadir Herramientas" class="btn btn-warning" onclick="javascript:window.open('http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/modificar-carrito.php?idSoli=<?php echo $idSoli ?>','','width=1200,height=800,left=50,top=50,toolbar=yes');"><i class="fas fa-plus"></i></button> -->


                                                                    <?php

                                                                    $queryHerramientas1 = "SELECT COUNT(*) total FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
                                                                    $res4 = mysqli_query($conexion, $queryHerramientas1);

                                                                    $filas1 = mysqli_fetch_assoc($res4);


                                                                    if ($filas1['total'] >= 1) {

                                                                    ?>
                                                                        <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#herramientas<?php echo $idSoli ?>"><i class="fas fa-eye"></i></button>


                                                                    <?php


                                                                    } else {

                                                                    ?>

                                                                        <button title="SIN HERRAMIENTAS PARA MOSTRAR" disabled type="submit" class="btn btn-secondary" data-toggle="modal" data-target="#herramientas<?php echo $idSoli ?>"><i class="fas fa-eye"></i></button>



                                                                    <?php

                                                                    }


                                                                    ?>


                                                                    <div class="modal fade" id="herramientas<?php echo $idSoli ?>" name="herramientas<?php echo $idSoli ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel">Listado Herramientas</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <table class="table">
                                                                                        <thead class="thead-light">
                                                                                            <tr>

                                                                                                <th scope="col" class="text-center">CÓDIGO</th>
                                                                                                <th scope="col" class="text-center">NOMBRE</th>
                                                                                                <th scope="col" class="text-center">IMAGEN</th>

                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php

                                                                                        $queryHerramientas = "SELECT * FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
                                                                                        $res1 = mysqli_query($conexion, $queryHerramientas);


                                                                                        foreach ($res1 as $indice => $herramienta) :

                                                                                            $idArriendo = $herramienta['idArriendo'];
                                                                                            $idHerramienta = $herramienta['idHerramienta'];
                                                                                            $cantidadSacar = $herramienta['cantidadHerramienta'];

                                                                                            $sqlHerramienta = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
                                                                                            $resHerramienta = mysqli_query($conexion, $sqlHerramienta);

                                                                                            while ($rowHerramienta = $resHerramienta->fetch_assoc()) {

                                                                                                $imagen = $rowHerramienta['imagen'];
                                                                                            }


                                                                                        ?>
                                                                                            <tbody>
                                                                                                <tr>

                                                                                                    <td class="text-center"><?php echo $herramienta['codHerramienta'] ?></td>
                                                                                                    <td class="text-center" style="text-transform: uppercase;"><?php echo $herramienta['nomHerramienta'] ?></td>
                                                                                                    <td class="text-center"><img src="../sdo-bodega-panol/img3/<?php echo $imagen; ?>" width="100px" alt="imagen herramienta"></td>
                                                                                                    <!-- <td class="text-center">



                                                                                                        <form action="../sdo-funciones/eliminar-herramientas-ticket.php" method="POST">

                                                                                                            <input type="hidden" name="idArriendo" value="<?php echo $idArriendo ?>">
                                                                                                            <input type="hidden" name="idHerramienta" value="<?php echo $idHerramienta ?>">
                                                                                                            <input type="hidden" name="cantidadHerramienta" value="<?php echo $cantidadSacar ?>">
                                                                                                            <input type="hidden" name="codSolicitud" value="<?php echo $codSolicitud ?>">



                                                                                                            <input class="btn btn-danger" value="Eliminar" type="submit"></input>
                                                                                                        </form>

                                                                                                    </td> -->

                                                                                                </tr>
                                                                                            </tbody>

                                                                                        <?php
                                                                                        endforeach;
                                                                                        ?>
                                                                                    </table>


                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">Revisado!</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align: middle;font-weight:bold">
                                                                <center>


                                                                    <!-- <button title="Añadir Materiales" class="btn btn-warning" onclick="javascript:window.open('http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/modificar-carrito-mate.php?idSoli=<?php echo $idSoli ?>','','width=1200,height=800,left=50,top=50,toolbar=yes');"><i class="fas fa-plus"></i></button> -->


                                                                    <?php

                                                                    $queryMateriales = "SELECT COUNT(*) total FROM material_retirado WHERE idSolicitud = ${idSoli}";
                                                                    $res2 = mysqli_query($conexion, $queryMateriales);

                                                                    $filas = mysqli_fetch_assoc($res2);



                                                                    if ($filas['total'] >= 1) {

                                                                    ?>
                                                                        <button class="btn btn-info" data-toggle="modal" data-target="#materiales<?php echo $idSoli ?>"><i class="fas fa-eye"></i></button>

                                                                    <?php


                                                                    } else {

                                                                    ?>

                                                                        <button disabled title="SIN MATERIALES PARA MOSTRAR" class="btn btn-secondary" data-toggle="modal" data-target="#materiales<?php echo $idSoli ?>"><i class="fas fa-eye"></i></button>


                                                                    <?php

                                                                    }


                                                                    ?>


                                                                    <div class="modal fade " id="materiales<?php echo $idSoli ?>" name="materiales<?php echo $idSoli ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel">Listado Materiales</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <?php

                                                                                    $queryMateriales1 = "SELECT *  FROM material_retirado WHERE idSolicitud = ${idSoli}";
                                                                                    $res3 = mysqli_query($conexion, $queryMateriales1);

                                                                                    ?>



                                                                                    <table class="table">
                                                                                        <thead class="thead-light">
                                                                                            <tr>

                                                                                                <th scope="col" class="text-center">CÓDIGO</th>
                                                                                                <th scope="col" class="text-center">NOMBRE</th>
                                                                                                <th scope="col" class="text-center">IMAGEN</th>
                                                                                                <th scope="col" class="text-center">CANTIDAD</th>



                                                                                            </tr>
                                                                                        </thead>

                                                                                        <?php


                                                                                        foreach ($res3 as $indice => $material) :

                                                                                            $idMaterial = $material['idMaterial'];
                                                                                            $idArriendo = $material['idMateRetirado'];
                                                                                            $cantidadSacar = $material['cantidadMaterial'];
                                                                                            $sqlMaterial = "SELECT * FROM pañol_materiales WHERE idMaterial = ${idMaterial}";
                                                                                            $resMaterial = mysqli_query($conexion, $sqlMaterial);

                                                                                            while ($rowMaterial = $resMaterial->fetch_assoc()) {

                                                                                                $imagen = $rowMaterial['imagen'];
                                                                                            }
                                                                                        ?>
                                                                                            <tbody>
                                                                                                <tr>

                                                                                                    <td class="text-center"><?php echo $material['codMaterial'] ?></td>
                                                                                                    <td class="text-center" style="text-transform: uppercase;"><?php echo $material['nomMaterial'] ?></td>
                                                                                                    <td class="text-center"><img src="../sdo-bodega-panol/img/<?php echo $imagen; ?>" width="100px" alt="imagen material"></td>
                                                                                                    <td class="text-center"><?php echo $material['cantidadMaterial']?></td>

                                                                                                    <!-- <td class="text-center"> -->

                                                                                                    <!-- <form action="../sdo-funciones/eliminar-materiales-ticket.php" method="POST">

                                                                                                            <input type="hidden" name="idArriendo" value="<?php echo $idArriendo ?>">
                                                                                                            <input type="hidden" name="idMaterial" value="<?php echo $idMaterial ?>">
                                                                                                            <input type="hidden" name="cantidadMaterial" value="<?php echo $cantidadSacar ?>">
                                                                                                            <input type="hidden" name="codSolicitud" value="<?php echo $codSolicitud ?>">



                                                                                                            <input class="btn btn-danger" value="Eliminar" type="submit"></input>
                                                                                                        </form> -->

                                                                                                    <!-- </td> -->

                                                                                                </tr>
                                                                                            </tbody>

                                                                                        <?php
                                                                                        endforeach;


                                                                                        ?>
                                                                                    </table>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">Revisado!</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </center>
                                                            </td>

                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $codSolicitud = mb_strtoupper($row['codSolicitud']);
                                                                    echo $codSolicitud;

                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    $fecha =  $row['fechaSolicitud'];
                                                                    $fechaNueva = date("d/m/Y", strtotime($fecha));
                                                                    echo $fechaNueva;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    $fechaTermino =  $row['fechaTermino'];
                                                                    $fechaTermino = date("d/m/Y", strtotime($fechaTermino));
                                                                    echo $fechaTermino;
                                                                    ?>
                                                                </center>
                                                            </td>
                      
                                                        </tr>
                                                    <?php

                                                        $numHerramienta++;
                                                    }


                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Estado<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Observaciones <center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Herramientas <center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Materiales <center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>N° Solicitud<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Termino<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Categoria Herramienta<center>
                                                        </th> -->
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
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
                }
            });
        });
    </script>
</body>

</html>