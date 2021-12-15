<?php
include("user.php");

$myUser = new User(
    "toto13",
    "tetatoto",
    "totodu13@gmail.com",
    "Toto",
    "Dubois"
);

$myUser->register(
    $myUser->login,
    $myUser->password,
    $myUser->email,
    $myUser->firstname,
    $myUser->lastname
);

$myUser->connect(
    $myUser->login,
    $myUser->password
);

var_dump($myUser->getAllInfos());
?>