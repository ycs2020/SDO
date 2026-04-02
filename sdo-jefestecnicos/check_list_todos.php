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
    <title>Check List</title>

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
    <link rel="stylesheet" href="../dist/css/cuadro-cantidad.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Check List</h4>

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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                <br>
                                <br>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <fieldset class="border p-3">
                                    <h4>Tabla de Pantallas Gráficas:</h4>
                                    <table style="font-size:90%" id="example2" class="table table-bordered table-hover">
                                        <thead class="bg-gradient-secondary text-center">
                                            <th>N°</th>
                                            <th>Fecha</th>
                                            <th>Pantalla</th>
                                            <th>Especialidad</th>
                                            <th>Folio OT</th>
                                            <th>Visualizar</th>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                                $sql = "SELECT * FROM datos_checklist where departamento = 1 ORDER BY id DESC";
                                                $res = mysqli_query($conexion,$sql);
                                                $count = 1;
                                                while($row = $res->fetch_assoc()){
                                                    $id = $row['id'];
                                                    $fecha = $row['fecha'];
                                                    $pantalla = $row['pantalla'];
                                                    $especialidad = $row['especialidad'];
                                                    $idTG = $row['idTG'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count?></td>
                                                        <td><?php echo $fecha?></td>
                                                        <td><?php echo $pantalla?></td>
                                                        <td><?php echo $especialidad?></td>
                                                        <td>
                                                            <?php
                                                                $sqlidTG = "SELECT * FROM `trabajos_general` WHERE idTG = $idTG";
                                                                $residTG = mysqli_query($conexion,$sqlidTG);
                                                                while($rowidTG = $residTG->fetch_assoc()){
                                                                    $folioTG = $rowidTG['folioTG'];
                                                                    echo $folioTG;
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <form action="../sdo-pdf/SDO-CheckList-PantallasGraficas.php" target="_blank" method="POST">
                                                                <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $id?>">
                                                                <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG?>">
                                                                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                    $count++;
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot  class="bg-gradient-secondary text-center">
                                            <th>N°</th>
                                            <th>Fecha</th>
                                            <th>Pantalla</th>
                                            <th>Especialidad</th>
                                            <th>Folio OT</th>
                                            <th>Visualizar</th>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <br>
                                <fieldset class="border p-3" id="checklistLogica" name="checklistLogica">
                                    <legend class="w-auto">Tabla de Check List - Logica de Programacion</legend>
                                    <table id="checklistlogica" class="table table-bordered table-sm" style="text-align: center">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlCheckListLogicas = "SELECT * FROM `datos_checklist_logica`";
                                        $resCheckListLogicas = mysqli_query($conexion,$sqlCheckListLogicas);
                                        $count = 1;
                                        while($rowCheckListLogicas = $resCheckListLogicas->fetch_assoc()){
                                            $idCheckLogica = $rowCheckListLogicas['id'];
                                            $ecbos = $rowCheckListLogicas['ecbos'];
                                            $controlador = $rowCheckListLogicas['controlador'];
                                            $observacionesLogica = $rowCheckListLogicas['obs'];
                                            $hojasprogram = $rowCheckListLogicas['hojasprogram'];
                                            $titulossub = $rowCheckListLogicas['titulossub'];
                                            $ordenbloques = $rowCheckListLogicas['ordenbloques'];
                                            $recursosinutilizados = $rowCheckListLogicas['recursosinutilizados'];
                                            $listaerrores = $rowCheckListLogicas['listaerrores'];
                                            $logicacontrol = $rowCheckListLogicas['logicacontrol'];
                                            $isVisadoCheckLogica = $rowCheckListLogicas['isVisado'];
                                            $idTG1 = $rowCheckListLogicas['idTG'];
                                            ?>
                                            <tr>
                                                <td><?php echo $count?></td>
                                                <td><?php echo $rowCheckListLogicas['fecha']?></td>
                                                <td><?php echo $rowCheckListLogicas['ecbos']?></td>
                                                <td><?php echo $rowCheckListLogicas['controlador']?></td>
                                                <td>
                                                    <?php
                                                    $sqlidTG1 = "SELECT * FROM `trabajos_general` WHERE idTG = $idTG1";
                                                    $residTG1 = mysqli_query($conexion,$sqlidTG1);
                                                    while($rowidTG1 = $residTG1->fetch_assoc()){
                                                        $folioTG1 = $rowidTG1['folioTG'];
                                                        echo $folioTG1;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/SDO-CheckList-Logica-Programacion.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idCheckLogica?>">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG1?>">
                                                        <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Tabla de Check List - Ucas</legend>
                                    <table id="checklistucas" class="table table-bordered table-sm" style="text-align: center">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">UCA</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $countUCAS = 1;
                                        $sqlUcas = "SELECT * FROM datos_checklist_ucas ORDER BY uca ASC";
                                        $resUcas = mysqli_query($conexion,$sqlUcas);
                                        while($rowUcas = $resUcas->fetch_assoc()){
                                            $fechaUca = $rowUcas['fecha'];
                                            $uca = $rowUcas['uca'];
                                            $idCheckUCA = $rowUcas['id'];
                                            $isVisadoUCA = $rowUcas['isVisado'];
                                            $idTG3 = $rowUcas['idTG'];
                                            ?>
                                            <tr>
                                                <td><?php echo $countUCAS?></td>
                                                <td><?php echo $fechaUca?></td>
                                                <td><?php echo $uca?></td>
                                                <td>
                                                    <?php
                                                    $sqlidTG3 = "SELECT * FROM `trabajos_general` WHERE idTG = $idTG3";
                                                    $residTG3 = mysqli_query($conexion,$sqlidTG3);
                                                    while($rowidTG3 = $residTG3->fetch_assoc()){
                                                        $folioTG3 = $rowidTG3['folioTG'];
                                                        echo $folioTG3;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <form target="_blank" action="../sdo-pdf/SDO-CheckList-Ucas.php" method="POST">
                                                        <input type="hidden" name="idCheck" value="<?php echo $idCheckUCA?>">
                                                        <input type="hidden" name="idTG" value="<?php echo $idTG3?>">
                                                        <button type="submit" class="btn btn-sm bg-success"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                            $countUCAS++;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">UCA</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
                                <br>
                                <br>
                                <fieldset id="checklistcontroladores" name="checklistcontroladores" class="border p-3">
                                    <legend class="w-auto">Tabla de Check List - Controlador Modelo ECB</legend>
                                    <table id="example3" class="table table-bordered table-sm" style="text-align: center">
                                        <thead class="text-center bg-gradient-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sqlControlador = "SELECT * FROM `datos_checklist_controlador_ecb`";
                                        $resControlador = mysqli_query($conexion,$sqlControlador);
                                        $count = 1;
                                        while($rowControlador = $resControlador->fetch_assoc()){
                                            $idControlador = $rowControlador['id'];
                                            $fechaControlador = $rowControlador['fecha'];
                                            $fecha_formato = date('d-m-Y H:i',strtotime($fechaControlador));
                                            $ecbosControlador = $rowControlador['ecbos'];
                                            $controladorECB = $rowControlador['controlador'];
                                            $isVisadoControlador = $rowControlador['isVisado'];
                                            $idTG2 = $rowControlador['idTG'];
                                            ?>
                                            <tr>
                                                <td><?php echo $count?></td>
                                                <td><?php echo $fecha_formato?></td>
                                                <td><?php echo $ecbosControlador?></td>
                                                <td><?php echo $controladorECB?></td>
                                                <td>
                                                    <?php
                                                    $sqlidTG2 = "SELECT * FROM `trabajos_general` WHERE idTG = $idTG2";
                                                    $residTG2 = mysqli_query($conexion,$sqlidTG2);
                                                    while($rowidTG2 = $residTG2->fetch_assoc()){
                                                        $folioTG2 = $rowidTG2['folioTG'];
                                                        echo $folioTG2;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <form action="../sdo-pdf/SDO-CheckList-Cotrolador-ECB.php" target="_blank" method="POST">
                                                        <input type="hidden" id="idCheck" name="idCheck" value="<?php echo $idControlador?>">
                                                        <input type="hidden" id="idTG" name="idTG" value="<?php echo $idTG2?>">
                                                        <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-eye"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                        ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="font-size: 15px">N°</th>
                                            <th style="font-size: 15px">Fecha de Ingreso</th>
                                            <th style="font-size: 15px">EC-BOS</th>
                                            <th style="font-size: 15px">Controlador</th>
                                            <th style="font-size: 15px">Folio OT</th>
                                            <th style="font-size: 15px">Visualizar</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </fieldset>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": false,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
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
<script>
    $('#example3').DataTable({
        "paging": true,
        "pageLength": 10,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": false,
        "language": {
            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
            "zeroRecords": "No se han encontrado Registros.",
            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
            "infoEmpty": "No existen Registros.",
            "search": "Buscar :",
            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            }
        }
    });
</script>
<script>
    $('#checklistlogica').DataTable({
        "paging": true,
        "pageLength": 10,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": false,
        "language": {
            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
            "zeroRecords": "No se han encontrado Registros.",
            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
            "infoEmpty": "No existen Registros.",
            "search": "Buscar :",
            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            }
        }
    });
</script>
<script>
    $('#checklistucas').DataTable({
        "paging": true,
        "pageLength": 10,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": false,
        "language": {
            "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
            "zeroRecords": "No se han encontrado Registros.",
            "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
            "infoEmpty": "No existen Registros.",
            "search": "Buscar :",
            "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            }
        }
    });
</script>
</body>
</html>

