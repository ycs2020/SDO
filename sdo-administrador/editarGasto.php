<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

$idGasto = $_POST['idGasto'];
$idHoja = $_POST['idHoja'];


if ($_POST['idGasto']) {

    $sqlEquipo = "SELECT * FROM hoja_de_vida WHERE idHoja = $idHoja";
    $resEquipo = mysqli_query($conexion, $sqlEquipo);

    while ($row = $resEquipo->fetch_assoc()) {
        $datos = [];

        $datos['nomEquipo'] = $row['nomEquipo'];

        $nomEquipo = $datos['nomEquipo'];
    }

    $sql = "SELECT * FROM gastoEquipos WHERE idGasto = $idGasto";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];

        $datos['nomMantenimiento'] = $row['nomMantenimiento'];
        $datos['idHojaTecnica'] = $row['idHojaTecnica'];
        $datos['tipoMantencion'] = $row['tipoMantencion'];
        $datos['solicitudCompra'] = $row['solicitudCompra'];
        $datos['codOrdenCompra'] = $row['codOrdenCompra'];
        $datos['costoTotal'] = $row['costoTotal'];
        $datos['mesGasto'] = $row['mesGasto'];
        $datos['encargadoMant'] = $row['encargadoMant'];
        $datos['fechaGasto'] = $row['fechaGasto'];


        $nomMantenimiento = $datos['nomMantenimiento'];
        $idHojaTecnica = $datos['idHojaTecnica'];
        $tipoMantencion = $datos['tipoMantencion'];
        $solicitudCompra = $datos['solicitudCompra'];
        $codOrdenCompra = $datos['codOrdenCompra'];
        $costoTotal = $datos['costoTotal'];
        $mesGasto = $datos['mesGasto'];
        $encargadoMant = $datos['encargadoMant'];
        $fechaGasto = $datos['fechaGasto'];

    }
} else {
    header('Location: administrar-equipos.php?noGasto=true');
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Gasto Equipo</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <div class="preloader  flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
        <br>
        <div class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="wrapper">
        <?php
        include '../sdo-templates/admin-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <br>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- general form elements -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h1 class="card-title"> <b> Editar Gastos del equipo - <?php echo $nomEquipo ?></b></h1>
                        </div>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="gastoEquipo.php?idHoja=<?php echo $idHoja ?>" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; <b>Volver Atras</b> </a>
                                    <br>
                                    <br>

                                    <div class="row">
                                        <div class="col-12">

                                            <form action="../sdo-funciones/editarGasto.php" method="POST" enctype="multipart/form-data">

                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Mantenimiento:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm" required id="nomMantenimiento" name="nomMantenimiento" value="<?php echo $nomMantenimiento ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hoja Técnica Asociada:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                                                            </div>
                                                            <select name="idHojaTecnica" id="idHojaTecnica" class="form-control form-control-sm">
                                                                <option value=""   >-- Seleccione --</option>
                                                                <?php
                                                                $sqlOrden = "SELECT * FROM ordenes_trabajos_general ORDER BY idOrden   DESC";
                                                                $resOrden = mysqli_query($conexion, $sqlOrden);

                                                                while ($rowOrden = $resOrden->fetch_assoc()) {
                                                                    $datos = [];
                                                                    $datos['idOrden'] = $rowOrden['idOrden'];
                                                                    $datos['nomOrden'] = $rowOrden['nomOrden'];

                                                                    $idOrden  = $datos['idOrden'];
                                                                    $nomOrden = $datos['nomOrden'];

                                                                    if ($rowOrden['idOrden'] == $idHojaTecnica) {
                                                                ?>
                                                                        <option value="<?php echo $rowOrden['idOrden'] ?>" selected="selected"><?php echo $rowOrden['nomOrden']  ?></option>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <option value="<?php echo $idOrden; ?>"><?php echo $nomOrden ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>

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
                                                                <?php
                                                                if ($tipoMantencion == 'MPE') {
                                                                ?>
                                                                    <option value="MPE" selected="selected">MPE - Mantenimiento Preventivo Externo</option>
                                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                    <option value="OTROS" >OTROS - Tipos de trabajos</option>


                                                                <?php
                                                                }
                                                                if ($tipoMantencion == 'MRE') {
                                                                ?>
                                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                    <option value="MRE" selected="selected">MRE - Mantenimiento Reparativo Externo</option>
                                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                    <option value="OTROS" >OTROS - Tipos de trabajos</option>

                                                                <?php
                                                                }
                                                                if ($tipoMantencion == 'MPI') {
                                                                ?>
                                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                    <option value="MPI" selected="selected">MPI - Mantenimiento Preventivo Interno</option>
                                                                    <option value="MRI">MRI - Mantenimiento Reparativo Interno</option>
                                                                    <option value="OTROS" >OTROS - Tipos de trabajos</option>

                                                                <?php
                                                                }
                                                                if ($tipoMantencion == 'MRI') {
                                                                ?>
                                                                    <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                    <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                    <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                    <option value="MRI" selected="selected">MRI - Mantenimiento Reparativo Interno</option>
                                                                    <option value="OTROS" >OTROS - Tipos de trabajos</option>
                                                                <?php
                                                                }

                                                                if ($tipoMantencion == 'OTROS') {
                                                                    ?>
                                                                        <option value="MPE">MPE - Mantenimiento Preventivo Externo</option>
                                                                        <option value="MRE">MRE - Mantenimiento Reparativo Externo</option>
                                                                        <option value="MPI">MPI - Mantenimiento Preventivo Interno</option>
                                                                        <option value="MRI" >MRI - Mantenimiento Reparativo Interno</option>
                                                                        <option value="OTROS" selected="selected">OTROS - Tipos de trabajos</option>
                                                                    <?php
                                                                    }

                                                                ?>
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

                                                                <option value="" disabled>-- Seleccione --</option>
                                                                <?php
                                                                $sql = "SELECT * FROM solicitudes_compra_sdo ORDER BY idSoliCompra  DESC";
                                                                $res = mysqli_query($conexion, $sql);

                                                                while ($rowSoli = $res->fetch_assoc()) {

                                                                    $datos = [];
                                                                    $datos['idSoliCompra'] = $rowSoli['idSoliCompra'];
                                                                    $datos['personalSoliCompra'] = $rowSoli['personalSoliCompra'];

                                                                    $idSoliCompra = $datos['idSoliCompra'];
                                                                    $personalSoliCompra = $datos['personalSoliCompra'];

                                                                    if ($rowSoli['idSoliCompra'] == $solicitudCompra) {
                                                                ?>
                                                                        <option value="<?php echo $rowSoli['idSoliCompra'] ?>" selected="selected"><?php echo 'N° Interno SDO: ' . $rowSoli['idSoliCompra'] . ' - ' .  $rowSoli['personalSoliCompra'] ?></option>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <option value="<?php echo $idSoliCompra; ?>"><?php echo 'N° Interno SDO: ' . $idSoliCompra . ' - ' .  $personalSoliCompra ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                <?php
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Código Orden Compra:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                                                            </div>
                                                            <input type="text"  min="1" class="form-control form-control-sm"  id="codOrdenCompra" name="codOrdenCompra" value="<?php echo $codOrdenCompra ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <!-- <label for="customFile">Custom File</label> -->
                                                        <label for="exampleInputTareasControlCent" style="font-size:12px;">Subir Orden de Compra:</label><br>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="archivoOC" name="archivoOC">
                                                            <label class="custom-file-label" for="customFile" style="font-size:12px;"><b>Seleccionar Archivo...</b> </label>
                                                            <label for="" style="font-size: 12px; font-weight: bold; color: red">**Subir una nueva orden de compra, elimina la enterior.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Costo Total:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                            </div>
                                                            <input type="number" required min="1" class="form-control form-control-sm" required id="costoTotal" name="costoTotal" value="<?php echo $costoTotal ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="exampleInputCodigoTarjeta" style="font-size:12px">Fecha Gasto:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                            </div>
                                                            <input type="date" name="fechaGasto" id="fechaGasto" required class="form-control form-control-sm" value="<?php echo $fechaGasto ?>">
                                                            <!-- <select name="mesGasto"  required id="mesGasto" class="form-control form-control-sm">
                                                                <option value="" disabled >-- Seleccione --</option>
                                                                <option value="Enero"  <?php echo $selectEnero?>>Enero</option>
                                                                <option value="Febrero" <?php echo $selectFebrero?>>Febrero</option>
                                                                <option value="Marzo" <?php echo $selectMarzo?>>Marzo</option>
                                                                <option value="Abril" <?php echo $selectAbril?>>Abril</option>
                                                                <option value="Mayo" <?php echo $selectMayo?>>Mayo</option>
                                                                <option value="Junio" <?php echo $selectJunio?>>Junio</option>
                                                                <option value="Julio" <?php echo $selectJulio?>>Julio</option>
                                                                <option value="Agosto" <?php echo $selectAgosto?>>Agosto</option>
                                                                <option value="Septiembre" <?php echo $selectSeptiembre?>>Septiembre</option>
                                                                <option value="Octubre" <?php echo $selectOctubre?>>Octubre</option>
                                                                <option value="Noviembre" <?php echo $selectNoviembre?>>Noviembre</option>
                                                                <option value="Diciembre" <?php echo $selectDiciembre?>>Diciembre</option>
                                                            </select> -->
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
                                                                    <input type="text" class="form-control form-control-sm"  id="encargadoMant" name="encargadoMant" value="<?php echo $encargadoMant ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                <div class="row justify-content-center ">
                                                    <input type="hidden" value="<?php echo $idGasto ?>" name="idGasto">
                                                    <input type="hidden" value="<?php echo $idHoja ?>" name="idHoja">
                                                    <button class="btn btn-success " type="submit"> <b> Guardar Cambios</b></button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div><!-- .tabla-responsive -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col  -->
        </div>
        <!-- /.row -->
        </section>
        <!-- /.content -->
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
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap4.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/dark-mode.js"></script>
    <script>
        $(document).ready(function() {
            $('#gasto_equipo').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "scrollX": true,
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
    <script>
        $(document).ready(
            $('.eliminar').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                var dataString = 'id=' + id;
                Swal.fire({
                    icon: 'question',
                    title: '¿Eliminar el registro de este gasto?',
                    text: 'Esta acción no se puede revertir',
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
                            url: '../sdo-funciones/eliminar-gasto-hoja-vida.php',
                            type: 'POST',
                            data: dataString,
                            success: function(response) {
                                console.log('eliminado.. ' + dataString);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro del gasto eliminado correctamente',
                                    showConfirmButton: false,
                                    timer: 2000,
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 2100)
                            }
                        })
                    }
                })
            })
        )
    </script>
</body>

</html>