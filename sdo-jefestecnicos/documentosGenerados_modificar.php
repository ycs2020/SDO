<?php

include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['idDocumento'])){
    $idDocumento = $_POST['idDocumento'];

    if($idDocumento > 0) {

        $dataDoc = obtenerDocumentoSolicitud($idDocumento);

        if (is_array($dataDoc)) {

            $idDocPDF = $dataDoc['idDoc'];
            $tituloDoc = $dataDoc['tituloDoc'];
            $textoDoc = $dataDoc['textoDoc'];
            $fechaDoc = $dataDoc['fechaDoc'];
            $creadorDoc = $dataDoc['creadorDoc'];
            $rolCreadorDoc = $dataDoc['rolCreadorDoc'];

            if(empty($fechaDoc)){
                $fechaCompleta = 'No Aplica';
            }else{
                $fechaDoc2 = strtotime($fechaDoc);

                $fecha = fechaEspañol2($fechaDoc2);
                $hora = date('H:i',$fechaDoc2);

                $fechaCompleta = $fecha.'<br>'.$hora.' hrs.';
            }

            if(!empty($tituloDoc)){
                $tituloDoc2 = mb_convert_case($tituloDoc,MB_CASE_UPPER,'UTF-8');
            }else{
                $tituloDoc2 = 'DOCUMENTO';
            }

        } else {
            header('Location: documentosGenerados_listado.php?errorGenerar=true');
        }

    }else{
        header('Location: documentosGenerados_listado.php?errorGenerar=true');
    }
}else{
    header('Location: documentosGenerados_listado.php?errorGenerar=true');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generar Documento</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <!-- CodeMirror -->
    <link rel="stylesheet" href="../plugins/codemirror/codemirror.css">
    <link rel="stylesheet" href="../plugins/codemirror/theme/monokai.css">
    <!-- SimpleMDE -->
    <link rel="stylesheet" href="../plugins/simplemde/simplemde.min.css">


    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">


    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php
    include '../sdo-templates/jefes-sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                    <div class="card">
                        <div class="card-header">
                            <a style="pointer-events: none;" class="btn font-weight-bold text-black ">Modificar Documento</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="documentosGenerados_listado.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                        </div>
                    </div>
                </div>

            </div>
            <form action="../sdo-funciones/documentos_modificarSolicitud.php" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-primary">
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="form-group col-12">
                                    <label for="exampleInputNomTarjeta">Titulo:<code>*</code></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" required class="form-control" id="tituloDoc" name="tituloDoc" value="<?php echo $tituloDoc2 ?>" placeholder="Titulo de Documento">
                                    </div>
                                </div>
                                <!-- Asegúrate de que el ID sea correcto -->
                                <textarea id="generarDocSolic" name="generarDocSolic"><?php echo $textoDoc ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-outline">
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $idDocumento; ?>"/>
                                    <input type="hidden" id="nomCreadorDocs" name="nomCreadorDocs" value="<?php echo $nomUsuario; ?>"/>
                                    <input type="hidden" id="rolCreadorDocs" name="rolCreadorDocs" value="<?php echo $rolUsuario; ?>"/>
                                    <input type="submit" id="btnGuardarDocs" class="btn btn-block btn-primary" value="Guardar" disabled/>
                                </div>

                                <div class="form-group">
                                    <a href="documentosGenerados_listado.php" class="btn btn-block btn-warning"> Volver </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
            </form>
            <!-- ./row -->
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- CodeMirror -->
<script src="../plugins/codemirror/codemirror.js"></script>
<script src="../plugins/codemirror/mode/css/css.js"></script>
<script src="../plugins/codemirror/mode/xml/xml.js"></script>
<script src="../plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>

<!-- Page specific script -->
<script>
    $(function () {
        // Summernote
        $('#generarDocSolic').summernote({
            height: 500,
            placeholder: 'Escribe aquí el contenido del documento...',

        });

        // Selección del botón de guardar
        const $btnGuardar = $('#btnGuardarDocs');

        // Función para verificar el contenido de Summernote
        function verificarContenidoSummernote() {
            const contenidoActual = $('#generarDocSolic').summernote('code');
            // Verificar si el contenido tiene texto (sin etiquetas vacías)
            if ($(contenidoActual).text().trim().length > 0) {
                $btnGuardar.prop('disabled', false); // Habilitar el botón
            } else {
                $btnGuardar.prop('disabled', true); // Deshabilitar si está vacío
            }
        }

        // Llamar a la función al cargar para manejar el contenido inicial
        verificarContenidoSummernote();

        // Detectar cambios en el contenido de Summernote
        $('#generarDocSolic').on('summernote.change', function (we, contents, $editable) {
            verificarContenidoSummernote();
        });


    })
</script>

</body>

</html>