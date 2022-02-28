<?php
$d = dirname(__DIR__, 2);
$fp = fopen("$d\.env", "r");
while(!feof($fp)){
    putenv(trim(fgets($fp)));
}
?>