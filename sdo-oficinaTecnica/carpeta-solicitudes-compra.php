<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $ruta = "sdo-oficinaTecnica";
}else{
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set('America/Santiago');
// echo '';

if (isset($_POST['idCarpeta'])) {
    $idCarpeta = $_POST['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }

    $_SESSION['idCarpeta'] = $idCarpeta;
} else if ($_SESSION['idCarpeta']) {
    $idCarpeta = $_SESSION['idCarpeta'];
    $sqlGeneral = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCarpeta";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomCategoria'] = $row['nomCategoria'];
        $datos['imagenCategoria'] = $row['imagenCategoria'];
        $datos['idDepartamento'] = $row['idDepartamento'];

        $nomCategoria = $datos['nomCategoria'];
        $imagenCategoria = $datos['imagenCategoria'];
        $idDepartamento = $datos['idDepartamento'];
    }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Solicitudes de Compra</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <!-- summernote -->
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/oficinaTecnica-sidebar.php';
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
            <?php
            if (isset($_GET['exitoCrearGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se agrego correctamente el gasto a la carpeta!',
                        'success'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorCrearGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible agregar el gasto a la carpeta!',
                        'error'
                    )
                </script>
                <?php
            }
            if (isset($_GET['exitoEditarGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se edito el gasto correctamente!',
                        'info'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorEditarGasto'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible editar el registro!',
                        'error'
                    )
                </script>
                <?php
            }

            ?>
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="carpeta-equipo.php" style="float: right;" class="btn btn-warning btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Solicitudes de Compra - <?php echo $nomCategoria ?> </h1>
                                        <hr>
                                        <br>
                                        <h5>&nbsp;&nbsp; Colores para Identificar:</h5>
                                        <ul>
                                            <li><i style="color: #FFC107; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Solicitudes de Compra de la carpeta</li>
                                            <li><i style="color: #252783; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Solicitudes de Compra por los Equipos</li>
                                        </ul>
                                        <hr>
                                        <!-- <button class="btn btn-info text-center" data-toggle="modal" data-target=".modalGasto"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Un Gasto A La Carpeta</b> </button> -->
                                        <br>
                                        <!-- <div class="modal fade modalGasto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del gasto</b> </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="../sdo-funciones/guardar-gasto-carpeta.php" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Mantenimiento:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" required id="nomMantenimiento" name="nomMantenimiento">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hoja Técnica Asociada:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                                                                            </div>
                                                                            <select name="idHojaTecnica" id="idHojaTecnica" class="form-control form-control-sm">
                                                                                <option value="" selected disabled>-- Seleccione --</option>
                                                                                <?php
                                        $sql = "SELECT * FROM ordenes_trabajos_general ORDER BY idOrden  DESC";
                                        $res = mysqli_query($conexion, $sql);
                                        while ($rowOrden = $res->fetch_assoc()) {
                                            $datos = [];
                                            $datos['idOrden'] = $rowOrden['idOrden'];
                                            $datos['nomOrden'] = $rowOrden['nomOrden'];
                                            $idOrden  = $datos['idOrden'];
                                            $nomOrden = $datos['nomOrden'];
                                            ?>
                                                                                    <option value="<?php echo $idOrden; ?>"><?php echo  $nomOrden ?></option>
                                                                                <?php
                                        }
                                        ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Mantención:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                            </div>
                                                                            <select name="tipoMantencion" class="form-control form-control-sm" required id="tipoMantencion">
                                                                                <option value="" disabled selected>-- Seleccione --</option>
                                                                                <option value="MPE" >MPE - Mantenimiento Preventivo Externo</option>
                                                                                <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                                <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                                <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                                <option value="OM">OM - Obras Menores</option>
                                                                                <option value="IS">IS - Inspección/Supervisión</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Solicitud de Compra Asociada:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-th-list"></i></span>
                                                                            </div>
                                                                            <select name="ordenCompra" id="ordenCompra" class="form-control form-control-sm">
                                                                                <option value="" selected disabled>-- Seleccione --</option>
                                                                                <?php
                                        $sql = "SELECT * FROM solicitudes_compra_sdo ORDER BY idSoliCompra  DESC";
                                        $res = mysqli_query($conexion, $sql);
                                        while ($rowSoli = $res->fetch_assoc()) {
                                            $datos = [];
                                            $datos['idSoliCompra'] = $rowSoli['idSoliCompra'];
                                            $datos['personalSoliCompra'] = $rowSoli['personalSoliCompra'];

                                            $idSoliCompra = $datos['idSoliCompra'];
                                            $personalSoliCompra = $datos['personalSoliCompra'];
                                            ?>
                                                                                    <option value="<?php echo $idSoliCompra; ?>"><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' .  $personalSoliCompra ?></option>
                                                                                <?php
                                        }

                                        ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Código Orden Compra:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                                            </div>
                                                                            <input type="text" min="1" class="form-control form-control-sm" id="codOrdenCompra" name="codOrdenCompra">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Orden de Compra:</label><br>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="archivoOC" name="archivoOC">
                                                                            <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-8">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Total:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                                            </div>
                                                                            <input type="number" required min="1" class="form-control form-control-sm" id="costoTotal" name="costoTotal">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                                            </div>
                                                                            <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-8">
                                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Encargado Mantenimiento:</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                            </div>
                                                                            <input type="text" class="form-control form-control-sm" id="encargadoMant" name="encargadoMant">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta ?>">
                                                            <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                            <button type="submit" class="btn btn-success"> <b>Añadir Gasto</b> </button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> -->

                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N° Interno SDO</th>
                                                <th scope="col" class="text-center">Fecha de Solicitud </th>
                                                <th scope="col" class="text-center">Personal Solicitante </th>
                                                <th scope="col" class="text-center">Folio de Solicitud </th>
                                                <th scope="col" class="text-center">Estado de Solicitud</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM solicitudes_compra_sdo WHERE idCategoria = $idCarpeta ORDER BY fechaSoliCompra ASC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['idSoliCompra'] = $rowEquipo['idSoliCompra'];
                                                $datos['numSoliCompra'] = $rowEquipo['numSoliCompra'];
                                                $datos['fechaSoliCompra'] = $rowEquipo['fechaSoliCompra'];
                                                $datos['personalSoliCompra'] = $rowEquipo['personalSoliCompra'];
                                                $datos['folioSoliCompra'] = $rowEquipo['folioSoliCompra'];
                                                $datos['estadoSoliCompra'] = $rowEquipo['estadoSoliCompra'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                $idSoliCompra  = $datos['idSoliCompra'];
                                                $numSoliCompra = $datos['numSoliCompra'];
                                                $fechaSoliCompra = $datos['fechaSoliCompra'];
                                                $personalSoliCompra = $datos['personalSoliCompra'];
                                                $folioSoliCompra = $datos['folioSoliCompra'];
                                                $estadoSoliCompra = $datos['estadoSoliCompra'];
                                                $idEquipo = $datos['idEquipo'];

                                                $fechaSoliCompra = date('d/m/Y', strtotime($fechaSoliCompra));

                                                if ($idEquipo == '') {
                                                    $color = '#FFC107';
                                                    $colorText = '#000';
                                                } else{
                                                    $color = '#252783';
                                                    $colorText = 'white';
                                                }


                                                ?>
                                                <tr style="background-color:<?php echo $color ?>;color:<?php echo $colorText ?>;">
                                                    <td class="text-center"><?php echo $numSoliCompra ?></td>
                                                    <td class="text-center"><?php echo $fechaSoliCompra ?></td>
                                                    <td class="text-center"><?php echo $personalSoliCompra ?></td>
                                                    <td class="text-center"><?php echo $folioSoliCompra ?></td>
                                                    <td class="text-center"><?php echo $estadoSoliCompra ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if($idEquipo == 0 || $idEquipo == "" || $idEquipo == " " || $idEquipo == "  "){
                                                            ?>
                                                            <?php echo "-";?>
                                                            <?php

                                                        } else{
                                                            ?>
                                                            <form style="margin: 0; text-align: center;display: inline;" target="_blank" action="equipo.php" method="post">
                                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-sm btn-primary"> <b> <i class="fa fa-eye"></i></i></b></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:middle;" nowrap="nowrap" align="center">

                                                        <?php
                                                        if ($idSoliCompra == 0 || $idSoliCompra == "" || $idSoliCompra == " " || $idSoliCompra == "  ") {
                                                            ?>
                                                            <?php echo "Error al Cargar Datos."; ?>
                                                            <?php
                                                        } else {

                                                            ?>

                                                            <form style="margin: 0; text-align: center;display: inline;"  target="_blank" action="soliCompra_visualizarSC_sdo.php" method="post">

                                                                <input type="hidden" name="idSoliCompra" value="<?php echo $idSoliCompra; ?>">
                                                                <button type="submit" id="inputMozi" class="btn btn-sm btn-primary"> <b> <i class="fa fa-eye"></i></i></b></button>

                                                            </form>

                                                            <?php

                                                        }
                                                        ?>
                                                        <a class="btn btn-sm btn-danger eliminar" id="<?php echo $idSoliCompra ?>"><i class="fas fa-trash-alt"></i></a>



                                                    </td>
                                                </tr>
                                                <?php
                                                $num++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N° Interno SDO</th>
                                                <th scope="col" class="text-center">Fecha de Solicitud </th>
                                                <th scope="col" class="text-center">Personal Solicitante </th>
                                                <th scope="col" class="text-center">Folio de Solicitud </th>
                                                <th scope="col" class="text-center">Estado de Solicitud</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </tfoot>
                                        </table>
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
<script src="../dist/js/adminlte.min.js"></script>
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
<script>
    $(document).ready(function() {
        $('#tabla-hojas-tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 50,
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
    $(document).ready(function() {
        $('#hoja_tecnicas').DataTable({
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
    $(document).ready(
        $('.eliminar').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar la el registro de la solicitud de compra?',
                text: 'Si el registro de la solicitud de compra le pertenece a un equipo, tambien se eliminara del registro',
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
                        url: '../sdo-funciones/eliminar-soliCompra-equipo.php',
                        type: 'POST',
                        data: dataString,
                        success: function(response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro eliminado',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 1800)
                        }
                    })
                }
            })
        })
    )
</script>
</body>

</html>