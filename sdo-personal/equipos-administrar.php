<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nomCategoria = '';

if (isset($_POST['idCategoria'])) {
    $idCategoria = trim($_POST['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);

        $_SESSION['idCategoria'] = $idCategoria;
    } else {
        $nomCategoria = '';
    }
} else if (isset($_SESSION['idCategoria'])) {
    $idCategoria = trim($_SESSION['idCategoria']);

    if (!empty($idCategoria) && $idCategoria > 0) {
        $nomCategoria = getNomCategoria($idCategoria);
    } else {
        $nomCategoria = '';
    }
} else {
    header('Location: index.php?errorCategoria=true');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrar Equipos</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/modelo.css">

    <script src="../dist/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../dist/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../dist/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">

    <script src="../sdo-funciones/jquery-3.2.1.min.js"></script>


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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>DataTables</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">DataTables</li>
                            </ol>
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
                                <div class="card-body">

                                    <input class="form-control col-md-4" type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar Equipos...">
                                    <br>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-bordered table-hover">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Nombre del Equipo<center>
                                                    </th>
                                                    <th>
                                                        <center>Sub-Categorias<center>
                                                    </th>
                                                    <th>
                                                        <center>Observaciones<center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="displayTableExample" name="displayTableExample">
                                                <?php

                                                $query = "SELECT e.idEquipo AS idEquipo, e.nomEquipo AS nomEquipo, sc.idSubcategoria AS idSubcategoria, c.idCategoria AS idCategoria FROM equipos e INNER JOIN subcategorias sc ON e.idSubcategoria = sc.idSubCategoria  INNER JOIN categorias c ON sc.idCategoria = c.idCategoria WHERE c.idCategoria = " . $idCategoria . " ORDER BY e.nomEquipo ASC";

                                                $res = mysqli_query($conexion, $query);

                                                $numEquipo = 1;

                                                while ($row = $res->fetch_assoc()) {

                                                    $idEquipo = $row['idEquipo'];

                                                ?>

                                                    <tr>
                                                        <td style="vertical-align:middle">
                                                            <center><?php echo $numEquipo; ?></center>
                                                        </td>
                                                        <td style="vertical-align:middle"><?php echo mb_strtoupper($row['nomEquipo']); ?></td>
                                                        <td style="vertical-align:middle">
                                                            <center><?php echo getNomSubCategoria($row['idSubcategoria']); ?></center>
                                                        </td>
                                                        <?php
                                                        if (!empty($idEquipo) || $idEquipo > 0) {
                                                        ?>
                                                            <td style="vertical-align:middle">
                                                                <form style="margin: 0; text-align: center;" class="" action="../sdo-personal/equipos-observaciones.php" method="post">

                                                                    <input type="hidden" name="idEquipo" value="<?php echo $idEquipo; ?>">

                                                                    <input type="submit" id="inputObservaciones" class="btn btn-primary" value="Observaciones">

                                                                </form>

                                                            </td>
                                                        <?php
                                                        } else {
                                                            echo '<td style="vertical-align:middle">Error al Cargar Datos</td>';
                                                        }

                                                        ?>
                                                    </tr>

                                                <?php

                                                    $numEquipo++;
                                                }

                                                ?>
                                            </tbody>
                                            <tfoot class="bg-secondary">
                                                <tr>
                                                    <th>
                                                        <center>N°</center>
                                                    </th>
                                                    <th>
                                                        <center>Nombre del Equipo<center>
                                                    </th>
                                                    <th>
                                                        <center>Sub-Categorias<center>
                                                    </th>
                                                    <th>
                                                        <center>Observaciones<center>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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

    <script src="js/dark-mode.js"></script>
    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>

    <script>
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>