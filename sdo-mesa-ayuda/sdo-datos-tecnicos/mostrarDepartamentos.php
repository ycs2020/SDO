<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';



if (isset($_POST['tipo']) == 'personal') {

    $tipo = trim($_POST['tipo']);

  
    $cadena = '';
    $id = '';
    $nombre = '';
    $cargo = '';

    if (!empty($_POST['tipo'])) {

        // $contarPersonal = contarJefe($idDepartamento);

            if($tipo == 'personal'){

                $id = 'idPersonal';
                $nombre = 'nomPersonal';

                $cargo = 'cargoPersonal';


            }
            if($tipo == 'jefes_tecnicos'){

                $id = 'idJefe';
                $nombre = 'nomJefe';

                $cargo = 'cargoJefe';


            }
            if($tipo == 'directivos'){

                $id = 'idDirectivo';
                $nombre = 'nomDirectivo';
                $cargo = '';

               


            }

        

            if($tipo == 'directivos'){
                $queryPersonal = "SELECT " . $id . "," . $nombre .  "  FROM " . $tipo;
            }else{
                $queryPersonal = "SELECT " . $id . "," . $nombre . "," .  $cargo  . "  FROM " . $tipo;
                
            }

            $resPersonal = mysqli_query($conexion, $queryPersonal);


            $cadena = $cadena . '<div class="input-group-prepend">';

            $cadena = $cadena . '<span class="input-group-text"><i class="fas fa-user"></i></span>';

            $cadena = $cadena . '<select id="solicitantePersonal" class="form-control" onchange="cambiar()" name="solicitantePersonal">';

           

            if($tipo == 'directivos'){
                while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                    $cadena = $cadena . '<option id="idFuncionario" value="' . $rowPersonal[0] . '">' . $rowPersonal[1] .   '</option>';
                }
    
            }else{
                while ($rowPersonal = mysqli_fetch_array($resPersonal)) {
                    $cadena = $cadena . '<option id="idFuncionario" value="' . $rowPersonal[0] . '">' . $rowPersonal[1] .  '' . "  -  " . '' . $rowPersonal[2] . '</option>';
                }
    
            }

         

            $cadena = $cadena . '</select>';

            $cadena = $cadena . '</div>';
         
    } else {
        $cadena = $cadena . 'Error';
    }



    echo $cadena;
}
