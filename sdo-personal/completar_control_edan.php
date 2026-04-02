<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Santiago');
$now = date('Y-m-d H:i');


if(isset($_POST['id']) || isset($_SESSION['id'])){

    if (isset($_POST['id'])) {
        $idEdan = $_POST['id'];
        if($idEdan > 0){
            $_SESSION['id'] = $idEdan;
        }else{
            header('Location: listar_informe_edan.php?errorCargaEdan=true');
        }
    }else if(isset($_SESSION['id'])){
        $idEdan = $_SESSION['id'];
        if($idEdan > 0){
            $_SESSION['id'] = $idEdan;
        }else{
            header('Location: listar_informe_edan.php?errorCargaEdan=true');
        }
    }

    $sql = "SELECT * FROM informes_edan WHERE id = $idEdan";
    $res = mysqli_query($conexion,$sql);
    while($row = $res->fetch_assoc()){
        $folio = $row['folio'];
    }

}else{
    header('Location: listar_informe_edan.php?errorCargaEdan=true');
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Informe EDAN</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .overlay {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posiciona el overlay encima de todo */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparente */
            z-index: 9999; /* Asegura que esté por encima de otros elementos */
        }

        .overlay .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
    </style>
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
    include '../sdo-templates/user-sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Detalle Informe: <?php echo $folio?></h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php" id="hrefInicio" class="text-dark">Inicio</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php
        if(isset($_GET['exito'])){
            ?>
            <script>
                Swal.fire({
                    title: "Exito",
                    text: "Se ha ingresado con exito!",
                    icon: "success"
                });
            </script>
            <?php
        }

        if(isset($_GET['error1'])){
            ?>
            <script>
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error. Por favor reintente!",
                    icon: "error"
                });
            </script>
            <?php
        }
        ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-3 col-md-6 col-12 float-right" id="volverAtras2" name="volverAtras2">
                                    <!-- small box -->
                                    <a href="listar_informe_edan.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
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
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-6_3" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">6.3 Estructural</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-6_4" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">6.4 No Estructural</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-6_6" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">6.6 Vias de Acceso</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_7-tab" data-toggle="pill" href="#custom-tabs-one-6_7" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.7 Gases Clinicos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_8-tab" data-toggle="pill" href="#custom-tabs-one-6_8" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.8 Energía Eléctrica de la red de distribución</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_9-tab" data-toggle="pill" href="#custom-tabs-one-6_9" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.9 Agua Potable de la red de distribución</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_9infra-tab" data-toggle="pill" href="#custom-tabs-one-6_9infra" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.9 Agua Potable de la red de distribución (Infra)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_10-tab" data-toggle="pill" href="#custom-tabs-one-6_10" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.10 Alcantarillado</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_15-tab" data-toggle="pill" href="#custom-tabs-one-6_15" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.15 Vehículos (Camionetas o Ambulancias)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-6_24-tab" data-toggle="pill" href="#custom-tabs-one-6_24" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">6.24 Otros</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-6_3" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <form action="../sdo-funciones/guardar_control_6_3.php" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                <label>Quien dio la información</label>
                                                <!--<input type="text" class="form-control" id="acusete_6_3" name="acusete_6_3" style="width: 25%">-->
                                                <select class="form-control listadoEspecialidades" required id="acusete_6_3" name="acusete_6_3[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                    <option value="Seleccione..." disabled>Seleccione...</option>
                                                    <?php
                                                    $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                    $resDepto = mysqli_query($conexion, $sqlDepto);

                                                    $departamentos = [];

                                                    if ($resDepto->num_rows > 0) {
                                                        while ($rowDepto = $resDepto->fetch_assoc()) {
                                                            if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                            }
                                                            $departamentos[] = $rowDepto;
                                                        }

                                                        usort($departamentos,function ($a,$b){
                                                            return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                        });

                                                        foreach ($departamentos as $departamento) {
                                                            $idDeptoBuscar = $departamento['idDepartamento'];
                                                            echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                            // Consulta SQL ajustada
                                                            $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                            $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                            if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                    $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                    echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                }
                                                            }

                                                            echo '</optgroup>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <br>
                                                <label>6.3 Estructural</label>
                                                <textarea name="obs_6-3" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                            </div>
                                            <br>
                                        </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_4" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <form action="../sdo-funciones/guardar_control_6_4.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_4" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_4" name="acusete_6_4[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.4 No Estructural</label>
                                                    <br>
                                                    <textarea name="obs_6-4" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres2(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_6" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                                        <form action="../sdo-funciones/guardar_control_6_6.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_6" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_6" name="acusete_6_6[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%') 
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.6 Vias de Acceso</label>
                                                    <br>
                                                    <textarea name="obs_6-6" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres3(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_7" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <form action="../sdo-funciones/guardar_control_6_7.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_7" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_7" name="acusete_6_7[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%') 
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.7 Gases Clínicos</label>
                                                    <br>
                                                    <textarea name="obs_6-7"  oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_8" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <form action="../sdo-funciones/guardar_control_6_8.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_8" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_8" name="acusete_6_8[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%') 
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.8 Energia Electrica de la red de distribucion</label>
                                                    <br>
                                                    <textarea name="obs_6-8" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_9" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <hr>
                                        <h5 class="text-center">Industriales</h5>
                                        <hr>
                                        <form action="../sdo-funciones/guardar_control_6_9_industriales.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_9" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_9_industriales" name="acusete_6_9_industriales[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%') 
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.9 Agua potable de la red de distribución</label>
                                                    <br>
                                                    <?php
                                                    $agua = "Cantidad de almacenamiento de agua en m3 : ";
                                                    ?>
                                                    <textarea name="obs_6-9"  oninput="limitarCaracteres2(this)" maxlength="515" class="form-control" style="resize: none" rows="10"><?php echo $agua?></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_9infra" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                                        <hr>
                                        <h5 class="text-center" style="text-align: center">Infraestructura</h5>
                                        <hr>
                                        <form action="../sdo-funciones/guardar_control_6_9_infra.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_9" name="acusete_6_9[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <div class="form-group col-md-12">
                                                        <label>6.9 Agua Potable de la red de distribución</label>
                                                        <br>
                                                        <textarea name="obs_6-9" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres4(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_10" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <form action="../sdo-funciones/guardar_control_6_10.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_10" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_10" name="acusete_6_10[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.10 Alcantarillado</label>
                                                    <br>
                                                    <textarea name="obs_6-10" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres5(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_15" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <form action="../sdo-funciones/guardar_control_6_15.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_15" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_15" name="acusete_6_15[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.15 Vehiculos (Camionetas o ambulancias)</label>
                                                    <br>
                                                    <textarea name="obs_6-15" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-6_24" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                        <form action="../sdo-funciones/guardar_control_6_24.php" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $idEdan?>">
                                                    <label>Quien dio la información</label>
                                                    <!--<input type="text" class="form-control" name="acusete_6_24" style="width: 25%">-->
                                                    <select class="form-control listadoEspecialidades" required id="acusete_6_24" name="acusete_6_24[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        $sqlDepto = "SELECT idDepartamento, nomDepartamento FROM departamentos ORDER BY nomDepartamento";
                                                        $resDepto = mysqli_query($conexion, $sqlDepto);

                                                        $departamentos = [];

                                                        if ($resDepto->num_rows > 0) {
                                                            while ($rowDepto = $resDepto->fetch_assoc()) {
                                                                if ($rowDepto['nomDepartamento'] == 'Instrumentación') {
                                                                    $rowDepto['nomDepartamento'] = 'Control Centralizado';
                                                                }
                                                                $departamentos[] = $rowDepto;
                                                            }

                                                            usort($departamentos,function ($a,$b){
                                                                return strcmp($a['nomDepartamento'],$b['nomDepartamento']);
                                                            });

                                                            foreach ($departamentos as $departamento) {
                                                                $idDeptoBuscar = $departamento['idDepartamento'];
                                                                echo '<optgroup label="' . htmlspecialchars($departamento['nomDepartamento'], ENT_QUOTES, 'UTF-8') . '">';

                                                                // Consulta SQL ajustada
                                                                $sqlUsuarios = "
                                                                SELECT nomPersonal AS nombre 
                                                                FROM personal 
                                                                WHERE departamentoPersonal = $idDeptoBuscar 
                                                                AND activoPersonal = 1 
                                                                UNION 
                                                                SELECT nomJefe AS nombre 
                                                                FROM jefes_tecnicos 
                                                                WHERE idDepartamento = $idDeptoBuscar 
                                                                AND userActivado = 1 AND nomJefe NOT LIKE('%Jaime Amador%') AND nomJefe NOT LIKE('%Encargado Pruebas%')
                                                                ORDER BY nombre;
                                                            ";

                                                                $resUsuarios = mysqli_query($conexion, $sqlUsuarios);

                                                                if ($resUsuarios && $resUsuarios->num_rows > 0) {
                                                                    while ($rowUsuarios = $resUsuarios->fetch_assoc()) {
                                                                        $nomEmpleado = trim($rowUsuarios['nombre']); // Corrección: usa 'nombre' que es el alias en la consulta

                                                                        echo "<option value=\"" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "\">" . htmlspecialchars($nomEmpleado, ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }

                                                                echo '</optgroup>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <br>
                                                    <label>6.24 Otros</label>
                                                    <br>
                                                    <textarea name="obs_6-24" onkeydown="bloquearSaltoDeLinea(event)" oninput="limitarCaracteres(this)" maxlength="515" class="form-control" style="resize: none" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-block bg-gradient-success">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
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
<script>
    function limitarCaracteres(element) {
        var maxLength = parseInt(element.getAttribute("maxlength"));
        if (element.value.length > maxLength) {
            element.value = element.value.substring(0, maxLength);
        }
    }
</script>
<script>

    $(document).ready(function () {
        $('.listadoEspecialidades').select2({
            placeholder: 'Técnicos de Especialidades',
            theme: 'bootstrap4',
            allowClear: true
        });
    });
</script>
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
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Page specific script -->
<script src="js/dark-mode.js"></script>
</body>
</html>
