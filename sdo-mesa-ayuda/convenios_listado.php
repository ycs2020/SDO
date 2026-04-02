<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';


date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Convenios</title>

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
                            <h4><b>Convenios</b></h4>
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
                                    <a href="index.php" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="text-align:center;">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#agregar">
                                        <i class="fas fa-plus"></i>&nbsp;&nbsp; <b>Agregar Convenio</b>
                                    </button>

                                    <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregar Convenio</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="../sdo-funciones/agregar_registros.php" method="post">
                                                    <div class="modal-body">
                                                        <label>Nombre del Convenio</label>
                                                        <input type="text" name="nombreConvenio" class="form-control" placeholder="Nombre del Convenio" required>
                                                        <br>
                                                        <label>ID Licitacion</label>
                                                        <input type="text" name="idLicitacion" required class="form-control">
                                                        <br>
                                                        <label>Duración</label>
                                                        <input type="text" name="duracion" required class="form-control">
                                                        <br>
                                                        <label>Total del Convenio</label>
                                                        <input type="text" name="totalConvenio" required class="form-control">
                                                    </div>
                                                    <div class="modal-footer">
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
                                <div class="card-header">
                                </div>
                                <div class="card-body">
                                    <table class="table text-center">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <td>Nombre del Convenio</td>
                                                <td>Duración</td>
                                                <td>ID Licitacion</td>
                                                <td>Total Convenio</td>
                                                <td>Total Consumo</td>
                                                <td>Acciones</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sqlConvenios = "SELECT * FROM convenios_carola ORDER BY id DESC";
                                                $resultConvenios = mysqli_query($conexion, $sqlConvenios);
                                                while($rowConvenios = mysqli_fetch_assoc($resultConvenios)){
                                                    $idConvenio = $rowConvenios['id'];
                                                    $nombreConvenio = $rowConvenios['convenio'];
                                                    $duracion = $rowConvenios['duracion'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $nombreConvenio?></td>
                                                        <td><?php echo $duracion?></td>
                                                        <td><?php echo $rowConvenios['id_licitacion']?></td>
                                                        <td><?php echo '$'.number_format($rowConvenios['total_convenio'], 0, ',', '.'); ?></td>
                                                        <td>
                                                            <?php
                                                                $sqlSuma = "SELECT SUM(monto) as total FROM `registros_convenios` WHERE idConvenio = $idConvenio";
                                                                $resSuma = mysqli_query($conexion, $sqlSuma);
                                                                while($rowSuma = $resSuma->fetch_assoc()){
                                                                    echo '$'.number_format($rowSuma['total'], 0, ',', '.');
                                                                }

                                                            ?>
                                                        </td>
                                                        <td style="display: flex; justify-content: center; align-items: center; gap: 5px;">

                                                            <form action="revisar_registros.php" method="post" style="margin: 0;">
                                                                <button type="submit" class="btn btn-sm bg-success">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <input type="hidden" name="idConvenio" value="<?php echo $idConvenio?>">
                                                            </form>

                                                            <!-- Segundo botón fuera del form -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarConvenio<?php echo $idConvenio?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="editarConvenio<?php echo $idConvenio?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Editar Convenio</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="../sdo-funciones/modificar_convenios.php" method="post">
                                                                            <input type="hidden" name="idConvenio" value="<?php echo $idConvenio?>">
                                                                            <div class="modal-body">
                                                                                <label>Nombre del Convenio</label>
                                                                                <input type="text" value="<?php echo $nombreConvenio?>" name="nombreConvenio" class="form-control" placeholder="Nombre del Convenio" required>
                                                                                <br>
                                                                                <label>ID Licitacion</label>
                                                                                <input type="text" value="<?php echo $rowConvenios['id_licitacion']?>" name="idLicitacion" required class="form-control">
                                                                                <br>
                                                                                <label>Duración</label>
                                                                                <input type="text" value="<?php echo $duracion?>" name="duracion" required class="form-control">
                                                                                <br>
                                                                                <label>Total del Convenio</label>
                                                                                <input type="text" value="<?php echo $rowConvenios['total_convenio']?>" name="totalConvenio" required class="form-control">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                                <button type="submit" class="btn btn-primary">Modificar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <td>Nombre del Convenio</td>
                                            <td>Duración</td>
                                            <td>ID Licitacion</td>
                                            <td>Total Convenio</td>
                                            <td>Total Consumo</td>
                                            <td>Acciones</td>
                                        </tr>
                                        </tfoot>
                                    </table>
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
</body>
</html>