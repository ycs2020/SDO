<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = trim($_SESSION['nomUsuario']);
$idUsuario = trim($_SESSION['idUsuario']);
$idDepartamento = $_SESSION['idDepartamento'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recepcion Conforme</title>

    <!-- Google Font: Source Sans Pro -->
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
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="js/dark-mode.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            cargarTipoObservacion();

            $('#departamentoNuevoResponsable').change(function() {
                cargarTipoObservacion();
            });


            function cargarTipoObservacion() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-solicitud/cargarTipoSolicitud.php",
                    data: "idDepartamento=" + $('#departamentoNuevoResponsable').val(),
                    success: function(r) {
                        $('#dataTipoSolicitud').html(r);


                    }
                });

            };

        });
    </script>
</head>
<body class="hold-transition sidebar-mini ">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <!-- /.navbar -->

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Recepcion Conforme</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
            <?php
            if($_GET['exitoCrearCategoria']){
                ?>
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "Se ha creado la categoría exitosamente.",
                        icon: "success"
                    });
                </script>
                <?php
            }
            ?>
        </section>
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
                                <br>
                                <div class="text-center">
                                    <button type="button" style="float: right"
                                            title="Agregar una categoria"
                                            class="btn btn-primary btn-sm"
                                            data-toggle="modal"
                                            data-target="#agregarcategoria">
                                        <i class="fas fa-plus"></i> Agregar Categoria
                                    </button>
                                </div>
                                <br><br>
                                <div class="text-center">
                                    <button type="button" style="float: right"
                                            title="Modificar Nombre"
                                            class="btn btn-primary btn-sm"
                                            data-toggle="modal"
                                            data-target="#modificarcategoria">
                                        <i class="fas fa-pencil-alt"></i> Cambiar Nombre de una Categoria
                                    </button>
                                </div>
                                <div class="modal fade" id="agregarcategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h5 class="modal-title" id="exampleModalLabel">Agregar Categoria</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/guardar-categorias-recepcion-conforme.php" method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Nombre Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="nomCategoria" name="nomCategoria">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Departamento:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <select name="idDepartamento" id="idDepartamento" class="form-control form-control-sm">
                                                                <option value="">Seleccione....</option>
                                                                <?php
                                                                if($nomUsuario == 'Marcela Rojas Perez'){
                                                                    ?>
                                                                    <option selected value="7">Logistica</option>
                                                                    <?php
                                                                }else{
                                                                    $sqlDepa = "SELECT * FROM `departamentos` WHERE idDepartamento != 11 AND idDepartamento != 13 AND idDepartamento != 14 AND idDepartamento != 15 ORDER BY idDepartamento DESC";
                                                                    $resDepa = mysqli_query($conexion, $sqlDepa);
                                                                    while($rowDepa = $resDepa->fetch_assoc()){
                                                                        ?>
                                                                        <option value="<?php echo $rowDepa['idDepartamento']?>"><?php echo $rowDepa['nomDepartamento']?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </div>
                                                <input type="hidden" name="nomUsuario" value="<?php echo $nomUsuario; ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modificarcategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-secondary">
                                                <h5 class="modal-title" id="exampleModalLabel">Modificar Categoria</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="../sdo-funciones/modificar-categorias-recepcion-conforme.php" method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Escoja una Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <select name="nombreCategoriaAntigua" id="nombreCategoriaAntigua" class="form-control form-control-sm">
                                                                <option value="" selected disabled>Seleccione.....</option>
                                                                <?php
                                                                if($idUsuario == 5){
                                                                    $sqlCategorias = "SELECT * FROM `categorias_recepcion_conforme` WHERE nomCreador = 'Marcela Rojas Perez' OR idDepartamento = $idDepartamento ORDER BY id DESC";
                                                                }else if($idUsuario == 1){
                                                                    $sqlCategorias = "SELECT * FROM categorias_recepcion_conforme ORDER BY id DESC";
                                                                }else{
                                                                    $sqlCategorias = "SELECT * FROM categorias_recepcion_conforme ORDER BY id DESC";
                                                                }

                                                                $resCategorias = mysqli_query($conexion, $sqlCategorias);
                                                                while ($rowCategorias = $resCategorias->fetch_assoc()) {
                                                                    $idCategoria = $rowCategorias['id'];
                                                                    $nombreCategoria = $rowCategorias['nombre'];
                                                                    echo '<option value="' . $idCategoria . '">' . $nombreCategoria . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 col-12">
                                                        <label style="font-size:12px">Nuevo Nombre de la Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="nombreCategoriaNueva" name="nombreCategoriaNueva">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Modificar</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <div class="card card-success card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <?php
                                            if($idUsuario == 5){
                                                $sql = "SELECT * FROM categorias_recepcion_conforme WHERE nomCreador = 'Marcela Rojas Perez' OR idDepartamento = $idDepartamento ORDER BY id DESC";
                                            }else if($idUsuario == 1){
                                                $sql = "SELECT * FROM categorias_recepcion_conforme ORDER BY id DESC";
                                            }else{
                                                $sql = "SELECT * FROM categorias_recepcion_conforme ORDER BY id DESC";
                                            }

                                            $res = mysqli_query($conexion, $sql);
                                            $first = true;

                                            while ($row = $res->fetch_assoc()) {
                                                $id = $row['id'];

                                                $nombre = $row['nombre'];
                                                ?>
                                                <li class="nav-item" role="presentation">
                                                    <button
                                                        class="nav-link <?php echo $first ? 'active' : ''; ?>"
                                                        id="target<?php echo $id; ?>-tab"
                                                        data-toggle="tab"
                                                        data-target="#target<?php echo $id; ?>"
                                                        type="button"
                                                        role="tab"
                                                        aria-controls="target<?php echo $id; ?>"
                                                        aria-selected="<?php echo $first ? 'true' : 'false'; ?>">
                                                        <?php echo  $nombre; ?>
                                                    </button>
                                                </li>
                                                <?php
                                                $first = false;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent">
                                        <?php
                                        $res = mysqli_query($conexion, $sql);
                                        $first = true;

                                        while ($row = $res->fetch_assoc()) {
                                            $id = $row['id'];
                                            ?>
                                            <div
                                                class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>"
                                                id="target<?php echo $id; ?>"
                                                role="tabpanel"
                                                aria-labelledby="target<?php echo $id; ?>-tab">

                                                <!--Contenido de la categoría <?php echo $id; ?>-->
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-3 col-md-6 col-12 text-center">
                                                        <button class="btn bg-gradient-info btn-md w-100">
                                                            <span class="text-white fw-bold">Saldo Disponible:</span><br>
                                                            <?php
                                                            $sqlSaldoDisponible = "
                SELECT *
                FROM datos_montos_recepcion_conforme
                WHERE idCategoria = $id
                ORDER BY id DESC
                LIMIT 1
            ";
                                                            $resSaldoDisponible = mysqli_query($conexion,$sqlSaldoDisponible);

                                                            if ($rowSaldoDisponible = $resSaldoDisponible->fetch_assoc()) {
                                                                echo '<span class="text-white">$ ' . number_format($rowSaldoDisponible['saldoDisponible'], 0, ',', '.') . '</span>';
                                                            } else {
                                                                echo '<span class="text-white">$ 0</span>';
                                                            }
                                                            ?>
                                                        </button>
                                                    </div>
                                                </div>

                                                <br>
                                                <br>
                                                <form action="../sdo-mesa-ayuda/detalles-categorias-recepciones-conformes.php" method="POST" target="_blank">
                                                    <button type="submit" class="btn btn-block bg-success">Visualizar</button>
                                                    <input type="hidden" name="idCategoria" value="<?php echo $id; ?>">
                                                </form>
                                            </div>
                                            <?php
                                            $first = false;
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

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

</body>
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
</html>


