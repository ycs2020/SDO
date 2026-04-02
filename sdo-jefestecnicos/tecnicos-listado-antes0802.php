<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Tecnicos</title>

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

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();

            $('#departamentoAgregar').change(function() {
                recargarSubDepartamento();
            });


            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/subdepartamento-agregar.php",
                    data: "idDepartamento=" + $('#departamentoAgregar').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);


                    }
                });

            };
        });
    </script>

</head>

<body class="hold-transition sidebar-mini dark-mode">
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
                            <h4>Listado de Tecnicos</h4>
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

                                    <div class="form-group">
                                        <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                            <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                        </div>
                                    </div>

                                    <script>
                                        function toggleDark() {

                                            document.body.classList.toggle("dark-mode");
                                            var element2 = document.getElementById("hrefInicio");

                                            const color = element2.style.color;

                                            if(color == 'white'){
                                                element2.style.color = 'blue';
                                            }else{
                                                element2.style.color = 'white';
                                            }

                                        }
                                    </script>
                                    <br>
                                    <br>
                                    <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <span>
                                        <form method="post" action="../sdo-funciones/tecnicos-agregar.php">
                                            <button type="button" data-toggle="modal" data-target="#modalAddTecnico" id="inputMozi" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Tecnico</button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalAddTecnico" name="modalAddTecnico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title" id="exampleModalLabel">Agregar Tecnico</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                <input type="text" class="form-control form-control-border" id="addNombre" name="addNombre" placeholder="Nombre del Tecnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addUser" name="addUser" placeholder="Usuario del Tecnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="addPassword" name="addPassword" placeholder="Contrase&ntilde;a del Tecnico" value="" required>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group">
                                                                <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                <select class="custom-select" name="departamentoAgregar" id="departamentoAgregar">
                                                                    <?php

                                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE idDepartamento = '" . $idDepartamento . "' ORDER BY nomDepartamento ASC";

                                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                    ?>
                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>">
                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                        </option>
                                                                    <?php

                                                                    }


                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div style="text-align:left;" class="form-group" name="visualizarSubDepartamento" id="visualizarSubDepartamento">

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Agregar</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </span>
                                </div>
                                <?php

                                //AVISOS AGREGAR
                                if (isset($_GET['exitoAgregarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Creado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorAgregarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Agregar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['existeUsuario'])) {
                                    echo '<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="vertical-align: middle;"><i class="fa fa-exclamation-triangle"></i>&nbsp;El Nombre de Usuario ya se encuentra registrado. Reintente.</h5></div>';
                                }
                                //AVISOS AGREGAR

                                //AVISOS MODIFICAR
                                if (isset($_GET['exitoModificarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Modificado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorModificarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Modificar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorSeleccionarModificarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Seleccionar el Personal a Modificar. Reintente.</h5></div>';
                                }
                                //AVISOS MODIFICAR

                                //AVISOS ELIMINAR
                                if (isset($_GET['exitoEliminarPersonal'])) {
                                    echo '<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 style="text-align:left;"><i class="fa fa-check"></i>&nbsp;Personal Eliminado con &Eacute;xito.</h5></div>';
                                }

                                if (isset($_GET['errorEliminarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Eliminar al Personal. Reintente.</h5></div>';
                                }

                                if (isset($_GET['errorSeleccionarEliminarPersonal'])) {
                                    echo '<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="fa fa-exclamation-triangle"></i>&nbsp;Error al Seleccionar el Personal a Eliminar. Reintente.</h5></div>';
                                }
                                //AVISOS ELIMINAR

                                ?>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form action="../sdo-funciones/jefe-personal-modificar.php" method="post">

                                        <div class="table-responsive">
                                            <table id="personalDesktop" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Nombre<center>
                                                        </th>
                                                        <th>
                                                            <center>Usuario<center>
                                                        </th>
                                                        <th>
                                                            <center>Contrase&ntilde;a<center>
                                                        </th>
                                                        <th>
                                                            <center>Departamento<center>
                                                        </th>
                                                        <?php
                                                        if ($departamentoJefe == "Equipos Industriales" || $departamentoJefe == "equipos industriales") {
                                                        ?>
                                                            <th>
                                                                <center>Sub-Departamento<center>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th>
                                                            <center>Estado<center>
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
                                                    $query = "SELECT * FROM personal WHERE departamentoPersonal = '" . $idDepartamento . "'  ORDER BY nomPersonal, departamentoPersonal ASC";

                                                    $res = mysqli_query($conexion, $query);

                                                    $numReporte = 1;

                                                    while ($row = $res->fetch_assoc()) {
                                                    ?>
                                                        <tr>
                                                            <td style="vertical-align:middle;">
                                                                <center><?php echo $numReporte; ?></center>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="justify">
                                                                <?php
                                                                $nuevoNom = mb_convert_case($row['nomPersonal'], MB_CASE_TITLE, "UTF-8");
                                                                echo $nuevoNom;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $userPersonal = $row['userPersonal'];
                                                                echo $userPersonal;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;" align="center">
                                                                <?php
                                                                $passPersonal = $row['passPersonal'];
                                                                echo $passPersonal;

                                                                ?>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $departamentoPersonal = getNomDepartamento($row['departamentoPersonal']);
                                                                    echo $departamentoPersonal;

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <?php
                                                            if ($departamentoJefe == "Equipos Industriales" || $departamentoJefe == "equipos industriales") {
                                                            ?>
                                                                <td style="vertical-align:middle;">
                                                                    <center>
                                                                        <?php
                                                                        $nomSubdepartamento = getNomSubDepartamento($row['idSubdepartamento']);
                                                                        echo $nomSubdepartamento;

                                                                        ?>
                                                                    </center>
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $activoPersonal = trim($row['activoPersonal']);

                                                                    if ($activoPersonal == 0) {
                                                                        echo "Pendiente";
                                                                    } else if ($row['activoPersonal'] == 1) {
                                                                        echo "Activo";
                                                                    } else {
                                                                        echo "Error";
                                                                    }
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $idPersonal = $row['idPersonal'];
                                                                    $idPersonal = trim($idPersonal);
                                                                    if ($idPersonal == 0 || $idPersonal == "" || $idPersonal == " " || $idPersonal == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <form style="margin: 0; text-align: center;" action="../sdo-funciones/jefe-personal-modificar.php" method="post">

                                                                            <input type="hidden" name="idPersonal" value="<?php echo $idPersonal; ?>">
                                                                            <button type="button" data-toggle="modal" data-target="#modalDPersonal<?php echo $idPersonal; ?>" id="inputMozi" class="btn btn-block btn-warning"><i class="fa fa-pen"></i></button>

                                                                            <!-- Modal -->
                                                                            <div class="modal fade" id="modalDPersonal<?php echo $idPersonal; ?>" name="modalDPersonal<?php echo $idPersonal; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header bg-primary">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">Personal: <?php echo $nuevoNom; ?></h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <?php

                                                                                        $datosPersonalMobile = array();
                                                                                        $datosPersonalMobile = getDatosPersonal($idPersonal);

                                                                                        $nomPersonal = mb_convert_case($row['nomPersonal'], MB_CASE_TITLE, "UTF-8");
                                                                                        $userPersonal = $row['userPersonal'];
                                                                                        $passPersonal = $row['passPersonal'];
                                                                                        $activoPersonal = $row['activoPersonal'];
                                                                                        $modDepartamentoPersonal = $row['departamentoPersonal'];
                                                                                        $modSubDepartamentoPersonal = $row['idSubdepartamento'];


                                                                                        ?>
                                                                                        <div class="modal-body">
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Nombre: <code>*</code></label>
                                                                                                <input type="text" class="form-control form-control-border" id="modNombre" name="modNombre" placeholder="Nombre de Personal" value="<?php echo $nomPersonal; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Usuario: <code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modUser" name="modUser" placeholder="Usuario de Personal" value="<?php echo $userPersonal; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Contrase&ntilde;a: <code>*</code></label>
                                                                                                <input style="text-align:left;" type="text" style="text-align:center;" class="form-control form-control-border" id="modPassword" name="modPassword" placeholder="Contrase&ntilde;a de Personal" value="<?php echo $passPersonal; ?>" required>
                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Estado: <code>*</code></label>
                                                                                                <?php
                                                                                                if ($activoPersonal) {
                                                                                                ?>
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input" type="radio" value="false" name="modEstado">
                                                                                                        <label class="form-check-label">Pendiente</label>
                                                                                                    </div>
                                                                                                    <div class="form-check">
                                                                                                        <input checked class="form-check-input" type="radio" value="true" name="modEstado">
                                                                                                        <label class="form-check-label">Activo</label>
                                                                                                    </div>
                                                                                                <?php
                                                                                                } else {
                                                                                                ?>
                                                                                                    <div class="form-check">
                                                                                                        <input checked class="form-check-input" type="radio" value="false" name="modEstado">
                                                                                                        <label class="form-check-label">Pendiente</label>
                                                                                                    </div>
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input" type="radio" value="true" name="modEstado">
                                                                                                        <label class="form-check-label">Activo</label>
                                                                                                    </div>
                                                                                                <?php
                                                                                                }
                                                                                                ?>

                                                                                            </div>
                                                                                            <div style="text-align:left;" class="form-group">
                                                                                                <label for="exampleInputBorder">Departamento: <code>*</code></label>
                                                                                                <select class="custom-select" name="departamentoModificar" id="departamentoModificar">
                                                                                                    <?php

                                                                                                    $queryDepartamentos = "SELECT * FROM departamentos WHERE idDepartamento = " . $modDepartamentoPersonal . "  ORDER BY nomDepartamento ASC";

                                                                                                    $resDepartamentos = mysqli_query($conexion, $queryDepartamentos);

                                                                                                    while ($rowDepartamentos = $resDepartamentos->fetch_assoc()) {

                                                                                                    ?>
                                                                                                        <option value="<?php echo $rowDepartamentos['idDepartamento']; ?>" selected="selected">
                                                                                                            <?php echo $rowDepartamentos['nomDepartamento']; ?>
                                                                                                        </option>
                                                                                                    <?php

                                                                                                    }


                                                                                                    ?>
                                                                                                </select>

                                                                                            </div>
                                                                                            <?php
                                                                                            if ($departamentoJefe == "Equipos Industriales" || $departamentoJefe == "equipos industriales") {
                                                                                            ?>

                                                                                                <div style="text-align:left;" class="form-group">
                                                                                                    <label for="exampleInputBorder">Sub-Departamento: <code>*</code></label>
                                                                                                    <select class="custom-select" name="subdepartamentoModificar" id="subdepartamentoModificar">
                                                                                                        <?php

                                                                                                        $querySubDepartamentos = "SELECT * FROM subdepartamentos ORDER BY nomSubDepartamento ASC";

                                                                                                        $resSubDepartamentos = mysqli_query($conexion, $querySubDepartamentos);

                                                                                                        while ($rowSubDepartamentos = $resSubDepartamentos->fetch_assoc()) {

                                                                                                            if ($rowSubDepartamentos['idSubDepartamento'] == $modSubDepartamentoPersonal) {
                                                                                                        ?>
                                                                                                                <option value="<?php echo $rowSubDepartamentos['idSubDepartamento']; ?>" selected="selected">
                                                                                                                    <?php echo $rowSubDepartamentos['nomSubDepartamento']; ?>
                                                                                                                </option>
                                                                                                            <?php
                                                                                                            } else {
                                                                                                            ?>
                                                                                                                <option value="<?php echo $rowSubDepartamentos['idSubDepartamento']; ?>">
                                                                                                                    <?php echo $rowSubDepartamentos['nomSubDepartamento']; ?>
                                                                                                                </option>
                                                                                                        <?php
                                                                                                            }
                                                                                                        }


                                                                                                        ?>
                                                                                                    </select>

                                                                                                </div>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <input type="hidden" name="idPersonal" id="idPersonal" value="<?php echo $idPersonal; ?>">
                                                                                            <input type="hidden" name="tipoUsuario" id="tipoUsuario" value="JefeTecnico">
                                                                                            <button type="submit" class="btn btn-primary">Modificar</button>
                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </form>

                                                                    <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle;">
                                                                <center>
                                                                    <?php
                                                                    $idPersonal = $row['idPersonal'];
                                                                    $idPersonal = trim($idPersonal);
                                                                    if ($idPersonal == 0 || $idPersonal == "" || $idPersonal == " " || $idPersonal == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al Cargar Datos."; ?></center>
                                                                    <?php
                                                                    } else {

                                                                    ?>

                                                                        <form style="margin: 0; text-align: center;" class="" action="../sdo-funciones/jefe-personal-eliminar.php" method="post">

                                                                            <input type="hidden" name="idPersonal" value="<?php echo $idPersonal; ?>">
                                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-danger"><i class="fa fa-trash"></i></button>

                                                                        </form>

                                                                    <?php

                                                                    }
                                                                    ?>


                                                                </center>
                                                            </td>

                                                        </tr>
                                                    <?php

                                                        $numReporte = $numReporte + 1;
                                                    }
                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th>
                                                            <center>N°</center>
                                                        </th>
                                                        <th>
                                                            <center>Nombre<center>
                                                        </th>
                                                        <th>
                                                            <center>Usuario<center>
                                                        </th>
                                                        <th>
                                                            <center>Contrase&ntilde;a<center>
                                                        </th>
                                                        <th>
                                                            <center>Departamento<center>
                                                        </th>
                                                        <?php
                                                        if ($departamentoJefe == "Equipos Industriales" || $departamentoJefe == "equipos industriales") {
                                                        ?>
                                                            <th>
                                                                <center>Sub-Departamento<center>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th>
                                                            <center>Estado<center>
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
                                </div>
                                <!-- /.card-body -->
                                </form>
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
            $('#personalDesktop').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Buscar:",
                    "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                    "zeroRecords": "No se han encontrado Registros.",
                    "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen Registros.",
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