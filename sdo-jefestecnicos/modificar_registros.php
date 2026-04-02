<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userUsuario = $_SESSION['userUsuario'];
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];


$rolUsuario = $_SESSION['rolUsuario'];

$rolUsuario2 = strtolower($rolUsuario);


date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y H:i');

$rutaDireccion = "";

if ($rolUsuario2 == "personal") {
    $tipoUsuario = 'personal';
    $rutaDireccion = "sdo-personal";
} else if ($rolUsuario2 == "administrativo") {
    $tipoUsuario = 'administrativo';
    $rutaDireccion = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $tipoUsuario = 'bodega pañol';
    $rutaDireccion = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $tipoUsuario = 'jefe tecnico';
    $rutaDireccion = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $tipoUsuario = 'administrador';
    $rutaDireccion = "sdo-administrador";
} else if ($rolUsuario2 == "directiva") {
    $tipoUsuario = 'directiva';
    $rutaDireccion = "sdo-directiva";
} else if ($rolUsuario2 == "oficina tecnica") {
    $tipoUsuario = 'oficina tecnica';
    $rutaDireccion = "sdo-oficinaTecnica";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}


if(isset($_GET['idEquipo'])) {
    $idEquipo = $_GET['idEquipo'];
}

if (isset($_POST['idEquipo']) || isset($_SESSION['idEquipo'])) {

    if (isset($_POST['idEquipo'])) {
        $idEquipo = $_POST['idEquipo'];
        $_SESSION['idEquipo'] = $idEquipo;
    } else if (isset($_SESSION['idEquipo'])) {
        $idEquipo = $_SESSION['idEquipo'];
    } else {
        header('Location: ../' . $rutaDireccion . '/carpeta-equipo.php?errorCargarEquipo=true');
    }

    //$_SESSION['idEquipo'] = $idEquipo;

    $sql = "SELECT * FROM equipamiento_hbqp WHERE id = " . $idEquipo;

    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {

        $fechaRegistro = $row['fechaRegistro'];
        $identificadorUnico = $row['identificadorUnico'];
        $numeroInventario = $row['numeroInventario'];
        $fechaRecepcion = $row['fechaRecepcion'];
        $nombreEquipo = $row['nombreEquipo'];
        $marca = $row['marca'];
        $modelo = $row['modelo'];

        $modeloCadena = $modelo;

        $servicioDB = $row['idServicio'];
        $recinto = $row['recinto'];
        $pma = $row['pma'];
        $coordenadas = $row['coordenadas'];
        $fechaCompra = $row['fechaCompra'];
        $valorConIva = $row['valorConIva'];
        $idLicitacion = $row['idLicitacion'];
        $ordenCompra = $row['ordenCompra'];
        $fechaMarcha = $row['fechaMarcha'];
        $clase = $row['clase'];
        $subclase = $row['subclase'];
        $complejidadTec = $row['complejidadTec'];
        $nivelRiesgo = $row['nivelRiesgo'];
        $AhnoFrabricacion = $row['AhnoFrabricacion'];
        $vidaUtil = $row['vidaUtil'];
        $versionSoftware = $row['versionSoftware'];
        $paisOrigen = $row['paisOrigen'];
        $terminoGarantia = $row['terminoGarantia'];
        $costoMantAnual = $row['costoMantAnual'];
        $frecuenciaMantenimiento = $row['frecuenciaMantenimiento'];
        $numeroSerie = $row['numeroSerie'];
        $piso = $row['piso'];
        $idCategoria = $row['idCategoria'];
        $estadoEquipo = $row['estadoEquipo'];
        $tipoControlador = $row['tipoControlador'];
        $device = $row['device_id'];
        $network = $row['network'];
        $mac = $row['mac_address'];
        $tcc = $row['tcc'];
        $ejesTCC = $row['ejesTCC'];
        $alimentacionTCC = $row['alimentacionTCC'];
        $ejesECBoss = $row['ejesECBoss'];
        $ejesRack = $row['ejesRack'];
        $rack = $row['rack'];
        $puerto = $row['puerto'];
        $direccionIP = $row['direccionIP'];
        $idEquipoPadre = $row['idEquipoPadre'];
        $rutaImagen = $row['rutaImagen'];
        $equipo_controla = $row['equipo_controla'];
        $equipoSource = $row['sourceAlarma'];
        $ahnoAdquisicion = $row['AhnoAdquisicion'];
        $propiedades = $row['propiedades'];
        $evaluaciones = $row['evaluaciones'];
        $nombreProovedor = $row['nombreProovedor'];
        $mantenimientos = $row['mantenimientos'];
        $enGarantia = $row['enGarantia'];
        $vidaUtilResidual = $row['vidaUtilResidual'];
        $bajoPlanMantenimiento = $row['bajoPlanMantenimiento'];
        $idConvenioMantenimiento = $row['idConvenioMantenimiento'];
        $costoAnualMantenimiento = $row['costoAnualMantenimiento'];
        $frecuenciaAnualMantenimiento = $row['frecuenciaAnualMantenimiento'];

        $sql = "SELECT * FROM categorias_equipos WHERE idCategoria = $idCategoria";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['nomCategoria'] = $row['nomCategoria'];
            $nomCategoria = $datos['nomCategoria'];
        }

        $sql = "SELECT COUNT(*) AS cantidadHojasTec FROM ordenes_trabajos_general WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadHojasTec'] = $row['cantidadHojasTec'];
            $cantidadHojasTec = $datos['cantidadHojasTec'];
        }

        $sqlSolicitud = "SELECT COUNT(*) AS total FROM solicitudes_compra_sdo WHERE idEquipo = $idEquipo";
        $resSolicitud = mysqli_query($conexion, $sqlSolicitud);

        while ($rowSolicitud = $resSolicitud->fetch_assoc()) {
            $datosSolicitud = [];
            $datosSolicitud['total'] = $rowSolicitud['total'];
            $total = $datosSolicitud['total'];
        }

        $sql = "SELECT COUNT(*) AS cantidadSubEquipos FROM equipamiento_hbqp WHERE idEquipoPadre = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadSubEquipos'] = $row['cantidadSubEquipos'];
            $cantidadSubEquipos = $datos['cantidadSubEquipos'];
        }

        $sql = "SELECT SUM(costoTotal) AS gastoTotal FROM  gasto_equipo_carpeta WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['gastoTotal'] = $row['gastoTotal'];
            $gastoTotal = $datos['gastoTotal'];
        }

        $sql = "SELECT COUNT(*) AS cantidadArchivos FROM archivos_equipamiento_hbqp WHERE idEquipo = $idEquipo";
        $res = mysqli_query($conexion, $sql);

        while ($row = $res->fetch_assoc()) {
            $datos = [];
            $datos['cantidadArchivos'] = $row['cantidadArchivos'];
            $cantidadArchivos = $datos['cantidadArchivos'];
        }

        $sqlx = "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp";
        $resx = mysqli_query($conexion,$sqlx);
        while($rowx = $resx->fetch_assoc()){
            $ultimo_id = $rowx['ultimo_id'];
            $ultimo_id = $ultimo_id + 1;
        }

        $idGenerado = 'EQUIP-HBQP-' . $ultimo_id;

    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Registros</title>
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
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

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
    <style>
        .input-output-heading {
            font-size: 18px;
            color: #333; /* Cambiar color según tu preferencia */
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <h4>Modificar Registros</h4>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <form method="post" action="equipo.php" class="text-right">
                                    <input type="hidden" name="idEquipo" id="idEquipo" value="<?php echo $idEquipo; ?>"/>
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atrás</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="../sdo-funciones/modificar-registros.php" method="POST">
                                    <table id="miTablaInsumos" class="table table-bordered" style="font-size: 12px;">
                                        <thead class="text-center bg-secondary">
                                        <tr class="text-center">
                                            <th>Fecha</th>
                                            <th>Tipo de Intervencion</th>
                                            <th>Empresa</th>
                                            <th>OC</th>
                                            <th>N° Hoja Servicio Técnico</th>
                                            <th>Realizado</th>
                                            <th>Observaciones</th>
                                            <th>Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM `registros_inge_clinica` WHERE idEquipo = $idEquipo AND habilitado = 0";
                                            $res = mysqli_query($conexion,$sql);
                                            $numModiRegistros = 0; // Contador de filas para el input
                                            while($row = $res->fetch_assoc()){
                                                    $id = $row['id'];
                                                    $numModiRegistros++;
                                                    $fecha = $row['fecha'];
                                                    $tipoIntervencion = $row['tipoIntervencion'];
                                                    $empresa = $row['empresa'];
                                                    $oc = $row['oc'];
                                                    $numeroHojaServicio = $row['numeroHojaServicio'];
                                                    $realizado = $row['realizado'];
                                                    $obs = $row['obs'];

                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="datetime-local" class="form-control" value="<?php echo $fecha?>" id="fecha<?php echo $numModiRegistros?>" name="fecha<?php echo $numModiRegistros?>">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <select class="form-control" id="tipoIntervencion<?php echo $numModiRegistros?>" name="tipoIntervencion<?php echo $numModiRegistros?>">
                                                                <?php
                                                                    if($tipoIntervencion == 'MPPC'){
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="MPPC" selected>MPPC</option>
                                                                        <option value="MPPA">MPPA</option>
                                                                        <option value="PREDEC">PREDEC.</option>
                                                                        <option value="REPARAT">REPARAT.</option>
                                                                        <?php
                                                                    }else if($tipoIntervencion == 'MPPA'){
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="MPPC" >MPPC</option>
                                                                        <option value="MPPA" selected>MPPA</option>
                                                                        <option value="PREDEC">PREDEC.</option>
                                                                        <option value="REPARAT">REPARAT.</option>
                                                                        <?php
                                                                    }else if($tipoIntervencion == 'PREDEC'){
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="MPPC" >MPPC</option>
                                                                        <option value="MPPA" >MPPA</option>
                                                                        <option value="PREDEC" selected>PREDEC.</option>
                                                                        <option value="REPARAT">REPARAT.</option>
                                                                        <?php
                                                                    }else{
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="MPPC" >MPPC</option>
                                                                        <option value="MPPA" >MPPA</option>
                                                                        <option value="PREDEC" >PREDEC.</option>
                                                                        <option value="REPARAT" selected>REPARAT.</option>
                                                                        <?php
                                                                    }
                                                                ?>

                                                            </select>
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" value="<?php echo $empresa?>" id="empresa<?php echo $numModiRegistros?>" name="empresa<?php echo $numModiRegistros?>">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" value="<?php echo $oc?>" id="oc<?php echo $numModiRegistros?>" name="oc<?php echo $numModiRegistros?>">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <input type="text" class="form-control" value="<?php echo $numeroHojaServicio?>" id="numeroServicioTecnico<?php echo $numModiRegistros?>" name="numeroServicioTecnico<?php echo $numModiRegistros?>">
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <select class="form-control" id="realizado<?php echo $numModiRegistros?>" name="realizado<?php echo $numModiRegistros?>">
                                                                <?php
                                                                    if($realizado == 'SI'){
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="SI" selected>SI</option>
                                                                        <option value="NO">NO</option>
                                                                        <?php
                                                                    }else{
                                                                        ?>
                                                                        <option value=""  disabled>Seleccion...</option>
                                                                        <option value="SI" >SI</option>
                                                                        <option value="NO" selected>NO</option>
                                                                        <?php
                                                                    }
                                                                ?>

                                                            </select>
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <textarea class="form-control" rows="10" style="resize: none" name="obs<?php echo $numModiRegistros?>" id="obs<?php echo $numModiRegistros?>"><?php echo $obs?></textarea>
                                                        </td>
                                                        <td style="font-size: 10px; white-space: nowrap; vertical-align: middle; text-align: center">
                                                            <input type="hidden" name="idInsumos<?php echo $numModiRegistros?>" value="<?php echo $id?>">
                                                            <button type="button" class="btn-danger eliminarRegistro" id="<?php echo $id?>">Eliminar</button>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                }
                                            ?>
                                            <input type="hidden" id="cantidadFilasModiRegistros" name="cantidadFilasModiRegistros" value="<?php echo $numModiRegistros; ?>">
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary btn-block">Modificar</button>
                                </form>
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
<script>
    $(document).ready(
        $('.eliminarRegistro').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            var dataString = 'id=' + id;
            Swal.fire({
                icon: 'question',
                title: '¿Eliminar este registro?',
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
                        url: '../sdo-funciones/eliminar-registro.php',
                        type: 'POST',
                        data: dataString,
                        success: function (response) {
                            console.log('eliminado.. ' + dataString);
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro de actividad eliminado con exito',
                                showConfirmButton: false,
                                timer: 2000,
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 2100)
                        }
                    })
                }
            })
        })
    )
</script>
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
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
</body>
</html>
