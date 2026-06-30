<?php
include '../sdo-funciones/conexion.php';

include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$descargaConfirma = "";
$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];



date_default_timezone_set('America/Santiago');


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado del Criogenico</title>

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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <!-- include summernote css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- include summernote js-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

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
        $(document).ready(function() {


            // Show div by removing inline display none style rule
            $(".show-realizar").click(function() {
                $("#rowMenu2").show();
                $("#rowMenu1").hide();
                $("#listarAC").show();
                $("#registrarAC").hide();
            });

            // Show div by removing inline display none style rule
            $(".show-listar").click(function() {
                $("#rowMenu2").hide();
                $("#rowMenu1").show();
                $("#listarAC").hide();
                $("#registrarAC").show();
            });

            $(".show-verdash").click(function() {
                $("#verDash").show();
                $("#rowMenu1").hide();
                $("#rowMenu2").hide();
                $("#verDashAC").hide();
                $("#ocultarDashAC").show();
                $("#ocultarDashh").show();
            });

            $(".show-ocultardash").click(function() {
                $("#rowMenu1").show();
                $("#rowMenu2").hide();
                $("#verDashAC").show();
                $("#ocultarDashAC").hide();
                $("#ocultarDashh").hide();
                $("#verDash").hide();
            });
        });
    </script>
    <!-- <script>
         function DoCheckUncheckDisplay(d, dchecked, dunchecked) {
             if (d.checked == true) {
                 document.getElementById(dchecked).style.display = "block";
                 document.getElementById(dunchecked).style.display = "none";
             } else {
                 document.getElementById(dchecked).style.display = "none";
                 document.getElementById(dunchecked).style.display = "block";
             }
         }
     </script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            recargarSubDepartamento();
            recargarNomDepartamento();

            $('#departamentoSolicitante').change(function() {
                recargarSubDepartamento();
            });

            $('#servicioOrden').change(function() {
                recargarNomDepartamento();
            });

            $('#selectnCliente').change(function() {
                agregarCliente();
            });

            function recargarSubDepartamento() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-departamentos/mostrarSubDepartamentos.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {
                        $('#visualizarSubDepartamento').html(r);
                    }
                });

            };

            function recargarNomDepartamento() {
                $.ajax({
                    type: "POST",
                    url: "sdo-datos-servicios/habilitarIngresoServicio.php",
                    data: "idServicio=" + $('#servicioOrden').val(),
                    success: function(r) {
                        $('#visualizarNomServicio').html(r);
                    }
                });
            };

            function agregarCliente() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-cliente/ingresarCliente.php",
                    data: "idCliente=" + $('#selectnCliente').val(),
                    success: function(r) {
                        $('#visualizarCliente').html(r);
                    }
                });

            };
        });
    </script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Administracion de Consumo del Criogenico</h4>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <?php
            if(isset($_GET['exitoEdicion'])){
                ?>
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "Se ha modificado el registro con exito!",
                        icon: "success"
                    });
                </script>
                <?php
            }

            if(isset($_GET['exito'])){
                ?>
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "Se ha ingresado el registro con exito!",
                        icon: "success"
                    });
                </script>
                <?php
            }

            if(isset($_GET['errorEdicion']) || isset($_GET['errorGeneralEdicion'])){
                ?>
                <script>
                    Swal.fire({
                        title: "Error!",
                        text: "Ha ocurrido un error en la modificacion!",
                        icon: "error"
                    });
                </script>
                <?php
            }
            if(isset($_GET['error']) || isset($_GET['errorGeneral'])){
                ?>
                <script>
                    Swal.fire({
                        title: "Error!",
                        text: "Ha ocurrido un error en el ingreso!",
                        icon: "error"
                    });
                </script>
                <?php
            }
            ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="consumos_basicos.php" style="float: right;" class="btn bg-gradient-danger btn-md text-white text-center"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>


                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="text-align:center;">
                                <?php
                                if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Haroll Álvarez Barrera' || $nomUsuario == 'Carola Avila' || $nomUsuario == 'Pablo Sanchez Molina'){
                                    ?>
                                    <button class="btn bg-yellow show-realizar" id="registrarAC" name="registrarAC"><i class="fas fa-clipboard"></i>&nbsp;Registrar Cargas del Criogenico</button>
                                    <button class="btn bg-yellow show-listar" id="listarAC" style="display:none;" name="listarAC"><i class="fas fa-list"></i>&nbsp;Ver Cargas del Criogenico</button>
                                    <button class="btn bg-green" data-toggle="modal" data-target="#obsGenerales" id="verObs" name="verObs">
                                        <i class="fas fa-eye"></i> Ver Observaciones
                                    </button>
                                    <button class="btn bg-blue show-verdash" id="verDashAC" name="verDashAC">
                                        <i class="fas fa-chart-line"></i>&nbsp;Ver Dashboard
                                    </button>
                                    <button class="btn bg-blue show-ocultardash" style="display: none" id="ocultarDashAC" name="ocultarDashAC">
                                        <i class="fas fa-chart-line"></i>&nbsp;Ocultar DashBoard
                                    </button>
                                    <?php
                                }
                                ?>
                                <input type="hidden" id="nomUsuario" name="nomUsuario" value="<?php echo $nomUsuario; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>1.- Listado de Cargados del Criogenico</h5>
                                <hr>
                                <br>
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>OC</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Factura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>M<sup>3</sup> Cargados</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Precio Unitario</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Con IVA</center>
                                            </th>
                                            <?php
                                            if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Carola Avila' || $nomUsuario == 'Haroll Álvarez Barrera' || $nomUsuario == 'Pablo Sanchez Molina') {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Acciones</center>
                                                </th>
                                                <?php
                                            }
                                            ?>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha<br>del<br>Documento</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>OC</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>N° de Factura</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>M<sup>3</sup> Cargados</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Precio Unitario</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Neto</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Valor Total Con IVA</center>
                                            </th>
                                            <?php
                                            if($nomUsuario == 'Guillermo Hidalgo' || $nomUsuario == 'Matias Gutierrez' || $nomUsuario == 'Carola Avila' || $nomUsuario == 'Haroll Álvarez Barrera' || $nomUsuario == 'Pablo Sanchez Molina') {
                                                ?>
                                                <th style="vertical-align:middle;">
                                                    <center>Acciones</center>
                                                </th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowMenu2" name="rowMenu2" style="display:none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>2.- Registrar Carga del Criogenico </h5>
                                <hr>
                                <form action="../sdo-funciones/criogenico_nuevo_consumo.php" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Fecha: <code>*</code></label>
                                            <input type="month" class="form-control" name="fecha" id="fecha" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>OC: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input class="form-control" required id="oc" placeholder="Orden De Compra" type="text" name="oc" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>N° Factura: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input class="form-control" required id="nFactura" placeholder="Número de Factura" type="text" name="nFactura" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>M<sup>3</sup> Cargados: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input class="form-control" required id="mConsumidos" placeholder="Metros Cubicos Cargados" type="text" name="mConsumidos" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Precio Unitario: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input class="form-control" required id="precioUnitario" placeholder="Precio Unitario" type="number" name="precioUnitario" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Valor Total Neto: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input class="form-control" required id="valorTotalNeto" placeholder="Valor Total Neto" type="number" name="valorTotalNeto" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Valor Total con IVA: <code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                                </div>
                                                <input class="form-control" required id="valorTotalIVA" placeholder="Valor Total con IVA" type="number" name="valorTotalIVA" />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <button type="submit" class="form-control btn btn-success btn-lg">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="verDash" style="display:none;">
                    <div class="col-md-12">
                        <div class="card shadow-sm">

                            <div class="card-header">
                                <ul class="nav bg-dark p-2 rounded">

                                    <?php
                                    $sqlBuscarAño = "SELECT DISTINCT YEAR(fecha) AS anio
                                     FROM criogenico_datos
                                     ORDER BY anio DESC";

                                    $resBuscarAño = mysqli_query($conexion,$sqlBuscarAño);

                                    while($rowBuscarAño = mysqli_fetch_assoc($resBuscarAño)){

                                        $anio = $rowBuscarAño['anio'];

                                        echo "
                                            <li class='nav-item'>
                                                <a href='#'
                                                   class='nav-link text-white fw-bold anio-link'
                                                   data-anio='$anio'>
                                                    $anio
                                                </a>
                                            </li>";
                                    }
                                    ?>

                                </ul>
                            </div>

                            <div class="card-body">

                                <!-- KPI -->
                                <div class="row mb-4">

                                    <div class="col-md-3">
                                        <div class="card border-0 bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h6>Total Cargados</h6>
                                                <h2 id="totalM3">0</h2>
                                                <small>M³ Cargados</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card border-0 bg-success text-white">
                                            <div class="card-body text-center">
                                                <h6>Total Facturado</h6>
                                                <h2 id="totalFacturado">$0</h2>
                                                <small>Con Iva</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card border-0 bg-warning">
                                            <div class="card-body text-center">
                                                <h6>Facturas</h6>
                                                <h2 id="totalFacturas">0</h2>
                                                <small>Emitidas</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card border-0 bg-danger text-white">
                                            <div class="card-body text-center">
                                                <h6>Promedio M³</h6>
                                                <h2 id="promedioM3">0</h2>
                                                <small>Por Factura</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- GRÁFICOS -->
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Carga Mensual (M³)
                                            </div>
                                            <div class="card-body">
                                                <canvas id="graficoConsumo" height="120"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                Facturación Mensual
                                            </div>
                                            <div class="card-body">
                                                <canvas id="graficoFacturacion" height="120"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- TABLA RESUMEN -->
                                <div class="row mt-4">

                                    <div class="col-md-12">

                                        <div class="card">

                                            <div class="card-header">
                                                Detalle Mensual
                                            </div>

                                            <div class="card-body">

                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Mes</th>
                                                        <th>M³ Cargados</th>
                                                        <th>Total Neto</th>
                                                        <th>Total IVA</th>
                                                        <th>Facturas</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody id="tablaDetalleMeses">
                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
<script>
    let chartConsumo = null;
    let chartFacturacion = null;

    $(document).on('click', '.anio-link', function(e){

        e.preventDefault();

        let anio = $(this).data('anio');

        $.ajax({
            url: '../sdo-includes/obtener_datos.php',
            type: 'POST',
            dataType: 'json',
            data: {
                anio: anio
            },
            success: function(resp){

                // KPIs
                $('#totalM3').text(
                    Number(resp.totalM3).toLocaleString('es-CL')
                );

                $('#totalFacturado').text(
                    '$' + Number(resp.totalGastoIva).toLocaleString('es-CL')
                );

                $('#totalFacturas').text(
                    resp.facturas
                );

                $('#promedioM3').text(
                    Number(resp.promedioM3).toLocaleString('es-CL')
                );

                // Tabla
                let filas = '';

                resp.detalle.forEach(function(item){

                    filas += `
                        <tr>
                            <td>${item.mes}</td>
                            <td>${Number(item.m3).toLocaleString('es-CL')}</td>
                            <td>$${Number(item.neto).toLocaleString('es-CL')}</td>
                            <td>$${Number(item.iva).toLocaleString('es-CL')}</td>
                            <td>${item.facturas}</td>
                        </tr>
                    `;
                });

                $('#tablaDetalleMeses').html(filas);

                // Gráfico consumo
                if(chartConsumo){
                    chartConsumo.destroy();
                }

                chartConsumo = new Chart(
                    document.getElementById('graficoConsumo'),
                    {
                        type: 'bar',
                        data: {
                            labels: resp.labels,
                            datasets: [{
                                label: 'M³ Consumidos',
                                data: resp.consumos,
                                backgroundColor: '#0d6efd'
                            }]
                        }
                    }
                );

                // Gráfico facturación
                if(chartFacturacion){
                    chartFacturacion.destroy();
                }

                chartFacturacion = new Chart(
                    document.getElementById('graficoFacturacion'),
                    {
                        type: 'line',
                        data: {
                            labels: resp.labels,
                            datasets: [{
                                label: 'Gasto con IVA',
                                data: resp.gastos,
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25,135,84,0.2)',
                                fill: true,
                                tension: 0.3
                            }]
                        }
                    }
                );

            }
        });

    });
