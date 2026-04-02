<?php

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

if (isset($_POST['idServicio'])) {

    $idServicio = trim($_POST['idServicio']);

    $cadena = '';

    if ($idServicio == "Otro" || $idServicio == "otro" || $idServicio == "OTRO") {

            $cadena .= '<div class="form-group col-md-6">';

            $cadena .= '<label for="exampleInputRounded0">Otra Unidad: <code>*</code></label>';

                $cadena .= '<div class="input-group mb-2">';

                    $cadena .= '<div class="input-group-append">';

                        $cadena .= '<div class="input-group-text">';

                            $cadena .= '<span class="fas fa-building"></span>';

                        $cadena .= '</div>';

                    $cadena .= '</div>';

                    $cadena .= '<input type="text" id="otraUnidadUsuariosT" style="text-transform:uppercase;" required name="otraUnidadUsuariosT" class="form-control" placeholder="Otra Unidad" value="">';

                $cadena .= '</div>';

            $cadena .= '</div>';




    }

    echo $cadena;
}
