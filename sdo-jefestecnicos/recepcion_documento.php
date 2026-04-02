<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

$idDepartamento = $_SESSION['idDepartamento'];
$nomUsuario = $_SESSION['nomUsuario'];

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
    <title>Recepcion del Documento</title>

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
    <style>
        .rotar {
            transform: rotate(180deg);
            transition: transform 0.7s ease;
        }

        .animacion_agrandar:hover {
            animation-name: bounce;
            animation-duration: 1s;
        }
    </style>
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

                            <h3> <b>Formulario de Recepcion de Documentos</b> </h3>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title"> <b> Formulario de Recepcion de Documentos</b></h4>
                                </div>
                                <form action="../sdo-funciones/ingresar_recepcion_documento.php" method="post" enctype="multipart/form-data">
                                     <div class="card-body">
                                         <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                         <br>
                                         <br>
                                         <p><code>*</code> <b style="color:red">Campo obligatorio</b> </p>
                                         <br>
                                         <fieldset class="border p-3">
                                             <legend class="w-auto">&nbsp;Informaci&oacute;n de Control&nbsp;</legend>
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Fecha de Recepcion del Documento: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                         </div>
                                                         <?php
                                                         $now = date('Y-m-d H:i');
                                                         ?>
                                                         <input type="datetime-local"  required style="text-transform:capitalize" class="form-control" id="fechaRecepcion" name="fechaRecepcion" value="<?php echo $now?>">
                                                     </div>
                                                 </div>
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Nombre Recepcion: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                         </div>

                                                         <input type="text" required style="text-transform:capitalize" class="form-control" id="nomRecepcion" name="nomRecepcion">
                                                     </div>
                                                 </div>
                                             </div>
                                         </fieldset>
                                         <br>
                                         <fieldset class="border p-3">
                                             <legend class="w-auto">&nbsp;Informaci&oacute;n del Documento&nbsp;</legend>
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Fecha Emisor: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                         </div>

                                                         <input type="datetime-local" required style="text-transform:capitalize" class="form-control" id="fechaEmisor" name="fechaEmisor">
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Nombre Emisor: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                         </div>

                                                         <input type="text" style="text-transform:capitalize" required class="form-control" id="nomEmisor" name="nomEmisor">
                                                     </div>
                                                 </div>
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Unidad del Emisor: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>

                                                         <input type="text" style="text-transform:capitalize" required class="form-control" id="unidadEmisor" name="unidadEmisor">
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Metodo de Entrega: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>

                                                         <select class="form-control" id="selectMetodo" required name="selectMetodo">
                                                             <option value="" selected disabled>Seleccione</option>
                                                             <option value="Presencial">Presencial</option>
                                                             <option value="Correo">Correo</option>
                                                             <option value="Missvq">Mi SSVQ</option>
                                                             <option value="Otro">Otro</option>
                                                         </select>
                                                     </div>
                                                 </div>
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Email Emisor: </label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                         </div>
                                                        <input type="email" class="form-control"  name="email" id="email">
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-row">
                                                <?php
                                                if($nomUsuario == 'Barbara Gallo'){
                                                }else{
                                                    ?>
                                                    <div class="form-group col-md-6">
                                                        <label for="exampleInputQuienSolicita">Persona Asignada: <code style="color: red">*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                            </div>
                                                            <select name="selectControl" required id="selectControl" class="form-control">
                                                                <?php
                                                                $tecnicos = array(
                                                                    "Abel Torres Arancibia",
                                                                    "Felipe Retamal Fuentes",
                                                                    "Gonzalo Cáceres Carreño",
                                                                    "Jaime Estay Jorquera",
                                                                    "Jesús Silva Pellet",
                                                                    "Joaquín Jorquera Gonzalez",
                                                                    "Luis Pérez Bernal",
                                                                    "Manuel Torres Sagredo",
                                                                    "Pablo Sanchez Molina",
                                                                    "Rene Condell Vargas",
                                                                    "Yorgo Casimis Subiabre",
                                                                    "Jorge Cantillano",
                                                                    "Isaac Perez",
                                                                    "Barbara Gallo",
                                                                );

                                                                sort($tecnicos);

                                                                echo "<option value='' disabled selected>Seleccione...</option>";
                                                                foreach ($tecnicos as $tecnico) {
                                                                    echo "<option value=\"$tecnico\">$tecnico</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Estado: </label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <select class="form-control"  required name="estado" id="estado">
                                                             <option value="" selected disabled>Seleccione</option>
                                                             <option value="Recibido">Recibido</option>
                                                             <option value="En proceso">En proceso</option>
                                                             <option value="Derivado">Derivado</option>
                                                         </select>

                                                     </div>
                                                 </div>
                                             </div>

                                         </fieldset>
                                         <br>
                                         <fieldset class="border p-3">
                                             <legend class="w-auto">&nbsp;Informaci&oacute;n del Documento&nbsp;</legend>
                                             <!--
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Fecha de Ingreso: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <input type="datetime-local"   class="form-control" id="fechaIngresoDocu" name="fechaIngresoDocu">
                                                     </div>
                                                 </div>
                                             </div>
                                             -->
                                             <div class="form-row">
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Nombre del Documento: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <input type="text" class="form-control" required  id="nomDocumento" name="nomDocumento">
                                                     </div>
                                                 </div>
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Número de Documento: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <input type="text"  class="form-control"  required  id="numDocumento" name="numDocumento">
                                                     </div>
                                                 </div>
                                                 <div class="form-group col-md-6">
                                                     <label for="exampleInputQuienSolicita">Tipo de Documento: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <select class="form-control"  name="tipoDocumento" required  id="tipoDocumento">
                                                             <option value="" selected disabled>Seleccione</option>
                                                              <option value="MEMORANDUM">Memorandum</option>
                                                             <option value="Oficio">Oficio</option>
                                                             <option value="Otro">Otro</option>
                                                         </select>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-row">

                                                     <label for="exampleInputQuienSolicita">Subir el Archivo: <code style="color: red">*</code></label>
                                                     <div class="input-group mb-3">
                                                         <div class="input-group-prepend">
                                                             <span class="input-group-text"><i class="fas fa-file"></i></span>
                                                         </div>
                                                         <input type="file" name="archivoDocumento" required id="archivoDocumento" class="form-control">
                                                     </div>

                                             </div>
                                         </fieldset>
                                         <br>

                                         <button type="submit" id="guardarRecepDoc" name="guardarRecepDoc" disabled="disabled" class="btn btn-block btn-primary">Guardar</button>

                                     </div>
                                </form>
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
    <script src="js/dark-mode.js"></script>
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
</body>
<script>
    $(document).ready(function() {
        $('#selectControl').change(function() {
            var selectedValue = $(this).val();
            $('#guardarRecepDoc').prop('disabled', selectedValue === 'Seleccione');
        });
    });
</script>
</html>