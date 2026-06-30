<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idDepartamento = $_SESSION['idDepartamento'];

date_default_timezone_set('America/Santiago');

if (isset($_POST['idEquipo'])) {
    $idEquipo = $_POST['idEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }

    $_SESSION['idEquipo'] = $idEquipo;
} else if ($_SESSION['idEquipo']) {
    $idEquipo = $_SESSION['idEquipo'];
    $idCategoria = $_POST['idCategoria'];
    $sqlGeneral = "SELECT * FROM equipamiento_hbqp WHERE id = $idEquipo";
    $res = mysqli_query($conexion, $sqlGeneral);
    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nombreEquipo'] = $row['nombreEquipo'];
        $nombreEquipo = $datos['nombreEquipo'];
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carpeta - Sistema</title>
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
    <style>
        #mesInforme {
            display: none;
        }

        #rangoFecha {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/user-sidebar.php';
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
            if (isset($_GET['exitoAgregarHoja'])) {
                ?>
                <script>
                    Swal.fire(
                        'Exito!',
                        'Se agrego correctamente la hoja técnica a la carpeta!',
                        'success'
                    )
                </script>
                <?php
            }
            if (isset($_GET['errorAgregarHoja'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'No fue posible agregar la hoja técnica a la carpeta!',
                        'error'
                    )
                </script>
                <?php
            }
            if (isset($_GET['noExisteHojaTecnica'])) {
                ?>
                <script>
                    Swal.fire(
                        'Error!',
                        'Esta hoja técnica no existe, intente con otra!',
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
                            <div class="card-header bg-primary">
                                <?php
                                    if($idDepartamento == 6){
                                        ?>
                                        <a href="equipo-inge-clinica.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <?php
                                    }else{
                                        ?>
                                        <a href="equipo.php" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="idEquipo" value="<?php echo $idEquipo ?>">
                                <input type="hidden" id="idDepartamento" value="<?php echo $idDepartamento ?>">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 style="font-size: 32px;" class="text-center text-bold text-muted">Equipo <?php echo $nombreEquipo ?> - Hojas Técnicas </h1>
                                        <hr>
                                        <br>
                                        <button class="btn btn-primary text-center" data-toggle="modal" data-target="#modalHojas"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp; <b>Añadir hoja técnica al equipo</b> </button>
                                        <!--<button type="button" class="btn btn-warning" onclick="agregarHojaVida();" data-toggle="modal" data-target=".generarInforme"> <b><i class="far fa-file-pdf"></i> Generar Informes</b> </button>-->
                                        <br>
                                        <div class="modal fade generarInforme" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title"> <b>Generar Informe</b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../sdo-pdf/SDO-Informe-Hojas-Tecnicas.php" target="_blank" method="POST">

                                                            <input type="hidden" name="idEquipo" id="hojaSubir" value="<?php echo $idEquipo ?>">

                                                            <div class="form-group">
                                                                <h6 class="tex-center">Escoja el periodo</h6>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios1" value="mensual">
                                                                    <label class="form-check-label" for="exampleRadios1">
                                                                        Mensual
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios2" value="rangoFechas">
                                                                    <label class="form-check-label" for="exampleRadios2">
                                                                        Rango de Fechas
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="periodo" onchange="mostrarInputs(this.value)" id="exampleRadios3" value="todos">
                                                                    <label class="form-check-label" for="exampleRadios3">
                                                                        Todos
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="row" id="mesInforme">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Escoja el mes:<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <select type="text" class="form-control form-control-sm" required id="mensual" name="mesInforme">
                                                                            <option value="" disabled selected>-- Seleccione --</option>
                                                                            <option value="Enero">Enero</option>
                                                                            <option value="Febrero">Febrero</option>
                                                                            <option value="Marzo">Marzo</option>
                                                                            <option value="Abril">Abril</option>
                                                                            <option value="Mayo">Mayo</option>
                                                                            <option value="Junio">Junio</option>
                                                                            <option value="Julio">Julio</option>
                                                                            <option value="Agosto">Agosto</option>
                                                                            <option value="Septiembre">Septiembre</option>
                                                                            <option value="Octubre">Octubre</option>
                                                                            <option value="Noviembre">Noviembre</option>
                                                                            <option value="Diciembre">Diciembre</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" id="rangoFecha">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Desde :<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <input type="date" class="form-control form-control-sm" required id="fechaInicio" name="fechaInicio">

                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputCodigoTarjeta" style="font-size:12px">Hasta :<code>*</code></label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                        <input type="date" class="form-control form-control-sm" onchange="ValidarFechas()" required id="fechaFin" name="fechaFin">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="submit" id="enviar" class="btn btn-primary"> <b>Generar Informe</b> </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="modal fade" id="modalHojas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalHojasLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title" id="modalHojasLabel">Escoja la hoja técnica a añadir</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="hoja_tecnicas" class="table text-center table-sm table-hover  table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Nombre</th>
                                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                <th scope="col" class="text-center">Ver Hoja Técnicas</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <th scope="col" class="text-center">N°</th>
                                                                <th scope="col" class="text-center">Nombre</th>
                                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                                <th scope="col" class="text-center">Seleccionar</th>
                                                                <th scope="col" class="text-center">Ver Hoja Técnica</th>
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
                                        <table id="tabla-hojas-tecnicas" class="table table-hover text-center  table-bordered table-sm">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                <th scope="col" class="text-center">Tipo de Mantenimiento</th>
                                                <th scope="col" class="text-center">Ticket Asociado</th>
                                                <th scope="col" class="text-center">Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th scope="col" class="text-center">N°</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Tecnico Recepcionador</th>
                                                <th scope="col" class="text-center">Tipo de Mantenimiento</th>
                                                <th scope="col" class="text-center">Ticket Asociado</th>
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
        let idEquipo = document.getElementById('idEquipo').value;

        $('#tabla-hojas-tecnicas').DataTable({

            ajax: {
                url: "../sdo-personal/listar-HT-equipo.php",
                type: "POST",
                data: function(d) {
                    d.idEquipo = idEquipo;
                },
                dataSrc: ""
            },

            columns: [

                {
                    data: null,
                    render: function(data, type, row, meta){
                        return meta.row + 1;
                    }
                },

                { data: 'nomOrden' },

                { data: 'nomTecnicoRecepcionador' },

                { data: 'tipoMantencion' },

                {
                    data: 'ticketAsociado',
                    render: function(data){
                        return data ? data : '-';
                    }
                },

                {
                    data: 'idOrden',
                    render: function(data, type, row){

                        let botones = `
                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php"
                          target="_blank"
                          method="POST"
                          style="display:inline;">
                        <input type="hidden"
                               name="idOrden"
                               value="${data}">
                        <button type="submit"
                                class="btn btn-sm btn-info">
                            <i class="far fa-eye"></i>
                        </button>
                    </form>
                `;

                        if(row.tieneArchivos == 1){
                            botones += `
                        <form action="hojastecnicas.php"
                              method="POST"
                              style="display:inline;">
                            <input type="hidden"
                                   name="idOrden"
                                   value="${data}">
                            <input type="hidden"
                                   name="folio"
                                   value="${row.folioOrden}">
                            <button type="submit"
                                    class="btn btn-sm btn-warning">
                                <i class="fas fa-download"></i>
                            </button>
                        </form>
                    `;
                        }

                        botones += `
                    <a class="btn btn-sm btn-danger eliminar"
                       data-id="${data}">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                `;

                        return botones;
                    }
                }
            ],

            paging: true,
            iDisplayLength: 50,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        let idDepartamento = document.getElementById('idDepartamento').value;

        $('#hoja_tecnicas').DataTable({

            ajax: {
                url: "../sdo-personal/listar-HT-MODAL.php",
                type: "POST",
                data: function(d) {
                    d.idDepartamento = idDepartamento;
                },
                dataSrc: ""
            },

            columns: [

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },

                {
                    data: 'nomOrden'
                },

                {
                    data: 'nomTecnicoRecepcionador'
                },

                {
                    data: 'ticketAsociado',
                    render: function(data) {
                        return data ? data.toUpperCase() : 'SIN INFORMACIÓN';
                    }
                },

                {
                    data: 'idOrden',
                    render: function(data) {

                        return `
                    <form action="../sdo-funciones/agregar-hoja-tecnica-carpeta.php" method="POST">
                        <input type="hidden" name="idCategoria" value="${idCarpeta}">
                        <input type="hidden" name="idHojaTecnica" value="${data}">
                        <input type="submit" value="Añadir Esta Hoja" class="btn btn-danger">
                    </form>
                `;
                    }
                },

                {
                    data: 'idOrden',
                    render: function(data) {

                        return `
                    <form action="../sdo-pdf/SDO-OrdenTrabajoGeneral.php"
                          target="_blank"
                          method="POST">

                        <input type="hidden"
                               name="idOrden"
                               value="${data}">

                        <center>
                            <button type="submit"
                                    class="btn btn-sm btn-info">
                                <i class="far fa-eye"></i>
                            </button>
                        </center>

                    </form>
                `;
                    }
                }

            ],

            paging: true,
            iDisplayLength: 10,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: true
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
                title: '¿Eliminar la hoja ténica de la carpeta?',
                text: 'Si esta hoja técnica le pertenece a un equipo, tambien se eliminara del registro',
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
                        url: '../sdo-funciones/eliminar-hojatecnica-equipo.php',
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
<script>
    function ValidarFechas() {
        var fechainicial = document.getElementById("fechaInicio").value;
        var fechafinal = document.getElementById("fechaFin").value;
        var enviar = document.getElementById("enviar");



        console.log(fechainicial)
        console.log(fechafinal)

        fechainicial = Date.parse(fechainicial);
        fechafinal = Date.parse(fechafinal);

        console.log(fechainicial)
        console.log(fechafinal)

        if (fechainicial > fechafinal) {
            alert("La fecha final debe ser mayor a la fecha inicial");
            enviar.disabled = true;

        } else {
            enviar.disabled = false;
        }

    }
</script>
<script>
    function mostrarInputs(valor) {

        var enviar = document.getElementById("enviar");

        //Mensual
        mesInforme = document.getElementById('mesInforme');
        mensual = document.getElementById('mensual');

        //Rango de fechas
        rangoFecha = document.getElementById('rangoFecha');
        fechaInicio = document.getElementById('fechaInicio');
        fechaFin = document.getElementById('fechaFin');

        if (valor == 'mensual') {
            enviar.disabled = false;

            mensual.required = true;
            mesInforme.style.display = 'block';

            //Configura rango fechas
            rangoFecha.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;
        } else if (valor == 'rangoFechas') {
            enviar.disabled = false;

            fechaInicio.required = true;
            fechaFin.required = true;
            rangoFecha.style.display = 'block';


            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        } else if (valor == 'todos') {
            enviar.disabled = false;

            //Configura rango fechas
            rangoFecha.style.display = 'none';
            fechaInicio.required = false;
            fechaFin.required = false;

            //Configura radio mensual
            mensual.required = false;
            mesInforme.style.display = 'none';
        }

        console.log(valor);

    }
</script>
</body>

</html>