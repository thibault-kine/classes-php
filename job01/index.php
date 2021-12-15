<?php
include("user.php");

$myUser = new User(
    "thibault-kine",
    "test",
    "thibault.kine@laplateforme.io",
    "Thibault",
    "Kine"
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
?>