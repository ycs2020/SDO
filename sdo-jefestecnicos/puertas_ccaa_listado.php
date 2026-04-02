<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('America/Santiago');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Puertas en Sistema Control de Acceso</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Listado de Puertas en Sistema Control de Acceso</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
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

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                            <tr>
                                                <th><center>N°</center></th>
                                                <th><center>Nombre</center></th>
                                                <th><center>Copiar Nombre</center></th> <!-- NUEVA COLUMNA -->
                                                <th><center>UCA</center></th>
                                                <th><center>N° de Puerta</center></th>
                                                <th><center>Grupo de Puertas</center></th>
                                                <th><center>Usuarios Grupo de Puerta</center></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $pisosArray = array("-P0", "-P1", "-P2", "-P3", "-P4", "-P5", "-P6");
                                            $mensajeError = '<center>Error al Cargar Datos.</center>';

                                            foreach ($pisosArray as $piso) {
                                                $queryUCA = 'SELECT * FROM ucas_listado WHERE name LIKE "%' . $piso . '%" ORDER BY name ASC';
                                                $resUCA = mysqli_query($conexion, $queryUCA);
                                                $numAcceso = 1;

                                                while ($rowUCA = $resUCA->fetch_assoc()) {
                                                    $query = 'SELECT * FROM ACS_DOOR_GROUP_DOOR WHERE DeviceID = ' . $rowUCA['idUnique'] . ' ORDER BY DoorID ASC';
                                                    $res = mysqli_query($conexion, $query);

                                                    while ($row = $res->fetch_assoc()) {
                                                        $deviceID = $row['DeviceID'];
                                                        $doorID = $row['DoorID'];
                                                        $doorGroupID = $row['DoorGroupID'];

                                                        $nombrePuerta = ($deviceID > 0 && $doorID > -1)
                                                            ? mb_convert_case(htmlspecialchars(obtenerNomPuerta($deviceID, $doorID)), MB_CASE_UPPER, "UTF-8")
                                                            : $mensajeError;

                                                        $nombreUCA = ($deviceID > 0)
                                                            ? mb_convert_case(htmlspecialchars(obtenerNomUCA($deviceID)), MB_CASE_UPPER, "UTF-8")
                                                            : $mensajeError;

                                                        $numeroPuerta = ($doorID > -1)
                                                            ? htmlspecialchars($doorID + 1)
                                                            : $mensajeError;

                                                        $nombreGrupo = ($doorGroupID > 0)
                                                            ? mb_convert_case(htmlspecialchars(obtenerNameGrupoPuerta($doorGroupID)), MB_CASE_UPPER, "UTF-8")
                                                            : $mensajeError;

                                                        echo '<tr>';
                                                        echo '<td style="vertical-align:middle;"><center>' . $numAcceso . '</center></td>';
                                                        echo '<td style="vertical-align:middle;" align="justify">' . $nombrePuerta . '</td>';


                                                        $nombrePuertaTrim = trim($nombrePuerta);
                                                        $nombreID = 'puerta_' . uniqid();
                                                        echo '<td style="vertical-align:middle;" align="center">';
                                                        echo '<input type="text" id="' . $nombreID . '" value="' . htmlspecialchars($nombrePuertaTrim) . '" style="position:absolute; left:-9999px;">';
                                                        echo '<button class="btn btn-primary" onclick="copiarTexto(\'' . $nombreID . '\')">';
                                                        echo '<i class="fas fa-copy"></i>';
                                                        echo '</button>';
                                                        echo '</td>';

                                                        echo '<td style="vertical-align:middle;" align="center">' . $nombreUCA . '</td>';
                                                        echo '<td style="vertical-align:middle;" align="center">' . $numeroPuerta . '</td>';
                                                        echo '<td style="vertical-align:middle;" align="justify">' . $nombreGrupo . '</td>';

                                                        echo '<td style="vertical-align:middle;" align="justify">';
                                                        if ($doorGroupID > 0) {
                                                            echo '<form style="margin: 0; text-align: center;" action="../sdo-jefestecnicos/puertas_grupo_puertas_usuarios.php" method="post">';
                                                            echo '<input type="hidden" name="idGrupoPuerta" value="' . htmlspecialchars($doorGroupID) . '">';
                                                            echo '<button type="submit" class="btn btn-block btn-success"><i class="fa fa-users"></i></button>';
                                                            echo '</form>';
                                                        } else {
                                                            echo $mensajeError;
                                                        }
                                                        echo '</td>';
                                                        echo '</tr>';

                                                        $numAcceso++;
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                            <tr>
                                                <th><center>N°</center></th>
                                                <th><center>Nombre</center></th>
                                                <th><center>Copiar Nombre</center></th> <!-- NUEVA COLUMNA -->
                                                <th><center>UCA</center></th>
                                                <th><center>N° de Puerta</center></th>
                                                <th><center>Grupo de Puertas</center></th>
                                                <th><center>Usuarios Grupo de Puerta</center></th>
                                            </tr>
                                            </tfoot>
                                        </table>


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
        // Inicializa DataTable
        let table = $('#example2').DataTable({
            buttons: [{
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                title: 'LISTADO DE PUERTAS EN SISTEMA CONTROL DE ACCESO',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5] // OMITIMOS columna "Copiar Nombre" (índice 2)
                }
            }],
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: true,
            language: {
                lengthMenu: "Visualizando _MENU_ Registros por Página",
                zeroRecords: "No se han encontrado Registros.",
                info: "Mostrando _TOTAL_ Puertas",
                infoEmpty: "No existen Registros.",
                search: "Buscar:",
                infoFiltered: "(Filtrado desde _MAX_ Registros Totales)",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente"
                }
            }
        });

        // Mueve los botones a la parte superior izquierda
        table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    });
</script>
<script>
    function copiarTexto(idCampo) {
        const input = document.getElementById(idCampo);

        if (!input) {
            alert("No se encontró el campo a copiar.");
            return;
        }

        input.select();
        input.setSelectionRange(0, 99999); // Para móviles

        const exito = document.execCommand("copy");

        if (exito) {
            // alert("Texto copiado: " + input.value);
        } else {
            alert("Error al copiar el texto.");
        }
    }
</script>



<script src="js/dark-mode.js"></script>
</body>

</html>