
<?php
//fetch.php


include '../sdo-funciones/conexion.php';

$column = array("product.id", "product.name", "category.category_name", "product.price");
$query = "
 SELECT * FROM product 
 INNER JOIN category 
 ON category.category_id = product.category 
";
$query .= " WHERE ";
if (isset($_POST["is_category"])) {
    $query .= "product.category = '" . $_POST["is_category"] . "' AND ";
}
if (isset($_POST["search"]["value"])) {
    $query .= '(product.id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR category.category_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.price LIKE "%' . $_POST["search"]["value"] . '%") ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY product.id DESC ';
}

$query1 = '';

if ($_POST["length"] != 1) {
    $query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($conexion, $query));

$result = mysqli_query($conexion, $query . $query1);

$data = array();

while ($row = mysqli_fetch_array($result)) {
    $sub_array = array();
    $sub_array[] = $row["id"];
    $sub_array[] = $row["name"];
    $sub_array[] = $row["category_name"];
    $sub_array[] = $row["price"];
    $data[] = $sub_array;
}

function get_all_data($conexion)
{
    $query = "SELECT * FROM product";
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
