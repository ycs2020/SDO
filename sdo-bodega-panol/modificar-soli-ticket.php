<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $idSoli = $_POST['idSoli'];

    //TRAE LOS DATOS DE LA SOLICITUD

    $sql = "SELECT * FROM solicitudes_arriendo_retiro WHERE idSoli = ${idSoli}";

    // var_dump($sql);
    // exit;

    $resSQL = mysqli_query($conexion, $sql);

    while ($rowSQL = $resSQL->fetch_assoc()) {

        $datos = [];
        $datos['codSolicitud'] = $rowSQL['codSolicitud'];
        $datos['idSolicitante'] = $rowSQL['idSolicitante'];
        $datos['idDepaSolicitante'] = $rowSQL['idDepaSolicitante'];

        $codSolicitud = $datos['codSolicitud'];
        $idSolicitante = $datos['idSolicitante'];
        $idDepaSolicitante = $datos['idDepaSolicitante'];
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso Herramienta</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/estilos.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            recargarNomCategoria();



            $('#categoriaOrden').change(function() {
                recargarNomCategoria();
            });


            function recargarNomCategoria() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-categorias/habilitarIngresoCategoria.php",
                    data: "idCategoria=" + $('#categoriaOrden').val(),
                    success: function(r) {
                        $('#visualizarNomCategoria').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini dark-mode" id="body">

    <?php

    if (isset($_GET['eliminadoHerra'])) {

    ?>

        <script>
            Swal.fire({
                icon: 'success',
                title: 'Exito',
                text: 'Herramienta Eliminada',

            })
        </script>
    <?php

    }

    ?>

    <div class="preloader  flex-column justify-content-center align-items-center">

        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>

    </div>

    <div class="wrapper">
        <?php
        include '../sdo-templates/bodega-panol-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Modificación Solicitudes</h1>
                        </div>
                        <div class="col-sm-6">


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

                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Información Modificación Solicitudes</h3>
                        </div>
                        <div class="row">



                            <!-- left column -->
                            <div class="col-md-12">



                                <!-- /.card-header -->
                                <div class="card-body">

                                    <a href="listado-soli-ticket.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>

                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Informaci&oacute;n General de la Solicitud:</legend>



                                        <div class="form-row">
                                            <div class="form-group col-md-6">

                                                <label for="codigoBarra">Código de Solicitud:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                    </div>
                                                    <input type="text" required readonly style="text-transform:capitalize;cursor:not-allowed" class="form-control" value="<?php echo $codSolicitud ?>" id="codSolicitud" name="codSolicitud">
                                                </div>

                                            </div>


                                        </div>

                                        <div class="form-row">


                                            <!-- <div class="form-group col-md-6">
                                                        <label for="exampleInputNomTarjeta">Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                            </div> -->
                                            <!-- <input type="text" required style="text-transform:capitalize" class="form-control" id="rutTarjeta" name="categoria" placeholder="Categoria de la herramienta"> -->
                                            <!-- <select id="categoriaOrden" required class="form-control" name="categoriaOrden">
                                                                <option value="" disabled selected>--Seleccione--</option>
                                                                <?php

                                                                $queryCategorias //= "SELECT * FROM categorias_herramienta ORDER BY nomCategoria ASC";

                                                                //$resCategorias = mysqli_query($conexion, $queryCategorias);

                                                                //while ($rowCategorias = $resCategorias->fetch_assoc()) {

                                                                ?>
                                                                    <option value="<?php //echo $rowCategorias['idCategoria']; 
                                                                                    ?>">
                                                                        <?php //echo $rowCategorias['nomCategoria']; 
                                                                        ?>
                                                                    </option>
                                                                <?php

                                                                //}


                                                                ?>
                                                                <option value="Otro">
                                                                    Otro
                                                                </option>
                                                            </select>
                                                        </div>

                                                      
                                                    </div>
                                                    <div name="visualizarNomCategoria" id="visualizarNomCategoria" class="form-group col-md-6">
                                                        
                                                    </div> -->


                                        </div>

                                        <div class="form-row">

                                            <div class="form-group col-md-6">
                                                <label for="departamento">Departamento:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <?php


                                                    $sqlDepa = "SELECT * FROM departamentos WHERE idDepartamento = ${idDepaSolicitante}";
                                                    $resDepa = mysqli_query($conexion, $sqlDepa);

                                                    while ($rowDepa = $resDepa->fetch_assoc()) {

                                                        $datosDepa = [];

                                                        $datosDepa['nomDepartamento'] = $rowDepa['nomDepartamento'];

                                                        $nomDepartamento = $datosDepa['nomDepartamento'];
                                                    }


                                                    ?>

                                                    <input type="text" readonly style="cursor: not-allowed;" class="form-control" value="<?php echo $nomDepartamento ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="departamento">Personal Solicitante:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <?php


                                                    $sqlSolicitante = "SELECT * FROM jefes_tecnicos WHERE idJefe   = ${idSolicitante}";
                                                    $resSolicitante = mysqli_query($conexion, $sqlSolicitante);

                                                    while ($rowSolicitante = $resSolicitante->fetch_assoc()) {

                                                        $datosSolicitante = [];

                                                        $datosSolicitante['nomJefe'] = $rowSolicitante['nomJefe'];

                                                        $nomPersonal = $datosSolicitante['nomJefe'];
                                                    }


                                                    ?>

                                                    <input type="text" readonly style="cursor: not-allowed;" class="form-control" value="<?php echo $nomPersonal ?>">
                                                </div>
                                            </div>


                                        </div>

                                    </fieldset>
                                    <br>

                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Modificar Herramientas o Materiales Actuales</legend>

                                        <h2 class="text-center">Herramientas:</h2>


                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>

                                                    <th scope="col" class="text-center">CÓDIGO</th>
                                                    <th scope="col" class="text-center">NOMBRE</th>
                                                    <th scope="col" class="text-center">IMAGEN</th>
                                                    <th scope="col" class="text-center">CANTIDAD</th>
                                                    <th scope="col" class="text-center">ACCIONES</th>

                                                </tr>
                                            </thead>
                                            <?php

                                            $queryHerramientas = "SELECT * FROM herra_en_arriendo WHERE idSolicitud = ${idSoli}";
                                            $res1 = mysqli_query($conexion, $queryHerramientas);


                                            foreach ($res1 as $indice => $herramienta) :

                                                $idArriendo = $herramienta['idArriendo'];
                                                $idHerramienta = $herramienta['idHerramienta'];
                                                $sqlHerramienta = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
                                                $resHerramienta = mysqli_query($conexion, $sqlHerramienta);

                                                while ($rowHerramienta = $resHerramienta->fetch_assoc()) {

                                                    $imagen = $rowHerramienta['imagen'];
                                                }


                                            ?>
                                                <tbody>
                                                    <tr>

                                                        <td class="text-center"><?php echo $herramienta['codHerramienta'] ?></td>
                                                        <td class="text-center"><?php echo $herramienta['nomHerramienta'] ?></td>
                                                        <td class="text-center"><img src="img/<?php echo $imagen; ?>" width="100px" alt="imagen herramienta"></td>
                                                        <td class="text-center"><?php echo $herramienta['cantidadHerramienta'] ?></td>

                                                        <?php

                                                        $cantidadSacar = $herramienta['cantidadHerramienta'];

                                                        ?>

                                                        <td class="text-center">

                                                            <form action="../sdo-funciones/eliminar-herramientas-ticket.php" method="POST">

                                                                <input type="hidden" name="idArriendo" value="<?php echo $idArriendo ?>">
                                                                <input type="hidden" name="idHerramienta" value="<?php echo $idHerramienta ?>">
                                                                <input type="hidden" name="cantidadHerramienta" value="<?php echo $cantidadSacar ?>">



                                                                <input class="btn btn-danger" value="Eliminar" type="submit"></input>
                                                            </form>

                                                        </td>

                                                    </tr>
                                                </tbody>

                                            <?php
                                            endforeach;
                                            ?>
                                        </table>


                                        <h2 class="text-center">Materiales:</h2>

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
                                                    <th scope="col" class="text-center">ACCIONES</th>
                                                </tr>
                                            </thead>

                                            <?php


                                            foreach ($res3 as $indice => $material) :

                                                $idArriendo = $material['idMateRetirado'];
                                                $idMaterial = $material['idMaterial'];
                                                $sqlMaterial = "SELECT * FROM pañol_materiales WHERE idMaterial = ${idMaterial}";
                                                $resMaterial = mysqli_query($conexion, $sqlMaterial);

                                                while ($rowMaterial = $resMaterial->fetch_assoc()) {

                                                    $imagen = $rowMaterial['imagen'];
                                                }
                                            ?>
                                                <tbody>
                                                    <tr>

                                                        <td class="text-center"><?php echo $material['codMaterial'] ?></td>
                                                        <td class="text-center"><?php echo $material['nomMaterial'] ?></td>
                                                        <td class="text-center"><img src="img/<?php echo $imagen; ?>" width="100px" alt="imagen material"></td>
                                                        <td class="text-center"><?php echo $material['cantidadMaterial'] ?></td>
                                                        
                                                        <?php

                                                        $cantidadSacar = $material['cantidadMaterial'];

                                                        ?>

                                                        <td class="text-center">

                                                            <form action="../sdo-funciones/eliminar-materiales-ticket.php" method="POST">

                                                                <input type="hidden" name="idArriendo" value="<?php echo $idArriendo ?>">
                                                                <input type="hidden" name="idMaterial" value="<?php echo $idMaterial ?>">
                                                                <input type="hidden" name="cantidadMaterial" value="<?php echo $cantidadSacar ?>">
                                                                <input type="hidden" name="codSolicitud" value="<?php echo $codSolicitud?>">



                                                                <input class="btn btn-danger" value="Eliminar" type="submit"></input>
                                                            </form>

                                                        </td>

                                                    </tr>
                                                </tbody>

                                            <?php
                                            endforeach;


                                            ?>
                                        </table>


                                    </fieldset>

                                    <br>
                                    <div class="form-group">
                                        <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario; ?>">
                                        <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="<?php echo $nomUsuario; ?>" />
                                        <input type="submit" class="btn btn-block btn-primary" value="Guardar" style="font-weight: bold;" />
                                    </div>
                                    <div class="form-group">
                                        <a href="listado-soli-ticket.php" class="btn btn-block btn-warning" style="font-weight: bold;"> Volver </a>
                                    </div>



                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col  -->



                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/deshabilita.js"></script>
</body>

</html>