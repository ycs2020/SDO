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
    <title>Men&uacute; del Sistema</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
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

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>



    <script type="text/javascript">
        $(document).ready(function() {
            recargarListTecnicos();
            recargarListTecnicosPersonal();

            $('#departamentoSolicitante').change(function() {
                recargarListTecnicos();

            });

            $('#depaPersonal').change(function() {
                recargarListTecnicosPersonal();

            });



            function recargarListTecnicos() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicos1.php",
                    data: "idDepartamento=" + $('#departamentoSolicitante').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlSelect').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlSelect').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };

            function recargarListTecnicosPersonal() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-tecnicos/cargarTecnicosPersonal.php",
                    data: "idDepartamento=" + $('#depaPersonal').val(),
                    success: function(r) {

                        if (r == 0) {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">No Existen Tecnicos en este Departamento.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else if (r == "error" || r == "ERROR" || r == "Error") {

                            var cadena = "";

                            cadena = cadena + '<div class="input-group-prepend">';

                            cadena = cadena + '<span class="input-group-text"><i class="fas fa-user"></i></span>';

                            cadena = cadena + '<span class="input-group-text w-100">Error al Obtener Datos.</span>';

                            cadena = cadena + '</div>';

                            $('#htmlPersonal').html(cadena);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("enabled", "");

                            btnDelCentral.setAttribute("disabled", "");

                        } else {
                            $('#htmlPersonal').html(r);

                            var btnDelCentral = document.getElementById("guardarRecepcion");

                            btnDelCentral.removeAttribute("disabled", "");

                            btnDelCentral.setAttribute("enabled", "");

                        }

                    }
                });

            };


        });
    </script>
    <style>
        #personal,
        #jefes,
        #directivos {
            display: none;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini dark-mode" style="position: relative">



    <div class="wrapper">
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
                            <h4>Men&uacute; del Sistema Bodega Pañol</h4>
                            <h5>Administrar Herramientas</h5>
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
                            <div class="card card-primary">
                                <!-- /.card-header -->



                                <div class="card-header">
                                    <h3 class="card-title">Arriendo o Entrega</h3>
                                </div>

                                <!-- AQUI VAN LAS ALERTAS -->
                                <?php



                                ?>

                                <div class="card-body">

                                    <a href="panel-arriendo-retiro.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                    <br>
                                    <br>



                                    <fieldset class="border p-3">
                                        <legend class="w-auto">Escanear Código:</legend>

                                        <p>
                                        <div class="mx-auto" style="width: 200px;">
                                            <a class="btn btn-success " data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="fas fa-tools"></i> Herramienta
                                            </a>
                                        </div>
                                        <br>
                                        <div class="mx-auto" style="width: 200px;">
                                            <a class="btn btn-warning " data-toggle="collapse" href="#materiales" role="button" aria-expanded="false" aria-controls="materiales">
                                                <i class="fas fa-box"></i> Material
                                            </a>
                                        </div>

                                        </p>
                                        <br>

                                        <div class="tab-content" id="nav-tabContent">

                                            <div class="collapse" id="collapseExample">
                                                <form action="../sdo-funciones/validaExistencia.php" method="POST">
                                                    <div class="form-row">
                                                        <br>

                                                        <div class="form-group col-md-6">
                                                            <br>
                                                            <label for="codigoBarra">Código de Barra Herramienta:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:uppercase" class="form-control" id="codigoBarra3" name="codBarra" placeholder="Escanear Código de Barras">


                                                            </div>

                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <br>
                                                            <label for="codigoBarra">Código de Inventario Herramienta:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                                </div>
                                                                <input type="text" style="text-transform:uppercase" class="form-control" id="codigoBarra4" name="codInventario" placeholder="Escanear Código de Inventario">


                                                            </div>

                                                        </div>


                                                        <div class="mx-auto" style="width: 200px;">
                                                            <button class="btn btn-info">Validar Código</button>

                                                        </div>
                                                </form>

                                            </div>

                                        </div>

                                        <div class="collapse" id="materiales">
                                            <form action="../sdo-funciones/validaExistenciaMate.php" method="POST">
                                                <div class="form-row">
                                                    <br>

                                                    <div class="form-group col-md-6">
                                                        <br>
                                                        <label for="codigoBarra">Código de Barra Material:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:uppercase" class="form-control" id="codigoBarraMate" name="codBarraMate" placeholder="Escanear Código de Barras">


                                                        </div>

                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <br>
                                                        <label for="codigoBarra">Código de Inventario Material:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                            </div>
                                                            <input type="text" style="text-transform:uppercase" class="form-control" id="codigoInventarioMate" name="codInventarioMate" placeholder="Escanear Código de Inventario">


                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="mx-auto" style="width: 200px;">
                                                    <button class="btn btn-info">Validar Código</button>

                                                </div>
                                            </form>


                                        </div>

                                </div>




                                <?php


                                if (isset($_GET['eliminado'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'Herramienta Seleccionada Eliminada',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }

                                if (isset($_GET['eliminadoMate'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'Material Seleccionado Eliminado',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }

                                if (isset($_GET['ingresado'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'Si Existe  La Herramienta, se añadio al carrito',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }

                                if (isset($_GET['ingresadoMate'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'Si Existe  El Material, se añadio al carrito',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }


                                if (isset($_GET['repetido'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'info',
                                                    title: 'Esta herramienta ya se encuentra seleccionada',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }

                                if (isset($_GET['repetidoMate'])) {

                                    echo "<script>
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'info',
                                                    title: 'Este Material ya se encuentra seleccionado',
                                                    showConfirmButton: false,
                                                    timer: 2500
                                                  })
                                                </script>";
                                }

                                ?>


                                <?php

                                if (isset($_GET['noExiste'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'No existe la herramienta escaneada, por favor añadala al registro',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }


                                if (isset($_GET['vacios'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'no se pueden vacios',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }
                                if (isset($_GET['vaciosOpciones'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'debe seleccionar un personal',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }



                                if (isset($_GET['noExisteMate'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'No existe el material escaneado, por favor añadala al registro',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }

                                if (isset($_GET['exlusiones'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'No puede ingresar campos vacios',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }

                                if (isset($_GET['errorVacios'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'No puede ingresar campos vacios',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }

                                if (isset($_GET['exlusionesMaate'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'No puede ingresar campos vacios',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }

                                if (isset($_GET['sinStock'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'La herramienta Escaneada ya se encuentra en arriendo',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }
                                if (isset($_GET['noTiene'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'El Material escaneado no tiene stock disponible',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }
                                if (isset($_GET['entregado'])) {

                                    echo "<script>

                                                    Swal.fire({

                                                        position: 'center',
                                                        icon: 'error',
                                                        title: 'Esta herramienta fue entregada definitivamente',
                                                        showConfirmButton: false,
                                                        timer: 2500
                                                    })
                                                        
                                                    
                                                   

                                                    
                                                </script>";
                                }






                                ?>

                                </fieldset>



                                <fieldset class="border p-3">
                                    <legend class="w-auto">Herramientas Añadidas:</legend>



                                    <?php if (!empty($_SESSION['CARRITOHERRARETIRO'])) { ?>



                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col" class="text-center">N°</th>
                                                    <th scope="col" class="text-center">Imagen</th>
                                                    <th scope="col" class="text-center">Nombre</th>
                                                    <th scope="col" class="text-center">Código Barras</th>
                                                    <th scope="col" class="text-center">Código de Inventario</th>
                                                    <th scope="col" class="text-center">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <?php foreach ($_SESSION['CARRITOHERRARETIRO'] as $indice => $herramienta) {

                                                if (!isset($num)) {
                                                    $num = 0;
                                                } else {
                                                    $num = $num;
                                                }

                                                $num++;

                                            ?>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" class="text-center"><?php echo $num ?></th>
                                                        <td class="text-center"><img width="100px" height="100px" src="img3/<?php echo $herramienta['imagen'] ?>" alt="Imagen Herramienta"> </td>
                                                        <td class="text-center"><?php echo $herramienta['nomHerramienta'] ?></td>
                                                        <td class="text-center"><?php echo $herramienta['codHerramienta'] ?></td>
                                                        <td class="text-center"><?php echo $herramienta['codInventario'] ?></td>

                                                        <form action="../sdo-funciones/crud-arriendo-retiro/eliminar-herramienta.php" method="POST">


                                                            <input type="hidden" required name="id" id="id" value="<?php echo $herramienta['id'] ?>">


                                                            <td class="text-center"><button class="btn btn-danger" name="btnAccion" value="Eliminar" type="submit">Eliminar</button></td>

                                                        </form>

                                                    </tr>
                                                </tbody>


                                            <?php } ?>
                                        </table>


                                        </table>
                                    <?php } else { ?>

                                        <div class="alert alert-info">

                                            <strong>No ha añadido ninguna herramienta</strong>

                                        </div>

                                    <?php } ?>


                                </fieldset>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Materiales Añadidos:</legend>



                                    <?php if (!empty($_SESSION['CARRITOMATERETIRO'])) { ?>



                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col" class="text-center">N°</th>
                                                    <th scope="col" class="text-center">Imagen</th>
                                                    <th scope="col" class="text-center">Nombre</th>
                                                    <th scope="col" class="text-center">Código Barras</th>
                                                    <th scope="col" class="text-center">Stock Disponible</th>
                                                    <th scope="col" class="text-center">Cantidad</th>
                                                    <th scope="col" class="text-center">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <?php foreach ($_SESSION['CARRITOMATERETIRO'] as $indice => $material) {

                                                if (!isset($num)) {
                                                    $num = 0;
                                                } else {
                                                    $num = $num;
                                                }

                                                $num++;

                                            ?>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" class="text-center"><?php echo $num ?></th>
                                                        <td class="text-center"><img width="100px" height="100px" src="img/<?php echo $material['imagen'] ?>" alt="Imagen Herramienta"> </td>
                                                        <td class="text-center" style="text-transform: uppercase"><?php echo $material['nomMaterial'] ?></td>
                                                        <td class="text-center"><?php echo $material['codMaterial'] ?></td>
                                                        <td class="text-center"><?php echo $material['cantMaterial'] ?></td>
                                                        <td class="text-center"><input type="number" class="form-control" name="cantidad" form="envio" required onchange="cambiarCantidad(<?php echo $material['id'] ?>, this.value);"></td>

                                                        <form action="../sdo-funciones/crud-arriendo-retiro/eliminar-material.php" method="POST">


                                                            <input type="hidden" required name="id" id="id" value="<?php echo $material['id'] ?>">


                                                            <td class="text-center"><button class="btn btn-danger" name="btnAccion" value="Eliminar" type="submit">Eliminar</button></td>

                                                        </form>

                                                    </tr>
                                                </tbody>


                                            <?php } ?>
                                        </table>


                                        </table>
                                    <?php } else { ?>

                                        <div class="alert alert-info">

                                            <strong>No ha añadido ningun material</strong>

                                        </div>

                                    <?php } ?>


                                </fieldset>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Datos Solicitante</legend>
                                    <form action="../sdo-funciones/crud-arriendo-retiro/guardar-herramienta-material.php" id="envio" method="POST">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio1" value="personal">
                                            <label class="form-check-label" for="inlineRadio1">Personal Técnico</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio2" value="jefes">
                                            <label class="form-check-label" for="inlineRadio2">Jefes Ténicos</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones" id="inlineRadio3" value="directivos">
                                            <label class="form-check-label" for="inlineRadio3">Directivos</label>
                                        </div>
                                        <br>

                                        <div class="form-row fieldGroup" id="jefes">


                                            <div class="form-group col-md-3">

                                                <label for="departamentoSolicitante">Departamento: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <select name="departamentoSolicitante" id="departamentoSolicitante" class="form-control">
                                                        <option value="" disabled selected> -- Seleccione --</option>
                                                        <?php

                                                        $queryPersonal = "SELECT * FROM departamentos";
                                                        $res = mysqli_query($conexion, $queryPersonal);

                                                        while ($row = $res->fetch_assoc()) {
                                                            if ($row['idDepartamento'] == $idDepartamento) {
                                                        ?>



                                                                <option value="<?php echo   $row['idDepartamento']; ?>">
                                                                    <?php echo $row['nomDepartamento']; ?>

                                                                </option>


                                                            <?php
                                                            } else {

                                                            ?>
                                                                <option value="<?php echo $row['idDepartamento'] ?>">
                                                                    <?php echo $row['nomDepartamento'] ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        }


                                                        ?>
                                                    </select>
                                                    <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->

                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Personal:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <!-- <span class="input-group-text"><i class="fas fa-user"></i></span> -->
                                                    </div>
                                                    <div style="width: 100%;" id="htmlSelect" name="htmlSelect"></div>
                                                    <!-- <select name="personalSolicita" id="personal" name="personalSolicita" class="form-control">
                                                        <option value="" selected>-- Seleccione --</option>
                                                        <?php

                                                        /*$queryPersonal = "SELECT * FROM jefes_tecnicos";

                                                            $res = mysqli_query($conexion, $queryPersonal);

                                                            while($row = $res->fetch_assoc()){

                                                                ?>

                                                                    <option value="<?php echo $row['idJefe'];?>">
                                                                        <?php  echo $row['nomJefe'] . ' - ' .$row['cargoJefe']; ?>
                                                                        
                                                                    </option>
                                                                    

                                                                <?php 




                                                            }*/


                                                        ?>    
                                                    </select> -->

                                                    <!-- <input  readonly id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="N° de reserva"> -->



                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row fieldGroup" id="personal">


                                            <div class="form-group col-md-3">

                                                <label for="departamentoSolicitante">Departamento Personal Técnico: <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <select name="depaPersonal" id="depaPersonal" class="form-control">
                                                        <option value="" disabled selected> -- Seleccione --</option>
                                                        <?php

                                                        $queryPersonal = "SELECT * FROM departamentos";
                                                        $res = mysqli_query($conexion, $queryPersonal);

                                                        while ($row = $res->fetch_assoc()) {
                                                            if ($row['idDepartamento'] == $idDepartamento) {
                                                        ?>



                                                                <option value="<?php echo   $row['idDepartamento']; ?>">
                                                                    <?php echo $row['nomDepartamento']; ?>

                                                                </option>


                                                            <?php
                                                            } else {

                                                            ?>
                                                                <option value="<?php echo $row['idDepartamento'] ?>">
                                                                    <?php echo $row['nomDepartamento'] ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        }


                                                        ?>
                                                    </select>
                                                    <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->

                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Personal Técnico:<code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <!-- <span class="input-group-text"><i class="fas fa-user"></i></span> -->
                                                    </div>
                                                    <div style="width: 100%;" id="htmlPersonal" name="htmlPersonal"></div>
                                                    <!-- <select name="personalSolicita" id="personal" name="personalSolicita" class="form-control">
                                                        <option value="" selected>-- Seleccione --</option>
                                                        <?php

                                                        /*$queryPersonal = "SELECT * FROM jefes_tecnicos";

                                                            $res = mysqli_query($conexion, $queryPersonal);

                                                            while($row = $res->fetch_assoc()){

                                                                ?>

                                                                    <option value="<?php echo $row['idJefe'];?>">
                                                                        <?php  echo $row['nomJefe'] . ' - ' .$row['cargoJefe']; ?>
                                                                        
                                                                    </option>
                                                                    

                                                                <?php 




                                                            }*/


                                                        ?>    
                                                    </select> -->

                                                    <!-- <input  readonly id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="N° de reserva"> -->



                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row fieldGroup" id="directivos">


                                            <div class="form-group col-md-6">

                                                <label for="departamentoSolicitante">Directivo Solicitante : <code>*</code></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    </div>

                                                    <select name="directivo" id="directivo" class="form-control">
                                                        <option value="" disabled selected> -- Seleccione --</option>
                                                        <?php

                                                        $queryDirectivos = "SELECT * FROM directivos";
                                                        $resDirectivos = mysqli_query($conexion, $queryDirectivos);

                                                        while ($rowDirectivos = $resDirectivos->fetch_assoc()) {

                                                            $datos = [];

                                                            $datos['idDirectivo'] = $rowDirectivos['idDirectivo'];
                                                            $datos['nomDirectivo'] = $rowDirectivos['nomDirectivo'];

                                                            $idDirectivo = $datos['idDirectivo'];
                                                            $nomDirectivo = $datos['nomDirectivo'];

                                                        ?>

                                                            <option value="<?php echo $idDirectivo ?>">
                                                                <?php echo $nomDirectivo ?>
                                                            </option>

                                                        <?php
                                                        }


                                                        ?>
                                                    </select>
                                                    <!-- <input id="codigo" style="resize:none;" required name="codigo" class="form-control"  placeholder="Código Soli. Compra"> -->

                                                </div>
                                            </div>

                                        </div>


                                </fieldset>
                                <br>

                                <button type="submit"  class="btn btn-success btn-block">Guardar Solicitud</button>

                                </form>
                                <br>


                                <br>





                                <!-- /.card-body -->
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
        <strong>Copyright &copy; 2022. Derechos Reservados.
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
    <!-- Page specific script -->
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
    <script type="text/javascript">
        $("input[name='opciones']").change(function() {
            if ($(this).val() == 'personal') {
                $('#personal').show();
                $('#jefes').hide();
                $('#directivos').hide();


            } else if ($(this).val() == 'jefes') {
                $('#personal').hide();
                $('#jefes').show();
                $('#directivos').hide();

            } else {
                $('#personal').hide();
                $('#jefes').hide();
                $('#directivos').show();
            }
        })
    </script>
    <script type="text/javascript">
        function cambiarCantidad(id, cantidad) {

            var url = '../sdo-funciones/actualizar-cantidad-mate.php';
            var formData = new FormData();
            formData.append('id', id);
            formData.append('cantidad', cantidad);
            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(console.log('todo bien'));

        }

    </script>

</body>

</html>