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
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}

date_default_timezone_set('America/Santiago');
// echo '';

if(isset($_POST['ruta'])){
    // Ruta del archivo en el servidor
    $rutaArchivo = $_POST['ruta'];
    // Verificar si el archivo existe
    if(file_exists($rutaArchivo)){
        // Establecer las cabeceras para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($rutaArchivo));
        readfile($rutaArchivo);
        exit;
    }else{
        echo "El archivo no existe.";
    }
}

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
    <title>Carpeta - Archivos</title>
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            recargarNomDepartamento();

            $('#tipoArchivo').change(function() {
                recargarNomDepartamento();
            });

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-archivos/habilitarIngresoArchivo.php",
                    data: "id=" + $('#tipoArchivo').val(),
                    success: function(r) {
                        $('#visualizarNomTipo').html(r);
                    }
                });
            };
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/directiva-sidebar.php';

    if (isset($_GET['exitoSubirArchivos'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se creo subio correctamente el archivo adjunto!',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorSubirArchivos'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Ha ocurrido un problema al subir el archivo!',
                'error'
            )
        </script>
        <?php
    }

    if (isset($_GET['exitoEditar'])) {
        ?>
        <script>
            Swal.fire(
                'Exito!',
                'Se edito el registro correctamente!',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorEditar'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Hubo un problema al editar el registro!',
                'error'
            )
        </script>
        <?php
    }

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
                            <div class="card-header bg-pink">
                                <a href="carpeta-equipo.php" style="float: right;" class="btn btn-warning btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Archivos Adjuntos - <?php echo $nomCategoria ?> </h1>
                                        <hr>
                                        <br>
                                        <button class="btn btn-info text-center" data-toggle="modal" data-target=".modalGasto"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir Un Nuevo Archivo</b> </button>
                                        <br>
                                        <div class="modal fade modalGasto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title" id="exampleModalLabel"> <b>Formulario - Información del archivo</b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-funciones/guardar-archivos-carpeta.php" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="idCarpeta" id="idCarpeta" value="<?php echo $idCarpeta ?>">
                                                            <input type="hidden" name="nomCarpeta" id="nomCarpeta" value="<?php echo $nomCategoria ?>">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Nombre Archivo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm" required id="nomArchivo" name="nomArchivo">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Tipo Archivo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                                        </div>
                                                                        <select  class="form-control form-control-sm" required id="tipoArchivo" name="tipoArchivo">
                                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                                            <?php
                                                                            $sql = "SELECT * FROM  tiposarchivos";
                                                                            $res = mysqli_query($conexion, $sql);
                                                                            while($row = $res->fetch_assoc()){
                                                                                $datos = [];
                                                                                $datos['id '] = $row['id'];
                                                                                $datos['nomTipo'] = $row['nomTipo'];
                                                                                $id  = $datos['id'];
                                                                                $nomTipo = $datos['nomTipo'];
                                                                                ?>
                                                                                <option value="<?php echo $nomTipo ?>"><?php echo mb_convert_case( $nomTipo, MB_CASE_UPPER, 'UTF-8')  ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <option value="Otro">Otro</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div name="visualizarNomTipo" id="visualizarNomTipo" class="form-group col-md-12 col-12">

                                                                </div>
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Seleccione equipo:</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-hdd"></i></span>
                                                                        </div>
                                                                        <select  class="form-control form-control-sm" required id="equipoHijo" name="equipoHijo">
                                                                            <option disabled selected>-- Seleccione --</option>
                                                                            <?php
                                                                            $sql = "SELECT * FROM equipamiento_hbqp ";
                                                                            $res = mysqli_query($conexion, $sql);

                                                                            while($row = $res->fetch_assoc()){
                                                                                $datos = [];
                                                                                $datos['id'] = $row['id'];
                                                                                $datos['nombreEquipo'] = $row['nombreEquipo'];
                                                                                $datos['pma'] = $row['pma'];
                                                                                $datos['recinto'] = $row['recinto'];
                                                                                $id = $datos['id'];
                                                                                $nombreEquipo = $datos['nombreEquipo'];
                                                                                $pma = $datos['pma'];
                                                                                $recinto = $datos['recinto'];
                                                                                ?>
                                                                                <option value="<?php echo $id ?>"><?php echo $nombreEquipo . ' - ' . $pma . ' - ' . $recinto ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12 col-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Adjunto el archivo:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <input type="file" class="form-control form-control-sm" required id="archivoAdjunto" name="archivoAdjunto">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCarpeta ?>">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" class="btn btn-success"> <b>Añadir Archivo</b> </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <table id="tabla-hojas-tecnicas" class="table table-hover  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Nombre Archivo</th>
                                                <th scope="col" class="text-center">Tipo de Archivo</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Descargar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sqlEquipo = "SELECT * FROM archivos_equipamiento_hbqp WHERE idCategoria = $idCarpeta ORDER BY fecha DESC";
                                            $resEquipo = mysqli_query($conexion, $sqlEquipo);
                                            $num = 1;
                                            while ($rowEquipo = $resEquipo->fetch_assoc()) {
                                                $datos = [];
                                                $datos['id'] = $rowEquipo['id'];
                                                $datos['fecha'] = $rowEquipo['fecha'];
                                                $datos['nomArchivo'] = $rowEquipo['nomArchivo'];
                                                $datos['rutaArchivo'] = $rowEquipo['rutaArchivo'];
                                                $datos['tipoArchivo'] = $rowEquipo['tipoArchivo'];
                                                $datos['idEquipo'] = $rowEquipo['idEquipo'];

                                                $id  = $datos['id'];
                                                $fecha = $datos['fecha'];
                                                $nomArchivo = $datos['nomArchivo'];
                                                $rutaArchivo = $datos['rutaArchivo'];
                                                $tipoArchivo = $datos['tipoArchivo'];
                                                $idEquipo = $datos['idEquipo'];

                                                $fecha = date('d/m/Y H:i', strtotime($fecha));

                                                if ($idEquipo == 0) {
                                                    $idEquipo = '-';
                                                }

                                                //TRAE RUTA
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $num ?></td>
                                                    <td class="text-center"><?php echo $fecha ?></td>
                                                    <td class="text-center"><?php echo $nomArchivo ?></td>
                                                    <td class="text-center"><?php echo $tipoArchivo ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($idEquipo == 0) {
                                                            $idEquipo = '-';
                                                        }else{
                                                            ?>
                                                            <form action="equipo.php" method="POST">
                                                                <input type="hidden" name="idEquipo" value="<?php echo $idEquipo ?>">
                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCarpeta ?>">
                                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">

                                                        <form action="carpeta-archivos.php" method="POST" style="display: inline">
                                                            <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i></button>
                                                        </form>

                                                        <form action="editar-archivo-carpeta.php" method="POST" style="display: inline">
                                                            <input type="hidden" value="<?php echo $nomCategoria ?>" name="nomCarpeta">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                                                            <input type="hidden" value="carpeta" name="direccion">
                                                            <button type="submit"  class="btn btn-warning btn-sm "  ><i class="fas fa-pen"></i></button>
                                                        </form>

                                                        <button class="btn btn-danger btn-sm eliminar" id="<?php echo $id ?>"><i class="fas fa-trash-alt"></i></button>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num++;
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Fecha</th>
                                                <th scope="col" class="text-center">Nombre Archivo</th>
                                                <th scope="col" class="text-center">Tipo de Archivo</th>
                                                <th scope="col" class="text-center">Equipo</th>
                                                <th scope="col" class="text-center">Descargar</th>
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
<script>
    $(".abrirmodal").click(function(){
        //Capturamos el valor del id para enviarlo al modal
        let id = $(this).attr('id');
        //Pasamos el id al campo input hiddien del modal.
        $("#idParaEditar").val(id);
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtener-datos-registro-archivo.php',
            data: {
                'id': id
            },
            success: function(response) {
                console.log(response);
                var datos = JSON.parse(response);

                elementos.fecha = datos.fecha;
                elementos.nomArchivo = datos.nomArchivo;
                elementos.rutaArchivo = datos.rutaArchivo;

                console.log(elementos.folio);
            }
        })

    });

</script>
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
                title: '¿Eliminar el archivo adjunto?',
                text: 'Si el registro del archivo adjunto le pertenece a un equipo, tambien se eliminara del registro',
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
                        url: '../sdo-funciones/eliminar-archivo-carpeta.php',
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