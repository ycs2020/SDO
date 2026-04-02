<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

// var_dump($res);

// exit;


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $idHerramienta = $_POST['idHerramienta'];

    if(!empty($idHerramienta)){

        $query = "SELECT * FROM pañol_herramientas WHERE idHerramienta = ${idHerramienta}";
        $res = mysqli_query($conexion, $query);
        $row = $res->fetch_assoc();
        

        if($row){

            $datos = array();
            $datos['codHerramienta'] = $row['codHerramienta'];
            $datos['codInventario'] = $row['codInventario'];
            $datos['numSerie'] = $row['numSerie'];
            $datos['nomHerramienta'] = $row['nomHerramienta'];
            //$datos['idCategoria'] = $row['idCategoria'];
            $datos['modeloHerramienta'] = $row['modeloHerramienta'];
            $datos['cantHerramienta'] = $row['cantHerramienta'];
            $datos['marcaHerramienta'] = $row['marcaHerramienta'];
            $datos['fechaIngreso'] = $row['fechaIngreso'];
            $datos['idDepartamento'] = $row['idDepartamento'];

            //$datos['fechaVencimiento'] = $row['fechaVencimiento'];
            $datos['descriptHerramienta'] = $row['descriptHerramienta'];
            $datos['imagen'] = $row['imagen'];
            $datos['sector'] = $row['sector'];
            

        }

        $codHerramienta = $datos['codHerramienta'];
        $codInventario = $datos['codInventario'];
        $numSerie = $datos['numSerie'];
        $nomHerramienta = $datos['nomHerramienta'];
        //$idCategoria = $datos['idCategoria'];
        $modeloHerramienta = $datos['modeloHerramienta'];
        $cantHerramienta = $datos['cantHerramienta'];
        $marcaHerramienta = $datos['marcaHerramienta'];
        $fechaIngreso = $datos['fechaIngreso'];
        $idDepartamento = $datos['idDepartamento'];

        //$fechaVencimientoHerramienta = $datos['fechaVencimiento'];
        $descripHerramienta = $datos['descriptHerramienta'];
        $imagen = $datos['imagen'];
        $sector = $datos['sector'];
        //$idHerramienta = $datos['idHerramienta'];

        //var_dump($query);

    

    }

}else{

    echo "El envio del formulario no se realizo mediante POST";

    exit;
}


// // ASIGNAR ID
// include '../sdo-funciones/conexion.php';
// include '../sdo-funciones/funciones.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Herramienta</title>

    <link rel="icon" href="../dist/img/ss-vina-quillota.png" type="image/icon type">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

    <script src="../sdo-canvasdraw1/jquery.min.js"></script>
    <script src="../sdo-canvasdraw1/signature_pad.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            
            recargarNomCategoria();
       


            $('#categoriaOrden').change(function() {
                recargarNomCategoria();
            });


            function recargarNomCategoria() {

                $.ajax({
                    type: "POST",
                    url: "sdo-datos-categorias/habilitarModificarCategoriaHerra.php",
                    data: "idCategoria=" + $('#categoriaOrden').val(),
                    success: function(r) {
                        $('#visualizarNomCategoria').html(r);


                    }
                });

            };

        });
    </script>
</head>

