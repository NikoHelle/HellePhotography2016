<?php

session_start();
$_SESSION["time"] = microtime();
if(!isset($_SESSION["v2"])){
    $_SESSION["v1"] = false;
    $_SESSION["v2"] = md5(microtime()."c".rand());
}

?>

