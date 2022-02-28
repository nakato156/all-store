<?php
session_start();
require_once("../configs/loadenv.php");
require dirname(__DIR__) .  '/vendor/autoload.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    switch($_POST["method"]){
        case "id":
            $var = isset($_SESSION["shopcar"]) && count($_SESSION["shopcar"])!=0 ? true : null;
            echo json_encode(["value"=>$var]);
            break; 
        
        case "payment":
            $TOKEN = getenv("TOKEN");
            $URL = getenv("URL");

            MercadoPago\SDK::setAccessToken($TOKEN);
            $preference = new MercadoPago\Preference();
            
            $productosCompra = array();
            $car = $_SESSION["shopcar"];
        
            for($i=0; $i<count($_SESSION["shopcar"]); $i++){
                // Crea un ítem en la preferencia
                $item = new MercadoPago\Item();
                $item->title = $car[$i]["name"];
                $item->quantity = $car[$i]["cant"];
                $item->unit_price = $car[$i]["price"];
                $item->currency_id = "PEN";
                $productosCompra[] = $item;
            }
            $preference->back_urls = array(
                "success" => "$URL/success",
                "failure" => "$URL/failure",
                "pending" => "$URL/pending"
            );
            $preference->payment_methods = array(
                "excluded_payment_types" => array(
                    array("id" => "atm")
                ),
                "installments" => 4
            );
            $preference->binary_mode = true;
            $preference->items = array($item);
            
            $nombre = $_POST["nombre"];
            $direccion = $_POST["direccion"];
            $ciudad = $_POST["ciudad"];
            $email = $_POST["email"];
            $userID = isset($_SESSION["user"])? $_SESSION["user"] : "none";

            $payer = new MercadoPago\Payer();
            $payer->name = $nombre;
            $payer->surname = "Luevano";
            $payer->email = $email;
            $payer->phone = array(
                "area_code" => "51",
                "number" => "924 242 707"
            );
            $payer->address = array(
                "street_name" => "userID:$userID\nemail:$email\nnombre:$nombre\ndireccion:$direccion\nciudad:$ciudad"
            );

            $preference->payer = $payer;     
            $preference->save();   
            echo json_encode(["id"=>$preference->id]);
            break;
    }
    return;
}
?>