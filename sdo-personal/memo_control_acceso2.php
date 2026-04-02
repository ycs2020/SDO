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

if (isset($_POST['ruta'])) {
    $rutaArchivo = $_POST['ruta'];

    // Seguridad: evita rutas maliciosas
    $rutaArchivo = realpath($rutaArchivo);

    if ($rutaArchivo === false || !file_exists($rutaArchivo)) {
        echo "El archivo no existe o la ruta es inválida.";
        exit;
    }

    // Verifica permisos de lectura
    if (!is_readable($rutaArchivo)) {
        echo "El archivo no tiene permisos de lectura.";
        exit;
    }

    $mimeType = mime_content_type($rutaArchivo);
    if ($mimeType === false) {
        echo "No se pudo obtener el tipo MIME del archivo.";
        exit;
    }

    // Limpia cualquier salida previa
    if (ob_get_length()) ob_end_clean();

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: inline; filename="' . basename($rutaArchivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaArchivo));

    flush(); // asegúrate de enviar encabezados
    readfile($rutaArchivo);
    exit;
}



?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Memorandum de Control de Acceso</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
        <link rel="stylesheet" href="../dist/css/modelo.css">

        <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="css/dark-mode.css">
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
                                <h3> <b>Memorandum de Control de Acceso</b> </h3>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
                <?php
                if(isset($_GET['registroExitoso'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: "Exito!",
                            text: "El memo se ha registrado con exito!",
                            icon: "success"
                        });
                    </script>
                    <?php
                }
                if(
                    isset($_GET['error'])||
                    isset($_GET['error1'])
                ){
                    ?>
                    <script>
                        Swal.fire({
                            title: "Error!",
                            text: "Error al ingresar el memo, Reintente!",
                            icon: "error"
                        });
                    </script>
                    <?php
                }

                if(isset($_GET['errorArchivoSubido'])){
                    ?>
                    <script>
                        Swal.fire({
                            title: "Error!",
                            text: "Error al ingresar el memo, el documento ya se encuentra subido!",
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
                                        <a href="index.php" style="float: right;" class="btn btn-danger"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;<b>Volver Atras</b> </a>
                                        <br>
                                        <br>
                                        <button type="button" style="float: right" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            <i class="fas fa-plus"></i> Ingresar Memorandum
                                        </button>
                                        <button type="button" class="btn btn-success" style="float: right; margin-right: 10px" data-toggle="modal" data-target="#exampleBuscar">
                                            <i class="fas fa-eye"></i> Buscar Funcionario
                                        </button>
                                        <div class="modal fade" id="exampleBuscar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header btn-success">
                                                        <h5 class="modal-title" id="exampleModalLabel">Listado de Funcionarios General</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="tablaBuscar" class="table table-bordered table-hover" style="text-align: center">
                                                            <thead class="bg-secondary">
                                                                <tr>
                                                                    <th>Nombre del Funcionario</th>
                                                                    <th>Rut</th>
                                                                    <th>Ver Acta</th>
                                                                    <th>N° Memos Plataforma</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $sqlBuscar = "SELECT * FROM ( SELECT * FROM memos_funcionarios ORDER BY id ) AS sub GROUP BY nomFuncionario;";
                                                            $resBuscar = mysqli_query($conexion,$sqlBuscar);
                                                            while($rowBuscar = $resBuscar->fetch_assoc()){
                                                                $nomFuncionarioBuscar = $rowBuscar['nomFuncionario'];
                                                                $rutFuncionarioBuscar = $rowBuscar['rutFuncionario'];


                                                                // Inicializa la variable para evitar valores residuales
                                                                $idActaBuscar = null;

                                                                $sqlActaBuscar = "SELECT idActa FROM actas WHERE rutTarjeta = '$rutFuncionarioBuscar'";
                                                                $resActaBuscar = mysqli_query($conexion, $sqlActaBuscar);
                                                                if ($resActaBuscar && mysqli_num_rows($resActaBuscar) > 0) {
                                                                    $rowActaBuscar = $resActaBuscar->fetch_assoc();
                                                                    $idActaBuscar = $rowActaBuscar['idActa'];
                                                                }

                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $nomFuncionarioBuscar?></td>
                                                                    <td><?php echo $rutFuncionarioBuscar?></td>
                                                                    <td>
                                                                        <?php
                                                                        if(empty($idActaBuscar)){
                                                                            echo 'Sin Acta';
                                                                        }else{
                                                                            ?>
                                                                            <form action="actas-funcionario.php" method="POST">
                                                                                <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActaBuscar ?>">
                                                                                <input type="hidden" name="rutActa2" id="rutActa2" value="<?php echo $rutFuncionarioBuscar ?>">
                                                                                <input type="hidden" name="origen" value="memo_control_acceso.php">
                                                                                <button type="submit" class="btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                                            </form>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            $sqlMemosFuncionarios = "SELECT * FROM `memos_funcionarios` WHERE rutFuncionario LIKE '%$rutFuncionarioBuscar%'";
                                                                            $resMemosFuncionarios = mysqli_query($conexion, $sqlMemosFuncionarios);
                                                                            while($rowMemosFuncionarios = $resMemosFuncionarios->fetch_assoc()){
                                                                                $idMemo = $rowMemosFuncionarios['idMemo'];
                                                                                echo $idMemo.'<br>';
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                            </tbody>
                                                            <tfoot class="bg-secondary">
                                                                <tr>
                                                                    <th>Nombre del Funcionario</th>
                                                                    <th>Rut</th>
                                                                    <th>Ver Acta</th>
                                                                    <th>N° Memos Plataforma</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header btn-primary">
                                                        <h5 class="modal-title" id="exampleModalLabel">Agregar Memorandum</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="../sdo-funciones/guardar_memos_control_acceso.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <label>Numero del Memo:</label>
                                                            <input type="number" required class="form-control" id="numMemo" name="numMemo">
                                                            <br>
                                                            <label>Nombre del Emisor:</label>
                                                            <input type="text" required class="form-control" id="nomEmisor" name="nomEmisor">
                                                            <br>
                                                            <label>Nombre de la Unidad</label>
                                                            <input type="text" required class="form-control" id="nomUnidad" name="nomUnidad">
                                                            <br>
                                                            <label>Fecha de Recepcion</label>
                                                            <input type="date" required class="form-control" id="fechaRecepcion" name="fechaRecepcion">
                                                            <br>
                                                            <label>Fecha de Emision</label>
                                                            <input type="date" required class="form-control" id="fechaEmision" name="fechaEmision">
                                                            <hr>

                                                            <button id="agregarFila" type="button" style="float: right" class="btn btn-primary btn-sm">+ Funcionario</button>
                                                            <br>
                                                            <h6>Nota: El rut del funcionario, debe ser colocado sin puntos ni guion</h6>
                                                            <br>
                                                            <table class="table table-bordered" id="tablaFuncionarios">
                                                                <tr>
                                                                    <td colspan="3" style="text-align: center"><label>Ingrese el Nombre/s y Rut del Funcionario/os</label></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="text" class="form-control" id="nomFuncionario1" name="nomFuncionario1" placeholder="Nombre del Funcionario"></td>
                                                                    <td><input type="text" class="form-control" id="rutFuncionario1" name="rutFuncionario1" placeholder="Rut del Funcionario"></td>
                                                                    <td style="text-align: center">No se puede elminar</td>
                                                                </tr>
                                                            </table>
                                                            <hr>
                                                            <label>Subir Archivo:</label>
                                                            <input type="file" class="form-control" name="archivos[]" id="archivos[]" multiple>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" id="contarfuncionario" name="contarfuncionario" value="1">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="text-center bg-secondary">
                                            <tr>
                                                <th>N° Memo Plataforma</th>
                                                <th>Fecha de Recepci&oacute;n</th>
                                                <th>Nombre Unidad</th>
                                                <th>Nombre Emisor</th>
                                                <th>Fecha de Emisión</th>
                                                <th>Numero del Memo</th>
                                                <th>Detalles</th>
                                                <th>Funcionarios</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = "SELECT * FROM `memos_control_acceso` ORDER BY id DESC";
                                                    $res = mysqli_query($conexion,$sql);
                                                    while($row = $res->fetch_assoc()){
                                                        $id = $row['id'];
                                                        $nomEmisor = $row['nomEmisor'];
                                                        $fechaRecepcion = $row['fechaRecepcion'];
                                                        $fechaEmision = $row['fechaEmision'];
                                                        $nombreUnidad = $row['unidad'];
                                                        $numeroMemo = $row['numeroMemo'];

                                                        $sqlArchivo = "SELECT * FROM `archivos_memo_control_acceso` WHERE idMemo = $id";
                                                        $resArchivo = mysqli_query($conexion,$sqlArchivo);
                                                        while($rowArchivo = $resArchivo->fetch_assoc()){
                                                            $rutaArchivo = $rowArchivo['rutaArchivo'];
                                                        }
                                                        ?>
                                                        <tr style="text-align: center">
                                                            <td><?php echo $id?></td>
                                                            <td><?php
                                                                // Crear un objeto DateTime desde la fecha original
                                                                $fecha_obj = DateTime::createFromFormat('Y-m-d', $fechaRecepcion);

                                                                // Convertir al formato d-m-Y
                                                                $fecha_formateada = $fecha_obj->format('d-m-Y');
                                                                echo $fecha_formateada
                                                                ?>
                                                            </td>
                                                            <td><?php echo $nombreUnidad?></td>
                                                            <td><?php echo $nomEmisor?></td>
                                                            <td>
                                                                <?php
                                                                $fecha_objEmisor = DateTime::createFromFormat('Y-m-d', $fechaEmision);

                                                                // Convertir al formato d-m-Y
                                                                $fecha_formateadaEmisor = $fecha_objEmisor->format('d-m-Y');

                                                                echo $fecha_formateadaEmisor

                                                                ?>
                                                            </td>
                                                            <td><?php echo $numeroMemo?></td>
                                                            <td>
                                                                <?php
                                                                    if($rutaArchivo == '' || empty($rutaArchivo)){
                                                                       echo 'Sin Archivo';
                                                                    }else{
                                                                        ?>
                                                                        <form action="memo_control_acceso.php" target="_blank" method="POST" style="display: inline">
                                                                            <input type="hidden" name="ruta" id="ruta" value="<?php echo $rutaArchivo ?>">
                                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i></button>
                                                                        </form>
                                                                        <?php
                                                                    }
                                                                ?>

                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalFunc<?php echo $id?>">
                                                                    Ver Funcionarios
                                                                </button>
                                                                <div class="modal fade" id="exampleModalFunc<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-primary">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Funcionarios</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <table class="table table-bordered">
                                                                                    <thead class="btn-secondary">
                                                                                        <tr>
                                                                                            <th>Nombre</th>
                                                                                            <th>Rut</th>
                                                                                            <th>Ver Acta</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php
                                                                                    $sqlFuncionarios = "SELECT * FROM `memos_funcionarios` WHERE idMemo = $id";
                                                                                    $resFuncionarios = mysqli_query($conexion, $sqlFuncionarios);
                                                                                    while($rowFuncionarios = $resFuncionarios->fetch_assoc()){
                                                                                        $nomFuncionario = $rowFuncionarios['nomFuncionario'];
                                                                                        $rutFuncionario = $rowFuncionarios['rutFuncionario'];

                                                                                        // Inicializa la variable para evitar valores residuales
                                                                                        $idActa = null;

                                                                                        $sqlActa = "SELECT idActa FROM actas WHERE rutTarjeta = '$rutFuncionario'";
                                                                                        $resActa = mysqli_query($conexion, $sqlActa);
                                                                                        if ($resActa && mysqli_num_rows($resActa) > 0) {
                                                                                            $rowActa = $resActa->fetch_assoc();
                                                                                            $idActa = $rowActa['idActa'];
                                                                                        }

                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo $nomFuncionario; ?></td>
                                                                                            <td><?php echo $rutFuncionario; ?></td>
                                                                                            <td>
                                                                                                <?php
                                                                                                if(empty($idActa)){
                                                                                                    echo 'Sin Acta';
                                                                                                }else{
                                                                                                    ?>
                                                                                                    <form action="actas-funcionario.php" method="POST">
                                                                                                        <input type="hidden" name="idActa" id="idActa" value="<?php echo $idActa ?>">
                                                                                                        <input type="hidden" name="rutActa2" id="rutActa2" value="<?php echo $rutFuncionario ?>">
                                                                                                        <input type="hidden" name="origen" value="memo_control_acceso.php">
                                                                                                        <button type="submit" class="btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                                                                                                    </form>
                                                                                                    <?php
                                                                                                }
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                    </tbody>

                                                                                    <tfoot class="btn-secondary">
                                                                                        <tr>
                                                                                            <th>Nombre</th>
                                                                                            <th>Rut</th>
                                                                                            <th>Ver Acta</th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                                <?php

                                                                                ?>
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
                                            <tfoot class="text-center bg-secondary">
                                            <tr>
                                                <th>N° Memo Plataforma</th>
                                                <th>Fecha de Recepci&oacute;n</th>
                                                <th>Nombre Unidad</th>
                                                <th>Nombre Emisor</th>
                                                <th>Fecha de Emisión</th>
                                                <th>Numero del Memo</th>
                                                <th>Detalles</th>
                                                <th>Funcionarios</th>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btnAgregarFila = document.getElementById('agregarFila');
                const tabla = document.getElementById('tablaFuncionarios');
                const inputContador = document.getElementById('contarfuncionario');
                let correlativo = 2; // La primera fila ya existe

                btnAgregarFila.addEventListener('click', () => {
                    const nuevaFila = document.createElement('tr');

                    // Celda del nombre
                    const tdNombre = document.createElement('td');
                    const inputNombre = document.createElement('input');
                    inputNombre.type = 'text';
                    inputNombre.className = 'form-control';
                    tdNombre.appendChild(inputNombre);

                    // Celda del RUT
                    const tdRut = document.createElement('td');
                    const inputRut = document.createElement('input');
                    inputRut.type = 'text';
                    inputRut.className = 'form-control';
                    tdRut.appendChild(inputRut);

                    // Celda del botón eliminar
                    const tdEliminar = document.createElement('td');
                    tdEliminar.style.textAlign = 'center';
                    const btnEliminar = document.createElement('button');
                    btnEliminar.className = 'btn btn-danger btn-sm';
                    btnEliminar.textContent = 'Eliminar';
                    btnEliminar.addEventListener('click', () => {
                        tabla.removeChild(nuevaFila);
                        actualizarCorrelativos();
                        actualizarContador();
                    });
                    tdEliminar.appendChild(btnEliminar);

                    // Armar la fila
                    nuevaFila.appendChild(tdNombre);
                    nuevaFila.appendChild(tdRut);
                    nuevaFila.appendChild(tdEliminar);

                    // Insertar fila a la tabla
                    tabla.appendChild(nuevaFila);

                    // Actualizar correlativos y contador
                    actualizarCorrelativos();
                    actualizarContador();
                });

                function actualizarCorrelativos() {
                    let index = 1;
                    const filas = tabla.querySelectorAll('tr');
                    filas.forEach((fila, i) => {
                        if (i === 0) return; // Saltar encabezado

                        const inputs = fila.querySelectorAll('input');
                        if (inputs.length === 2) {
                            inputs[0].id = 'nomFuncionario' + index;
                            inputs[0].name = 'nomFuncionario' + index;

                            inputs[1].id = 'rutFuncionario' + index;
                            inputs[1].name = 'rutFuncionario' + index;

                            index++;
                        }
                    });

                    correlativo = index;
                }

                function actualizarContador() {
                    const totalFilas = tabla.querySelectorAll('tr').length - 1; // Excluye encabezado
                    inputContador.value = totalFilas;
                }
            });
        </script>




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

        <script>
            $(document).ready(function () {
                var tableBloqueo = $('#example2').DataTable({
                    "lengthChange": false,
                    "searching": true,
                    "autoWidth": false,
                    "responsive": true,
                    "ordering": false,
                    "language": {
                        "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                        "zeroRecords": "No se han encontrado Registros.",
                        "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                        "infoEmpty": "No existen Registros.",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "search": "Buscar:",
                        "processing": "Procesando...",
                        "paginate": {
                            "previous": "Anterior",
                            "next": "Siguiente"
                        }
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                var tablaBuscar = $('#tablaBuscar').DataTable({
                    "lengthChange": false,
                    "searching": true,
                    "autoWidth": false,
                    "responsive": true,
                    "ordering": false,
                    "language": {
                        "lengthMenu": "Visualizando _MENU_ Registros por P&aacute;gina",
                        "zeroRecords": "No se han encontrado Registros.",
                        "info": "Mostrando p&aacute;gina _PAGE_ de _PAGES_",
                        "infoEmpty": "No existen Registros.",
                        "infoFiltered": "(Filtrado desde _MAX_ Registros Totales)",
                        "search": "Buscar:",
                        "processing": "Procesando...",
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
