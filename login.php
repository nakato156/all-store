<?php
session_start();
include_once("./configs/config.php");
include_once("./configs/functions.php");

if($_SERVER['REQUEST_METHOD'] != "POST"){
    http_response_code(403);
	return;
}

$email = $_POST["email"];
if(check_login($mysqli, $email, $_POST["password"])){
    $_SESSION["user"] = $email;
    echo json_encode(["status"=>1]);
    return;
}else{
    echo json_encode(["status"=>0, "message"=>"Datos incorrectos"]);
}
exit;
?>