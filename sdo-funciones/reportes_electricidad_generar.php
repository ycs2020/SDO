<?php

include_once 'conexion.php';

ini_set( 'upload_max_size' , '256M' );
ini_set( 'post_max_size', '256M');
ini_set( 'max_execution_time', '300' );
ini_set( 'max_file_uploads','5000');

if (isset($_POST['idUsuario'], $_POST['fechaActualReporte'], $_POST['nomEncargadoTurno'], $_POST['nomEncargadoTurnoAnterior'], $_POST['listaTurno'])) {

    $fechaActualReporte = $_POST['fechaActualReporte'];
    $nomEncargadoTurno = trim($_POST['nomEncargadoTurno']);
    $nomEncargadoTurnoAnterior = trim($_POST['nomEncargadoTurnoAnterior']);
    $idUsuario = trim($_POST['idUsuario']);

    $nomEncargadoTurno = ucwords(strtolower($nomEncargadoTurno));
    $nomEncargadoTurnoAnterior = ucwords(strtolower($nomEncargadoTurnoAnterior));

    if ($idUsuario == "" || $idUsuario == " " || empty($idUsuario) || $nomEncargadoTurno == "" || $nomEncargadoTurno == " " || empty($nomEncargadoTurno) || $nomEncargadoTurnoAnterior == "" || $nomEncargadoTurnoAnterior == " " || empty($nomEncargadoTurnoAnterior)) {
        header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar=true');
    } else {

        $listaTurno = trim($_POST['listaTurno']);

        if ($listaTurno == "" || $listaTurno == " " || empty($listaTurno)) {
            header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar=true');
        } else {

            if (isset($_POST['tareasDeteccionElectrica'])) {

                $tareasDeteccionElectrica = trim($_POST['tareasDeteccionElectrica']);
                $turno = "";

                if (!empty($tareasDeteccionElectrica)) {

                    $tareasDeteccionElectrica = str_replace("'", "\'", $tareasDeteccionElectrica);
                }
                $tareasDeteccionElectrica = ucfirst($tareasDeteccionElectrica);


                if ($listaTurno == "Dia") {
                    $turno = "D";
                } else if ($listaTurno == "Noche") {
                    $turno = "N";
                } else {
                    header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar=true');
                }

                date_default_timezone_set('America/Santiago');
                $now = date('Y-m-d H:i:s', time());

                $query = "INSERT INTO reportes_electricidad(idReporte,nomReporte,turnoReporte,fechaReporte,folioReporte,nomEncargadoTurno,nomFuncionariosTurno,nomEncargadoTurnoAnterior,tareasDeteccionElectrica)
                VALUES (NULL,'Reporte de Eventos - Electricidad - " . $listaTurno . " - " . $fechaActualReporte . "','" . $listaTurno . "','".$now."','','" . $nomEncargadoTurno . "','" . $nomFuncionariosTurno . "','" . $nomEncargadoTurnoAnterior . "','" . $tareasDeteccionElectrica . "')";

                $res = mysqli_query($conexion, $query);

                if ($res) {

                    $idReporte = mysqli_insert_id($conexion);

                    $folioReporte = "HBQP-REP-E-" . $turno . "-" . $idReporte;

                    $queryFolio = "UPDATE reportes_electricidad SET folioReporte = '" . $folioReporte . "' WHERE idReporte =" . $idReporte;

                    $resfolio = mysqli_query($conexion, $queryFolio);

                    if (isset($_FILES["archivoImagen"])) {
                        $tot = count($_FILES["archivoImagen"]["name"]);

                        echo '<br><br>';

                        echo $tot;
                        for ($i = 0; $i < $tot; $i++) {
                            $tmp_name = $_FILES["archivoImagen"]["tmp_name"][$i];
                            $name = $_FILES["archivoImagen"]["name"][$i];

                            if ($tmp_name == '' || $tmp_name == ' ' || empty($tmp_name)) {
                                header('Location: ../sdo-personal/electricidad_reportes_turno.php?reporteSinFoto=true');
                            } else {

                                $directorio = "../sdo-archivos/R-E-" . $idReporte . "/";

                                chown($directorio, "adminSDO");

                                if (!file_exists($directorio)) {
                                    mkdir($directorio, 0777);
                                }



                                $dir = opendir($directorio);

                                $ruta = $directorio . $name;

                                $separarNombre = explode(".", $name);

                                $extImagen = end($separarNombre);

                                $extImagen = mb_strtolower($extImagen);


                                if ($extImagen == "jpg" || $extImagen == "png" || $extImagen == "jpeg" || $extImagen == "bmp" || $extImagen == "tif" || $extImagen == "webp" || $extImagen == "svg" || $extImagen == "jfif") {

                                    // if (is_dir($directorio) && is_writable($directorio)) {
                                    //     echo 'hola';
                                    // } else {
                                    //     echo 'Upload directory is not writable, or does not exist.';
                                    // }

                                    if (move_uploaded_file($tmp_name, $ruta)) {

                                        $queryImagen = "INSERT INTO imagenes_rep_electricidad(idImagen,nomImagen,extImagen,rutaImagen,idReporte,idOrden) VALUES (NULL,'" . $name . "','" . $extImagen . "','" . $ruta . "','" . $idReporte . "',0)";

                                        $resImagen = mysqli_query($conexion, $queryImagen);


                                        chown($ruta, "adminSDO");

                                        if ($resImagen) {
                                            header('Location: ../sdo-personal/electricidad_reportes_turno.php?reporteConFoto=true');
                                        } else {
                                            header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorCargaFoto=true');
                                        }
                                    } else {
                                        header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorCargaFoto=true');
                                    }
                                } else {

                                    header('Location: ../sdo-personal/electricidad_reportes_turno.php?formatoIncorrectoFoto=true');
                                }
                            }
                        }
                    } else {
                        header('Location: ../sdo-personal/electricidad_reportes_turno.php?reporteSinFoto=true');
                    }
                } else {
                    header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar1=true');
                }
            } else {

                header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar2=true');
            }
        }
    }
} else {

    header('Location: ../sdo-personal/electricidad_reportes_turno.php?errorGenerar3=true');
}
