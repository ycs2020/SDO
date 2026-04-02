<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

date_default_timezone_set('America/Santiago');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// echo '';
$idCategoria = $_POST['idCategoria'];


//VARIABLES DE SESSION

$rolUsuario = $_SESSION['rolUsuario'];
$rolUsuario2 = strtolower($rolUsuario);


if ($rolUsuario2 == "personal") {

    $sql = "SELECT * FROM personal";
    $res = mysqli_query($sql, $conexion);

    while($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['departamentoPersonal'] = $row['departamentoPersonal'];
        $idDepartamento = $datos['departamentoPersonal'];
    }

}else if ($rolUsuario2 == "jefe tecnico") {

    $sql = "SELECT * FROM jefes_tecnicos";
    $res = mysqli_query($sql, $conexion);

    while($row = $res->fetch_assoc()) {
        $datos = [];
        $datos['idDepartamento'] = $row['idDepartamento'];
        $idDepartamento = $datos['idDepartamento'];
    }
}
$codigoAleatorio = generarCodigoAleatorio();

// Construir ID completo
$sqlx = "SELECT MAX(id) AS ultimo_id FROM equipamiento_hbqp";
$resx = mysqli_query($conexion,$sqlx);
while($rowx = $resx->fetch_assoc()){
    $ultimo_id = $rowx['ultimo_id'];
    $ultimo_id = $ultimo_id + 1;
}

$idGenerado = 'EQUIP-HBQP-' . $ultimo_id;

// Función para generar código aleatorio
function generarCodigoAleatorio()
{
    // Longitud del código aleatorio
    $longitud = 6;

    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    $max = strlen($caracteres) - 1;
    // Generar caracteres aleatorios
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[mt_rand(0, $max)];
    }
    return $codigo;
}

