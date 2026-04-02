
<?php
//fetch.php
include '../sdo-funciones/conexion.php';
$column = array("equipos.idEquipo", "equipos.nomEquipo", "subcategorias.nomSubcategoria");
$query = "
 SELECT * FROM equipos 
 INNER JOIN subcategorias 
 ON subcategorias.idSubcategoria = equipos.idSubCategoria 
";
$query .= " WHERE ";
if(isset($_POST["idSubCategoria"]))
{
 $query .= "equipos.idSubCategoria = '".$_POST["idSubCategoria"]."' AND ";
}
if(isset($_POST["search"]["value"]))
{
 $query .= 'OR equipos.nomEquipo LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR subcategorias.nomSubcategoria LIKE "%'.$_POST["search"]["value"].'%" ';
}


$number_filter_row = mysqli_num_rows(mysqli_query($conexion, $query));

$result = mysqli_query($conexion, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["nomEquipo"];
 $sub_array[] = $row["nomSubcategoria"];
 $sub_array[] = $row["forzadoEquipo"];
 $data[] = $sub_array;
}

function get_all_data($conexion)
{
 $query = "SELECT * FROM equipos";
 $result = mysqli_query($conexion, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($conexion),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>
