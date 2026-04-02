<?php

include '../sdo-funciones/conexion.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/dark-mode.css">


    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="hold-transition sidebar-mini" id="body">
    <div class="wrapper">

        <div class="preloader  flex-column justify-content-center align-items-center">

            <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
            <br>
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>
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
                            <h4>Listado de solicitudes arriendo herramientas y/o retiro materiales </h4>
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
                                    <a href="panel-arriendo-retiro.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <a href="arriendo-retiro.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Generar Nueva Solicitud</a>


                                    <h5>Colores para Identificar:</h5>
                                    <ul>

                                        <li>
                                            <i style="color : #eb2d0c ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Pendiente - Solicitud que aún no preparas.
                                        </li>
                                        <li>
                                            <i style="color : #063970 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Preparado - Solicitud ya preparada y lista para su retiro.
                                        </li>
                                        <li>
                                            <i style="color : #DCE20E ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; En Ejecución - Ya entregaste las herramientas y/o materiales al personal.
                                        </li>
                                        <li>
                                            <i style="color : #fc03ca ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Devolución Pendiente - Se te devolvio algunas herramientas de la solicitud.
                                        </li>
                                        <li>
                                            <i style="color : #0cc5eb ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Entrega Definitiva - Entregas definitivamente las herramientas al personal, sin devolución.
                                        </li>
                                        <li>
                                            <i style="color : #4BD30B ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Completada - Las herramientas ya fueron devueltas, se cierra la solicitud.
                                        </li>


                                    </ul>
                                    <br>
                                    <br>
                                    <p  align="center" style="color:black; font-weight:bold; background-color: yellow">** Si el estado es Completado o Entrega Definitiva ya no es posible cambiar su estado o eliminar la solicitud.**</p>
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
                                                text: 'Solictud Finalizada y Completada',

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
                                                text: 'Estado solicitud modificado a pendiente de devolucion',

                                            })
                                        </script>
                                    <?php

                                    }

                                    if (isset($_GET['preparado'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Estado solicitud modificado a preparado',

                                            })
                                        </script>
                                    <?php

                                    }



                                    if (isset($_GET['ejecucion'])) {

                                    ?>

                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Exito.',
                                                text: 'Estado solicitud modificado a En Ejecución',

                                            })
                                        </script>
                                    <?php

                                    }

                                    
                                    if (isset($_GET['entrega'])) {

                                        ?>
    
                                            <script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Exito.',
                                                    text: 'Estado solicitud modificado a Entrega Definitiva',
    
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
                                                            <center>Modificar Estado<center>
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
                                                        <th style="vertical-align:middle;">
                                                            <center>Personal Solicitante<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Departamento<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Acciones <center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    $query = 'SELECT * FROM solicitudes_arriendo_retiro ORDER BY fechaSolicitud DESC;';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numHerramienta = 1;

                                                    while ($row = $res->fetch_assoc()) {

                                                        $tipoUsuario = $row['tipoUsuario'];
                                                        $color = "";
                                                        $row['estadoSolicitud'];
                                                        $idSoli = $row['idSoli'];
                                                        if ($row['estadoSolicitud'] == "Pendiente") {
                                                            $color = "style=' background-color: #eb2d0c; font-weight:bold;vertical-align:middle;color:white;text-align:center'";
                                                        } else if ($row['estadoSolicitud'] == "Preparado") {
                                                            $color = "style=' background-color: #0a30f2;font-weight:bold;vertical-align:middle;color:white;text-align:center'";
                                                        } else if ($row['estadoSolicitud'] == "En Ejecucion") {
                                                            $color = "style=' background-color: #DCE20E;font-weight:bold;vertical-align:middle;color:black;text-align:center'";
                                                        } else if ($row['estadoSolicitud'] == "Devolucion Pendiente") {
                                                            $color = "style=' background-color: #fc03ca;font-weight:bold;vertical-align:middle;color:white;text-align:center'";
                                                        } else if ($row['estadoSolicitud'] == "Entrega Definitiva") {
                                                            $color = "style=' background-color: #0cc5eb;font-weight:bold;vertical-align:middle;color:black;text-align:center'";
                                                        } else if ($row['estadoSolicitud'] == "Completado") {
                                                            $color = "style=' background-color: #4BD30B;font-weight:bold;vertical-align:middle;color:black;text-align:center'";
                                                        }
                                                    ?>
                                                        <tr>



                                                            <td nowrap="nowrap" <?php echo $color ?>>

                                                                <?php

                                                                $estadoParaValidar = $row['estadoSolicitud'];
                                                                $estado = mb_strtoupper($row['estadoSolicitud']);
                                                                echo $estado;

                                                                ?>


                                                            </td>
                                                            <td style="vertical-align:middle">
                                                                <center>


                                                                    <?php if ($estadoParaValidar == 'Entrega Definitiva' || $estadoParaValidar == 'Completado') {

                                                                    ?>
                                                                        <button type="button" disabled class="btn  btn-danger" data-toggle="modal" data-target="#idSoli<?php echo $idSoli ?>"><i class="nav-icon fa fa-edit"></i></button>

                                                                    <?php
                                                                    } else {

                                                                    ?>
                                                                        <button type="button" class="btn  btn-danger" data-toggle="modal" data-target="#idSoli<?php echo $idSoli ?>"><i class="nav-icon fa fa-edit"></i></button>

                                                                    <?php

                                                                    }

                                                                    ?>

                                                                    <!-- <button title="Añadir Herramientas" class="btn btn-warning" onclick="javascript:window.open('http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/modificar-carrito.php?idSoli=<?php echo $idSoli ?>','','width=1200,height=800,left=50,top=50,toolbar=yes');"><i class="fas fa-plus"></i></button> -->

                                                                    <!-- <button type="button" class="btn btn-xs  btn-success" onclick="javascript:window.open('http://localhost/TrabajarSDO_Pruebas/sdo-bodega-pa%C3%B1ol/modificar-carrito.php?idSoli=<?php echo $idSoli ?>','','width=1200,height=800,left=50,top=50,toolbar=yes');"><i class="nav-icon fa fa-check"></i></button> -->



                                                                    <form action="../sdo-funciones/cambiar-estado-herra-mate.php" method="POST">
                                                                        <?php

                                                                        $sqlSelect = "SELECT  * FROM solicitudes_arriendo_retiro WHERE idSoli  = ${idSoli}";
                                                                        $resSelect = mysqli_query($conexion, $sqlSelect);

                                                                        while ($rowSelect = $resSelect->fetch_assoc()) {

                                                                            $datosSelect['codSolicitud'] = $rowSelect['codSolicitud'];
                                                                            $datosSelect['fechaSolicitud'] = $rowSelect['fechaSolicitud'];
                                                                            $datosSelect['idSolicitante'] = $rowSelect['idSolicitante'];
                                                                            $datosSelect['idDepaSolicitante'] = $rowSelect['idDepaSolicitante'];
                                                                            $datosSelect['fechaTermino'] = $rowSelect['fechaTermino'];
                                                                            $datosSelect['tipoUsuario'] = $rowSelect['tipoUsuario'];

                                                                            $codSolicitud = $rowSelect['codSolicitud'];
                                                                            $fechaSolicitud = $rowSelect['fechaSolicitud'];
                                                                            $idSolicitante = $rowSelect['idSolicitante'];
                                                                            $fechaTermino = $rowSelect['fechaTermino'];
                                                                            $tipoUsuario = $rowSelect['tipoUsuario'];



                                                                            $idDepaSolicitante = $rowSelect['idDepaSolicitante'];
                                                                        }

                                                                        ?>

                                                                        <input type="hidden" value="<?php echo $idSoli ?>" name="idSoli">

                                                                        <input type="hidden" value="<?php echo $idDepaSolicitante ?>" name="idDepa">
                                                                        <input type="hidden" value="<?php echo $idSolicitante ?>" name="idSolicitante">
                                                                        <input type="hidden" value="<?php echo $fechaSolicitud ?>" name="fecha">
                                                                        <input type="hidden" value="<?php echo $codSolicitud ?>" name="codigo">
                                                                        <input type="hidden" value="<?php echo $fechaTermino ?>" name="fechaTermino">
                                                                        <input type="hidden" value="<?php echo $tipoUsuario ?>" name="tipoUsuario">

                                                                        <div class="modal fade" id="idSoli<?php echo $idSoli ?>" name="idSoli<?php echo $idSoli ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" style="color: #FFF;" id="EjemploModalLabel">Modificar Estado</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder" style="color: #FFF;">Nuevo Estado: <code>*</code></label>
                                                                                            <select name="nuevoEstado" id="nuevoEstado" required class="form-control">
                                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                                <option value="Preparado">Preparado</option>
                                                                                                <option value="En Ejecucion">En Ejecución</option>
                                                                                                <option value="Devolucion Pendiente">Devolución Pendiente</option>
                                                                                                <option value="Entrega Definitiva">Entrega Definitiva</option>
                                                                                                <option value="Completado">Completado</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div style="text-align:left;" class="form-group">
                                                                                            <label for="exampleInputBorder" style="color: #FFF;">Observaciones: <code>*</code></label>
                                                                                            <textarea name="observaciones" required rows="15" class="form-control" placeholder="Escriba aqui si las herramientas fueron devueltas en buen estado, detalles de entrega, observaciones o si devuelve algo del material de esta solicitud"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                        <input type="submit" class="btn btn-primary" value="Completado" id="Completado"></input>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                    <?php

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
                                                                                                <th scope="col" class="text-center">SECTOR</th>


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
                                                                                                    <td class="text-center"><?php echo $herramienta['sector'] ?></td>

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
                                                                                                <th scope="col" class="text-center">SECTOR</th>




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
                                                                                                    <td class="text-center"><?php echo $material['cantidadMaterial'] ?></td>
                                                                                                    <td class="text-center"><?php echo $material['sector'] ?></td>


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

                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:center;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    $fecha =  $row['fechaSolicitud'];
                                                                    $fechaNueva = date("d/m/Y", strtotime($fecha));
                                                                    echo $fechaNueva;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:center;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    $fechaTermino =  $row['fechaTermino'];
                                                                    $newDate = date("d/m/Y", strtotime($fechaTermino));
                                                                    echo $newDate;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle; text-align:center;font-weight:bold">

                                                                <?php
                                                                $idSolicitante = mb_strtoupper($row['idSolicitante']);

                                                                if ($tipoUsuario == 'personal') {

                                                                    $queryPersonal = "SELECT * FROM personal WHERE idPersonal  = ${idSolicitante}";
                                                                    $resPersonal = mysqli_query($conexion, $queryPersonal);


                                                                    while ($rowPersonal = $resPersonal->fetch_assoc()) {

                                                                        $datos = [];

                                                                        $datos['nomPersonal'] = $rowPersonal['nomPersonal'];

                                                                        $nombre = $datos['nomPersonal'];
                                                                    }
                                                                } else if ($tipoUsuario == 'jefe tecnico') {
                                                                    $queryPersonal = "SELECT * FROM jefes_tecnicos WHERE idJefe  = ${idSolicitante}";
                                                                    $resPersonal = mysqli_query($conexion, $queryPersonal);

                                                                    while ($rowPersonal = $resPersonal->fetch_assoc()) {

                                                                        $datos = [];

                                                                        $datos['nomJefe'] = $rowPersonal['nomJefe'];

                                                                        $nombre = $datos['nomJefe'];
                                                                    }
                                                                } else if ($tipoUsuario == 'directivos') {
                                                                    $queryPersonal = "SELECT * FROM directivos WHERE idDirectivo  = ${idSolicitante}";
                                                                    $resPersonal = mysqli_query($conexion, $queryPersonal);

                                                                    while ($rowPersonal = $resPersonal->fetch_assoc()) {

                                                                        $datos = [];

                                                                        $datos['nomDirectivo'] = $rowPersonal['nomDirectivo'];

                                                                        $nombre = $datos['nomDirectivo'];
                                                                    }
                                                                }


                                                                echo mb_strtoupper($nombre);



                                                                ?>


                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    if ($tipoUsuario == 'directivos') {

                                                                        echo 'DIRECTIVO';
                                                                    } else {
                                                                        $idDepartamento = mb_strtoupper($row['idDepaSolicitante']);

                                                                        $queryDepa = "SELECT * FROM departamentos WHERE idDepartamento = ${idDepartamento}";
                                                                        $resDepa = mysqli_query($conexion, $queryDepa);

                                                                        while ($rowDepa = $resDepa->fetch_assoc()) {

                                                                            $datos = [];

                                                                            $datos['nomDepartamento'] = $rowDepa['nomDepartamento'];

                                                                            $nomDepartamento = $datos['nomDepartamento'];
                                                                        }

                                                                        echo mb_strtoupper($nomDepartamento);
                                                                    }





                                                                    ?>
                                                                </center>
                                                            </td>



                                                            <td style="vertical-align: middle;font-weight:bold">
                                                                <center>

                                                                    <div style="display: inline-block;">

                                                                        <button type="button"  class="btn  btn-info" data-toggle="modal" data-target="#generaActa<?php echo $idSoli ?>"><i class="fas fa-download"></i></button>

                                                                        <form target="_blank" action="../sdo-pdf/SDO-Acta-Entrega.php" method="POST">


                                                                            
                                                                            <input type="hidden" name="idSoli" value="<?php echo $idSoli ?>">

                                                                            <div class="modal fade" id="generaActa<?php echo $idSoli ?>" name="generaActa<?php echo $idSoli ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="EjemploModalLabel">Tipo Acta</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">×</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">

                                                                                            <select name="opcion" id="opcion" class="form-control">
                                                                                                <option value="Entrega">Entrega Definitiva</option>
                                                                                                <option value="Arriendo">Arriendo Herramientas y Entrega Materiales</option>                                                                                        
                                                                                            </select>


                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar Ventana</button>
                                                                                            <button type="submit"  class="btn btn-info" >Generar Acta</button>


                                                                                        </div>
                                                                                    </div>
                                                                                </div>



                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                    <div style="display: inline-block;">
                                                                        <form action="../sdo-funciones/eliminar-arriendo-retiro.php" method="POST">


                                                                            <?php if ($estadoParaValidar == 'Entrega Definitiva' || $estadoParaValidar == 'Completado') {

                                                                            ?>
                                                                                <button type="submit" disabled class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>

                                                                            <?php
                                                                            } else {

                                                                            ?>
                                                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>

                                                                            <?php

                                                                            }

                                                                            ?>


                                                                            <input type="hidden" name="idSoli" value="<?php echo $idSoli ?>">
                                                                            <input type="hidden" name="codigo" value="<?php echo $codSolicitud ?>">
                                                                            <input type="hidden" name="idDepartamento" value="<?php echo $idDepartamento ?>">
                                                                            <input type="hidden" name="idSolicitante" value="<?php echo $idSolicitante ?>">
                                                                            <input type="hidden" name="fechaIngreso" value="<?php echo $fecha ?>">




                                                                        </form>
                                                                    </div>


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
                                                            <center>Modificar Estado<center>
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
                                                        <th style="vertical-align:middle;">
                                                            <center>Personal Solicitante<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Departamento<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Acciones<center>
                                                        </th>

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
                },
                initComplete: function() {
                    this.api().columns([0]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Estado...</option></select>')
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
                    this.api().columns([8]).every(function() {
                        var column = this;
                        var select = $('<select style="width: 100%;"><option value="">Seleccione Solicitante...</option></select>')
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

            });
        });
    </script>
    <script src="js/dark-mode.js"></script>

</body>

</html>