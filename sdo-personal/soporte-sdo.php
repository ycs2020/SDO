<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');

if ($horaActual >= '08' && $horaActual <= '11') {
    $mensajeBienvenida = '¡ Buenos Dias';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '12' &&  $horaActual <= '19') {
    $mensajeBienvenida = '¡ Buenas Tardes';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
} else if ($horaActual >= '20' && $horaActual <= '07') {
    $mensajeBienvenida = '¡ Buenas Noches';
    $icono = '<i class="far fa-moon"></i> !';
} else {
    $mensajeBienvenida = '¡ Bienvenido(a)';
    $icono = '<i class="far fa-smile-beam fa-lg"></i> !';
}




//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];

$rolUsuario = $_SESSION['rolUsuario'];

$idDepartamento = $_SESSION['idDepartamento'];

$rolUsuario2 = strtolower($rolUsuario);

$ruta = "";

if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";
    $sqlPersonal = "SELECT * FROM personal WHERE idPersonal = $idUsuario";
    $resPersonal = mysqli_query($conexion,$sqlPersonal);
    while($rowPersonal = $resPersonal->fetch_assoc()){
            $idPersonal = $rowPersonal['idPersonal'];
            $departamentoPersonal = $rowPersonal['departamentoPersonal'];
            $cargoTecnico = $rowPersonal['cargoPersonal'];

    }
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";
} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";
    $sql = "SELECT * FROM administracion WHERE idAdministrador  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['passAdministrador'] = $row['passAdministrador'];
        $datos['emailAdministrador'] = $row['emailAdministrador'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdministrador'];
        $correoElectronico = $datos['emailAdministrador'];
        $fechaNacimiento = $datos['fechaNacimiento'];
    }
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";
} else {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
}