</script>
<script>
    $(document).ready(function(){
        $('.anio-link:first').trigger('click');
    });
</script>
<script>
    $(function() {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": false,

            "ajax": {
                "url": "/sdo-includes/datos_criogenico.php",
                "type": "POST",
                "data": function(d) {
                    d.nomUsuario = $('#nomUsuario').val();
                },
                "dataSrc": "data"
            },

            "columns": [
                { "data": "fecha" },
                { "data": "oc" },
                { "data": "nFactura" },
                { "data": "metrosConsumidos" },
                { "data": "precioUnitario" },
                { "data": "valorTotal" },
                { "data": "valorTotalConIVA" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        const usuariosPermitidos = [
                            'Guillermo Hidalgo',
                            'Matias Gutierrez',
                            'Haroll Álvarez Barrera',
                            'Pablo Sanchez Molina'
                        ];

                        let botones = '';
                        if (usuariosPermitidos.includes(row.nomUsuario)) {
                            botones = `
                                    <div class="d-flex justify-content-center">
                                        <button title="Agregar Observacion" class="btn btn-warning" data-toggle="modal" data-target="#modificar${row.id}">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <button title="Agregar Observacion" class="btn btn-success" data-toggle="modal" data-target="#exampleModal${row.id}">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        <button title="Ver Observacion" class="btn btn-primary" data-toggle="modal" data-target="#listado${row.id}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                `;
                        }
                        return `

                             ${botones}

                            <form action="../sdo-funciones/criogenico_nueva_observacion.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="nomUsuario" value="${row.nomUsuario}">
                        <div class="modal fade" id="exampleModal${row.id}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header bg-secondary">
                                        <h5 class="modal-title">Agregar Observación</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" name="fecha" value="${row.now}" readonly>

                                        <br>

                                        <label>Observación</label>
                                        <textarea class="form-control" name="obs" rows="15" style="resize: none"></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <input type="hidden" name="idConsumo" value="${row.id}">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                            <!-- Modal -->
                            <div class="modal fade" id="listado${row.id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-success">
                                    <h5 class="modal-title" id="exampleModalLabel">Observaciones</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Usuario Creador</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${
                            row.observaciones.length > 0
                                ? row.observaciones.map(obs => `
                                            <tr>
                                                <td>${obs.fecha}</td>
                                                <td>${obs.nomUsuarioCreador}</td>
                                                <td>${obs.obs}</td>
                                            </tr>
                                        `).join('')
                                : `<tr><td colspan="3" class="text-center">Sin observaciones</td></tr>`
                        }
                                </tbody>
                            <tfoot class="bg-secondary">
                                    <tr>
                                        <th>Fecha</th>
                                         <th>Usuario Creador</th>
                                        <th>Observación</th>
                                    </tr>
                                </tfoot>
                            </table>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                                    <!-- Modal -->
                            <div class="modal fade" id="modificar${row.id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-warning">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Registros ${row.id}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form action="../sdo-funciones/editar_registros_criogenico.php" method="POST">
                                  <div class="modal-body">
                                    <label>Fecha: </label>
                                    <br>
                                     <input type="month" class="form-control" value="${row.fecha_raw}" name="fecha" id="fecha" required>
                                    <br>
                                    <label>OC: </label>
                                    <br>
                                    <input type="text" class="form-control" value="${row.oc}" name="oc" id="oc" required>
                                    <br>
                                    <label>N° Factura: </label>
                                    <br>
                                    <input type="text" class="form-control" value="${row.nFactura}" name="nFactura" id="nFactura" required>
                                    <br>
                                    <label>M<sup>3</sup> Consumidos: <code>*</code></label>
                                    <input class="form-control" required id="mConsumidos" value="${row.metrosConsumidos_raw}" placeholder="Metros Cubicos Consumidos" type="text" name="mConsumidos" />
                                    <br>
                                    <label>Precio Unitario: <code>*</code></label>
                                    <input class="form-control" required id="precioUnitario" value="${row.precioUnitario_raw}" placeholder="Precio Unitario" type="number" name="precioUnitario" />
                                    <br>
                                    <label>Valor Total Neto: <code>*</code></label>
                                    <input class="form-control" required id="valorTotalNeto" value="${row.valorTotal_raw}" placeholder="Valor Total Neto" type="number" name="valorTotalNeto" />
                                    <br>
                                    <label>Valor Total con IVA: <code>*</code></label>
                                    <input class="form-control" required id="valorTotalIVA" value="${row.valorTotalConIVA_raw}" placeholder="Valor Total con IVA" type="number" name="valorTotalIVA" />
                    </div>
                                  <div class="modal-footer">
                                    <input type="hidden" name="idConsumo" value="${row.id}">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-success">Modificar</button>
                                  </div>
                                  </form>

                                </div>
                              </div>
                            </div>

`;
                    }
                }
            ],

            "columnDefs": [
                { "className": "text-center", "targets": "_all" }
            ],

            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar :",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="obsGenerales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h5 class="modal-title" id="exampleModalLabel"> Visualizar todas las Observaciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="text-center table table-bordered">
                    <thead class="bg-secondary">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario Creador</th>
                        <th>Observación</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot class="bg-secondary">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario Creador</th>
                        <th>Observación</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#obsGenerales').on('show.bs.modal', function () {

            let tbody = $('#obsGenerales tbody');

            tbody.html(`
            <tr>
                <td colspan="3" class="text-center">
                    Cargando observaciones...
                </td>
            </tr>
        `);

            $.ajax({
                url: '../sdo-includes/obtener_observaciones_criogenico.php',
                type: 'GET',
                dataType: 'json',
                success: function(data){

                    let filas = '';

                    if(data.length === 0){
                        filas = `
                        <tr>
                            <td colspan="3" class="text-center">
                                No hay observaciones registradas
                            </td>
                        </tr>
                    `;
                    } else {

                        data.forEach(obs => {

                            filas += `
                            <tr>
                                <td>${obs.fecha}</td>
                                <td>${obs.usuario}</td>
                                <td>${obs.observacion}</td>
                            </tr>
                        `;
                        });
                    }

                    tbody.html(filas);
                },
                error: function(xhr){

                    console.log(xhr.responseText);

                    tbody.html(`
                    <tr>
                        <td colspan="3" class="text-center text-danger">
                            Error al cargar datos
                        </td>
                    </tr>
                `);

                }
            });

        });

    });
</script>
</body>
</html>
