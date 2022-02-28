<?php
include_once("./configs/config.php");

$sentencia = $mysqli->prepare("SELECT * FROM products");
try {
    $sentencia->execute();
    $data = $sentencia->get_result();
    $products = $data->fetch_all();
    echo json_encode($products);
} catch (\Throwable $th) {
    echo("<h1>Error</h1>");
}
return;
?>