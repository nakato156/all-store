<?php
session_start();
include_once("./configs/config.php");
include_once("./configs/functions.php");

if(!(isset($_SESSION["user"]))){
    die("");
}
$not = json_encode(["status"=>0]);

if($_POST["op"]=="get"){
    $email = $_SESSION['user'];
    $result = $mysqli->query("SELECT id FROM users WHERE email='$email'");
    if(!$result){
        echo json_encode(["status"=>0]);
        return;
    }
    $id = $result->fetch_object()->id;
    $sentencia = $mysqli->prepare("SELECT fecha, productos, pago FROM compras WHERE `user_id`=?");
    $sentencia->bind_param("s", $id);
    $sentencia->execute();
    $data = $sentencia->get_result();
    if($compras = $data->fetch_all()){
        echo json_encode($compras);
        return;
    }
    echo $not;
    return;
}else if($_POST["op"]=="tracking"){
    if($id = get_id($mysqli)){
        $status = 0;
        $sentencia = $mysqli->prepare("SELECT `fecha`, `status` FROM compras WHERE `user_id`=? AND `status`=?");
        $sentencia->bind_param("si", $id, $status);
        $sentencia->execute();
        $data = $sentencia->get_result();
        if($compras = $data->fetch_all()){
            echo json_encode($compras);
            return;
        }
        echo $not;
        return;
    }
    echo $not;
    return;
}
?>