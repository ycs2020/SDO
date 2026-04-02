<?php

include '../sdo-funciones/conexion.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}





?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Herramientas</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/estilos.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="hold-transition sidebar-mini dark-mode" id="body">
    <div class="wrapper">

        <!-- <div class="preloader  flex-column justify-content-center align-items-center">

            <img class="animation__shake" src="../dist/img/ssvq1.png" alt="logo">
            <br>
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div> -->
        <!-- Navbar -->
        <?php
        include '../sdo-templates/bodega-panol-sidebar.php';
        ?>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>Listado de Herramientas</h4>
                        </div>
                        <div class="col-sm-6">

                            <div class="form-group">
                                <div style="float: right;" class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" onclick="toggleDark()">
                                    <label class="custom-control-label" for="customSwitch3">Modo Claro</label>
                                </div>
                            </div>

                            <script>
                                function toggleDark() {
                                    // var element = document.getElementById("navBarPrincipal")
                                    // element.classList.toggle("dark-mode")

                                    document.body.classList.toggle("dark-mode");

                                }
                            </script>
                            <br>
                            <br>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-header">
                                    <a href="bodega-administrar.php" style="float: right;" class="btn btn-info  "><i class="fa fa-chevron-left "></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>
                                    <a href="ingreso-herramientas.php" style="float: right;" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Nueva Herramienta</a>
                                    <br>
                                    <br>
                                    <a href="historial-herramientas.php" style="float: right;" class="btn btn-warning"><i class="fa fa-eye"></i>&nbsp;&nbsp;Historial Herramientas</a>


                                    <h5>Colores para Identificar:</h5>
                                    <ul>
                                        <li>
                                            <i style="color : #4BD30B ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; Disponible
                                        </li>
                                        <li>
                                            <i style="color : #E05214 ; text-shadow: 0 0 3px #000;" class="fa fa-circle nav-icon"></i>&nbsp;&nbsp; En Arriendo
                                        </li>

                                    </ul>
                                    <br>
                                    <br>
                                    <?php



                                    if (isset($_GET['eliminado'])) {

                                        echo "<script>
                                            
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Se Eliminio el registro de la herramienta correctamente',
                                                showConfirmButton: true,
                                                timer: 7000
                                              })
                                            
                                            
                                            </script>";
                                    }

                                    if (isset($_GET['modificacion'])) {

                                        echo "<script>
                                            
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'warning',
                                                title: 'Se modifico el registro de la herramienta correctamente',
                                                showConfirmButton: true,
                                                timer: 7000
                                            
                                              })
                                            
                                            
                                            </script>";
                                    }

                                    if (isset($_GET['exitoCrearHerramienta'])) {

                                        echo "<script>
                                            
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Herramienta guardada con exito',
                                                showConfirmButton: false,
                                                timer: 2000
                                              })
                                            
                                            
                                            </script>";
                                    }

                                    if (isset($_GET['cambioValido'])) {

                                        echo "<script>
                                            
                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Unidades Añadidas Correctamente',
                                                showConfirmButton: false,
                                                timer: 2000
                                              })
                                            
                                            
                                            </script>";
                                    }
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive">
                                            <table id="example2" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Barras<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo inventario<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Imagen<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Sector<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Descriptivo<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Categoria Herramienta<center>
                                                        </th> -->
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Marca<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Modelo<center>
                                                        </th> -->
                                                        <th style="vertical-align:middle;">
                                                            <center>Estado <center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Uso Departamento<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Descripción<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Modificar <center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Eliminar <center>
                                                        </th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    $query = 'SELECT * FROM pañol_herramientas WHERE cantHerramienta >= 0 ORDER BY idHerramienta DESC;';

                                                    $res = mysqli_query($conexion, $query);

                                                    $numHerramienta = 1;

                                                    while ($row = $res->fetch_assoc()) {

                                                        $color = "";
                                                        $cantidad = $row['cantHerramienta'];

                                                        if ($cantidad == 0) {
                                                            $color = "style=' background-color: #E05214;font-weight:bold;vertical-align:middle;color:black;'";
                                                        }
                                                        if ($cantidad == 1) {

                                                            $color = "style=' background-color: #4BD30B; font-weight:bold;vertical-align:middle;color:black;'";
                                                        }


                                                    ?>
                                                        <tr>

                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $codigo = mb_strtoupper($row['codHerramienta']);
                                                                    echo $codigo;

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $codInventario = mb_strtoupper($row['codInventario']);
                                                                    echo $codInventario;

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                        $imagen = $row['imagen'];
                                                                   
                                                                    ?>

                                                                        <img width="85px" height="80px" src="../sdo-bodega-panol/img3/<?php echo $imagen ?>" alt="Imagen Herramienta">

                                                                    <?php

                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $sector = mb_strtoupper($row['sector']);
                                                                    echo $sector;

                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <td nowrap="nowrap" style="vertical-align:middle; text-transform: uppercase;text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    echo $row['nomHerramienta'];
                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <!-- <td  nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php echo mb_strtoupper($row['marcaHerramienta']); ?>
                                                                </center>
                                                            </td> -->
                                                            <!-- <td style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    // if ($row['fechaActa'] == 0) {
                                                                    //     echo "No registra fecha";
                                                                    // } else {
                                                                    //     $inicioFecha = date('d/m/Y', strtotime(str_replace('/', '-', $row['fechaActa'])));
                                                                    //     $inicioHora = date('H:i', strtotime($row['fechaActa']));
                                                                    //     echo $inicioFecha . '<br>' . $inicioHora;
                                                                    // }

                                                                    echo mb_strtoupper($row['modeloHerramienta']);
                                                                    ?>
                                                                </center>
                                                            </td> -->
                                                            <td <?php echo $color ?> style="text-transform: uppercase">
                                                                <center>
                                                                    <?php

                                                                    $row['cantHerramienta'];

                                                                    if ($row['cantHerramienta'] == 1) {
                                                                        echo 'Disponible';
                                                                    } else if ($row['cantHerramienta'] == 0) {
                                                                        echo 'En Arriendo';
                                                                    }

                                                                    ?>
                                                                </center>
                                                            </td>

                                                            <td style="vertical-align:middle; text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $inicioFecha =  date('d/m/Y', (strtotime(str_replace('/', '-', $row['fechaIngreso']))));
                                                                    $inicioHora = date('H:i', strtotime($row['fechaIngreso']));
        
                                                                    echo $inicioFecha . '<br>' . $inicioHora;
                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td nowrap="nowrap" style="vertical-align:middle;font-weight:bold">
                                                                <center>
                                                                    <?php

                                                                    $idDepartamento = mb_strtoupper($row['idDepartamento']);

                                                                    if($idDepartamento == 10){

                                                                        $nomDepartamento = 'Todos';
                                                                        
                                                                        echo mb_strtoupper($nomDepartamento);

                                                                    }else{
                                                                        
                                                                        $queryDepa = "SELECT * FROM departamentos WHERE idDepartamento = ${idDepartamento}";
                                                                        $resDepa = mysqli_query($conexion, $queryDepa);
    
                                                                        while ($rowDepa = $resDepa->fetch_assoc()) {
    
                                                                            $datos = [];
    
                                                                            $datos['nomDepartamento'] = $rowDepa['nomDepartamento'];
    
                                                                            $nomDepartamento = $datos['nomDepartamento'];
                                                                        }
    
                                                                        echo mb_strtoupper($nomDepartamento);
    
                                                                    }

                                                                  


                                                                    ?>
                                                                </center>
                                                            </td>
                                                            <td style="vertical-align:middle; text-align:justify;font-weight:bold">
                                                                <center>
                                                                    <?php


                                                                    $idHerramienta = $row['idHerramienta'];

                                                                    ?>

                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" class="btn btn-block btn-primary" id="#idHerramienta<?php echo $idHerramienta ?>" data-toggle="modal" data-target="#idHerramienta<?php echo $idHerramienta ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="idHerramienta<?php echo $idHerramienta ?>" name="idHerramienta<?php echo $idHerramienta ?>" tabindex="-1" role="dialog" aria-labelledby="EjemploModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="EjemploModalLabel">Especificaciones de la herramienta</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <?php

                                                                                    $sqlDescript = "SELECT descriptHerramienta FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
                                                                                    $resDescript = mysqli_query($conexion, $sqlDescript);

                                                                                    while ($rowDescript = $resDescript->fetch_assoc()) {

                                                                                        $descript = $rowDescript['descriptHerramienta'];
                                                                                    }


                                                                                    ?>

                                                                                    <div style="text-align:left;" class="form-group">
                                                                                        <textarea type="text" readonly class="form-control" rows="10"><?php echo $descript ?></textarea>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Ya he leído esto!</button>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>

                                                            <td style="vertical-align: middle;font-weight:bold">
                                                                <center>
                                                                    <?php
                                                                    $idHerramienta = $row['idHerramienta'];
                                                                    $idHerramienta = trim($idHerramienta);
                                                                    if ($idHerramienta == 0 || $idHerramienta == "" || $idHerramienta == " " || $idHerramienta == "  ") {
                                                                    ?>
                                                                        <center><?php echo "Error al cargar datos"; ?></center>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <form action="herramientas-modificar-completo.php" method="POST" style="margin: 0;text-align:center;" class="">

                                                                            <input type="hidden" name="idHerramienta" value="<?php echo $idHerramienta; ?>">

                                                                            <button type="submit" id="inputMozi" class="btn btn-block btn-warning"><i class="fas fa-pen"></i></button>
                                                                        </form>

                                                                    <?php

                                                                    }
                                                                    ?>

                                                                </center>
                                                            </td>

                                                        </tr>
                                                    <?php

                                                        $numHerramienta++;
                                                    }


                                                    ?>



                                                </tbody>
                                                <tfoot class="bg-secondary">
                                                    <tr>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo Barras<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Codigo inventario<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Imagen<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Sector<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Nombre Descriptivo<center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Categoria Herramienta<center>
                                                        </th> -->
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Marca<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Modelo<center>
                                                        </th> -->
                                                        <th style="vertical-align:middle;">
                                                            <center>Estado <center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Fecha Ingreso<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Uso Departamento<center>
                                                        </th>
                                                        <th style="vertical-align:middle;">
                                                            <center>Descripción<center>
                                                        </th>

                                                        <th style="vertical-align:middle;">
                                                            <center>Modificar <center>
                                                        </th>
                                                        <!-- <th style="vertical-align:middle;">
                                                            <center>Eliminar <center>
                                                        </th> -->
                                                        </th>

                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                        <!-- /.col -->
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
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
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
                }
            });
        });
    </script>
    <script src="js/lector-barras.js"></script>
</body>

</html>