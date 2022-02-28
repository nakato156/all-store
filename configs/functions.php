<?php
function is_exist_user($mysqli, $email, $dni){
    $sentencia = $mysqli->prepare("SELECT * FROM users WHERE email=? OR DNI=?");
    $sentencia->bind_param("ss",$email,$dni);
    $sentencia->execute();
    $data = $sentencia->get_result();
    if($data = $data->fetch_all()){
        return true;
    }
    return false;
}

function create_user($mysqli, array $arr, $pass, $dni){
    $names = $arr['nombres'];
    $apellidos = $arr['apellidos'];
    $email = $arr["email"];
    $phone = $arr["phone"];
    try {
        $mysqli->query("INSERT INTO `users` (`id`, `nombres`, `apellidos`, `email`, `phone`, `DNI`, `password`) VALUES (UUID(), '$names', '$apellidos', '$email', '$phone', '$dni', '$pass')");
        $_SESSION["user"] = $email;
        $mysqli->close();
        return json_encode(["status"=>1, "message"=>"Registrado exitosamente"]);
    } catch (Exception $th) {
        return json_encode(["status"=>0, "message"=>"->".$th]);
    }
}

function check_login($mysqli, string $email, string $pass){
    $sentencia = $mysqli->prepare("SELECT `password` FROM users WHERE email=?");
    $sentencia->bind_param("s", $email);
    $sentencia->execute();
    $data = $sentencia->get_result();
    if($BDpass = $data->fetch_all()){
        if(password_verify($pass, $BDpass[0][0])){
            return true;
        }
        return false;
    }
    return false;
}

function get_id($mysqli){
    $email = $_SESSION["user"];
    $result = $mysqli->query("SELECT id FROM users WHERE email='$email'");
    if(!$result){
        return false;
    }
    $id = $result->fetch_object()->id;
    return $id;
}

function get_comments($mysqli, string $id){
    $sentencia = $mysqli->prepare("SELECT `name`, `comentario` FROM comentarios WHERE id_product=? LIMIT 0,5");
    $sentencia->bind_param("s", $id);
    $data = $sentencia->execute();
    $comments = $sentencia->get_result();
    return $comments->fetch_all();
}

function get_name_from_email($mysqli){
    $email = $_SESSION["user"];
    $result = $mysqli->query("SELECT `nombres` FROM users WHERE email='$email'");
    if(!$result){
        return false;
    }
    $names = $result->fetch_object()->nombres;
    return $names;
}

function get_compra($id_compra){
    require_once("loadenv.php");
    $ch = curl_init();
    $token = getenv("TOKEN");
    $defaults = [
        CURLOPT_URL => "https://api.mercadopago.com/v1/payments/$id_compra",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $token"
        ),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FRESH_CONNECT => true
    ];
    curl_setopt_array($ch, $defaults);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}
?>