<body class="hold-transition sidebar-mini dark-mode">
    
    <div class="wrapper">
        
        <?php
        include '../sdo-templates/directiva-sidebar.php';
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Modificar Herramienta</h1>
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
                  
                        <br>
                   <form action="../sdo-funciones/herramienta-modificar.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">

                                <!-- general form elements -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Informaci&oacute;n General de la Herramienta:</h3> 
                                    </div>                                     <!-- /.card-header -->
                                    <?php


                            //        if (isset($_GET['exitoingresoherramienta'])) {
                             //           echo '<div class="success alert-success alert-dismissible">
                             //           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                             //           <h5><i class="fa fa-check"></i>&nbsp;Ingreso de la Herramienta Completado</h5></div>';
                             //       }


                                    ?>
                                    <div class="card-body">

                                        <a href="listado-herramientas.php" style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Volver Atras</a>
                                        <br>
                                        <br>

                                        <fieldset class="border p-3">
                                            <legend class="w-auto">Informaci&oacute;n General de la Herramienta:</legend>
                                            <div class="form-row"> 
                                                <div class="form-group col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                         <!--   <span class="input-group-text"><i class="fas fa-barcode"></i></span> -->
                                                        </div>
                                                      <!--  <button class="btn btn-primary" name="buscar">Buscar</button> -->
                                                   
                                                        

                                                      <!--   <style="float: right;" class="btn btn-info"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Buscar Codigo Barra</style=>-->

                                                    </div>
                                                </div>
                                            </div>
                                              <!--  <?php
                                            
                                    //               $query = 'SELECT * FROM pañol_herramientas WHERE codHerramienta = ;';

                                                 //   $res = mysqli_query($conexion, $query);
                                                //?> -->
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Codigo de Barras:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase; cursor:not-allowed" class="form-control" id="codHerramienta" name="codHerramienta" value="<?php echo $codHerramienta?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Codigo de Inventario:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase;" class="form-control" id="codInventario" name="codInventario" value="<?php echo $codInventario?>" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputNomTarjeta">Número Serie:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase;" class="form-control" id="numSerie" name="numSerie" value="<?php echo $numSerie?>" >
                                                    </div>
                                                </div>

                                                <input type="hidden" value="<?php echo $cantHerramienta?>" name="cantidadHerramienta">

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Fecha Ingreso:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input disabled  type="text" required class="form-control" style="cursor:not-allowed"  id="fechaHerramienta" name="fechaHerramienta" value="<?php echo $fechaIngreso?>" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                        <label for="departamento">Imagen:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-file-image"></i></span>
                                                            </div>
                                                            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/png, image/jpeg">
                                                        </div>
                                                        <p style="color: red;font-weight:bold">**Solo subir una imagen, si desea cambiar la actual</p>
                                                        <a class="btn btn-info" target="_blank" href="../sdo-bodega-panol/img3/<?php echo $imagen ?>" ><i class="fas fa-eye"> Visualizar Imagen Actual</i></a>
                                                </div>

                                                <div class="form-group col-md-6">
                                                        <label for="departamento">Sector:<code>*</code></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                                            </div>
                                                            <input type="text" required name="sector" id="sector" class="form-control" value="<?php echo $sector ?>" placeholder="ESCRIBA SECTOR EJ: A">
                                                        </div>
                                                    </div>

                                                
                                                <div class="form-group col-md-6">
                                                    
                                                    <label for="exampleInputNomTarjeta">Nombre Descriptivo:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                        </div>
                                                        <input type="text" required style="text-transform:uppercase" class="form-control" id="nomHerramienta" name="nomHerramienta" value="<?php echo $nomHerramienta?>" >
                                                    </div>
                                                </div>
                            
                                                


                                                
                                              
                                            </div>
                                            <div class="form-row">

                                            <div class="form-group col-md-6">


                                                    <?php 
                                                    
                                                        if($idDepartamento == 10){

                                                            $nomDepa = "Todos";
                                                        }
                                                    
                                                    
                                                    ?>

                                                    
                                                    <label for="exampleInputNomTarjeta">Departamento Actual:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                        </div>
                                                        <input type="text" disabled required style="text-transform:uppercase; cursor:not-allowed;" class="form-control" id="depaActual" name="depaActual" value="<?php echo $nomDepa?>" >
                                                        <input type="hidden"   name="idDepaActual" value="<?php echo $idDepartamento?>" >
                                                         
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    
                                                    <label for="exampleInputNomTarjeta">Nuevo Departamento:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                        </div>
                                                        <select name="nuevoDepa" id="nuevoDepa" class="form-control">

                                                            <option disabled selected value="">-- Seleccione --</option>

                                                            <?php 
                                                            
                                                                $sql = "SELECT * FROM departamentos";
                                                                $res = mysqli_query($conexion, $sql);

                                                                while($row = $res->fetch_assoc()){

                                                                    $datos = [];

                                                                    $datos['nomDepartamento'] = $row['nomDepartamento'];
                                                                    $datos['idDepa'] = $row['idDepartamento'];


                                                                    $nomDepartamento = $datos['nomDepartamento'];
                                                                    $idDepa = $datos['idDepa'];

                                                                    ?>
                                                                    <option value="<?php echo $idDepa ?>">
                                                                        <?php echo $nomDepartamento ?>
                                                                    </option>
                                                                <?php

                                                                }
                                                                

                                                                
                                                               
                                                            ?>

                                                            <option value="10">Todos</option>


                                                        </select>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-row">
                                                <!-- <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Cantidad:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                        </div>
                                                        <input type="number" required class="form-control" style="text-transform:uppercase" id="cantidad" name="cantidadHerramienta" value="<?php echo $cantHerramienta?>" placeholder="Cantidad" >
                                                    </div>
                                                </div> -->
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Marca:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" style="text-transform:uppercase" id="marcaHerramienta" name="marcaHerramienta" value="<?php echo $marcaHerramienta?>" >
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputCodigoTarjeta">Modelo:<code></code></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                                        </div>
                                                        <input type="text" required class="form-control" style="text-transform:uppercase" id="modeloHerramienta" name="modeloHerramienta" value="<?php echo $modeloHerramienta?>" >
                                                    </div>
                                                </div>
                                            
                                            </div>
                                          
                                  
                            
                                            </fieldset>

                                                <br>
                                                <fieldset class="border p-3">
                                                    <legend class="w-auto">Información Especifica del producto:</legend>
                                                    <textarea rows="04" cols="10" rows="10" class="form-control" id="descripHerramienta" name="descripHerramienta" required aria-label="With textarea" ><?php echo $descripHerramienta ?></textarea>
                                                

                                                </fieldset>


                                      
                                           

                                    </div>
                                     
                                   
                                       

                                        <br>
                                        <br>


                                        <div class="form-group">
                                            <input type="hidden" name="idTecnico" id="idTecnico" value="<?php echo $idUsuario; ?>">
                                            <input type="hidden" id="nomTecnicoTurno" name="nomTecnicoTurno" value="<?php echo $nomUsuario; ?>" />
                                            <input type="hidden" id="idHerramienta" name="idHerramienta" value="<?php echo $idHerramienta?>">
                                            <input type="submit" class="btn btn-block" value="Guardar Modificación" style="background-color:yellowgreen ; border-color:green; font-weight:bold; color: white;" >
                                        </div>
                                        <div class="form-group">
                                            <a href="../sdo-bodega-panol/listado-herramientas.php" class="btn btn-block btn-info" style="color: white;font-weight: bold;"> Volver </a>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!--/.col  -->
                        </div>
                    </form>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script src="js/funciones.js"></script>
    <script src="js/sumar.js"></script>
</body>

</html>





