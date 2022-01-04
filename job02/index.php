<?php
include("user-pdo.php");

$myUser = new UserPDO();

$myUser->register("tati", "tatatati", "tati@gmail.com", "tati", "tata");
$myUser->connect("tati", "tatatati");
var_dump($myUser->getAllInfos());
?>