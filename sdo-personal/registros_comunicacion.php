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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro General de Comunicacion</title>

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
    <script>
        function validarCampos() {
            var textarea = document.getElementById('obs');
            var turno = document.getElementById('turno');
            var select = document.getElementById('responsable');
            var botonGuardar = document.getElementById('guardar');

            // Habilita o deshabilita el botón basado en la validación de los campos
            botonGuardar.disabled = textarea.value.trim() === '' || select.selectedOptions.length === 0 || turno.selectedOptions.length === 0;
        }

        // Event listeners para validar cuando el usuario ingresa datos
        document.addEventListener('DOMContentLoaded', function() {
            var textarea = document.getElementById('obs');
            var select = document.getElementById('responsable');
            var turno = document.getElementById('turno');

            textarea.addEventListener('input', validarCampos);
            select.addEventListener('change', validarCampos);
            turno.addEventListener('change',validarCampos);
        });
    </script>
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
                        <h4>Registros de Comunicacion</h4>
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
            if($_GET['exito']){
                ?>
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "Se ha ingresado correctamente!",
                        icon: "success"
                    });
                </script>
                <?php
            }
        if($_GET['error']){
            ?>
            <script>
                Swal.fire({
                    title: "Error!",
                    text: "Ha ocurrido un problema. Reintente!",
                    icon: "error"
                });
            </script>
            <?php
        }

        if($_GET['exitoVisar']){
            ?>
            <script>
                Swal.fire({
                    title: "Exito!",
                    text: "Se ha visado correctamente.!",
                    icon: "success"
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
                                    <a href="index.php" style="float: right;" class="btn bg-gradient-red float-right btn-md text-white"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                </div>
                                <br>
                                <br>
                                <div class="col-lg-3 col-md-6 col-12 float-right" id="añadir" name="añadir">
                                    <button style="float: right;" data-toggle="modal" data-target="#exampleModal" class="btn bg-gradient-info float-right btn-md text-white"><i class="fas fa-plus"></i> Añadir Registro</button>
                                </div>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="../sdo-funciones/guardar-registro-comunicacion.php" method="POST">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-info">
                                                    <h5 class="modal-title" id="exampleModalLabel">Añadir Registro de Comunicación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <label>Responsable de la actividad:</label>
                                                    <select class="form-control listadoEspecialidades" required id="responsable"  onchange="validarCampos()" name="responsable[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <option value="Seleccione..." disabled>Seleccione...</option>
                                                        <?php
                                                        echo "<option value='Jorge Cantillano'>Jorge Cantillano</option>";
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
                                                                
                                                                UNION ALL
                                                                
                                                                SELECT nomJefe AS nombre
                                                                FROM jefes_tecnicos
                                                                WHERE idDepartamento = $idDeptoBuscar
                                                                AND userActivado = 1
                                                    
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
                                                    <label>Asistentes:</label>
                                                    <select class="form-control listadoAsistentes" required id="asistentes" onchange="validarCampos()" name="asistentes[]" multiple="multiple" onchange="limitarSeleccion(this)">
                                                        <?php
                                                        echo "<option value='Jorge Cantillano'>Jorge Cantillano</option>";
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
                                                                
                                                                UNION ALL
                                                                
                                                                SELECT nomJefe AS nombre
                                                                FROM jefes_tecnicos
                                                                WHERE idDepartamento = $idDeptoBuscar
                                                                AND userActivado = 1
                                                    
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
                                                    <label>Turno:</label>
                                                    <select class="form-control" required id="turno" name="turno" onchange="validarCampos()">
                                                       <option value="" selected disabled>Seleccione...</option>
                                                        <option value="D">Diurno (8:00 hasta las 20:00)</option>
                                                        <option value="N">Noche (20:00 hasta las 8:00)</option>
                                                    </select>
                                                    <br>
                                                    <label>Tema:</label>
                                                    <input type="text" id="tema" name="tema" class="form-control">
                                                    <br>
                                                    <label>Inicio:</label>
                                                    <input type="time" id="inicio" name="inicio" class="form-control">
                                                    <br>
                                                    <label>Fin:</label>
                                                    <input type="time" id="fin" name="fin" class="form-control">
                                                    <br>
                                                    <label>Temas y acuerdos de la Reunion</label>
                                                    <textarea class="form-control" style="resize: none" name="obs" id="obs" rows="10"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" disabled class="btn btn-primary" id="guardar">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row" id="rowMenu1" name="rowMenu1">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="font-size:80%" id="example2" class="table table-bordered cell-border">
                                        <input type="hidden" name="nomUsuarioModificar" id="nomUsuarioModificar" value="<?php echo $nomUsuario; ?>">
                                        <thead class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Responsable de la Actividad</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <!--
                                            <th style="vertical-align:middle;">
                                                <center>Visar</center>
                                            </th>
                                            -->
                                            <th style="vertical-align:middle;">
                                                <center>Modificar</center>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tfoot class="bg-secondary">
                                        <tr>
                                            <th style="vertical-align:middle;">
                                                <center>Fecha</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Responsable de la Actividad</center>
                                            </th>
                                            <th style="vertical-align:middle;">
                                                <center>Visualizar</center>
                                            </th>
                                            <!--
                                            <th style="vertical-align:middle;">
                                                <center>Visar</center>
                                            </th>
                                            -->
                                            <th style="vertical-align:middle;">
                                                <center>Modificar</center>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 - ∞. Derechos Reservados</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
</div>
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
<script>
    $(document).ready(function () {
        $('.listadoEspecialidades').select2({
            placeholder: 'Funcionarios',
            theme: 'bootstrap4',
            allowClear: true
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.listadoAsistentes').select2({
            placeholder: 'Funcionarios',
            theme: 'bootstrap4',
            allowClear: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example2').DataTable({
            "lengthChange": false,
            "searching": true,
            "autoWidth": false,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por Página",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "search": "Buscar:",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
            "ajax": {
                "url": "sdo-includes/listado_registro_comunicacion.php", // Archivo PHP que contiene la consulta SQL
                "type": "POST",
                "data": function (d) {
                    d.nomUsuarioModificar = $('#nomUsuarioModificar').val(); // Pasa el valor seleccionado del select como parámetro
                    // Puedes pasar parámetros adicionales si es necesario
                    console.log(d); // Muestra los parámetros enviados al servidor
                },
                "dataSrc": "data", // Coincide con la clave 'data' en la respuesta JSON
                "error": function (xhr, error, thrown) {
                    console.error("Error occurred during AJAX request:", error, thrown);
                    console.log("XHR object:", xhr);
                }
            },
            "columns": [
                {"data": "fecha"},
                {"data": "responsables"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.id < 1) {
                            return 'Deshabilitado';
                        } else {
                            return '<button class="btn btn-primary btn-visualizar" data-id="' + row.id + '"><i class="fas fa-eye"></i></button>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        if (row.aplicaModificar === 'si') {
                            return '<button class="btn btn-warning btn-modificar" data-id="' + row.id + '"><i class="fas fa-pen"></i></button>';
                        } else {
                            return 'Deshabilitado';
                        }
                    }
                }

            ],
            "createdRow": function (row, data, dataIndex) {
                $('td', row).css({
                    'vertical-align': 'middle',  // Centra verticalmente el texto en las celdas
                    'text-align': 'center',      // Centra horizontalmente el texto en las celdas
                    'font-size': '15px'          // Establece el tamaño de la fuente a 15px
                });
            }
        });

        $('#example2').on('click', '.btn-visualizar', function () {
            var id = $(this).data('id');

            // Crear un formulario dinámico
            var form = $('<form>', {
                'action': '../sdo-pdf/Registro-General.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
                'target': '_blank',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'id',
                'value': id
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
        $('#example2').on('click', '.btn-modificar', function () {
            var id = $(this).data('id');

            var form = $('<form>', {
                'action': 'registros-modificar.php', // Reemplaza 'tu_url_destino' con la URL de destino
                'method': 'POST',
            });

            // Agregar un campo oculto para el ID
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'id',
                'value': id
            }));

            // Agregar el formulario al documento y enviarlo
            form.appendTo('body').submit();
        });
    });
    /* Visar elementos
     {
                    "data": null,
                    "render": function (data, type, row) {
                        var modalId = 'exampleModalVisar' + row.id;
                        var userId = <?php echo json_encode($nomUsuario); ?>;
                        var buttonHtml = '';
                        var modalHtml = '';

                        if (row.cantidad >= 1 || row.responsables == userId) {
                            buttonHtml = 'Deshabilitado';
                        } else {
                            buttonHtml = '<button class="btn btn-info btn-visar" data-toggle="modal" data-target="#' + modalId + '" data-id="' + row.id + '">' +
                                '<i class="fas fa-check-circle"></i>' +
                                '</button>';

                            modalHtml = '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel' + row.id + '" aria-hidden="true">' +
                                '<div class="modal-dialog" role="document">' +
                                '    <div class="modal-content">' +
                                '        <div class="modal-header bg-gradient-info">' +
                                '            <h5 class="modal-title" id="exampleModalLabel' + row.id + '">Visar Registro General de Comunicación: ' + row.id + '</h5>' +
                                '            <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                '                <span aria-hidden="true">&times;</span>' +
                                '            </button>' +
                                '        </div>' +
                                '        <div class="modal-body">' +
                                '            <form action="../sdo-funciones/visar-registro-general.php" method="POST">' +
                                '                <input type="hidden" name="id" value="'+row.id+'">' +
                                '                <input type="hidden" name="nombreUsuario" value="' + userId + '">' +
                                '                <input type="password" class="form-control" id="passwordVisar" name="passwordVisar" placeholder="Ingrese su contraseña">' +
                                '                <button type="submit" class="btn btn-primary mt-3">Visar</button>' +
                                '            </form>' +
                                '        </div>' +
                                '        <div class="modal-footer">' +
                                '            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
                                '        </div>' +
                                '    </div>' +
                                '</div>' +
                                '</div>';
                        }

                        return buttonHtml + modalHtml;
                    }
                },
     */
</script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
