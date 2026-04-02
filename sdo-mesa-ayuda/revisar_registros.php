<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_REQUEST['idConvenio'])){
   // $idConvenio = $_POST['idConvenio'];
    $idConvenio = intval($_REQUEST['idConvenio']);

    $sqlConvenios = "SELECT * FROM convenios_carola WHERE id = $idConvenio";
    $resultConvenios = mysqli_query($conexion, $sqlConvenios);
    while($rowConvenios = mysqli_fetch_assoc($resultConvenios)){
        $idConvenio = $rowConvenios['id'];
        $nombreConvenio = $rowConvenios['convenio'];
        $duracion = $rowConvenios['duracion'];
        $totalConvenio = $rowConvenios['total_convenio'];
    }
}else{
    echo 'Sin Datos';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registros <?php echo $nombreConvenio?></title>

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
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
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
                            <h4><b>Registros: <?php echo $nombreConvenio?></b></h4>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="text-align:right;">
                                    <a href="convenios_listado.php" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <style>
                                        .monto-box {
                                            font-size: 24px;
                                            font-weight: bold;
                                        }
                                        .panel-box {
                                            background:#f8f9fa;
                                            border:1px solid #e3e3e3;
                                            padding:12px 22px;
                                            border-radius:10px;
                                            font-size:15px;
                                            box-shadow:0 2px 6px rgba(0,0,0,0.08);
                                            text-align:center;
                                            min-width:200px;
                                        }
                                    </style>

                                    <div style="display:flex; justify-content:center; align-items:center; gap:20px; flex-wrap:wrap; margin-top:15px;">

                                        <!-- Botón -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregar">
                                            <i class="fa fa-plus"></i> <b>Agregar Registros</b>
                                        </button>

                                        <!-- Panel 1 -->
                                        <div class="panel-box">
                                            <span style="color:#6c757d;">Monto Total del Convenio</span><br>
                                            <span class="monto-box" style="color:#198754;">
            <?php echo '$'.number_format($totalConvenio, 0, ',', '.'); ?>
        </span>
                                        </div>

                                        <!-- Panel 2 -->
                                        <div class="panel-box">
                                            <span style="color:#6c757d;">Monto Total Consumo General</span><br>
                                            <span class="monto-box" style="color:#007bff;">
            <?php
            $sqlPagadoRegistros = "SELECT SUM(monto) AS totalPagado 
                                  FROM registros_convenios 
                                  WHERE idConvenio = $idConvenio";
            $resPagadoRegistros = mysqli_query($conexion, $sqlPagadoRegistros);

            if($rowPagadoRegistros = $resPagadoRegistros->fetch_assoc()){
                echo '$'.number_format($rowPagadoRegistros['totalPagado'], 0, ',', '.');
            } else {
                echo '$0';
            }
            ?>
        </span>
                                        </div>

                                    </div>

                                    <!-- Modal (sin cambios) -->
                                    <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar un Registro</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="../sdo-funciones/agregar_registros.php" method="post">
                                                    <div class="modal-body">
                                                        <label>Fecha</label>
                                                        <input type="month" name="fecha" class="form-control">
                                                        <br>
                                                        <label>OC</label>
                                                        <input type="text" name="oc" class="form-control">
                                                        <br>
                                                        <label>Monto $</label>
                                                        <input type="number" name="monto" class="form-control">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="idConvenio" value="<?php echo $idConvenio ?>">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Modificar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-0">

                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs">
                                        <?php
                                        $stmt = $conexion->prepare("
                                            SELECT YEAR(fecha) as ano 
                                            FROM registros_convenios 
                                            WHERE idConvenio = ? 
                                            GROUP BY YEAR(fecha) 
                                            ORDER BY YEAR(fecha) DESC
                                        ");
                                        $stmt->bind_param("i", $idConvenio);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        $primero = true;
                                        $anios = [];

                                        while ($row = $result->fetch_assoc()) {
                                            $year = (int)$row['ano'];
                                            $anios[] = $year;
                                        }

                                        foreach ($anios as $year) {
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?= $primero ? 'active' : '' ?>"
                                                        data-toggle="tab"
                                                        href="#tab<?= $year ?>">
                                                    <?= $year ?>
                                                </a>
                                            </li>
                                            <?php
                                            $primero = false;
                                        }
                                        ?>
                                    </ul>

                                    <!-- Contenido de tabs -->
                                    <div class="tab-content p-3">
                                        <?php
                                        $primero = true;

                                        foreach ($anios as $year) {
                                            ?>
                                            <div class="tab-pane fade <?= $primero ? 'show active' : '' ?>"
                                                    id="tab<?= $year ?>">

                                                <h5 class="text-center">Año <?= $year ?></h5>
                                                <hr>
                                                <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-top:15px;">

                                                    <!-- Segundo panel (ejemplo con otra suma desde SQL) -->
                                                    <div style="
                                            background:#f8f9fa;
                                            border:1px solid #e3e3e3;
                                            padding:12px 22px;
                                            border-radius:10px;
                                            font-size:15px;
                                            box-shadow:0 2px 6px rgba(0,0,0,0.08);
                                            text-align:center;
                                            min-width:200px;
                                        ">
                                                        <span style="color:#6c757d;">Monto Total Consumo</span><br>
                                                        <span style="font-size:24px; font-weight:bold; color:#007bff;">
                                            <?php
                                            $sqlPagado = "SELECT SUM(monto) AS totalPagado 
                                            FROM registros_convenios 
                                            WHERE idConvenio = $idConvenio 
                                            AND YEAR(fecha) = $year";
                                            $resPagado = mysqli_query($conexion, $sqlPagado);

                                            if($rowPagado = $resPagado->fetch_assoc()){
                                                echo '$'.number_format($rowPagado['totalPagado'], 0, ',', '.');
                                            } else {
                                                echo '$0';
                                            }
                                            ?>
                                            </span>
                                                    </div>
                                            </div>
                                            <br>
                                            <table id="example2_<?php echo $year?>" class="table table-hover table-bordered text-center align-middle shadow-sm">
                                                <thead style="background:#343a40; color:white;">
                                                <tr>
                                                    <th style="width:30%;">📅 Fecha</th>
                                                    <th style="width:35%;">📄 OC</th>
                                                    <th style="width:35%;">💰 Monto</th>
                                                    <th style="width:35%;">
                                                        ✏️ Acciones
                                                    </th>
                                                </tr>
                                                </thead>
                                                <?php
                                                $totalCostos = 0;
                                                ?>

                                                <tbody>
                                                <?php
                                                $sqlRegistros = "SELECT * 
                                                FROM registros_convenios 
                                                WHERE idConvenio = $idConvenio 
                                                AND YEAR(fecha) = $year 
                                            ORDER BY fecha DESC";
                                                $resRegistros = mysqli_query($conexion, $sqlRegistros);

                                                setlocale(LC_TIME, 'es_ES.UTF-8', 'spanish');

                                                while($rowRegistros = mysqli_fetch_assoc($resRegistros)){
                                                    $idRegistro = $rowRegistros['id'];
                                                    $fecha = $rowRegistros['fecha'];
                                                    $oc = $rowRegistros['oc'];
                                                    $monto = $rowRegistros['monto'];

                                                    $totalCostos += $monto; // suma
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo ucfirst(strftime('%B-%Y', strtotime($fecha))); ?>
                                                        </td>
                                                        <td><?php echo $oc ?></td>
                                                        <td><?php echo '$'.number_format($monto, 0, ',', '.'); ?></td>
                                                        <td style="display: flex; gap: 5px; justify-content: center; align-items: center;">

                                                            <!-- Botón editar -->
                                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal<?php echo $idRegistro?>">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>

                                                            <!-- Modal editar -->
                                                            <div class="modal fade" id="exampleModal<?php echo $idRegistro?>" tabindex="-1" role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title">Modificar el Registro</h5>
                                                                            <button type="button" class="close" data-dismiss="modal">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <form action="../sdo-funciones/modificar_registros.php" method="post">
                                                                            <div class="modal-body">
                                                                                <label>Fecha</label>
                                                                                <input type="month" name="fecha" class="form-control" value="<?php echo date('Y-m', strtotime($fecha)); ?>">

                                                                                <br>

                                                                                <label>OC</label>
                                                                                <input type="text" name="oc" class="form-control" value="<?php echo $oc ?>">

                                                                                <br>

                                                                                <label>Monto $</label>
                                                                                <input type="text" name="monto" class="form-control" value="<?php echo $monto ?>">
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <input type="hidden" name="idRegistro" value="<?php echo $idRegistro ?>">
                                                                                <input type="hidden" name="idConvenio" value="<?php echo $idConvenio ?>">

                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-primary">Modificar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Botón agregar observación -->
                                                            <button title="Agregar Observacion" type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregarObs<?php echo $idRegistro?>">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="agregarObs<?php echo $idRegistro?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel"> Agregar Observaciones</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/guardar_observacion_convenios.php" method="post">
                                                                            <input type="hidden" name="idConvenio" value="<?php echo $idConvenio ?>">
                                                                            <input type="hidden" name="idRegistro" value="<?php echo $idRegistro?>"> <!-- ID del registro -->                                                                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria?>"> <!-- ID del registro -->
                                                                            <div class="modal-body">
                                                                                <label>Fecha</label>
                                                                                <?php $now = date('Y-m-d H:i:s')?>
                                                                                <input name="fecha" readonly type="text" class="form-control" value="<?php echo $now?>">
                                                                                <br>
                                                                                <label>Observaciones</label>
                                                                                <textarea name="obs" class="form-control" rows="15" style="resize: none"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button title="Listar Observacion" type="button" class="btn btn-primary" data-toggle="modal" data-target="#listadoObservacion<?php echo $idRegistro?>">
                                                                <i class="fas fa-file-alt"></i>
                                                            </button>
                                                            <div class="modal fade" id="listadoObservacion<?php echo $idRegistro?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Listado de Observaciones</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table id="listadoObservacion" class="table table-bordered cell-border" style="width:100%">
                                                                                <thead class="bg-secondary">
                                                                                <tr>
                                                                                    <td>Fecha</td>
                                                                                    <td>Observación</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                $sqlListado = "SELECT * FROM `observaciones_convenios` WHERE idRegistro = $idRegistro AND idConvenio = $idConvenio ORDER BY fecha DESC";
                                                                                $resListado = mysqli_query($conexion,$sqlListado);
                                                                                while($rowListado = $resListado->fetch_assoc()){
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td><?php echo date("d-m-Y H:i:s", strtotime($rowListado['fecha'])) ?></td>
                                                                                        <td><?php echo nl2br($rowListado['obs']) ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                                <tfoot class="bg-secondary">
                                                                                <tr>
                                                                                    <td>Fecha</td>
                                                                                    <td>Observación</td>
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


                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                </tbody>


                                            </table>
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#example2_<?php echo $year?>').DataTable({
                                                            "pageLength": 5,
                                                            "order": [[0, "desc"]],
                                                            "language": {
                                                                "lengthMenu": "Mostrar _MENU_ registros",
                                                                "zeroRecords": "No se encontraron datos",
                                                                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                                                "infoEmpty": "Sin registros disponibles",
                                                                "search": "Buscar:",
                                                                "paginate": {
                                                                    "next": "Siguiente",
                                                                    "previous": "Anterior"
                                                                }
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <?php
                                            $primero = false;
                                        }
                                        ?>
                                    </div>

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

    <script src="js/dark-mode.js"></script>
    <script>
        $(function () {
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
</body>

</html>