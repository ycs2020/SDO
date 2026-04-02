<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

//VARIABLES DE SESSION
$userUsuario = $_SESSION['userUsuario'];
$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);
$ruta = "";



if ($rolUsuario2 == "personal") {
    $ruta = "sdo-personal";

    $sql = "SELECT * FROM personal WHERE idPersonal  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomPersonal'] = $row['nomPersonal'];
        $datos['passPersonal'] = $row['passPersonal'];
        $datos['emailPersonal'] = $row['emailPersonal'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passPersonal'];
        $correoElectronico = $datos['emailPersonal'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomPersonal'];
    }
} else if ($rolUsuario2 == "administrativo") {
    $ruta = "sdo-admtec";

    $sql = "SELECT * FROM adm_tecnicos WHERE idAdmTecnicos    = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomAdmTecnicos'] = $row['nomAdmTecnicos'];
        $datos['passAdmTecnicos'] = $row['passAdmTecnicos'];
        $datos['emailAdmTecnicos'] = $row['emailAdmTecnicos'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdmTecnicos'];
        $correoElectronico = $datos['emailAdmTecnicos'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomAdmTecnicos'];
    }
} else if ($rolUsuario2 == "bodega pañol") {
    $ruta = "sdo-bodega-panol";

    $sql = "SELECT * FROM bodega_pañol WHERE idBodegaPañol = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomBodegaPañol'] = $row['nomBodegaPañol'];
        $datos['passBodegaPañol'] = $row['passBodegaPañol'];
        $datos['emailBodegaPañol'] = $row['emailBodegaPañol'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passBodegaPañol'];
        $correoElectronico = $datos['emailBodegaPañol'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomBodegaPañol'];
    }
} else if ($rolUsuario2 == "jefe tecnico") {
    $ruta = "sdo-jefestecnicos";

    $sql = "SELECT * FROM jefes_tecnicos WHERE idJefe   = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomJefe'] = $row['nomJefe'];
        $datos['passJefe'] = $row['passJefe'];
        $datos['emailJefe'] = $row['emailJefe'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passJefe'];
        $correoElectronico = $datos['emailPersonal'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomJefe'];
    }


} else if ($rolUsuario2 == "administrador") {
    $ruta = "sdo-administrador";

    $sql = "SELECT * FROM administracion WHERE idAdministrador  = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomAdministrador'] = $row['nomAdministrador'];
        $datos['passAdministrador'] = $row['passAdministrador'];
        $datos['emailAdministrador'] = $row['emailAdministrador'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passAdministrador'];
        $correoElectronico = $datos['emailAdministrador'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomAdministrador'];
    }
} else if ($rolUsuario2 == "directiva") {
    $ruta = "sdo-directiva";

    $sql = "SELECT * FROM directivos WHERE idDirectivo   = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomDirectivo'] = $row['nomDirectivo'];
        $datos['passDirectivo'] = $row['passDirectivo'];
        $datos['emailDirectivo'] = $row['emailDirectivo'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passDirectivo'];
        $correoElectronico = $datos['emailDirectivo'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomDirectivo'];
    }
} else if ($rolUsuario2 == "mesa ayuda") {
    $ruta = "sdo-directiva";

    $sql = "SELECT * FROM mesa_ayuda WHERE idMesaAyuda    = $idUsuario";
    $res = mysqli_query($conexion, $sql);

    while ($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['nomMesaAyuda'] = $row['nomMesaAyuda'];
        $datos['passMesaAyuda'] = $row['passMesaAyuda'];
        $datos['emailMesaAyuda'] = $row['emailMesaAyuda'];
        $datos['fechaNacimiento'] = $row['fechaNacimiento'];
        $password = $datos['passMesaAyuda'];
        $correoElectronico = $datos['emailMesaAyuda'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $nomUsuario = $datos['nomMesaAyuda'];
    }
}else {
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
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/mesaayuda-sidebar.php';

    ?>
    <!-- /.navbar -->
    <!-- /.mensajes -->

    <?php

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
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="../sdo-administrador/img/ssvq1.png" alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center"><?php echo $nomUsuario ?></h3>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Mi datos</a></li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class=" tab-pane" id="activity">
                                        <!-- Post -->
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                                <span class="username">
                                                        <a href="#">Jonathan Burke Jr.</a>
                                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                    </span>
                                                <span class="description">Shared publicly - 7:30 PM today</span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie
                                                Sheen fans.
                                            </p>

                                            <p>
                                                <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                                <span class="float-right">
                                                        <a href="#" class="link-black text-sm">
                                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                                        </a>
                                                    </span>
                                            </p>

                                            <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                        </div>
                                        <!-- /.post -->

                                        <!-- Post -->
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                                <span class="username">
                                                        <a href="#">Sarah Ross</a>
                                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                    </span>
                                                <span class="description">Sent you a message - 3 days ago</span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie
                                                Sheen fans.
                                            </p>

                                            <form class="form-horizontal">
                                                <div class="input-group input-group-sm mb-0">
                                                    <input class="form-control form-control-sm" placeholder="Response">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-danger">Send</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.post -->

                                        <!-- Post -->

                                        <!-- /.post -->
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- /.tab-pane -->

                                    <div class="tab-pane active" id="settings">
                                        <form class="form-horizontal" id="form-datos" method="POST" action="../sdo-funciones/editar-miperfil.php">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="codigoBarra">Nombre:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" name="nomUsuario" required value="<?php echo $nomUsuario ?>" id="nomUsuario" placeholder="nomUsuario">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="codigoBarra">Fecha de nacimiento:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control form-control-sm" id="fechaNacimiento"  name="fechaNacimiento" value="<?php echo $fechaNacimiento ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="codigoBarra">Correo Electronico:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                        </div>
                                                        <input type="email" class="form-control form-control-sm" name="correoElectronico" required value="<?php echo $correoElectronico ?>" id="correoElectronico" placeholder="nomUsuario">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="codigoBarra">Nueva Contraseña:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" title="Debe contar con al menos 8 caracteres y al menos 1 Mayuscula, 1 Minuscula, 1 Número y uno de estos simbolos @$!%*?& y " pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" minlength="8" name="password" id="password" placeholder="Escriba nueva contraseña">
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" id="passwordComprueba" value="<?php echo $password ?>">

                                            <div class="form-row justify-content-center text-center">
                                                <button type="submit" class="btn btn-danger">Guardar Cambios</button>
                                            </div>
                                        </form>

                                        <!-- Modal -->
                                        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="confirmarModalLabel"> <b>Comprobar Contraseña Actual</b> </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-danger text-bold">*Debe confirmar los cambios con su contraseña Actual</p>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-12 col-12">
                                                                <label for="codigoBarra">Contraseña Actual:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" name="passwordActual" id="passwordActual" placeholder="Escriba contraseña Actual">
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <b>Cerrar</b> </button>
                                                        <button type="button" class="btn btn-success" id="comprueba"> <b>Comprobar</b> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
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
<!-- Page specific script -->

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
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
<script>
    const toggle = document.getElementById('toggle-sidebar');
    const icon = document.getElementById('sidebar-icon');


    toggle.addEventListener('click', function() {
        console.log('hola');
        icon.classList.toggle('rotar');
    })
</script>
<script>
    $('#form-datos').submit(function(e) {
        e.preventDefault();

        $("#confirmarModal").modal("show");

    })

    $('#comprueba').click(function() {

        var password = $("#passwordActual").val()
        var passwordActual = $("#passwordComprueba").val();

        if (password == passwordActual) {
            $("#form-datos").off("submit").submit();
        } else {

            Swal.fire(
                'Error!',
                'La contraseña ingresada no es correcta, intente nuevamente',
                'error'
            )
            $("#passwordActual").val("");
            $("#confirmarModal").modal("hide");

        }
    })
</script>
</body>

</html>