?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men&uacute; del Sistema</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }
        #jefes {
            display: none;
        }
        #personal {
            display: none;
        }
        #directivos {
            display: none;
        }
        .table-hover {
            cursor: pointer;
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
    <!-- /.mensajes -->
    <?php

    if (isset($_GET['exitoTicket'])) {
        ?>
        <script>
            Swal.fire(
                'Bien!',
                'Ticket ingresado en el sistema correctamente!',
                'success'
            )
        </script>
        <?php
    }
    if(isset($_GET['exito'])){
        ?>
        <script>
            Swal.fire(
                'Bien!',
                'Todo salio correctamente',
                'success'
            )
        </script>
        <?php
    }

    if (isset($_GET['errorTicket'])) {
        ?>
        <script>
            Swal.fire(
                'Error!',
                'Error al ingresar el ticket al sistema!',
                'error'
            )
        </script>
        <?php
    }

    if (isset($_GET['exitoEditar'])) {
        ?>
        <script>
            Swal.fire(
                'Bien!',
                'Cambios guardados Correctamente!',
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
                'Error al guardar los cambios!',
                'error'
            )
        </script>
        <?php
    }

    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <br>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="col-12">
                    <div class="card card-danger card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <fieldset class="border p-3">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Nuevo</a>
                                            <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Consultar</a>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-9 col-12">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                            <form method="POST" id="form" action="../sdo-funciones/guardar-soporte-sdo1.php" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="codigoBarra">Categoria:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                                            </div>
                                                            <select type="text" class="form-control form-control-sm" name="categoria" required id="categoria" placeholder="nomUsuario">
                                                                <option value="" selected disabled>-- Seleccione --</option>
                                                                <option value="Reportar Falla">Reportar Falla</option>
                                                                <option value="Consultas">Consultas</option>
                                                                <option value="Sugerencias">Sugerencias</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="codigoBarra">Tipo de usuario:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                                            </div>
                                                          <!--  <select type="text" onchange="escuchaTipoUsuario()" class="form-control form-control-sm" name="tipoUsuario" required id="tipoUsuario">
                                                                <option value="">-- Seleccione --</option>
                                                                <option value="tecnicos">Técnicos</option>
                                                              <option value="jefes">Jefes</option>
                                                                <option value="directivos">Directivos</option>
                                                            </select>-->
                                                            <input type="text" name="tipoUsuario" id="tipoUsuario" readonly class="form-control form-control-sm" value="<?php echo $cargoTecnico?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">

                                                        <label for="departamentoSolicitante">Departamento: <code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                            </div>
                                                                <?php
                                                                $sqlDepartamento = "SELECT * FROM departamentos WHERE idDepartamento = $idDepartamento";
                                                                $resDepartamento = mysqli_query($conexion,$sqlDepartamento);
                                                                while($rowDepartamento = $resDepartamento->fetch_assoc()){
                                                                    $nomDepartamento = $rowDepartamento['nomDepartamento'];
                                                                }

                                                                ?>
                                                                <input name="" id="" readonly class="form-control form-control-sm" value="<?php echo $nomDepartamento?>">
                                                            <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label>Nombre Técnico:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            </div>
                                                            <input type="text" name="nomTecnicoRecepcionador" id=" nomTecnicoRecepcionador" readonly class="form-control form-control-sm" value="<?php echo $nomUsuario?>">

                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label for="codigoBarra">Comentarios:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <textarea name="comentarios"  rows="20" required class="form-control form-control-sm"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <!-- <label for="customFile">Custom File</label> -->
                                                    <label for="exampleInputTareasControlCent">Subir Archivos: (Maximo 10 Archivos)</label><br>
                                                    <div class="custom-file">
                                                        <input type="file" max-uploads="10" class="custom-file-input" multiple="" id="adjuntosSC[]" name="adjuntosSC[]">
                                                        <label class="custom-file-label" for="customFile"><b>Seleccionar Archivo...</b> </label>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="departamentoSolicitante" id="departamentoSolicitante" class="form-control form-control-sm" value="<?php echo $idDepartamento?>">
                                                <input type="hidden" id="idSolicitante" name="idSolicitante" value="<?php echo $idPersonal?>">
                                                <button type="submit" class="btn btn-block btn-success"> <b>Generar Ticket</b> </button>

                                            </form>
                                        </div>
                                        <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                            <h5>&nbsp;&nbsp; Colores para Identificar Ticket:</h5>
                                            <ul>
                                                <li><i style="color: #FFFF00; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; En progreso</li>
                                                <li><i style="color: #70FF8B ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Cerrado</li>
                                                <li><i style="color: #B5B2B2; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Rechazado</li>
                                                <li><i style="color: #ffa420 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon "></i>&nbsp;&nbsp; Respuesta Parcial</li>
                                            </ul>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table  table-hover table-bordered" id="table_soporte">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Ticket</th>
                                                        <th scope="col">Estado</th>
                                                        <th scope="col">SubEstado</th>
                                                        <th scope="col">Categoría</th>
                                                        <th scope="col">Fecha Solicitud</th>
                                                        <th scope="col">Fecha Resolución</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php

                                                    $sqlTicket = "SELECT * FROM soportesdo WHERE idSolicitante = $idPersonal ORDER BY fechaSolicitud DESC";
                                                    $resTicket = mysqli_query($conexion, $sqlTicket);

                                                    while ($rowTicket = $resTicket->fetch_assoc()) {
                                                        $datos = [];
                                                        $datos['idSoporte'] = $rowTicket['idSoporte'];
                                                        $datos['fechaSolicitud'] = $rowTicket['fechaSolicitud'];
                                                        $datos['folioSoporte'] = $rowTicket['folioSoporte'];
                                                        $datos['estado'] = $rowTicket['estado'];
                                                        $datos['subestado'] = $rowTicket['subestado'];
                                                        $datos['categoria'] = $rowTicket['categoria'];
                                                        $datos['fechaResolucion'] = $rowTicket['fechaResolucion'];
                                                        $datos['idResponsable'] = $rowTicket['idResponsable'];
                                                        $idSoporte = $datos['idSoporte'];
                                                        $fechaSolicitud = $datos['fechaSolicitud'];
                                                        $folioSoporte = $datos['folioSoporte'];
                                                        $estado = $datos['estado'];
                                                        $subestado = $datos['subestado'];
                                                        $categoria = $datos['categoria'];
                                                        $fechaResolucion = $datos['fechaResolucion'];
                                                        $idResponsable = $datos['idResponsable'];




                                                        if($estado == 'En Progreso'){
                                                            $colorBG = '#FFFF00';
                                                        }else if($estado == 'Cerrada' && $subestado == 'Aprobada'){
                                                            $colorBG = '#70FF8B';
                                                        }else if($estado == 'Cerrada' && $subestado == 'Parcialmente Aprobada' || $estado == 'Cerrada' && $subestado == 'Parcialmente Rechazada'){
                                                            $colorBG = '#ffa420';
                                                        }else if($estado == 'Cerrada' && $subestado == 'Rechazada'){
                                                            $colorBG = '#B5B2B2';
                                                        }

                                                        $fechaSolicitud = strtotime($fechaSolicitud);
                                                        $fechaSolicitud = date('d/m/Y H:i', $fechaSolicitud);

                                                        $color = "";


                                                        ?>

                                                        <tr class="text-center " style=" color: black ;background-color: <?php echo $colorBG ?>" data-id='<?php echo $idSoporte ?>'>
                                                            <td><?php echo $folioSoporte ?></td>
                                                            <td><?php echo mb_convert_case($estado, MB_CASE_UPPER, 'UTF-8')  ?></td>
                                                            <td>
                                                                <?php
                                                                if (!empty($subestado)) {
                                                                    echo mb_convert_case($subestado, MB_CASE_UPPER, 'UTF-8');
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $categoria ?></td>
                                                            <td><?php echo $fechaSolicitud ?></td>
                                                            <td>
                                                                <?php
                                                                if (!empty($fechaResolucion)) {

                                                                    $fechaResolucion = strtotime($fechaResolucion);
                                                                    $fechaResolucion = date('d/m/Y H:i', $fechaResolucion);
                                                                    echo $fechaResolucion;
                                                                } else {
                                                                    echo '-';
                                                                }
                                                                ?>
                                                            </td>

                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                                <div class="modal" tabindex="-1" id="modalTicket">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success">
                                                                <h5 class="modal-title"> <b>Detalle Ticket - <span id="folioTitulo">()</span> </b> </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group row ">
                                                                    <label for="staticEmail" style="font-size: 15px;" class="col-sm-2 col-form-label">Solicitante:</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" readonly class="form-control form-control-sm" id="solicitanteModal" name="solicitanteModal" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputPassword" style="font-size: 15px;" class="col-sm-2 col-form-label">Departamento:</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" readonly class="form-control form-control-sm" name="departamentoModal" id="departamentoModal">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputPassword" style="font-size: 15px;" class="col-sm-2 col-form-label">Categoria:</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" readonly class="form-control form-control-sm" name="categoriaModal" id="categoriaModal">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputPassword" style="font-size: 15px;" class="col-sm-2 col-form-label">Archivos Adjuntos:</label>
                                                                    <div class="col-sm-10">
                                                                        <button type="button" class="btn bg-purple btn-block"><i class="fas fa-file-download"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="inputPassword" style="font-size: 15px;" class="col-sm-2 col-form-label">Comentarios:</label>
                                                                    <div class="col-sm-10">
                                                                        <textarea readonly class="form-control  form-control-sm" rows="7" name="comentariosModal" id="comentariosModal">
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                    <div class="form-group row">
                                                                        <label for="inputPassword" style="font-size: 15px;" class="col-sm-2 col-form-label">Respuesta:</label>
                                                                        <div class="col-sm-10">
                                                                                <textarea readonly class="form-control form-control-sm" rows="7" name="respuestaModal" id="respuestaModal">

                                                                                </textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" readonly class="form-control form-control-sm" id="idModal" name="idModal" value="">
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"> <b>Cerrar Modal</b></button>
                                                                       <!-- <button type="submit" class="btn btn-primary btn-sm"><b>Guardar Cambios</b></button> -->
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2022. Derechos Reservados.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<!-- SCRIPT DARK MODE -->
<script src="js/dark-mode.js"></script>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

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
<script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@2.3.3/dist/purify.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Page specific script -->

<script>
    $(document).ready(function() {
        $('#table_soporte').DataTable({
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
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function() {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
        recargarListTecnicos();
        recargarListTecnicosPersonal();

        $('#departamentoSolicitante').change(function() {
            recargarListTecnicos();

        });

        $('#depaPersonal').change(function() {
            recargarListTecnicosPersonal();

        });

        function recargarListTecnicos() {
            $.ajax({
                type: "POST",
                url: "sdo-datos-tecnicos/cargarTecnicos1.php",
                data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                success: function(r) {

                    if (r == 0) {

                        var cadena = "";

                        cadena = cadena + '<div class="input-group-prepend">';

                        cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                        cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                        cadena = cadena + '</div>';

                        $('#htmlSelect').html(cadena);

                    } else if (r == "error" || r == "ERROR" || r == "Error") {

                        var cadena = "";

                        cadena = cadena + '<div class="input-group-prepend">';

                        cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                        cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                        cadena = cadena + '</div>';

                        $('#htmlSelect').html(cadena);
                    } else {
                        $('#htmlSelect').html(r);
                    }
                }
            });
        };
        function recargarListTecnicosPersonal() {
            $.ajax({
                type: "POST",
                url: "sdo-datos-tecnicos/cargarTecnicosPersonal.php",
                data: "idDepartamento=" + $('#depaPersonal').val(),
                success: function(r) {

                    if (r == 0) {

                        var cadena = "";

                        cadena = cadena + '<div class="input-group-prepend">';

                        cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                        cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                        cadena = cadena + '</div>';

                        $('#htmlPersonal').html(cadena);

                    } else if (r == "error" || r == "ERROR" || r == "Error") {

                        var cadena = "";

                        cadena = cadena + '<div class="input-group-prepend">';

                        cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                        cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                        cadena = cadena + '</div>';

                        $('#htmlPersonal').html(cadena);

                    } else {
                        $('#htmlPersonal').html(r);
                    }
                }
            });
        };
    });
</script>
<script>
    function escuchaTipoUsuario() {
        tipoUsuario = document.getElementById("tipoUsuario").value;
        tecnicos = document.getElementById("personal");
        jefes = document.getElementById("jefes");
        directivos = document.getElementById("directivos");

        departamentoSolicitante = document.getElementById("departamentoSolicitante");
        depaPersonal = document.getElementById("depaPersonal");

        if (tipoUsuario == 'tecnicos') {
            tecnicos.style.display = "block";
            jefes.style.display = "none";
            directivos.style.display = "none";

            depaPersonal.setAttribute("required", "")
        } else if (tipoUsuario == 'jefes') {
            tecnicos.style.display = "none";
            jefes.style.display = "block";
            directivos.style.display = "none";

            departamentoSolicitante.setAttribute("required", "")
        } else if (tipoUsuario == 'directivos') {
            tecnicos.style.display = "none";
            jefes.style.display = "none";
            directivos.style.display = "block";
        }

    }
</script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            lang: 'es-ES',
            langSrc: 'https://cdn.jsdelivr.net/npm/summernote/lang/summernote-es-ES.min.js',
            height: 300,
            width: 1200,
            disablePicture: true,
            disableVideo: true,
            styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15'],
        });
    })
</script>
<script>
    $('[data-id]').click(function() {
        var dataId = $(this).attr('data-id');
        $('#modalTicket').data('id', dataId).modal('show');
        console.log(dataId);

        var elementos = {};
        //AJAX
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtener-datos-ticket.php',
            data: {
                'id': dataId
            },
            success: function(response) {
                console.log(response);
                var datos = JSON.parse(response);

                elementos.folio = datos.folioSoporte;
                elementos.nomSolicitante = datos.nomSolicitante;
                elementos.nomDepartamento = datos.nomDepartamento;
                elementos.categoria = datos.categoria;
                elementos.comentarios = datos.comentarios;
                elementos.idSoporte = datos.idSoporte;
                elementos.idResponsable = datos.idResponsable;
                elementos.respuesta = datos.respuesta;
                // elementos.estado = datos.estado;
                // elementos.subestado = datos.subestado;
                // elementos.fechaResolucion = datos.fechaResolucion;


                $('#folioTitulo').text(elementos.folio);
                $('#solicitanteModal').val(elementos.nomSolicitante);
                $('#departamentoModal').val(elementos.nomDepartamento);
                $('#categoriaModal').val(elementos.categoria);
                $('#comentariosModal').html(elementos.comentarios);
                $('#idModal').val(elementos.idSoporte);
                $('#idResponsableModal').val(elementos.idResponsable);
                $('#respuestaModal').html(elementos.respuesta);
                // $('#estadoModal').val(elementos.estado);
                // $('#subestadoModal').val(elementos.subestado);
                // $('#fechaResolucionModal').val(elementos.fechaResolucion);

                console.log(elementos.folio);
            }
        })
    })

    $('#modalTicket').on('show.bs.modal', function() {
        var id = $(this).data('id');
        $('#idTicket').val(id);
    })
</script>
</body>
</html>