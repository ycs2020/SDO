<?php

include_once '../sdo-funciones/conexion.php'; // Asegúrate que $conexion está disponible
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rolUsuario'])) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$rolUsuario = strtolower(trim($_SESSION['rolUsuario']));
$rolMap = [
    "personal" => "sdo-personal",
    "administrativo" => "sdo-admtec",
    "bodega pañol" => "sdo-bodega-panol",
    "jefe tecnico" => "sdo-jefestecnicos",
    "administrador" => "sdo-administrador",
    "directiva" => "sdo-directiva",
    "mesa ayuda" => "sdo-mesa-ayuda"
];

if (!array_key_exists($rolUsuario, $rolMap)) {
    header('Location: ../sdo-flujo/login_index.php?errorSesion=true');
    exit;
}

$ruta = $rolMap[$rolUsuario];

// Generar ID único y asegurarse que no exista en la tabla
$idGenerado = generarIdUnico();

// ============================================
// FUNCIÓN: Generar ID Único no repetido
// ============================================
function generarIdUnico()
{
    do {
        include_once '../sdo-funciones/conexion.php';
        $codigo = generarCodigoAleatorio(); // 6 caracteres aleatorios
        $id = 'EQUIP-HBQP-' . $codigo;

        // Verificar si ya existe en la base de datos
        $query = "SELECT COUNT(*) FROM equipamiento_hbqp WHERE idEquipo = '$id'";
        $result = mysqli_query($conexion, $query);
        $existe = mysqli_fetch_row($result)[0];

    } while ($existe > 0); // Repetir si ya existe

    return $id;
}

// ============================================
// FUNCIÓN: Generar código aleatorio
// ============================================
function generarCodigoAleatorio()
{
    $longitud = 6;
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[mt_rand(0, $max)];
    }

    return $codigo;
}

// ============================================
// EJEMPLO DE USO: Mostrar el ID generado
// ============================================
//echo "ID Generado: <strong>$idGenerado</strong>";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Recepción de Trabajo</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const $departamento = $('#departamentoSolicitante');
            const $servicio = $('#servicioOrden');

            const $subDepContainer = $('#visualizarSubDepartamento');
            const $nomServContainer = $('#visualizarNomServicio');

            // Funciones genéricas de carga AJAX
            const cargarContenido = (url, data, $target) => {
                $.post(url, data, r => $target.html(r));
            };

            const recargarSubDepartamento = () => {
                cargarContenido('sdo-datos-departamentos/mostrarSubDepartamentos.php', {
                    idDepartamento: $departamento.val()
                }, $subDepContainer);
            };

            const recargarNomDepartamento = () => {
                cargarContenido('sdo-datos-servicios/habilitarIngresoServicio.php', {
                    idServicio: $servicio.val()
                }, $nomServContainer);
            };

            // Eventos
            $departamento.change(recargarSubDepartamento);
            $servicio.change(recargarNomDepartamento);

            // Cargar al inicio
            recargarSubDepartamento();
            recargarNomDepartamento();

            // Checkbox mostrar/ocultar
            $('.fantasma').change(function () {
                const visible = $(this).is(':checked');
                $('#fieldset1, #parrafo').toggle(visible);
            });
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
<div class="wrapper">
    <?php
    include '../sdo-templates/admin-sidebar.php';
    ?>
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
                    <!-- left column -->
                    <!-- left column -->
                    <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <a style="pointer-events: none;" class="btn font-weight-bold ">Crear Hoja T&eacute;cnica</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <a href="../sdo-administrador/ordenes-subir-firmas.php" style="float: right;" class="btn btn-block btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">

                        <!-- general form elements -->
                        <div class="card card-primary">

                            <div class="card-body">

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="tab-solicitante" role="tabpanel" aria-labelledby="tab-solicitante-tab">

                                    </div>

                                </div>
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col  -->

                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
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
<!-- Page specific script -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<?php
$notificacionesSweet = [
    'errorGenerar' => ['Error al Crear Hoja Técnica. Reintente.', 'error'],
];

include_once "../sdo-includes/sweetalert_dark_mode.php";
?>
<script>
    $(document).ready(function () {
        $('#hoja_tecnicas').DataTable({
            "paging": true,
            "iDisplayLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                "zeroRecords": "No se han encontrado Registros.",
                "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                "infoEmpty": "No existen Registros.",
                "search": "Buscar:",
                "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                }
            },
        });
    });
</script>
<script>
    // Obtener referencia al campo de entrada de archivo
    const inputImagen = document.getElementById('imagen');
    // Obtener referencia al div de vista previa
    const vistaPreviaDiv = document.getElementById('vista-previa');

    // Escuchar el evento change del campo de entrada de archivo
    inputImagen.addEventListener('change', function (e) {
        // Obtener la imagen seleccionada
        const imagenSeleccionada = e.target.files[0];

        // Crear un objeto URL para la imagen seleccionada
        const imagenURL = URL.createObjectURL(imagenSeleccionada);

        // Crear un elemento de imagen para mostrar la vista previa
        const imagenPrevia = document.createElement('img');
        imagenPrevia.src = imagenURL;
        imagenPrevia.classList.add('img-fluid');

        // Limpiar el div de vista previa antes de añadir la nueva imagen
        vistaPreviaDiv.innerHTML = '';

        // Agregar la imagen de vista previa al div
        vistaPreviaDiv.appendChild(imagenPrevia);
    });
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>
    function obtenerid(id) {
        console.log(id);
        idHoja = document.getElementById('idHoja');
        idHoja.value = id;
        $("#modalHojas").modal("hide");
        Swal.fire(
            'Exito!',
            'Equipo seleccionado correctamente!',
            'success'
        )
        botonAddHT = document.getElementById('botonAddHT');

        botonAddHT.classList.remove('btn-primary');
        botonAddHT.classList.add('btn-danger');
        botonAddHT.innerText = 'Equipo Seleccionado';

        var dataId = id;
        console.log(dataId);
        var elementos = {};
        //AJAX
        $.ajax({
            type: 'POST',
            url: '../sdo-funciones/obtener-datos-equipo.php',
            data: {
                'id': dataId
            },
            success: function (response) {
                console.log(response);
                var datos = JSON.parse(response);

                elementos.nombreEquipo = datos.nombreEquipo;
                elementos.marca = datos.marca;
                elementos.modelo = datos.modelo;
                elementos.numeroInventario = datos.numeroInventario;
                elementos.numeroSerie = datos.numeroSerie;
                elementos.idCategoria = datos.idCategoria;
                elementos.idServicio = datos.idServicio;
                elementos.idDepaPertenece = datos.idDepaPertenece;
                elementos.piso = datos.piso;
                elementos.id = datos.id;

                $('#nomEquipo').val(elementos.nombreEquipo);
                $('#marcaEquipo').val(elementos.marca);
                $('#modeloEquipo').val(elementos.modelo);
                $('#numInventario').val(elementos.numeroInventario);
                $('#numSerie').val(elementos.numeroSerie);
                $('#idCategoria').val(elementos.idCategoria);
                $('#idEquipo').val(elementos.id);
                $('#idServicio').val(elementos.idServicio);
                $('#idDepaPertenece').val(elementos.idDepaPertenece);
                $('#piso').val(elementos.piso);

            }
        })

    }
</script>
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