if($_POST['idCategoria']){
    $idCategoriaExter = $_POST['idCategoria'];
    $_SESSION['idCarpeta'] = $idCategoriaExter;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>
    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-flag-icons/3.5.0/css/country-flag-icons.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="preloader  flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
    <br>
    <div class="spinner-border text-danger" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="wrapper">
    <?php
    include '../sdo-templates/directiva-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <br>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- general form elements -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h1 class="card-title"> <b>Registre la información del nuevo equipo</b> </h1>
                        <a href="carpeta-equipo.php" class="btn btn-danger" style="float: right;"><b><i class="fas fa-arrow-left"></i> Volver Atras</b> </a>
                    </div>
                    <div class="row">
                        <!-- left column -->

                        <div class="col-md-12">
                            <!-- /.card-header -->
                            <div class="card-body">

                                <p class="text-bold text-danger">(*) Campos Obligatorios</p>

                                <form action="../sdo-funciones/guardar-equipo.php" method="post">

                                    <input type="hidden" name="idDepartamento" value="<?php  $idDepartamento ?>">
                                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria ?>">

                                    <fieldset>
                                        <legend>Información Técnica</legend>

                                        <div class="form-row">

                                            <div class="form-group col-md-2 col-12">
                                                <label for="inputIDUnico" style="font-size: 12px;">ID Único: <code>*</code></label>
                                                <input type="text" class="form-control form-control-sm" required readonly name="inputIDUnico" id="inputIDUnico" value="<?php echo $idGenerado ?>" placeholder="Escriba el Identificador unico del equipo">

                                            </div>
                                            <div class="col-md-1">
                                                <div style="display: inline ; margin-left: 20px; font-size: 20px">
                                                    <a class="pop-me-over" data-toggle="popover" data-placement="right" data-content="El ID Único es generado aleatoriamente y es la identificación del equipo dentro del sistema, guarde este código." data-title="Información"><i class="fas fa-question-circle"></i></a>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="inputInventario" style="font-size: 12px;">N° de Inventario</label>
                                                <input type="text" class="form-control form-control-sm" id="inputInventario" name="inputInventario" placeholder="Escriba el numero de inventario del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="IDLicitacion" style="font-size: 12px;">ID - Licitación: </label>
                                                <input type="text" class="form-control form-control-sm" id="IDLicitacion" name="IDLicitacion" placeholder="Escriba el id de litación del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="ordenCompra" style="font-size: 12px;">Orden de compra:</label>
                                                <input type="text" class="form-control form-control-sm" id="ordenCompra" name="ordenCompra">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="inputMantenimiento" style="font-size: 12px;">F. Mantenimiento</label>
                                                <input type="text" class="form-control form-control-sm" name="inputMantenimiento" id="inputMantenimiento">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaRecepcion" style="font-size: 12px;">Fecha de recepción</label>
                                                <input type="date" class="form-control form-control-sm" name="fechaRecepcion" id="fechaRecepcion">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaCompra" style="font-size: 12px;">Fecha de Compra:</label>
                                                <input type="date" class="form-control form-control-sm" name="fechaCompra" id="fechaCompra" name="fechaCompra">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaMarcha" style="font-size: 12px;">Fecha en Marcha:</label>
                                                <input type="date" class="form-control form-control-sm" name="fechaMarcha" id="fechaMarcha" name="fechaMarcha">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="clase" style="font-size: 12px;">Clase:</label>
                                                <input type="text" class="form-control form-control-sm" id="clase" name="clase">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="subClase" style="font-size: 12px;">Sub-Clase:</label>
                                                <input type="text" class="form-control form-control-sm" id="subClase" name="subClase">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="complejidad" style="font-size: 12px;">Complejidad Tecnológica:</label>
                                                <input type="text" class="form-control form-control-sm" id="complejidad" name="complejidad">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="riesgo" style="font-size: 12px;">Nivel de riesgo:</label>
                                                <input type="text" class="form-control form-control-sm" id="riesgo" name="riesgo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="costoMantencion" style="font-size: 12px;">Costo Mantención Anual: $</label>
                                                <input type="text" class="form-control form-control-sm" id="costoMantencion" name="costoMantencion">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <fieldset>
                                        <legend>Información Básica</legend>

                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-12">
                                                <label for="nombreEquipo" style="font-size: 12px;">Nombre del equipo: <code>*</code></label>
                                                <input type="text" class="form-control form-control-sm" required id="nombreEquipo" name="nombreEquipo" placeholder="Escriba el nombre del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="valorConIva" style="font-size: 12px;">Valor con IVA:</label>
                                                <input type="number" class="form-control form-control-sm" id="valorConIva" name="valorConIva">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="marcaEquipo" style="font-size: 12px;">Marca</label>
                                                <input type="text" class="form-control form-control-sm" id="marcaEquipo" name="marcaEquipo" placeholder="Escriba la marca del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="modeloEquipo" style="font-size: 12px;">Modelo</label>
                                                <input type="text" class="form-control form-control-sm" id="modeloEquipo" name="modeloEquipo" placeholder="Escriba el modelo del equipo">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="fechaFabricación" style="font-size: 12px;">Año de fabricación</label>
                                                <input type="text" class="form-control form-control-sm" id="fechaFabricación" name="fechaFabricación">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="vidaUtil" style="font-size: 12px;">Vida Útil (años):</label>
                                                <input type="text" class="form-control form-control-sm" id="vidaUtil" name="vidaUtil">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="versionSoftware" style="font-size: 12px;">Versión de Software:</label>
                                                <input type="text" class="form-control form-control-sm" id="versionSoftware" name="versionSoftware">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="paisOrigen" style="font-size: 12px;">Pais de origen:</label>
                                                <input type="text" class="form-control form-control-sm" id="paisOrigen" name="paisOrigen">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="terminoGarantia" style="font-size: 12px;">Termino de garantía:</label>
                                                <input type="text" class="form-control form-control-sm" id="terminoGarantia" name="terminoGarantia">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="numSerie" style="font-size: 12px;">Numero de serie:</label>
                                                <input type="text" class="form-control form-control-sm" id="numSerie" name="numSerie">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <fieldset>
                                        <legend>Ubicación</legend>
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-12">
                                                <label for="IDLicitacion" style="font-size: 12px;">Servicio: <code>*</code></label>
                                                <select class="form-control form-control-sm" id="servicio" required name="servicio">
                                                    <option value="" disabled selected>-- Seleccione --</option>

                                                    <?php

                                                    $sqlServicios = "SELECT * FROM servicios";
                                                    $resServicios = mysqli_query($conexion, $sqlServicios);

                                                    while ($rowServicios = $resServicios->fetch_assoc()) {
                                                        $datos = [];

                                                        $datos['idServicio'] = $rowServicios['idServicio'];
                                                        $datos['nomServicio'] = $rowServicios['nomServicio'];
                                                        $idServicio  = $datos['idServicio'];
                                                        $nomServicio  = $datos['nomServicio'];

                                                        ?>

                                                        <option value="<?php echo $idServicio ?>"><?php echo mb_strtoupper($nomServicio, 'UTF-8') ?></option>

                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="recinto" style="font-size: 12px;">Recinto:</label>
                                                <input type="text" class="form-control form-control-sm" id="recinto" name="recinto">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="pma" style="font-size: 12px;">PMA:</label>
                                                <input type="text" class="form-control form-control-sm" id="pma" name="pma">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Coordenadas:</label>
                                                <input type="text" class="form-control form-control-sm" id="coordenadas" name="coordenadas">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Piso: <code>*</code> <code>Coloque un 0 si no conoce el piso</code></label>
                                                <input type="text"  required class="form-control form-control-sm" id="piso" name="piso">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="coordenadas" style="font-size: 12px;">Departamento al que pertenece: <code>*</code></label>
                                                <select name="iddepartamento" id="iddepartamento" class="form-control form-control-sm" required>
                                                    <option value="selected">
                                                        Seleccione un departamento
                                                    </option>
                                                    <?php
                                                    $sqlDepa = "SELECT * FROM departamentos";
                                                    $resDepa = mysqli_query($conexion,$sqlDepa);
                                                    while($rowDepa = $resDepa->fetch_assoc()){
                                                        $datos1 = [];
                                                        $datos1['nomDepartamento'] = $rowDepa['nomDepartamento'];
                                                        $datos1['idDepartamento'] = $rowDepa['idDepartamento'];
                                                        $idDepartamento1 = $datos1['idDepartamento'];
                                                        $nomDepartamento = $datos1['nomDepartamento'];

                                                        ?>
                                                        <option value="<?php echo $idDepartamento1?>">
                                                            <?php echo $nomDepartamento?>
                                                        </option>
                                                        <?php
                                                    }

                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr>
                                    <button type="submit" class="btn btn-success btn-block text-center"><b> Guardar Equipo</b></button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col  -->
    </div>
    <!-- /.row -->
    </section>
    <!-- /.content -->
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
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
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
<!-- Page specific script -->
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<script>
    $(function() {
        $('[data-toggle="popover"]').popover()
    })
</script>
<script src="js/dark-mode.js"></script>

</body>

</html>