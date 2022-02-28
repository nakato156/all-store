<?php 
namespace allstore\index;
session_start();

require_once("./routes/routes.php");
require_once("./configs/loadenv.php");
include_once("./configs/config.php");

use routes\Router;

$router = new Router();
$router->path_render = "./templates";
$router->header = "header.php";

// definiendo rutas
$router->post("/notify", [function ($post){
    include_once("./configs/functions.php");
    global $mysqli;

    $data = $post["data"]["id"];
    $data_shop = get_compra($data);

    $info = str_replace(["\r\n", "\n", "\r"], '\n', $data_shop['additional_info']['payer']['address']['street_name']);
    $split_info = explode('\n', $info);

    $infoPay = array("pago"=>$data_shop['transaction_details']['total_paid_amount']);
    foreach($split_info as $inf){
        $sep = explode(':', $inf);
        $infoPay[trim($sep[0])] = $sep[1];
    }
    $products = array();
    foreach($data_shop['additional_info']['items'] as $item){
        $products[] = $item["title"];
    }
    $products = implode(",", $products);

    $sentencia = $mysqli->prepare("INSERT INTO `compras` (`user_id`, `email`, `productos`, `ciudad`, `direccion`, `pago`) VALUES (?, ?, ?, ?, ?, ?)");
    $sentencia->bind_param("sssssi", $infoPay['userID'], $infoPay['email'], $products, $infoPay['ciudad'], $infoPay['direccion'], $infoPay['pago']);
    $sentencia->execute();
    
    http_response_code(200);
}]);
$router->view('/', "home.html");
$router->get("/:id/:name", true, [function($id, $name){
    global $mysqli;
    $sentencia = $mysqli->prepare("SELECT * FROM products WHERE id=?");
    $sentencia->bind_param("i", $id);
    $sentencia->execute();
    $data = $sentencia->get_result();
    $product = $data->fetch_all()[0];

    $name = $product[1];
    $description = $product[2];
    $price = $product[3];
    $img = $product[4];

    include_once("./templates/product.php");

}]);
$router->view("/login", "login.html", ["middleware"=>"not_session"]);
$router->view("/registro", "registro.html", ["middleware"=>"not_session"]);
$router->view("/perfil", "perfil.html", ["middleware"=>"use_session"]);
$router->view("/pay", "payment.php");
$router->get("/terminos-y-condiciones", false, [function(){
    include_once("./templates/terminos.html");
}]);
$router->post("/comment", [function($post){
    global $mysqli;
    $name = "Desconocido";
    if(isset($_SESSION["user"])){
        include_once("./configs/functions.php");
        $name = get_name_from_email($mysqli);
    }
    $sentencia = $mysqli->prepare("INSERT INTO comentarios (`name`,`comentario`, `id_product`) VALUES (?, ?, ?)");
    $sentencia->bind_param("sss", $name, $post["comment"], $post["id_product"]);
    $sentencia->execute();
    echo json_encode(["name"=>$name]);
    return;
}]);
$router->post("/get-comments", [function($post){
    global $mysqli;
    include_once("./configs/functions.php");
    $id = $post["id"];
    $result_comment = get_comments($mysqli, $id);
    if($result_comment){
        echo json_encode($result_comment);
    }else{
        echo json_encode(["data"=>false]);
    }
    return;
}]);
$router->post("/cant", [function($post){
    $cant = 0;
    if(isset($_SESSION["shopcar"])){
        foreach($_SESSION["shopcar"] as $pd){
            $cant+= $pd["cant"];
        }
    }
    echo json_encode(["cant"=>$cant]);
}]);
$router->error_404(__DIR__);
?>