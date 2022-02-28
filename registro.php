<?php
session_start();
include_once("./configs/config.php");
include_once("./configs/functions.php");

if($_SERVER['REQUEST_METHOD'] != "POST"){
    http_response_code(403);
	return;
}

$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$dni = $_POST["dni"];

if(is_exist_user($mysqli, $email, $dni)){
    echo json_encode(["status"=>0, "message"=>"El email ya está en uso"]);
}else{
    echo create_user($mysqli, $_POST, $password, $dni);
}
exit;
?>