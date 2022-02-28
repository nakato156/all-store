<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if(!(isset($_SESSION["shopcar"]))){
        $_SESSION["shopcar"][] = array(
            "name"=> $data["name"],
            "cant"=> 1,
            "price"=> get_price($data["id"])
            
        );
        echo json_encode(array(
            "status"=>"ok"
        ));
        return;
    }
    $carrito = $_SESSION["shopcar"];
    
    for($i=0; $i<count($carrito); $i++){
        if(($data["name"] == $carrito[$i]["name"])){
                $_SESSION["shopcar"][$i]["cant"]+=1;
        }else{
            $_SESSION["shopcar"][$i]["cant"] = 1;
            $_SESSION["shopcar"][$i]["price"] = get_price($data["id"]);
        }
    }

    $cant = 0;
    if(isset($_SESSION["shopcar"])){
        foreach($_SESSION["shopcar"] as $pd){
            $cant+= $pd["cant"];
        }
    }
    echo json_encode(array(
        "status"=>"ok",
        "cant" => $cant
    ));
    return;
}
function get_price($id){
    require_once("../configs/config.php");

    $sentencia = $mysqli->prepare("SELECT `price` FROM products WHERE id=?");
    $sentencia->bind_param("i", $id);
    $sentencia->execute();
    $data = $sentencia->get_result();
    return $data->fetch_all()[0][0];
}
?>