<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$horaActual = date('H');
$nomUsuario = $_SESSION['nomUsuario'];
$horaActual = date('H');
$idUsuario = $_SESSION['idUsuario'];
$idDepartamento = $_SESSION['idDepartamento'];


if ($_POST['id']) {
    $id = $_POST['id'];
    if (!empty($id)) {
        $sql = "SELECT * FROM `registro_general_comunicacion` WHERE id = $id";
        $res = mysqli_query($conexion, $sql);
        while ($row = $res->fetch_assoc()) {
            $responsables = $row['responsables'];
            $obs = $row['obs'];
            $fecha = $row['fecha'];
            $turno = $row['turno'];
            $tema = $row['tema'];
            $inicio = $row['inicio'];
            $fin = $row['fin'];
            $asistentes = $row['asistentes'];

        }
    } else {
        header('Location: ../sdo-personal/registros_comunicacion.php?errorModificarRegistro=true');
    }
} else {
    header('Location: ../sdo-personal/registros_comunicacion.php?errorModificarRegistro=true');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar registro general de comunicación</title>

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
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* select2-custom.css */
        /* Estilos para modo claro */
        .select2-container--bootstrap4 .select2-results__option {
            color: #000000; /* Color rojo para el texto en modo claro */
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo claro */
            background-color: #b1b1b3;
        }

        /* Estilos para modo oscuro */
        .dark-mode .select2-container--bootstrap4 .select2-results__option {
            color: #ffffff; /* Color rojo para el texto en modo oscuro */

        }

        .dark-mode .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice,
        .dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #000000; /* Color rojo para el texto de elementos seleccionados en modo oscuro */
            background-color: #b1b1b3;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';

    $_SESSION['id'] = $id;
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Modificar Registro General de Comunicación</h1>
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
                        <div class="card card-primary">
                            <div class="card-body">
                                <a href="registros_comunicacion.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                        <div class="card">
                            <form action="../sdo-funciones/modificar-registro-general-comunicacion.php" method="POST">
                                <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Responsable de la actividad:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <?php
                                                $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                $resDepto = mysqli_query($conexion, $sqlDepto);

                                                $departamentos = [];
                                                $empleados = [];
                                                $asistentesLol = [];

                                                // Recolectar departamentos
                                                if ($resDepto->num_rows > 0) {
                                                    while ($rowDepto = $resDepto->fetch_assoc()) {
                                                        if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                            $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                        }
                                                        $departamentos[] = $rowDepto;
                                                    }

                                                    usort($departamentos, function ($a, $b) {
                                                        return strcmp($a['nomDepartamento'], $b['nomDepartamento']);
                                                    });

                                                    // Recolectar empleados para cada departamento
                                                    foreach ($departamentos as $departamento) {
                                                        $idDeptoBuscar = $departamento['idDepartamento'];

                                                        // Consulta SQL para obtener empleados
                                                        $sqlUsuarios = "
                                                                    SELECT nomPersonal AS nombre 
                                                                    FROM personal 
                                                                    WHERE departamentoPersonal = $idDeptoBuscar 
                                                                    AND activoPersonal = 1
                                                                    
                                                                    UNION ALL
                                                                    
                                                                    SELECT nomJefe AS nombre
                                                                    FROM jefes_tecnicos
                                                                    WHERE idDepartamento = $idDeptoBuscar
                                                                    AND userActivado = 1
                                                                    
                                                                    ";

                                                        $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                        if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                            while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                $empleados[] = trim($rowUsuarios['nombre']);
                                                                $asistentesLol[] = trim($rowUsuarios['nombre']);
                                                            }
                                                        }
                                                    }

                                                }

                                                $sqlAdmin = "SELECT nomAdministrador AS nombre FROM administracion WHERE adminActivo = 1";

                                                $resAdmin = mysqli_query($conexion, $sqlAdmin);

                                                if ($resAdmin  && $resAdmin->num_rows > 0) {
                                                    while ($rowAdmin  = $resAdmin->fetch_assoc()) {
                                                        $empleados[] = trim($rowAdmin ['nombre']);
                                                        $asistentesLol[] = trim($rowAdmin ['nombre']);
                                                    }
                                                }

                                                ?>
                                                <select class="form-control listadoEspecialidades" required id="responsable" name="responsable[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="Seleccione..." disabled>Seleccione...</option>
                                                    <?php
                                                    // Mostrar opciones en el primer select
                                                    foreach ($empleados as $nomEmpleado) {
                                                        $nombres = explode(" - ", $responsables);
                                                        $encontrado = false;
                                                        foreach ($nombres as $nombre) {
                                                            if (strpos($nombre, $nomEmpleado) !== false) {
                                                                $encontrado = true;
                                                                break;
                                                            }
                                                        }
                                                        if ($encontrado) {
                                                            echo '<option value="' . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . '" selected>' . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . '</option>';
                                                        } else {
                                                            echo '<option value="' . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Asistentes:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control listadoAsistentes" required id="asistentes" name="asistentes[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <?php
                                                    // Suponiendo que $asistentes es un array con nombres de asistentes predefinidos
                                                    foreach ($asistentesLol as $nomAsistentes) {
                                                        $nombresAsiste = explode(" - ", $asistentes);
                                                        $encontradoAsiste = false;

                                                        foreach ($nombresAsiste as $nombreASISTEB) {
                                                            // Verificar si el nombre actual está en el array de nombres seleccionados
                                                            if (strpos($nombreASISTEB, $nomAsistentes) !== false) {
                                                                $encontradoAsiste = true;
                                                                break;
                                                            }
                                                        }

                                                        if ($encontradoAsiste) {
                                                            echo '<option value="' . htmlspecialchars(trim($nomAsistentes), ENT_QUOTES, 'UTF-8') . '" selected>' . htmlspecialchars(trim($nomAsistentes), ENT_QUOTES, 'UTF-8') . '</option>';
                                                        } else {
                                                            echo '<option value="' . htmlspecialchars(trim($nomAsistentes), ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(trim($nomAsistentes), ENT_QUOTES, 'UTF-8') . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Tema:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" id="tema" name="tema" value="<?php echo $tema ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Turno:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <select class="form-control" required id="turno" name="turno">
                                                    <?php
                                                    if ($turno == 'D') {
                                                        ?>
                                                        <option value="" disabled>Seleccione...</option>
                                                        <option value="D" selected>Diurno (8:00 hasta las 20:00)</option>
                                                        <option value="N">Noche (20:00 hasta las 8:00)</option>
                                                        <?php
                                                    } else if ($turno == 'N') {
                                                        ?>
                                                        <option value="" disabled>Seleccione...</option>
                                                        <option value="D">Diurno (8:00 hasta las 20:00)</option>
                                                        <option value="N" selected>Noche (20:00 hasta las 8:00)</option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        <option value="D">Diurno (8:00 hasta las 20:00)</option>
                                                        <option value="N">Noche (20:00 hasta las 8:00)</option>
                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Inicio:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="text" id="inicio" name="inicio" value="<?php echo $inicio ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputNomEncargadoTurno">Fin:<code>*</code></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="text" id="fin" name="fin" value="<?php echo $fin ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputNomEncargadoTurno">Temas y acuerdos de la Reunion:<code>*</code></label>
                                            <textarea class="form-control" style="resize: none" name="obs" id="obs" rows="10"><?php echo $obs ?></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-block bg-success">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<footer class="main-footer">
    <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
</body>
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
<script src="js/dark-mode.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script>
    $(document).ready(function () {
        $('.listadoEspecialidades').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.listadoAsistentes').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });
    });
</script>
</html>
