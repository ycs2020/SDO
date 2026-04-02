<?php

include_once('../sdo-funciones/funciones.php');
include_once('../sdo-funciones/conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$nomUsuario = $_SESSION['nomUsuario'];

//VARIABLES DE SESSION

if (isset($_POST['idFormFuncCCAA']) || isset($_SESSION['idFormFuncCCAA'])) {

    if (isset($_POST['idFormFuncCCAA'])) {
        $idFormFuncCCAA = $_POST['idFormFuncCCAA'];

    } else if (isset($_SESSION['idFormFuncCCAA'])) {
        $idFormFuncCCAA = $_SESSION['idFormFuncCCAA'];
    }

    if ($idFormFuncCCAA > 0) {

        $getDataRegistroFunc = array();
        $getDataRegistroFunc = getDataFuncionarioForm($idFormFuncCCAA);

        $idFormFuncCCAA = $getDataRegistroFunc['idFormFuncCCAA'];
        $nomFormFuncCCAA = $getDataRegistroFunc['nomFormFuncCCAA'];
        $rutFormFuncCCAA = $getDataRegistroFunc['rutFormFuncCCAA'];
        $calidadFormFuncCCAA = $getDataRegistroFunc['calidadFormFuncCCAA'];
        $unidadFormFuncCCAA = $getDataRegistroFunc['unidadFormFuncCCAA'];
        $fechaFormCCAA = $getDataRegistroFunc['fechaFormCCAA'];

        $idFormRegAcceso = $getDataRegistroFunc['idFormRegAcceso'];
        $verificaActaFormFuncCCAA = $getDataRegistroFunc['verificaActaFormFuncCCAA'];
        $fechaActaFormFuncCCAA = $getDataRegistroFunc['fechaActaFormFuncCCAA'];
        $verificaCAFormFuncCCAA = $getDataRegistroFunc['verificaCAFormFuncCCAA'];
        $fechaVerCAFormFuncCCAA = $getDataRegistroFunc['fechaVerCAFormFuncCCAA'];
        $permisosFormFuncCCAA = $getDataRegistroFunc['permisosFormFuncCCAA'];
        $fechaPermisoFormFuncCCAA = $getDataRegistroFunc['fechaPermisoFormFuncCCAA'];


    } else {
        header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario=true');
    }


} else {
    header('Location: ../sdo-administrador/formulariosCCAA_listado.php?errorGenerarFormulario=true');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizando Funcionario</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Visualizando Funcionario</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">


                                <a href="formulariosCCAA_listadoUsuarios.php" style="float: right;"
                                   class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br><br>

                                <table>
                                    <tr>
                                        <td>
                                            <h5>
                                                <b>Funcionario(a): </b>
                                            </h5>
                                        </td>
                                        <td>
                                            <h5>
                                                <?php

                                                $nomFormFuncCCAA2 = mb_convert_case($nomFormFuncCCAA, MB_CASE_TITLE, 'UTF-8');
                                                echo '&nbsp;' . $nomFormFuncCCAA2;

                                                ?>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>
                                                <b>RUT Funcionario(a): </b>
                                            </h5>
                                        </td>
                                        <td>
                                            <h5>
                                                <?php

                                                $rutFormFuncCCAA2 = mb_convert_case($rutFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');
                                                echo '&nbsp;' . $rutFormFuncCCAA2;

                                                ?>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>
                                                <b>Calidad Juridica: </b>
                                            </h5>
                                        </td>
                                        <td>
                                            <h5>
                                                <?php

                                                $calidadFormFuncCCAA2 = mb_convert_case($calidadFormFuncCCAA, MB_CASE_TITLE, 'UTF-8');
                                                echo '&nbsp;' . $calidadFormFuncCCAA2;

                                                ?>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>
                                                <b>Unidad Funcionario: </b>
                                            </h5>
                                        </td>
                                        <td>
                                            <h5>
                                                <?php

                                                $unidadFormFuncCCAA2 = mb_convert_case($unidadFormFuncCCAA, MB_CASE_UPPER, 'UTF-8');
                                                echo '&nbsp;' . $unidadFormFuncCCAA2;

                                                ?>
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <?php

                                if (isset($_GET['exitoObservacion'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Observación Agregada con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['errorObservacion'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Agregar una Observaci&oacute;n.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['exitoFirmaDocumento'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Documento Subido con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        });
                                    </script>
                                    <?php
                                }

                                if (isset($_GET['errorFirmaDocumento'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Subir Formato Firmado. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }
                                if (isset($_GET['exitoGenerarFormulario'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Formulario Generado con &Eacute;xito.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'success',
                                        });
                                    </script>
                                    <?php
                                }
                                if (isset($_GET['errorGenerarFormulario'])) {
                                    ?>
                                    <script>
                                        Swal.fire({
                                            title: 'Error al Visualizar Formato. Reintente.',
                                            confirmButtonText: 'Cerrar',
                                            icon: 'error',
                                        });
                                    </script>
                                    <?php
                                }
                                ?>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body text-center">


                                <div class="card col-12">

                                    <div class="card-body">
                                        <div class="table-responsive">

                                            <table id="miTabla" class="table table-bordered"
                                                   style="font-size: 12px; text-align: center;">
                                                <thead class="bg-secondary">
                                                <tr>

                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        FOLIO DEL FORMULARIO:
                                                    </td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        UNIDAD DEL FORMULARIO:
                                                    </td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        JEFATURA SOLICITANTE:
                                                    </td>
                                                    <?php
                                                    for ($i = 1; $i < 19; $i++) {
                                                        ?>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            PUERTA N° <?php echo $i; ?>:
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                $sqlIDForm = "SELECT * FROM formularios_funcionarios WHERE rutFormFuncCCAA LIKE '%" . $rutFormFuncCCAA2 . "%' ORDER BY idFormRegAcceso DESC";

                                                $resIDForm = mysqli_query($conexion, $sqlIDForm);

                                                $numAcceso = 1;

                                                while ($rowIDForm = $resIDForm->fetch_assoc()) {

                                                    $idFormRegAcceso = $rowIDForm['idFormRegAcceso'];

                                                    if ($idFormRegAcceso > 0) {

                                                        $getDataRegistroForm = array();
                                                        $getDataRegistroForm = getDataRegistroForm($idFormRegAcceso);

                                                        $idFormRegAcceso2 = $getDataRegistroForm['idFormRegAcceso'];
                                                        $folioFormRegAcceso = $getDataRegistroForm['folioFormRegAcceso'];
                                                        $folioFormRegAcceso2 = mb_convert_case($folioFormRegAcceso,MB_CASE_UPPER,'UTF-8');
                                                        $unidadFormRegAcceso = $getDataRegistroForm['unidadFormRegAcceso'];
                                                        $unidadFormRegAcceso2 = mb_convert_case($unidadFormRegAcceso,MB_CASE_UPPER,'UTF-8');
                                                        $solicitanteFormRegAcceso = $getDataRegistroForm['solicitanteFormRegAcceso'];
                                                        $solicitanteFormRegAcceso2 = mb_convert_case($solicitanteFormRegAcceso,MB_CASE_UPPER,'UTF-8');

                                                    }

                                                    ?>

                                                    <tr>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <?php

                                                            echo $folioFormRegAcceso2;

                                                            ?>
                                                        </td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <?php

                                                            $getUnidadFormatosCCAA = getUnidadFormatosCCAA($unidadFormRegAcceso);
                                                            $getUnidadFormatosCCAA2 = mb_convert_case($getUnidadFormatosCCAA,MB_CASE_UPPER,'UTF-8');
                                                            echo $getUnidadFormatosCCAA2;

                                                            ?>
                                                        </td>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            <?php

                                                            echo $solicitanteFormRegAcceso2;

                                                            ?>
                                                        </td>
                                                        <?php

                                                        for ($j = 1; $j < 19; $j++) {
                                                            ?>

                                                            <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                                <?php

                                                                $opcionPuerta = $getDataRegistroFunc['door'.$j.'FormCCAA'];

                                                                $getNomPuertaCCAA = getNomPuertaCCAA($j,$unidadFormRegAcceso);

                                                                if($rowIDForm['door'.$j.'FormCCAA'] == 1){
                                                                    echo $getNomPuertaCCAA;
                                                                }else{
                                                                    echo '';
                                                                }


                                                                ?>
                                                            </td>
                                                            <?php
                                                        }

                                                        ?>
                                                    </tr>
                                                    <?php

                                                }
                                                ?>
                                                </tbody>
                                                <tfoot class="bg-secondary">

                                                <tr>

                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        FOLIO DEL FORMULARIO:
                                                    </td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        UNIDAD DEL FORMULARIO:
                                                    </td>
                                                    <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                        JEFATURA SOLICITANTE:
                                                    </td>
                                                    <?php
                                                    for ($i = 1; $i < 19; $i++) {
                                                        ?>
                                                        <td style="white-space: nowrap; vertical-align:middle; text-align:center;">
                                                            PUERTA N° <?php echo $i; ?>:
                                                        </td>
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
<script src="js/dark-mode.js"></script>
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
<!-- Page specific script -->
<script>
    $(function () {
        $('#miTabla').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
<script>
    $(function () {
        $('#miTabla1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            }
        });
    });
</script>
</body>

</html>