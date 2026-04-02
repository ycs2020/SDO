<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nomUsuario = $_SESSION['nomUsuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear HT</title>

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

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>

    <script src="https://kit.fontawesome.com/e2e5c3fa16.js" crossorigin="anonymous"></script>

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <script>
        function mostrarInputs1(radio) {
            var panolDiv = document.getElementById('rango');
        }
    </script>
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
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- TITULO INTERFAZ -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <a style="pointer-events: none;" class="btn font-weight-bold">Formulario de Hoja Tecnica</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="index.php" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>
                    <!-- FIN TITULO INTERFAZ -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="mb-0">
                                    <code>*</code> <b class="text-danger">Campo obligatorio</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div id="accordion">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100 font-weight-bold" data-toggle="collapse" href="#infoSolicitud">
                                            1.- INFORMACI&Oacute;N DE LA SOLICITUD
                                        </a>
                                    </h4>
                                </div>
                                <div id="infoSolicitud" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="horarioInicio">Inicio Fecha/Hora:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    </div>
                                                    <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioInicio" name="horarioInicio" value="">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="horarioTermino">Termino Fecha/Hora:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    </div>
                                                    <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="horarioTermino" name="horarioTermino" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="ticketAsociado">Ticket Asociado:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize" class="form-control" id="ticketAsociado" name="ticketAsociado" value="" placeholder="Escriba el ticket asociado">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="noOrdenTrabajo">N° Orden de Trabajo:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-file"></i></span>
                                                    </div>
                                                    <input type="text" style="text-transform:capitalize"
                                                            class="form-control" id="noOrdenTrabajo"
                                                            name="noOrdenTrabajo" value=""
                                                            placeholder="Escriba el N° de Orden de Trabajo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <!-- Departamento -->
                                            <div class="form-group col-md-6">
                                                <label for="departamentoSolicitante">Departamento:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                    </div>
                                                    <select id="departamentoSolicitante" name="departamentoSolicitante" class="form-control" required>
                                                        <?php
                                                        $query = "SELECT * FROM departamentos 
                                                                  WHERE idDepartamento = $idDepartamento 
                                                                  AND nomDepartamento NOT LIKE('%Control Centralizado%') 
                                                                  ORDER BY nomDepartamento ASC";
                                                        $result = mysqli_query($conexion, $query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="' . htmlspecialchars($row['idDepartamento']) . '">' . htmlspecialchars($row['nomDepartamento']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Técnicos Recepcionadores -->
                                            <div class="form-group col-md-6">
                                                <label for="listadoUsuarios">
                                                    Técnico Recepcionador: <code>* (Máximo 4 Personas)</code>
                                                </label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <select id="listadoUsuarios" name="listadoUsuarios[]" class="form-control listadoUsuarios" multiple required onchange="limitarSeleccion(this)">
                                                        <option value="" disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlUsuarios = "
                                                            SELECT jt.idJefe AS id, jt.nomJefe AS nombre, 'Encargados y Profesionales' AS categoria 
                                                            FROM jefes_tecnicos jt 
                                                            WHERE jt.nomJefe NOT LIKE('%Jaime Amador Estay Jorquera%') 
                                                            AND jt.idDepartamento = '$idDepartamento'
                                                            AND userActivado
                                                            UNION
                                                            SELECT p.rutPersonal AS id, p.nomPersonal AS nombre, 'Técnicos y Auxiliares' AS categoria 
                                                            FROM personal p 
                                                            WHERE p.departamentoPersonal = '$idDepartamento'
                                                        ";

                                                        $resUsuarios = mysqli_query($conexion, $sqlUsuarios);
                                                        $usuarios = [];

                                                        while ($row = $resUsuarios->fetch_assoc()) {
                                                            $usuarios[] = [
                                                                'nombre' => $row['nombre'],
                                                                'categoria' => $row['categoria']
                                                            ];
                                                        }

                                                        // Ordenar por categoría y nombre
                                                        usort($usuarios, function ($a, $b) {
                                                            return $a['categoria'] === $b['categoria']
                                                                ? strcmp($a['nombre'], $b['nombre'])
                                                                : strcmp($a['categoria'], $b['categoria']);
                                                        });

                                                        // Agrupar por categoría
                                                        $agrupados = [];
                                                        foreach ($usuarios as $u) {
                                                            $agrupados[$u['categoria']][] = $u['nombre'];
                                                        }

                                                        foreach ($agrupados as $categoria => $nombres) {
                                                            echo '<optgroup label="' . htmlspecialchars($categoria) . '">';
                                                            foreach ($nombres as $nombre) {
                                                                echo '<option value="' . htmlspecialchars($nombre) . '">' . htmlspecialchars($nombre) . '</option>';
                                                            }
                                                            echo '</optgroup>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Script para limitar selección a 4 personas -->
                                        <script>
                                            function limitarSeleccion(select) {
                                                const maxSeleccion = 4;
                                                const seleccionados = $(select).val() || [];

                                                if (seleccionados.length >= maxSeleccion) {
                                                    $(select).find('option:not(:selected)').prop('disabled', true);
                                                    if (seleccionados.length === maxSeleccion) {
                                                        Swal.fire({
                                                            icon: 'info',
                                                            title: 'Límite alcanzado',
                                                            text: 'Ya has seleccionado 4 personas.',
                                                            confirmButtonText: 'Aceptar'
                                                        });
                                                    }
                                                } else {
                                                    $(select).find('option').prop('disabled', false);
                                                }
                                            }
                                        </script>



                                    </div>
                                </div>
                            </div>
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100 font-weight-bold" data-toggle="collapse" href="#detalleSolicitud">
                                            2.- DETALLE DE LA SOLICITUD
                                        </a>
                                    </h4>
                                </div>
                                <div id="detalleSolicitud" class="collapse" data-parent="#accordion">
                                    <div class="card-body">

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
<!-- Page specific script -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Page specific script -->

<script>

    $(document).ready(function () {

        //Initialize Select2 Elements
        $('.listadoUsuarios').select2({
            theme: 'bootstrap4'
        })

    });
</script>
</body>

</html>

